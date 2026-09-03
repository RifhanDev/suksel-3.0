<div class="tender-menu-bar d-print-none">
	{{-- Row 1: Agency link (left) + Print button (right) --}}
	<div class="tender-menu-top">
		<a href="{{ asset('agencies/' . $tender->tenderer->id) }}" class="tender-agency-link">
			<svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18" viewBox="0 0 24 24">
				<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
					d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
			</svg>
			<span class="text-capitalize">
				{{ $tender->type }} oleh <strong>{{ $tender->tenderer->name }}</strong>
			</span>
			<svg xmlns="http://www.w3.org/2000/svg" class="ms-1" width="16" height="16" viewBox="0 0 24 24">
				<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
					d="M9 6l6 6l-6 6" />
			</svg>
		</a>

		{{-- Print Button --}}
		<div class="d-flex align-items-center gap-2">
			@if (Route::currentRouteAction() == 'TendersController@vendors')
				<a href="{{ asset('tenders/' . $tender->id . '/vendors/print') }}" class="tender-menu-btn tender-menu-btn-ghost"
					target="_new">
					<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="15" height="15" viewBox="0 0 24 24">
						<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4M7 15a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2l0 -4" />
					</svg>
					Cetak
				</a>
			@else
				<a href="javascript:window.print()" class="tender-menu-btn tender-menu-btn-ghost">
					<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="15" height="15" viewBox="0 0 24 24">
						<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4M7 15a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2l0 -4" />
					</svg>
					Cetak
				</a>
			@endif
		</div>
	</div>

	{{-- Row 2: Status badges (left) + Action buttons (right) — admin only --}}
	@if (Auth::check() && !Auth::user()->hasRole('Vendor') && $tender->canUpdate())
		<div class="tender-menu-bottom">
			{{-- Status Badges --}}
			<div class="tender-status-badges">
				@if ($tender->invitation)
					<span class="badge bg-warning d-inline-flex align-items-center">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0M8 11v-4a4 4 0 1 1 8 0v4" />
						</svg>
						Tender Terhad
					</span>
				@endif
				@if ($tender->approver_id)
					<span class="badge bg-success d-inline-flex align-items-center">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M5 12l5 5l10 -10" />
						</svg>
						Disiarkan
					</span>
				@endif
				@if ($tender->publish_prices)
					<span class="badge bg-info d-inline-flex align-items-center">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M5 12l5 5l10 -10" />
						</svg>
						Carta Tender
					</span>
				@endif
				@if ($tender->publish_winner)
					<span class="badge bg-success d-inline-flex align-items-center"
						style="background: linear-gradient(135deg, #f59e0b, #d97706) !important;">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="13" height="13" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M8 21l8 0M12 17l0 4M17 3L7 3L6 13a5 5 0 0 0 5 5a5 5 0 0 0 5 -5L17 3zM6.7 9l-1.7 0M17.3 9l1.7 0" />
						</svg>
						Keputusan Diumumkan
					</span>
				@endif
			</div>

			{{-- Action Buttons --}}
			<div class="d-flex align-items-center gap-2">
				@if ($tender->canAllowEdit() && Route::currentRouteAction() != 'TendersController@edit')
					<a href="{{ asset('tenders/' . $tender->id . '/edit') }}" class="tender-menu-btn tender-menu-btn-primary">
						<svg xmlns="http://www.w3.org/2000/svg" class="me-1" width="15" height="15" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415M16 5l3 3" />
						</svg>
						Kemaskini
					</a>
				@endif

				<div class="btn-group">
					<button type="button" class="tender-menu-btn tender-menu-btn-ghost dropdown-toggle" data-bs-toggle="dropdown"
						data-toggle="dropdown" aria-expanded="false">
						Tindakan
						<svg xmlns="http://www.w3.org/2000/svg" class="ms-1" width="14" height="14" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M6 9l6 6l6-6" />
						</svg>
					</button>
					<ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="min-width: 200px;">
						@if (empty($tender->publish_prices))
							@if ($tender->canUpdate() && empty($tender->approver_id))
								<li>
									<a class="dropdown-item publish-tender" href="{{ asset('tenders/' . $tender->id . '/publish') }}">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="15" height="15" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2" d="M5 12l5 5l10 -10" />
										</svg>
										Siar Tender
									</a>
								</li>
							@endif
							@if ($tender->canCancelSiar() && $tender->approver_id)
								<li>
									<a class="dropdown-item text-danger" href="{{ asset('tenders/' . $tender->id . '/cancel') }}"
										onclick="return confirm('Batal siar tender ini? Status akan kembali ke peringkat Penyediaan Iklan.')">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="15" height="15" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2" d="M18 6l-12 12M6 6l12 12" />
										</svg>
										Batal Siar
									</a>
								</li>
							@endif
						@endif

						@if ($tender->canShowPrices())
							@if ($tender->canUpdate() && $tender->approver_id > 0 && empty($tender->publish_prices))
								<li>
									<hr class="dropdown-divider my-1">
								</li>
								<li>
									<a class="dropdown-item" href="{{ asset('tenders/' . $tender->id . '/publishPrices') }}">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="15" height="15" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2" d="M5 12l5 5l10 -10" />
										</svg>
										Umum Carta Tender
									</a>
								</li>
							@endif
							@if ($tender->canCancel() && $tender->publish_prices > 0 && empty($tender->publish_winner))
								<li>
									<a class="dropdown-item text-danger" href="{{ asset('tenders/' . $tender->id . '/publishPrices') }}">
										<svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="15" height="15" viewBox="0 0 24 24">
											<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
												stroke-width="2" d="M18 6l-12 12M6 6l12 12" />
										</svg>
										Batal Umum Carta Tender
									</a>
								</li>
							@endif
						@endif

						<li>
							<hr class="dropdown-divider my-1">
						</li>
						@if ($tender->canShowWinner() && empty($tender->publish_winner))
							<li>
								<a class="dropdown-item" href="{{ asset('tenders/' . $tender->id . '/publishWinner') }}">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="15" height="15" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
											stroke-width="2" d="M5 12l5 5l10 -10" />
									</svg>
									Umum Penender Berjaya
								</a>
							</li>
						@else
							<li>
								<a class="dropdown-item text-danger" href="{{ asset('tenders/' . $tender->id . '/publishWinner') }}">
									<svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="15" height="15" viewBox="0 0 24 24">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
											stroke-width="2" d="M18 6l-12 12M6 6l12 12" />
									</svg>
									Batal Umum Penender Berjaya
								</a>
							</li>
						@endif
					</ul>
				</div>
			</div>
		</div>
	@endif
</div>
<div class="clearfix"></div>
