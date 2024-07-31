<?php

namespace App\Http\Controllers\Archieve;

use App\Http\Controllers\Controller;
use App\Models\Archieve\OutgoingMail;
use App\Models\Master\Classification;
use App\Models\Master\Institution;
use App\Models\Master\TypeMailContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OutgoingMailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = OutgoingMail::select(DB::raw('YEAR(date) as year'))->whereNull('deleted_by')->whereNull('deleted_at')->groupBy(DB::raw('YEAR(date)'))->orderBy(DB::raw('YEAR(date)'), 'DESC')->get()->toArray();
        $data['years'] = !empty($years) ? $years : [['year' => date('Y')]];
        $data['dt_route'] = route('archieve.outgoing-mail.dataTable'); // Route DataTables
        return view('archieve.outgoing_mail.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['levels'] = Institution::getLevel();
        $data['classifications'] = Classification::whereNull('deleted_at')->get();
        $data['type_mail_contents'] = TypeMailContent::whereNull('deleted_at')->get();
        return view('archieve.outgoing_mail.create', $data);
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable(Request $request)
    {
        $outgoing_mails = OutgoingMail::with(['classification', 'typeMailContent', 'institution'])
            ->whereYear('date', $request->year)
            ->whereNull('deleted_by')
            ->whereNull('deleted_at')
            ->orderBy('date', 'ASC')
            ->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($outgoing_mails)
            ->addIndexColumn()
            ->addColumn('date', function ($data) {
                return date('d F Y', strtotime($data->date));
            })
            ->addColumn('institution', function ($data) {
                return !is_null($data->institution_id) ? $data->institution->name : 'Kemendikbud';
            })
            ->addColumn('classification', function ($data) {
                return $data->classification->name;
            })
            ->addColumn('type_mail_content', function ($data) {
                return $data->typeMailContent->name;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('archieve.outgoing-mail.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5 ml-2 mb-1" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('archieve.outgoing-mail.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                $btn_action .= '<a target="_blank" href="' . asset($data->attachment) . '" class="btn btn-sm btn-info rounded-5 ml-2 mb-1" title="Lampiran Dokumen"><i class="fas fa-paperclip"></i></a>';
                return $btn_action;
            })
            ->only(['number', 'date', 'classification', 'institution', 'type_mail_content', 'name', 'action'])
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
                'number' => 'required',
                'name' => 'required',
                'date' => 'required',
                'classification' => 'required',
                'type_mail_content' => 'required',
                'attachment' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Outgoing Mail
            $outgoing_mail = OutgoingMail::lockforUpdate()->create([
                'number' => $request->number,
                'classification_id' => $request->classification,
                'type_mail_content_id' => $request->type_mail_content,
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($outgoing_mail) {
                // Image Path
                $path = 'public/archieve/outgoing-mail';
                $path_store = 'storage/archieve/outgoing-mail';

                // Check Exsisting Path
                if (!Storage::exists($path)) {
                    // Create new Path Directory
                    Storage::makeDirectory($path);
                }

                // $attachment_collection = [];

                // foreach ($request->file('attachment') as $index => $attachment) {
                //     // File Upload Configuration
                //     $exploded_name = explode(' ', strtolower($request->name));
                //     $file_name_config = implode('_', $exploded_name);
                //     $file_name = $outgoing_mail->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

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
                //             ->with(['failed' => 'Gagal Upload Lampiran Surat Keluar'])
                //             ->withInput();
                //     }
                // }

                // // Update Record for Attachment
                // $outgoing_mail_update = OutgoingMail::where('id', $outgoing_mail->id)->update([
                //     'attachment' => $attachment_collection,
                // ]);

                // // Validation Update Attachment Outgoing Mail Record
                // if ($outgoing_mail_update) {
                //     DB::commit();
                //     return redirect()
                //         ->route('archieve.outgoing-mail.show', ['id' => $outgoing_mail->id])
                //         ->with(['success' => 'Berhasil Menambahkan Surat Keluar']);
                // } else {
                //     // Failed and Rollback
                //     DB::rollBack();
                //     return redirect()
                //         ->back()
                //         ->with(['failed' => 'Gagal Update Lampiran Surat Keluar'])
                //         ->withInput();
                // }

                $exploded_name = explode(' ', strtolower($request->name));
                $file_name_config = implode('_', $exploded_name);
                $file = $request->file('attachment');
                $file_name = $outgoing_mail->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                // Uploading File
                $file->storePubliclyAs($path, $file_name);

                // Check Upload Success
                if (Storage::exists($path . '/' . $file_name)) {
                    // Update Record for Attachment
                    $outgoing_mail_update = OutgoingMail::where('id', $outgoing_mail->id)->update([
                        'attachment' => $path_store . '/' . $file_name,
                    ]);

                    // Validation Update Attachment Outgoing Mail Record
                    if ($outgoing_mail_update) {
                        DB::commit();
                        return redirect()
                            ->route('archieve.outgoing-mail.show', ['id' => $outgoing_mail->id])
                            ->with(['success' => 'Berhasil Menambahkan Surat Keluar']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Update Lampiran Surat Keluar'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Upload Lampiran Surat Keluar'])
                        ->withInput();
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah Surat Keluar'])
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
            $outgoing_mail = OutgoingMail::with(['classification', 'typeMailContent', 'institution.parent'])->findOrFail($id);
            $data['outgoing_mail'] = $outgoing_mail;
            return view('archieve.outgoing_mail.detail', $data);
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
            $outgoing_mail = OutgoingMail::with(['classification', 'typeMailContent', 'institution.parent'])->findOrFail($id);
            $data['outgoing_mail'] = $outgoing_mail;
            $data['levels'] = Institution::getLevel();
            $data['classifications'] = Classification::whereNull('deleted_at')->get();
            $data['type_mail_contents'] = TypeMailContent::whereNull('deleted_at')->get();
            return view('archieve.outgoing_mail.edit', $data);
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
                'number' => 'required',
                'classification' => 'required',
                'type_mail_content' => 'required',
                'name' => 'required',
                'date' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Outgoing Mail
            $outgoing_mail_update = OutgoingMail::where('id', $id)->update([
                'number' => $request->number,
                'classification_id' => $request->classification,
                'type_mail_content_id' => $request->type_mail_content,
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($outgoing_mail_update) {
                // Check Has Request File
                if (!empty($request->allFiles())) {
                    // Get Outgoing Mail Record
                    $outgoing_mail = OutgoingMail::find($id);

                    // Image Path
                    $path = 'public/archieve/outgoing-mail';
                    $path_store = 'storage/archieve/outgoing-mail';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    // $outgoing_mail_attachment = json_decode($outgoing_mail->attachment);

                    // foreach ($outgoing_mail_attachment as $last_attachment) {
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
                    //     $file_name = $outgoing_mail->id . '_' . ($index + 1) . '_' . $file_name_config . '.' . $attachment->getClientOriginalExtension();

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
                    //             ->with(['failed' => 'Gagal Upload Lampiran Surat Keluar'])
                    //             ->withInput();
                    //     }
                    // }

                    // // Update Record for Attachment
                    // $outgoing_mail_attachment_update = $outgoing_mail->update([
                    //     'attachment' => $attachment_collection,
                    // ]);

                    // // Validation Update Attachment Outgoing Mail Record
                    // if ($outgoing_mail_attachment_update) {
                    //     DB::commit();
                    //     return redirect()
                    //         ->route('archieve.outgoing-mail.show', ['id' => $id])
                    //         ->with(['success' => 'Berhasil Perbarui Surat Keluar']);
                    // } else {
                    //     // Failed and Rollback
                    //     DB::rollBack();
                    //     return redirect()
                    //         ->back()
                    //         ->with(['failed' => 'Gagal Update Lampiran Surat Keluar'])
                    //         ->withInput();
                    // }

                    /**
                     * Get Filename Attachment Record
                     */
                    $picture_record_exploded = explode('/', $outgoing_mail->attachment);
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
                    $file_name = $outgoing_mail->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $outgoing_mail_attachment_update = $outgoing_mail->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Outgoing Mail Record
                        if ($outgoing_mail_attachment_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.outgoing-mail.show', ['id' => $id])
                                ->with(['success' => 'Berhasil Perbarui Surat Keluar']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Surat Keluar'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Surat Keluar'])
                            ->withInput();
                    }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.outgoing-mail.show', ['id' => $id])
                        ->with(['success' => 'Berhasil Perbarui Surat Keluar']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Surat Keluar'])
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
            $outgoing_mail_destroy = OutgoingMail::where('id', $id)->update(['deleted_by' => Auth::user()->id, 'deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Classification
            if ($outgoing_mail_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Surat Keluar');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Surat Keluar');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
