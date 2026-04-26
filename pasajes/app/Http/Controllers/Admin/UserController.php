<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cooperativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with("roles", "cooperativa")
            ->where("id", "!=", auth()->id())
            ->paginate(15);
        
        $cooperativas = Cooperativa::all();
        $roles = Role::whereIn("name", ["Admin", "Oficinista", "Chofer", "Controlador"])->get();

        return view("admin.usuarios.index", compact("users", "cooperativas", "roles"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "lowercase", "email", "max:255", "unique:users"],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
            "cooperativa_id" => ["nullable", "exists:cooperativas,id"],
            "cedula" => ["nullable", "string", "max:20"],
            "telefono" => ["nullable", "string", "max:20"],
            "fecha_nacimiento" => ["nullable", "date"],
            "roles" => ["required", "array"],
            "roles.*" => ["exists:roles,name"],
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "cooperativa_id" => $request->cooperativa_id,
            "cedula" => $request->cedula,
            "telefono" => $request->telefono,
            "fecha_nacimiento" => $request->fecha_nacimiento,
        ]);

        $user->assignRole($request->roles);

        return back()->with("success", "Usuario creado exitosamente.");
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "lowercase", "email", "max:255", "unique:users,email," . $user->id],
            "cooperativa_id" => ["nullable", "exists:cooperativas,id"],
            "cedula" => ["nullable", "string", "max:20"],
            "telefono" => ["nullable", "string", "max:20"],
            "fecha_nacimiento" => ["nullable", "date"],
            "roles" => ["required", "array"],
            "roles.*" => ["exists:roles,name"],
        ]);

        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "cooperativa_id" => $request->cooperativa_id,
            "cedula" => $request->cedula,
            "telefono" => $request->telefono,
            "fecha_nacimiento" => $request->fecha_nacimiento,
        ]);

        $user->syncRoles($request->roles);

        return back()->with("success", "Usuario actualizado exitosamente.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with("error", "No puedes eliminar tu propio usuario.");
        }

        try {
            $user->delete();
            return back()->with("success", "Usuario eliminado.");
        } catch (\Exception $e) {
            return back()->with("error", "No se puede eliminar el usuario porque tiene registros asociados.");
        }
    }
}