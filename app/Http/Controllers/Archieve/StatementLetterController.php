<?php

namespace App\Http\Controllers\Archieve;

use App\Http\Controllers\Controller;
use App\Models\Archieve\StatementLetter;
use App\Models\Master\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class StatementLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = StatementLetter::select(DB::raw('YEAR(date) as year'))->whereNull('deleted_by')->whereNull('deleted_at')->groupBy(DB::raw('YEAR(date)'))->orderBy(DB::raw('YEAR(date)'), 'DESC')->get()->toArray();
        $data['years'] = !empty($years) ? $years : [['year' => date('Y')]];
        $data['dt_route'] = route('archieve.statement-letter.dataTable'); // Route DataTables
        return view('archieve.statement_letter.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['levels'] = Institution::getLevel();
        return view('archieve.statement_letter.create', $data);
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable(Request $request)
    {
        $statement_letters = StatementLetter::with(['institution'])
            ->whereYear('date', $request->year)
            ->whereNull('deleted_by')
            ->whereNull('deleted_at')
            ->orderBy('date', 'ASC')
            ->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($statement_letters)
            ->addIndexColumn()
            ->addColumn('date', function ($data) {
                return date('d F Y', strtotime($data->date));
            })
            ->addColumn('institution', function ($data) {
                return !is_null($data->institution_id) ? $data->institution->name : 'Eksternal';
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('archieve.statement-letter.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5 ml-2 mb-1" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('archieve.statement-letter.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                $btn_action .= '<a target="_blank" href="' . asset($data->attachment) . '" class="btn btn-sm btn-info rounded-5 ml-2 mb-1" title="Lampiran Dokumen"><i class="fas fa-paperclip"></i></a>';
                return $btn_action;
            })
            ->only(['date', 'institution', 'name', 'action'])
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
                'name' => 'required',
                'date' => 'required',
                // 'attachment' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Statement Letter
            $statement_letter = StatementLetter::lockforUpdate()->create([
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($statement_letter) {
                // Image Path
                $path = 'public/archieve/statement-letter';
                $path_store = 'storage/archieve/statement-letter';

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
                    //     $file_name = $statement_letter->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

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
                    //             ->with(['failed' => 'Gagal Upload Lampiran Surat Pernyataan'])
                    //             ->withInput();
                    //     }
                    // }

                    // // Update Record for Attachment
                    // $statement_letter_update = StatementLetter::where('id', $statement_letter->id)->update([
                    //     'attachment' => $attachment_collection,
                    // ]);

                    // // Validation Update Attachment Statement Letter Record
                    // if ($statement_letter_update) {
                    //     DB::commit();
                    //     return redirect()
                    //         ->route('archieve.statement-letter.show', ['id' => $statement_letter->id])
                    //         ->with(['success' => 'Berhasil Menambahkan Surat Pernyataan']);
                    // } else {
                    //     // Failed and Rollback
                    //     DB::rollBack();
                    //     return redirect()
                    //         ->back()
                    //         ->with(['failed' => 'Gagal Update Lampiran Surat Pernyataan'])
                    //         ->withInput();
                    // }

                    $exploded_name = explode(' ', strtolower($request->name));
                    $file_name_config = implode('_', $exploded_name);
                    $file = $request->file('attachment');
                    $file_name = $statement_letter->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $statement_letter_update = StatementLetter::where('id', $statement_letter->id)->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Statement Letter Record
                        if ($statement_letter_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.statement-letter.show', ['id' => $statement_letter->id])
                                ->with(['success' => 'Berhasil Menambahkan Surat Pernyataan']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Surat Pernyataan'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Surat Pernyataan'])
                            ->withInput();
                    }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.statement-letter.show', ['id' => $statement_letter->id])
                        ->with(['success' => 'Berhasil Menambahkan Surat Pernyataan']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah Surat Pernyataan'])
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
            $statement_letter = StatementLetter::with(['institution.parent'])->findOrFail($id);
            $data['statement_letter'] = $statement_letter;
            return view('archieve.statement_letter.detail', $data);
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
            $statement_letter = StatementLetter::with(['institution.parent'])->findOrFail($id);
            $data['statement_letter'] = $statement_letter;
            $data['levels'] = Institution::getLevel();
            return view('archieve.statement_letter.edit', $data);
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
                'name' => 'required',
                'date' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Statement Letter
            $statement_letter_update = StatementLetter::where('id', $id)->update([
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($statement_letter_update) {
                // Check Has Request File
                if (!empty($request->allFiles())) {
                    // Get Statement Letter Record
                    $statement_letter = StatementLetter::find($id);

                    // Image Path
                    $path = 'public/archieve/statement-letter';
                    $path_store = 'storage/archieve/statement-letter';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    // $statement_letter_attachment = json_decode($statement_letter->attachment);

                    // foreach ($statement_letter_attachment as $last_attachment) {
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
                    //     $file_name = $statement_letter->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

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
                    //             ->with(['failed' => 'Gagal Upload Lampiran Surat Pernyataan'])
                    //             ->withInput();
                    //     }
                    // }

                    // // Update Record for Attachment
                    // $statement_letter_attachment_update = $statement_letter->update([
                    //     'attachment' => $attachment_collection,
                    // ]);

                    // // Validation Update Attachment Statement Letter Record
                    // if ($statement_letter_attachment_update) {
                    //     DB::commit();
                    //     return redirect()
                    //         ->route('archieve.statement-letter.show', ['id' => $id])
                    //         ->with(['success' => 'Berhasil Perbarui Surat Pernyataan']);
                    // } else {
                    //     // Failed and Rollback
                    //     DB::rollBack();
                    //     return redirect()
                    //         ->back()
                    //         ->with(['failed' => 'Gagal Update Lampiran Surat Pernyataan'])
                    //         ->withInput();
                    // }

                    /**
                     * Get Filename Attachment Record
                     */
                    $picture_record_exploded = explode('/', $statement_letter->attachment);
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
                    $file_name = $statement_letter->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $statement_letter_attachment_update = $statement_letter->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Statement Letter Record
                        if ($statement_letter_attachment_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.statement-letter.show', ['id' => $id])
                                ->with(['success' => 'Berhasil Perbarui Surat Pernyataan']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Surat Pernyataan'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Surat Pernyataan'])
                            ->withInput();
                    }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.statement-letter.show', ['id' => $id])
                        ->with(['success' => 'Berhasil Perbarui Surat Pernyataan']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Surat Pernyataan'])
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
            $statement_letter_destroy = StatementLetter::where('id', $id)->update(['deleted_by' => Auth::user()->id, 'deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Classification
            if ($statement_letter_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Surat Pernyataan');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Surat Pernyataan');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
