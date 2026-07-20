# Migration Guide: Angular.js to Vanilla JavaScript

This guide explains how to update the blade templates to use the new vanilla JavaScript modules instead of Angular.js.

## Step 1: Update Scripts Section

### Remove Angular.js (OLD):
```html
@section('scripts-closeTemporary')
    <script src="{{ asset('js/definitions.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
@endsection
```

### Add Vanilla JS Modules (NEW):
```html
@section('scripts-closeTemporary')
    <script src="{{ asset('js/definitions.js') }}"></script>
    <!-- Vanilla JS Modules -->
    <script src="{{ asset('js/ajax-loader.js') }}"></script>
    <script src="{{ asset('js/item-controller.js') }}"></script>
    <script src="{{ asset('js/two-way-binding.js') }}"></script>
    <script src="{{ asset('js/form-validator.js') }}"></script>
    <script src="{{ asset('js/percentage-calculator.js') }}"></script>
    <script src="{{ asset('js/vendor-form-init.js') }}"></script>
@endsection
```

## Step 2: Update Container Div

### Remove Angular Directives (OLD):
```html
<div ng-app="vendor" data-show-mode="{{strstr(Route::currentRouteName(), 'show') ? 'true' : 'false'}}">
```

### Use Plain HTML (NEW):
```html
<div data-show-mode="{{strstr(Route::currentRouteName(), 'show') ? 'true' : 'false'}}">
```

## Step 3: Update Tab Containers

### Remove Angular Controller (OLD):
```html
<div class="tab-pane" id="vf-shareholders" ng-controller="ItemController"
     <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/shareholders') }}" <?php } ?>>
```

### Use Data Attributes (NEW):
```html
<div class="tab-pane" id="vf-shareholders"
     data-entity-name="shareholder"
     <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/shareholders') }}" <?php } ?>>
```

**Apply this change to all tab containers:**
- `#vf-shareholders` → `data-entity-name="shareholder"`
- `#vf-directors` → `data-entity-name="directors"`
- `#vf-contacts` → `data-entity-name="contact"`
- `#vf-awards` → `data-entity-name="award"`
- `#vf-assets` → `data-entity-name="asset"`
- `#vf-projects` → `data-entity-name="project"`
- `#vf-products` → `data-entity-name="product"`

## Step 4: Update Hidden Inputs for Deleted Items

### Remove Angular Directives (OLD):
```html
<input type="hidden" name="deleted[shareholder][]" ng-repeat="item in deletedItems" ng-value="item.id">
```

### This will be handled automatically (NEW):
The `ItemController` will automatically create a hidden container for deleted items. You can remove these lines completely.

## Step 5: Update Table Headers

### Add data-field Attributes (NEW):
```html
<thead>
    <tr>
        <th data-field="name">Nama</th>
        <th data-field="identity">IC / Pasport</th>
        <th data-field="nationality">Kewarganegaraan</th>
        <th data-field="bumiputera_status">Taraf</th>
        <th data-field="actions" ng-if="!show" width="100" class="text-center">Tindakan</th>
    </tr>
</thead>
```

**Note:** Keep the `ng-if="!show"` for now - we'll handle this with the data-show-mode attribute.

## Step 6: Update Table Body

### Remove Angular Directives (OLD):
```html
<tbody>
    <tr ng-repeat="item in items" ng-class="item == editingItem && 'editing'">
        <td ng-bind="item.name"></td>
        <td ng-bind="item.identity"></td>
        <td ng-bind="item.nationality"></td>
        <td ng-bind="item.bumiputera_status"></td>
        <td class="text-center" ng-if="!show">
            <input type="hidden" name="shareholder[id][]" ng-value="item.id">
            <input type="hidden" name="shareholder[name][]" ng-value="item.name">
            <input type="hidden" name="shareholder[identity][]" ng-value="item.identity">
            <input type="hidden" name="shareholder[nationality][]" ng-value="item.nationality">
            <input type="hidden" name="shareholder[bumiputera_status][]" ng-value="item.bumiputera_status">
            <div ng-show="item != editingItem">
                <button type="button" class="btn btn-info btn-sm" ng-click="edit(item)">...</button>
                <button type="button" class="btn btn-danger btn-sm" ng-click="remove($index)">...</button>
            </div>
            <div ng-show="item == editingItem">
                <span class="badge text-bg-warning">Editing</span>
            </div>
        </td>
    </tr>
</tbody>
```

### Use Empty tbody (NEW):
```html
<tbody>
    <!-- Rows will be generated dynamically by ItemController -->
</tbody>
```

