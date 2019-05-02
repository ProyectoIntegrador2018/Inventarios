<!DOCTYPE html>
<html>
    <head>
        <title>Tu préstamo ha sido cancelado</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div>Estimado(a), <strong>{{$applicant}}</strong></div>
        <br>
        <div>El préstamo que ha solicitado con el identificador <strong>{{$loanID}}</strong> ha sido cancelado por el administrador del almacén.</div>
        <br>
        <!-- <div>Puedes volver a solicitar un préstamo mediante el siguiente enlace: <a href="http://127.0.0.1:8000/dispositivos">Inventario de Dispositivos<a></div> -->
        <div>Puedes volver a solicitar un préstamo mediante el siguiente enlace: <a href="http://inventariosdecomputacion.herokuapp.com/dispositivos">Inventario de Dispositivos<a></div>
        <br>
        <div>Los horarios de los laboratorios son de <strong>9:00 AM</strong> a <strong>6:00 PM</strong>.</div>
        <br>
        <div>Si tienes dudas sobre la información del préstamo puedes volver a consultar la información con el identificador proporcionado en la siguiente liga:</div>
        <br>
        <!-- <a href="http://127.0.0.1:8000/buscar-prestamo">Mis préstamos<a> -->
        <a href="http://inventariosdecomputacion.herokuapp.com/buscar-prestamo">Mis préstamos<a>
        <br><br>
        <div>Saludos,</div>
        <br>
        <div>Laboratorio de Inventarios Tec</div>
    </body>
</html>
