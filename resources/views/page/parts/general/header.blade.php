<nav class="navbar navbar-expand-lg navbar-light p-0">
    <div class="container">
        <a class="navbar-brand position-absolute" href="{{ URL::to( '/' ) }}">
            <img onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($datos['empresa']['images']['logo']) }}?t=<?php echo time(); ?>" />
        </a>
        <div class="row justify-content-end w-100">
            <div class="col-10 col-lg-9 d-flex flex-column pt-3">
                <ul class="list-unstyled d-flex justify-content-end align-items-center info">
                    <li><a href="tel:{{$datos['empresa']['telefono']['tel'][0]}}"><i class="fas fa-phone-volume"></i>{{$datos['empresa']['telefono']['tel'][0]}}</a></li>
                    <li><a href="https://wa.me/{{$datos['empresa']['telefono']['wha'][0]}}"><i class="fab fa-whatsapp"></i>{{$datos['empresa']['telefono']['wha'][0]}}</a></li>
                    <li><i class="far fa-clock"></i>{!! $datos['empresa']['horario'] !!}</li>
                    <li>
                        <form action="" class="position-relative">
                            <input type="text" name="" style="width:150px" placeholder="Buscar..." id="">
                            <i class="fas fa-search position-absolute"></i>
                        </form>
                    </li>
                </ul>
                <ul class="list-unstyled menu d-flex justify-content-end align-items-center">
                    <li><a href="{{ route('empresa') }}">empresa</a></li>
                    <li><a>productos</a></li>
                    <li><a>ofertas</a></li>
                    <li><a href="{{ route('contacto') }}">contacto</a></li>
                    <li><a class="btn shadow rounded-0"><i class="fas fa-shopping-cart mr-2"></i>compra online</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>