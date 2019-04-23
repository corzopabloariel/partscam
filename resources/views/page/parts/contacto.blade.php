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
                <div class="row">
                    <div class="col-lg-6">
                        <input placeholder="Nombre" type="text" name="" id="" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <input placeholder="Apellido" type="text" name="" id="" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <input placeholder="Email" type="email" name="" id="" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <input placeholder="Teléfono" type="phone" name="" id="" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <textarea name="" id="" rows="5" placeholder="Mensaje" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <input type="text" name="" placeholder="Marca Iveco" id="" class="form-control">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="" placeholder="Modelo" id="" class="form-control">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="" placeholder="Año" id="" class="form-control">
                    </div>
                </div>
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