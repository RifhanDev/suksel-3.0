<h3>Senarai Prestasi Syarikat</h3>
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
        @forelse ($tender -> petenderPerformances as $petenderPerformance)
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
                    <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal{{ $petenderPerformance -> id }}">
                        Papar
                    </button>
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