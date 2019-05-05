<div class="modal fade bd-example-modal-lg" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="hijosModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <button id="btnADD" onclick="add(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>
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
                <div class="table-responsive">
                    <table class="table mb-0 table-striped table-hover" id="tabla"></table>
                </div>
                {{ $categorias->links() }}
            </div>
        </div>
    </div>
</section>
@push('scripts_distribuidor')
<script>
    
    const src = "{{ asset('images/general/no-img.png') }}";
    window.familias = @json($familias);
    
    window.pyrus = new Pyrus("categorias", {familia_id: {TIPO:"OP",DATA: window.familias}}, src);
    window.categorias = @json($categorias);
    /** */
    /*$("#modelos").select2({
        theme: "bootstrap",
        tags: "true",
        placeholder: "FAMILIA",
        data: window.select2.familias,
        width: "resolve"
    });
    $("#categorias").select2({
        theme: "bootstrap",
        tags: "true",
        placeholder: "CATEGORÍA",
        data: window.select2.categorias,
        width: "resolve"
    });*/
    /** ------------------------------------- */
    readURL = function(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                console.log(input.id)
                $(`#${target}`).attr(`src`,`${e.target.result}`);
            };
            reader.readAsDataURL(input.files[0]);
        }
    };
    /** */
    habilitar = function(t) {
        if($(t).val() == "") 
            $("#form").find("button").attr("disabled",true);
        else
            $("#form").find("button").removeAttr("disabled");
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
            //window.categoriaID = data.id;
            if(data.tipo == 1)
                $("#padre_id,#modelo_id").closest(".row").addClass("d-none");
            if(data.tipo == 2) 
                $("#padre_id").closest(".row").addClass("d-none");
            
            for(let x in window.pyrus.especificacion) {
                if(!$(`[name="${x}"]`).length) continue;
                if(x == "padre_id") {//MODELO
                    if(data.tipo == 2)
                        window.padreID = data.modelo;
                    else if(data.tipo == 3) {
                        window.padreID = data.padre.padre.id
                        window.categoriaID = data.padre_id;
                    }
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
    addModal = function(t, id = 0, data = null) {
        let btn = $(t);
        if(btn.is(":disabled")){
            btn.removeAttr("disabled");
        } else {
            btn.attr("disabled",true);
        }
        $("#wrapper-formModal").toggle(800,"swing");

        $("#wrapper-tablaModal").toggle("fast");

        if(id != 0)
            action = `{{ url('/adm/familias/${window.pyrus.entidad}/subcategorias/update/${id}') }}`;
        else
            action = `{{ url('/adm/familias/${window.pyrus.entidad}/subcategorias/store') }}`;
        if(data !== null) {
            //window.categoriaID = data.id;
            if(data.tipo == 1)
                $("#padre_id,#modelo_id").closest(".row").addClass("d-none");
            if(data.tipo == 2) 
                $("#padre_id").closest(".row").addClass("d-none");
            
            for(let x in window.pyrus.especificacion) {
                if(!$(`[name="${x}"]`).length) continue;
                if(x == "padre_id") {//MODELO
                    if(data.tipo == 2)
                        window.padreID = data.modelo;
                    else if(data.tipo == 3) {
                        window.padreID = data.padre.padre.id
                        window.categoriaID = data.padre_id;
                    }
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
        elmnt = document.getElementById("formModal");
        elmnt.scrollIntoView();
        $("#formModal").attr("action",action);
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
    eraseModal = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/${window.pyrus.entidad}/subcategorias/delete/${id}') }}`;
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", url, true );
            
            xmlHttp.send( null );
            resolve(xmlHttp.responseText);
        });

        promiseFunction = () => {
            promise
                .then(function(msg) {
                    $("#wrapper-tablaModal table").find(`tr[data-id="${id}"]`).remove();
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
        $("#padre_id,#modelo_id").closest(".row").removeClass("d-none");
    };
    removeModal = function(t) {
        addModal($("#btnADDmodal"));
        let subcategorias = new Pyrus("subcategorias", null, src);

        if(window.padreID !== undefined)
            delete window.padreID;
        if(window.modeloID !== undefined)
            delete window.modeloID;
        if(window.categoriaID !== undefined)
            delete window.categoriaID;

        for(let x in subcategorias.especificacion) {
            if(subcategorias.especificacion[x].EDITOR !== undefined) {
                CKEDITOR.instances[x].setData('');
                continue;
            }
            if(subcategorias.especificacion[x].TIPO == "TP_FILE")
                $(`#src-${x}`).attr("src","");
            $(`[name="${x}"]`).val("");
        }
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
                                data: data.results,
                                width: 'resolve'
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
                                data: data.results,
                                width: 'resolve'
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
    editModal = function(t, id) {
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
                    addModal($("#btnADDmodal"),parseInt(id),data);
                })
        };
        promiseFunction();
    };
    /** ------------------------------------- */
    submitModal = function(t) {
        let formElement = document.getElementById("formModal");
        let elementForm = new FormData(formElement);
        $("#formModal *").attr("disabled", true);
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.responseType = 'json';
        xmlHttp.open( "POST", t.action );
        xmlHttp.onload = function() {
            console.log(xmlHttp.response);
            $("#formModal *").removeAttr("disabled");
            let table = $("#wrapper-tablaModal table");
            date = new Date();

            if(table.find(`tbody tr[data-id="${xmlHttp.response.id}"]`).length) {
                let tr = table.find(`tbody tr[data-id="${xmlHttp.response.id}"]`);
                img = `{{ asset('${xmlHttp.response.image}') }}?t=${date.getTime()}`;

                tr.find("td:first-child").text(xmlHttp.response.orden);
                tr.find("td:nth-child(2) img").attr("src",img);
                tr.find("td:nth-child(3)").text(xmlHttp.response.nombre);
            } else {
                let columnas = window.pyrus.columnas();
                tr = "";
                if(!table.find("tbody").length) 
                    table.append("<tbody></tbody>");
                columnas.forEach(function(c) {
                    td = xmlHttp.response[c.COLUMN] === null ? "" : xmlHttp.response[c.COLUMN];
                    if(typeof td == 'object')
                        td = td.nombre;
                    if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_FILE") {
                        date = new Date();
                        img = `{{ asset('${td}') }}?t=${date.getTime()}`;
                        td = `<img class="w-100" src="${img}" onerror="this.src='${src}'"/>`;
                    }
                    tr += `<td class="${c.CLASS}">${td}</td>`;
                });
                tr += `<td>`;
                    tr += `<div class="d-flex flex-wrap h-100 w-100 justify-content-around align-items-center">`
                        tr += `<button onclick="editModal(this,${xmlHttp.response.id})" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>`;
                        tr += `<button onclick="eraseModal(this,${xmlHttp.response.id})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>`;
                        //tr += `<hr>`;
                        tr += `<button onclick="hijos(this,${xmlHttp.response.id},${xmlHttp.response.tipo}, 1)" type="button" class="btn btn-primary"><i class="fas fa-table" title="Listar hijos"></i></button>`;
                    tr += `</div>`;
                tr += `</td>`;
                table.find("tbody").append(`<tr data-id="${xmlHttp.response.id}">${tr}</tr>`);
            }
            addModal($("#btnADDmodal"));
        }
        xmlHttp.send(elementForm);
        return false;
    }
    hijos = function(t, id, tipo, conPadre = 0) {
        let target = $("#hijosModal");
        let title = target.find(".modal-title");
        let body = target.find(".modal-body");

        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/${window.pyrus.entidad}/show/${id}/${tipo}') }}`;
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
                    let columnas = window.pyrus.columnas();
                    let subcategorias = new Pyrus("subcategorias", null, src);
                    title.text(conPadre ? `${data.padre.nombre} > ${data.nombre}` : data.nombre);
                    
                    html = "";

                    html += '<div class="d-flex justify-content-between mb-2">';
                        html += '<button id="btnADDmodal" onclick="addModal(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>';
                        if(conPadre)
                            html += `<button onclick="hijos(this, ${data.padre.id}, ${data.padre.tipo})" class="btn btn-dark text-uppercase" type="button">regresar<i class="fas fa-undo-alt ml-2"></i></button>`;
                    html += '</div>';
                    html += '<div class="table-responsive" id="wrapper-tablaModal"><table class="table mb-0 table-striped table-hover" id=""></table></div>';
                    html += '<div style="display: none;" id="wrapper-formModal" class="mt-2 position-relative">'
                        html += '<button style="right: 0; top: 0;" onclick="removeModal(this)" type="button" class="close position-absolute" aria-label="Close">';
                            html += '<span aria-hidden="true"><i class="fas fa-times"></i></span>';
                        html += '</button>';
                        html += '<form id="formModal" novalidate class="pt-2" onsubmit="event.preventDefault(); submitModal(this)" method="post" enctype="multipart/form-data">';
                            html += '<input type="hidden" name="_token" value="{{ csrf_token() }}" />';
                            html += `<input type="hidden" name="padre_id" value="${data.id}" />`;
                            html += `<input type="hidden" name="familia_id" value="${data.familia_id}" />`;
                            html += `<input type="hidden" name="tipo" value="${data.tipo}" />`;
                            html += '<div class="container-formModal"></div>';
                        html += '</form>';
                    html += '</div>';
                    body.html(html);
                    let table = body.find("table");
                    $("#formModal .container-formModal").html(subcategorias.formulario());
                    columnas.forEach(function(e) {
                        if(!table.find("thead").length) 
                            table.append('<thead class="thead-dark"></thead>');
                        table.find("thead").append(`<th class="${e.CLASS}" style="width:${e.WIDTH}">${e.NAME}</th>`);
                    });
                    table.find("thead").append(`<th class="text-uppercase text-center" style="width:150px">acción</th>`);
                    
                    data.hijos.forEach(function(data) {
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
                        tr += `<td>`;
                            tr += `<div class="d-flex flex-wrap h-100 w-100 justify-content-around align-items-center">`
                                tr += `<button onclick="editModal(this,${data.id})" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>`;
                                tr += `<button onclick="eraseModal(this,${data.id})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>`;
                                //tr += `<hr>`;
                                tr += `<button onclick="hijos(this,${data.id},${data.tipo}, 1)" type="button" class="btn btn-primary"><i class="fas fa-table" title="Listar hijos"></i></button>`;
                            tr += `</div>`;
                        tr += `</td>`;
                        table.find("tbody").append(`<tr data-id="${data.id}">${tr}</tr>`);
                    });
                    target.modal("show");
                })
        };
        promiseFunction();
    }
    /** ------------------------------------- */
    init = function() {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());
        
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
            tr += `<td>`;
                tr += `<div class="d-flex flex-wrap h-100 w-100 justify-content-around align-items-center">`
                    tr += `<button onclick="edit(this,${data.id})" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>`;
                    tr += `<button onclick="erase(this,${data.id})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>`;
                    //tr += `<hr>`;
                    tr += `<button onclick="hijos(this,${data.id},${data.tipo})" type="button" class="btn btn-primary"><i class="fas fa-table" title="Listar hijos"></i></button>`;
                tr += `</div>`;
            tr += `</td>`;
            table.find("tbody").append(`<tr ${data.tipo == 3 ? 'class="table-info"' : ""} data-id="${data.id}">${tr}</tr>`);
        });
    }
    /** */
    init();
</script>
@endpush