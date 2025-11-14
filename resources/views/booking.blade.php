@extends('layout')

@section('title','Programează-te - DariaBeauty')

@section('content')
<div class="container py-5">
  <h1 class="mb-4">Programează-te</h1>
  <p class="text-muted">Alege un specialist sau un serviciu și continuă la programare.</p>

  <h3 class="mt-4">Servicii populare</h3>
  <div class="row g-3">
    @foreach($services as $service)
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-title">{{ $service->name }}</h6>
          <div class="small text-muted">{{ $service->specialist->name ?? 'Specialist' }}</div>
          <div class="fw-bold">{{ $service->formatted_price }}</div>
          @if($service->specialist)
          <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('specialists.booking',['slug'=>$service->specialist->slug ?? 'specialist','service_id'=>$service->id]) }}">Programează-te</a>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <h3 class="mt-5">Specialiști disponibili</h3>
  <div class="row g-3">
    @foreach($specialists as $spec)
    <div class="col-md-3">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-title">{{ $spec->name }}</h6>
          <div class="small text-muted">{{ $spec->sub_brand }}</div>
          <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('specialists.booking',['slug'=>$spec->slug ?? 'specialist']) }}">Programează-te</a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection