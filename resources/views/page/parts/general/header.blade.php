<nav class="navbar navbar-expand-lg navbar-light p-0 shadow-sm">
    <div class="container">
        <a class="navbar-brand position-absolute" href="{{ URL::to( '/' ) }}">
            <img onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($datos['empresa']['images']['logo']) }}?t=<?php echo time(); ?>" />
        </a>
        <div class="row justify-content-end w-100">
            <div class="col-12 d-none col-lg-9 d-flex flex-column pt-3 pr-0" style="margin-right: -15px">
                <ul class="list-unstyled d-flex justify-content-end align-items-center info">
                    <li class="hidden-tablet"><a class="d-flex align-items-center" href="tel:{{$datos['empresa']['telefono']['tel'][0]}}"><i class="fas fa-phone-volume"></i>{{$datos['empresa']['telefono']['tel'][0]}}</a></li>
                    <li><a class="d-flex align-items-center" href="https://wa.me/{{$datos['empresa']['telefono']['wha'][0]}}"><i class="fab fa-whatsapp"></i>{{$datos['empresa']['telefono']['wha'][0]}}</a></li>
                    <li class="hidden-tablet d-flex align-items-center"><i class="far fa-clock"></i>{!! $datos['empresa']['horario'] !!}</li>
                    <li>
                        <form action="{{ url('/buscador/header') }}" class="position-relative">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="text" name="input" placeholder="Buscar..." id="">
                            <i class="fas fa-search position-absolute"></i>
                        </form>
                    </li>
                </ul>
                <ul class="list-unstyled menu d-flex justify-content-end align-items-center">
                    <li class="hidden-tablet"><a href="{{ route('empresa') }}">empresa</a></li>
                    <li class="hidden-tablet">
                        <a href="{{ URL::to('productos') }}">productos</a>
                        <ul class="submenu list-unstyled">
                            @foreach($datos["familias"] AS $i => $f)
                                <li><a href="{{ URL::to('productos/familia/' . $i) }}">{{ $f }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="hidden-tablet"><a href="{{ URL::to('productos/ofertas') }}">ofertas</a></li>
                    <li class="hidden-tablet"><a href="{{ route('contacto') }}">contacto</a></li>
                    <li><a class="btn shadow rounded-0"><i class="fas fa-shopping-cart mr-2"></i>compra online</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>