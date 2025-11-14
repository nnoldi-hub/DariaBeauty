@extends('layout')

@section('title','Programează-te - '.$specialist->name)

@section('content')
<div class="container py-5">
  <h1 class="mb-4">Programează-te la {{ $specialist->name }}</h1>
  <form method="POST" action="#">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Alege serviciul</label>
        <select class="form-select" name="service_id" required>
          @foreach($services as $service)
            <option value="{{ $service->id }}" {{ optional($selectedService)->id === $service->id ? 'selected' : '' }}>
              {{ $service->name }} - {{ $service->formatted_price }} ({{ $service->formatted_duration }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Data</label>
        <input type="date" name="date" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Ora</label>
        <input type="time" name="time" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Adresă</label>
        <input type="text" name="address" class="form-control" placeholder="Strada, număr, bloc, etc.">
      </div>
      <div class="col-12">
        <button class="btn btn-primary">Trimite solicitarea</button>
      </div>
    </div>
  </form>
</div>
@endsection