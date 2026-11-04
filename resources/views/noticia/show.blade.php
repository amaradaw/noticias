<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $noticia->titulo }} - Banditore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('noticia.index') }}">Banditore</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('noticia.index') }}">Portada</a>
                <a class="nav-link" href="{{ route('noticia.create') }}">Nueva Noticia</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-9">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tarjeta Principal de la Noticia -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">{{ $noticia->created_at->format('d/m/Y H:i') }}</span>
                            <div>
                                @if($noticia->sePuedeEditar())
                                    <a href="{{ route('noticia.edit', $noticia->id) }}" class="btn btn-sm btn-outline-primary">Editar noticia</a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Han pasado más de 20 minutos">Edición bloqueada</button>
                                @endif

                                @if($noticia->sePuedeBorrar())
                                    <form action="{{ route('noticia.destroy', $noticia->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de borrar esta noticia?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="No se puede borrar porque tiene comentarios">No borrable</button>
                                @endif
                            </div>
                        </div>

                        <h1 class="fw-bold mb-3">{{ $noticia->titulo }}</h1>
                        <hr>
                        <!-- Renderizado de Markdown y Texto Normal -->
                        <div class="fs-5 text-secondary">
                            {!! Illuminate\Support\Str::markdown($noticia->texto) !!}
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Escribir un Comentario</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('comentario.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="noticia_id" value="{{ $noticia->id }}">

                            <div class="mb-3">
                                <label for="mensaje" class="form-label fw-bold">Comentario</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required placeholder="Escribe tu opinión..." minlength="2" maxlength="1000">{{ old('mensaje') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="valoracion" class="form-label fw-bold">Valoración</label>
                                <select class="form-select" id="valoracion" name="valoracion" required>
                                    <option value="pos">Positivo</option>
                                    <option value="neg">Negativo</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Publicar Comentario</button>
                        </form>
                    </div>
                </div>

                <!-- Sección de Lista de Comentarios -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Comentarios ({{ $noticia->comentarios->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @forelse($noticia->comentarios as $comentario)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-{{ $comentario->valoracion == 'pos' ? 'success' : 'danger' }}">
                                            {{ $comentario->valoracion == 'pos' ? 'Positivo' : 'Negativo' }}
                                        </span>
                                        <small class="text-muted ms-2">{{ $comentario->created_at->diffForHumans() }}</small>
                                    </div>

                                    <div>
                                        @if($comentario->sePuedeModificarOBorrar())
                                            <a href="{{ route('comentario.edit', $comentario->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2 me-1" style="font-size: 0.8rem;">Editar</a>

                                            <form action="{{ route('comentario.destroy', $comentario->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Borrar comentario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.8rem;">Borrar</button>
                                            </form>
                                        @else
                                            <small class="text-muted">Plazo de 5 min expirado</small>
                                        @endif
                                    </div>
                                </div>
                                <p class="mt-2 mb-1">{{ $comentario->mensaje }}</p>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No hay comentarios todavía. ¡Sé el primero en opinar!</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('noticia.index') }}" class="btn btn-secondary">&larr; Volver a la Portada</a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>