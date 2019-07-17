<footer>
    <div class="container">
        <div class="row justify-content-end pt-3">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <h5 class="title text-uppercase">categorías</h5>
                        <ul class="list-unstyled mb-0">
                            <li><a href="/">Home</a></li>
                            <li><a href="{{ route('empresa') }}">Empresa</a></li>
                            {{--<li><a href="{{ URL::to('productos') }}">Productos</a></li>
                            <li><a href="{{ URL::to('productos/ofertas') }}">Ofertas</a></li>--}}
                            <li><a href="{{ route('contacto') }}">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-lg-5">
                        <h5 class="title text-uppercase">información</h5>
                        <ul class="list-unstyled mb-0">
                            <li><a href="{{ route('pagos') }}">Pagos y envíos</a></li>
                            <li><a href="{{ route('terminos') }}">Términos y condiciones</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-lg-4">
                        <h5 class="title text-uppercase">partscam s.r.l.</h5>
                        <ul class="list-unstyled info mb-0">
                            <li class="d-flex">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="ml-2">
                                    <p class="mb-0">{!! $datos["empresa"]["domicilio"]["calle"] !!} {!! $datos["empresa"]["domicilio"]["altura"] !!}</p>
                                    <p class="mb-0">C.A.B.A | Argentina</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <i class="fas fa-phone-volume"></i>
                                <div class="ml-2">
                                    @foreach($datos["empresa"]["telefono"]["tel"] as $t)
                                        <a title="{{$t}}" class="text-truncate d-block" href="tel:{{$t}}">{!!$t!!}</a>
                                    @endforeach
                                </div>
                            </li>
                            <li class="d-flex">
                                <i class="far fa-envelope"></i>
                                <div class="ml-2">
                                    @foreach($datos["empresa"]["email"] as $e)
                                        <a title="{{$e}}" class="text-truncate d-block" href="mailto:{!!$e!!}" target="blank">{!!$e!!}</a>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top mt-3">
            <div class="col-12">
                <p style="color:#878787; font-size: 12px" class="mb-0 d-flex justify-content-between">
                    <span>© 2019</span>
                    <a href="http://osole.es" style="color:inherit" class="right text-uppercase">by osole</a>
                </p>
            </div>
        </div>
    </div>
</footer>