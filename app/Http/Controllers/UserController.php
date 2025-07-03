<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // Importa el Validador para un logging más detallado


class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('role')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Log del inicio del proceso y los datos recibidos
        Log::info('Iniciando proceso de creación de usuario.', ['request_data' => $request->all()]);

        // En lugar de dd(), que detiene la ejecución, puedes usar Log para depurar
        // Log::debug('Datos completos del request', $request->all());

        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Log en caso de que la validación falle
        if ($validator->fails()) {
            Log::error('La validación para crear usuario falló.', [
                'errors' => $validator->errors()
            ]);
            // Redirige de vuelta con los errores
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Log::info('La validación de datos fue exitosa.');

        try {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                Log::info('Se encontró un archivo de foto para subir.');
                $fotoPath = $request->file('foto')->store('fotos', 'public');
                Log::info('La foto se guardó exitosamente en: ' . $fotoPath);
            }

            $user = User::create([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'foto' => $fotoPath,
                'password' => Hash::make($request->password), // ¡Importante! Usa el password del request, no uno fijo.
                'role_id' => $request->role_id,
            ]);

            Log::info('Usuario creado exitosamente.', ['user_id' => $user->id, 'email' => $user->email]);

            return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');

        } catch (\Exception $e) {
            // Log si ocurre cualquier otro error durante la creación
            Log::error('Ocurrió un error al crear el usuario.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // Opcional: para un rastreo completo del error
            ]);

            // Redirige con un mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al crear el usuario.');
        }
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        if (!$usuario) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }

        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role_id' => 'required|exists:roles,id',
        ]);

        $usuario = User::findOrFail($id);
        if (!$usuario) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }

        $fotoPath = $usuario->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        $usuario->update([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'foto' => $fotoPath,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        if (!$usuario) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }

        // Eliminar la foto del usuario si existe
        if ($usuario->foto) {
            Storage::disk('public')->delete($usuario->foto);
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
