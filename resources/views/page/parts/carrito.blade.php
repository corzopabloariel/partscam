@push("styles")
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush
<div class="wrapper-carrito py-5">
    <div class="container">
        <form action="{{ route('order') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <h3 class="title text-uppercase">compra online</h3>
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
            <div class="row justify-content-end mt-4 custom">
                <div class="col-12 col-md-6 col-lg-4">
                    <h5 class="title border-bottom">Forma de envío</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="retiro" id="retiroLocal" value="local" checked>
                        <label class="form-check-label" for="retiroLocal">
                            Retiro en el local (sin cargo)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="retiro" id="aConvenir" value="aconvenir">
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
                                <input class="with-gap" name="payment_method" onchange="payment(this)" type="radio" value="1" checked="">
                                <span>Mercado Pago</span>
                            </label>
                        </div>
                        <div id="payment_method_mercado_pago" class="pdl35 mgt5 color-scorpion">
                            <p>Usted podrá retirar su compra a las 24hs hábiles posteriores a recibir vía email el cobro por el pago del pedido.</p>
                            <div class="alert alert-danger">
                                <p class="mb-0">El pago por la plataforma de <strong>Mercado Pago</strong> tiene un incremento de 9%.</p>
                            </div>
                        </div>
                        <div>
                            <label>
                                <input class="with-gap" name="payment_method" onchange="payment(this)" type="radio" value="2">
                                <span>Transferencia Bancaria</span>
                            </label>
                        </div>
                        <div id="payment_method_bank" class="d-none pdl35 mgt5 color-scorpion">
                            <p><strong>BANCO:</strong><br>
                            <strong>TIPO:</strong><br>
                            <strong>NRO.</strong><br>
                            <strong>SUC.:</strong><br>
                            <strong>NOMBRE DE LA CUENTA:</strong><br>
                            <strong>CBU.:</strong><br>
                            <strong>CUIT:</strong><br>
                            Enviar comprobante a</p>
                        </div>
                        <div id="payment_method_pago_local" class="">
                            <label>
                                <input class="with-gap" name="payment_method" onchange="payment(this)" type="radio" value="3">
                                <span>Pago en local</span>
                            </label>
                            <div id="payment_method_pago_local_info" class="d-none pdl35 mgt5 color-scorpion">
                                <p>En caso que abone el pedido directamente en nuestro local, usted podrá acercarse a pagar y retirar su pedido a las 24hs hábiles posteriores a recibir vía email la solicitud de pedido.</p>
                                <p>El pago lo podrá hacer en efectivo, con tarjeta de débito o con tarjeta de crédito.</p>
                            </div>
                        </div>
                    </div>
                    <h5 class="title border-top pt-2 mt-2">MP 9%<big class="float-right" id="subtotal">$ 0,00</big></h5>
                    <h4 class="title mt-2 pb-2 border-bottom" style="border-color: #2D3E75 !important;">Total a pagar<big class="float-right" id="total">$ 0,00</big></h4>
                </div>
            </div>
            <div class="row justify-content-end mt-4 custom">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ URL::to('productos') }}" class="btn btn-ml-inverse text-uppercase mr-2">seguir comprando</a>
                    <a onclick="return confirm('¿Está seguro que desea procesar la compra?')" class="btn btn-ml text-white text-uppercase" type="button">pagar</a>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://jqueryui.com/resources/demos/external/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

