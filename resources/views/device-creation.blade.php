@extends('layouts.app')
@section('content')
@csrf
<!-- Vista principal -->
<main role="main" class="container bg-white">
  <br>
  <div class="alert alert-success devices-success" role="alert" style="display:none;">Los dispositivos se han agregado correctamente al inventario.</div>
  <div class="alert alert-danger devices-error" role="alert" style="display:none;">Error: Verifique que los números de serie no estén repetidos en el inventario.</div>

  <div class="row flex-center position-ref full-height">
    <div class="col">
      <h1 class="text-center">Alta de Dispositivos</h1>
    </div>
    <div class="container">
      <!-- Formulario -->
      <div class="row">
        <!-- Datos del dispositivo -->
        <div class="col-md-4 card">
          <div class="card-body">
            <h4 class="card-title">Datos del dispositivo</h4>
            <form class="needs-validation" novalidate="">
              <div class="row my-3">
                <div class="col ui-widget">
                  <label for="firstName">Nombre</label>
                  <input type="text" class="form-control" id="txb_nombre" placeholder="" value="" required="">
                  <div class="device-invalid-feedback" style="display:none;color:red;">Por favor escriba el nombre del dispositivo</div>
                </div>
              </div>
              <div class="row my-3">
                <div class="col-md-6">
                  <label for="firstName">Marca</label>
                  <input type="text" class="form-control" id="txb_marca" placeholder="" value="" required="">
                  <div class="brand-invalid-feedback" style="display:none;color:red;">Por favor escriba la marca</div>
                </div>
                <div class="col-md-6">
                  <label for="firstName">Modelo</label>
                  <input type="text" class="form-control" id="txb_modelo" placeholder="" value="" required="">
                  <div class="model-invalid-feedback" style="display:none;color:red;">Por favor ingrese un modelo</div>
                </div>
              </div>
              <div class="row my-3">
                <div class="col">
                  <label for="firstName">Cantidad</label>
                  <input type="number" class="form-control" id="txb_cantidad" placeholder="" value="" required="">
                  <div class="quantity-invalid-feedback" style="display:none;color:red;">Por favor ingrese una cantidad mayor a 1</div>
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
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Se requiere(n) número(s) de serie</label>
              </div>
              <br>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control" id="txb_noSeries" placeholder="Número(s) de Serie" required="" style="display:none">
              <div class="serial-number-invalid-feedback" style="display:none;color:red;">Por favor, ingrese un número de serie.</div>
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
                  <option value="A3">A3</option>
                  <option value="A4">A4</option>
                  <option value="A7">A7</option>
                </select>
                <div class="building-invalid-feedback" style="display:none;color:red;">Por favor seleccione un edificio</div>
              </div>
              <div class="col-md-6">
                <label for="firstName">Salón</label>
                <input type="text" class="form-control" id="txb_salon" placeholder="" value="" required="">
                <div class="room-invalid-feedback" style="display:none;color:red;">Por favor escriba el salón</div>
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
          <button id="btn-dar-alta" class="btn btn-primary btn-lg btn-block" type="submit">Dar de alta dispositivo</button>
        </div>
      </div>
    </div>
  </div>
<br>
</main>

@endsection

