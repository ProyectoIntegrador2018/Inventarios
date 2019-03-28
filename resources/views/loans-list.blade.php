<!doctype html>
@extends('layouts.app')

@section('content')
  <body class="bg-light">
      <!-- Vista principal -->
      <main role="main" class="container bg-white">
        <br>
        <div class="content">

          <div class="col-md-12">
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
@endsection
