<!DOCTYPE html>
<html lang="en" dir="ltr" style="height:100%">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Detalle de dispositivo</title>
</head>

<body class="bg-light" style="height:100%">
    <main role="main" class="container bg-white" style="height:100%">
        <br><a class="btn btn-primary" href="{{url('/inventory')}}" role="button">Regresar al Inventario de Dispositivos</a>
        <!-- @php
            var_dump($availableDevices);
        @endphp -->
        <div class="col">
            <h1 class="text-center">Detalles de Dispositivo</h1>
        </div>
        <div class="container">
            <!--Product information-->
            <div class="row">
                <!--Left side (Name, No. Serie, etc)-->
                <div class="card col-sm-4 border-top-0 border-bottom-0 border-left-0 rounded-0">
                    <div class="card-body">
                        <h2 class="card-title">{{$device->name}}</h2>
                        <br>

                        <h6 class="card-subtitle mb-2 text-muted"><b>Números de Serie: </b></h6>
                        @foreach($serialNumbers as $serialNumber)
                        <h6><b>{{$serialNumber->serial_number}}</b></h6>
                        @endforeach

                        <br>
                        <h6 class="card-subtitle mb-2 text-muted"><b>Marca: </b>{{$device->brand}}</h6>
                        <br>
                        <h6 class="card-subtitle mb-2 text-muted"><b>Modelo: </b>{{$device->model}}</h6>
                    </div>
                </div>
                <!--Rigth side (Estados)-->
                <div class="card col-sm-8 border-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Disponibles: {{$availableDevices}}</li>
                        <li class="list-group-item">En prestamo/reservado: {{$reservedDevices}}</li>
                        <li class="list-group-item">Reparación: {{$repairingDevices}}</li>
                        <li class="list-group-item">Mermas: {{$decreaseDevices}}</li>
                        <li class="list-group-item">Exclusivos clase: 0</li>
                    </ul>
                    <br>
                    <div class="col">
                        <h3>Total de dispositivos: {{$totalDevices}}</h3>
                    </div>
                </div>
            </div>
            <!--Button-->
            <div class="row">
                <div class="col">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Hacer cambios</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>