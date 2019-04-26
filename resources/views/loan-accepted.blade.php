@extends('layouts.app')
@section('content')
    <h1>Responsabilidad aceptada</h1>
    <div>Identificador del préstamo: <i>{{$loanID}}</i></div>
    <br>
    <div>Resultado de la decisión:</div>
    <div><i>{{$message}}</i></div>
    <br>
    <div>Puede cerrar esta ventana.</div>
@endsection