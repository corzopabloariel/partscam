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
                            <ul style="" data-nivel="{{$dato['nivel']}}" data-id="{{$id}}" class="list-group">
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
                            <select name="" style="width:auto !important;" class="text-uppercase bg-light form-control rounded-0 ml-3" id="">
                                <option value="1">alfabético a-z</option>
                                <option value="1">alfabético z-a</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="ordenamiento">
                    @foreach($datos["menu"][$datos["familia"]["id"]]["hijos"] AS $k => $v)
                        <div class="col-md-4 my-2 d-flex align-self-stretch">
                            <a href="{{ URL::to('productos/categoria/'. $k) }}" class="border p-3 d-block categoria d-flex align-items-center w-100">
                                {{$v["titulo"]}}
                            </a>
                        </div>
                    @endforeach
                </div>
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
</script>
@endpush