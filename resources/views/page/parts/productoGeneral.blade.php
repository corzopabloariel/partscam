@push("styles")
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush
<div class="wrapper-producto">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <div class="sidebar">
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
                        @endif
                    </div>
                    <div class="col-12 col-lg-6 detalles">
                        <h3 class="title" style="color: #2D3E75;">{{$datos["producto"]["nombre"]}}</h3>
                        @if(!is_null($datos["producto"]["codigo"]))
                            <p class="text-uppercase">Cód. del producto: <span>{{$datos["producto"]["codigo"]}}</span></p>
                        @endif
                        @if($datos["stock"]["cantidad"] > 0)
                            @php
                                $articulo = ($datos["stock"]["cantidad"] == 1) ? "1 Artículo" : "{$datos["stock"]["cantidad"]} Artículos";
                            @endphp
                            <p class="text-uppercase">en stock: <span>{{$articulo}}</span></p>
                            @if(empty($datos["oferta"]))
                                <h3 class="title price">${{$datos["precio"]}}</h3>
                            @else
                                <h3 class="title price"><strike class="mr-2" style="color:#A0A3A5; font-weight: normal">${{$datos["precio"]}}</strike> ${{$datos["oferta"]}}</h3>
                            @endif
                            <div class="d-flex justify-content-between align-items-start mt-4">
                                <div class="d-flex cantidad align-items-center text-uppercase">
                                    <span class="mr-2">cantidad</span><input type="number" value="1" class="form-control form-control-sm" name="" min="1" max="{{$datos['stock']['cantidad']}}" id="cantidad">
                                </div>
                                <div class="d-flex align-items-center flex-column">
                                    <button onclick="addCarrito(this,{{$datos['producto']['id']}})" class="btn btn-sm mb-2 text-uppercase"><i class="fas fa-shopping-cart mr-2"></i>compra online</button>
                                    @if(!empty($datos["producto"]['mercadolibre']))
                                        <a href="{{$datos['producto']['mercadolibre']}}" target="blank"><img src="{{ asset('images/general/mercadolibre.jpg') }}" /></a>
                                    @endif
                                </div>
                            </div>
                        @else
                        @endif
                    </div>
                </div>
                <p class="title mt-5">Productos Relacionados</p>
                <div class="wrapper-oferta">
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
                                @if(!empty($oferta))
                                    <img class="position-absolute oferta" src="{{ asset('images/general/ofertas.fw.png') }}" />
                                @endif
                                <img class="d-block w-100" onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($image) }}?t=<?php echo time(); ?>" />
                                <div class="py-2 px-3 border">
                                    <p class="text-center mb-0"><small>{{ $p["nombre"] }}</small></p>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://jqueryui.com/resources/demos/external/jquery-mousewheel/jquery.mousewheel.js"></script>
<script>
    if($("#cantidad").length)
        $( "#cantidad" ).spinner();
    
    addCarrito = function(t,idProducto) {
        let cantidad = $("#cantidad");
        let max = cantidad.attr("max");
        if(sessionStorage.carrito == undefined) 
            sessionStorage.setItem("carrito","{}");
        window.session = JSON.parse(sessionStorage.carrito);

        if(window.session[idProducto] === undefined)
            window.session[idProducto] = parseInt(cantidad.val());
        else {
            if(parseInt(max) < parseInt(window.session[idProducto]) + parseInt(cantidad.val())) {
                alert("STOCK superado. Verifique cantidad.");
                return false;
            } else {
                window.session[idProducto] = parseInt(window.session[idProducto]) + parseInt(cantidad.val());
            }
        }
        sessionStorage.carrito = JSON.stringify(window.session);
        alert("Producto agregado")
    }
</script>
@endpush