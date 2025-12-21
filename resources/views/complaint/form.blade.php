<div class="row g-2 mb-2">
    <!-- Email Field -->
    <div class="{{ auth()->check() ? 'd-none' : 'col-md-6' }}">
        @if(auth()->check())
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
        @else
            <label for="email" class="form-label">Alamat Emel <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        @endif
    </div>

    <!-- Subject Field -->
    <div class="{{ auth()->check() ? 'col-12' : 'col-md-6' }}">
        <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" required>
        @error('subject')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Content Field -->
<div class="mb-3">
    <label for="content" class="form-label">Kandungan / Butiran <span class="text-danger">*</span></label>
    <textarea name="content" id="content" rows="6" class="form-control @error('content') is-invalid @enderror" required></textarea>
    @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Recaptcha -->
<div class="mb-3">
    <div class="g-recaptcha" data-sitekey="{{ Config::get('captcha.site') }}"></div>
    @error('g-recaptcha-response')
        <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
    @enderror
</div>