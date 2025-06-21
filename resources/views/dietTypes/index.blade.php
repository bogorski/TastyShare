@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="fw-bold text-center mb-5">Wszystkie rodzaje diet</h1>

    @if ($dietTypes->isEmpty())
    <p class="text-center">Brak dostępnych diet.</p>
    @else
    <div class="row g-4">
        @foreach ($dietTypes as $dietType)
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('dietTypes.show', $dietType->id) }}" class="text-decoration-none text-dark d-block h-100">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    @if($dietType->image_url)
                    <img src="{{ $dietType->image_url }}" class="card-img-top diet-img" alt="{{ $dietType->name }}">
                    @endif
                    <div class="card-body d-flex justify-content-center align-items-center text-center">
                        <h5 class="card-title mb-0">{{ $dietType->name }}</h5>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection