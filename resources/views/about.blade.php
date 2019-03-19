@extends('layouts.app')

@section('content')
  <!-- Main view -->
  <div class="container">
    <!-- About -->
    <div class="row align-items-center pt-5">
      <div class="col-md-4">
        <div class="display-3"> Acerca del sitio </div>
      </div>
      <div class="col-md-8">
        <p>Bienvenido al Inventario de Dispositivos Tec, un sitio web
        donde podrás consultar y reservar gadgets para realizar
        proyectos académicos o actividades de investigación
        personales. No requieres de un usuario para usar el sitio, solo
        necesitas contar con un correo electrónico institucional del
        Tecnológico de Monterrey. </p>
      </div>
    </div>
    <br>
    <!-- What does it do? -->
    <div class="row pt-5">
      <div class="col-md-4 px-4">
        <h1 class="text-center">Consulta el catálogo</h1>
        <p class="text-justify">Consulta la oferta de dispositivos que el
        laboratorio de computación ofrece para ti. Utilizando la barra de
        búsqueda podrás encontrar un gadget ya sea por su nombre, marca o
        modelo. </p>
      </div>
      <div class="col-md-4 px-4">
        <h1 class="text-center">Solicita un préstamo</h1>
        <p class="text-justify"> Una vez que encuentres el dispositivo que
        buscabas, llena la solicitud para pedirlo prestado. Al momento de
        realizar la solicitud, podrás ver la disponibilidad del dispositivo
        en las fechas que indiques. </p>
      </div>
      <div class="col-md-4 px-4">
        <h1 class="text-center">Revisa tus reservaciones</h1>
        <p class="text-justify"> Conoce el estado de tu solicitud de
          préstamo en línea. Aquí podrás saber si tu solicitud fue aproabada
          las fechas de entrega y devolución, y los detalles del dispositivo
          que pediste prestado. </p>
      </div>
    </div>
    <br><br>
    <!-- Acknowledgements -->
    <div class="row align-items-center pt-5">
      <div class="col-md-12">
        <div class="display-3"> Reconocimientos </div>
      </div>
      <div class="col-md-12">
        <p>Este fue un proyecto realizado para la profesora Armandina Leal
          y el Departamento de Computación, durante el curso de “Proyecto
          Integrador para el Desarrollo de Soluciones Empresariales” en el
          semestre de enero-mayo 2019.</p>
      </div>
    </div>
  </div>
@endsection
