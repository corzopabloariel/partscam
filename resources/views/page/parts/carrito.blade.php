@push("styles")
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
@endpush
<div class="wrapper-carrito py-5">
    <div class="container">
        <form action="{{ route('order') }}" method="post" onsubmit="event.preventDefault();">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <h3 class="title text-uppercase">compra online</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <th class="text-uppercase" style="width:150px;"></th>
                        <th class="text-uppercase">familia</th>
                        <th class="text-uppercase">modelo</th>
                        <th class="text-uppercase">categoría</th>
                        <th class="text-uppercase">producto</th>
                        <th class="text-uppercase">precio unitario</th>
                        <th class="text-uppercase">cantidad</th>
                        <th class="text-uppercase">subtotal</th>
                        <th class="text-uppercase">eliminar</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="row justify-content-end mt-4 custom">
                <div class="col-12 col-md-6 col-lg-4">
                    <h5 class="title border-bottom text-uppercase">Forma de envío</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_shipping" id="retiroLocal" value="local" checked>
                        <label class="form-check-label" for="retiroLocal">
                            Retiro en el local (sin cargo)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_shipping" id="aConvenir" value="aconvenir">
                        <label class="form-check-label" for="aConvenir">
                            A convenir
                        </label>
                    </div>
                    <p>Acuerdas la forma de entrega del producto después de la compra.</p>
                    
                    <div class="box-additions mt-3">
                        <h5 class="title border-bottom text-uppercase">Sistema de pago</h5>
                        <div class="box-additions-subtitle">SELECCIONE UN MÉTODO DE PAGO</div>
                        <div>
                            <label>
                                <input class="with-gap" name="payment_method" onchange="payment(this)" type="radio" value="mp" checked="">
                                <span>Mercado Pago</span>
                            </label>
                        </div>
                        <div id="payment_method_mercado_pago" class="pdl35 mgt5 color-scorpion">
                            {!! $datos["empresa"]["pago"]["mp"] !!}
                            <div class="alert alert-danger">
                                <p class="mb-0">El pago por la plataforma de <strong>Mercado Pago</strong> tiene un adicional de 9% del total.</p>
                            </div>
                        </div>
                        <div>
                            <label>
                                <input class="with-gap" name="payment_method" onchange="payment(this)" type="radio" value="tb">
                                <span>Transferencia Bancaria</span>
                            </label>
                        </div>
                        <div id="payment_method_bank" class="d-none pdl35 mgt5 color-scorpion">
                            <p><strong>BANCO:</strong> {{$datos["empresa"]["pago"]["tb"]["banco"]}}<br>
                            <strong>TIPO:</strong> {{$datos["empresa"]["pago"]["tb"]["tipo"]}}<br>
                            <strong>NRO.</strong> {{$datos["empresa"]["pago"]["tb"]["nro"]}}<br>
                            <strong>SUC.:</strong> {{$datos["empresa"]["pago"]["tb"]["suc"]}}<br>
                            <strong>NOMBRE DE LA CUENTA:</strong> {{$datos["empresa"]["pago"]["tb"]["nombre"]}}<br>
                            <strong>CBU.:</strong> {{$datos["empresa"]["pago"]["tb"]["cbu"]}}<br>
                            <strong>CUIT:</strong> {{$datos["empresa"]["pago"]["tb"]["cuit"]}}<br></p>
                            <p class="mt-2">Enviar comprobante a <a class="title" href="mailto:{{$datos['empresa']['pago']['tb']['emailpago']}}">{{$datos['empresa']['pago']['tb']['emailpago']}}</a></p>
                        </div>
                        <div id="payment_method_pago_local" class="">
                            <label>
                                <input class="with-gap" name="payment_method" onchange="payment(this)" type="radio" value="pl">
                                <span>Pago en local</span>
                            </label>
                            <div id="payment_method_pago_local_info" class="d-none pdl35 mgt5 color-scorpion">
                                {!! $datos["empresa"]["pago"]["pl"] !!}
                            </div>
                        </div>
                    </div>
                    <h5 class="title border-top pt-2 mt-2">MercadoPago 9%<big class="float-right" id="subtotal">$ 0,00</big></h5>
                    <h4 class="title mt-2 pb-2 border-bottom" style="border-color: #2D3E75 !important;">Total a pagar<big class="float-right" id="total">$ 0,00</big></h4>
                </div>
            </div>
            <div class="row justify-content-end mt-4 custom">
                <div class="col-12 col-md-4 col-xl-3">
                    <a href="{{ URL::to('productos') }}" class="btn btn-block btn-ml-inverse text-uppercase mr-2">seguir comprando</a>
                </div>
                <div class="col-12 col-md-4 col-xl-3">
                    <button type="button" disabled="true" id="btnPago" onclick="confirmarOp(this)" class="btn btn-block btn-ml text-white text-uppercase">pagar</button>
                </div>
            </div>
        </form>
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

<script>
    alertify.defaults.transition = "slide";
    alertify.defaults.theme.ok = "btn btn-primary";
    alertify.defaults.theme.cancel = "btn btn-danger";
    alertify.defaults.theme.input = "form-control";
    //Mercadopago.setPublishableKey("TEST-b3d5b663-664a-4e8f-b759-de5d7c12ef8f");
    //Mercadopago.createToken(form, tokenHandler);

    const src = "{{ asset('images/general/no-img.png') }}";
    $(document).ready(function() {
        $("[name='payment_method']").on("change", function() {
            op = $(this).val();
            
            Arr = { mp: "payment_method_mercado_pago", tb: "payment_method_bank", pl: "payment_method_pago_local_info"};
            $(".pdl35").addClass("d-none");
            $(`#${Arr[op]}`).removeClass("d-none");
        });

        $(document).on("click",".ui-button.ui-widget",function(t) {
            $(this).parent().find(".cantidad").click();
        })
    });
    confirmarOp = function(t) {
        let payment_method = $('[name="payment_method"]:checked').val();
        let payment_shipping = $('[name="payment_shipping"]:checked').val();
        let url = `{{ url('/confirmar/${payment_method}') }}`;

        localStorage.setItem("payment_method",payment_method);
        localStorage.setItem("payment_shipping",payment_shipping);
        localStorage.setItem("pedido",JSON.stringify(window.sumTotal));
        window.location = url;
        /*alertify.confirm("ATENCIÓN","Está seguro de continuar?",
            function() {
                let url = `{{ url('/order') }}`;
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open( "POST", url, true );
                xmlHttp.onload = function() {
                    resolve(xmlHttp.response);
                }
                xmlHttp.send( null );
            },
            function(){
            }
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});*/
    };
    payment = function(t) {
        total = 0;
        switch($(t).val()) {
            case "mp":
                if(window.sumTotal !== undefined) {
                    total = window.sumTotal.TOTAL;
                    total += window.sumTotal.TOTAL * .09;
                }
                $("#subtotal").parent().removeClass("d-none");
                $("#total").text(`${formatter.format(total)}`);
                $("#btnPago").text("pagar");
                break;
            default:
                if(window.sumTotal !== undefined) {
                    total = window.sumTotal.TOTAL;
                }
                $("#subtotal").parent().addClass("d-none");
                $("#total").text(`${formatter.format(total)}`);
                $("#btnPago").text("confirmar compra");
        }
    };
    recursivaCategoria = function(categoria) {
        if(categoria.padre === null || categoria.padre === undefined)
            return `${categoria.nombre}--`;
        else
            return recursivaCategoria(categoria.padre) + `, ${categoria.nombre}`;
    };
    del = function(t) {
        id = $(t).closest("tr").data("id");
        alertify.confirm("ATENCIÓN","¿Seguro de quitar el producto del carrito?",
            function(){
                $(t).closest("tr").find("+ tr").remove();
                $(t).closest("tr").remove();
                delete window.session[id];
                localStorage.carrito = JSON.stringify(window.session);
                
                window.sumTotal.TOTAL -= parseFloat(window.sumTotal[id].PRECIO);
                delete window.sumTotal[id];
                $("#subtotal").text(`${formatter.format(window.sumTotal.TOTAL * .09)}`);
                    
                total = window.sumTotal.TOTAL;
                if($('[name="payment_method"]:checked').val() == "mp")
                    total += window.sumTotal.TOTAL * .09;
                $("#total").text(`${formatter.format(window.sumTotal.TOTAL)}`);

                $("#carritoHeader").find("span").text(Object.keys(window.session).length);
                if(window.sumTotal.TOTAL == 0)
                    $("#btnPago").attr("disabled",true);
                
            },
            function(){
            }
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
    formatter = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    });
    recalcular = function(t) {
        cantidad = $(t).val();
        max = $(t).attr("max");
        if(parseInt(max) < parseInt(cantidad)) {
            $(t).val(max);
            return false;
        }
        precio = $(t).closest("td").find(".precio").val();
        id = $(t).closest("td").find(".id").val();
        stock = $(t).closest("td").find(".stock").val();
        auxStock = 0;
        flagStock = false;
        
        if(stock < cantidad) {
            auxStock = cantidad - stock;//Discrimino la cantidad a consultar
            total = precio * stock;
            flagStock = true;
        } else {
            total = precio * cantidad;
        }
        window.session[id] = cantidad;
        localStorage.carrito = JSON.stringify(window.session);
        window.sumTotal[id].PEDIDO = cantidad;
        if(flagStock) {
            tr = `<td colspan="9" class="border-bottom border-top-0">`;
                tr += `Cantidad solicitada supera STOCK disponible por <strong>${auxStock}</strong>. El excedente se consultará de forma automática cuando finalice la operación.`;
            tr += `</td>`;
        } else {
            tr = ''
            window.sumTotal.TOTAL -= parseFloat(window.sumTotal[id].PRECIO);
            window.sumTotal[id].PRECIO = parseFloat(cantidad * precio);
            window.sumTotal.TOTAL += window.sumTotal[id].PRECIO;
            $("#subtotal").text(`${formatter.format(window.sumTotal.TOTAL * .09)}`);
            
            total = window.sumTotal.TOTAL;
            if($('[name="payment_method"]:checked').val() == "mp")
                total += window.sumTotal.TOTAL * .09;

            $("#total").text(`${formatter.format(total)}`);
            trRecalcular = ``;
            trRecalcular += `<small class="d-block text-rigth">${cantidad} x ${formatter.format(precio)}</small class="d-block text-rigth">`;
            trRecalcular += `${formatter.format(cantidad * precio)}`;
            $(t).closest("tr").find("td.recalcular").html(trRecalcular);
        }
        $(t).closest("tr").find("+").html(tr)
    };
    /** ------------------------------------- */
    addRow = function(data) {
        let stockReal = data.stock.cantidad;
        let cantidadPedida = 0;//Relacion REAL / PEDIDO
        let flagStock = false;
        let auxStock = 0;
        let categorias = "";
        let modelos = "";

        window.session[data.id].modelo.forEach( function(m) {
            let url = `{{ url('/modelos/show/${m}') }}`;
            var xmlHttp = new XMLHttpRequest();
            //xmlHttp.responseType = 'json';
            xmlHttp.open( "GET", url, false );
            xmlHttp.onload = function() {
                if(modelos != "") modelos += "<br/>";
                modelos += xmlHttp.response;
                console.log(xmlHttp)
            }
            xmlHttp.send( null );
        });
        data.categorias.forEach( function(c) {
            let url = `{{ url('/categorias/show/${c}') }}`;
            var xmlHttp = new XMLHttpRequest();
            //xmlHttp.responseType = 'json';
            xmlHttp.open( "GET", url, false );
            xmlHttp.onload = function() {
                console.log(xmlHttp)
                if(categorias != "") categorias += "<br/>";
                categorias += xmlHttp.response;
            }
            xmlHttp.send( null );
        });

        if($("#btnPago").is(":disabled"))
            $("#btnPago").removeAttr("disabled");

        let ARR = [
            /* 0 */data.imagenes.length > 0 ? "{{ asset('/') }}" + data.imagenes[0].image : null,
            /* 1 */data.familia.nombre,
            /* 2 */modelos,//1° de categoria
            /* 3 */categorias,//categoria
            /* 4 */`<a href="{{ URL::to('productos/producto/${data.id}') }}" class="text-primary">${data.nombre}</a>`,//producto nombre
            /* 5 */data.oferta === null ? data.precio.precio : data.oferta.precio,//oferta !== undefined ? oferta. : producto.
            /* 6 */window.session[data.id].cantidad,//input
            /* 7 */null
        ];
        if(stockReal < ARR[6]) {
            auxStock = ARR[6] - stockReal;//Discrimino la cantidad a consultar
            ARR[7] = ARR[5] * stockReal;
            cantidadPedida = stockReal;
            flagStock = true;
        } else {
            cantidadPedida = ARR[6];
            ARR[7] = ARR[5] * ARR[6];
        }
        if(window.sumTotal === undefined) {
            window.sumTotal = {};
            window.sumTotal["TOTAL"] = 0;
        }
        if(window.sumTotal[data.id] === undefined) {
            window.sumTotal[data.id] = {};
            window.sumTotal[data.id]["PRECIO"] = 0;
            window.sumTotal[data.id]["STOCK"] = stockReal;
            window.sumTotal[data.id]["PEDIDO"] = parseInt(ARR[6]);
        }
        window.sumTotal[data.id].PRECIO = parseFloat(ARR[7]);
        window.sumTotal.TOTAL += parseFloat(ARR[7]);
        $("#subtotal").text(`${formatter.format(window.sumTotal.TOTAL * .09)}`);

        total = window.sumTotal.TOTAL;
        if($('[name="payment_method"]:checked').val() == "mp")
            total += window.sumTotal.TOTAL * .09;
        $("#total").text(`${formatter.format(total)}`);
        tr = `<tr data-id="${data.id}">`;
        console.log(ARR)
        ARR.forEach(function(e, index) {
            if(index == 0) {
                tr += `<td><img class="w-100" src="${e}" onerror="this.src='${src}'"/></td>`;
            } else if(index == 6) {
                tr +=` <td>`;
                    tr += `<input class="id" type="hidden" value="${data.id}" name="idProducto[]" >`;
                    tr += `<input class="precio" type="hidden" value="${ARR[5]}" name="precio[]" />`;
                    tr += `<input class="stock" type="hidden" value="${stockReal}" name="stock[]" />`;
                    tr += `<input onclick="recalcular(this)" type="number" value="${e}" class="form-control cantidad" name="cantidad[]" min="1" max="${data.stock.cantidad}">`;
                tr +=`</td>`;
            } else if(index == 5)
                tr += `<td class="text-right">${formatter.format(e)}</td>`;
            else if(index == 7) {
                tr += `<td class="recalcular text-right">`;
                    tr += `<small class="d-block text-rigth">${cantidadPedida} x ${formatter.format(ARR[5])}</small class="d-block text-rigth">`;
                    tr += `${formatter.format(e)}`;
                tr += `</td>`;
            } else
                tr += `<td>${e}</td>`;
        });
        tr += `<td class="text-center"><i onclick="del(this)" style="cursor: pointer" class="far fa-times-circle"></i></td>`;
        tr += `</tr>`;
        tr += `<tr class="table-warning data-id="${data.id}">`;
        if(flagStock) {
            tr += `<td colspan="9" class="border-bottom border-top-0">`;
                tr += `Cantidad solicitada supera STOCK disponible por <strong>${auxStock}</strong>. El excedente se consultará de forma automática`;
            tr += `</td>`;
        } else
        tr += `</tr>`;
        
        $("table").find("tbody").append(tr);
        $("table").find("tbody").find(".cantidad").spinner();
    };
    /** ------------------------------------- */
    edit = function(id) {
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/productos/show/${id}') }}`;
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.responseType = 'json';
            xmlHttp.open( "GET", url, true );
            xmlHttp.onload = function() {
                resolve(xmlHttp.response);
            }
            xmlHttp.send( null );
        });

        promiseFunction = () => {
            promise
                .then(function(data) {
                    console.log(data)
                    addRow(data);
                })
        };
        promiseFunction();
    };

    if(localStorage.carrito !== undefined) {
        window.session = JSON.parse(localStorage.carrito);
        for(let x in window.session) {
            edit(x);
        }
    }
</script>
@endpush