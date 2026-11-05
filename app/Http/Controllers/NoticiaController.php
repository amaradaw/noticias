<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticiaController extends Controller
{
    private const RULES = [
        'titulo' => 'required|string|min:3|max:200',
        'texto' => 'required|string',
    ];

    private const MESSAGES = [
        'titulo.required' => 'El título es obligatorio.',
        'titulo.min' => 'El título debe tener al menos 3 caracteres.',
        'titulo.max' => 'El título no puede superar los 200 caracteres.',
        'texto.required' => 'El contenido de la noticia no puede estar vacío.',
    ];

    /**
     * Muestra el listado de noticias.
     */
    public function index(): View
    {
        $noticias = Noticia::withCount('comentarios')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $totalComentarios = Comentario::count();

        return view('noticia.index', compact('noticias', 'totalComentarios'));
    }

    /**
     * Muestra el formulario para crear una nueva noticia.
     */
    public function create(): View
    {
        return view('noticia.create');
    }

    /**
     * Almacena una nueva noticia en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate(self::RULES, self::MESSAGES);

        Noticia::create([
            'titulo' => $request->titulo,
            'texto' => $request->texto,
        ]);

        return redirect()->route('noticia.index')->with('success', 'Noticia publicada correctamente.');
    }

    /**
     * Muestra el detalle de una noticia específica junto con sus comentarios.
     */
    public function show(string $id): View
    {
        $noticia = Noticia::with('comentarios')->findOrFail($id);
        return view('noticia.show', compact('noticia'));
    }

    /**
     * Muestra el formulario de edición (validando la regla de los 20 minutos).
     */
    public function edit(string $id)
    {
        $noticia = Noticia::findOrFail($id);

        //No se puede editar si han pasado 20 minutos o más
        if (!$noticia->sePuedeEditar()) {
            return redirect()->route('noticia.index')->with('error', 'No se puede editar la noticia porque han transcurrido 20 minutos o más desde su publicación.');
        }

        return view('noticia.edit', compact('noticia'));
    }

    /**
     * Actualiza la noticia en la base de datos (validando la regla de los 20 minutos).
     */
    public function update(Request $request, string $id)
    {
        $noticia = Noticia::findOrFail($id);

        if (!$noticia->sePuedeEditar()) {
            return redirect()->route('noticia.index')->with('error', 'El tiempo límite de 20 minutos para editar esta noticia ha expirado.');
        }

        $request->validate(self::RULES, self::MESSAGES);

        $noticia->update([
            'titulo' => $request->titulo,
            'texto' => $request->texto,
        ]);

        return redirect()->route('noticia.index')->with('success', 'Noticia actualizada con éxito.');
    }

    /**
     * Elimina la noticia.
     */
    public function destroy(string $id)
    {
        $noticia = Noticia::findOrFail($id);

        if (!$noticia->sePuedeBorrar()) {
            return redirect()->route('noticia.index')->with('error', 'No se puede eliminar la noticia porque tiene comentarios asociados.');
        }

        $noticia->delete();

        return redirect()->route('noticia.index')->with('success', 'Noticia eliminada correctamente.');
    }
}