**The `ItemController` will automatically:**
- Render rows from loaded data
- Add hidden inputs for form submission
- Add edit/delete buttons
- Handle editing state

## Step 7: Update Footer Inputs

### Remove Angular Directives (OLD):
```html
<tfoot ng-if="!show">
    <tr ng-keyup="setHasNewItem()" style="background: #f8fafc;">
        <td><input class="form-control input-sm" ng-keypress="handleKeypress($event)"
                   ng-model="newItem.name" ng-keyup="newItem.name=newItem.name.toUpperCase()"
                   type="text" placeholder="Nama Penuh"></td>
        <td><input class="form-control input-sm" ng-keypress="handleKeypress($event)"
                   ng-model="newItem.identity" type="text" placeholder="IC / Passport"></td>
        <td>
            <select class="form-control input-sm" name="nat" ng-model="newItem.nationality">
                @foreach(App\Vendor::$nationalities as $key => $value)
                    <option value="{{ $key }}" {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </td>
        <td><select class="form-control input-sm" ng-model="newItem.bumiputera_status" ng-options="type for type in shareHolderTypes"></select></td>
        <td class="text-center">
            <button type="button" class="btn btn-primary btn-sm" ng-click="save()">...</button>
            <button type="button" class="btn btn-secondary btn-sm" ng-click="clear()">...</button>
        </td>
    </tr>
</tfoot>
```

### Add data-field and data-action Attributes (NEW):
```html
<tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
    <tr style="background: #f8fafc;">
        <td><input class="form-control input-sm" data-field="name" type="text" placeholder="Nama Penuh"></td>
        <td><input class="form-control input-sm" data-field="identity" type="text" placeholder="IC / Passport"></td>
        <td>
            <select class="form-control input-sm" name="nat" data-field="nationality">
                @foreach(App\Vendor::$nationalities as $key => $value)
                    <option value="{{ $key }}" {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </td>
        <td><select class="form-control input-sm" data-field="bumiputera_status">
            <!-- Options will be populated by vendor-form-init.js -->
        </select></td>
        <td class="text-center">
            <button type="button" class="btn btn-primary btn-sm" data-action="save">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </button>
            <button type="button" class="btn btn-secondary btn-sm" data-action="clear">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </td>
    </tr>
</tfoot>
```

## Step 8: Update Percentage Inputs

### Remove Angular Directives (OLD):
```html
<td>
    <div class="modern-input-group">
        <input ng-init="percentages.bumi=({{ Request::old('bumi_percentage', isset($vendor) ? $vendor->bumi_percentage : 0) }} || 0)"
               name="bumi_percentage" ng-model="percentages.bumi" min="0" type="number" class="form-control">
        <div class="addon">%</div>
    </div>
</td>
```

### Simplify (NEW):
```html
<td>
    <div class="modern-input-group">
        <input name="bumi_percentage" min="0" type="number" class="form-control"
               value="{{ Request::old('bumi_percentage', isset($vendor) ? $vendor->bumi_percentage : 0) }}">
        <div class="addon">%</div>
    </div>
</td>
```

**Apply to:**
- `bumi_percentage`
- `nonbumi_percentage`
- `foreigner_percentage`

The total will be calculated automatically by `ShareholderPercentageCalculator`.

## Step 9: Remove Global Angular Variables

### Remove these lines from vendor/form.blade.php (OLD):
```html
<script>
    var isAdmin = {{(Auth::user() && !Auth::user()->hasRole('Vendor') ? 'true' : 'false')}};
    var show = {{strstr(Route::currentRouteName(), 'show') ? 'true' : 'false'}};
    var shareHolderTypes = ['Bumiputera', 'Bukan Bumiputera', 'Warga Asing'];
    @if(Request::old('shareholder'))var inputOldShareholdes = {{json_encode(Request::old('shareholder'))}}; @endif
</script>
```

### Keep only this (NEW):
```html
<script>
    var nationalities = @json(App\Vendor::$nationalities);
</script>
```

The `shareHolderTypes` are now defined in `vendor-form-init.js`.

## Step 10: Update Show Mode Conditionals

### Replace Angular ng-if (OLD):
```html
<th ng-if="!show" width="100" class="text-center">Tindakan</th>
```

### Use PHP conditionals (NEW):
```html
<?php if(!strstr(Route::currentRouteName(), 'show')) { ?>
    <th width="100" class="text-center">Tindakan</th>
<?php } ?>
```

## Complete Example: Shareholders Table

