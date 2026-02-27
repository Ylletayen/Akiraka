<div id="landing-view" class="vh-100 d-flex flex-column">
    <header class="container-fluid py-4 px-5 d-flex justify-content-between align-items-center bg-white">
        <div class="logo-wrapper d-flex flex-column align-items-center">
            <div class="custom-logo-triangles">
                <div class="tri-1"></div>
                <div class="tri-2"></div>
                <div class="tri-3"></div>
            </div>
            <span class="logo-brand-text mt-1">ESTUDIO AKIRAKA</span>
        </div>

        <nav class="d-none d-lg-flex align-items-center gap-4">
            <a href="#" class="nav-link-akira">ABOUT</a>
            <a href="#" class="nav-link-akira">PROJECTS</a>
            <a href="#" class="nav-link-akira">CONSTRUCTION</a>
            <a href="#" class="nav-link-akira">CONTACT</a>
            <div class="social-links-akira d-flex gap-2 ms-3">
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </nav>
    </header>

    <div class="landing-hero-image flex-grow-1">
        <div class="enter-overlay d-flex align-items-end justify-content-center pb-5">
            <a href="{{ route('project.detail') }}" class="btn-enter text-decoration-none text-white">
                EXPLORAR ESTUDIO
            </a>
        </div>
    </div>
</div>