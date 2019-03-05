<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Préstamos</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                /* height: 100vh; */
                margin: 0;
            }
            .full-height {
                height: 100vh;
            }
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }
            .position-ref {
                position: relative;
            }
            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
            .content {
                text-align: center;
            }
            .title {
                font-size: 84px;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
        <script src="https://code.jquery.com/jquery-3.3.1.js"integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <!-- Font awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <!-- JS scripts -->
        <script type="text/javascript" src="/js/prestamos.js"></script>
    </head>
    <body class="bg-light">
        <!-- Barra de herramientas -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
          <!-- Link a Home -->
          <a class="navbar-brand" href="#">Inventario de Laboratorios Tec</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Link a Inventarios -->
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle border-left" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Inventario
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Consultar inventario</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Alta de dispositivo</a>
                  <a class="dropdown-item" href="#">Modificar inventario</a>
                </div>
              </li>
              <!-- Link a Reportes -->
              <li class="nav-item">
                <a class="nav-link" href="#">Reportes</a>
              </li>
              <!-- Link a Préstamos -->
              <li class="nav-item">
                <a class="nav-link active" href="">Préstamos</a>
              </li>
            </ul>

            <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
              <!-- Link a Iniciar sesión -->
              <li class="nav-item">
                <a class="nav-link" href="#">Iniciar Sesión</a>
              </li>
              <!-- Link a Cerrar sesión -->
              <li class="nav-item">
                <a class="nav-link" href="#">Cerrar Sesión</a>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Vista principal -->
        <main role="main" class="container bg-white">
          <br><a class="btn btn-primary" href="{{url('/home')}}" role="button">Regresar al Menú Principal</a>
          <div class="content">

            <h1>Lista de Préstamos</h1>
            <div class="col-md-12 mt-5 input-group">
              <input type="text" class="form-control" id="txb_search" placeholder="Buscar préstamos..." value="" required="">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                  <i class="fas fa-search"></i>
                </span>
              </div>
            </div>

            <div class="col">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Solicitante</th>
                    <th scope="col">Profesor responsable</th>
                    <th scope="col">Dispositivo</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Estatus</th>
                    <th scope="col">Herramientas</th>
                  </tr>
                </thead>
                <tbody id="tbl_prestamos">
                </tbody>
              </table>
            </div>
          </div>

          <div id="noLoans" style="text-align:center";></div>

          <!-- Modal -->
          <div class="modal fade" id="mdl_detallesPrestamo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalCenterTitle">Detalles de Préstamo</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row" id="info_solicitante">
                    <div class="col-md-12"><h5>Solicitante</h5></div>
                    <div class="col-md-6"><p id="txt_solicitante_nombre"></p></div>
                    <div class="col-md-6"><p id="txt_solicitante_degree"></p></div>
                    <div class="col-md-6"><p id="txt_solicitante_email"></p></div>
                    <div class="col-md-6"><p id="txt_solicitante_id"></p></div>
                  </div>
                  <hr>
                  <div class="row" id="info_responsable">
                    <div class="col-md-12"><h5 id="">Responsable</h5></div>
                    <div class="col-md-6"><p id="txt_responsable_nombre"></p></div>
                    <div class="col-md-6"><p id="txt_responsable_email"></p></div>
                  </div>
                  <hr>
                  <div class="row" id="info_dispositivo">
                    <div class="col-md-12"><h5>Dispositivo</h5></div>
                    <div class="col-md-6"><p id="txt_dispositivo_nombre"></p></div>
                    <div class="col-md-6"><span class="badge-lg badge-pill" id="bdg_dispositivo_status"></span></div>
                    <div class="col-md-6"><p id="txt_dispositivo_cantidad"></p></div>
                    <div class="col-md-6"><p id="txt_dispositivo_serie"></p></div>

                    <div class="col-md-2"><p>Del: </p></div>
                    <div class="col-md-4"><p id="txt_dispositivo_inicio"></p></div>
                    <div class="col-md-2"><p>Al: </p></div>
                    <div class="col-md-4"><p id="txt_dispositivo_fin"></p></div>

                    <div class="col-md-12"><p id="txt_dispositivo_motivo"></p></div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
        </main>
    </body>
</html>