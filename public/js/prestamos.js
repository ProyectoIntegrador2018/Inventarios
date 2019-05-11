var dbQueryLoans = [];

$(document).ready(function() {

  $.ajax({
    url : route('loan.get.all'),
    type : 'GET',
    dataType: 'json',
    success: function (jsonReceived) {
      for(var i = 0; i < jsonReceived.length; i++){
        dbQueryLoans.push(jsonReceived[i]);
      }

      if(dbQueryLoans.length == 0){
        $("#noLoans").append(
        '<div class="card">' +
          '<div class="card-header">' +
            'Registro de préstamos vacío' +
          '</div>' +
          '<div class="card-body">' +
            '<h5 class="card-title">Sin préstamos existente</h5>' +
            '<p class="card-text">No hay ningún préstamo registrado en el sistema</p>' +
            '<a href="/inventario" class="btn btn-primary">Ver los dispositivos existentes</a>' +
          '</div>' +
        '</div><br>'
        );
      }
      fillLoanTable(dbQueryLoans);
    }
  });

  $("#txb_search").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#tbl_prestamos tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
  });
});

  function loadLoanDetailsInModal(loanID){
    for(var i = 0; i < dbQueryLoans.length; i++){
      if(loanID == dbQueryLoans[i].id){
        $('#txt_solicitante_nombre').html(dbQueryLoans[i].solicitantname);
        $('#txt_solicitante_degree').html(dbQueryLoans[i].solicitantdegree);
        $('#txt_solicitante_email').html(dbQueryLoans[i].solicitantemail);
        $('#txt_solicitante_id').html(dbQueryLoans[i].solicitantid);

        $('#txt_responsable_nombre').html(setNotApplicableMessage(dbQueryLoans[i].responsablename));
        $('#txt_responsable_email').html(setNotApplicableMessage(dbQueryLoans[i].responsableemail));

        $('#txt_dispositivo_nombre').html(dbQueryLoans[i].devicename);
        $('#bdg_dispositivo_status').html(statesTraductor(dbQueryLoans[i].devicestate));
        $('#txt_dispositivo_serie').html(dbQueryLoans[i].deviceserialnumber);
        $('#txt_dispositivo_cantidad').html(dbQueryLoans[i].devicequantity);

        $('#txt_dispositivo_inicio').html(dbQueryLoans[i].loanstartdate);
        $('#txt_dispositivo_fin').html(dbQueryLoans[i].loanenddate);
        $('#txt_dispositivo_motivo').html(dbQueryLoans[i].loanreason);
      }
    }
  }

function setNotApplicableMessage(info) {
  if(info == null) {
    return "No Aplica";
  }else{
    return info;
  }
}

function loanCancel (loanID, loanStatus) {
  // Make call to DB and update loan with 'nextStatus' and 'loanID'
  var data = {
    _token     : $('meta[name="csrf-token"]').attr('content'),
    loanID     : loanID,
    loanStatus : loanStatus
  };

  callAjax(route('loan.cancel'), data);
}

function fillLoanTable(dbQueryLoans) {
  // Variable declaration
  var table = $("#tbl_prestamos");

  // Fetch from DB all the loans

  // Insert each loan into a row
  for (i = 0; i < dbQueryLoans.length; i++) {
    table.append(insertLoan(dbQueryLoans[i]))
  }
}

function setSerialNumbers(serials, htmlTarget) {
  htmlTarget.html("");
  if (serials.length > 0) {
    for (i = 0; i < serials.length; i++) {
      htmlTarget.append("<p>" + serials[i] + "</p>");
    }
  } else {
    htmlTarget.append("Este dispositivo no tiene número de serie.");
  }
}

function insertLoan(loan) {
  var row = "<tr id=\"" + loan.id + "\">";
  var data = "";

  data  = "<td>" + loan.solicitantname + "</td>";
  if(loan.responsablename == null){
  data += "<td style='color:red;'>" + "Sin responsable" + "</td>";
  }else{
    data += "<td>" + loan.responsablename + "</td>";
  }

  data += "<td>" + loan.devicename + "</td>";
  data += "<td>" + loan.devicequantity + "</td>";
  data += getHTMLstatusBadge(loan.status);
  data += getHTMLtoolButtons(loan.status, loan.id, loan.status);

  return row + data + "</tr>"
}

function statesTraductor(state){
  var stateDictionary = {
    "New":            "Nueva solicitud",
    "Cancelled":      "Cancelado",
    "Separated":      "Apartado",
    "Taken":          "Prestado",
    "Received":       "Recibido",
    "Received late":  "Recibido tarde",
    "Expired":        "Expirado"
  }

  if (state in stateDictionary) {
    return stateDictionary[state];
  } else {
    return "Pendiente";
  }
}

