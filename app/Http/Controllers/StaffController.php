<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    function all()
    {
        return response(AdminUser::all());
    }
    function delete($id)
    {
        return response(AdminUser::find($id)->delete());
    }

    function create(Request $request)
    {
        $admin = new AdminUser();

       

        if ($request->filled('id')) {
            $admin = AdminUser::find($request->id);
        }else{
            $data = $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:admin_users'],
            ]);
        }

        $admin->username = $request->input('username');
        $admin->name = $request->input('name');
        $admin->admin_id = auth()->user()->id;
        $admin->business_id = auth()->user()->business_id;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->save();

        if (!$request->filled('id')) {
            // Create a new role if 'pos' role doesn't exist
            $posRoleId = DB::table('admin_roles')->where('slug', 'pos')->value('id');
            if (!$posRoleId) {
                $posRoleId = DB::table('admin_roles')->insertGetId([
                    'name' => 'POS Staff',
                    'slug' => 'pos'
                ]);
            }

            // Assign the role to the user
            DB::table('admin_role_users')->insert([
                'role_id' => $posRoleId,
                'user_id' => $admin->id
            ]);
        }

        $admin->load('roles', 'permissions');

        return response()->json($admin);
    }
}
