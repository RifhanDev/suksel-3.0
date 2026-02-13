@extends('layouts.modernLanding')

@section('styles')
<style>
    .selangor-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: 0 5px 25px -5px rgba(196, 30, 58, 0.08);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        position: relative;
    }

    .selangor-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--sg-red) 50%, var(--sg-yellow) 50%);
    }

    .card-header-custom {
        padding: 2rem 2rem 1rem 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px dashed #f3f4f6;
    }

    .card-body-custom {
        padding: 1.5rem 2rem 2rem 2rem;
    }

    /* =========================================
    	FORM ELEMENTS
       ========================================= */
    .form-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #374151;
        margin-bottom: 0.3rem;
    }

    .form-control {
        border: 1px solid #e5e7eb;
        border-radius: var(--radius-sm);
        padding: 0.6rem 0.9rem;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--sg-black);
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--sg-red);
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1); 
        color: var(--sg-black);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    /* =========================================
       BUTTONS & ALERTS
       ========================================= */
    .btn-selangor {
        background-color: var(--sg-red);
        color: white;
        font-weight: 700;
        padding: 0.7rem 1.5rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--sg-red);
        transition: all 0.2s ease;
        font-size: 0.85rem;
        display: inline-flex; align-items: center; gap: 0.5rem;
    }

    .btn-selangor:hover {
        background-color: var(--sg-red-dark);
        border-color: var(--sg-red-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .note-box {
        background-color: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        color: #92400e;
        display: flex; gap: 0.75rem; align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .header-icon {
        width: 48px; height: 48px;
        background: rgba(196, 30, 58, 0.08);
        color: var(--sg-red);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .card-header-custom, .card-body-custom { padding: 1.5rem; }
    }
</style>
@endsection

@section('content')
	<div class="page-header">
		<div class="page-title">
			<div class="page-pretitle">
				Sistem Tender Online
			</div>
		</div>
	</div>

	<h2 class="page-title">
		<i class="ti ti-message-circle me-2"></i>Aduan
	</h2>
	<br>

	<div class="card">
		<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
			<h3 class="card-title mb-0">
				<i class="ti ti-file-text me-2"></i>Hantar Aduan
			</h3>
			<a href="{{ auth()->check() ? route('my.aduan.index') : url('/') }}" class="btn btn-outline-secondary btn-sm">
				<i class="ti ti-arrow-left me-1"></i>Kembali
			</a>
		</div>
		<div class="card-body">
			{!! Former::open(url('aduan')) !!}
			@include('complaint.form')
			<div class="d-flex justify-content-end gap-2 mt-4">
				{!! Former::submit('Hantar')->class('btn btn-primary') !!}
			</div>
			{!! Former::close() !!}
		</div>
	</div>
@stop
