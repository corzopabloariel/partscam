@push('styles')
<link href="{{ asset('css/slick.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('css/slick-theme.css') }}" rel="stylesheet" type="text/css" >
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
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="d-flex justify-content-end ordenamiento py-3 w-100 border-top border-bottom">
                            <div class="text-uppercase d-flex align-items-center">vista:<i onclick="ordenamiento(this,1)" class="activo fas fa-th-large ml-2"></i><i onclick="ordenamiento(this,2)" class="fas fa-th-list ml-2"></i></div>
                            <select onchange="order(this)" style="width:auto !important;" class="text-uppercase bg-light form-control rounded-0 ml-3" id="">
                                <option value="1">alfabético a-z</option>
                                <option value="2">alfabético z-a</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row wrapper" id="ordenamiento">
                    @php
                    for($i = 0 ; $i < count($datos["idsCategorias"]) ; $i++) {
                        if(count($datos["idsCategorias"]) - 1 == $i) {
                            if(empty($datos["menu"][$datos["idsCategorias"][$i]]["hijos"]))
                                $datos["menu"] = $datos["menu"][$datos["idsCategorias"][$i]]["productos"];
                            else
                                $datos["menu"] = $datos["menu"][$datos["idsCategorias"][$i]]["hijos"];
                        } else
                            $datos["menu"] = $datos["menu"][$datos["idsCategorias"][$i]]["hijos"];
                    }
                    @endphp
                    @foreach($datos["menu"] AS $i => $d)
                        @if(isset($d['imagenes']))
                        @php
                        $img = null;
                        $oferta = $d->oferta;
                        if(isset($d['imagenes'][0]))
                            $img = $d['imagenes'][0]['image'];
                        @endphp
                        <a href="{{ URL::to('productos/producto/'. $d['id']) }}" class="position-relative col-lg-4 col-md-6 col-12 mb-4">
                            <div class="img position-relative">
                                @if(!empty($oferta))
                                    <img class="position-absolute oferta" style="top: -8px; left: -8px; z-index: 11;" src="{{ asset('images/general/ofertas.fw.png') }}" />
                                @endif
                                <div></div>
                                <i class="fas fa-plus"></i>
                                <img src="{{ asset($img) }}" onError="this.src='{{ asset('images/general/no-img.png') }}'" class="w-100" />
                            </div>
                            <p class="text-center mt-1 mb-0">{{ $d['nombre'] }}</p>
                        </a>
                        @else
                        <a href="{{ URL::to('productos/categoria/'. $i) }}" onError="this.src='{{ asset('images/general/no-img.png') }}'" class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="img">
                                <div></div>
                                <i class="fas fa-plus"></i>
                                <img src="{{ asset($d['image']) }}" class="w-100" />
                            </div>
                            <p class="text-center mt-1 mb-0">{{ $d['titulo'] }}</p>
                        </a>
                        @endif
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
    order = function(t) {
        let tipo = $(t).val();
        let items = [];
        $('#ordenamiento a').each(function() {
            let name = $(this).find("p").text(),
                pos = $(this).index;
            items.push({
                item: this,
                name: name,
                pos: pos 
            });
        });
        items.sort(function(a,b) {
            console.log(a.name.localeCompare(b.name))
            if(parseInt(tipo) == 1)
                return a.name.localeCompare(b.name);
            else {
                if(a.name.localeCompare(b.name) == 1)
                    return -1;
                return 1;
            }
        });
        let target = $('#ordenamiento');
        target.empty();
        items.forEach(function(e) {
            target.append(e.item);
        });
    }
    ordenamiento = function(e,tipo) {
        $(e).parent().find(".activo").removeClass("activo");
        $(e).addClass("activo");
        if(tipo == 1) 
            $("#ordenamiento").removeClass("largo");
        else
            $("#ordenamiento").addClass("largo");
    };
</script>
@endpush