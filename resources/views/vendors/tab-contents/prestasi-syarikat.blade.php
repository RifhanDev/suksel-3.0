<div class="tab-pane @if (isset($active_prestasi_tab)) active @endif" id="vf-prestasi-syarikat">
    <h2>Senarai Tender Syarikat</h2>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            @forelse ($vendor -> winningParticipations -> sortByDesc('created_at') as $participation)
            <div class="panel-heading border my-1" role="tab" id="heading">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ $loop -> index }}" aria-expanded="false" aria-controls="collapseTwo">
                        <b>{{ $loop -> index + 1 }}. {{ $participation -> tender -> ref_number }}</b>
                        - 
                        <span class="@if ($participation -> tender -> petenderPerformances -> count() > 0) text-success @else text-danger @endif">
                            <b>
                                {{ $participation -> tender -> petenderPerformances -> count() }} Rekod Penilaian
                            </b>
                        </span>
                        <br>
                        <br>
                        {{ $participation -> tender -> name }}
                    </a>
                </h4>
            </div>
            <div id="{{ $loop -> index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading">
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead class="bg-blue-selangor">
                            <tr>
                                <th>Bil.</th>
                                <th>Tarikh</th>
                                <th>Penilai</th>
                                <th>Keseluruhan Markah</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($participation -> tender -> petenderPerformances as $petenderPerformance)
                            <tr>
                                <td>{{ $loop -> index + 1 }}.</td>
                                <td>{{ Carbon\Carbon::parse($petenderPerformance -> acquisition_date) -> format('d/m/Y') }}</td>
                                <td>
                                    <b>{{ $petenderPerformance -> user -> name }}</b>
                                    <br>
                                    {{ $petenderPerformance -> user -> agency -> name }}
                                </td>
                                <td>
                                    <b>{{ number_format($petenderPerformance -> total_score, 2) }} %</b>
                                </td>
                                <td align="center">
                                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#exampleModal{{ $petenderPerformance -> id }}">
                                        Papar
                                    </a>
                                    {{-- Modal Body --}}
                                    @include('tenders.petender-performance.modal.petender-performance')
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">Tiada rekod penilaian</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <span>
                Tiada Rekod Tender yang Menang
            </span>
            @endforelse    
        </div>
    </div>
</div>