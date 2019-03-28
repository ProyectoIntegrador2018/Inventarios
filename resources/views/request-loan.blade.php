<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inventario</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
      </style>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
        <script src="https://code.jquery.com/jquery-3.3.1.js"integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <!-- Date picker -->
        <script type="text/javascript" src="/daterangepicker/moment.min.js"></script>
        <script type="text/javascript" src="/daterangepicker/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="/daterangepicker/daterangepicker.css" />
        <!-- JS scripts -->
        <script type="text/javascript" src="/js/reservaciones.js"></script>

    </head>
    <body class="bg-light">
      <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                  @auth
                    <!-- Link a Inventarios -->
                    <li class="nav-item dropdown" id="nav-inventory">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Inventario
                      </a>
                      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{url('/inventory')}}">Ver inventario</a>
                        <a class="dropdown-item" href="{{url('/deviceCreation')}}">Alta de dispositivo</a>
                      </div>
                    </li>
                    <!-- Link a Préstamos -->
                    <li class="nav-item" id="nav-loans">
                      <a class="nav-link" href="{{url('/loansList')}}">Préstamos</a>
                    </li>
                    <!-- Link a Reportes -->
                    <li class="nav-item" id="nav-reports">
                      <a class="nav-link" href="#">Reportes</a>
                    </li>
                  @else
                    <li class="nav-item">
                      <a class="nav-link" href="#">Ver dispositivos</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Mis préstamos</a>
                    </li>
                  @endauth
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
      </nav>
        <!-- Vista principal -->
        <main role="main" class="container bg-white">

          <div class="alert alert-success loans-success" role="alert" style="display:none;">El préstamo de dispositivo(s) se ha creado correctamente.</div><br>
          <div class="alert alert-danger loans-error" role="alert" style="display:none;">Error: Verifique que la información sea correcta para poder crear el préstamo</div>
          <div class="col">
              <h1 class="text-center">Solicitud de Préstamo</h1>
          </div>
          <!-- <div class="flex-center position-ref full-height"> -->
            <div class="container">
              <!-- Formulario -->
              <div class="row">
                <!-- Datos del dispositivo -->
                <div class="card col-sm-4 border-0">
                    <div class="card-body" id="crd_detallesDispositivo">
                        <h2 class="card-title" id="h_nombreDispositivo">{{$modelInformation->name}}</h2>
                        <br>
                        <h5 class="card-subtitle mb-2 text-muted" id="h_cantidad"><span style="color:black;">{{$quantity}}</span> dispositivos disponibles:</h5>
                        <br>
                        @foreach($serialNumbers as $serialNumber)
                        <h6 class="card-subtitle mb-2 text-muted" id="h_noSerie"><b>Número de Serie:</b> <span style="color:black;">{{$serialNumber->serial_number}}</span></h6>
                        @endforeach
                        <br>
                        <h6 class="card-subtitle mb-2 text-muted" id="h_marca"><b>Marca:</b> <span style="color:black;">{{$modelInformation->brand}}</span></h6>
                        <br>
                        <h6 class="card-subtitle mb-2 text-muted"><b>Modelo:</b> <span id="h_modelo" style="color:black;">{{$modelInformation->model}}</span></h6>
                    </div>
                </div>

                <!-- Reservación -->
                <div class="col-md-8 card border-top-0 border-bottom-0 border-right-0 rounded-0">
                  <div class="card-body">
                    <!-- Reservación -->
                    <div class="row">
                      <div class="col-md-12">
                        <h4 class="card-title">Apartar dispositivo</h4>
                      </div>

                      <div class="col-md-12">
                        <label for="firstName">Cantidad</label>
                        <input type="number" class="form-control" id="txb_cantidad" placeholder="" value="" required="">
                        <div class="invalid-feedback">
                          Por favor ingrese una cantidad mayor a 1
                        </div>
                      </div>

                      <div class="col-md-12 mt-3">
                        <label for="firstName">Motivo</label>
                        <textarea type="text"
                          class="form-control"
                          id="txa_motivo"
                          placeholder="Descripción del motivo, como nombre de la clase o evento, por el cual está solicitando este dispositivo"
                          required=""></textarea>
                        <div class="invalid-feedback">
                          Por favor escriba el motivo por el cual hace esta reservación
                        </div>
                      </div>

                      <div class="col-md-12 mt-3">
                        <label for="firstName">Fechas</label>
                        <input type="text" class="form-control" id="dat_fechas" placeholder="" value="" required="">
                        <div class="invalid-feedback">
                          Por favor seleccione una fecha
                        </div>
                      </div>
                    </div>

                    <!-- Datos del solicitante -->
                    <div class="row mt-5">
                      <div class="col-md-12">
                        <h4 class="card-title">Datos del solicitante</h4>
                      </div>
                      <div class="col-md-12">
                        <label for="firstName">Nombre</label>
                        <input type="text" class="form-control" id="txb_nombreSolicitante" placeholder="" value="" required="">
                        <div class="invalid-feedback">
                          Por favor escriba su nombre
                        </div>
                      </div>
                      <div class="col-md-6 mt-3">
                        <label for="firstName">Matrícula / Nómina</label>
                        <input type="text" class="form-control" id="txb_idSolicitante" placeholder="" value="" required="">
                        <div class="invalid-feedback">
                          Por favor escriba su matrícula o nómina
                        </div>
                      </div>
                      <div class="col-md-6 mt-3">
                        <label for="firstName">Correo electrónico</label>
                        <input type="text" class="form-control" id="txb_emailSolicitante" placeholder="" value="" required="">
                        <div class="invalid-feedback">
                          Por favor escriba su correo electrónico
                        </div>
                      </div>
                      <div class="col-md-12 mt-3">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="chb_esEstudiante">
                            <label class="form-check-label" for="chb_esEstudiante">
                              Soy un estudiante
                            </label>
                          </div>
                        </div>
                      </div>

                      <hr class="my-4">
                      <div class="row" id="div_student">
                        <div class="col-md-4 mt-4">
                          <label for="firstName">Carrera</label>
                          <input type="text" class="form-control" id="txb_carrera" placeholder="" value="" required="">
                          <div class="invalid-feedback">
                            Por favor escriba las siglas de su carrera
                          </div>
                        </div>
                        <div class="col-md-8 mt-4">
                          <label for="firstName">Nombre de profesor responsable</label>
                          <input type="text" class="form-control" id="txb_nombreResponsable" placeholder="" value="" required="">
                          <div class="invalid-feedback">
                            Por favor escriba el nombre del profesor que será responsable de su préstamo
                          </div>
                        </div>
                        <div class="col-md-12 mt-3">
                          <label for="firstName">Correo electrónico de profesor responsable</label>
                          <input type="text" class="form-control" id="txb_emailResponsable" placeholder="" value="" required="">
                          <div class="invalid-feedback">
                            Por favor escriba el correo electrónico del profesor responsable de su préstamo
                          </div>
                        </div>
                      </div>
                    </div>

                    <script>
                      $("#div_student").hide();
                    </script>

                  </div>
                </div>
              </div>
              <br>
              <!-- Boton de enviar -->
              <div class="row">
                <div class="col">
                  @csrf
                  <button class="btn btn-primary btn-lg btn-block" id="btn_reservar" type="submit">Reservar</button>
                </div>
              </div>
            </div>
          <!-- </div> -->
        <br>
        </main>

    </body>
</html>
