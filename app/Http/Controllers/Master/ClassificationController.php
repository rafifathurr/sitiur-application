<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('master.classification.dataTable'); // Route DataTables
        return view('master.classification.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.classification.create');
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $classficiations = Classification::whereNull('deleted_at')->get();

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($classficiations)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn_action = '<a href="' . route('master.classification.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';
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
            $name_check = Classification::whereNull('deleted_at')
                ->where('name', $request->name)
                ->orWhere('name', strtolower($request->name))
                ->first();

            // Check Validation Field
            if (is_null($name_check)) {
                DB::beginTransaction();

                // Query Store Classification
                $classification = Classification::lockforUpdate()->create([
                    'name' => $request->name,
                ]);

                // Checking Store Data
                if ($classification) {
                    DB::commit();
                    return redirect()
                        ->route('master.classification.index')
                        ->with(['success' => 'Berhasil Menambahkan Klasifikasi']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah Klasifikasi'])
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $classification = Classification::findOrFail($id);
            $data['classification'] = $classification;
            return view('master.classification.edit', $data);
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
            $name_check = Classification::whereNull('deleted_at')
                ->where('name', $request->name)
                ->orWhere('name', strtolower($request->name))
                ->where('id', '!=', $id)
                ->first();

            // Check Validation Field
            if (is_null($name_check)) {
                DB::beginTransaction();

                // Update Classification Record Without Password Request
                $classification_update = Classification::where('id', $id)->update([
                    'name' => $request->name,
                ]);

                // Validation Update Classification
                if ($classification_update) {
                    DB::commit();
                    return redirect()
                        ->route('master.classification.index')
                        ->with(['success' => 'Berhasil Perbarui Klasifikasi']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Perbarui Klasifikasi'])
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
            $classification_destroy = Classification::where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);

            // Validation Destroy Classification
            if ($classification_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus Klasifikasi');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus Klasifikasi');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
