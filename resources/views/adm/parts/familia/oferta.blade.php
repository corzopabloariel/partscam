<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <button id="btnADD" onclick="add(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>
            <form class="position-relative" action="" method="post">
                <input style="width: 350px;" type="text" name="" class="form-control" placeholder="Buscador: Nombre, Categoría y Código"/>
                <i style="right:10px;top: calc(50% - 7px); z-index: 1;" class="fas fa-search position-absolute"></i>
            </form>
        </div>
        <div style="display: none;" id="wrapper-form" class="mt-2">
            <div class="card">
                <div class="card-body">
                    <button onclick="remove(this)" type="button" class="close position-absolute" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <form id="form" novalidate class="pt-2" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="container-form"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-2" id="wrapper-tabla">
            <div class="card-body">
                <table class="table mb-0" id="tabla"></table>
                {{ $ofertas->links() }}
            </div>
        </div>
    </div>
</section>
@push('scripts_distribuidor')
<script>
    window.productos = @json($productos);
    window.pyrus = new Pyrus("ofertas", {producto: {TIPO:"OP",DATA: window.productos}});
    window.ofertas = @json($ofertas);
    window.precios = @json($precios);
    window.ofertasActivas = @json($ofertasActivas);
    
    /** ------------------------------------- */
    add = function(t, id = 0, data = null) {
        let btn = $(t);
        if(btn.is(":disabled"))
            btn.removeAttr("disabled");
        else
            btn.attr("disabled",true);
        $("#wrapper-form").toggle(800,"swing");

        $("#wrapper-tabla").toggle("fast");

        if(id != 0)
            action = `{{ url('/adm/familias/categorias/${window.pyrus.entidad}/update/${id}') }}`;
        else
            action = `{{ url('/adm/familias/categorias/${window.pyrus.entidad}/store') }}`;
        if(data !== null) {
            console.log(data)
            $(`#producto option:disabled`).removeAttr("disabled");
            $(`[name="producto"]`).val(data.producto_id).trigger("change");
            pr = data.precio + "";
            $(`[name="orden"]`).val(data.orden);
            $(`[name="precio"]`).val(pr.replace(".",","));
            $(`#precio`).focus();
            $(`#porcentaje`).focus();
        } else {
            for(let x in window.ofertasActivas)
                $(`#producto option[value="${x}"]`).attr("disabled",true);
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
    };
    /** ------------------------------------- */
    edit = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/categorias/${window.pyrus.entidad}/edit/${id}') }}`;
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
                    $(t).removeAttr("disabled");
                    add($("#btnADD"),parseInt(id),data);
                })
        };
        promiseFunction();
    };
    /** ------------------------------------- */
    erase = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/categorias/${window.pyrus.entidad}/delete/${id}') }}`;
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", url, true );
            
            xmlHttp.send( null );
            resolve(xmlHttp.responseText);
        });

        promiseFunction = () => {
            promise
                .then(function(msg) {
                    delete window.ofertasActivas[id];
                    $("#tabla").find(`tr[data-id="${id}"]`).remove();
                })
        };
        promiseFunction();
    };
    /** */
    activarCalculo = function(t) {
        let value = $(t).val();
        if(value == "" || value === null) {
            $("#precio").val("");
            $("#precio,#form button").attr("disabled",true);
            $("#porcentaje").val("");
        } else {
            $("#precio,#form button").removeAttr("disabled");
        }
    }
    calcular = function(t) {
        let valorPrecio = $(t).val();
        let producto = $("#producto").val();
        $("#porcentaje").val("");
        if(valorPrecio != "") {
            valorPrecio = valorPrecio.replace("$ ","").replace(/\./g,"").replace(",",".");
            valorPrecio = parseFloat(valorPrecio);
            console.log(valorPrecio)
            calculo = ((valorPrecio * 100) / window.precios[producto]).toFixed(2);
            calculo = calculo.replace(".",",");
            $("#porcentaje").val(calculo);
            $("#porcentaje").focus();
        }
    };
    /** ------------------------------------- */
    remove = function(t) {
        add($("#btnADD"));

        if(window.categoriaID !== undefined)
            delete window.categoriaID;

        for(let x in window.pyrus.especificacion) {
            if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                CKEDITOR.instances[x].setData('');
                continue;
            }
            if(window.pyrus.especificacion[x].TIPO == "TP_FILE")
                $(`#src-${x}`).attr("src","");
            $(`[name="${x}"]`).val("");
        }
        $("#producto").val($("#producto option:first-child").val()).trigger("change");
    };
    /** ------------------------------------- */
    init = function() {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());
        $("#form button").attr("disabled",true);
        $("#precio").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix: '$ '});
        $("#porcentaje").maskMoney({thousands:'.', decimal:',', allowZero:true, suffix: ' %'});

        if($("#form .container-form .select__2").length) {
            $("#form .container-form #producto.select__2").select2({
                theme: "bootstrap",
                tags: "true",
                allowClear: true,
                placeholder: "Seleccione: PRODUCTO",
            });
        }
        let columnas = window.pyrus.columnas();
        let table = $("#tabla");
        columnas.forEach(function(e) {
            if(!table.find("thead").length) 
                table.append('<thead class="thead-dark"></thead>');
            table.find("thead").append(`<th class="${e.CLASS}" style="width:${e.WIDTH}">${e.NAME}</th>`);
        });
        table.find("thead").append(`<th class="text-uppercase text-center" style="width:150px">acción</th>`);
        
        window.ofertas.data.forEach(function(data) {
            let tr = "";
            if(!table.find("tbody").length) 
                table.append("<tbody></tbody>");
            columnas.forEach(function(c) {
                td = data[c.COLUMN] === null ? "" : data[c.COLUMN];
                if(typeof td == 'object') {
                    switch(c.COLUMN) {
                        case "stock":
                            td = td.cantidad;
                            break;
                        case "precio":
                            td = td.precio;
                            break;
                        default:
                            td = td.nombre;
                    }
                }
                if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${td}') }}?t=${date.getTime()}`;
                    td = `<img class="w-100" src="${img}" onerror="this.src='${src}'"/>`;
                }
                tr += `<td class="${c.CLASS}">${td}</td>`;
            });
            tr += `<td class="text-center">`;
                tr += `<button onclick="edit(this,${data.id})" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>`;
                tr += `<button onclick="erase(this,${data.id})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>`;
            tr += `</td>`;
            table.find("tbody").append(`<tr data-id="${data.id}">${tr}</tr>`);
        });
    }
    /** */
    init();
</script>
@endpush