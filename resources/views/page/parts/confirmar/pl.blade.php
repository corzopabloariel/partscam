
<div class="wrapper-carrito py-5">
    <div class="container">
        <form id="form" action="{{ route('order') }}" method="post" onsubmit="event.preventDefault(); procesar(this)">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <h3 class="title text-uppercase text-center">compra online - pago en el local</h3>
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <fieldset>
                        <legend>DATOS PERSONALES</legend>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <input maslength="200" onblur="buscarData(this,'email')" type="email" placeholder="Email" name="email" class="form-control">
                            </div>
                            <div class="col-md-6 col-12">
                                <input maxlength="20" onblur="buscarData(this,'cuit')" type="text" name="cuit" placeholder="CUIT" class="form-control">
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
                                <select disabled id="condicionIva" name="condicioniva_id" class="form-control"></select>
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
    $("#condicionIva").select2({
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
                            $("#nombre,#apellido,#telefono,#condicionIva,#domicilio,#provincia_id").removeAttr("disabled");
                        } else {
                            $("#nombre").val(data.nombre);
                            $("#apellido").val(data.apellido);
                            $("#cuit").val(data.cuit);
                            $("#email").val(data.email);
                            $("#cuit").attr("readonly",true);
                            $("#telefono").val(data.telefono);
                            
                            $("#domicilio").val(data.domicilio);
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
                })
        };
        promiseFunction();
    };

    procesar = function(t) {
        let formElement = document.getElementById("form");
        let request = new XMLHttpRequest();
        let formData = new FormData(formElement);
        let url = `{{ url('/order') }}`;
        request.responseType = 'json';
        formData.append("pedido", localStorage.pedido);
        request.open("POST", url);
        request.onload = function() {
            console.log(request.response);
        }
        request.send(formData);
    }
</script>
@endpush