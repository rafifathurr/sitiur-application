<?php

namespace App\Http\Controllers\Archieve;

use App\Http\Controllers\Controller;
use App\Models\Archieve\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('archieve.gallery.dataTable'); // Route DataTables
        return view('archieve.gallery.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('archieve.gallery.create');
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $galeries = Gallery::whereNull('deleted_by')->whereNull('deleted_at')->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($galeries)
            ->addIndexColumn()
            ->addColumn('date', function ($data) {
                return date('d F Y', strtotime($data->date));
            })
            ->addColumn('attachment', function ($data) {
                return '<img width="100%" src="' . asset($data->attachment) . '" alt="" class="rounded-5 border border-1-default">';
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('archieve.gallery.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5 ml-2 mb-1" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('archieve.gallery.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                // $btn_action .= '<a target="_blank" href="' . asset($data->attachment) . '" class="btn btn-sm btn-info rounded-5 ml-2 mb-1" title="Lampiran Dokumen"><i class="fas fa-paperclip"></i></a>';
                return $btn_action;
            })
            ->only(['date', 'name', 'attachment', 'action'])
            ->rawColumns(['attachment', 'action'])
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
                'attachment' => 'required',
            ]);

            DB::beginTransaction();

            // Query Store Gallery
            $gallery = Gallery::lockforUpdate()->create([
                'name' => $request->name,
                'date' => $request->date,
                'description' => $request->description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($gallery) {
                // Image Path
                $path = 'public/archieve/gallery';
                $path_store = 'storage/archieve/gallery';

                // Check Exsisting Path
                if (!Storage::exists($path)) {
                    // Create new Path Directory
                    Storage::makeDirectory($path);
                }

                // File Upload Configuration
                $exploded_name = explode(' ', strtolower($request->name));
                $file_name_config = implode('_', $exploded_name);
                $file = $request->file('attachment');
                $file_name = $gallery->id . '_' . $file_name_config . '.' . $file->getClientOriginalExtension();

                // Uploading File
                $file->storePubliclyAs($path, $file_name);

                // Check Upload Success
                if (Storage::exists($path . '/' . $file_name)) {
                    // Update Record for Gallery
                    $gallery_update = Gallery::where('id', $gallery->id)->update([
                        'attachment' => $path_store . '/' . $file_name,
                    ]);

                    // Validation Update Gallery Menu Record
                    if ($gallery_update) {
                        DB::commit();
                        return redirect()
                            ->route('archieve.gallery.show', ['id' => $gallery->id])
                            ->with(['success' => 'Berhasil Menambahkan Galeri']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Update Lampiran Galeri'])
                            ->withInput();
                    }
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Upload Lampiran Galeri'])
                        ->withInput();
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Tambah Galeri'])
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
            $gallery = Gallery::findOrFail($id);
            $data['gallery'] = $gallery;
            return view('archieve.gallery.detail', $data);
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
            $gallery = Gallery::findOrFail($id);
            $data['gallery'] = $gallery;
            return view('archieve.gallery.edit', $data);
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

            // Query Store Gallery
            $gallery_update = Gallery::where('id', $id)->update([
                'name' => $request->name,
                'date' => $request->date,
                'description' => $request->description,
                'updated_by' => Auth::user()->id,
            ]);

            // Checking Store Data
            if ($gallery_update) {
                // Check Has Request File
                if (!empty($request->allFiles())) {
                    // Get Gallery Record
                    $gallery = Gallery::find($id);

                    // Image Path
                    $path = 'public/archieve/gallery';
                    $path_store = 'storage/archieve/gallery';

                    // Check Exsisting Path
                    if (!Storage::exists($path)) {
                        // Create new Path Directory
                        Storage::makeDirectory($path);
                    }

                    // File Last Record
                    $attachment_exploded = explode('/', $gallery->attachment);
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
                        $gallery_attachment_update = $gallery->update([
                            'attachment' => $path_store . '/' . $file_name,
                        ]);

                        // Validation Update Attachment Menu Record
                        if ($gallery_attachment_update) {
                            DB::commit();
                            return redirect()
                                ->route('archieve.gallery.show', ['id' => $id])
                                ->with(['success' => 'Berhasil Perbarui Galeri']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Update Lampiran Galeri'])
                                ->withInput();
                        }
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Upload Lampiran Galeri'])
                            ->withInput();
                    }
                } else {
                    DB::commit();
                    return redirect()
                        ->route('archieve.gallery.show', ['id' => $id])
                        ->with(['success' => 'Berhasil Perbarui Galeri']);
                }
            } else {
                // Failed and Rollback
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with(['failed' => 'Gagal Perbarui Galeri'])
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
            $gallery_destroy = Gallery::where('id', $id)->update(['deleted_by' => Auth::user()->id, 'deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Gallery
            if ($gallery_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Galeri');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Galeri');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
