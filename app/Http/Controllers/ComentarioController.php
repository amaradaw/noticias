<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    private const RULES = [
        'noticia_id' => 'required|exists:noticia,id',
        'mensaje' => 'required|string|min:2|max:1000',
        'valoracion' => 'required|in:pos,neg',
    ];

    private const MESSAGES = [
        'mensaje.required' => 'El comentario no puede estar vacío.',
        'mensaje.min' => 'El comentario debe tener al menos 2 caracteres.',
        'valoracion.in' => 'La valoración debe ser pos o neg.',
    ];

    public function store(Request $request)
    {
        $request->validate(self::RULES, self::MESSAGES);

        Comentario::create([
            'noticia_id' => $request->noticia_id,
            'mensaje' => $request->mensaje,
            'valoracion' => $request->valoracion,
        ]);

        return redirect()->route('noticia.show', $request->noticia_id)
            ->with('success', 'Comentario publicado correctamente.');
    }

    public function destroy(string $id)
    {
        $comentario = Comentario::findOrFail($id);
        $noticiaId = $comentario->noticia_id;

        if (!$comentario->sePuedeModificarOBorrar()) {
            return redirect()->route('noticia.show', $noticiaId)
                ->with('error', 'No se puede eliminar el comentario porque han transcurrido más de 5 minutos desde su publicación.');
        }

        $comentario->delete();

        return redirect()->route('noticia.show', $noticiaId)
            ->with('success', 'Comentario eliminado correctamente.');
    }

    public function edit(string $id)
{
    $comentario = Comentario::findOrFail($id);

    // Validar plazo de 5 minutos
    if (!$comentario->sePuedeModificarOBorrar()) {
        return redirect()->route('noticia.show', $comentario->noticia_id)
            ->with('error', 'El plazo de 5 minutos para editar este comentario ha expirado.');
    }

    return view('comentario.edit', compact('comentario'));
}


  public function update(Request $request, string $id)
    {
        $comentario = Comentario::findOrFail($id);
        $noticiaId = $comentario->noticia_id;

        // Comprobación de seguridad en servidor
        if (!$comentario->sePuedeModificarOBorrar()) {
            return redirect()->route('noticia.show', $noticiaId)
                ->with('error', 'El plazo de 5 minutos para editar este comentario ha expirado.');
        }

        $request->validate([
            'mensaje'    => 'required|string|min:2|max:1000',
            'valoracion' => 'required|in:pos,neg',
        ], self::MESSAGES);

        $comentario->update([
            'mensaje'    => $request->mensaje,
            'valoracion' => $request->valoracion,
        ]);

        return redirect()->route('noticia.show', $noticiaId)
            ->with('success', 'Comentario actualizado correctamente.');
    }
}