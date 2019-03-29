@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Menú Principal</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        
                    @endif

                    
                    
                    <a href="{{url('/deviceCreation')}}">Alta de Dispositivos</a>
                    <hr><a href="{{url('/inventory')}}">Inventario de Dispositivos</a>
                    <hr><a href="{{url('/loansList')}}">Lista de Préstamos</a>
                    <hr><a href="{{url('/exportCSV')}}">Generación de Reportes</a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
