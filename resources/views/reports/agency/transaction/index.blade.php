@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Transaksi Agensi Mengikut Tender</h3>
            <p class="text-muted small m-0">Jana laporan transaksi agensi mengikut tender sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4">
        <form action="{{ action('ReportAgencyTransactionController@view') }}" method="POST" target="_blank">
            @csrf

            @if (Auth::user()->can('Report:view:agency_transaction'))
                <div class="mb-3">
                    <label for="ou" class="form-label fw-semibold">Agensi</label>
                    <select name="ou" id="ou" class="form-select" required>
                        <option value="">Pilihan agensi...</option>
                        @foreach ($select_ou as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label for="year" class="form-label fw-semibold">Tahun</label>
                <select name="year" id="year" class="form-select" required>
                    <option value="">Pilihan tahun...</option>
                    @foreach ($select_year as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="month" class="form-label fw-semibold">Bulan</label>
                <select name="month" id="month" class="form-select" required>
                    <option value="">Pilihan bulan...</option>
                    @foreach ($select_month as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="btn-form btn-form-primary">Jana Laporan</button>
            </div>
        </form>
    </div>

@endsection
