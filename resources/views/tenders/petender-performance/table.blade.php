<div class="tender-tab-card mt-3">
    <div class="card-header">
        <h3 class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3.5 5.5l1.5 1.5l2.5 -2.5M3.5 11.5l1.5 1.5l2.5 -2.5M3.5 17.5l1.5 1.5l2.5 -2.5M11 6l9 0M11 12l9 0M11 18l9 0" />
            </svg>
            Senarai Prestasi Syarikat
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table perf-table mb-0">
                <thead>
                    <tr>
                        <th>Bil.</th>
                        <th>Tarikh</th>
                        <th>Penilai</th>
                        <th>Keseluruhan Markah</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tender->petenderPerformances as $petenderPerformance)
                        <tr>
                            <td>{{ $loop->index + 1 }}.</td>
                            <td>{{ Carbon\Carbon::parse($petenderPerformance->acquisition_date)->format('d/m/Y') }}
                            </td>
                            <td>
                                <b>{{ $petenderPerformance->user->name }}</b>
                                <br>
                                <small class="text-muted">{{ $petenderPerformance->user->agency->name }}</small>
                            </td>
                            <td>
                                <span class="fw-bold">{{ number_format($petenderPerformance->total_score, 2) }}
                                    %</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal{{ $petenderPerformance->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="16" height="16"
                                        viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                    </svg>
                                    Papar
                                </button>
                                {{-- Modal Body --}}
                                @include('tenders.petender-performance.modal.petender-performance')
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Tiada rekod penilaian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
