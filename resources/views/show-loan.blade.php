<!doctype html>
@extends('layouts.app')
@section('content')
  <!-- Vista principal -->
  <main role="main" class="container bg-white">
    <div class="col-md-12">
      <h1 class="text-center">Busca tu solicitud de prestamo</h1>
    </div>
    <!-- Barra de búsqueda -->
    <div class="col-md-12 mt-5 input-group align-items-center">
      <label for="txb_search" class="p-0 px-2 m-0">Código de Reservación</label>
      <input type="text" class="form-control" id="txb_search" placeholder="Buscar préstamos..." value="" required="">
      <div class="input-group-prepend align-self-stretch">
        <span class="input-group-text" id="basic-addon1">
          <i class="fas fa-search"></i>
        </span>
      </div>
    </div>

    <!-- Vista contenedor vacío -->
    <div class="row mt-5 d-flex justify-content-center align-items-center text-center">
      <div class="col-md-4 mx-auto" id="mensaje">
        <span style="color: grey;"><i id="mensaje_icono" class="fas fa-10x mx-auto"></i></span>
        <p id="mensaje_texto" class="mt-4"></p>
      </div>
    </div>

    <!-- Vista contenedor de "solicitud de préstamo" -->
    <div id="loan" style="display: none;">
      <div class="row mt-5 justify-content-center">
        <h2 id="loan_id" class="card-title">Solicitud: ABCDE</h2>
      </div>
      <div class="row mt-2">
        <!-- Detalles de dispositivo -->
        <div class="col-md-6 card border-0">
          <div class="card-body border border-top-0 border-bottom-0 border-left-0">
            <h4 class="card-title">Solicitante</h4>
            <form class="needs-validation" novalidate="">
              <div class="row my-3">
                <div class="col">
                  <label for="solicitante_nombre">Nombre</label>
                  <p class="text-secondary border-bottom" id="solicitante_nombre">Guillermo Mendoza Soni</p>
                </div>
              </div>
              <div class="row my-3">
                <div class="col-md-6">
                  <label for="solicitante_id">Matrícula</label>
                  <p class="text-secondary border-bottom" id="solicitante_id">A01234567</p>
                </div>
                <div class="col-md-6">
                  <label for="solicitante_carrera">Carrera</label>
                  <p class="text-secondary border-bottom" id="solicitante_carrera">ITC</p>
                </div>
              </div>
              <div class="row my-3">
                <div class="col">
                  <label for="profesor_responsable">Profesor Responsable</label>
                  <p class="text-secondary border-bottom" id="profesor_responsable">Armandina Leal</p>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!-- Detalles de dispositivo -->
        <div class="col-md-6 card border-0">
          <div class="card-body">
            <h4 class="card-title">Datos del dispositivo</h4>
            <form class="needs-validation" novalidate="">
              <div class="row my-3">
                <div class="col-lg-4">
                  <label for="dispositivo_nombre">Nombre</label>
                  <p class="text-secondary border-bottom" id="dispositivo_nombre">Galaxy</p>
                </div>
                <div class="col-lg-4">
                  <label for="dispositivo_marca">Marca</label>
                  <p class="text-secondary border-bottom" id="dispositivo_marca">Samsung</p>
                </div>
                <div class="col-lg-4">
                  <label for="dispositivo_modelo">Modelo</label>
                  <p class="text-secondary border-bottom" id="dispositivo_modelo">S9</p>
                </div>
              </div>
              <div class="row my-3">
                <div class="col-lg-6">
                  <label for="fechas">Periodo de préstamo del:</label>
                  <p class="text-secondary border-bottom fechas" id="fecha_inicio">01 Febrero 2019</p>
                </div>
                <div class="col-lg-6">
                  <label for="fechas">Al:</label>
                  <p class="text-secondary border-bottom fechas" id="fecha_fin">13 Abril 2019</p>
                </div>
              </div>
              <div class="row my-3">
                <div class="col-md-3">
                  <label for="estatus">Estatus</label>
                </div>
                <div class="col-md-9">
                  <div class="badge-lg badge-pill badge-primary text-center" id="estatus">Estatus</div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="row my-3 justify-content-center">
        <div class="col-lg-4">
          <button type="button" id="btn_eliminar" class="btn btn-danger w-100">
            <i class="fas fa-trash-alt"></i> Cancelar Solicitud
          </button>
        </div>
      </div>
    </div>
  </main>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      setMessage('EMPTY');

      displayDeleteButton();

      $('#btn_eliminar').on('click', deleteLoanApplication);

      $('#txb_search').keyup(function(e){
        if(e.keyCode == 8) {
          setMessage('EMPTY');
        }
      })
      $('#txb_search').keyup(function(e){
        if(e.keyCode == 13) {
          searchLoan()
        }
      })

      function displayDeleteButton() {
        var status = $('#estatus');
        var btn_delete = $('#btn_eliminar');

        if(status.text() == 'Cancelado') {
          btn_delete.parent().parent().hide();
        } else {
          btn_delete.parent().parent().show();
        }
      }

      function searchLoan() {
        var searchBar = $('#txb_search');

        // Remove blanks and spaces
        searchBar.val(searchBar.val().replace(/ /g,''))
        // If the search bar is not empty
        if (searchBar.val().length > 0) {
          var prueba = "hola"
          // Search the given loan ID
          $( function() {
            $.ajax({
              url : '/searchLoan',
              type : 'POST',
              data : {
                _token: $('meta[name="csrf-token"]').attr('content'),
                txb_search: searchBar.val()
              },
              dataType: 'json',
              success: function (dataReceived) {
                loadLoanDetails(dataReceived);
                displayDeleteButton();
              },
              error: function (error) {
                console.log("CONNECTION ERROR");
              }
            });
          });
        } else {
          setMessage('EMPTY');
        }
      }

      function setEmptyMessage() {
        var message = $('#mensaje');
        var message_icon = $('#mensaje_icono');
        var message_text = $('#mensaje_texto');
        var loan = $('#loan');

        loan.hide();
        message.show();
        message_icon.addClass("fa-search")
        message_text.text("Ingresa tu código de reservación para buscar tu solicitud de préstamo y darle seguimiento.")
      }

      function setNotFoundMessage() {
        var message = $('#mensaje');
        var message_icon = $('#mensaje_icono');
        var message_text = $('#mensaje_texto');
        var loan = $('#loan');

        loan.hide();
        message.show();
        message_icon.addClass("fa-search")
        message_text.text("Oops! No encontramos la solicitud con el código que proporcionaste.")
      }

      function setMessage(message_type) {
        var message = $('#mensaje');
        var message_icon = $('#mensaje_icono');
        var message_text = $('#mensaje_texto');
        var loan = $('#loan');

        switch (message_type) {
          case 'EMPTY':
            loan.hide();
            message.show();
            message_icon.addClass("fa-search")
            message_icon.removeClass("fa-question-circle")
            message_text.text("Ingresa tu código de reservación para buscar tu solicitud de préstamo y darle seguimiento.")
            break;

          case 'SUCCESS':
            loan.show();
            message.hide();
            break;

          case 'NOT FOUND':
            loan.hide();
            message.show();
            message_icon.removeClass("fa-search")
            message_icon.addClass("fa-question-circle")
            message_text.text("Oops! No encontramos la solicitud con el código que proporcionaste.")
            break;

          default:
            console.log("Unknown message type");
        }
      }

      function loadLoanDetails(data) {
        setMessage(data.status);

        if(data.status == "SUCCESS") {
          // If there is no responsable, then the loan belongs to a professor
          if(data.responsable.name == null) {
            formatForProfessor(data);
          // If there IS responsable, then the loan belongs to a student
          } else {
            formatForStudent(data);
          }
        }
      }

      function formatForStudent(data) {
        var loan_ID = $('#loan_id');
        var applicant_name = $('#solicitante_nombre');
        var applicant_id = $('#solicitante_id');
        var applicant_degree = $('#solicitante_carrera');
        var applicant_responsable = $('#profesor_responsable');
        var device_name = $('#dispositivo_nombre');
        var device_brand = $('#dispositivo_marca');
        var device_model = $('#dispositivo_modelo');
        var date_start = $('#fecha_inicio');
        var date_end = $('#fecha_fin');
        var device_status = $('#estatus');

        loan_ID.text('Solicitud: ' + $('#txb_search').val());
        applicant_name.text(data.applicant.name);
        $('label[for="solicitante_id"]').text("Matrícula");
        applicant_id.text(data.applicant.applicant_id);
        $('label[for="solicitante_carrera"]').parent().show();
        applicant_degree.text(data.applicant.degree);
        applicant_responsable.parent().parent().show();
        applicant_responsable.text(data.responsable.name);
        device_name.text(data.device.name);
        device_brand.text(data.device.brand);
        device_model.text(data.device.model);
        date_start.text(formatDate(data.loan.start_date));
        date_end.text(formatDate(data.loan.end_date));
        setStatus(data.loan.status, device_status);
      }

      function formatForProfessor(data) {
        var loan_ID = $('#loan_id');
        var applicant_name = $('#solicitante_nombre');
        var applicant_id = $('#solicitante_id');
        var applicant_degree = $('#solicitante_carrera');
        var applicant_responsable = $('#profesor_responsable');
        var device_name = $('#dispositivo_nombre');
        var device_brand = $('#dispositivo_marca');
        var device_model = $('#dispositivo_modelo');
        var date_start = $('#fecha_inicio');
        var date_end = $('#fecha_fin');
        var device_status = $('#estatus');

        loan_ID.text('Solicitud: ' + $('#txb_search').val());
        applicant_name.text(data.applicant.name);
        $('label[for="solicitante_id"]').text("Nómina");
        applicant_id.text(data.applicant.applicant_id);
        $('label[for="solicitante_carrera"]').parent().hide();
        applicant_degree.text(data.applicant.degree);
        applicant_responsable.parent().parent().hide();
        device_name.text(data.device.name);
        device_brand.text(data.device.brand);
        device_model.text(data.device.model);
        date_start.text(formatDate(data.loan.start_date));
        date_end.text(formatDate(data.loan.end_date));
        setStatus(data.loan.status, device_status);
      }

      function formatDate (date) {
        var newDate = new Date(date);
        var options = {
          weekday: 'long',
          day: 'numeric',
          month: 'long',
          year: 'numeric',
          hour12: true
        };
        return new Intl.DateTimeFormat('es-US', options).format(newDate);
      }

      function setStatus(status, htmlTarget) {
        status = translate(status, 'to_ES');
        htmlTarget.removeClass();
        htmlTarget.addClass("badge-lg badge-pill badge-primary text-center");
        htmlTarget.text(status);
        switch (status) {
          case 'Nueva solicitud':
            htmlTarget.addClass("badge-primary");
            break;

          case 'Cancelado':
            htmlTarget.addClass("badge-warning");
            break;

          case 'Apartado':
            htmlTarget.addClass("badge-secondary");
            break;

          case 'Prestado':
            htmlTarget.addClass("badge-secondary");
            break;

          case 'Recibido':
            htmlTarget.addClass("badge-success");
            break;

          case 'Recibido tarde':
            htmlTarget.addClass("badge-info");
            break;

          case 'Expirado':
            htmlTarget.addClass("badge-danger");
            break;

          default:
            htmlTarget.addClass("badge-dark");
            break;
        }
      }

      function translate(state){
        var translated = '';

        if ('to_EN') {
          switch (state) {

            case 'Nueva solicitud':
            translated = "New";
            break;

            case 'Cancelado':
            translated = "Cancelled";
            break;

            case 'Apartado':
            translated = "Separated";
            break;

            case 'Prestado':
            translated = "Taken";
            break;

            case 'Recibido':
            translated = "Received";
            break;

            case 'Recibido tarde':
            translated = "Received late";
            break;

            case 'Expirado':
            translated = "Expired";
            break;

            default:
            translated = "Sin estado";
            break;
          }
        }

        if ('to_ES') {
          switch (state) {

            case 'New':
            translated = "Nueva solicitud";
            break;

            case 'Cancelled':
            translated = "Cancelado";
            break;

            case 'Separated':
            translated = "Apartado";
            break;

            case 'Taken':
            translated = "Prestado";
            break;

            case 'Received':
            translated = "Recibido";
            break;

            case 'Received late':
            translated = "Recibido tarde";
            break;

            case 'Expired':
            translated = "Expirado";
            break;

            default:
            translated = "Sin estado";
            break;
          }
        }
        return translated;
      }

      function deleteLoanApplication() {
        var loanID = $('#txb_search').val();
        var status = $('#estatus').text();
        status = translate(status, 'to EN');

        var data = {
          _token     : $('meta[name="csrf-token"]').attr('content'),
          loanID     : loanID,
          loanStatus : status
        };

        $.ajax({
          url : '/cancelLoan',
          type : 'POST',
          data: data,
          dataType: 'json',
          success: function (jsonReceived) {

            if(jsonReceived.status == 1){
              location.reload();
            }else{
              console.log("No se canceló el préstamo");
            }
          }

        });
      }

    });
  </script>
@endsection
