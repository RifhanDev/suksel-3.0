@extends('layouts.default')
@section('content')
    <h2>Lihat Aduan</h2>
    <hr>

    <table class="table table-bordered">
        <tr>
            <th style="width: 20%">Subjek</th>
            <td>{{ $complaint->subject }}</td>
        </tr>
        <tr>
            <th>Kandungan</th>
            <td style="height: 300px; overflow-y:auto"">{{ $complaint->content }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $complaint->email }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $complaint->complaintStatus() }}</td>
        </tr>
        <tr>
            <th>Tarikh</th>
            <td>{{ Carbon::parse($complaint->created_at)->format('j M Y h:i a') }}</td>
        </tr>
    </table>
    <div class="well">
        <a href="{{ asset('aduan/list') }}" class="btn btn-default">Senarai Aduan</a>
        @if (App\Models\Complaint::canApprove())
            @if ($complaint->status == 0)
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 1]) }}"
                    class="btn btn-primary link-confirm">Ambil Maklum</a>
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 2]) }}"
                    class="btn btn-warning link-confirm">Dalam Tindakan</a>
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
                    class="btn btn-success link-confirm">Selesai</a>
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
                    class="btn btn-danger link-confirm">Ditolak</a>
            @elseif ($complaint->status == 1)
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 2]) }}"
                    class="btn btn-warning link-confirm">Dalam Tindakan</a>
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
                    class="btn btn-success link-confirm">Selesai</a>
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
                    class="btn btn-danger link-confirm">Ditolak</a>
            @elseif ($complaint->status == 2)
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 3]) }}"
                    class="btn btn-success link-confirm">Selesai</a>
                <a href="{{ action('ComplaintController@updateStatus', [$complaint->id, 4]) }}"
                    class="btn btn-danger link-confirm">Ditolak</a>
            @endif
        @endif
    </div>
@stop
