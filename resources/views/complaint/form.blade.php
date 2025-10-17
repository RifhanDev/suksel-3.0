@section('styles')
    <style>
        .fixed_width {
            resize: vertical;
        }
    </style>
@endsection
<div class="form-group required" style="{{ auth()->check() ? 'display:none' : '' }}">
    <label for="email" class="control-label col-lg-3 col-sm-3">
        Email <sup>*</sup>
    </label>
    <div class="col-lg-9 col-sm-9">
        <input type="email" name="email" class="form-control" value="{{ auth()->check() ? auth()->user()->email : '' }}"
            required>
    </div>
</div>
{!! Former::text('subject')->label('Subjek')->required() !!}
{!! Former::textarea('content')->id('content')->addClass('fixed_width')->rows(10)->label('Kandungan')->required() !!}
<div class="form-group required">
    <label for="email" class="control-label col-lg-3 col-sm-3">
         
    </label>
    <div class="col-lg-9 col-sm-9">
        <div class="g-recaptcha" data-sitekey="{{ Config::get('captcha.site') }}"></div>
    </div>
</div>

@section('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
