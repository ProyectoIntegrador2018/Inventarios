$(document).ready(function() {
  $('#dat_fechas').daterangepicker();
  $("#chb_esEstudiante").on("click", showStudentForm);
  $("#btn_reservar").on("click", sendForm)

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

  /*
  $(document).on('click', '#btn_reservar', function(){
    // alert("Botón presionado");
    // Selection of elements por the post function
    
    var model            = $("#h_modelo").text();
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

        // console.log(jsonReceived);
        if(jsonReceived.status == 1){
          
          // Para el sprint dos, que los mensajes de error que se devuelven del Back-End se pongan en el mensaje de alerta

          $('.loans-success').show();
          $('.loans-error').hide();

          // $("#h_modelo").text('');
          $('#txb_cantidad').val('');
          $('#txa_motivo').val('');
          // $('#dat_fechas').val(''); // Investigar que onda para resetear las fechas después [Tal vez al día actual y al día siguiente]
          $('#txb_nombreSolicitante').val('');
          $('#txb_idSolicitante').val('');
          $('#txb_emailSolicitante').val('');
          $('#txb_carrera').val('');
          $('#txb_nombreResponsable').val('');
          // Aquí falta la nómina la nómina del profesor responsable, si se decide por no poner, también removerse de la base de datos
          $('#txb_emailResponsable').val('');

        }else{
          $('.loans-error').show();
          $('.loans-success').hide();
        }

      }
    });

  });
  */

});

function showStudentForm() {
  console.log("CHECK!");
  var isStudent = $(this).is(":checked")

  if (isStudent) {
    $("#div_student").show()
  } else {
    $("#div_student").hide()
  }
}

function sendForm() {
  validateFields();
}

function validateFields() {

  var quantity = $("#txb_cantidad");
  var purpose = $("#txa_motivo");
  var dates = $("#dat_fechas");
  var solicitantName = $("#txb_nombreSolicitante");
  var solicitantID = $("#txb_idSolicitante");
  var solicitantEmail = $("#txb_emailSolicitante");
  var solicitantDegree = $("#txb_carrera");
  var responsableName = $("#txb_nombreResponsable");
  var responsableEmail = $("#txb_emailResponsable");
  var isStudent = $("#chb_esEstudiante").is(":checked");
  var formIsCorrect = true;

  var inputsGeneric = [quantity, purpose, dates,
                        solicitantName, solicitantID, solicitantEmail]

  var inputsStudent = [solicitantDegree, responsableName, responsableEmail];


  // Strip spaces
  for (i = 0; i < inputsGeneric.length; i++) {
      cleanInputSpaces(inputsGeneric[i]);
  }

  // Check if empty
  for (i = 0; i < inputsGeneric.length; i++) {
      checkEmpty(inputsGeneric[i]);
  }

  // Follow the same process above if the user is a student
  if (isStudent) {
    for (i = 0; i < inputsStudent.length; i++) {
        cleanInputSpaces(inputsStudent[i]);
    }
    for (i = 0; i < inputsStudent.length; i++) {
        checkEmpty(inputsStudent[i]);
    }
  }

  // Individual and specific rules for each input
  //--------------------------- Rules for QUANTITY ---------------------------//
  if (quantity.val() <= 0 || quantity.hasClass('is-invalid')) {
    quantity.addClass("is-invalid");
  } else  {
    quantity.removeClass("is-invalid");
  }
  //------------------------ Rules for SOLICITANT NAME -----------------------//
  if (solicitantName.val().match(/\d/) || solicitantName.hasClass('is-invalid')) {
    solicitantName.addClass("is-invalid");
  } else  {
    solicitantName.removeClass("is-invalid");
  }
  //----------------------- Rules for SOLICITANT EMAIL -----------------------//
  
  //---------------------------- Rules for DEGREE ----------------------------//
  if (solicitantDegree.val().match(/\d/) || solicitantDegree.hasClass('is-invalid')) {
    solicitantDegree.addClass("is-invalid");
  } else  {
    solicitantDegree.removeClass("is-invalid");
  }
  //----------------------- Rules for RESPONSABLE NAME -----------------------//
  if (responsableName.val().match(/\d/) || responsableName.hasClass('is-invalid')) {
    responsableName.addClass("is-invalid");
  } else  {
    responsableName.removeClass("is-invalid");
  }
  //---------------------- Rules for RESPONSABLE EMAIL -----------------------//

  // Check on all generic inputs if they are invalid
  for (i = 0; i < inputsGeneric.length && formIsCorrect; i++) {
    if (inputsGeneric[i].hasClass("is-invalid")) {
      formIsCorrect = false;
    }
  }

  // Check on all student related inputs if they are invalid
  if (isStudent) {
    for (i = 0; i < inputsStudent.length && formIsCorrect; i++) {
      if (inputsStudent[i].hasClass("is-invalid")) {
        formIsCorrect = false;
      }
    }
  }

  // If every input doesn't have the "is-invalid" class, then everything is ok
  if (formIsCorrect) {
    console.log("Form seems OK!");

    var model            = $("#h_modelo").text();
    var quantity         = $('#txb_cantidad').val();
    var reason           = $('#txa_motivo').val();
    var dates            = $('#dat_fechas').val();
    var applicant        = $('#txb_nombreSolicitante').val();
    var applicantID      = $('#txb_idSolicitante').val();
    var email            = $('#txb_emailSolicitante').val();
    var bachelor         = $('#txb_carrera').val();
    var responsableName  = $('#txb_nombreResponsable').val();
    var responsableEmail = $('#txb_emailResponsable').val();
    var isStudent        = $("#chb_esEstudiante").is(":checked");

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
      responsableEmail : responsableEmail,
      isStudent        : isStudent
    };

    $.ajax({
      url : '/createLoan',
      type : 'POST',
      data: data,
      dataType: 'json',
      success: function (jsonReceived) {

        // console.log(jsonReceived);
        if(jsonReceived.status == 1){
          
          // Para el sprint dos, que los mensajes de error que se devuelven del Back-End se pongan en el mensaje de alerta

          $('.loans-success').show();
          $('.loans-error').hide();

          // $("#h_modelo").text('');
          $('#txb_cantidad').val('');
          $('#txa_motivo').val('');
          // $('#dat_fechas').val(''); // Investigar que onda para resetear las fechas después [Tal vez al día actual y al día siguiente]
          $('#txb_nombreSolicitante').val('');
          $('#txb_idSolicitante').val('');
          $('#txb_emailSolicitante').val('');
          $('#txb_carrera').val('');
          $('#txb_nombreResponsable').val('');
          // Aquí falta la nómina la nómina del profesor responsable, si se decide por no poner, también removerse de la base de datos
          $('#txb_emailResponsable').val('');

        }else{
          $('.loans-error').show();
          $('.loans-success').hide();
        }

      }
    });
  }

}

function cleanInputSpaces(htmlInput) {
  // Deletes repeated spaces to a single space
  htmlInput.val(htmlInput.val().replace(/\s\s+/g, ' '));
  // Deletes spaces at the end
  htmlInput.val(htmlInput.val().replace(/\s+$/, ''));
  // Deletes spaces at the beggining
  htmlInput.val(htmlInput.val().replace(/^\s+/, ''));
}

function checkEmpty(htmlInput) {
  if (htmlInput.val() == "") {
    htmlInput.addClass("is-invalid");
  } else  {
    htmlInput.removeClass("is-invalid");
  }
}