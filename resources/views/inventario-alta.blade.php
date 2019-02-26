<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inventario</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
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
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
              <li class="nav-item active dropdown">
                <a class="nav-link dropdown-toggle border-left" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Inventario
                  <span class="sr-only">(current)</span>
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
                <a class="nav-link" href="#">Préstamos</a>
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

          <div class="flex-center position-ref full-height">
            <div class="container">
              <!-- Formulario -->
              <div class="row">
                <!-- Datos del dispositivo -->
                <div class="col-md-4 card border-0">
                  <div class="card-body">
                    <h4 class="card-title">Datos del dispositivo</h4>
                    <form class="needs-validation" novalidate="">
                      <div class="row my-3">
                        <div class="col">
                          <label for="firstName">Nombre</label>
                          <input type="text" class="form-control" id="txb_nombre" placeholder="" value="" required="">
                          <div class="invalid-feedback">
                            Por favor escriba el nombre del dispositivo
                          </div>
                        </div>
                      </div>
                      <div class="row my-3">
                        <div class="col-md-6">
                          <label for="firstName">Marca</label>
                          <input type="text" class="form-control" id="txb_marca" placeholder="" value="" required="">
                          <div class="invalid-feedback">
                            Por favor escriba la marca
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="firstName">Modelo</label>
                          <input type="text" class="form-control" id="txb_modelo" placeholder="" value="" required="">
                        </div>
                      </div>
                      <div class="row my-3">
                        <div class="col">
                          <label for="firstName">Cantidad</label>
                          <input type="number" class="form-control" id="txb_cantidad" placeholder="" value="" required="">
                          <div class="invalid-feedback">
                            Por favor ingrese una cantidad mayor a 1
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Identificadores -->
                <div class="col-md-4 card border-top-0 border-bottom-0">
                  <div class="card-body">
                    <h4 class="card-title">Identificadores</h4>
                    <label for="address">No. de Serie</label>
                    <div class="col">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="chb_requiereSerie">
                        <label class="custom-control-label" for="same-address">Se requiere un número de serie</label>
                      </div>
                      <br>
                    </div>
                    <div class="mb-3">
                      <input type="text" class="form-control" id="txb_noSerie_1" placeholder="No. de Serie" required="">
                      <div class="invalid-feedback">
                        Por favor, ingrese un número de serie.
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Others -->
                <div class="col-md-4 card border-0">
                  <div class="card-body">
                    <h4 class="card-title">Ubicación</h4>
                    <div class="row my-3">
                      <div class="col-md-6">
                        <label for="firstName">Edificio</label>
                        <select class="custom-select" id="dpm_edificio">
                          <option selected>Elija uno</option>
                          <option value="A1">A1</option>
                          <option value="A2">A2</option>
                          <option value="A3">A3</option>
                          <option value="A4">A4</option>
                          <option value="A5">A5</option>
                          <option value="A6">A6</option>
                          <option value="A7">A7</option>
                          <option value="CIAP">CIAP</option>
                          <option value="CETEC">CETEC</option>
                        </select>
                        <div class="invalid-feedback">
                          Por favor seleccione un edificio
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="firstName">Salón</label>
                        <input type="text" class="form-control" id="txb_salon" placeholder="" value="" required="">
                        <div class="invalid-feedback">
                          Por favor escriba el salón
                        </div>
                      </div>
                    </div>
                    <hr class="my-4">
                    <h4 class="card-title">Etiquetas</h4>
                    <div class="row my-3">
                      <div class="col">
                        <label for="firstName">Palabras clave que ayudan a encontrar el dispositivo separadas por coma</label>
                        <textarea type="text"
                        class="form-control"
                        id="txa_etiquetas"
                        placeholder="Por ejemplo: móvil, teléfono, celular, tableta, dron, ios, android, etc..."
                        required=""></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <!-- Boton de enviar -->
              <div class="row">
                <div class="col">
                  <button class="btn btn-primary btn-lg btn-block" type="submit">Dar de alta dispositivo</button>
                </div>
              </div>
            </div>
          </div>

        </main>

    </body>
</html>
