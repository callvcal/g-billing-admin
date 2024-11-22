<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    function all()
    {
        return response(AdminUser::with('roles', 'business')->where('business_id', auth()->user()->business_id)->get());
    }
    function delete($id)
    {

        if ($id == (auth()->user()->id)) {
            return response([
                'message' => "You can' delete by own"
            ], 401);
        }
        return response(AdminUser::find($id)->delete());
    }

    function create(Request $request)
    {


        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('admin_users')->ignore($request->id)],
            'role' => ['required'],
        ]);

        $admin = new AdminUser();
        if ($request->filled('id')) {
            $admin = AdminUser::find($request->id);

            if ($admin->business_id != (auth()->user()->business_id)) {
                return response([
                    'message' => "You don't have access to change this user."
                ], 401);
            }
        }

        $role = $request->role;

        $username = str_replace(' ', '', $request->input('username'));

        $admin->username = $username;
        $admin->name = $request->input('name');
        $admin->mobile = $request->input('mobile');
        $admin->admin_id = auth()->user()->id;
        $admin->business_id = auth()->user()->business_id;
        $admin->business_key = auth()->user()->business_key;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->save();

        $posRoleId = DB::table('admin_roles')->where('slug', $role)->value('id');
        if (!$posRoleId) {
            $posRoleId = DB::table('admin_roles')->insertGetId([
                'name' => $role,
                'slug' => $role
            ]);
        }

        // Assign the role to the user
        DB::table('admin_role_users')->insert([
            'role_id' => $posRoleId,
            'user_id' => $admin->id
        ]);

        $admin->load('roles', 'permissions');

        return response()->json($admin);
    }
}
