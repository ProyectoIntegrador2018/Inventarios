$(document).ready(function() {
  $('#dat_fechas').daterangepicker();
  console.log("terminado");


  $(function() {
    $('#dat_fechas').daterangepicker({
      timePicker: true,
      startDate: moment().startOf('hour'),
      endDate: moment().startOf('hour').add(32, 'hour'),
      locale: {
        format: 'M/DD hh:mm A'
      },
      maxSpan: {
        "days": 30
      }

    });
  });

  $(document).on('click', '#btn_reservar', function(){
    // alert("Botón presionado");
    // Selection of elements por the post function
    
    var model            = $("#h_modelo").val();
    var quantity         = $('#txb_cantidad').val();
    var reason           = $('#txa_motivo').val();
    var dates            = $('#dat_fechas').val();
    var applicant        = $('#txb_nombreSolicitante').val();
    var applicantID      = $('#txb_idSolicitante').val();
    var email            = $('#txb_emailSolicitante').val();
    var bachelor         = $('#txb_carrera').val();
    var responsableName  = $('#txb_nombreResponsable').val();
    var responsableEmail = $('#txb_emailResponsable').val();

    // alert(quantity + " " + reason + " " + dates + applicant + " " + applicantID + " " + email + " " + bachelor + " " + responsableName + " " + responsableEmail);

    // Hacer validaciones después

    var data = {
      _token           : $('meta[name="csrf-token"]').attr('content'),
      model            : model,
      quantity         : quantity,
      reason           : reason,
      dates            : dates,
      applicant        : applicant,
      applicantID      : applicantID,
      email            : email,
      bachelor         : bachelor,
      responsableName  : responsableName,
      responsableEmail : responsableEmail
    };

    $.ajax({
      url : '/createLoan',
      type : 'POST',
      data: data,
      dataType: 'json',
      success: function (jsonReceived) {

        console.log(jsonReceived);

      }
    });


  });

});