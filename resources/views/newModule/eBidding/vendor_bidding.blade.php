@extends('layouts.v3.master')

@section('content')
    <div class="content-card p-4">
        <div id="vendor-bid-alert" class="alert d-none py-2 px-3 mb-3"></div>
        <div class="d-flex justify-content-end mb-3">
            <span class="badge {{ !empty($hasVendorSubmitted) ? 'bg-success' : 'bg-warning text-dark' }}">
                {{ !empty($hasVendorSubmitted) ? 'Submitted' : 'Pending Submission' }}
            </span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label small">Tarikh Mula Bidaan</label>
                <input type="text" class="form-control form-control-sm"
                    value="{{ optional($jadualBidaan->tarikh_bidaan_mula)->format('d/m/Y') }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Masa Mula Bidaan</label>
                <input type="text" class="form-control form-control-sm" value="{{ $jadualBidaan->masa_bidaan_mula }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Tarikh Tamat Bidaan</label>
                <input type="text" class="form-control form-control-sm"
                    value="{{ optional($jadualBidaan->tarikh_bidaan_tamat)->format('d/m/Y') }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Masa Tamat Bidaan</label>
                <input type="text" class="form-control form-control-sm" value="{{ $jadualBidaan->masa_bidaan_tamat }}" readonly>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="text-white text-center" style="background-color:#2d3e84;">
                    <tr>
                        <th>Item Spesifikasi</th>
                        <th width="120">Kekerapan / Kuantiti</th>
                        <th width="120">Unit Ukuran</th>
                        <th width="120">Pematuhan</th>
                        <th width="140">Harga Sebelum Bidaan</th>
                        <th width="140">Harga Bidaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vendorItems as $row)
                        <tr>
                            <td>{{ $row['spesifikasi'] }}</td>
                            <td class="text-center">{{ $row['kuantiti'] }}</td>
                            <td class="text-center">{{ $row['unit_ukuran'] }}</td>
                            <td class="text-center">{{ $row['pematuhan'] }}</td>
                            <td class="text-center">{{ $row['previous_price'] !== '' ? number_format((float) $row['previous_price'], 2) : '-' }}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm vendor-bid-price"
                                    data-item-id="{{ $row['pemilihan_item_id'] }}" min="0.01" step="0.01"
                                    value="{{ $row['bid_price'] }}" {{ $canVendorEditBid ? '' : 'readonly' }}>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tiada item bidaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
            <span class="fw-semibold">Jumlah Harga Bidaan</span>
            <input type="text" id="vendor-bid-total" class="form-control form-control-sm" style="max-width:180px;" readonly>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button type="button" class="btn btn-selangor" id="vendor-bid-submit" {{ $canVendorEditBid ? '' : 'disabled' }}>
                Hantar
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            const canEdit = @json($canVendorEditBid);
            const hasVendorSubmitted = @json(!empty($hasVendorSubmitted));
            const submitUrl = @json(route('eBidding.vendorBidaan.hantar', ['id' => $tender->id]));
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('meta[name="_token"]').attr('content');
            const $alert = $('#vendor-bid-alert');

            function showAlert(message, type) {
                $alert.removeClass('d-none alert-success alert-danger')
                    .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                    .text(message || '');
            }

            function calcTotal() {
                let sum = 0;
                $('.vendor-bid-price').each(function() {
                    const v = parseFloat($(this).val() || '0');
                    if (!Number.isNaN(v)) sum += v;
                });
                $('#vendor-bid-total').val(sum.toLocaleString('en-MY', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }

            calcTotal();
            $(document).on('input', '.vendor-bid-price', calcTotal);

            $('#vendor-bid-submit').on('click', function() {
                if (!canEdit) {
                    showAlert('Tempoh bidaan belum dibuka atau sudah tamat.', 'error');
                    return;
                }
                if (hasVendorSubmitted) {
                    showAlert('Harga bidaan telah dihantar. Sila tunggu semakan Agency Admin.', 'error');
                    return;
                }

                const items = [];
                let hasInvalid = false;
                $('.vendor-bid-price').each(function() {
                    const itemId = parseInt($(this).data('item-id'), 10);
                    const raw = ($(this).val() || '').toString().trim();
                    const price = raw === '' ? null : parseFloat(raw);
                    if (raw !== '' && (Number.isNaN(price) || price <= 0)) hasInvalid = true;
                    items.push({
                        pemilihan_item_id: itemId,
                        bid_price: price
                    });
                });

                if (hasInvalid) {
                    showAlert('Harga Bidaan mesti melebihi 0 jika diisi.', 'error');
                    return;
                }

                if (!window.confirm('Anda pasti setuju untuk hantar harga bidaan ini?')) {
                    return;
                }

                $.ajax({
                    url: submitUrl,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    },
                    data: {
                        items: items
                    },
                    success: function(resp) {
                        showAlert(resp.message || 'Harga bidaan berjaya dihantar.', 'success');
                        const nextUrl = resp?.redirect_url || "{{ route('eBidding.index') }}";
                        setTimeout(function() {
                            window.location.href = nextUrl;
                        }, 900);
                    },
                    error: function(xhr) {
                        let message = xhr?.responseJSON?.message || 'Operasi gagal. Sila cuba semula.';
                        if (xhr?.responseJSON?.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join(' ');
                        }
                        showAlert(message, 'error');
                    }
                });
            });
        });
    </script>
@endsection
