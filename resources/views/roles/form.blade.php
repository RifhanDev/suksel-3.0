<div class="form-group required">
	<label for="name" class="control-label col-lg-3 col-sm-3">Nama Peranan <sup>*</sup></label>
	<div class="col-lg-9 col-sm-9">
	<input class="form-control" required="true"<?php if(isset($role)) : ?> disabled="disabled"<?php endif; ?>id="name" type="text" name="name" value="<?php echo isset($role) ? $role->name : ''; ?>">
	</div>
</div>

@include('permissions.matrix')
