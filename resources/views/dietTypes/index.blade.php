@extends('layouts.app')

@section('title', 'Wszystkie rodzaje diet')

@section('content')
<div class="container">
    <h2 class="mb-4">Wszystkie rodzaje diet</h2>

    @if ($dietTypes->isEmpty())
    <p>Brak dostępnych diet.</p>
    @else
    <div class="row">
        @foreach ($dietTypes as $dietType)
        <div class="col-12 col-md-4 mb-3">
            <div class="card h-100">
                @if($dietType->image_url)
                <img src="{{ $dietType->image_url }}" class="card-img-top" alt="{{ $dietType->name }}" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $dietType->name }}</h5>
                    <a href="{{ route('dietTypes.show', $dietType->id) }}" class="btn btn-primary">Zobacz przepisy</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection