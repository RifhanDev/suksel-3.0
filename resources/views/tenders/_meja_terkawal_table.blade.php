@php
	$mejaTerkawal = $mejaTerkawal ?? \App\Support\TenderMejaTerkawalPresenter::for($tender);
	$mejaDocs = $mejaTerkawal->documents();
@endphp

@once
	<style>
		.meja-terkawal-table {
			margin-bottom: 0;
			width: 100%;
		}

		.meja-terkawal-table thead th {
			background: #337ab7;
			color: #fff;
			font-weight: 600;
			padding: 10px 12px;
			border: 1px solid #2e6da4;
			vertical-align: middle;
			white-space: nowrap;
		}

		.meja-terkawal-table tbody td {
			padding: 10px 12px;
			vertical-align: middle;
			border: 1px solid #ddd;
			color: #333;
			font-size: 0.875rem;
		}

		.meja-terkawal-table tbody tr:hover {
			background: #f9f9f9;
		}

		.meja-terkawal-table .col-nama {
			min-width: 220px;
		}

		.meja-terkawal-table .col-saiz {
			width: 110px;
			white-space: nowrap;
		}

		.meja-terkawal-table .col-jenis {
			width: 160px;
		}

		.meja-terkawal-table .col-tindakan {
			width: 130px;
			text-align: center;
		}
	</style>
@endonce

@if (count($mejaDocs) > 0)
	<div class="table-responsive">
		<table class="table table-bordered table-hover meja-terkawal-table">
			<thead>
				<tr>
					<th class="col-nama">Nama</th>
					<th class="col-saiz">Saiz</th>
					<th class="col-jenis">Jenis</th>
					<th class="col-tindakan">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($mejaDocs as $doc)
					<tr>
						<td class="col-nama">{{ $doc['label'] }}</td>
						<td class="col-saiz">{{ $doc['size'] ?? '-' }}</td>
						<td class="col-jenis">{{ $doc['type'] ?? '-' }}</td>
						<td class="col-tindakan">
							@php
								$downloadName = trim((string) ($doc['label'] ?? 'dokumen'));
								$ext = 'pdf';
								if (! empty($doc['type']) && str_contains((string) $doc['type'], '/')) {
									$guess = explode('/', (string) $doc['type'])[1] ?? '';
									if ($guess !== '' && $guess !== 'octet-stream') {
										$ext = $guess === 'jpeg' ? 'jpg' : $guess;
									}
								}
								if (pathinfo($downloadName, PATHINFO_EXTENSION) === '') {
									$downloadName .= '.' . $ext;
								}
							@endphp
							<a href="{{ $doc['url'] }}"
								class="btn btn-sm btn-primary"
								download="{{ $downloadName }}">Muat Turun</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@else
	<div class="p-4 text-muted small">Tiada Dokumen Meja Terkawal.</div>
@endif
