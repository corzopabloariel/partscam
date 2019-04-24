@push('styles')
<link href="{{ asset('css/slick.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('css/slick-theme.css') }}" rel="stylesheet" type="text/css" >
@endpush

@if(in_array('slider',$datos["contenido"]["PAGE"]))
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
            <img class="d-block w-100" src="{{asset($datos['slider'][$i]['image'])}}" >
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
@endif
@if(in_array('marcas',$datos["contenido"]["PAGE"]))
<div class="wrapper-marcas">
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="regular slider" id="marcas">
                    @foreach($datos["marcas"] AS $m)
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <img src="{{ asset('/')}}{{$m['image']}}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if(in_array('familias',$datos["contenido"]["PAGE"]))
<div class="wrapper-producto py-2">
    <div class="container">
        <div class="row justify-content-center">
            @foreach($datos["prodfamilias"] AS $f)
            <a href="{{ URL::to('productos/familia/' . $f['id']) }}" class="col-12 col-lg-3 my-2">
                <img onError="this.src='{{ asset('images/general/no-img.png') }}'" class="w-100" src="{{ $f['image'] }}" alt="{{ $f['nombre'] }}">
                <p class="title nombre mb-0">{{ $f["nombre"] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
@if(in_array('buscador',$datos["contenido"]["PAGE"]))
<div class="wrapper-buscador d-flex align-items-center">
    <div class="container d-flex w-100">
        <div class="row w-100">
            <div class="col-12 col-lg-7">
                <p class="title">¡Encontrá la pieza que tu camión necesita!</p>
                <p>Buscá por código, modelo o nombre del repuesto</p>
            </div>
            <div class="col-12 col-lg-5 d-flex align-items-center">
                <form action="{{ url('/buscador/home') }}" class="position-relative w-100">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="text" name="input" placeholder="Buscar Producto" class="form-control rounded-0 border-0" name="" id="">
                    <i class="fas fa-search position-absolute"></i>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@if(in_array('ofertas',$datos["contenido"]["PAGE"]))
<div class="wrapper-oferta py-5">
    <div class="container">
        <div class="row justify-content-center">
            @foreach($datos["ofertas"] AS $o)
                <div class="col-lg-3 col-sm-6 col-12">
                    <a href="{{ URL::to('productos/producto/' . $o['producto_id']) }}" class="position-relative oferta title">
                        <img class="position-absolute oferta" src="{{ asset('images/general/ofertas.fw.png') }}" />
                        <img class="d-block w-100" src="{{ asset($o['image']) }}" onError="this.src='{{ asset('images/general/no-img.png') }}'" alt="{{ $o['producto'] }}" srcset=""/>
                        <div class="py-2 px-3 border">
                            <p class="text-center w-75 mx-auto mb-0">{{ $o["producto"] }}</p>
                            <div class="d-flex justify-content-between">
                                <strike>$ {{ $o["precioAnterior"]}}</strike>
                                <span>$ {{ $o["precio"] }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@if(in_array('entrega',$datos["contenido"]["PAGE"]))
<div class="wrapper-entrega">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="text-right mr-2">{!! $datos["contenido"]["CONTENIDO"]["texto"] !!}</div>
        <img src="{{ asset($datos['contenido']['CONTENIDO']['image']) }}" alt="" srcset="">
    </div>
</div>
@endif
@if(in_array('mercadopago',$datos["contenido"]["PAGE"]))
<div class="mercadopago">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('images/general/mercadopago.fw.png') }}" alt="MercadoPago" srcset="">
            </div>
        </div>
    </div>
</div>
@endif
@push('scripts')
<script>
    slickMarcas = $("#marcas").slick({
        dots: false,
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 425,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true
                }
            }
        ]
    });
</script>
@endpush