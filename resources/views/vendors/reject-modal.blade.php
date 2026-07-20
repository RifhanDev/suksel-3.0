<div id="rejectForm" class="d-none">
    <form id="myForm">
        
        <!-- 1. Manual Input -->
        <div class="mb-3">
            <label class="form-label fw-bold text-secondary small text-uppercase">Alasan Penolakan Manual</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Sila nyatakan sebab penolakan di sini..."></textarea>
        </div>

        @if ($templates && count($templates) > 0)
            <!-- 2. Divider -->
            <div class="d-flex align-items-center my-3">
                <hr class="flex-grow-1 text-muted">
                <span class="px-3 text-muted small fw-bold text-uppercase bg-white">Atau Pilih Templat</span>
                <hr class="flex-grow-1 text-muted">
            </div>

            <!-- 3. Template List -->
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary small text-uppercase">Templat Penolakan</label>
                
                <!-- Scrollable Container -->
                <div class="border rounded bg-light p-3" style="max-height: 200px; overflow-y: auto;">
                    @foreach ($templates as $template)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="cb{{ $template->id }}" name="template" value="{{ $template->id }}">
                            <label class="form-check-label text-dark" for="cb{{ $template->id }}" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="right" 
                                   title="{{ $template->content }}">
                                {{ $template->title }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="form-text small mt-1 text-muted">
                    <i class="ti ti-info-circle"></i> Anda boleh memilih lebih daripada satu templat.
                </div>
            </div>
        @endif

    </form>
</div>