@section('script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  $(document).ready(function(){

    // Autocomplete for device names
    $( function() {
      $.ajax({
        url : '/searchLoan',
        type : 'GET',
        dataType: 'json',
        success: function (dataReceived) {
          dataReceived = dataReceived[0];
          // Translate dataReceived (array of objects) to newData (array of strings)
          var newData = [];
          for (i = 0; i < dataReceived.length; i++) {
            newData[i] = dataReceived[i].name;
          }
          dataReceived = newData;
          $("#txb_nombre").autocomplete({ source: dataReceived });
        }
      });
    });

    // Autocomplete for device names
    $( function() {

      $.ajax({
        url : '/getDeviceBrands',
        type : 'GET',
        dataType: 'json',
        success: function (dataReceived) {
          dataReceived = dataReceived[0];
          // Translate dataReceived (array of objects) to newData (array of strings)
          var newData = [];
          for (i = 0; i < dataReceived.length; i++) {
            newData[i] = dataReceived[i].brand;
          }
          dataReceived = newData;
          $("#txb_marca").autocomplete({ source: dataReceived });
        }
      });
    });

    // Autocomplete for device names
    $( function() {

      $.ajax({
        url : '/getDeviceModels',
        type : 'GET',
        dataType: 'json',
        success: function (dataReceived) {
          dataReceived = dataReceived[0];
          // Translate dataReceived (array of objects) to newData (array of strings)
          var newData = [];
          for (i = 0; i < dataReceived.length; i++) {
            newData[i] = dataReceived[i].model;
          }
          dataReceived = newData;
          $("#txb_modelo").autocomplete({ source: dataReceived });
        }
      });
    });
    var requiredSerialNumbers = false;

    $('#exampleCheck1').change(function() {
        if(this.checked) {
          requiredSerialNumbers = true;
          console.log("Se requieren números de serie");
          $("#txb_noSeries").show();
        }else{
          requiredSerialNumbers = false;
          console.log("No se requieren números de serie");
          $("#txb_noSeries").hide();
        }
    });

    var allowPost = false;

    $(document).on('click', '#btn-dar-alta', function () {

      // Things to verify before allowing to send information to the back-end:
      /*
        Name of the device(s)
        Brand of the device(s)
        Model of the device(s)
        Quantity of the device(s)
        Serial Number(s) of the devices(s)
        Name of the building
        Name of the room
        Name(s) of the tag(s)
      */


      var name           = $('#txb_nombre').val();
      var brand          = $('#txb_marca').val();
      var model          = $('#txb_modelo').val();
      var quantity       = $('#txb_cantidad').val();
      var serial_numbers = $('#txb_noSeries').val();
      var building       = $('#dpm_edificio option:selected').text();
      var room           = $('#txb_salon').val();
      var tags           = $('#txa_etiquetas').val();

      tags = tags.replace(/\s/g,'');

      if(name != ""){
        $('.device-invalid-feedback').hide();
      }else{
        $('.device-invalid-feedback').show();
      }

      if(brand != ""){
        $('.brand-invalid-feedback').hide();
      }else{
        $('.brand-invalid-feedback').show();
      }

      if(model != ""){
        $('.model-invalid-feedback').hide();
      }else{
        $('.model-invalid-feedback').show();
      }

      if(quantity != ""){
        $('.quantity-invalid-feedback').hide();
      }else{
        $('.quantity-invalid-feedback').show();
      }


      if(serial_numbers != ""){
        if(requiredSerialNumbers == false){
          $('.serial-number-invalid-feedback').hide();
        }else{
          $('.serial-number-invalid-feedback').show();
        }
      }else{
        if(requiredSerialNumbers == true){
          $('.serial-number-invalid-feedback').show();
        }else{
          $('.serial-number-invalid-feedback').hide();
        }
      }


      if(parseInt(quantity) == (parseInt((serial_numbers.match(/,/g) || []).length) + 1)){
        // Falta hacer esta parte
      }else{
        if(requiredSerialNumbers == true){
          alert("La cantidad de dispositivos y números de serie no es la misma.")
        }
      }

      if(building != "Elija uno"){
        $('.building-invalid-feedback').hide();
      }else{
        $('.building-invalid-feedback').show();
      }

      if(room != ""){
        $('.room-invalid-feedback').hide();
      }else{
        $('.room-invalid-feedback').show();
      }

      // Let's allow the post if no one of the previous conditions are false
      if(
        (name != "") &&
        (brand != "") &&
        (model != "") &&
        (quantity != "") &&
        (serial_numbers != "") &&
        (parseInt(quantity) == (parseInt((serial_numbers.match(/,/g) || []).length) + 1)) &&
        (building != "Elija uno") &&
        (room != "")
      ){
        allowPost = true;
      }else{
        // One or more of the conditions are false
      }

      if(
        (requiredSerialNumbers == false) &&
        (name != "") &&
        (brand != "") &&
        (model != "") &&
        (quantity != "") &&
        (building != "Elija uno") &&
        (room != "")
      ){
        // Allowed because the serial numbers are not required
        allowPost = true;
      }else{
        console.log("De todos modos no se pudo");
      }

      if(allowPost == true){

        var requiredSerialNumbersInString = "no";

        if(requiredSerialNumbers == true){
          requiredSerialNumbersInString = "yes";
        }

        var data = {
          _token         : '{{csrf_token()}}',
          name           : name,
          brand          : brand,
          model          : model,
          quantity       : quantity,
          serial_numbers : serial_numbers,
          building       : building,
          room           : room,
          tags           : tags,
          requiredSN     : requiredSerialNumbersInString
        };

        $.ajax({
          url : '/createDevice',
          type : 'POST',
          data: data,
          dataType: 'json',
          success: function (jsonReceived) {

            if(jsonReceived.status == 1){
              $('.devices-success').show();
              $('.devices-error').hide();

              $('#txb_nombre').val('');
              $('#txb_marca').val('');
              $('#txb_modelo').val('');
              $('#txb_cantidad').val(0);
              $('#txb_noSeries').val('');
              $('#dpm_edificio').text('Elija uno');
              $('#txb_salon').val('');
              $('#txa_etiquetas').val('');
            }

            if(jsonReceived.status == 2){
              $('.devices-error').show();
              $('.devices-success').hide();
            }

          }
        });
      }else{
        // The post function could not be triggered
        console.log("The post function could not be triggered");
      }

    });

  });
</script>
@endsection
