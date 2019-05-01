
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
<div class="wrapper-oferta wrapper py-5">
    <div class="container">
        <div class="row justify-content-center">
            @foreach($datos["ofertas"] AS $o)
                <div class="col-lg-3 col-sm-6 col-12">
                    <a href="{{ URL::to('productos/producto/' . $o['producto_id']) }}" class="position-relative oferta title">
                        <div class="img position-relative">
                            <img class="position-absolute oferta" src="{{ asset('images/general/ofertas.fw.png') }}" />
                            <div></div>
                            <i class="fas fa-plus"></i>
                            <img class="d-block w-100" src="{{ asset($o['image']) }}" onError="this.src='{{ asset('images/general/no-img.png') }}'" alt="{{ $o['producto'] }}" srcset=""/>
                        </div>
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