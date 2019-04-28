<!DOCTYPE html>
<html>
    <head>
        <title>Préstamo próximo a finalizar</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div>Estimado(a), <strong>{{$name}}</strong></div>
        <br>
        <div>El préstamo que ha solicitado con el identificador <strong>{{$id}}</strong> se encuentra próximo a finalizar, siendo <strong>{{$end_date}}</strong> la fecha establecida de entrega.</div>
        <br>
        <div>Los horarios de entrega en los laboratorios son de <strong>9:00 AM</strong> a <strong>6:00 PM</strong>.</div>
        <br>
        <div>Si tienes dudas sobre la información del préstamo puedes volver a consultar la información con el identificador proporcionado en la siguiente liga:</div>
        <br>
        <a href="http://127.0.0.1:8000/buscar-prestamo">Mis préstamos<a>
        <br><br>
        <div>Saludos,</div>
        <br>
        <div>Laboratorio de Inventarios Tec</div>
    </body>
</html>
