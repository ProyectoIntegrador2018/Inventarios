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



                    <a href="{{ route('view.createDevice') }}">Alta de Dispositivos</a>
                    <hr><a href="{{ route('view.inventory') }}">Inventario de Dispositivos</a>
                    <hr><a href="{{ route('view.loansList') }}">Lista de Préstamos</a>
                    <hr><a href="{{ route('view.reports') }}">Generación de Reportes</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
