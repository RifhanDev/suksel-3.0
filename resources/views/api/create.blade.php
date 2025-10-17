@extends('layouts.default')
@section('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
@endsection
@section('content')
    <h2 class="tender-title">Masukkan Token Agensi Baru</h2>

    <form action="{{ route('apitoken.store') }}" class="form-horizontal" method="POST">
        @csrf   
        <div class="form-group required">
            <label for="agency" class="control-label col-lg-3 col-sm-3">
                Agensi <sup>*</sup>
            </label>
            <div class="col-lg-9 col-sm-9">
                <select name="organization_unit_id" id="organization_unit_id" class="form-control" required>
                    @foreach ($agencies as $agency)
                        <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group required">
            <label for="agency" class="control-label col-lg-3 col-sm-3">
                Token <sup>*</sup>
            </label>
            <div class="col-lg-7 col-sm-7">
                <input type="text" name="token" id="token" class="form-control" readonly>
            </div>
            <div class="col-lg-2 col-sm-2">
                <button class="btn btn-xs btn-primary generate">Jana</button>
            </div>
        </div>
        <div class="well">
            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="{{ asset('apitoken') }}" class="btn btn-default pull-right">Senarai API Token</a>
        </div>
    </form>
@endsection
@section('scripts')
    <script type="text/javascript">
        $('.generate').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('apitoken.generate') }}",
                data: {
                    'id': $('#organization_unit_id').val()
                },
                dataType: "json",
                success: function(response) {
                    $('#token').val(response);
                }
            });
        });
    </script>
@endsection
