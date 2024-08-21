<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('master.institution.dataTable'); // Route DataTables
        return view('master.institution.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['levels'] = Institution::getLevel();
        return view('master.institution.create', $data);
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
                'level' => 'required',
            ]);

            // Validation Field
            $name_check = Institution::whereNull('deleted_at')
                ->where('name', $request->name)
                ->where('name', strtolower($request->name))
                ->first();

            // Check Validation Field
            if (is_null($name_check)) {
                DB::beginTransaction();

                // Query Store TypeMailContent
                $institution = Institution::lockforUpdate()->create([
                    'name' => $request->name,
                    'parent_id' => $request->institution,
                    'level' => $request->level,
                ]);

                if (!$request->ajax()) {
                    // Checking Store Data
                    if ($institution) {
                        DB::commit();
                        return redirect()
                            ->route('master.institution.index')
                            ->with(['success' => 'Berhasil Menambahkan Instansi']);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with(['failed' => 'Gagal Tambah Instansi'])
                            ->withInput();
                    }
                } else {
                    // Checking Store Data
                    if ($institution) {
                        DB::commit();
                        return response()->json(['data' => $institution], 200);
                    } else {
                        // Failed and Rollback
                        DB::rollBack();
                        return response()->json(['message' => 'Gagal Menambahkan Instansi'], 400);
                    }
                }
            } else {
                if (!$request->ajax()) {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Nama Instansi Sudah Terdaftar'])
                        ->withInput();
                } else {
                    return response()->json(['message' => 'Nama Instansi Sudah Terdaftar'], 400);
                }
            }
        } catch (\Exception $e) {
            if (!$request->ajax()) {
                return redirect()
                    ->back()
                    ->with(['failed' => $e->getMessage()])
                    ->withInput();
            } else {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
    }

    /**
     * Show datatable of resource.
     */

    public function dataTable()
    {
        $institutions = Institution::with(['parent'])
            ->whereNull('deleted_at')
            ->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($institutions)
            ->addIndexColumn()
            ->addColumn('level', function ($data) {
                $filter_level = collect(Institution::getLevel())
                    ->where('level', $data->level)
                    ->first();
                return !is_null($filter_level) ? $filter_level['name'] : $filter_level;
            })
            ->addColumn('parent', function ($data) {
                return !is_null($data->parent) ? $data->parent->name : $data->parent;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('master.institution.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
                $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                return $btn_action;
            })
            ->only(['name', 'level', 'parent', 'action'])
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $institution = Institution::with('parent')->findOrFail($id);
            $data['institution'] = $institution;
            $data['levels'] = Institution::getLevel();
            return view('master.institution.edit', $data);
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
                'level' => 'required',
            ]);

            // Validation Field
            $name_check = Institution::whereNull('deleted_at')
                ->where('name', $request->name)
                ->where('name', strtolower($request->name))
                ->where('id', '!=', $id)
                ->first();

            // Check Validation Field
            if (is_null($name_check)) {
                DB::beginTransaction();

                // Query Store Institution
                $institution_update = Institution::where('id', $id)->update([
                    'name' => $request->name,
                    'parent_id' => $request->institution,
                    'level' => $request->level,
                ]);

                // Checking Store Data
                if ($institution_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.institution.index')
                        ->with(['success' => 'Berhasil Perbarui Instansi']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Instansi'])
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
            $institution_destroy = Institution::where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Institution
            if ($institution_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Instansi');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Instansi');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
