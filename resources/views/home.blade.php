@extends('layouts.app')

@section('content')
    <div class="container">
        @if (!$ruta)
            <h1>Error 404</h1>
            <h2>Pagina no encontrada</h2>
            <h3>Este sitio pudo haber sido traslado a otro lado, porfavor verifique dicha direccion otorgada</h3>
        @else

        @endif
    </div>

@endsection