Here's a complete example of the migrated shareholders table:

```html
<div class="tab-pane" id="vf-shareholders" data-entity-name="shareholder"
     <?php if(isset($vendor)) { ?> data-remote="{{ asset('vendor/'.$vendor->id.'/shareholders') }}" <?php } ?>>

    <table class="clean-table">
        <thead>
            <tr>
                <th data-field="name">Nama</th>
                <th data-field="identity">IC / Pasport</th>
                <th data-field="nationality">Kewarganegaraan</th>
                <th data-field="bumiputera_status">Taraf</th>
                <?php if(!strstr(Route::currentRouteName(), 'show')) { ?>
                    <th data-field="actions" width="100" class="text-center">Tindakan</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <!-- Rows generated by ItemController -->
        </tbody>
        <tfoot <?php if(strstr(Route::currentRouteName(), 'show')) { ?>style="display:none;"<?php } ?>>
            <tr style="background: #f8fafc;">
                <td><input class="form-control input-sm" data-field="name" type="text" placeholder="Nama Penuh"></td>
                <td><input class="form-control input-sm" data-field="identity" type="text" placeholder="IC / Passport"></td>
                <td>
                    <select class="form-control input-sm" name="nat" data-field="nationality">
                        @foreach(App\Vendor::$nationalities as $key => $value)
                            <option value="{{ $key }}" {{ $key == 'MALAYSIAN' ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </td>
                <td><select class="form-control input-sm" data-field="bumiputera_status"></select></td>
                <td class="text-center">
                    <button type="button" class="btn btn-primary btn-sm" data-action="save">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-action="clear">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Percentage Summary Section -->
    <h4 style="margin-top: 2rem; font-size: 1rem; font-weight: 700;">Ringkasan Pegangan Saham <sup>*</sup></h4>
    <table class="clean-table">
        <thead>
            <tr>
                <th>Bumiputera</th>
                <th>Bukan Bumiputera</th>
                <th>Warga Asing</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="modern-input-group">
                        <input name="bumi_percentage" min="0" type="number" class="form-control"
                               value="{{ Request::old('bumi_percentage', isset($vendor) ? $vendor->bumi_percentage : 0) }}">
                        <div class="addon">%</div>
                    </div>
                </td>
                <td>
                    <div class="modern-input-group">
                        <input name="nonbumi_percentage" min="0" type="number" class="form-control"
                               value="{{ Request::old('nonbumi_percentage', isset($vendor) ? $vendor->nonbumi_percentage : 0) }}">
                        <div class="addon">%</div>
                    </div>
                </td>
                <td>
                    <div class="modern-input-group">
                        <input name="foreigner_percentage" min="0" type="number" class="form-control"
                               value="{{ Request::old('foreigner_percentage', isset($vendor) ? $vendor->foreigner_percentage : 0) }}">
                        <div class="addon">%</div>
                    </div>
                </td>
                <td>
                    <div class="modern-input-group">
                        <input class="form-control" disabled="disabled">
                        <div class="addon">%</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

## Testing Checklist

After migration, test the following:

- [ ] Load page - no JavaScript errors
- [ ] Data loads from AJAX (if vendor exists)
- [ ] Add new row - saves to table
- [ ] Edit row - populates inputs and updates
- [ ] Delete row - removes with confirmation
- [ ] Clear button - clears inputs
- [ ] Enter key - saves item
- [ ] Percentage calculation - updates total
- [ ] Percentage validation - shows error if not 100%
- [ ] Form validation - shows errors for required fields
- [ ] Uppercase inputs - converts to uppercase
- [ ] Show mode - hides edit/delete/add controls
- [ ] Form submission - includes hidden inputs
- [ ] Deleted items - tracked in hidden inputs

## Rollback Plan

If you need to rollback:

1. Restore the Angular.js script tags
2. Restore the `ng-` directives in HTML
3. Remove the new vanilla JS files

Keep a backup of the original `vendor/form.blade.php` before making changes.

## Performance Benefits

The new vanilla JS implementation:
- ✅ **Smaller bundle size** - No Angular.js framework (~150KB saved)
- ✅ **Faster load time** - Less JavaScript to parse
- ✅ **Better maintainability** - Modular, well-documented code
- ✅ **Modern JavaScript** - ES6+ features
- ✅ **No dependencies** - Pure vanilla JS

## Support

For issues or questions, refer to:
- `VANILLA-JS-README.md` - Detailed documentation
- Browser console - Check for JavaScript errors
- `window.VendorForm` - Inspect controllers and state
