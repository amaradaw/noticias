<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Comentario - Banditore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('noticia.index') }}">Banditore</a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0">Editar Comentario</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('comentario.update', $comentario->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="mensaje" class="form-label fw-bold">Comentario</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required minlength="2" maxlength="1000">{{ old('mensaje', $comentario->mensaje) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="valoracion" class="form-label fw-bold">Valoración</label>
                                <select class="form-select" id="valoracion" name="valoracion" required>
                                    <option value="pos" {{ old('valoracion', $comentario->valoracion) == 'pos' ? 'selected' : '' }}>Positivo</option>
                                    <option value="neg" {{ old('valoracion', $comentario->valoracion) == 'neg' ? 'selected' : '' }}>Negativo</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('noticia.show', $comentario->noticia_id) }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Actualizar Comentario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>