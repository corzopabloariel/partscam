<div class="wrapper-carrito py-5">
    <div class="container">
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
    Mercadopago.setPublishableKey("TEST-b3d5b663-664a-4e8f-b759-de5d7c12ef8f");
    Mercadopago.createToken(form, tokenHandler);

    const src = "{{ asset('images/general/no-img.png') }}";
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
    }
    formatter = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    });
    recalcular = function(t) {
        cantidad = $(t).val();
        precio = $(t).closest("td").find(".precio").val();
        id = $(t).closest("td").find(".id").val();
        window.session[id] = cantidad;
        sessionStorage.carrito = JSON.stringify(window.session);
        $(t).closest("tr").find("td.recalcular").text(formatter.format(cantidad * precio));
    }
    /** ------------------------------------- */
    addRow = function(data) {
        console.log(data)
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
        $("table").find("tbody").find("> tr:last-child .cantidad").spinner();
    }
    /** ------------------------------------- */
    edit = function(id) {
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/categorias/productos/show/${id}') }}`;
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