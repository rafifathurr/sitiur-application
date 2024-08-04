<?php

namespace App\Http\Controllers\Archieve;

use App\Http\Controllers\Controller;
use App\Models\Archieve\Mou;
use App\Models\Master\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MouController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = Mou::select(DB::raw('YEAR(date) as year'))->whereNull('deleted_by')->whereNull('deleted_at')->groupBy(DB::raw('YEAR(date)'))->orderBy(DB::raw('YEAR(date)'), 'DESC')->get()->toArray();
        $data['years'] = !empty($years) ? $years : [['year' => date('Y')]];
        $data['dt_route'] = route('archieve.mou.dataTable'); // Route DataTables
        return view('archieve.mou.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['levels'] = Institution::getLevel();
        return view('archieve.mou.create', $data);
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable(Request $request)
    {
        $mou = Mou::with(['institution'])
            ->whereYear('date', $request->year)
            ->whereNull('deleted_by')
            ->whereNull('deleted_at')
            ->orderBy('date', 'ASC')
            ->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($mou)
            ->addIndexColumn()
            ->addColumn('date', function ($data) {
                return date('d F Y', strtotime($data->date));
            })
            ->addColumn('type', function ($data) {
                return $data->type == 0 ? 'Korlantas - Kemendikbud' : 'Kewilayahan';
            })
            ->addColumn('institution', function ($data) {
                return !is_null($data->institution) ? $data->institution->name : 'Kemendikbud';
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('archieve.mou.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5 ml-2 mb-1" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('archieve.mou.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                $btn_action .= '<a target="_blank" href="' . asset($data->attachment) . '" class="btn btn-sm btn-info rounded-5 ml-2 mb-1" title="Lampiran Dokumen"><i class="fas fa-paperclip"></i></a>';
                return $btn_action;
            })
            ->only(['number_mou', 'date', 'type', 'institution', 'name', 'action'])
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
                'number_mou' => 'required',
                'name' => 'required',
                'date' => 'required',
                'type' => 'required',
                // 'attachment' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store MOU
            $mou = Mou::lockforUpdate()->create([
                'number_mou' => $request->number_mou,
                'name' => $request->name,
                'date' => $request->date,
                'type' => $request->type,
                'institution_id' => $request->type == 1 ? $request->institution : null,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($mou) {
                // Image Path
                $path = 'public/archieve/mou';
                $path_store = 'storage/archieve/mou';

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
                    //     $file_name = $mou->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

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
                    //             ->with(['failed' => 'Gagal Upload Lampiran MOU'])
                    //             ->withInput();
                    //     }
                    // }

                    // // Update Record for Attachment
                    // $mou_update = Mou::where('id', $mou->id)->update([
                    //     'attachment' => $attachment_collection,
                    // ]);

                    // // Validation Update Attachment MOU Record
                    // if ($mou_update) {
                    //     DB::commit();
                    //     return redirect()
                    //         ->route('archieve.mou.show', ['id' => $mou->id])
                    //         ->with(['success' => 'Berhasil Menambahkan MOU']);
                    // } else {
                    //     // Failed and Rollback
                    //     DB::rollBack();
                    //     return redirect()
                    //         ->back()
                    //         ->with(['failed' => 'Gagal Update Lampiran MOU'])
                    //         ->withInput();
                    // }

                    $exploded_name = explode(' ', strtolower($request->name));
                    $file_name_config = implode('_', $exploded_name);
                    $file = $request->file('attachment');
                    $file_name = $mou->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $mou_update = Mou::where('id', $mou->id)->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment MOU Record
                        if ($mou_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.mou.show', ['id' => $mou->id])
                                ->with(['success' => 'Berhasil Menambahkan MOU']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran MOU'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran MOU'])
                            ->withInput();
                    }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.mou.show', ['id' => $mou->id])
                        ->with(['success' => 'Berhasil Menambahkan MOU']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah MOU'])
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
            $mou = Mou::with('institution.parent')->findOrFail($id);
            $data['mou'] = $mou;
            return view('archieve.mou.detail', $data);
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
            $mou = Mou::with('institution.parent')->findOrFail($id);
            $data['mou'] = $mou;
            $data['levels'] = Institution::getLevel();
            return view('archieve.mou.edit', $data);
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
                'number_mou' => 'required',
                'name' => 'required',
                'date' => 'required',
                'type' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store MOU
            $mou_update = Mou::where('id', $id)->update([
                'number_mou' => $request->number_mou,
                'name' => $request->name,
                'date' => $request->date,
                'type' => $request->type,
                'institution_id' => $request->type == 1 ? $request->institution : null,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($mou_update) {
                // Check Has Request File
                if (!empty($request->allFiles())) {
                    // Get MOU Record
                    $mou = Mou::find($id);

                    // Image Path
                    $path = 'public/archieve/mou';
                    $path_store = 'storage/archieve/mou';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    // $mou_attachment = json_decode($mou->attachment);

                    // foreach ($mou_attachment as $last_attachment) {
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
                    //     $file_name = $mou->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

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
                    //             ->with(['failed' => 'Gagal Upload Lampiran MOU'])
                    //             ->withInput();
                    //     }
                    // }

                    // // Update Record for Attachment
                    // $mou_attachment_update = $mou->update([
                    //     'attachment' => $attachment_collection,
                    // ]);

                    // // Validation Update Attachment MOU Record
                    // if ($mou_attachment_update) {
                    //     DB::commit();
                    //     return redirect()
                    //         ->route('archieve.mou.show', ['id' => $id])
                    //         ->with(['success' => 'Berhasil Perbarui MOU']);
                    // } else {
                    //     // Failed and Rollback
                    //     DB::rollBack();
                    //     return redirect()
                    //         ->back()
                    //         ->with(['failed' => 'Gagal Update Lampiran MOU'])
                    //         ->withInput();
                    // }

                    /**
                     * Get Filename Attachment Record
                     */
                    $picture_record_exploded = explode('/', $mou->attachment);
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
                    $file_name = $mou->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $mou_attachment_update = $mou->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment MOU Record
                        if ($mou_attachment_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.mou.show', ['id' => $id])
                                ->with(['success' => 'Berhasil Perbarui MOU']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran MOU'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran MOU'])
                            ->withInput();
                    }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.mou.show', ['id' => $id])
                        ->with(['success' => 'Berhasil Perbarui MOU']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui MOU'])
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
            $mou_destroy = Mou::where('id', $id)->update(['deleted_by' => Auth::user()->id, 'deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy MOU
            if ($mou_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus MOU');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus MOU');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
