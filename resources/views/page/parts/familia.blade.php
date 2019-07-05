@push('styles')
<link href="{{ asset('css/slick.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('css/slick-theme.css') }}" rel="stylesheet" type="text/css" >
@endpush

<div class="wrapper-producto">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <button class="btn btn-primary text-uppercase d-block d-sm-none mb-2" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Productos
                </button>
                <div class="sidebar collapse dont-collapse-sm" id="collapseExample">
                    <div class="sidebar">
                        @foreach($datos["menu"] AS $id => $dato)
                            <h3 class="title mb-1 nombre text-left @if($id == $datos['familia']['id']) active @endif">
                                <a href="{{ URL::to('productos/familia/'. $id) }}">{{$dato["nombre"]}}</a>
                            </h3>
                            @if(isset($datos["productos"]))
                                <ul class="list-group">
                                    @foreach ($dato["modelos"] AS $modelo_id => $modelo)
                                    <li class="list-group-item @if($modelo['activo'] == 1) active-menu @endif">
                                        <span class="d-block position-relative">
                                            <a class="d-block" href="{{ URL::to('productos/familia/' . $id . '/modelo/' . $modelo_id . '/' . $modelo['tipo']) }}">{{$modelo["nombre"]}}</a><i class="fas fa-angle-down position-absolute"></i><i class="fas fa-angle-right position-absolute"></i>
                                        </span>
                                        @if(isset($modelo["categorias"]))
                                        <ul class="list-group @if($modelo['activo'] == 1)  active-submenu @endif">
                                            @foreach ($modelo["categorias"] AS $categoria_id => $categoria)
                                            <li class="list-group-item @if($categoria['activo'] == 1) active-menu @endif">
                                                <span class="d-block position-relative">
                                                    <a class="d-block" href="{{ URL::to('productos/familia/' . $id . '/modelo/' . $modelo_id . '/categoria/' . $categoria_id . '/' . $categoria['tipo']) }}">{{$categoria["nombre"]}}</a><i class="fas fa-angle-down position-absolute"></i><i class="fas fa-angle-right position-absolute"></i>
                                                </span>
                                                @if(isset($categoria["subcategorias"]))
                                                <ul class="list-group @if($categoria['activo'] == 1)  active-submenu @endif">
                                                    @foreach($categoria["subcategorias"] AS $subcategoria_id => $subcategoria)
                                                    <li class="list-group-item @if($subcategoria['activo'] == 1) active-menu @endif">
                                                        <span class="d-block position-relative">
                                                            <a class="d-block" href="{{ URL::to('productos/familia/' . $id . '/modelo/' . $modelo_id . '/categoria/' . $categoria_id . '/subcategoria/' . $subcategoria_id . '/' . $subcategoria['tipo']) }}">{{$subcategoria["nombre"]}}</a><i class="fas fa-angle-down position-absolute"></i><i class="fas fa-angle-right position-absolute"></i>
                                                        </span>
                                                        @if(isset($categoria["ssubcategorias"]))
                                                        <ul class="list-group @if($subcategoria['activo'] == 1)  active-submenu @endif">
                                                            @foreach($categoria["ssubcategorias"] AS $subcategoria_id => $subcategoria)
                                                            <li class="list-group-item @if($ssubcategoria['activo'] == 1) active-menu @endif">
                                                                <span class="d-block position-relative">
                                                                    <a class="d-block" href="{{ URL::to('productos/familia/' . $id . '/modelo/' . $modelo_id . '/categoria/' . $categoria_id . '/subcategoria/' . $subcategoria_id . '/ssubcategoria/' . $ssubcategoria['id'] . '/' . $ssubcategoria['tipo']) }}">{{$ssubcategoria["nombre"]}}</a><i class="fas fa-angle-down position-absolute"></i><i class="fas fa-angle-right position-absolute"></i>
                                                                </span>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                @if(count($dato["modelos"]) > 0)
                                    <ul class="list-group">
                                    @foreach ($dato["modelos"] AS $modelo_id => $modelo)
                                    <li class="list-group-item @if($modelo['activo'] == 1) active-menu @endif">
                                        <span class="d-block position-relative">
                                            <a class="d-block" href="{{ URL::to('productos/familia/' . $id . '/modelo/' . $id . '/1') }}">{{$modelo["nombre"]}}</a><i class="fas fa-angle-down position-absolute"></i><i class="fas fa-angle-right position-absolute"></i>
                                        </span>
                                    </li>
                                    @endforeach
                                    </ul>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="d-flex justify-content-end ordenamiento py-3 w-100 border-top border-bottom">
                        <div class="text-uppercase d-flex align-items-center hidden-tablet">vista:<i onclick="ordenamiento(this,1)" class="activo fas fa-th-large ml-2"></i><i onclick="ordenamiento(this,2)" class="fas fa-th-list ml-2"></i></div>
                            <select onchange="ordenar(this)" name="" style="width:auto !important;" class="text-uppercase bg-light form-control rounded-0 ml-3" id="">
                                <option value="ASC" @if($datos["order"] == "ASC") selected @endif>alfabético a-z</option>
                                <option value="DESC" @if($datos["order"] == "DESC") selected @endif>alfabético z-a</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="ordenamiento">
                    @if(isset($datos["productos"]))
                        @foreach($datos["productos"] AS $p)
                        @php
                        $imgs = $p->imagenes;
                        $img = null;
                        if(count($imgs) > 0)
                            $img = $imgs[0]['image'];
                        $oferta = $p->oferta;
                        
                        @endphp
                        <a href="{{ URL::to('productos/producto/'. $p['id'] . '/' . $datos['modelo_id']) }}" class="position-relative col-lg-4 col-md-6 col-12 mb-4">
                            <div class="img position-relative">
                                @if(!empty($oferta))
                                    <img class="position-absolute oferta" style="top: -8px; left: -8px; z-index: 11;" src="{{ asset('images/general/ofertas.fw.png') }}" />
                                @endif
                                <div></div>
                                <i class="fas fa-plus"></i>
                                <img src="{{ asset($img) }}" onError="this.src='{{ asset('images/general/no-img.png') }}'" class="w-100" />
                            </div>
                            <p class="text-center mt-1 mb-0">{{ $p['nombre'] }}</p>
                        </a>
                        @endforeach
                    @else
                        @if(isset($datos["productosSIN"]))
                            @foreach($datos["productosSIN"] AS $p)
                            @php
                            $imgs = $p->imagenes;
                            $img = null;
                            if(count($imgs) > 0)
                                $img = $imgs[0]['image'];
                            $oferta = $p->oferta;
                            
                            @endphp
                            <a href="{{ URL::to('productos/producto/'. $p['id']) }}" class="position-relative col-lg-4 col-md-6 col-12 mb-4">
                                <div class="img position-relative">
                                    @if(!empty($oferta))
                                        <img class="position-absolute oferta" style="top: -8px; left: -8px; z-index: 11;" src="{{ asset('images/general/ofertas.fw.png') }}" />
                                    @endif
                                    <div></div>
                                    <i class="fas fa-plus"></i>
                                    <img src="{{ asset($img) }}" onError="this.src='{{ asset('images/general/no-img.png') }}'" class="w-100" />
                                </div>
                                <p class="text-center mt-1 mb-0">{{ $p['nombre'] }}</p>
                            </a>
                            @endforeach
                        @elseif(isset($datos["modelos"]))
                            @foreach($datos["modelos"] AS $i => $m)
                            <div class="col-md-4 my-2 d-flex align-self-stretch">
                                <a href="{{ URL::to('productos/familia/'. $datos['familia']['id'] . '/modelo/'. $i . '/2') }}" class="border p-3 d-block categoria d-flex align-items-center w-100">
                                    {{$m}}
                                </a>
                            </div>
                            @endforeach
                        @endif
                    @endif
                </div>
                @isset($datos["productos"])
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">{{ $datos["productos"]->links() }}</div>
                </div>
                @endisset
                @isset($datos["productosSIN"])
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">{{ $datos["productosSIN"]->links() }}</div>
                </div>
                @endisset
                
            </div>
        </div>
    </div>
</div>


<div class="wrapper-marcas">
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="regular slider" id="marcas">
                    @foreach($datos["marcas"] AS $m)
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <img src="{{ asset('/')}}{{$m['image']}}">
                        </div>
                    @endforeach
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
<script>
    ordenamiento = function(e,tipo) {
        $(e).parent().find(".activo").removeClass("activo");
        $(e).addClass("activo");
        if(tipo == 1) 
            $("#ordenamiento").removeClass("largo");
        else
            $("#ordenamiento").addClass("largo");
    };
    ordenar = function(t) {
        let orden = $(t).val();
        let u = `${window.url}/${orden}`;
        if(u.indexOf("DESC") > 0)
            u = u.replace("/DESC","");
        if(u.indexOf("ASC") > 0)
            u = u.replace("/ASC","");
        u = `${u}/${orden}`;
        window.location = u;
    }
</script>
@endpush