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

});