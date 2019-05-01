<iframe class="w-100" style="height: 450px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3281.302037320648!2d-58.50106808489096!3d-34.672325768638224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc92259cd1a31%3A0x6f763169e761a758!2sPartsCam+SRL!5e0!3m2!1ses!2sar!4v1555951920958!5m2!1ses!2sar" frameborder="0" style="border:0" allowfullscreen></iframe>
<div class="wrapper-contacto py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
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
            <div class="col-lg-8">
                <form action="{{ url('/form/contacto') }}" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <input placeholder="Nombre *" required type="text" value="{{ old('nombre') }}" name="nombre" class="form-control">
                        </div>
                        <div class="col-lg-6 col-12">
                            <input placeholder="Apellido" type="text" name="apellido" value="{{ old('apellido') }}" class="form-control">
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
                        <div class="col-12">
                            <textarea name="mensaje" rows="5" placeholder="Mensaje" class="form-control">{{ old('mensaje') }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-12">
                            <input type="text" name="marca" placeholder="Marca Iveco" value="{{ old('marca') }}" class="form-control">
                        </div>
                        <div class="col-lg-4 col-12">
                            <input type="text" name="modelo" placeholder="Modelo" value="{{ old('modelo') }}" class="form-control">
                        </div>
                        <div class="col-lg-4 col-12">
                            <input type="number" name="anio" placeholder="Año" value="{{ old('anio') }}" class="form-control">
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
            </div>
        </div>
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
<script src='https://www.google.com/recaptcha/api.js'></script>
@endpush