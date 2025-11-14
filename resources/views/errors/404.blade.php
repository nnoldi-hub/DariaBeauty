@extends('layout')

@section('title', 'Pagina nu a fost găsită - 404')

@section('content')
<div class="container" style="padding-top:140px; padding-bottom:100px;">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 mb-3">404</h1>
            <h2 class="h4 mb-4">Pagina nu a fost găsită</h2>
            <p class="text-muted mb-4">Ne pare rău, pagina pe care o cauți nu există sau a fost mutată.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Înapoi la acasă</a>
        </div>
    </div>
</div>
@endsection
