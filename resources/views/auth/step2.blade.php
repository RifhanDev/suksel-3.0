@extends('layouts.default')
@section('content')
<h2>Step 2: Fill in vendor details</h2>
<hr>
<?php $current_step = 2; ?>
@include('auth.registration_steps')
<br>
<br>
{{Former::open(action('AuthController@step2', $company->registration_code))}}
    
    {{Former::text('name')
        ->label('Company Name')
        ->required() }}
    
    {{Former::textarea('address')
        ->label('Company Address')
        ->rows(4)
        ->required() }}
    
    {{Former::text('telephone')
        ->label('Company Telephone')
        ->required() }}
    
    {{Former::text('fax')
        ->label('Company Fax')
        ->required() }}
    
    <div class="form-group required">
        <label for="submission_location_address" class="control-label col-lg-2 col-sm-2">Company Certification <sup>*</sup></label>
        <div class="col-lg-10 col-sm-10">
            <div id="certificates"></div>
        </div>
        <input type="hidden" id="certification-input" name="certifications">
    </div>

    <div class="form-group required">
        <label for="upload-document" class="control-label col-lg-2 col-sm-2">Company Documents <sup>*</sup></label>
        <div class="col-lg-10 col-sm-10">
            {{Company::advanceMultiUploader(20, 'image/*')}}
            <span class="help-block">
                <ul>
                    <li>Form 49</li>
                    <li>Form 32</li>
                    <li>Form 25</li>
                    <li>Form 9</li>
                    <li>Form MAA</li>
                </ul>
            </span>
        </div>
    </div>

    {{Former::submit('Submit')
        ->class('btn btn-primary btn-raised')}}

{{Former::close()}}
<br>
@stop
