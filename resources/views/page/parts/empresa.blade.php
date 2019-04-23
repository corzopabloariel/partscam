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
<div class="wrapper-empresa py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="title">{{$datos["contenido"]["CONTENIDO"]["empresa"]["titulo"]}}</h3>
                <div>
                    {!! $datos["contenido"]["CONTENIDO"]["empresa"]["texto"] !!}
                </div>
                
                <h3 class="title mt-3">{{$datos["contenido"]["CONTENIDO"]["filosofia"]["titulo"]}}</h3>
                <div>
                    {!! $datos["contenido"]["CONTENIDO"]["filosofia"]["texto"] !!}
                </div>
            </div>
            <div class="col-lg-4">
                <img class="w-100" src="{{ asset($datos['contenido']['CONTENIDO']['image']) }}" alt="" srcset="">
            </div>
        </div>
    </div>
</div>
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