function getHTMLstatusBadge(status) {
  var html = "<td><div class=\"badge-lg badge-pill ";
  var badgeClass = "";

  status = statesTraductor(status);
  badgeClass = getStatusDictionary(status).badge;

  html += badgeClass + "\">" + status + "</div></td>"

  return html
}

function getStatusDictionary(status) {
  var statusDictionary = {
    'Nueva solicitud':{
        badge: "badge-primary", nextStatus: 'Apartado', english: "New" },
    'Cancelado':      {
        badge: "badge-warning", nextStatus: '', english: "Cancelled" },
    'Apartado':       {
        badge: "badge-secondary", nextStatus: 'Prestado', english: "Separated" },
    'Prestado':       {
        badge: "badge-secondary", nextStatus: 'Recibido', english: "Taken" },
    'Recibido':       {
        badge: "badge-success", nextStatus: '', english: "Received" },
    'Recibido tarde': {
        badge: "badge-info", nextStatus: '', english: "Received late" },
    'Expirado':       {
        badge: "badge-danger", nextStatus: 'Recibido tarde', english: "Expired" },
    'Pendiente':      {
      badge: "badge-dark", nextStatus: 'Nueva solicitud', english: "Expired" }
  }

  if (!(status in statusDictionary)) {
    status = 'Pendiente'
  }

  return statusDictionary[status]
}

function getHTMLtoolButtons(status, loanID, loanStatus) {

  status = statesTraductor(status);

  var commonHTML = "<button type=\"button\" class=\"btn btn-secondary w-100 border-white rounded-0 ";
  var commonFunction = createHTMLfunction(loanID, loanStatus)
  var html = "";
  var buttonNext =   commonHTML + "btn_siguiente\" onclick=";
  var buttonCancel = commonHTML + "btn_cancelar\" onclick=";
  var buttonDetails = "";

  buttonNext += "\"loanChangeStatus" + commonFunction
  buttonNext += " role=\"button\">";

  buttonCancel += "\"loanCancel" + commonFunction
  buttonCancel += " role=\"button\">";

  buttonDetails = createButtonDetails(loanID);

  html  = "<td>";
    html += "<div class=\"btn-group-sm d-flex\" role=\"group\" aria-label=\"Basic example\">";
      html += closeButtons(status, buttonNext, buttonCancel);
      html += buttonDetails + "Detalles" + "</button>";
    html += "</div>";
  html += "</td>";

  return html
}

function createHTMLfunction(loanID, loanStatus) {
  return "(" + loanID + "," + "\'" + loanStatus + "\'" + ")\"";
}

function createButtonDetails(loanID) {
  var buttonDetails;

  buttonDetails  = "<button";
  buttonDetails += " onclick=\"loadLoanDetailsInModal(" + loanID + ")\"";
  buttonDetails += " type=\"button\"";
  buttonDetails += " class=\"btn btn-secondary w-100 border-white rounded-0 btn_detalles\"";
  buttonDetails += " role=\"button\"";
  buttonDetails += " data-toggle=\"modal\"";
  buttonDetails += " data-target=\"#mdl_detallesPrestamo\">";

  return buttonDetails;
}

function closeButtons(status, buttonNext, buttonCancel) {
  var button = "";

  switch (status) {
    case 'Nueva solicitud':
      button += buttonNext + "Aprobar" + "</buton>";
      button += buttonCancel + "Rechazar" + "</buton>";
      break;

    case 'Apartado':
      button += buttonNext + "Entregar" + "</buton>";
      button += buttonCancel + "Rechazar" + "</buton>";
      break;

    case 'Prestado':
      button += buttonNext + "Recibir" + "</buton>";
      break;

    case 'Expirado':
      button += buttonNext + "Recibir" + "</buton>";
      break;

    default:
      break;
  }
  return button;
}

function loanChangeStatus(loanID, loanStatus) {
  // Get the next status
  var nextStatus = getStatusDictionary(statesTraductor(loanStatus)).next;
  nextStatus = getStatusDictionary(nextStatus).english;

  var data = {
    _token     : $('meta[name="csrf-token"]').attr('content'),
    loanID     : loanID,
    loanStatus : loanStatus
  };
  callAjax(route('loan.set.status'), data);
}

function callAjax(route, data) {
  $.ajax({
    url : route,
    type : 'POST',
    data: data,
    dataType: 'json',
    success: function (jsonReceived) {
      if(jsonReceived.status == 1){
        location.reload();
      }
    }
  });
}
