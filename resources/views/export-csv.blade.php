@extends('layouts.app')

@section('content')
<main role="main" class="container bg-white">
  <div class="container">
    <!-- Date filter -->
    <div class="row">
      <div class="col-lg-12">
        <h4 class="card-title">Fechas</h4>
      </div>
      <div class="col">
        <input type="text" class="form-control" id="dat_fechas" placeholder="" value="" required="">
        <div class="invalid-feedback">
          Por favor seleccione una fecha
        </div>
        <br>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="chb_fechas">
          <label class="form-check-label" for="chb_fechas">
            Histórico
          </label>
        </div>
      </div>
    </div>
    <br>
    <hr>
    <br>
    <!-- Solicitant type filter -->
    <div class="row">
      <div class="col-lg-12 mb-2">
        <h4 class="card-title m-0">Tipo de Solicitante</h4>
        <div class="device-invalid-feedback" style="display:none;color:red;" id="error_user">Elija al menos un solicitante</div>
      </div>
      <div class="col form-group">
        <div class="form-check">
          <input class="form-check-input chb_user" type="checkbox" value="true" id="chb_profesor" checked>
          <label class="form-check-label" for="chb_profesor">Profesores</label>
          <br>
          <input class="form-check-input chb_user" type="checkbox" value="" id="chb_estudiante" checked>
          <label class="form-check-label" for="chb_estudiante">Estudiantes</label>
        </div>
      </div>
    </div>
    <br>
    <hr>
    <br>
    <!-- Status filter -->
    <div class="row">
      <div class="col-lg-12 mb-2">
        <h4 class="card-title m-0">Estatus de solicitud</h4>
        <div class="device-invalid-feedback" style="display:none;color:red;" id="error_status">Elija al menos un estatus</div>
      </div>
      <div class="col form-group">
        <div class="form-check">
          <input class="form-check-input chb_stat" type="checkbox" value="New" id="chb_stat_nuevo" checked>
          <label class="form-check-label" for="chb_stat_nuevo">Nuevas</label>
          <br>
          <input class="form-check-input chb_stat" type="checkbox" value="Cancelled" id="chb_stat_cancelado" checked>
          <label class="form-check-label" for="chb_stat_cancelado">Cancelados</label>
          <br>
          <input class="form-check-input chb_stat" type="checkbox" value="Separated" id="chb_stat_apartado" checked>
          <label class="form-check-label" for="chb_stat_apartado">Apartados</label>
          <br>
          <input class="form-check-input chb_stat" type="checkbox" value="Taken" id="chb_stat_prestado" checked>
          <label class="form-check-label" for="chb_stat_prestado">Prestados</label>
          <br>
          <input class="form-check-input chb_stat" type="checkbox" value="Expired" id="chb_stat_expirado" checked>
          <label class="form-check-label" for="chb_stat_expirado">Expirado</label>
          <br>
          <input class="form-check-input chb_stat" type="checkbox" value="Received" id="chb_stat_recibido" checked>
          <label class="form-check-label" for="chb_stat_recibido">Recibido</label>
          <br>
          <input class="form-check-input chb_stat" type="checkbox" value="Received late" id="chb_stat_recibidoTarde" checked>
          <label class="form-check-label" for="chb_stat_recibidoTarde">Recibido  Tarde</label>
        </div>
      </div>
    </div>
    <!-- Generate report button -->
    @csrf
    <button class="btn btn-primary btn-lg btn-block" id="btn_generar" type="submit">Generar Reporte de Préstamos</button>
  </div>
</main>
@endsection

@section('script')
<!-- Date picker -->
<script type="text/javascript" src="/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="/daterangepicker/daterangepicker.css" />
<!-- JS scripts -->
<script type="text/javascript">
  $(document).ready(function(){
    $('#dat_fechas').daterangepicker();

    $(function() {
      $('#dat_fechas').daterangepicker({
        startDate: moment().startOf('day'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'YYYY/MM/DD'
        }
      });
    });

    $('#btn_generar').on('click', generateReport);

    $('#chb_fechas').on('click', setHistoricDate);

  });

  function generateReport() {
    verifyInputs();
    if(formIsValid()) {
      inputs = getInputs();
      var url = route('report.get.loans')+ "?" + $.param(inputs);
      window.location = url;
    }
  }

  function getInputs() {

    var allDates  = $('#chb_fechas').prop('checked');
    var startDate = $('#dat_fechas').val().substring(0, 10);
    var endDate   = $('#dat_fechas').val().substring(13);
    var professor = $('#chb_profesor').prop('checked');
    var student   = $('#chb_estudiante').prop('checked');
    var statuses  = getStatusFromCheckboxes();
    var allStatus = statuses.length == $('.chb_stat').length ? true : false;

    var inputs = {
      _token    : $('meta[name="csrf-token"]').attr('content'),
      allDates  : allDates,
      startDate : startDate,
      endDate   : endDate,
      professor : professor,
      student   : student,
      allStatus : allStatus,
      statuses  : statuses
    };

    return inputs;
  }

  function getStatusFromCheckboxes() {
    var statuses = [];
    var checkboxes = $('.chb_stat')

    for(var i=0; i < checkboxes.length; i++){
      if(checkboxes[i].checked) statuses.push(checkboxes[i].value)
    }

    return statuses
  }

  function setHistoricDate() {
    if($('#chb_fechas').prop('checked')) {
      $('#dat_fechas').prop('disabled', true);
    } else {
      $('#dat_fechas').prop('disabled', false);
    }
  }

  function verifyInputs() {
    // Vefiry that at least one solicitant checkbox is selected
    var statusesHasChecked = false;
    var checkboxes = $('.chb_user')
    for(var i=0; i < checkboxes.length; i++){
      if(checkboxes[i].checked) statusesHasChecked = true;
    }
    if (statusesHasChecked) {
      $('#error_user').hide();
    } else {
      $('#error_user').show();
    }

    // Vefiry that at least one status checkbox is selected
    var statusesHasChecked = false;
    var checkboxes = $('.chb_stat')
    for(var i=0; i < checkboxes.length; i++){
      if(checkboxes[i].checked) statusesHasChecked = true;
    }
    if (statusesHasChecked) {
      $('#error_status').hide();
    } else {
      $('#error_status').show();
    }
  }

  function formIsValid() {
    return !$('#error_status').is(':visible') && !$('#error_user').is(':visible')
  }
</script>
@endsection
