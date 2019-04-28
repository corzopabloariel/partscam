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
                    <li class="list-group-item text-uppercase"><a href="{{ URL::to('productos') }}">Productos</a></li>
                    <li class="list-group-item text-uppercase"><a href="{{ URL::to('productos/ofertas') }}">Ofertas</a></li>
                    <li class="list-group-item text-uppercase"><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </div>
            <div class="modal-footer bg-light">
                
            </div>
        </div>
    </div>
</div>


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
                    <li class="hidden-tablet d-flex align-items-center">
                        <span class="text-truncate d-inline-block"><i class="far fa-clock"></i>{!! $datos['empresa']['horario'] !!}</span>
                    </li>
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
                    <li class="menuBTN">
                        <button class="navbar-toggler" type="button" data-toggle="modal" data-target="#menuNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <a id="carritoHeader" class="btn shadow-sm" href="{{ URL::to('carrito') }}">
                            <span class="badge badge-light mr-2">0</span>
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>