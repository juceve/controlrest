<?php

namespace App\Http\Controllers;

use App\Models\Sucursale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.users.index')->only('index');
        $this->middleware('can:admin.users.create')->only('create', 'store');
        $this->middleware('can:admin.users.edit')->only('edit', 'update');
    }

    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $sucursales = Sucursale::all();
        return view('admin.user.create', compact('sucursales'));
    }

    public function store(Request $request)
    {
        request()->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "sucursale_id" => "required"
        ]);
        DB::beginTransaction();
        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt('12345678'),
                "sucursale_id" => $request->sucursale_id,
                "avatar" => ""
            ]);
            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Usuario registrado correctamente!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.users.create')->with('error', 'Ha ocurrido un error, no se registro la transacción.');
        }
    }


    public function edit(User $user)
    {
        $sucursales = Sucursale::all()->pluck('nombre', 'id');
        $estados = array(["id"=>"1","nombre"=>"Activo"],["id"=>"0","nombre"=>"Inactivo"]);
        return view('admin.user.edit', compact('sucursales', 'user','estados'));
    }

    public function update(Request $request, User $user)
    {
        request()->validate([
            "name" => "required",
            "sucursale_id" => "required"
        ]);
        DB::beginTransaction();
        try {
            $usuario = User::find($user->id);
            $usuario->name = $request->name;
            $usuario->sucursale_id = $request->sucursale_id;
            $usuario->avatar = $request->avatar;
            $usuario->estado = $request->estado;
            $usuario->save();
            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario editado correctamente.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.users.edit', $user)
                // ->with('error', $th->getMessage());
                ->with('error', 'Ha ocurrido un error, no se realizo la transacción');
        }
    }

    public function asinaRol(User $user)
    {
        $roles = Role::all();
        return view('admin.user.asignaRol', compact('user', 'roles'));
    }


    public function updateRol(Request $request, User $user)
    {
        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.asignaRol', $user)
            ->with('success', 'Roles asignados correctamente');
    }

    public function resetPassword($id)
    {
        $usuario = User::find($id);
        DB::beginTransaction();
        try {
            $usuario->password = bcrypt('12345678');
            $usuario->save();

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Password reseteado!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.users.index')->with('error', 'Ha ocurrido un error, no se registro la transacción.');
        }
    }
}
