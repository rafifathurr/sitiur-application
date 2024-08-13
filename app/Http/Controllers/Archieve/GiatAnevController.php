<?php

namespace App\Http\Controllers\Archieve;

use App\Http\Controllers\Controller;
use App\Models\Archieve\GiatAnev;
use App\Models\Master\Classification;
use App\Models\Master\Institution;
use App\Models\Master\TypeMailContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GiatAnevController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = GiatAnev::select(DB::raw('YEAR(date) as year'))->whereNull('deleted_by')->whereNull('deleted_at')->groupBy(DB::raw('YEAR(date)'))->orderBy(DB::raw('YEAR(date)'), 'DESC')->get()->toArray();
        $data['years'] = !empty($years) ? $years : [['year' => date('Y')]];
        $data['dt_route'] = route('archieve.giat-anev.dataTable'); // Route DataTables
        return view('archieve.giat_anev.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['levels'] = Institution::getLevel();
        return view('archieve.giat_anev.create', $data);
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable(Request $request)
    {
        $giat_anevs = GiatAnev::with(['institution'])
            ->whereYear('date', $request->year)
            ->whereNull('deleted_by')
            ->whereNull('deleted_at')
            ->orderBy('date', 'ASC')
            ->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($giat_anevs)
            ->addIndexColumn()
            ->addColumn('date', function ($data) {
                return date('d F Y', strtotime($data->date));
            })
            ->addColumn('institution', function ($data) {
                return !is_null($data->institution_id) ? $data->institution->name : 'Eksternal';
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('archieve.giat-anev.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5 ml-2 mb-1" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('archieve.giat-anev.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                $btn_action .= '<a target="_blank" href="' . asset($data->attachment) . '" class="btn btn-sm btn-info rounded-5 ml-2 mb-1" title="Lampiran Dokumen"><i class="fas fa-paperclip"></i></a>';
                return $btn_action;
            })
            ->only(['number_giat', 'date', 'institution', 'name', 'action'])
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Request Validation
            $request->validate([
                // 'number_giat' => 'required',
                'name' => 'required',
                'date' => 'required',
                // 'attachment' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Giat Anev Diseminasi
            $giat_anev = GiatAnev::lockforUpdate()->create([
                // 'number_giat' => $request->number_giat,
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($giat_anev) {
                // Image Path
                $path = 'public/archieve/giat-anev';
                $path_store = 'storage/archieve/giat-anev';

                // Check Exsisting Path
                if (!Storage::exists($path)) {
                    // Create new Path Directory
                    Storage::makeDirectory($path);
                }

                if (!empty($request->allFiles())) {
                    // $attachment_collection = [];

                    // foreach ($request->file('attachment') as $index => $attachment) {
                    //     // File Upload Configuration
                    //     $exploded_name = explode(' ', strtolower($request->name));
                    //     $file_name_config = implode('_', $exploded_name);
                    //     $file_name = $giat_anev->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

                    //     // Uploading File
                    //     $attachment->storePubliclyAs($path, $file_name);

                    //     // Check Upload Success
                    //     if (Storage::exists($path . '/' . $file_name)) {
                    //         array_push($attachment_collection, $path_store . '/' . $file_name);
                    //     } else {
                    //         // Failed and Rollback
                    //         DB::rollBack();
                    //         return redirect()
                    //             ->back()
                    //             ->with(['failed' => 'Gagal Upload Lampiran Giat Anev Diseminasi'])
                    //             ->withInput();
                    //     }
                    // }

                    // // Update Record for Attachment
                    // $giat_anev_update = GiatAnev::where('id', $giat_anev->id)->update([
                    //     'attachment' => $attachment_collection,
                    // ]);

                    // // Validation Update Attachment Giat Anev Record
                    // if ($giat_anev_update) {
                    //     DB::commit();
                    //     return redirect()
                    //         ->route('archieve.giat-anev.show', ['id' => $giat_anev->id])
                    //         ->with(['success' => 'Berhasil Menambahkan Giat Anev Diseminasi']);
                    // } else {
                    //     // Failed and Rollback
                    //     DB::rollBack();
                    //     return redirect()
                    //         ->back()
                    //         ->with(['failed' => 'Gagal Update Lampiran Giat Anev Diseminasi'])
                    //         ->withInput();
                    // }

                    $exploded_name = explode(' ', strtolower($request->name));
                    $file_name_config = implode('_', $exploded_name);
                    $file = $request->file('attachment');
                    $file_name = $giat_anev->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $giat_anev_update = GiatAnev::where('id', $giat_anev->id)->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Giat Anev Record
                        if ($giat_anev_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.giat-anev.show', ['id' => $giat_anev->id])
                                ->with(['success' => 'Berhasil Menambahkan Giat Anev Diseminasi']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Giat Anev Diseminasi'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Giat Anev Diseminasi'])
                            ->withInput();
                    }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.giat-anev.show', ['id' => $giat_anev->id])
                        ->with(['success' => 'Berhasil Menambahkan Giat Anev Diseminasi']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah Giat Anev Diseminasi'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $giat_anev = GiatAnev::with(['institution.parent'])->findOrFail($id);
            $data['giat_anev'] = $giat_anev;
            return view('archieve.giat_anev.detail', $data);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $giat_anev = GiatAnev::with(['institution.parent'])->findOrFail($id);
            $data['giat_anev'] = $giat_anev;
            $data['levels'] = Institution::getLevel();
            $data['classifications'] = Classification::whereNull('deleted_at')->get();
            $data['type_mail_contents'] = TypeMailContent::whereNull('deleted_at')->get();
            return view('archieve.giat_anev.edit', $data);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Request Validation
            $request->validate([
                // 'number_giat' => 'required',
                'name' => 'required',
                'date' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Giat Anev Diseminasi
            $giat_anev_update = GiatAnev::where('id', $id)->update([
                'number_giat' => $request->number_giat,
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($giat_anev_update) {
                // Check Has Request File
                if (!empty($request->allFiles())) {
                    // Get Giat Anev Diseminasi Record
                    $giat_anev = GiatAnev::find($id);

                    // Image Path
                    $path = 'public/archieve/giat-anev';
                    $path_store = 'storage/archieve/giat-anev';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    // $giat_anev_attachment = json_decode($giat_anev->attachment);

                    // foreach ($giat_anev_attachment as $last_attachment) {
                    //     // File Last Record
                    //     $last_attachment_exploded = explode('/', $last_attachment);
                    //     $file_name_record = $last_attachment_exploded[count($last_attachment_exploded) - 1];

                    //     // Remove Last Record
                    //     if (Storage::exists($path . '/' . $file_name_record)) {
                    //         Storage::delete($path . '/' . $file_name_record);
                    //     }
                    // }

                    // $attachment_collection = [];

                    // foreach ($request->file('attachment') as $index => $attachment) {
                    //     // File Upload Configuration
                    //     $exploded_name = explode(' ', strtolower($request->name));
                    //     $file_name_config = implode('_', $exploded_name);
                    //     $file_name = $giat_anev->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

                    //     // Uploading File
                    //     $attachment->storePubliclyAs($path, $file_name);

                    //     // Check Upload Success
                    //     if (Storage::exists($path . '/' . $file_name)) {
                    //         array_push($attachment_collection, $path_store . '/' . $file_name);
                    //     } else {
                    //         // Failed and Rollback
                    //         DB::rollBack();
                    //         return redirect()
                    //             ->back()
                    //             ->with(['failed' => 'Gagal Upload Lampiran Giat Anev Diseminasi'])
                    //             ->withInput();
                    //     }
                    // }

                    // // Update Record for Attachment
                    // $giat_anev_attachment_update = $giat_anev->update([
                    //     'attachment' => $attachment_collection,
                    // ]);

                    // // Validation Update Attachment Giat Anev Diseminasi Record
                    // if ($giat_anev_attachment_update) {
                    //     DB::commit();
                    //     return redirect()
                    //         ->route('archieve.giat-anev.show', ['id' => $id])
                    //         ->with(['success' => 'Berhasil Perbarui Giat Anev Diseminasi']);
                    // } else {
                    //     // Failed and Rollback
                    //     DB::rollBack();
                    //     return redirect()
                    //         ->back()
                    //         ->with(['failed' => 'Gagal Update Lampiran Giat Anev Diseminasi'])
                    //         ->withInput();
                    // }

                    /**
                     * Get Filename Attachment Record
                     */
                    $picture_record_exploded = explode('/', $giat_anev->attachment);
                    $file_name_record = $picture_record_exploded[count($picture_record_exploded) - 1];

                    /**
                     * Remove Has File Exist
                     */
                    if (Storage::exists($path . '/' . $file_name_record)) {
                        Storage::delete($path . '/' . $file_name_record);
                    }

                    $exploded_name = explode(' ', strtolower($request->name));
                    $file_name_config = implode('_', $exploded_name);
                    $file = $request->file('attachment');
                    $file_name = $giat_anev->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $giat_anev_attachment_update = $giat_anev->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Giat Anev Record
                        if ($giat_anev_attachment_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.giat-anev.show', ['id' => $id])
                                ->with(['success' => 'Berhasil Perbarui Giat Anev Diseminasi']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Giat Anev Diseminasi'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Giat Anev Diseminasi'])
                            ->withInput();
                    }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.giat-anev.show', ['id' => $id])
                        ->with(['success' => 'Berhasil Perbarui Giat Anev Diseminasi']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Giat Anev Diseminasi'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['failed' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            // Destroy with Softdelete
            $giat_anev_destroy = GiatAnev::where('id', $id)->update(['deleted_by' => Auth::user()->id, 'deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Classification
            if ($giat_anev_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Giat Anev Diseminasi');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Giat Anev Diseminasi');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
