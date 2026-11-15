@extends('layouts.app')

@section('title', ($heading ?? 'Page') . ' | ' . school_detail('name'))

@section('content')
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-5 fw-bold text-primary mb-3">{{ $heading ?? 'Page' }}</h1>
                <p class="lead text-muted">{{ $note ?? 'Content coming soon.' }}</p>

                @if (session('status'))
                    <div class="alert alert-success mt-4">{{ session('status') }}</div>
                @endif

                <a href="{{ route('home') }}" class="btn btn-primary mt-4"><i class="fas fa-home me-2"></i>Back to Home</a>
            </div>
        </div>
    </div>
</section>
@endsection
