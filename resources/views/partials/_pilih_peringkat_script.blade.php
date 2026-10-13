{{--
	Pengendali untuk _pilih_peringkat_modal. Letak dalam bahagian skrip halaman,
	selepas jQuery, Bootstrap dan SweetAlert2 dimuatkan.
--}}
<script>
	$(function () {
            // ── Modal Pilih Peringkat ──────────────────────────────────────
            var _lantikUrl   = '';
            var _tenderUuid  = '';

            // Capture trigger data when the Lantik modal opens
            $(document).on('click', '.btn-pilih-peringkat', function () {
                _lantikUrl  = $(this).data('lantik-url');
                _tenderUuid = $(this).data('tender-uuid');
                $('#modalPilihPeringkat').data('tender-uuid', _tenderUuid);

                // Always reset to 1 Peringkat as default
                $('#peringkatOptionGroup .peringkat-option').removeClass('selected');
                $('#peringkatOptionGroup .peringkat-option[data-value="1"]').addClass('selected');
                $('#peringkatOptionGroup input[value="1"]').prop('checked', true);
            });

            // Option card selection
            $(document).on('click', '.peringkat-option', function () {
                $('#peringkatOptionGroup .peringkat-option').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
            });

            // Teruskan — show Swal confirmation, then redirect
            $('#btnTeruskanLantik').on('click', function () {
                var peringkat  = $('#peringkatOptionGroup input[name="modal_kaedah"]:checked').val();
                var tenderUuid = $('#modalPilihPeringkat').data('tender-uuid');
                var label      = peringkat === '1' ? '1 Peringkat' : '2 Peringkat';

                // Dismiss the Bootstrap modal, then show Swal once hidden
                var bsModalEl = document.getElementById('modalPilihPeringkat');
                var bsModal   = bootstrap.Modal.getOrCreateInstance(bsModalEl);

                $(bsModalEl).one('hidden.bs.modal', function () {
                    Swal.fire({
                        title: 'Sahkan Pilihan Peringkat',
                        html: 'Anda pasti ingin memilih <strong>' + label + '</strong>?<br><small class="text-muted">Pilihan ini tidak boleh diubah selepas disimpan.</small>',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#c41e3a',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Teruskan',
                        cancelButtonText: 'Batal',
                        reverseButtons: false
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            if (peringkat === '1') {
                                window.location.href = '{{ route("pelantikanJawatankuasaSatuPeringkat") }}?tender=' + tenderUuid;
                            } else {
                                window.location.href = _lantikUrl;
                            }
                        } else {
                            // User cancelled — re-open the selection modal
                            bsModal.show();
                        }
                    });
                });

                bsModal.hide();
            });
	});
</script>
