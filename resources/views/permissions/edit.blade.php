@extends('layouts.v3.master')

@section('content')
    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Kebenaran</h3>
            <p class="text-muted small m-0">Kemaskini maklumat kebenaran sistem.</p>
        </div>
    </div>

    <form action="{{ url('permissions/' . $permission->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('permissions.form')
        <div class="content-card mt-3">
            @include('permissions.actions-footer', ['has_submit' => true])
        </div>
    </form>
@endsection