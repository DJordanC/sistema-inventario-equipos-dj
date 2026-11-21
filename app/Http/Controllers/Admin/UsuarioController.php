<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Oficina;
use App\Enums\RolUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('oficina')->latest()->paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $oficinas = Oficina::all();
        $roles = RolUsuario::cases();
        return view('admin.usuarios.create', compact('oficinas', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'password' => ['required', 'string', 'min:8'],
            'rol' => ['required', Rule::enum(RolUsuario::class)],
            'oficina_id' => ['nullable', 'exists:oficinas,id'],
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'oficina_id' => $request->oficina_id,
        ]);

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Usuario $usuario)
    {
        $oficinas = Oficina::all();
        $roles = RolUsuario::cases();
        return view('admin.usuarios.edit', compact('usuario', 'oficinas', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'rol' => ['required', Rule::enum(RolUsuario::class)],
            'oficina_id' => ['nullable', 'exists:oficinas,id'],
        ]);

        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'rol' => $request->rol,
            'oficina_id' => $request->oficina_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario)
    {
        // Evitar que el administrador se elimine a sí mismo
        if (auth()->id() === $usuario->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();
        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario eliminado correctamente.');
    }
}