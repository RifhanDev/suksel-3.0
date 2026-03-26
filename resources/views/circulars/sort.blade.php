@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Susunan Pekeliling</h3>
            <p class="text-muted small m-0">Seret dan lepas untuk mengubah susunan paparan pekeliling.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"></line>
                <line x1="8" y1="12" x2="21" y2="12"></line>
                <line x1="8" y1="18" x2="21" y2="18"></line>
                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
            <span class="fw-bold text-dark text-uppercase small">Susunan Pekeliling</span>
        </div>

        <div class="p-4">
            <ul id="simpleList" class="list-group mb-0">
                @foreach ($circulars as $circular)
                    <li data-id="{{ $circular->id }}"
                        class="list-group-item d-flex align-items-center gap-3 border-0 border-bottom"
                        style="cursor: move;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-muted">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        {{ $circular->title }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
            <a href="{{ asset('circulars') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Senarai Pekeliling
            </a>
            <div class="d-flex gap-2">
                <button class="btn-form btn-form-secondary" id="resetOrder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <path d="M3.51 15a9 9 0 1 0 .49-3.49"></path>
                    </svg>
                    Set Semula
                </button>
                <button class="btn-form btn-form-primary" id="saveCurrOrder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/sortable.js') }}"></script>
    <script type="text/javascript">
        var simpleList = document.getElementById('simpleList');

        // create sortable and save instance
        var sortable = Sortable.create(simpleList, {
            animation: 150
        });

        // save initial order
        var initialOrder = sortable.toArray();

        document.getElementById('saveCurrOrder').addEventListener('click', function(e) {
            var order = sortable.toArray();

            $.post('{{ route('circulars.update.position') }}', {
                order: order
            }).done(function(response) {
                window.location.href = '{{ route('circulars.index') }}';
            });
        });

        document.getElementById('resetOrder').addEventListener('click', function(e) {
            sortable.sort(initialOrder);
        })
    </script>
@endsection
