<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia - Banditore</title>
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
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0">Editar Noticia</h4>
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

                        <form action="{{ route('noticia.update', $noticia->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-bold">Título de la Noticia</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $noticia->titulo) }}" required minlength="3" maxlength="200">
                            </div>

                            <div class="mb-3">
                                <label for="texto" class="form-label fw-bold">Contenido (Texto Largo)</label>
                                <textarea class="form-control" id="texto" name="texto" rows="8" required>{{ old('texto', $noticia->texto) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('noticia.index') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Actualizar Noticia</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>