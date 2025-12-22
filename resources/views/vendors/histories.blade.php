@extends('layouts.v3.master')

@section('styles')
<style>
	.page-title-text {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .title-pipe {
        font-size: 1.5rem;
        color: #cbd5e1;
        font-weight: 300;
        margin: 0 15px;
    }

    .vendor-highlight-text {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--sg-red);
        letter-spacing: 0.5px;
        text-shadow: 0 2px 4px rgba(196, 30, 58, 0.1); 
    }

    /* CARD WRAPPER */
    .stats-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .stats-card-header {
        padding: 20px 24px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 10px;
    }

    .table-clean thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 16px 24px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-clean tbody td {
        padding: 16px 24px;
        vertical-align: middle;
        color: #334155;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-clean tbody tr:hover {
        background-color: #fff9f9;
    }

    .date-text { font-weight: 700; color: #1e293b; }
    .time-text { font-size: 0.75rem; color: #94a3b8; font-weight: 500; margin-top: 2px; }

    .badge-action {
        background: var(--bs-info-bg-subtle);
        color: var(--bs-info);
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }

    .remarks-box {
        margin-top: 8px;
        font-size: 0.85rem;
        color: #64748b;
        background: #fffbeb;
        border: 1px solid #fef3c7;
        padding: 8px 12px;
        border-radius: 6px;
        border-left: 3px solid #f59e0b;
    }

    .rejection-box {
        margin-top: 8px;
        background: #fef2f2;
        border: 1px solid #fee2e2;
        border-left: 3px solid #ef4444;
        padding: 10px 12px;
        border-radius: 6px;
    }
    .rejection-title {
        color: #991b1b; font-weight: 700; font-size: 0.75rem; margin-bottom: 4px; text-transform: uppercase;
    }
    .rejection-content { font-size: 0.85rem; color: #7f1d1d; }

    .avatar-circle {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, var(--sg-red), var(--sg-red-dark));
        color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.75rem;
        box-shadow: 0 2px 4px rgba(196, 30, 58, 0.2);
    }

    .link-slide-underline {
        position: relative; text-decoration: none !important; transition: color 0.3s ease-in-out; padding-bottom: 3px;
    }
    .link-slide-underline:hover { color: var(--sg-red) !important; }
    .link-slide-underline::after {
        content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 10;
        background-color: var(--sg-red); transition: width 0.3s ease-in-out;
    }
    .link-slide-underline:hover::after { width: 90%; }
</style>
@endsection

@section('content')

<div class="mb-4 d-flex align-items-center flex-wrap">
	<h2 class="page-title-text m-0">
		Sejarah Kemaskini
	</h2>

	@if (isset($vendor))
		<span class="title-pipe">|</span>
		
		<span class="vendor-highlight-text">
			{{ $vendor->name }}
		</span>
	@endif
</div>

<div class="stats-card mb-4">
    
    {{-- <div class="stats-card-header">
        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2 text-dark">
            <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-2" style="width: 36px; height: 36px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            Log Aktiviti
        </h5>
    </div> --}}

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="DT2 table table-clean w-100 mb-0">
                <thead>
                    <tr>
                        <th style="width: 15%;">Tarikh</th>
                        <th>Keterangan</th>
                        <th style="width: 20%;">Pengguna</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendor->histories as $history)
                        <tr>
                            <!-- 1. DATE COLUMN -->
                            <td>
                                <div class="date-text">{{ Carbon\Carbon::parse($history->created_at)->format('d M Y') }}</div>
                                <div class="time-text">{{ Carbon\Carbon::parse($history->created_at)->format('h:i A') }}</div>
                            </td>
                            
                            <!-- 2. CONTENT COLUMN -->
                            <td>
                                <!-- Main Label -->
                                <span class="badge-action">
                                    {{ $history->label }}
                                </span>

                                <!-- Remarks -->
                                @if ($history->remarks && $history->remarks != '')
                                    <div class="remarks-box">
                                        <strong>Catatan:</strong> {{ $history->remarks }}
                                    </div>
                                @endif

                                <!-- Rejection -->
                                @if ($history->rejection_template_id)
                                    <div class="rejection-box">
                                        <div class="rejection-title">Sebab Penolakan</div>
                                        <ul class="mb-0 ps-3">
                                            @foreach (json_decode($history->rejection_template_id, true) as $reject_id)
                                                @foreach ($templates as $template)
                                                    @if ($template['id'] == $reject_id)
                                                        <li class="rejection-content">
                                                            <strong>{{ $template['title'] }}</strong> - {!! $template['content'] !!}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </td>

                            <!-- 3. USER COLUMN -->
                            <td>
                                @if ($history->user)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle flex-shrink-0">
                                            {{ strtoupper(substr($history->user->name, 0, 1)) }}
                                        </div>
                                        <div class="text-truncate fw-bold text-dark" style="max-width: 150px;" title="{{ $history->user->name }}">
                                            {{ $history->user->name }}
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap-3 opacity-50">
                                        <div class="avatar-circle bg-secondary flex-shrink-0" style="background: #94a3b8;">S</div>
                                        <div class="fst-italic text-truncate">Sistem</div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-start pb-5">
    <a href="{{ asset('vendors/' . $vendor->id) }}" class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-2 link-slide-underline">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        <span class="fw-bold">Maklumat Syarikat</span>
    </a>
</div>
@endsection