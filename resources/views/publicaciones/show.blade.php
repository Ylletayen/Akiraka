@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left"></i> Volver al portafolio
                </a>
            </div>

            <article>
                <header class="mb-5">
                    <h1 class="display-4 fw-light text-dark mb-3">{{ $publicacion->titulo }}</h1>
                    <div class="d-flex align-items-center text-secondary border-bottom pb-3">
                        <span class="text-uppercase tracking-widest small">
                            {{ \Carbon\Carbon::parse($publicacion->fecha)->format('d . m . Y') }}
                        </span>
                        <span class="mx-3 text-muted">|</span>
                        <span class="small italic">Arquitectura Sustentable</span>
                    </div>
                </header>

                <section class="mb-5">
                    <p class="lead text-dark lh-lg" style="font-weight: 300; text-align: justify;">
                        {{ $publicacion->descripcion }}
                    </p>
                </section>

                @if($publicacion->url)
                <footer class="mt-5 pt-4 border-top">
                    <a href="{{ $publicacion->url }}" 
                       target="_blank" 
                       class="btn btn-outline-dark rounded-0 px-4 py-2 text-uppercase fw-bold small">
                        Explorar Proyecto Completo
                    </a>
                </footer>
                @endif
            </article>
        </div>
    </div>
</div>

<style>
    /* Estilos adicionales para ese look de estudio de arquitectura */
    body {
        background-color: #ffffff;
        font-family: 'Inter', sans-serif; /* O cualquier fuente sans-serif limpia */
    }
    .display-4 {
        letter-spacing: -1px;
    }
    .tracking-widest {
        letter-spacing: 0.15em;
    }
    .btn-outline-dark:hover {
        background-color: #000;
        color: #fff;
    }
</style>
@endsection