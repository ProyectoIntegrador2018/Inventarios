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
          <div class="content">

            <div class="col">
              <br><br>
              <p class="text-center bg-warning">
                NOTA AL DESARROLLADOR: Esta es un demo de como se vería una
                sola fila de dispositivos. Agregar un máximo de 4 elementos por
                cada fila. Borrar este texto cuando se empieze a desarrollar
              </p>
            </div>

            <div class="col">
              <div class="card-deck">
                <div class="card">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">iPhone 8</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Apple</h6>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content</p>
                    <div class="row mt-auto">
                      <div class="col">
                        <a href="#" class="card-link float-left">Detalles</a>
                      </div>
                      <div class="col">
                        <a href="#" class="card-link float-right">Apartar</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <small class="text-muted">11 disponibles</small>
                  </div>
                </div>

                <div class="card">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Kinekt V2</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Xbox</h6>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content</p>
                    <div class="row mt-auto">
                      <div class="col">
                        <a href="#" class="card-link float-left">Detalles</a>
                      </div>
                      <div class="col">
                        <a href="#" class="card-link float-right">Apartar</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <small class="text-muted">2 disponibles</small>
                  </div>
                </div>

                <div class="card">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Arduino UNO</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Arduino</h6>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content</p>
                    <div class="row mt-auto">
                      <div class="col">
                        <a href="#" class="card-link float-left">Detalles</a>
                      </div>
                      <div class="col">
                        <a href="#" class="card-link float-right">Apartar</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <small class="text-muted">No hay disponibles</small>
                  </div>
                </div>

                <div class="card">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Nombre de dispositivo</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Marca</h6>
                    <p class="card-text">Todos los tags de este producto</p>
                    <div class="row mt-auto">
                      <div class="col">
                        <a href="#" class="card-link float-left">Detalles</a>
                      </div>
                      <div class="col">
                        <a href="#" class="card-link float-right">Apartar</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <small class="text-muted">[Disponibilidad]</small>
                  </div>
                </div>

              </div>
              <br>
            </div>
          </div>
        </main>
    </body>
</html>
