$(document).ready(function() {
  updateNavbar();
});

function updateNavbar() {
  removeClasses();

  var navigationDictionary = {
    "inventario" :  "nav-inventory",
    "alta" :        "nav-inventory",
    "prestamos" :   "nav-loans",
    "reportes" :    "nav-reports",
    "checkLoan" :   "nav-checkloan"
  }

  for (var key in navigationDictionary) {
    if(window.location.href.indexOf(key) > -1) {
        $('#' + navigationDictionary[key]).addClass("border-bottom border-5");
    }
  }
}

function removeClasses() {
  $('#nav-inventory').removeClass("border-bottom border-5");
  $('#nav-loans').removeClass("border-bottom border-5");
  $("#nav-reports").removeClass("border-bottom border-5");
  $("#nav-checkloan").removeClass("border-bottom border-5");
  $("#nav-reports").removeClass("border-bottom border-5");
}
