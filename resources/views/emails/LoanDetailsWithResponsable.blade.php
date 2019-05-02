<!DOCTYPE html>
<html>
    <head>
        <title>Referencia en un préstamo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div>Estimado(a), <strong>{{$responsableName}}</strong></div>
        <br>
        <div>El alumno <strong>{{$applicant}}</strong> con la matrícula <strong>{{$applicantID}}</strong> lo ha referido como profesor(a) responsable en el siguiente préstamo:</div>
        <br>
        <div>Nombre del dispositivo: <strong>{{$sendModelName}}</strong></div>
        <br>
        <div>Cantidad: <strong>{{$quantity}}</strong></div>
        <br>
        <div>Motivo de solicitud: <strong>{{$reason}}</strong></div>
        <br>
        <div>Del {{$startDate}} al {{$endDate}}</div>
        <br>
        <div>A continuación puede hacer uso de los siguientes enlaces para aceptar o rechazar la responsabilidad de este préstamo:</div>
        <br>
        <div>
            <!-- <a href='http://127.0.0.1:8000/acceptLoanResponsability/{{$loanID}}'>Aceptar responsabilidad del préstamo</a> -->
            <a href='http://inventariosdecomputacion.herokuapp.com/acceptLoanResponsability/{{$loanID}}'>Aceptar responsabilidad del préstamo</a>
            <br>
            <br>
            <!-- <a href='http://127.0.0.1:8000/declineLoanResponsability/{{$loanID}}'>Declinar responsabilidad del préstamo</a> -->
            <a href='http://inventariosdecomputacion.herokuapp.com/declineLoanResponsability/{{$loanID}}'>Declinar responsabilidad del préstamo</a>
        </div>
        <br>
        <div>Saludos,</div>
        <br>
        <div>Laboratorio de Inventarios Tec</div>
    </body>
</html>