<script>
    tokenHandler = {
    "id": "ff8080814cbd77a8014cc",
    "public_key": "TEST-98638d24-eb00-4dd5-82d8-4e573fac6a80",
    "card_id": null,
    "luhn_validation": true,
    "status": "active",
    "date_used": null,
    "card_number_length": 16,
    "date_created": "2015-04-16T13:06:25.525-04:00",
    "first_six_digits": "450995",
    "last_four_digits": "3704",
    "security_code_length": 3,
    "expiration_month": 6,
    "expiration_year": 2017,
    "date_last_updated": "2015-04-16T13:06:25.525-04:00",
    "date_due": "2015-04-24T13:06:25.531-04:00",
    "live_mode": false,
    "cardholder": {
        "identification": {
            "number": "23456789",
            "type": "type"
        },
        "name": "name"
    }
}
    //Mercadopago.setPublishableKey("TEST-b3d5b663-664a-4e8f-b759-de5d7c12ef8f");
    //Mercadopago.createToken(form, tokenHandler);

    const src = "{{ asset('images/general/no-img.png') }}";
    $(document).ready(function() {
        $("[name='payment_method']").on("change", function() {
            op = $(this).val();
            op = parseInt(op);
            Arr = ["payment_method_mercado_pago","payment_method_bank","payment_method_pago_local_info"];
            $(".pdl35").addClass("d-none");
            $(`#${Arr[op - 1]}`).removeClass("d-none");
        });

        $(document).on("click",".ui-button.ui-widget",function(t) {
            $(this).parent().find(".cantidad").click();
        })
    })
    payment = function(t) {
        switch(parseInt($(t).val())) {
            case 1:
                total = window.sumTotal.TOTAL;
                total += window.sumTotal.TOTAL * .09;
                $("#subtotal").parent().removeClass("d-none");
                $("#total").text(`${formatter.format(total)}`);
                break;
            default:
                total = window.sumTotal.TOTAL;
                $("#subtotal").parent().addClass("d-none");
                $("#total").text(`${formatter.format(total)}`);
        }
    };
    recursivaCategoria = function(categoria) {
        if(categoria.padre === null)
            return `${categoria.nombre}--`;
        else
            return recursivaCategoria(categoria.padre) + `, ${categoria.nombre}`;
    };
    del = function(t) {
        id = $(t).closest("tr").data("id");
        $(t).closest("tr").remove();
        delete window.session[id];
        localStorage.carrito = JSON.stringify(window.session);
        
        window.sumTotal.TOTAL -= parseFloat(window.sumTotal[id].C);
        delete window.sumTotal[id];
        $("#subtotal").text(`${formatter.format(window.sumTotal.TOTAL * .09)}`);
            
        total = window.sumTotal.TOTAL;
        if(parseInt($('[name="payment_method"]:checked').val()) == 1)
            total += window.sumTotal.TOTAL * .09;
        $("#total").text(`${formatter.format(window.sumTotal.TOTAL)}`);

        $("#carritoHeader").find("span").text(Object.keys(window.session).length);
    };
    formatter = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    });
    recalcular = function(t) {
        cantidad = $(t).val();
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
        if(flagStock) {
            tr = `<td colspan="9" class="border-bottom border-top-0">`;
                tr += `Cantidad solicitada supera STOCK disponible por <strong>${auxStock}</strong>. El excedente se consultará de forma automática cuando finalice la operación.`;
            tr += `</td>`;
        } else {
            tr = ''
            window.sumTotal.TOTAL -= parseFloat(window.sumTotal[id].C);
            window.sumTotal[id].C = parseFloat(cantidad * precio);
            window.sumTotal.TOTAL += window.sumTotal[id].C;
            $("#subtotal").text(`${formatter.format(window.sumTotal.TOTAL * .09)}`);
            
            total = window.sumTotal.TOTAL;
            if(parseInt($('[name="payment_method"]:checked').val()) == 1)
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
        let aux = recursivaCategoria(data.categoria);//data.padre
        let stockReal = data.stock.cantidad;
        let cantidadPedida = 0;//Relacion REAL / PEDIDO
        let flagStock = false;
        let auxStock = 0;
        aux = aux.split("--, ");
        let ARR = [
            /* 0 */data.imagenes.length > 0 ? "{{ asset('/') }}" + data.imagenes[0].image : null,
            /* 1 */data.familia.nombre,
            /* 2 */aux[0],//1° de categoria
            /* 3 */aux[1],//categoria
            /* 4 */data.nombre,//producto nombre
            /* 5 */data.oferta === null ? data.precio.precio : data.oferta.precio,//oferta !== undefined ? oferta. : producto.
            /* 6 */window.session[data.id],//input
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
            window.sumTotal[data.id]["C"] = 0;
            window.sumTotal[data.id]["T"] = stockReal;
        }
        window.sumTotal[data.id].C = parseFloat(ARR[7]);
        window.sumTotal.TOTAL += parseFloat(ARR[7]);
        $("#subtotal").text(`${formatter.format(window.sumTotal.TOTAL * .09)}`);

        total = window.sumTotal.TOTAL;
        if(parseInt($('[name="payment_method"]:checked').val()) == 1)
            total += window.sumTotal.TOTAL * .09;
        $("#total").text(`${formatter.format(total)}`);
        tr = `<tr data-id="${data.id}">`;
        ARR.forEach(function(e, index) {
            if(index == 0) {
                tr += `<td><img class="w-100" src="${e}" onerror="this.src='${src}'"/></td>`;
            } else if(index == 6) {
                tr +=` <td>`;
                    tr += `<input class="id" type="hidden" value="${data.id}" name="idProducto[]" >`;
                    tr += `<input class="precio" type="hidden" value="${ARR[5]}" name="precio[]" />`;
                    tr += `<input class="stock" type="hidden" value="${stockReal}" name="stock[]" />`;
                    tr += `<input onclick="recalcular(this)" type="number" value="${e}" class="form-control cantidad" name="cantidad[]" min="1" data-max="${data.stock.cantidad}">`;
                tr +=`</td>`;
            } else if(index == 5)
                tr += `<td class="text-right">${formatter.format(e)}</td>`;
            else if(index == 7) {
                tr += `<td class="recalcular text-right">`;
                    tr += `<small class="d-block text-rigth">${cantidadPedida} x ${formatter.format(ARR[7])}</small class="d-block text-rigth">`;
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
                    addRow(data);
                })
        };
        promiseFunction();
    };

    window.session = JSON.parse(localStorage.carrito);
    for(let x in window.session) {
        edit(x);
    }
</script>
@endpush