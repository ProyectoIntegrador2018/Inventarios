<!doctype html>
@extends('layouts.app')

@section('content')
  <!-- Vista principal -->
  <main role="main" class="container bg-white">
  <br>
    <div class="content">
      <div class="col p-5">
        <h1 class="text-center">Catálogo de dispositivos</h1>
      </div>

      @include('layouts.search-bar')

      <div class="col">
        <!-- <div class="card-deck"> -->
          <!-- <div class="card-deck"> -->
          @if($quantity == 0)
            <div class="card">
              <div class="card-header">
                Almacén vacío
              </div>
              <div class="card-body">
                <h5 class="card-title">Sin dispositivos</h5>
                <p class="card-text">No hay ningún dispositivo registrado en los almacénes</p>
                @auth
                <a href="{{ route('view.createDevice') }}" class="btn btn-primary">Dar de alta un dispositivo</a>
                @endauth
              </div>
            </div>
          @endif
          @for($i = 1; $i <= $quantity; $i++)
            @if(($i % 4) == 1)
              <div class="card-deck">
            @endif
            <div class="col-md-5 col-lg-3">
            <div class="card">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{$devices[$i-1]->name}}</h5>
                <div><h6 class="card-subtitle mb-2 text-muted">{{$devices[$i-1]->brand}}</h6></div>
                <div><h6 class="card-subtitle mb-2 text-muted">{{$devices[$i-1]->model}}</h6></div>
                <!-- <p class="card-text">{{$devices[$i-1]->model}}</p> -->
                <div class="row mt-auto">
                  @auth
                  <div class="col">
                    <a href="{{ route('view.deviceDetails', $devices[$i-1]->model) }}" class="card-link">Detalles</a>
                  </div>
                  @endauth
                  <div class="col">
                    <a href="{{ route('view.requestLoan', $devices[$i-1]->model) }}" class="card-link">Apartar</a>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <small class="text-muted">{{$devices[$i-1]->quantity}} disponibles</small>
              </div>
            </div>
            </div>
            @if(($i % 4) == 0)
              </div>
              <br>
            @endif
          @endfor
          </div>
        <!-- </div> -->
        <!-- <br> -->
      </div>
    </div>
    <br>
  </main>
@endsection
