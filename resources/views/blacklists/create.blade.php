@extends('layouts.v3.master')

@section('styles')
<style>
    .card-sidebar-compact {
        background: linear-gradient(160deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        position: sticky;
        top: 120px;
        box-shadow: 0 4px 10px rgba(196, 30, 58, 0.15);
        overflow: hidden;
    }
    .card-sidebar-compact::after {
        content: ''; position: absolute; bottom: -20px; right: -20px; width: 100px; height: 100px;
        background: var(--sg-yellow); opacity: 0.15; border-radius: 50%; pointer-events: none;
    }

    /* Form Card */
    .card-form-compact {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .card-form-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
    }
    .card-form-body {
        padding: 20px;
    }
    
    /* Link Animation */
    .link-slide-underline {
        position: relative; text-decoration: none !important; transition: color 0.3s ease-in-out; padding-bottom: 3px;
    }
    .link-slide-underline:hover { color: var(--sg-red) !important; }
    .link-slide-underline::after {
        content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0;
        background-color: var(--sg-red); transition: width 0.3s ease-in-out;
    }
    .link-slide-underline:hover::after { width: 100%; }
</style>
@endsection

@section('content')

<div class="mb-4">
    <h4 class="fw-bold text-dark m-0">Masukkan Senarai Hitam Baru</h4>
</div>

<form action="{{ route('vendor.blacklists.store', $vendor->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        
        <!-- LEFT -->
        <div class="col-lg-3">
            <div class="card-sidebar-compact">
                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                    </div>
                    <div class="fw-bold text-white small text-uppercase" style="letter-spacing: 1px;">Sasaran Syarikat</div>
                </div>

                <div class="mb-3">
                    <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Nama Syarikat</label>
                    <div class="fw-bold text-white fs-6 lh-sm">{{ $vendor->name }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">No. Pendaftaran</label>
                    <div class="fw-bold fs-6 text-white font-monospace">{{ $vendor->registration }}</div>
                </div>
                
                <hr class="border-white opacity-25 my-3">
                <p class="text-white small mb-0 opacity-75" style="font-size: 0.75rem; line-height: 1.4;">
                    <i class="ti ti-alert-circle me-1"></i> Tindakan ini akan menyenarai hitam syarikat daripada menyertai tender.
                </p>
            </div>
        </div>

        <!-- RIGHT: FORM CARD -->
        <div class="col-lg-9">
            <div class="card-form-compact">
                
                <div class="card-form-header">
                    <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Butiran Penyenaraian Hitam
                    </h6>
                </div>

                <div class="card-form-body">
                    
                    @include('blacklists.form')

                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light rounded-bottom">
                    
                    <!-- LEFT -->
                    <div>
                        <a href="{{ route('vendor.blacklists.index', $vendor->id) }}" class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-1 ps-0 link-slide-underline small">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            <span class="fw-bold">Kembali ke Senarai Hitam</span>
                        </a>
                    </div>

                    <!-- RIGHT -->
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-2 fw-bold px-4 py-2 btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        Hantar
                    </button>
                </div>

            </div>
        </div>
    </div>

</form>

@endsection