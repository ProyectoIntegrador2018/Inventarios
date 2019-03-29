@extends('layouts.app')

@section('content')

<!-- <button onclick="reportGeneration()">Generar reporte de préstamos</button> -->
<a href="{{ route('exportLoans') }}" >Export to Excel</a>
<!-- <form action="{{route('exportCSV')}}" method="get">
    {{ csrf_field() }}
    <button type="submit" class="btn btn-success" >Generar reporte</button>
</form> -->

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){

    });

    function reportGeneration(){
        $.ajax({
          url : '/exportCSV',
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