@push('styles')
<link href="{{ asset('css/slick.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('css/slick-theme.css') }}" rel="stylesheet" type="text/css" >
@endpush
<div id="carouselExampleIndicators" class="carousel slide wrapper-slider" data-ride="carousel">
    <ol class="carousel-indicators">
        @for($i = 0 ; $i < count($datos['slider']) ; $i++)
            @if($i == 0)
                <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="active"></li>
            @else
                <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}"></li>
            @endif
        @endfor
    </ol>
    <div class="carousel-inner">
        @for($i = 0 ; $i < count($datos['slider']) ; $i++)
        @if($i == 0)
            <div class="carousel-item active">
        @else
            <div class="carousel-item">
        @endif
            <img class="d-block w-100" src="{{asset('' . $datos['slider'][$i]['image'])}}" >
            <div class="carousel-caption position-absolute w-100 h-100" style="top: 0; left: 0;">
                <div class="container position-relative h-100">
                    <div class="position-absolute texto">
                        {!! $datos['slider'][$i]['texto'] !!}
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
<div class="wrapper-marcas hidden-tablet">
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="regular slider" id="marcas">
                    @foreach($datos["marcas"] AS $m)
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <img src="{{ asset('' . $m['image']) }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper-producto wrapper py-2">
    <div class="container">
        <fieldset>
            <legend>productos</legend>
            <div class="row justify-content-center">
                @foreach($datos["prodfamilias"] AS $f)
                <a href="{{ URL::to('productos/familia/' . $f['id']) }}" class="col-6 col-lg-3 my-2 position-relative">
                    <div class="img position-relative">
                        <div></div>
                        <i class="fas fa-plus"></i>
                        <img onError="this.src='{{ asset('images/general/no-img.png') }}'" class="w-100" src="{{ '' . $f['image'] }}" alt="{{ $f['nombre'] }}">
                    </div>
                    <p class="title nombre mb-0 text-truncate">{{ $f["nombre"] }}</p>
                </a>
                @endforeach
            </div>
        </fieldset>
    </div>
</div>
{{--<div class="wrapper-buscador d-flex align-items-center">
    <div class="container d-flex w-100">
        <div class="row w-100">
            <div class="col-12 col-lg-7">
                <p class="title">¡Encontrá la pieza que tu camión necesita!</p>
                <p>Buscá por código, modelo o nombre del repuesto</p>
            </div>
            <div class="col-12 col-lg-5 d-flex align-items-center">
                <form method="post" action="{{ url('/buscador/home') }}" class="position-relative w-100">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="text" name="input" placeholder="Buscar Producto" class="form-control rounded-0 border-0" name="" id="">
                    <i class="fas fa-search position-absolute"></i>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="wrapper-oferta wrapper py-5">
    <div class="container">
        <fieldset>
            <legend>Ofertas</legend>
        
            <div class="row justify-content-center">
            @foreach($datos["ofertas"] AS $o)
                <div class="col-lg-3 col-sm-6 col-12">
                    <a href="{{ URL::to('productos/producto/' . $o['producto_id']) }}" class="position-relative oferta title">
                        <div class="img position-relative">
                            <img class="position-absolute oferta" src="{{ asset('images/general/ofertas.fw.png') }}" />
                            <div></div>
                            <i class="fas fa-plus"></i>
                            <img class="d-block w-100 border border-bottom-0" src="{{ asset('' . $o['image']) }}" onError="this.src='{{ asset('images/general/no-img.png') }}'" alt="{{ $o['producto'] }}" srcset=""/>
                        </div>
                        <div class="py-2 px-3 border">
                            <p class="text-center text-truncate mx-auto mb-0">{{ $o["producto"] }}</p>
                            <div class="d-flex justify-content-between">
                                <strike>$ {{ $o["precioAnterior"]}}</strike>
                                <span>$ {{ $o["precio"] }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        </fieldset>
    </div>
</div>--}}

<div class="iconos py-5 d-flex justify-content-center">
    <ul class="d-flex justify-content-center border px-5 py-3 rounded-pill">
        @foreach($datos["contenido"]["iconos"] AS $i)
        <li class="d-flex align-items-center">
            <img src="{{ asset($i['image']) }}" class="mr-2" srcset="">
            {!! $i["texto"] !!}
        </li>
        @endforeach
    </ul>
</div>

<div class="servicio d-flex align-items-center">
    <div class="container w-100">
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                <h4 class="mb-0 title">Más de 40 años al servicio de nuestros clientes<span class="w-75"></span></h4>
            </div>
        </div>
        <div class="row">
            @foreach($datos["servicios"] AS $s)
            <div class="col-12 col-md-3">
                <img src="{{ asset($s['image']) }}" onError="this.src='{{ asset('images/general/no-img.png') }}'" class="d-block mx-auto mb-2" alt="" srcset="">
                <p class="text-center mb-0">{{ $s["titulo"] }}</p>
                <p class="text-center mb-0">{{ $s["subtitulo"] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="repuestos py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <img src="{{ asset($datos['contenido']['repuestos']['images']) }}" class="w-100 d-block">
            </div>
            <div class="col-12 col-md-6">
                <div class="title">{!! $datos['contenido']['repuestos']['frases'] !!}</div>
                <div class="row mt-3 repuesto">
                    @foreach($datos["contenido"]["repuestos"]["repuesto"] AS $r)
                    <div class="col-12 col-md-6 d-flex align-items-center mt-2">
                        <img src="{{ asset($r['image']) }}" alt="" srcset="">
                        <span class="ml-4">{{ $r["texto"] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wrapper-contacto bg-light py-5">
    <div class="container">
        <fieldset>
            <legend>consulte</legend>
            <form action="{{ url('/form/contacto') }}" method="post">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-12">
                        <input placeholder="Nombre y Apellido / Empresa *" required type="text" value="{{ old('empresa') }}" name="empresa" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <input placeholder="Email *" required type="email" name="email" value="{{ old('email') }}" class="form-control">
                    </div>
                    <div class="col-lg-6 col-12">
                        <input placeholder="Teléfono" type="phone" name="telefono" value="{{ old('telefono') }}" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-12">
                        <input type="text" name="marca" readonly value="IVECO" class="form-control">
                    </div>
                    <div class="col-lg-4 col-12">
                        <input type="text" name="modelo" placeholder="Modelo" value="{{ old('modelo') }}" class="form-control">
                    </div>
                    <div class="col-lg-4 col-12">
                        <input type="number" name="anio" placeholder="Año" value="{{ old('anio') }}" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <textarea name="mensaje" rows="5" placeholder="Consulta" class="form-control">{{ old('mensaje') }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="g-recaptcha" data-sitekey="6Lf8ypkUAAAAAKVtcM-8uln12mdOgGlaD16UcLXK"></div>
                    </div>
                    <div class="col-lg-6 col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="terminos" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                            Acepto los términos y condiciones de privacidad
                        </label>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-g text-white text-uppercase">enviar</button>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
</div>
<div class="wrapper-entrega">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="text-right mr-2">{!! $datos["contenido"]["CONTENIDO"]["texto"] !!}</div>
        <img src="{{ asset($datos['contenido']['CONTENIDO']['image']) }}" alt="" srcset="">
    </div>
</div>
<div class="mercadopago">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('images/general/mercadopago.fw.png') }}" alt="MercadoPago" srcset="">
            </div>
        </div>
    </div>
</div>
@push('scripts')
@endpush