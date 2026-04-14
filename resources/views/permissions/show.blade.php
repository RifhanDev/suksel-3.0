@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Paparan Kebenaran</h3>
            <p class="text-muted small m-0">Butiran maklumat kebenaran sistem.</p>
        </div>
    </div>

    @include('permissions.form')

    <div class="content-card mt-3">
        <div class="d-flex justify-content-between align-items-center p-4 bg-light">
            <a href="{{ route('permissions.index') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Senarai Kebenaran
            </a>
            <div class="d-flex gap-2">
                @if ($permission->canUpdate())
                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn-form btn-form-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Kemaskini
                    </a>
                @endif
                @if ($permission->canDelete())
                    <form action="{{ url('permissions/' . $permission->id) }}" method="POST" class="d-inline m-0">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-form btn-form-danger confirm-delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6l-1 14H6L5 6"></path>
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M9 6V4h6v2"></path>
                            </svg>
                            Padam
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection