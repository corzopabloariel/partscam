
<div class="wrapper-carrito py-5">
    <div class="container">
        <div class="wrapper-oferta">
            <p>Se {{count($datos["resultados"]) == 1 ? "ha encontrado 1 resultado" : count($datos["resultados"]) . "han encontrado resultados"}} para su búsqueda <strong>"{{$datos["buscar"]}}"</strong>.</p>
            <div class="row">
                @foreach($datos["resultados"] AS $p)
                @php
                    $image = null;
                    
                    $imagenes = $p->imagenes;
                    $precio = $p->precio["precio"];
                    
                    $oferta = $p->oferta;
                    if(count($imagenes))
                        $image = $imagenes[0]["image"];
                @endphp
                <div class="col-lg-3 col-md-6 col-12 my-2">
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
<script src="{{ asset('js/alertify.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://jqueryui.com/resources/demos/external/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
@endpush