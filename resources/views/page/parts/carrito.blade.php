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
                                <input class="with-gap" name="payment_method" type="radio" value="1" checked="">
                                <span>Mercado Pago</span>
                            </label>
                        </div>
                        <div id="payment_method_mercado_pago" class="pdl35 mgt5 color-scorpion">
                            <p>Usted podrá retirar su compra a las 24hs hábiles posteriores a recibir vía email el cobro por el pago del pedido.</p>
                        </div>
                        <div>
                            <label>
                                <input class="with-gap" name="payment_method" type="radio" value="2">
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
                                <input class="with-gap" name="payment_method" type="radio" value="3">
                                <span>Pago en local</span>
                            </label>
                            <div id="payment_method_pago_local_info" class="d-none pdl35 mgt5 color-scorpion">
                                <p>En caso que abone el pedido directamente en nuestro local, usted podrá acercarse a pagar y retirar su pedido a las 24hs hábiles posteriores a recibir vía email la solicitud de pedido.</p>
                                <p>El pago lo podrá hacer en efectivo, con tarjeta de débito o con tarjeta de crédito.</p>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="title">Total a pagar<big class="float-right" id="total">$ 0,00</big></h4>

                    <a onclick="return confirm('¿Está seguro que desea procesar la compra?')" class="btn btn-success mt-3 text-white" type="button">PROCESAR COMPRA</a>
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
    })

    recursivaCategoria = function(categoria) {
        if(categoria.padre === null)
            return `${categoria.nombre}--`;
        else
            return recursivaCategoria(categoria.padre) + `, ${categoria.nombre}`;
    }
    del = function(t) {
        id = $(t).closest("tr").data("id");
        $(t).closest("tr").remove();
        delete window.session[id];
        sessionStorage.carrito = JSON.stringify(window.session);
        
        window.sumTotal.TOTAL -= parseFloat(window.sumTotal[id]);
        delete window.sumTotal[id]
        $("#total").text(`${formatter.format(window.sumTotal.TOTAL)}`);
    }
    formatter = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    });
    recalcular = function(t) {
        cantidad = $(t).val();
        precio = $(t).closest("td").find(".precio").val();
        id = $(t).closest("td").find(".id").val();
        console.log(id)
        window.sumTotal.TOTAL -= parseFloat(window.sumTotal[id]);
        window.sumTotal[id] = parseFloat(cantidad * precio);
        window.sumTotal.TOTAL += window.sumTotal[id];
        $("#total").text(`${formatter.format(window.sumTotal.TOTAL)}`);
        
        window.session[id] = cantidad;
        sessionStorage.carrito = JSON.stringify(window.session);
        $(t).closest("tr").find("td.recalcular").text(formatter.format(cantidad * precio));
    }
    /** ------------------------------------- */
    addRow = function(data) {
        let aux = recursivaCategoria(data.categoria);//data.padre
        aux = aux.split("--, ");
        let ARR = [
            data.imagenes.length > 0 ? "{{ asset('/') }}" + data.imagenes[0].image : null,
            data.familia.nombre,
            aux[0],//1° de categoria
            aux[1],//categoria
            data.nombre,//producto nombre
            data.oferta === null ? data.precio.precio : data.oferta.precio,//oferta !== undefined ? oferta. : producto.
            window.session[data.id],//input
            null
        ]
        ARR[7] = ARR[5] * ARR[6];
        if(window.sumTotal === undefined) {
            window.sumTotal = {};
            window.sumTotal["TOTAL"] = 0;
        }
        if(window.sumTotal[data.id] === undefined)
            window.sumTotal[data.id] = 0
        window.sumTotal[data.id] = parseFloat(ARR[7]);
        window.sumTotal.TOTAL += parseFloat(ARR[7]);

        $("#total").text(`${formatter.format(window.sumTotal.TOTAL)}`);
        tr = `<tr data-id="${data.id}">`;
        ARR.forEach(function(e, index) {
            if(index == 0) {
                tr += `<td><img class="w-100" src="${e}" onerror="this.src='${src}'"/></td>`;
            } else if(index == 6) {
                tr +=` <td>`;
                    tr += `<input class="id" type="hidden" value="${data.id}" name="idProducto[]" >`;
                    tr += `<input class="precio" type="hidden" value="${ARR[5]}" name="precio[]" />`;
                    tr += `<input onchange="recalcular(this)" type="number" value="${e}" class="form-control form-control-sm cantidad" name="cantidad[]" min="1" max="${data.stock.cantidad}">`;
                tr +=`</td>`;
            } else if(index == 5)
                tr += `<td class="text-right">${formatter.format(e)}</td>`;
            else if(index == 7)
                tr += `<td class="recalcular text-right">${formatter.format(e)}</td>`;
            else
                tr += `<td>${e}</td>`;
        });
        tr += `<td class="text-center"><i onclick="del(this)" style="cursor: pointer" class="far fa-times-circle"></i></td>`;
        tr += `</tr>`;
        $("table").find("tbody").append(tr);
        $("table").find("tbody").find(".cantidad").spinner();
        
    }
    /** ------------------------------------- */
    edit = function(id) {
        console.log(id)
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

    window.session = JSON.parse(sessionStorage.carrito);
    for(let x in window.session) {
        edit(x);
    }
</script>
@endpush