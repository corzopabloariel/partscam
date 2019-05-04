<div id="menuNav" class="modal fade menuNav" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-top-0 border-left-0 border-bottom-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Menú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-uppercase"><a href="/">Home</a></li>
                    <li class="list-group-item text-uppercase"><a href="{{ route('empresa') }}">Empresa</a></li>
                    <li class="list-group-item text-uppercase position-relative pr-1">
                        <div data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation"><a href="{{ URL::to('productos') }}">Productos</a><i class="fas fa-caret-down position-absolute" style="right: 0; top: 15px"></i></div>
                        <ul class="collapse list-group list-group-flush" id="navbarToggleExternalContent">
                            @foreach($datos["familias"] AS $i => $f)
                                <li class="list-group-item pr-1 position-relative">
                                    <div data-toggle="collapse" data-target="#navbarToggleExternalContent-{{$i}}" aria-controls="navbarToggleExternalContent-{{$i}}" aria-expanded="false" aria-label="Toggle navigation"><a href="{{ URL::to('productos/familia/' . $i) }}">{{ $f["nombre"] }}</a><i class="fas fa-caret-down position-absolute" style="right: 0; top: 15px"></i></div>
                                    <ul class="collapse list-group list-group-flush" id="navbarToggleExternalContent-{{$i}}">
                                        @foreach($f["sub"] AS $ii => $ff)
                                            <li class="list-group-item"><a href="{{ URL::to('productos/categoria/' . $ii) }}">{{ $ff }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="list-group-item text-uppercase"><a href="{{ URL::to('productos/ofertas') }}">Ofertas</a></li>
                    <li class="list-group-item text-uppercase"><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
                <p class="mt-5 mb-0 text-dark text-center">
                    <i class="far fa-clock mr-2"></i>{!! $datos['empresa']['horario'] !!}
                </p>
                <p class="my-3 text-dark text-center">
                    <a class="" href="tel:{{$datos['empresa']['telefono']['tel'][0]}}"><i class="fas fa-phone-volume mr-2"></i>{{$datos['empresa']['telefono']['tel'][0]}}</a>
                </p>
                <p class="mb-0 text-dark text-center">
                    <i class="fas fa-map-marker-alt mr-2"></i>{!! $datos["empresa"]["domicilio"]["calle"] !!} {!! $datos["empresa"]["domicilio"]["altura"] !!}<br/>C.A.B.A | Argentina
                </p>
            </div>
            <div class="modal-footer bg-light info">
                <form method="post" action="{{ url('/buscador/header') }}" class="position-relative w-100">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="text" class="form-control" name="input" placeholder="Buscar..." id="">
                    <i class="fas fa-search position-absolute"></i>
                </form>
            </div>
        </div>
    </div>
</div>


<nav class="navbar navbar-expand-lg navbar-light p-0 shadow-sm">
    <div class="container">
        <a class="navbar-brand position-absolute hidden-tablet" href="{{ URL::to( '/' ) }}">
            <img onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($datos['empresa']['images']['logo']) }}?t=<?php echo time(); ?>" />
        </a>
        <div class="row justify-content-end w-100">
            <div class="col-12 d-none col-lg-9 d-flex flex-column pt-3 pr-0" style="margin-right: -15px">
                <ul class="list-unstyled d-flex justify-content-end align-items-center info">
                    <li class="hidden-tablet"><a class="d-flex align-items-center" href="tel:{{$datos['empresa']['telefono']['tel'][0]}}"><i class="fas fa-phone-volume"></i>{{$datos['empresa']['telefono']['tel'][0]}}</a></li>
                    <li class="hidden-tablet"><a class="d-flex align-items-center" href="https://wa.me/{{$datos['empresa']['telefono']['wha'][0]}}"><i class="fab fa-whatsapp"></i>{{$datos['empresa']['telefono']['wha'][0]}}</a></li>
                    <li class="hidden-tablet d-flex align-items-center">
                        <span class="text-truncate d-inline-block"><i class="far fa-clock"></i>{!! $datos['empresa']['horario'] !!}</span>
                    </li>
                    <li class="hidden-tablet">
                        <form method="post" action="{{ url('/buscador/header') }}" class="position-relative">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="text" name="input" placeholder="Buscar..." id="">
                            <i class="fas fa-search position-absolute"></i>
                        </form>
                    </li>
                </ul>
                <ul id="ulNavFixed" class="list-unstyled menu d-flex justify-content-end align-items-center">
                    <li class="logo">
                        <a class="" href="{{ URL::to( '/' ) }}">
                            <img onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($datos['empresa']['images']['logo']) }}?t=<?php echo time(); ?>" />
                        </a>
                    </li>
                    <li class="hidden-tablet"><a href="{{ route('empresa') }}">empresa</a></li>
                    <li class="hidden-tablet">
                        <a href="{{ URL::to('productos') }}">productos</a>
                        <ul class="submenu list-unstyled">
                            @foreach($datos["familias"] AS $i => $f)
                                <li>
                                    <a href="{{ URL::to('productos/familia/' . $i) }}">{{ $f["nombre"] }}</a>
                                    <ul class="list-unstyled">
                                        @foreach($f["sub"] AS $ii => $ff)
                                            <li><a href="{{ URL::to('productos/categoria/' . $ii) }}">{{ $ff }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="hidden-tablet"><a href="{{ URL::to('productos/ofertas') }}">ofertas</a></li>
                    <li class="hidden-tablet"><a href="{{ route('contacto') }}">contacto</a></li>
                    <li class="menuBTN">
                        <button class="navbar-toggler text-white rounded-0" style="background-color:#2CC5EB;" type="button" data-toggle="modal" data-target="#menuNav">
                            <i class="fas fa-bars"></i>
                        </button>
                        <a id="carritoHeader" style="font-size: 13.5px;" class="btn btn-sm rounded-0 shadow-sm" href="">
                            <span class="">0</span>
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>