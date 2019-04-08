$(document).ready(function() {
  updateNavbar();
});

function updateNavbar() {
  if(window.location.href.indexOf("inventario") > -1) {
      $('#nav-inventory').addClass("border-bottom border-5");
      $('#nav-loans').removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
      $("#nav-checkloan").removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
  }

  if(window.location.href.indexOf("alta") > -1) {
      $('#nav-inventory').addClass("border-bottom border-5");
      $('#nav-loans').removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
      $("#nav-checkloan").removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
  }

  if(window.location.href.indexOf("prestamos") > -1) {
      $('#nav-inventory').removeClass("border-bottom border-5");
      $('#nav-loans').addClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
      $("#nav-checkloan").removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
  }

  if(window.location.href.indexOf("reportes") > -1) {
      $('#nav-inventory').removeClass("border-bottom border-5");
      $('#nav-loans').removeClass("border-bottom border-5");
      $("#nav-reports").addClass("border-bottom border-5");
      $("#nav-checkloan").removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
  }
  if(window.location.href.indexOf("checkLoan") > -1) {
      $('#nav-inventory').removeClass("border-bottom border-5");
      $('#nav-loans').removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
      $("#nav-checkloan").addClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
  }
  if(window.location.href.indexOf("reportes") > -1) {
      $('#nav-inventory').removeClass("border-bottom border-5");
      $('#nav-loans').removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
      $("#nav-checkloan").removeClass("border-bottom border-5");
      $("#nav-reports").addClass("border-bottom border-5");
  }
}
