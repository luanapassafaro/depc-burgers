@extends('web.plantilla', ['page'=>'index'])
@section('slider')
<section class="slider">
  <div id="customCarousel1" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner text-center text-md-left">
      <div class="carousel-item active">
        <div class="container">
          <div class="row">
            <div class="col-md-7 col-lg-6">
              <h1>LAS MEJORES HAMBURGUESAS</h1>
              <a href="/takeaway" class="btn btn-primary mt-2">PEDIR AHORA</a>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="container">
          <div class="row">
            <div class="col-md-7 col-lg-6">
              <h1>LAS PAPAS MÁS RICAS</h1>
              <a href="/takeaway" class="btn btn-primary mt-2">PEDIR AHORA</a>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="container">
          <div class="row">
            <div class="col-md-7 col-lg-6">
              <h1>EL PAN MÁS CROCANTE</h1>
              <a href="/takeaway" class="btn btn-primary mt-2">PEDIR AHORA</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <ol class="carousel-indicators">
        <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
        <li data-target="#customCarousel1" data-slide-to="1"></li>
        <li data-target="#customCarousel1" data-slide-to="2"></li>
      </ol>
    </div>
  </div>
</section>
@endsection
@section('contenido')
<section class="offer_section layout_padding-bottom">
  <div class="offer_container">
    <div class="container ">
      <div class="row">
        <div class="col-md-6  ">
          <div class="box ">
            <div class="img-box">
              <img src="web/images/o1.jpg" alt="">
            </div>
            <div class="detail-box">
              <h5>Tasty Thursdays</h5>
              <h6><span>20%</span> Off</h6>
              <a href="" class="btn btn-primary">
                Pedir Ahora <i class="fa fa-shopping-cart"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6  ">
          <div class="box ">
            <div class="img-box">
              <img src="web/images/o2.jpg" alt="">
            </div>
            <div class="detail-box">
              <h5>Pizza Days</h5>
              <h6><span>15%</span> Off</h6>
              <a href="" class="btn btn-primary">
                Pedir Ahora <i class="fa fa-shopping-cart"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="sucursales" class="layout_padding-bottom">
  <div class="container">
    <hgroup class="text-center">
      <h2 class="mb-5">Sucursales</h2>
      <div class="row">
        @foreach($aSucursales as $sucursal)
        <div class="col-md-6 col-lg-4 pb-3">
          <article class="sucursal text-white">
            <h3>{{ $sucursal->nombre }}</h3>
            <p>{{ $sucursal->direccion }}</p>
            <a class="mt-2" href="tel:{{ $sucursal->telefono }}"><i class="fa fa-phone"></i> {{ $sucursal->telefono }}</a>
            @if($sucursal->maps_url)
              <div class="mt-2">
                <a href="{{ $sucursal->maps_url }}" target="_blank" class="btn btn-primary">Ver en Maps</a>
              </div>
            @endif
          </article>
        </div>
        @endforeach
      </div>
    </hgroup>
  </div>
</section>
@endsection