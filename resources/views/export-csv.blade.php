@extends('layouts.app')

@section('content')

<!-- <button onclick="reportGeneration()">Generar reporte de préstamos</button> -->
<a href="{{ route('report.get.loans') }}" >Export to Excel</a>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){

    });

    function reportGeneration(){
        $.ajax({
          url : route('view.reports'),
          type : 'GET',
        //   data: data,
        //   dataType: 'json',
          success: function (jsonReceived) {

            console.log(jsonReceived.message);

          }
        });
    }

</script>
@endsection
