@extends('layouts.app')
@section('content')
@csrf
<!-- Vista principal -->
<main role="main" class="container bg-white">
  <div class="alert alert-success devices-success" role="alert" style="display:none;">Los dispositivos se han agregado correctamente al inventario.</div>
  <div class="alert alert-danger devices-error" role="alert" style="display:none;">Error: Verifique que los números de serie no estén repetidos en el inventario.</div>
  <h1 class="text-center">Editar dispositivo</h1>
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
                <input type="text" class="form-control" id="txb_nombre" placeholder="" value="{{$device->name}}" required="">
                  <div class="device-invalid-feedback" style="display:none;color:red;">Por favor escriba el nombre del dispositivo</div>
                </div>
              </div>
              <div class="row my-3">
                <div class="col-md-6">
                  <label for="firstName">Marca</label>
                  <input type="text" class="form-control" id="txb_marca" placeholder="" value="{{$device->brand}}" required="">
                  <div class="brand-invalid-feedback" style="display:none;color:red;">Por favor escriba la marca</div>
                </div>
                <div class="col-md-6">
                  <label for="firstName">Modelo</label>
                  <input type="text" class="form-control" id="txb_modelo" placeholder="" value="{{$device->model}}" readonly>
                  <div class="model-invalid-feedback" style="display:none;color:red;">Por favor ingrese un modelo</div>
                </div>
              </div>
              <div class="row my-3">
                <div class="col">
                  <label for="firstName">Cantidad</label>
                  <input type="number" class="form-control" id="txb_cantidad" placeholder="" value="{{$totalDevices}}" readonly>
                  <div class="quantity-invalid-feedback" style="display:none;color:red;">Por favor ingrese una cantidad mayor a 1</div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Identificadores -->
        <div class="col-md-4 card border-top-0 border-bottom-0">
          <div class="card-body">
            <h4 class="card-title">Indicadores</h4>
            <!-- Tabla con numeros de serie -->
            <table style="width:100%">
              <tr>
                <th>Número de serie</th>
                <th>Estado</th>
              </tr>
              @foreach($serialNumbers as $serialNumber)
              <tr>
                <td>{{$serialNumber->serial_number}}</td>
                <td>
                <select class="custom-select" id="{{$serialNumber->serial_number}}">
                    @switch($serialNumber->state)
                        @case('Available')
                          <option selected value="Available">Disponible</option>
                          <option value="Reserved">En prestamo/reservado</option>
                          <option value="Repairing">En reparación</option>
                          <option value="Decrease">Mermas</option>
                          <option value="Exclusive">Exclusivos clase</option>
                          @break
                        @case('Reserved')
                            <option value="Available">Disponible</option>
                            <option selected value="Reserved">En prestamo/reservado</option>
                            <option value="Repairing">En reparación</option>
                            <option value="Decrease">Mermas</option>
                            <option value="Exclusive">Exclusivos clase</option>
                            @break
                        @case('Repairing')
                          <option value="Available">Disponible</option>
                          <option value="Reserved">En prestamo/reservado</option>
                          <option selected value="Repairing">En reparación</option>
                          <option value="Decrease">Mermas</option>
                          <option value="Exclusive">Exclusivos clase</option>
                          @break
                        @case('Decrease')
                          <option value="Available">Disponible</option>
                          <option value="Reserved">En prestamo/reservado</option>
                          <option value="Repairing">En reparación</option>
                          <option selected value="Decrease">Mermas</option>
                          <option value="Exclusive">Exclusivos clase</option>
                          @break
                        @case('Exclusive')
                          <option value="Available">Disponible</option>
                          <option value="Reserved">En prestamo/reservado</option>
                          <option value="Repairing">En reparación</option>
                          <option value="Decrease">Mermas</option>
                          <option selected value="Exclusive">Exclusivos clase</option>
                          @break
                        @default
                          <option selected value="">N/A</option>
                    @endswitch
                  </select>
                </td>
              </tr>
              @endforeach
            </table>

          </div>
        </div>

        <!-- Others -->
        <div class="col-md-4 card border-0">
          <div class="card-body">
            <h4 class="card-title">Ubicación</h4>
            <div class="row my-3">
              <div class="col-md-6">
                <label for="firstName">Edificio</label>
                <input type="text" class="form-control" id="txb_salon" placeholder="" value="{{$location->building}}" readonly>
                <div class="building-invalid-feedback" style="display:none;color:red;">Por favor seleccione un edificio</div>
              </div>
              <div class="col-md-6">
                <label for="firstName">Salón</label>
                <input type="text" class="form-control" id="txb_salon" placeholder="" value="{{$location->room}}" readonly>
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
                id="txa_etiquetas">{{$tags}}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <!-- Boton de enviar -->
      <div class="row">
        <div class="col">
          <button id="btn-edit" class="btn btn-primary btn-lg btn-block" type="submit">Actualizar</button>
        </div>
      </div>
    </div>
  </div>
<br>
</main>

@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function(){

    var allowPost = false;
    var oldStatus = [];
    var serial_numbers = [];

    $.ajax({
      url : route('device.get.serialNumbers', $('#txb_modelo').val()),
      type : 'GET',
      dataType: 'json',
      success: function (jsonReceived) {
        for(var i = 0; i < jsonReceived.length; i++){
          oldStatus.push(jsonReceived[i].state);
          serial_numbers.push(jsonReceived[i].serial_number);
        }
      }
    });

    $('#btn-edit').on('click', function () {

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

      var model          = $('#txb_modelo').val();
      var name           = $('#txb_nombre').val();
      var brand          = $('#txb_marca').val();
      var tags           = $('#txa_etiquetas').val();
      var newStatus      = [];
      var oldTags        = '{{$tags}}';
      var newTags        = tags.replace(/\s/g,'');
      oldTags = oldTags.replace(/\s/g,'');

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

      //Gets the new status of the devices
      var i;
      for(i = 0; i < serial_numbers.length; i++){
        var status = $('#'+serial_numbers[i]).val();
        newStatus.push(status);
      }
      console.log(serial_numbers, newStatus, oldStatus);

      // Let's allow the post if no one of the previous conditions are false
      if(
        (name != "") &&
        (brand != "") &&
        (serial_numbers != []) &&
        (newStatus != []) &&
        (newTags != "")
      ){
        allowPost = true;
      }else{
        // One or more of the conditions are false
      }

      if(allowPost == true){
        var data = {
          _token         : '{{csrf_token()}}',
          model          : model,
          name           : name,
          brand          : brand,
          oldTags        : oldTags,
          newTags        : newTags,
          serial_numbers : serial_numbers,
          oldStatus      : oldStatus,
          newStatus      : newStatus
        };

        $.ajax({
          url : route('device.edit'),
          type : 'POST',
          data: data,
          dataType: 'json',
          success: function (jsonReceived) {

            if(jsonReceived.status == 1){
              $('.devices-success').show();
              $('.devices-error').hide();
              console.log(jsonReceived);
            }

            if(jsonReceived.status == 2){
              $('.devices-error').show();
              $('.devices-success').hide();
            }

          }
        });
      }else{
        // The post function could not be triggered
      }
    });

  });
</script>
@endsection
