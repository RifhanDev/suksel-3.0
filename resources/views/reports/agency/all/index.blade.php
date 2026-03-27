@extends('layouts.v3.master')

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Laporan Transaksi Semua Agensi</h3>
            <p class="text-muted small m-0">Jana laporan transaksi semua agensi sistem tender online.</p>
        </div>
    </div>

    <div class="content-card p-4">
        <form action="{{ action('ReportAgencyAllController@view') }}" method="POST" target="_blank">
            @csrf

            <div class="mb-3">
                <label for="type" class="form-label fw-semibold">Jenis Laporan</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="">Pilih jenis laporan yang ingin dihasilkan...</option>
                    @foreach ($select_type as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 form-group">
                <label for="year_start" class="form-label fw-semibold">Tahun</label>
                <select name="year_start" id="year_start" class="form-select" required>
                    <option value="">Pilih tahun laporan yang ingin dihasilkan...</option>
                    @foreach ($select_year as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4 form-group">
                <label for="year_end" class="form-label fw-semibold">Hingga</label>
                <select name="year_end" id="year_end" class="form-select">
                    <option value="">Pilih tahun laporan yang ingin dihasilkan...</option>
                    @foreach ($select_year as $value => $label)
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

@section('scripts')
    <script type="text/javascript">
        $("form").find("select[name=year_end]").each(function() {
            $(this).parents('.form-group').hide();
        });

        $("form select[name=type]").on('change', function() {
            selected = $(this).find('option:selected')

            if (selected && selected.val() == 'yearly') {
                year_end = $("select[name=year_end]");
                form_group = year_end.parents('.form-group')
                form_group.fadeIn();
                form_group.find('label').text('hingga');
                $("select[name=year_start]").parents('.form-group').find('label').text('Mulai Tahun');
                year_end.attr('required', true);
            } else {
                year_end = $("select[name=year_end]:visible");
                form_group = year_end.parents('.form-group')
                form_group.fadeOut();
                $("select[name=year_start]").parents('.form-group').find('label').text('Tahun');
                year_end.attr('required', false);
            }
        });
    </script>
@endsection
