<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="modalCompra">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Transacción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container-fluid">
        <div class="card mb-0" id="wrapper-tabla">
            <div class="card-body">
                <table class="table table-striped table-hover mb-0" id="tabla">
                    <thead class="thead-light">
                        <th>Código</th>
                        <th>Pago</th>
                        <th>Envio</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Ver</th>
                    </thead>
                    <tbody>
                        @php
                            $Arr_tipopago = ["MP" => "MercadoPago", "TB" => "Transferencia bancaría", "PL" => "Pago en el local"];
                            $Arr_shipping = ["LOCAL" => "Retira en el Local","ACONVENIR" => "A convenir"];
                            $Arr_estado = [0 => "Cancelado", 1 => "Pendiente", 2 => "Completado"];
                        @endphp
                        @foreach($compras AS $c)
                        
                        <tr>
                            <td>{{ $c["codigo"] }}</td>
                            <td>{{ $Arr_tipopago[$c["tipopago"]] }}</td>
                            <td>{{ $Arr_shipping[$c["shipping"]] }}</td>
                            <td>{{ $Arr_estado[$c["estado"]] }}</td>
                            <td>{{ $c["total"] }}</td>
                            <td><button type="button" onclick="ver({{ $c['id'] }})" class="btn btn-sm btn-link"><i class="far fa-eye"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@push('scripts_distribuidor')
<script>
    const Arr_estado = ["Cancelado","Pendiente","Completado"];
    const Arr_tipopago = {MP:"MercadoPago", TB:"Transferencia bancaría", PL:"Pago en el local"};
    const Arr_shipping = {LOCAL:"Retira en el Local",ACONVENIR:"A convenir"};
    formatter = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    });
    dates = {
        string: function(d = new Date(), flagSecond = 1, formato = "ddmmaaaa") {
            day = (d.getDate() < 10 ? "0" + d.getDate() : d.getDate());
            month = d.getMonth() + 1;//los meses [0 - 11]
            month = (month < 10 ? "0" + month : month);
            year = d.getFullYear();
            hour = (d.getHours() < 10 ? "0" + d.getHours() : d.getHours());
            minute = (d.getMinutes() < 10 ? "0" + d.getMinutes() : d.getMinutes());
            if(flagSecond) {
                second = (d.getSeconds() < 10 ? "0" + d.getSeconds() : d.getSeconds());
                if(formato == "ddmmaaaa")
                    return day + "/" + month + "/" + year + " " + hour + ":" + minute + ":" + second;
                if(formato == "aaaammdd")
                    return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
            }
            if(formato == "ddmmaaaa")
                return day + "/" + month + "/" + year + " " + hour + ":" + minute;
            if(formato == "aaaammdd")
                return year + "-" + month + "-" + day + " " + hour + ":" + minute;
        },
        convert:function(d) {
            return (
                d.constructor === Date ? d :
                d.constructor === Array ? new Date(d[0],d[1],d[2]) :
                d.constructor === Number ? new Date(d) :
                d.constructor === String ? new Date(d) :
                typeof d === "object" ? new Date(d.year,d.month,d.date) :
                NaN
            );
        },
        compare:function(a,b) {
            return ((a.getTime() === b.getTime()) ? 0 : ((a.getTime() > b.getTime()) ? 1 : - 1));
        }
    }
    ver = function(id) {
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/transaccion/${id}') }}`;
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
                    let html = "";
                    html += `<h1>Estado: ${Arr_estado[data.estado]}<span class="float-right">${dates.string(new Date(data.created_at))}</span></h1>`;
                    html += `<p><strong class="text-uppercase">tipo de pago</strong> ${Arr_tipopago[data.tipopago]}</p>`;
                    html += `<p><strong class="text-uppercase">tipo de envío</strong> ${Arr_shipping[data.shipping]}</p>`;
                    html += `<h2 class="border-bottom">Transacción: ${data.codigo === null ? "-" : data.codigo}<span class="float-right">Total: ${formatter.format(data.total)}</span></h2>`;
                    html += '<blockquote class="blockquote p-2 bg-light">';
                        html += `<h3 class="text-center text-uppercase">Persona</h3>`;
                        html += `<div class="row">`;
                            html += `<div class="col-12 col-md-4">`;
                                html += `<p><strong>NOMBRE COMPLETO:</strong> ${data.persona.nombre} ${data.persona.apellido}</p>`;
                                html += `<p><strong>TELÉFONO:</strong> ${data.persona.telefono}</p>`;
                                html += `<p class="mb-0"><strong>EMAIL:</strong> <a class="text-primary" href="mailto:${data.persona.email}">${data.persona.email}</a></p>`;
                            html += `</div>`;
                            html += `<div class="col-12 col-md-4">`;
                                html += `<p><strong>DOMICILIO:</strong> ${data.persona.domicilio}</p>`;
                                html += `<p><strong>PROVINCIA:</strong> ${data.persona.provincia.nombre}</p>`;
                                html += `<p class="mb-0"><strong>LOCALIDAD:</strong> ${data.persona.localidad.nombre}</p>`;
                            html += `</div>`;
                            html += `<div class="col-12 col-md-4">`;
                                html += `<p><strong class="text-uppercase">c.u.i.t:</strong> ${data.persona.cuit}</p>`;
                                html += `<p><strong class="text-uppercase">Condición IVA:</strong> ${data.persona.iva.nombre}</p>`;
                            html += `</div>`;
                        html += `</div>`;
                    html += `</blockquote>`;
                    html += `<table class="table">`;
                        html += `<thead>`;
                            html += `<th>Producto</th>`;
                            html += `<th>Cantidad</th>`;
                            html += `<th>Pre. Total</th>`;
                            html += `<th>Familia</th>`;
                            html += `<th>Categoría</th>`;
                        html += `</thead>`;
                        html += `<tbody>`;
                        data.productos.forEach(function(p) {
                            html += `<tr>`;
                                html += `<td>${p.producto.nombre}</td>`;
                                html += `<td>${p.cantidad}</td>`;
                                html += `<td>${formatter.format(p.precio)}</td>`;
                                html += `<td>${p.producto.familia.nombre}</td>`;
                                html += `<td>${p.producto.categoria.nombre}</td>`;
                            html += `</tr>`;
                        });
                        html += `</tbody>`;
                    html += `</table>`;

                    $("#modalCompra .modal-body").html(html);
                    $("#modalCompra").modal("show");
                })
        };
        promiseFunction();
    };
</script>
@endpush