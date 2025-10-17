@extends('layouts.default')
@section('content')
    <h2>
        {{ $vendor->name }}: Sejarah Kemaskini</h2>
    <table class="DT2 table table-striped table-hover table-bordered">
        <thead class="bg-blue-selangor">
            <tr>
                <th>Tarikh</th>
                <th>Keterangan</th>
                <th>Pengguna</th>
            </tr>
        </thead>
        <tbody>
            {{-- {{ dd($templates) }} --}}
            {{-- @foreach ($templates as $template)
            {{ dd($template['title']) }}
			@endforeach --}}
            @foreach ($vendor->histories as $history)
                <tr>
                    <td>{{ Carbon\Carbon::parse($history->created_at)->format('d/m/Y') }}</td>
                    <td>
                        <b>{{ $history->label }}</b>
                        @if ($history->remarks || $history->remarks != '')
                            <br> Catatan : {{ $history->remarks }} <br>
                        @endif
                        @if ($history->rejection_template_id)
						<br> Alasan :
                            <ol>
                                @foreach (json_decode($history->rejection_template_id, true) as $reject_id)
                                    @foreach ($templates as $template)
                                        @if ($template['id'] == $reject_id)
                                            <li style="text-decoration: underline;">{{ $template['title'] }}
                                            </li>
                                            {!! $template['content'] !!}
                                        @endif
                                    @endforeach
                                @endforeach
                            </ol>
                        @endif
                    </td>
                    <td>
                        @if ($history->user)
                            {{ $history->user->name }}
                        @else
                            {{ boolean_icon(false) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="well">
        <a href="{{ asset('vendors/' . $vendor->id) }}" class="btn btn-default">Maklumat Syarikat</a>
    </div>
@endsection
