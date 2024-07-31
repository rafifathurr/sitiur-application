<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\TypeMailContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TypeMailContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('master.type-mail-content.dataTable'); // Route DataTables
        return view('master.type_mail_content.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.type_mail_content.create');
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $type_mail_contents = TypeMailContent::whereNull('deleted_at')->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($type_mail_contents)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('master.type-mail-content.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                return $btn_action;
            })
            ->only(['name', 'action'])
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
            ]);

            // Validation Field
            $name_check = TypeMailContent::whereNull('deleted_at')
                ->where('name', $request->name)
                ->where('name', strtolower($request->name))
                ->first();

            // Check Validation Field
            if (is_null($name_check)) {
                DB::beginTransaction();

                // Query Store TypeMailContent
                $type_mail_content = TypeMailContent::lockforUpdate()->create([
                    'name' => $request->name,
                ]);

                if (!isset($request->redirect)) {
                    // Checking Store Data
                    if ($type_mail_content) {
                        DB::commit();
                        return redirect()
                            ->route('master.type-mail-content.index')
                            ->with(['success' => 'Berhasil Menambahkan Jenis Isi Surat']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Tambah Jenis Isi Surat'])
                            ->withInput();
                    }
                } else {
                    // Checking Store Data
                    if ($type_mail_content) {
                        DB::commit();
                        return redirect()
                            ->back()
                            ->with(['success' => 'Berhasil Menambahkan Jenis Isi Surat']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Tambah Jenis Isi Surat'])
                            ->withInput();
                    }
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Nama Sudah Tersedia'])
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $type_mail_content = TypeMailContent::findOrFail($id);
            $data['type_mail_content'] = $type_mail_content;
            return view('master.type_mail_content.edit', $data);
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
                'name' => 'required|string',
            ]);

            // Validation Field
            $name_check = TypeMailContent::whereNull('deleted_at')
                ->where('name', $request->name)
                ->where('name', strtolower($request->name))
                ->where('id', '!=', $id)
                ->first();

            // Check Validation Field
            if (is_null($name_check)) {
                DB::beginTransaction();

                // Update Type Mail Content Record Without Password Request
                $type_mail_content_update = TypeMailContent::where('id', $id)->update([
                    'name' => $request->name,
                ]);

                // Validation Update Type Mail Content
                if ($type_mail_content_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.type-mail-content.index')
                        ->with(['success' => 'Berhasil Perbarui Jenis Isi Surat']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Perbarui Jenis Isi Surat'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Nama Sudah Tersedia'])
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
            $type_mail_content_destroy = TypeMailContent::where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Type Mail Content
            if ($type_mail_content_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Jenis Isi Surat');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Jenis Isi Surat');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
