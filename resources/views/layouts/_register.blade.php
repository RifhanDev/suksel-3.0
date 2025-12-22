@if(!Auth::check())
<div class="sidebar-widget d-flex flex-column justify-content-center" 
     style="background: linear-gradient(135deg, var(--sg-red) 0%, #9f1239 100%); border: none; min-height: 216px; position: relative; overflow: hidden;">
    
    <!-- Background -->
    <div style="position: absolute; top: -15px; right: -15px; opacity: 0.15; transform: rotate(-10deg);">
        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 21l18 0" />
            <path d="M5 21v-14l8 -4l8 4v14" />
            <path d="M13 21v-10l-6 -4" />
            <path d="M10 13l4 0" />
            <path d="M10 17l4 0" />
        </svg>
    </div>

    <div class="p-4 text-center text-white position-relative" style="z-index: 2;">
        
        <h5 class="fw-bold mb-2">Vendor Baru?</h5>
        <p class="small text-white-50 mb-3" style="line-height: 1.4;">
            Daftar akaun untuk sertai tender Kerajaan Negeri Selangor.
        </p>
        
        <a href="{{ asset('register') }}" class="btn btn-light w-100 fw-bold text-danger text-uppercase rounded-pill shadow-sm" style="font-size: 0.85rem;">
            <div class="d-flex align-items-center justify-content-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -3.85" /></svg>
                Daftar Syarikat
            </div>
        </a>
    </div>
</div>
@endif