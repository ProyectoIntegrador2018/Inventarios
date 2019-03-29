<!doctype html>
@extends('layouts.app')

@section('content')
  <!-- Vista principal -->
  <main role="main" class="container bg-white">
  <br>
    <div class="content">
      <div class="col">
        <h1 class="text-center">Inventario de dispositivos</h1>
      </div>
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
                <a href="{{url('/deviceCreation')}}" class="btn btn-primary">Dar de alta un dispositivo</a>
              </div>
            </div>
          @endif
          @for($i = 1; $i <= $quantity; $i++)
            @if(($i % 4) == 1)
              <div class="card-deck">
            @endif
            <div class="col-md-3">
            <div class="card">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{$devices[$i-1]->name}}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{$devices[$i-1]->brand}}</h6>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content</p>
                <div class="row mt-auto">
                  <div class="col">
                    <a href="{{url('/deviceDetails/'.$devices[$i-1]->model)}}" class="card-link float-left">Detalles</a>
                  </div>
                  <div class="col">
                    <a href="{{url('/requestLoan/'.$devices[$i-1]->model)}}" class="card-link float-right">Apartar</a>
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
