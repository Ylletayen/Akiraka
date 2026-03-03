<div id="main-project-carousel" class="vertical-carousel">
    
    <a href="{{ url('/') }}" class="btn-back-fixed">
        Cerrar
    </a>

    @foreach($slides as $index => $slide)
    <section class="carousel-item">
        <div class="container-fluid h-100 p-0">
            <div class="row g-0 h-100">
                <div class="col-lg-8">
                    <div class="image-container">
                        <img src="{{ $slide['img'] }}" alt="Slide">
                    </div>
                </div>
                <div class="col-lg-4 d-flex align-items-center bg-white px-5">
                    <div class="content-wrapper">
                        <span class="slide-number">0{{ $index + 1 }}</span>
                        <h2 class="project-title-mini">{{ $proyecto_titulo }}</h2>
                        <p class="project-description">{{ $slide['desc'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endforeach
</div>