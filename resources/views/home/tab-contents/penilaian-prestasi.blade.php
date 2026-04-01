<div class="tab-pane fade @if (isset($active_prestasi_tab)) show active @endif" id="db-penilaian-prestasi" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold text-dark m-0">Senarai Tender Syarikat</h5>
    </div>

    <div class="accordion" id="accordionPrestasi">
        @forelse (Auth::user()->vendor->winningParticipations->sortByDesc('created_at') as $participation)
            <div class="accordion-item border rounded-3 mb-3 overflow-hidden shadow-sm">
                <h2 class="accordion-header" id="heading{{ $loop->index }}">
                    <button class="accordion-button collapsed p-3 bg-light" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false"
                        aria-controls="collapse{{ $loop->index }}">
                        <div class="d-flex flex-column gap-1 w-100 pe-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">{{ $loop->index + 1 }}.
                                    {{ $participation->tender->ref_number }}</span>
                                <span
                                    class="badge @if ($participation->tender->petenderPerformances->count() > 0) bg-success @else bg-danger @endif bg-opacity-10 @if ($participation->tender->petenderPerformances->count() > 0) text-success @else text-danger @endif rounded-pill px-3 py-2 fw-bold border @if ($participation->tender->petenderPerformances->count() > 0) border-success @else border-danger @endif">
                                    {{ $participation->tender->petenderPerformances->count() }} Rekod Penilaian
                                </span>
                            </div>
                            <span class="text-muted small lh-sm"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $participation->tender->name }}</span>
                        </div>
                    </button>
                </h2>
                <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse"
                    aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#accordionPrestasi">
                    <div class="accordion-body p-0 border-top">
                        <div class="table-responsive">
                            <table class="table table-modern table-hover align-middle mb-0 w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase text-muted small fw-bold py-3 ps-4"
                                            style="width: 5%;">Bil.</th>
                                        <th class="text-uppercase text-muted small fw-bold py-3">Tarikh</th>
                                        <th class="text-uppercase text-muted small fw-bold py-3 text-center">Keseluruhan
                                            Markah</th>
                                        <th class="text-uppercase text-muted small fw-bold py-3 pe-4 text-center"
                                            style="width: 15%;">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($participation->tender->petenderPerformances as $petenderPerformance)
                                        <tr>
                                            <td class="ps-4 text-muted">{{ $loop->index + 1 }}.</td>
                                            <td class="text-dark fw-bold">
                                                <i class="icon-calendar me-1 text-muted"></i>
                                                {{ Carbon\Carbon::parse($petenderPerformance->acquisition_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border p-2 fw-bold"
                                                    style="font-size: 0.85rem;">
                                                    {{ number_format($petenderPerformance->total_score, 2) }} %
                                                </span>
                                            </td>
                                            <td class="pe-4 text-center">
                                                <button type="button"
                                                    class="btn-action btn-action-blue w-100 justify-content-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal{{ $petenderPerformance->id }}">
                                                    Papar
                                                </button>
                                                {{-- Modal Body --}}
                                                @include('tenders.petender-performance.modal.petender-performance')
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                Tiada rekod penilaian
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info d-flex align-items-center gap-2">
                <i class="icon-info"></i> Tiada Rekod Tender yang Menang
            </div>
        @endforelse
    </div>
</div>
