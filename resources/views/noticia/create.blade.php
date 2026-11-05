<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Noticia - NoticieroAmara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('noticia.index') }}">NoticieroAmara</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('noticia.index') }}">Portada</a>
                <a class="nav-link active" href="{{ route('noticia.create') }}">Nueva Noticia</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0">Publicar Nueva Noticia</h4>
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

                        <form action="{{ route('noticia.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-bold">Título de la Noticia</label>
                                <input type="text" class="form-control" id="titulo" name="titulo"
                                    value="{{ old('titulo') }}" required minlength="3" maxlength="200">
                                <small class="text-muted">Entre 3 y 200 caracteres.</small>
                            </div>

                            <div class="mb-3">
                                <label for="texto" class="form-label fw-bold">Contenido (Texto Largo)</label>
                                <textarea class="form-control" id="texto" name="texto" rows="8"
                                    required>{{ old('texto') }}</textarea>
                            </div>
                            <div class="mb-4 p-3 bg-light rounded border">
                                <label for="archivo_md" class="form-label fw-bold mb-1">
                                    <i class="bi bi-filetype-md text-primary me-1"></i> Opcional: Subir nota en formato
                                    <code>.md</code> o <code>.txt</code>
                                </label>
                                <input type="file" name="archivo_md" id="archivo_md"
                                    class="form-control @error('archivo_md') is-invalid @enderror"
                                    accept=".md,.txt,text/plain,text/markdown">
                                @error('archivo_md')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted small">
                                    Si seleccionas un archivo, su texto se utilizará como contenido de la publicación.
                                    (Máx. 2MB).
                                </div>
                            </div>


                            <div class="d-flex justify-content-between">
                                <a href="{{ route('noticia.index') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Publicar Noticia</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>