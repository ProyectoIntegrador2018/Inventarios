<!doctype html>

@extends('layouts.app')
@section('script')
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
@endsection



@section('content')
        <!-- Vista principal -->
        <main role="main" class="container bg-white">

          <div class="alert alert-success loans-success" role="alert" style="display:none;">El préstamo de dispositivo(s) se ha creado correctamente.</div><br>
          <div class="alert alert-danger loans-error" role="alert" style="display:none;">Error: Verifique que la información sea correcta para poder crear el préstamo</div>
          <div class="col">
              <h1 class="text-center">Solicitud de Préstamo</h1>
          </div>
          <!-- <div class="flex-center position-ref full-height"> -->
          
          <!-- Start of the section of requesting a loan -->
            <div class="container">
              <!-- Formulario -->
              <div class="row">
                <!-- Datos del dispositivo -->
                <div class="card col-sm-4 border-0">
                    <div class="card-body" id="crd_detallesDispositivo">
                        <h2 class="card-title" id="h_nombreDispositivo">{{$modelInformation->name}}</h2>
                        <br>
                        <h5 class="card-subtitle mb-2 text-muted" id="h_cantidad"><span style="color:black;" id="h_cantidad_numero">{{$quantity}}</span> dispositivos disponibles:</h5>
                        <br>
                        
                          @foreach($serialNumbers as $serialNumber)
                            @if($serialNumber->serial_number != "")
                              <h6 class="card-subtitle mb-2 text-muted" id="h_noSerie"><b>Número de Serie:</b> <span style="color:black;">{{$serialNumber->serial_number}}</span></h6>
                            @else
                              <h6 class="card-subtitle mb-2 text-muted" id="h_noSerie"><b>Número de Serie:</b> <span style="color:black;">No disponible</span></h6>
                            @endif
                          @endforeach
                          <br>
                        <h6 class="card-subtitle mb-2 text-muted" id="h_marca"><b>Marca:</b> <span style="color:black;">{{$modelInformation->brand}}</span></h6>
                        <br>
                        <h6 class="card-subtitle mb-2 text-muted"><b>Modelo:</b> <span id="h_modelo" style="color:black;">{{$modelInformation->model}}</span></h6>
                    </div>
                </div>

                <!-- Reservación -->
                @if($quantity == 0)
                  <div class="col-md-8 card border-top-0 border-bottom-0 border-right-0 rounded-0">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12">
                          <h5 style="color:red;">El dispositivo no se encuntra disponible para ser reservado</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                @else
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
                          Verifique la cantidad de dispositivos solicitada
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
                        <label for="firstName">Correo Institucional</label>
                        <input type="text" class="form-control" id="txb_emailSolicitante" placeholder="" value="" required="">
                        <div class="invalid-feedback">
                          Por favor escriba correctamente su correo institucional
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
                    </div>
                    <div class="row" id="div_student" style="display: none;">
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
        @endif
        
        <!-- End of the section of requesting a loan -->

        </main>

        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
          Launch demo modal
        </button> -->

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Préstamo creado correctamente.</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button> -->
              </div>
              <div class="modal-body">
                Ya lo puedes visualizar en la sección de préstamos.
              </div>
              <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                @auth
                  <button onclick="adminAfterLoanCreated()" type="button" class="btn btn-primary">Continuar</button>
                @endauth
                @guest
                  <button onclick="afterLoanCreated()" type="button" class="btn btn-primary">Continuar</button>
                @endguest
              </div>
            </div>
          </div>
        </div>

    </body>
</html>
@endsection
