
<div class="wrapper-carrito py-5">
    <div class="container">
        <form id="form" action="{{ route('order') }}" method="post" onsubmit="event.preventDefault(); procesar(this)">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <h3 class="title text-uppercase text-center">compra online - Mercado Pago</h3>
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <fieldset>
                        <legend>DATOS PERSONALES</legend>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <input maslength="200" onblur="buscarData(this,'email')" type="email" placeholder="Email" name="email" class="form-control">
                            </div>
                            <div class="col-md-6 col-12">
                                <input maxlength="20" onblur="buscarData(this,'cuit')" type="text" id="cuit" name="cuit" placeholder="CUIT" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <input maxlength="100" disabled type="text" name="nombre" id="nombre" placeholder="Nombre" class="form-control">
                            </div>
                            <div class="col-md-6 col-12">
                                <input maxlength="100" disabled type="text" name="apellido" id="apellido" placeholder="Apellido" class="form-control">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-12">
                                <input maxlength="100" disabled type="text" name="telefono" id="telefono" placeholder="Teléfono" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-6">
                            <fieldset>
                                <legend>CONDICIÓN FRENTE AL IVA</legend>
                                <select disabled id="condicioniva_id" name="condicioniva_id" class="form-control"></select>
                            </fieldset>
                        </div>
                    </div>    
                    
                    <fieldset>
                        <legend>DOMICILIO</legend>
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-12">
                                <input maxlength="250" disabled id="domicilio" type="text" name="domicilio" placeholder="Calle, altura" class="form-control">
                            </div>
                            <div class="col-md-6 col-12">
                                <select disabled onchange="localidades(this)" id="provincia_id" name="provincia_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <select disabled id="localidad_id" name="localidad_id" class="form-control"></select>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-12">
                            <button disabled id="btnSuccess" type="submit" class="btn btn-block btn-success text-uppercase text-center">confirmar</button>
                        </div>
                    </div>
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
@push("scripts")
<script>
    window.select2 = @json($datos["select2"]);
    $("#condicioniva_id").select2({
        theme: "bootstrap",
        tags: "true",
        placeholder: "Condicion IVA",
        data: window.select2.condicion,
        width: "resolve"
    });
    $("#provincia_id").select2({
        theme: "bootstrap",
        tags: "true",
        placeholder: "Seleccione PROVINCIA",
        data: window.select2.provincia,
        width: "resolve"
    });
    $("#localidad_id").select2({
        theme: "bootstrap",
        tags: "true",
        placeholder: "Seleccione LOCALIDAD",
        width: "resolve"
    });

    buscarData = function(t, tipo) {
        let value = $(t).val();
        if(value != "") {
            let promise = new Promise(function (resolve, reject) {
                let url = `{{ url('/persona/${tipo}/${value}') }}`;
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
                        $("#btnSuccess").removeAttr("disabled");
                        if(data === null) {
                            delete window.buscar;
                            $("#nombre,#apellido,#telefono,#condicioniva_id,#domicilio,#provincia_id").removeAttr("disabled");
                        } else {
                            $("#nombre").val(data.nombre);
                            $("#apellido").val(data.apellido);
                            $("#cuit").val(data.cuit);
                            $("#email").val(data.email);
                            $("#cuit").attr("readonly",true);
                            $("#telefono").val(data.telefono);
                            $("#provincia_id").val(data.provincia_id).trigger("change");
                            $("#domicilio").val(data.domicilio);
                            $("#condicioniva_id").val(data.condicioniva_id).trigger("change");
                            window.localidadID = data.localidad_id;
                            $("#nombre,#apellido,#telefono,#condicioniva_id,#domicilio,#provincia_id").removeAttr("disabled");
                        }
                    })
            };
            if(window.buscar === undefined) {
                window.buscar = 1;
                promiseFunction();
            }
        }
    };
    localidades = function(t) {
        let id = $(t).val();
        $("#localidad_id").attr("disabled", true);
        $("#localidad_id").find("option").remove();
        $("#localidad_id").select2({
            theme: "bootstrap",
            tags: "true",
            placeholder: "Seleccione LOCALIDAD",
            width: "resolve",
            data: []
        });
        if(id == "") {
            return false;
        }
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/localidad/${id}') }}`;
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
                    $("#localidad_id").removeAttr("disabled");
                    $("#localidad_id").select2({
                        theme: "bootstrap",
                        tags: "true",
                        placeholder: "Seleccione LOCALIDAD",
                        width: "resolve",
                        data: data
                    });

                    if(window.localidadID !== undefined) {
                        $("#localidad_id").val(window.localidadID).trigger("change");
                        delete window.localidadID;
                    }
                })
        };
        promiseFunction();
    };

    procesar = function(t) {
        let formElement = document.getElementById("form");
        let request = new XMLHttpRequest();
        let formData = new FormData(formElement);
        let url = `{{ url('/order') }}`;

        console.log(url)
        request.responseType = 'json';
        formData.append("pedido", localStorage.pedido);
        formData.append("payment_method", localStorage.payment_method);
        formData.append("payment_shipping", localStorage.payment_shipping);
        
        request.open("POST", url);
        request.onload = function() {
            data = request.response;
            switch(data.tipo) {
                case "pl":
                    url = `{{ url('/pedido/ok') }}`;
                    localStorage.removeItem("carrito");
                    localStorage.removeItem("payment_method");
                    localStorage.removeItem("payment_shipping");
                    localStorage.removeItem("pedido");
                    window.location = url
                    break;
                    
                case "tb":
                    url = `{{ url('/pedido/ok') }}`;
                    localStorage.removeItem("carrito");
                    localStorage.removeItem("payment_method");
                    localStorage.removeItem("payment_shipping");
                    localStorage.removeItem("pedido");
                    window.location = url
                    
                case "mp":
                    url = `{{ url('/pedido/mp') }}`;
                    localStorage.removeItem("carrito");
                    localStorage.removeItem("payment_method");
                    localStorage.removeItem("payment_shipping");
                    localStorage.removeItem("pedido");
                    window.location = url
            }
        }
        request.send(formData);
    }
</script>
@endpush