$(document).ready(function() {
  updateNavbar();
});

function updateNavbar() {
  if(window.location.href.indexOf("inventory") > -1) {
      $('#nav-inventory').addClass("border-bottom border-5");
      $('#nav-loans').removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
      $("#nav-checkloan").removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
  }

  if(window.location.href.indexOf("deviceCreation") > -1) {
      $('#nav-inventory').addClass("border-bottom border-5");
      $('#nav-loans').removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
      $("#nav-checkloan").removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
  }

  if(window.location.href.indexOf("loansList") > -1) {
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
  if(window.location.href.indexOf("exportCSV") > -1) {
      $('#nav-inventory').removeClass("border-bottom border-5");
      $('#nav-loans').removeClass("border-bottom border-5");
      $("#nav-reports").removeClass("border-bottom border-5");
      $("#nav-checkloan").removeClass("border-bottom border-5");
      $("#nav-reports").addClass("border-bottom border-5");
  }
}
