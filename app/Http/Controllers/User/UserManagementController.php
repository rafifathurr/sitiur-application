<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dt_route'] = route('user-management.dataTable'); // Route DataTables
        return view('user_management.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['roles'] = Role::all(); // List of Roles
        return view('user_management.create', $data);
    }

    /**
     * Show datatable of resource.
     */
    public function dataTable()
    {
        $list_of_users = User::whereNull('deleted_at')->get(); // All Users

        // DataTables Yajraa Configuration
        $dataTable = DataTables::of($list_of_users)
            ->addIndexColumn()
            ->addColumn('role', function ($data) {
                // Get Role Relation
                $exploded_raw_role = explode('-', $data->getRoleNames()[0]);
                $user_role = ucwords(implode(' ', $exploded_raw_role));
                return $user_role;
            })
            ->addColumn('action', function ($data) {
                $btn_action = '<div align="center">';
                $btn_action .= '<a href="' . route('user-management.show', ['id' => $data->id]) . '" class="btn btn-sm btn-primary rounded-5 ml-2 mb-1" title="Detail"><i class="fas fa-eye"></i></a>';
                $btn_action .= '<a href="' . route('user-management.edit', ['id' => $data->id]) . '" class="btn btn-sm btn-warning rounded-5 ml-2 mb-1" title="Ubah"><i class="fas fa-pencil-alt"></i></a>';

                // Validation Current User Can't Delete it Self
                if (Auth::user()->id != $data->id) {
                    $btn_action .= '<button class="btn btn-sm btn-danger rounded-5 ml-2 mb-1" onclick="destroyRecord(' . $data->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                }
                $btn_action .= '</div>';
                return $btn_action;
            })
            ->only(['name', 'role', 'action'])
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
                'name' => 'required|string',
                'email' => 'required|email',
                'username' => 'required',
                'roles' => 'required',
                'password' => 'required',
                're_password' => 'required|same:password',
            ]);

            // Validation Field
            $username_check = User::whereNull('deleted_at')
                ->where('username', $request->username)
                ->first();
            $email_check = User::whereNull('deleted_at')
                ->where('email', $request->email)
                ->first();

            // Check Validation Field
            if (is_null($username_check) && is_null($email_check)) {
                DB::beginTransaction();

                // Query Store User
                $user = User::lockforUpdate()->create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => bcrypt($request->password),
                ]);

                // Assigning Role of User
                $model_has_role = $user->assignRole($request->roles);

                // Checking Store Data
                if ($user && $model_has_role) {
                    DB::commit();
                    return redirect()
                        ->route('user-management.index')
                        ->with(['success' => 'Berhasil Menambahkan User']);
                } else {
                    // Failed and Rollback
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Gagal Tambah User'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Email atau Username Sudah Terdaftar'])
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
            $user = User::find($id);

            // Validation User Request
            if (!is_null($user)) {
                // Getting Role
                $exploded_raw_role = explode('-', $user->getRoleNames()[0]);
                $data['user'] = $user;
                $data['user_role'] = ucwords(implode(' ', $exploded_raw_role));
                return view('user_management.detail', $data);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Permintaan Gagal!']);
            }
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
            $user = User::find($id);

            // Validation User Request
            if (!is_null($user)) {
                $data['user'] = $user; // User Record
                $data['roles'] = Role::all(); // List of Roles
                $data['role_disabled'] = $id == Auth::user()->id ? 'disabled' : ''; // Disabled Changing Role User It Self
                return view('user_management.edit', $data);
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Permintaan Gagal!']);
            }
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
                'email' => 'required|email',
                'username' => 'required',
                'roles' => 'required',
            ]);

            // Validation Field
            $username_check = User::whereNull('deleted_at')
                ->where('username', $request->username)
                ->where('id', '!=', $id)
                ->first();
            $email_check = User::whereNull('deleted_at')
                ->where('email', $request->email)
                ->where('id', '!=', $id)
                ->first();

            // Check Validation Field
            if (is_null($username_check) && is_null($email_check)) {
                // Get User Record
                $user = User::find($id);

                // Validation User
                if (!is_null($user)) {
                    // Checking Password Request
                    if (isset($request->password)) {
                        // Validation Password
                        $request->validate([
                            'password' => 'required',
                            're_password' => 'required|same:password',
                        ]);

                        DB::beginTransaction();

                        // Update User Record With Password Request
                        $user_update = User::where('id', $id)->update([
                            'name' => $request->name,
                            'email' => $request->email,
                            'username' => $request->username,
                            'password' => bcrypt($request->password),
                        ]);
                    } else {
                        DB::beginTransaction();

                        // Update User Record Without Password Request
                        $user_update = User::where('id', $id)->update([
                            'name' => $request->name,
                            'email' => $request->email,
                            'username' => $request->username,
                        ]);
                    }

                    // Validation Request Role
                    if ($user->getRoleNames()[0] != $request->roles) {
                        // Remove Last Role
                        $model_has_role_delete = $user->removeRole($user->getRoleNames()[0]);

                        // Assigning New Role
                        $model_has_role_update = $user->assignRole($request->roles);

                        // Validation Update User
                        if ($user_update && $model_has_role_delete && $model_has_role_update) {
                            DB::commit();
                            return redirect()
                                ->route('user-management.index')
                                ->with(['success' => 'Berhasil Perbarui User']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Perbarui User'])
                                ->withInput();
                        }
                    } else {
                        // Validation Update User
                        if ($user_update) {
                            DB::commit();
                            return redirect()
                                ->route('user-management.index')
                                ->with(['success' => 'Berhasil Perbarui User']);
                        } else {
                            // Failed and Rollback
                            DB::rollBack();
                            return redirect()
                                ->back()
                                ->with(['failed' => 'Gagal Perbarui User'])
                                ->withInput();
                        }
                    }
                } else {
                    return redirect()
                        ->back()
                        ->with(['failed' => 'Permintaan Gagal!']);
                }
            } else {
                return redirect()
                    ->back()
                    ->with(['failed' => 'Email or Username Already Exist'])
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
            $user_destroy = User::where('id', $id)->update([
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

            // Validation Destroy User
            if ($user_destroy) {
                DB::commit();
                session()->flash('success', 'Berhasil Hapus User');
            } else {
                // Failed and Rollback
                DB::rollBack();
                session()->flash('failed', 'Gagal Hapus User');
            }
        } catch (\Exception $e) {
            session()->flash('failed', $e->getMessage());
        }
    }
}
