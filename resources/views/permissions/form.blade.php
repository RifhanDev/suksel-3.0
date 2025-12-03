<div class="card modern-form-card">
	<div class="card-header">
		<h3 class="card-title">
			<i class="ti ti-key"></i>
			Maklumat Kebenaran
		</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<!-- Group Name Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label required">
					<i class="ti ti-folder"></i>
					Nama Kumpulan
				</label>
				<?php
				$group_name = Former::text('group_name')
				    ->label(false)
				    ->placeholder('Masukkan nama kumpulan')
				    ->useDatalist(App\Permission::select('id', 'group_name')->groupBy('group_name')->get(), 'group_name')
				    ->required()
				    ->class('form-control');
				if (isset($permission)) {
				    $group_name->disabled();
				}
				?>
				{!! $group_name !!}
			</div>

			<!-- Name Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label required">
					<i class="ti ti-key"></i>
					Nama
				</label>
				<?php
				$name = Former::text('name')->label(false)->placeholder('Masukkan nama kebenaran')->required()->class('form-control');
				if (isset($permission)) {
				    $name->disabled();
				}
				?>
				{!! $name !!}
			</div>

			<!-- Display Name Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label required">
					<i class="ti ti-file-text"></i>
					Keterangan
				</label>
				{!! Former::text('display_name')->label(false)->placeholder('Masukkan keterangan kebenaran')->required()->class('form-control') !!}
			</div>
		</div>
	</div>
</div>
