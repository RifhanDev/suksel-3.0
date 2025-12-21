@extends('layouts.modernLanding')

@section('styles')
<style>
    .page-header-modern {
        position: relative;
        background: white;
        border-radius: var(--radius-lg);
        padding: 2.5rem 2.5rem;
        margin-bottom: 1.5rem;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px -10px rgba(196, 30, 58, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header-modern::before {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background-image: 
            radial-gradient(at 90% 10%, rgba(196, 30, 58, 0.08) 0px, transparent 50%),
            radial-gradient(at 10% 90%, rgba(255, 204, 0, 0.08) 0px, transparent 50%);
        z-index: 0;
    }

    /* Decorative Circle */
    .page-header-modern::after {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 150px; height: 150px;
        background: linear-gradient(135deg, var(--sg-red) 0%, transparent 80%);
        border-radius: 50%;
        opacity: 0.1;
        z-index: 0;
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .header-pretitle {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--sg-red);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .header-pretitle::before {
        content: '';
        display: block;
        width: 20px;
        height: 2px;
        background: var(--sg-yellow);
    }

    .header-title {
        font-family: var(--font-display);
        font-weight: 800;
        font-size: 2rem;
        color: #111827;
        margin: 0;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .header-subtitle {
        font-size: 0.95rem;
        color: #6b7280;
        margin-top: 0.5rem;
        max-width: 600px;
    }

    /* Floating 3D Icon Box */
    .header-icon-box {
        position: relative;
        z-index: 2;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        color: var(--sg-red);
        transform: rotate(-5deg);
        transition: transform 0.3s ease;
    }

    .page-header-modern:hover .header-icon-box {
        transform: rotate(0deg) scale(1.05);
    }

    /* =========================================
       SIDEBAR & LIST
       ========================================= */
    .circular-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid #f3f4f6;
        box-shadow: 0 4px 15px -5px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-top: 1.5rem;
    }

    .circular-header {
        background: #fff;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        font-weight: 800;
        color: #1f2937;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .list-group-item-action {
        border: none;
        border-bottom: 1px solid #f3f4f6;
        padding: 1rem 1.25rem;
        font-size: 0.9rem;
        color: #4b5563;
        transition: all 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .list-group-item-action:hover {
        background-color: #fef2f2;
        color: var(--sg-red);
        padding-left: 1.5rem;
    }

    .list-group-item-action.active {
        background-color: var(--sg-red);
        color: white;
        border-color: var(--sg-red);
        font-weight: 700;
    }

    /* =========================================
       PDF VIEWER
       ========================================= */
    .pdf-viewer-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        padding: 1rem;
        min-height: 200px;
    }

    .pdfobject-container {
        height: 800px; /* Safe fixed height to prevent overflow */
        width: 100%;
        border: 1px solid #f3f4f6;
    }
    
    @media (max-width: 768px) {
        .page-header-modern { flex-direction: column; align-items: flex-start; padding: 1.5rem; gap: 1.5rem; }
        .header-icon-box { display: none; }
        .pdfobject-container { height: 500px; }
    }
</style>
@endsection

@section('content')
<div class="row g-4">
    
    <!-- LEFT SIDEBAR -->
    <div class="col-lg-3">
        
        <!-- Register Widget -->
        @include('layouts._register')

        <!-- Circulars List -->
        <div class="circular-card">
            <div class="circular-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                Senarai Pekeliling
            </div>
            <div class="list-group list-group-flush">
                @forelse ($circulars as $circular)
                    <a class="list-group-item list-group-item-action btn-circular-view" 
                       id="circular-{{ $circular->id }}"
                       data-id="{{ $circular->id }}"
                       data-url="{{ $circular->pdf_link ? $circular->pdf_link : $circular->file->url . '/' . $circular->file->name }}"
                       href="javascript:void(0);">
                        <span class="text-truncate">{{ $circular->title }}</span>
                        <i class="ti ti-chevron-right" style="font-size: 0.8rem;"></i>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted">
                        <p class="mb-0 small fw-bold">Tiada Pekeliling</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="col-lg-9">
        
        <div class="page-header-modern">
            <div class="header-content">
                <div class="header-pretitle">Sistem e-Perolehan Selangor</div>
                <h2 class="header-title">Pekeliling</h2>
                <p class="header-subtitle">
                    Rujukan rasmi mengenai tatacara perolehan dan pekeliling perbendaharaan Kerajaan Negeri Selangor.
                </p>
            </div>
            
            <div class="header-icon-box d-none d-md-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
					<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
					<path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
					<path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
					<path d="M9 12h6" />
					<path d="M9 16h6" />
				</svg>
            </div>
        </div>

        <!-- PDF Viewer Area -->
        <div class="pdf-viewer-card">
            <div id="doc-view">
                @if($circulars->isEmpty())
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#e5e7eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21h-14a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                        <p class="mt-3">Tiada dokumen untuk dipaparkan.</p>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="ti ti-loader animate-spin fs-2"></i>
                        <p class="mt-2">Memuatkan dokumen...</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.8/pdfobject.min.js"></script>
	<script src="{{ asset('js/displayfile.js') }}"></script>
    
    <script>
        // Auto-click the first item if available so the PDF loads immediately
        document.addEventListener('DOMContentLoaded', function() {
            var firstItem = document.querySelector('.btn-circular-view');
            if(firstItem) {
                firstItem.click();
            }
        });

        // Add active class handling for visual feedback
        const buttons = document.querySelectorAll('.btn-circular-view');
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                buttons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
@endsection