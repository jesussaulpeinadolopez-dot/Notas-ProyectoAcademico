<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $tasks = Task::where('id_usuario', $userId)->get();
        return response()->json($tasks);

     
    }

    public function store(Request $request) 
    {
        try {
            $userId = auth()->id();

            $task = new Task();
            $task->nombre = $request->nombre;
            $task->descripcion = $request->descripcion ?? '';
            $task->id_usuario = $userId;
            $task->save();

            return response()->json([
                'mensaje' => '¡Tarea guardada con éxito!',
                'tarea'   => $task
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error_real' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $userId = auth()->id();
            $task = Task::where('id_tarea', $id)->where('id_usuario', $userId)->first();

            if (!$task) {
                return response()->json(['error' => 'Tarea no encontrada'], 404);
            }

            $task->nombre = $request->nombre;
            $task->descripcion = $request->descripcion ?? '';
            $task->save();

            return response()->json([
                'mensaje' => 'Tarea actualizada',
                'tarea' => $task
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error_real' => $e->getMessage()
            ], 500);
        }
    }
      public function destroy($id)
    {
        try {
            $userId = auth()->id();
            $task = Task::where('id_tarea', $id)->where('id_usuario', $userId)->first();

            if (!$task) {
                return response()->json(['error' => 'Tarea no encontrada'], 404);
            }

            $task->delete();

            return response()->json(['mensaje' => 'Tarea eliminada']);

        } catch (\Exception $e) {
            return response()->json([
                'error_real' => $e->getMessage()
            ], 500);
        }
    }       

    public function logout(Request $request) {
        try {
            if (!$request->user()) {
                return response()->json(['mensaje' => 'No autenticado'], 401);
            }

            $token = $request->user()->currentAccessToken();

            // Verificamos si es un token real de la base de datos y no el "fantasma" de las cookies
            if ($token && get_class($token) === 'Laravel\Sanctum\PersonalAccessToken') {
                $token->delete();
                \Log::info('Token de DB eliminado. ID: ' . $token->id);
            } else {
                \Log::info('El usuario estaba autenticado por sesión/cookie, no por token de API.');
            }

            // Destruimos la sesión web por si acaso
            if ($request->hasSession()) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            return response()->json(['mensaje' => 'Sesión cerrada correctamente'], 200);
        } catch (\Exception $e) {
            \Log::error('Error en logout: ' . $e->getMessage());
            return response()->json(['mensaje' => 'Error al cerrar sesión'], 500);
        }
    }

}