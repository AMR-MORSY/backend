<?php

namespace App\Http\Controllers\Admin;

use App\Models\Users\User;
use App\Traits\Admin\Users;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\Admin\AuthAndAuthorization;
use App\Services\EnergyAlarms\DownAlarmsHelpers;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    use AuthAndAuthorization;
    use Users;
    public function userAbilities()
    {

        $user = User::find(Auth::user()->id);
        if ($user) {
            $perissions = [];

            if ($user->hasRole('super-admin')) {
                $perissions = Permission::all()->pluck('name');
            } else {

                $perissions = $user->getAllPermissions()->pluck('name');
            }

            return response()->json([
                "permissions" => $perissions


            ], 200);
        }
    }
    public function roles()
    {
        $roles = $this->retrieveAllRoles();
        return response()->json([
            "success" => true,
            "roles" => $roles
        ], 200);
    }
    public function viewRole($id)
    {
        $data = $this->retrieveRoleAndAssignedPermissions($id);
        if ($data["role"]) {
            return response()->json([
                "success" => true,
                "role" => $data["role"],
                "permissions" => $data["permissions"]
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Role Not found"

        ], 422);
    }
    public function getRolesPermissionsByRoleName(Request $request)
    {
        $validator = Validator::make($request->all(), [

            "roles" => ["required", "array"],
            "roles.*" => ["required", "exists:roles,name"],



        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
            $validated = $validator->validated();
            $perissions = [];
            foreach ($validated["roles"] as $role_name) {
                $role = Role::where("name", $role_name)->first();
                $role_perissions = $role->permissions;
                array_push($perissions, $role_perissions->toArray());
            }
            return response()->json([
                "success" => true,

                "permissions" => $perissions,

            ], 200);
        }
    }
    public function editRole($id)
    {
        $data = $this->retrieveRoleAndAssignedPermissions($id);
        if ($data["role"]) {
            $allpermissions = $this->retrieveAllPermissions();
            $Rolepermissions = $data["permissions"];
            $permDiff = $allpermissions->diff($Rolepermissions);
            return response()->json([
                "success" => true,
                "role" => $data["role"],
                "permissions" => $data["permissions"],
                "permDiff" => $permDiff
            ], 200);
        }
        return response()->json([
            "success" => false,
            "message" => "Role Not found"

        ], 422);
    }

    public function updateRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => ["required", "exists:roles,id"],
            "name" => ["required", "exists:roles,name"],
            "rolePermissions" => ["required", "array"],
            "rolePermissions.*" => ["required", "exists:permissions,name"],


        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
            $validated = $validator->validated();
            $role = Role::find($validated["id"]);
            $role->syncPermissions($validated["rolePermissions"]);
            return response()->json([
                "success" => true,
                "role" => $role
            ]);
        }
    }
    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(), [

            "name" => ["required", "regex:/^[a-zA-Z]{3,}[a-zA-Z_-]*$/", "min:3", "max:30", "unique:roles"],
            "selectedRollPermissions" => ["required", "array"],
            "selectedRollPermissions.*" => ["required", "exists:permissions,name"],



        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
            $validated = $validator->validated();
            $role = Role::create(["name" => $validated["name"], "guard_name" => "web"]);
            $role = $role->givePermissionTo($validated["selectedRollPermissions"]);
            return response()->json([
                "success" => true,
                "role" => $role

            ], 200);
        }
    }
    public function permissions()
    {
        $perissions = $this->retrieveAllPermissions();
        return response()->json([
            "success" => true,
            "permissions" => $perissions
        ], 200);
    }
    public function deletePermission($id)
    {
        $data = ["id" => $id];

        $validator = Validator::make($data, [
            "id" => ["required", "exists:permissions,id"],
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
            $validated = $validator->validated();
            $perission = Permission::find($validated["id"]);
            $perission->delete();
            return response()->json([
                "success" => true,

            ], 200);
        }
    }

    public function createPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required", "regex:/^[a-zA-Z]{3,}[a-zA-Z_-]*$/", "min:3", "max:30", "unique:permissions"],
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
            $validated = $validator->validated();
            $perission = Permission::create(["name" => $validated["name"], "guard_name" => "web"]);
            return response()->json(array(
                'success' => true,
                'permission' => $perission,

            ), 200);
        }
    }
    public function users()
    {
        // $this->authorize("viewAny", User::class);

        $users = $this->retrievAllUsersTable();

        return response()->json([
            "success" => true,
            "users" => $users
        ], 200);
    }
    public function updateUserRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [

            "id" => ["required", "exists:users,id"],
            // "name" => ["required", "regex:/^[a-zA-Z]{3,}[a-zA-Z_-]*$/", "min:3", "max:30", "unique:roles"],
            "roles" => ["required", "array"],
            "roles.*" => ["exists:roles,name"],




        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
            $validated = $validator->validated();
            $user = User::find($validated["id"]);
            $user->syncRoles($validated["roles"]);
            return response()->json([
                "success" => true
            ], 200);
        }
    }


    public function user($id)
    {
        $userData = $this->retrievUserData($id);
        if (count($userData) > 0) {
            return response()->json([
                "success" => true,
                "user" => $userData
            ], 200);
        }
        return response()->json([
            "success" => false,
            "message" => "user does not exits"

        ], 422);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
    }



    public function adminLogin(Request $request)
    {

        $validated = $this->validateCredintials($request->all())["validated"];
        if (count($validated) > 0) {
            $user = User::where("email", $validated["email"])->first();
            if ($this->canAccessAdminPanel($user)) {
                return $this->userAuthAttempt($validated);
            } else {
                return response()->json([
                    "message" => "User does not has the right to access this panel"
                ], 402);
            }
        }
        return response()->json([

            "errors" => $this->validateCredintials($request->all())["errors"]
        ], 422);
    }
    public function canAccessAdminPanel($user): bool
    {
        return str_ends_with($user->email, 'morsy.mamr@gmail.com') && $this->hasVerifiedEmail($user);
    }
}
