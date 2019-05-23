@push("styles")
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
@endpush
<div class="modal" id="modalConsulta" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('/form/consultar') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="productoIDinput" id="productoIDinput" value="0">
                    <input type="hidden" name="productoCantidad" id="productoCantidad" value="0">
                    <div class="alert alert-warning" role="alert">
                        Se enviará una consulta del producto por la cantidad de <span id="cantidadFORM"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" required name="nombre" class="form-control" placeholder="Nombre completo *">
                    </div>
                    <div class="form-group">
                        <input type="email" required name="email" class="form-control" id="email" placeholder="Email *">
                    </div>
                    <div class="form-group">
                        <textarea name="consulta" id="" cols="30" class="form-control"></textarea>
                    </div>
                    <small class="form-text text-muted">* campos necesarios</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Consultar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="wrapper-producto">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <button class="btn btn-primary text-uppercase hidden visible-xs mb-2" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Productos
                </button>
                <div class="sidebar collapse dont-collapse-sm" id="collapseExample">
                    @foreach($datos["menu"] AS $id => $dato)
                        <h3 class="title mb-1 nombre text-left @if($id == $datos['familia']['id']) active @endif">
                            <a href="{{ URL::to('productos/familia/'. $id) }}">{{$dato["titulo"]}}</a>
                        </h3>
                        @if(count($dato["hijos"]) > 0)
                            <ul class="list-group @if($dato['activo']) active-submenu @endif">
                            @foreach ($dato["hijos"] AS $did => $ddato)
                                @include('page.parts.general._menuItem', ['id' => $did,'dato' => $ddato])
                            @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-8">
                <div class="row wrapper">
                    <div class="col-12 col-lg-6">
                        @if(count($datos["imagenes"]) > 0)
                        <div id="carouselExampleIndicators" class="carouselProducto carousel slide wrapper-slider position-relative" data-ride="carousel">
                            @if(!empty($datos["oferta"]))
                                <img class="position-absolute oferta" src="{{ asset('images/general/ofertas.fw.png') }}" style="z-index: 11;left: -7px;top: -7px;" />
                            @endif
                            <ol class="carousel-indicators">
                                @for($i = 0 ; $i < count($datos["imagenes"]) ; $i++)
                                    @if($i == 0)
                                        <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="active"></li>
                                    @else
                                        <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}"></li>
                                    @endif
                                @endfor
                            </ol>
                            <div class="carousel-inner">
                                @for($i = 0 ; $i < count($datos["imagenes"]) ; $i++)
                                @if($i == 0)
                                    <div class="carousel-item active">
                                @else
                                    <div class="carousel-item">
                                @endif 
                                    <img class="d-block w-100" onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($datos['imagenes'][$i]['image'])}}" >
                                </div>
                                @endfor
                            </div>
                        </div>
                        @else
                        <img class="d-block w-100" onError="this.src='{{ asset('images/general/no-img.png') }}'" src="" >
                        @endif
                    </div>
                    <div class="col-12 col-lg-6 detalles">
                        <h3 class="title" style="color: #2D3E75;">{{$datos["producto"]["nombre"]}}</h3>
                        @if(!is_null($datos["producto"]["codigo"]))
                            <p class="text-uppercase">Cód. del producto: <span>{{$datos["producto"]["codigo"]}}</span></p>
                        @endif
                        @if($datos["stock"]["cantidad"] > 0)
                            <p class="text-uppercase"><span>Artículo disponible</span></p>
                        @else
                            <p class="text-uppercase"><span>Sin Stock</span></p>
                        @endif

                        @if(empty($datos["oferta"]))
                            <h3 class="title price">${{$datos["precio"]}}</h3>
                        @else
                            <h3 class="title price">
                                <div class="row">
                                    <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                                        <strike class="mr-2" style="color:#A0A3A5; font-weight: normal">${{$datos["precio"]}}</strike>        
                                    </div>
                                    <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                                        ${{$datos["oferta"]}}
                                    </div>
                                </div>
                            </h3>
                        @endif
                        <div class="row mt-4">
                            <div class="col-12 col-sm-6 col-lg-12 col-xl-6 cantidad flex-column justify-content-end align-items-center text-uppercase">
                                <div class="d-flex align-items-center justify-content-end">
                                    <small class="mr-2">cantidad</small>
                                    <input type="number" value="1" class="form-control form-control-sm" name="" min="1" data-max="{{$datos['stock']['cantidad']}}" id="cantidad">
                                </div>
                                <small class="d-flex align-items-center justify-content-end w-100" id="consultarTEXT" style="margin-top: 10px;"></small>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-12 col-xl-6 align-items-start flex-column ">
                                {{--  --}}
                                <button @if($datos['stock']['cantidad'] <= 0) disabled @endif onclick="addCarrito(this,{{$datos['producto']['id']}})" class="btn btn-sm btn-carrito btn-block text-uppercase" id="btnADD"><i class="fas fa-shopping-cart mr-2"></i><small>añadir a carrito</small></button>
                                <button data-toggle="modal" data-target="#modalConsulta" onclick="consultar(this,{{$datos['producto']['id']}})" class="btn btn-warning mb-2 btn-sm btn-block text-uppercase" id="btnCONSULTAR"><small><i class="fas fa-question-circle mr-2"></i>consultar</small></button>
                            </div>
                            <div class="col-12 align-items-center flex-column">
                                @if(!empty($datos["producto"]['mercadolibre']))
                                    <a href="{{$datos['producto']['mercadolibre']}}" class="mt-2" target="blank"><img src="{{ asset('public/images/general/mercadolibre.jpg') }}" /></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($datos["productos"]) > 0)
                <p class="title mt-5">Productos Relacionados</p>
                <div class="wrapper-oferta wrapper">
                    <div class="row">
                        @foreach($datos["productos"] AS $p)
                        @php
                            $image = null;
                            $imagenes = $p->imagenes;
                            $precio = $p->precio["precio"];
                            
                            $oferta = $p->oferta;
                            if(count($imagenes))
                                $image = $imagenes[0]["image"];
                        @endphp
                        <div class="col-lg-4 col-md-6 col-12 my-2">
                            <a href="{{ URL::to('productos/producto/' . $p['id']) }}" class="position-relative oferta title">
                                <div class="img position-relative">
                                    @if(!empty($oferta))
                                        <img class="position-absolute oferta" src="{{ asset('images/general/ofertas.fw.png') }}" />
                                    @endif
                                    <div></div>
                                    <i class="fas fa-plus"></i>
                                    <img class="d-block w-100" onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($image) }}?t=<?php echo time(); ?>" />
                                </div>
                                <div class="py-2 px-3 border">
                                    <p class="text-center mb-0 text-truncate"><small>{{ $p["nombre"] }}</small></p>
                                    <div class="d-flex justify-content-center">
                                        @if(!empty($oferta))
                                            <strike class="mr-2">$ {{ number_format($oferta["precio"],2,",",".") }}</strike>
                                        @endif
                                        <span>$ {{ number_format($precio,2,",",".") }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
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
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="{{ asset('js/alertify.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://jqueryui.com/resources/demos/external/jquery-mousewheel/jquery.mousewheel.js"></script>
<script>
    if($("#cantidad").length)
        $( "#cantidad" ).spinner();
    consultar = function(t, idProducto) {
        if(window.consultarINT === undefined) window.consultarINT = 1;
        //window.consultarINT = parseInt($("#cantidad").val());
        $("#cantidadFORM").text((window.consultarINT == 1 ? "1 unidad" : `${window.consultarINT} unidades`));
        $("#productoIDinput").val(idProducto);
        $("#productoCantidad").val(window.consultarINT);
    }
    addCarrito = function(t,idProducto) {
        let cantidad = $("#cantidad");
        let max = cantidad.data("max");
        
        if(localStorage.carrito == undefined) 
            localStorage.setItem("carrito","{}");
        window.session = JSON.parse(localStorage.carrito);

        if(window.session[idProducto] === undefined) {
            window.session[idProducto] = parseInt(cantidad.val());
            console.log(max)
            $("#carritoHeader").find("span").text(Object.keys(window.session).length);
            if(max > parseInt(cantidad.val())) {
                localStorage.carrito = JSON.stringify(window.session);
                alertify.success('Producto agregado');
            } else {
                alertify.notify('Producto supera el stock disponible', 'warning');
                alertify.success(`Se agregó ${max} u. al carrito`);
                
                if(window.consultarINT === undefined) window.consultarINT = 0;
                if(cantidad.val() > max)
                    window.consultarINT = parseInt(cantidad.val()) - max;
                else
                    window.consultarINT = parseInt(cantidad.val());

                window.session[idProducto] = parseInt(max);
                localStorage.carrito = JSON.stringify(window.session);
                $("#consultarTEXT").text(`Consulta por: ${window.consultarINT}`);
                if(!$("#btnCONSULTAR").length)
                    $("#btnADD").parent().append(`<button data-toggle="modal" data-target="#modalConsulta" onclick="consultar(this,${idProducto})" class="btn btn-sm btn-block btn-warning mb-2 text-uppercase" id="btnCONSULTAR"><small><i class="fas fa-question-circle mr-2"></i>consultar</small></button>`);
            }
            $("#carritoHeader").attr("href","{{ URL::to('carrito') }}");
        } else {
            if(max >= parseInt(cantidad.val()) + parseInt(window.session[idProducto])) {
                window.session[idProducto] = parseInt(window.session[idProducto]) + parseInt(cantidad.val());
                localStorage.carrito = JSON.stringify(window.session);
                $("#carritoHeader").find("span").text(Object.keys(window.session).length);
                
                alertify.success('Producto agregado');
            } else {
                if(window.consultarINT === undefined) window.consultarINT = 0;
                
                if(cantidad.val() + parseInt(window.session[idProducto]) > max) {
                    window.consultarINT = parseInt(cantidad.val()) + parseInt(window.session[idProducto]) - max;
                    window.session[idProducto] = parseInt(max);
                    console.log(window.consultarINT)
                    console.log(parseInt(cantidad.val()) + parseInt(window.session[idProducto]) - max)
                } else {
                    window.session[idProducto] = parseInt(cantidad.val());
                    window.consultarINT = parseInt(cantidad.val());
                }
                localStorage.carrito = JSON.stringify(window.session);
                
                $("#consultarTEXT").text(`Consulta por: ${window.consultarINT}`);
                
                alertify.notify('Producto supera el stock disponible', 'warning');
            }
        }
    }
</script>
@endpush