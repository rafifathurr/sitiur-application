<?php

namespace App\Http\Controllers\Archieve;

use App\Http\Controllers\Controller;
use App\Models\Archieve\Documentation;
use App\Models\Master\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DocumentationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = Documentation::select(DB::raw('YEAR(date) as year'))->whereNull('deleted_by')->whereNull('deleted_at')->groupBy(DB::raw('YEAR(date)'))->orderBy(DB::raw('YEAR(date)'), 'DESC')->get()->toArray();
        $data['years'] = !empty($years) ? $years : [['year' => date('Y')]];
        $data['dt_route'] = route('archieve.documentation.dataTable'); // Route DataTables
        return view('archieve.documentation.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['levels'] = Institution::getLevel();
        return view('archieve.documentation.create', $data);
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable(Request $request)
    {
        $documentations = Documentation::with(['institution'])
            ->whereYear('date', $request->year)
            ->whereNull('deleted_by')
            ->whereNull('deleted_at')
            ->orderBy('date', 'ASC')
            ->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($documentations)
            ->addIndexColumn()
            ->addColumn('date', function ($data) {
                return date('d F Y', strtotime($data->date));
            })
            ->addColumn('institution', function ($data) {
                return !is_null($data->institution_id) ? $data->institution->name : 'Eksternal';
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('archieve.documentation.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5 ml-2 mb-1" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('archieve.documentation.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';

                if (!is_null($data->attachment)) {
                    $btn_action .= '<a target="_blank" href="' . asset($data->attachment) . '" class="btn btn-sm btn-info rounded-5 ml-2 mb-1" title="Lampiran Video" download><i class="fas fa-paperclip"></i></a>';
                }

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
            ]);

            DB::beginTransaction();

            // Query Store Documentation
            $documentation = Documentation::lockforUpdate()->create([
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($documentation) {
                if (!isset($request->has_link_attachment)) {
                    // Image Path
                    $path = 'public/archieve/documentation';
                    $path_store = 'storage/archieve/documentation';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    // File Upload Configuration
                    $exploded_name = explode(' ', strtolower($request->name));
                    $file_name_config = implode('_', $exploded_name);
                    $file = $request->file('attachment');
                    $file_name = $documentation->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    // Uploading File
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $documentation_update = Documentation::where('id', $documentation->id)->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Menu Record
                        if ($documentation_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.documentation.show', ['id' => $documentation->id])
                                ->with(['success' => 'Berhasil Menambahkan Dokumentasi Video']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Dokumentasi Video'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Dokumentasi Video'])
                            ->withInput();
                    }
                } else {
                    // Update Record for Attachment
                    $documentation_update = Documentation::where('id', $documentation->id)->update([
                        'link_attachment' => $request->link_attachment,
                    ]);

                    // Validation Update Attachment Menu Record
                    if ($documentation_update) {
                        DB::commit();
                        return redirect()
                            ->route('archieve.documentation.show', ['id' => $documentation->id])
                            ->with(['success' => 'Berhasil Menambahkan Dokumentasi Video']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Update Lampiran Dokumentasi Video'])
                            ->withInput();
                    }
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah Dokumentasi Video'])
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
            $documentation = Documentation::with('institution.parent')->findOrFail($id);
            $data['documentation'] = $documentation;
            return view('archieve.documentation.detail', $data);
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
            $documentation = Documentation::with('institution.parent')->findOrFail($id);
            $data['documentation'] = $documentation;
            $data['levels'] = Institution::getLevel();
            return view('archieve.documentation.edit', $data);
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

            // Query Store Documentation
            $documentation_update = Documentation::where('id', $id)->update([
                'name' => $request->name,
                'date' => $request->date,
                'institution_id' => $request->institution,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($documentation_update) {
                // Get Documentation Record
                $documentation = Documentation::find($id);

                // Check Has Request File
                if (!empty($request->allFiles())) {
                    // Image Path
                    $path = 'public/archieve/documentation';
                    $path_store = 'storage/archieve/documentation';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    // File Last Record
                    $attachment_exploded = explode('/', $documentation->attachment);
                    $file_name_record = $attachment_exploded[count($attachment_exploded) - 1];

                    // Remove Last Record
                    if (Storage::exists($path . '/' . $file_name_record)) {
                        Storage::delete($path . '/' . $file_name_record);
                    }

                    // File Upload Configuration
                    $exploded_name = explode(' ', strtolower($request->name));
                    $file_name_config = implode('_', $exploded_name);
                    $file = $request->file('attachment');
                    $file_name = $id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                    /**
                     * Upload File
                     */
                    $file->storePubliclyAs($path, $file_name);

                    // Check Upload Success
                    if (Storage::exists($path . '/' . $file_name)) {
                        // Update Record for Attachment
                        $documentation_attachment_update = $documentation->update([
                            'attachment' => $path_store . '/' . $file_name,
                            'link_attachment' => null,
                        ]);

                        // Validation Update Attachment Menu Record
                        if ($documentation_attachment_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.documentation.show', ['id' => $id])
                                ->with(['success' => 'Berhasil Perbarui Dokumentasi Video']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Dokumentasi Video'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Dokumentasi Video'])
                            ->withInput();
                    }
                } else {
                    if (isset($request->has_link_attachment)) {
                        // Update Record for Attachment
                        $documentation_update = $documentation->update([
                            'attachment' => null,
                            'link_attachment' => $request->link_attachment,
                        ]);

                        // Validation Update Attachment Menu Record
                        if ($documentation_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.documentation.show', ['id' => $documentation->id])
                                ->with(['success' => 'Berhasil Menambahkan Dokumentasi Video']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Dokumentasi Video'])
                                ->withInput();
                        }
                    } else {
                        DB::commit();
                        return redirect()
                            ->route('archieve.documentation.show', ['id' => $id])
                            ->with(['success' => 'Berhasil Perbarui Dokumentasi Video']);
                    }
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Dokumentasi Video'])
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
            $documentation_destroy = Documentation::where('id', $id)->update(['deleted_by' => Auth::user()->id, 'deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Documentation
            if ($documentation_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Dokumentasi Video');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Dokumentasi Video');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
