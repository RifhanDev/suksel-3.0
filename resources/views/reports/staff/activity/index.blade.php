@extends('layouts.default')
@section('styles')
@endsection
@section('content')
    <h4 class="tender-title">Laporan Sistem Tender Online: Laporan Aktiviti Pengguna Sistem</h4>

    <form action="" method="POST" target="_blank" class="form-horizontal">
        @csrf
        <div class="form-group required">
            <label class="control-label col-lg-3 col-sm-3">
                Carian Mengikut
            </label>
            <div class="col-lg-9 col-sm-9">
                <select name="type" id="type" class="form-control" onchange="inputSelector(this.value)">
                    <option value="year" selected>Tahunan</option>
                    <option value="month">Bulan</option>
                </select>
            </div>
        </div>
        <div class="form-group required">
            <label class="control-label col-lg-3 col-sm-3">
                Tarikh <sup>*</sup>
            </label>
            <div class="col-lg-9 col-sm-9">
                <input type="text" class="form-control" name="year" id="year" value="">
                <input type="month" class="form-control" name="month" id="month" value=""
                    onfocus="this.showPicker()" style="display: none;">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
                <button type="submit" class="btn bg-blue-selangor">Hantar</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script src="{{ asset('js/report-date.js') }}"></script>
@endsection
