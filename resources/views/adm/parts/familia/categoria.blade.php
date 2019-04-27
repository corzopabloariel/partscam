<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <button id="btnADD" onclick="add(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>
            <form id="formBusqueda" class="position-relative d-flex input-group input-group-lg w-50" action="" method="post">
                <select id="modelos" class="form-control input-lg select2-multiple select2-hidden-accessible" style="width: 150px"></select>
                <input style="width: 150px;" type="text" name="" class="form-control" placeholder="Buscador: Categorías"/>
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
                {{ $categorias->links() }}
            </div>
        </div>
    </div>
</section>
@push('scripts_distribuidor')
<script>
    
    const src = "{{ asset('images/general/no-img.png') }}";
    window.familias = @json($familias);
    window.familiasSelect2 = @json($familiasSelect2);
    window.pyrus = new Pyrus("categorias", {familia_id: {TIPO:"OP",DATA: window.familias}}, src);
    window.categorias = @json($categorias);
    /** */
    $("#modelos").select2({
        theme: "bootstrap",
        tags: "true",
        allowClear: true,
        placeholder: "FAMILIA",
        data: window.familiasSelect2.results,
        width: "resolve"
    });
    /** ------------------------------------- */
    readURL = function(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(`#${target}`).attr(`src`,`${e.target.result}`);
            };
            reader.readAsDataURL(input.files[0]);
        }
    };
    /** ------------------------------------- */
    add = function(t, id = 0, data = null) {
        let btn = $(t);
        if(btn.is(":disabled")){
            btn.removeAttr("disabled");
            $("#formBusqueda *").removeAttr("disabled");
        } else {
            $("#formBusqueda *").attr("disabled", true);
            btn.attr("disabled",true);
        }
        $("#wrapper-form").toggle(800,"swing");

        $("#wrapper-tabla").toggle("fast");

        if(id != 0)
            action = `{{ url('/adm/familias/${window.pyrus.entidad}/update/${id}') }}`;
        else
            action = `{{ url('/adm/familias/${window.pyrus.entidad}/store') }}`;
        if(data !== null) {
            window.categoriaID = data.id;
            for(let x in window.pyrus.especificacion) {
                if(!$(`[name="${x}"]`).length) continue;
                if(x == "padre_id") {//MODELO
                    window.padreID = data.modelo;
                    continue;
                }
                if(x == "familia_id") {//FAMILIA
                    window.familiaID = data[x];
                    continue;
                }
                if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                    CKEDITOR.instances[`${x}_es`].setData(data[x]);
                    continue;
                }
                if(window.pyrus.especificacion[x].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${data[x]}') }}?t=${date.getTime()}`;
                    $(`#src-${x}`).attr("src",img);
                    continue;
                }
                if(window.pyrus.especificacion[x].TIPO == "TP_ENUM") {
                    $(`[name="${x}"]`).val(data[x]).trigger("change");
                    continue;
                }
                $(`[name="${x}"]`).val(data[x]);
            }
            $("#familia_id").val(window.familiaID).trigger("change");
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
    };
    /** ------------------------------------- */
    erase = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/${window.pyrus.entidad}/delete/${id}') }}`;
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", url, true );
            
            xmlHttp.send( null );
            resolve(xmlHttp.responseText);
        });

        promiseFunction = () => {
            promise
                .then(function(msg) {
                    $("#tabla").find(`tr[data-id="${id}"]`).remove();
                })
        };
        promiseFunction();
    };
    /** ------------------------------------- */
    remove = function(t) {
        add($("#btnADD"));

        if(window.padreID !== undefined)
            delete window.padreID;
        if(window.modeloID !== undefined)
            delete window.modeloID;
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
        $("#familia_id").val("").trigger("change");
        $("#padre_id,#modelo_id").attr("disabled",true);
    };
    /** ------------------------------------- */
    changeFamilia = function(t, tipo) {
        id = $(t).val();
        if(id == "") {
            if(parseInt(tipo) == 1) {
                $("#modelo_id").attr("disabled",true);
                $("#modelo_id").val("").trigger("change");
                $("#modelo_id").find("option").remove();
            } else {
                $("#padre_id").attr("disabled",true);
                $("#padre_id").val("").trigger("change");
                $("#padre_id").find("option").remove();
            }
            return false;
        }
        
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/${window.pyrus.entidad}/familia_categoria/${id}/${tipo}') }}`;
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
                    $(t).removeAttr("disabled");
                    if(data !== null) {
                        console.log(tipo)
                        if(parseInt(tipo) == 1) {
                            $("#modelo_id").attr("disabled",true);
                            $("#modelo_id").removeAttr("disabled");
                            $("#modelo_id").select2({
                                theme: "bootstrap",
                                tags: "true",
                                allowClear: true,
                                placeholder: "Seleccione: MODELO",
                                data: data.results
                            });
                            if(window.padreID !== undefined) {
                                if(window.padreID != 0)
                                    $("#modelo_id").val(window.padreID).trigger("change");
                            }
                        } else {
                            $("#padre_id").attr("disabled",true);
                            $("#padre_id").removeAttr("disabled");
                            $("#padre_id").select2({
                                theme: "bootstrap",
                                tags: "true",
                                allowClear: true,
                                placeholder: "Seleccione: CATEGORÍA",
                                data: data.results
                            });
                            if(window.categoriaID !== undefined) {
                                if(window.categoriaID != 0)
                                    $("#padre_id").val(window.categoriaID).trigger("change");
                            }
                        }
                    } else {
                        if(parseInt(tipo) == 1) {
                            //$("#modelo_id").attr("disabled",true);
                            $("#modelo_id").val("").trigger("change");
                        } else {
                            $("#padre_id").attr("disabled",true);
                            $("#modelo_id").attr("disabled",true);
                            $("#padre_id").val("").trigger("change");
                        }
                    }
                })
        };
        promiseFunction();
    };
    /** ------------------------------------- */
    edit = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/${window.pyrus.entidad}/edit/${id}') }}`;
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
                    $(t).removeAttr("disabled");
                    add($("#btnADD"),parseInt(id),data);
                })
        };
        promiseFunction();
    };
    /** ------------------------------------- */
    init = function() {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());
        $("#form .container-form #modelo_id.select__2").select2({
            theme: "bootstrap",
            tags: "true",
            allowClear: true,
            placeholder: "Seleccione: MODELO",
        });
        $("#form .container-form #padre_id.select__2").select2({
            theme: "bootstrap",
            tags: "true",
            allowClear: true,
            placeholder: "Seleccione: CATEGORÍA",
        });
        $("#form .container-form #familia_id.select__2").select2({
            theme: "bootstrap",
            tags: "true",
            allowClear: true,
            placeholder: "Seleccione: FAMILIA",
        });
        let columnas = window.pyrus.columnas();
        let table = $("#tabla");
        columnas.forEach(function(e) {
            if(!table.find("thead").length) 
                table.append('<thead class="thead-dark"></thead>');
            table.find("thead").append(`<th class="${e.CLASS}" style="width:${e.WIDTH}">${e.NAME}</th>`);
        });
        table.find("thead").append(`<th class="text-uppercase text-center" style="width:150px">acción</th>`);
        
        window.categorias.data.forEach(function(data) {
            let tr = "";
            if(!table.find("tbody").length) 
                table.append("<tbody></tbody>");
            columnas.forEach(function(c) {
                td = data[c.COLUMN] === null ? "" : data[c.COLUMN];
                if(typeof td == 'object')
                    td = td.nombre;
                if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${td}') }}?t=${date.getTime()}`;
                    td = `<img class="w-100" src="${img}" onerror="this.src='${src}'"/>`;
                }
                tr += `<td class="${c.CLASS}">${td}</td>`;
            });
            tr += `<td class="text-center"><button onclick="edit(this,${data.id})" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button><button onclick="erase(this,${data.id})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button></td>`;
            table.find("tbody").append(`<tr data-id="${data.id}">${tr}</tr>`);
        });
    }
    /** */
    init();
</script>
@endpush