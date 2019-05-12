$(document).ready(function () {
  $('#dat_fechas').daterangepicker();
  $("#chb_esEstudiante").on("click", showStudentForm);
  $("#btn_reservar").on("click", sendForm)

  $(function () {
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
  validateQuantity(quantity)
  validateName(solicitantName)
  validateID(solicitantID, isStudent)
  //----------------------- Rules for SOLICITANT EMAIL -----------------------//
  var atPosition = solicitantEmail.val().indexOf("@");
  if (atPosition !== -1) {
    var domain = solicitantEmail.val().substring(atPosition + 1);
    var mail = solicitantEmail.val().substring(0, atPosition);

    if (isStudent) {
      if (mail.substring(0,1) === "A" && mail.length === 9 && checkDomain(domain) && mail === solicitantID.val()) {
        solicitantEmail.removeClass("is-invalid");
      } else {
        solicitantEmail.addClass("is-invalid");
      }
    } else {
      if (checkDomain(domain)) {
        solicitantEmail.removeClass("is-invalid");
      } else {
        solicitantEmail.addClass("is-invalid");
      }
    }
  } else {
    solicitantEmail.addClass("is-invalid");
  }

  validateDegree(solicitantDegree)
  validateName(responsableName)

  // Check on all generic inputs if they are invalid
  formIsCorrect = allInputsValid(inputsGeneric);

  // Check on all student related inputs if they are invalid
  if (isStudent) {
    formIsCorrect = allInputsValid(inputsStudent);
  }

  // If every input doesn't have the "is-invalid" class, then everything is ok
  if (formIsCorrect) {
    createLoan();
  }
}

function createLoan() {
    console.log("Form seems OK!");
    var model = $("#h_modelo").text();
    var quantity = $('#txb_cantidad').val();
    var reason = $('#txa_motivo').val();
    var dates = $('#dat_fechas').val();
    var applicant = $('#txb_nombreSolicitante').val();
    var applicantID = $('#txb_idSolicitante').val();
    var email = $('#txb_emailSolicitante').val();
    var bachelor = $('#txb_carrera').val();
    var responsableName = $('#txb_nombreResponsable').val();
    var responsableEmail = $('#txb_emailResponsable').val();
    var isStudent = $("#chb_esEstudiante").is(":checked");

    var student = 0;

    if(isStudent == true){
      student = 1;
    }

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
      isStudent        : student
    };
    ajaxCreateLoan(data);
}

function validateQuantity(quantity) {
  var availableQuantity = parseInt(document.getElementById('h_cantidad_numero').innerText);

  if (availableQuantity < parseInt($('#txb_cantidad').val()) || quantity.val() <= 0 || quantity.hasClass('is-invalid') || parseInt(document.getElementById('h_cantidad_numero').innerText) < parseInt($('#txb_cantidad').val())) {
    quantity.addClass("is-invalid");
  } else {
    quantity.removeClass("is-invalid");
  }
}

function validateName(name) {
  //------------------------ Rules for SOLICITANT NAME -----------------------//
  if (name.val().match(/\d/) || name.hasClass('is-invalid')) {
    name.addClass("is-invalid");
  } else {
    name.removeClass("is-invalid");
  }
}

function validateDegree(degree) {
  //---------------------------- Rules for DEGREE ----------------------------//
  if (degree.val().match(/\d/) || degree.hasClass('is-invalid')) {
    degree.addClass("is-invalid");
  } else {
    degree.removeClass("is-invalid");
  }
}

function validateID(id, isStudent) {
  if (isValidID(id, isStudent)) {
    id.removeClass("is-invalid");
  } else {
    id.addClass("is-invalid");
  }
}

function isValidID(id, isStudent) {
  console.log(isStudent);
  if (isStudent) {
    return (id.val().match(/(a|A)\d{8}/) || id.hasClass('is-invalid'));
  } else {
    return (id.val().match(/(l|L)\d{8}/) || id.hasClass('is-invalid'));
  }
}

function allInputsValid(inputs) {
  var formIsCorrect = true;

  for (i = 0; i < inputs.length && formIsCorrect; i++) {
    if (inputs[i].hasClass("is-invalid")) {
      formIsCorrect = false;
    }
  }

  return formIsCorrect;
}

function flushForm() {
  $('#txb_cantidad').val('');
  $('#txa_motivo').val('');
  $('#txb_nombreSolicitante').val('');
  $('#txb_idSolicitante').val('');
  $('#txb_emailSolicitante').val('');
  $('#txb_carrera').val('');
  $('#txb_nombreResponsable').val('');
  $('#txb_emailResponsable').val('');
}

function ajaxCreateLoan(data) {
  $.ajax({
    url: route('loan.create'),
    type: 'POST',
    data: data,
    dataType: 'json',
    success: function (jsonReceived) {
      $("#exampleModalCenter").modal('show');

      if (jsonReceived.status == 1) {
        // Para el sprint dos, que los mensajes de error que se devuelven del Back-End se pongan en el mensaje de alerta
        $('.loans-success').show();
        $('.loans-error').hide();
        flushForm()
      } else {
        $('.loans-error').show();
        $('.loans-success').hide();
      }

    }
  });
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
  } else {
    htmlInput.removeClass("is-invalid");
  }
}

function adminAfterLoanCreated() {
  document.location.href = route('view.inventory');
}

function afterLoanCreated() {
  document.location.href = route('view.inventoryGuest');
}

function checkDomain(mailDomain){
  if (mailDomain === "tec.mx" || mailDomain === "itesm.mx") {
    return true
  }
  return false
}
