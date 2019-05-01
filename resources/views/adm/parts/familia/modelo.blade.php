<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container-fluid">
        <div class="d-flex">
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
                <table class="table mb-0" id="tabla"></table>
                
            </div>
        </div>
    </div>
</section>
@push('scripts_distribuidor')
<script>
    
    const src = "{{ asset('images/general/no-img.png') }}";
    window.familias = @json($familias);
    window.pyrus = new Pyrus("modelos", {familia_id: {TIPO:"OP",DATA: window.familias}}, src);
    window.categorias = @json($categorias);
    
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
        if(btn.is(":disabled")) {
            btn.removeAttr("disabled");
            $("#formBuscador input").removeAttr("disabled");
        } else {
            btn.attr("disabled",true);
            $("#formBuscador input").attr("disabled",true);
        }
        $("#wrapper-form").toggle(800,"swing");

        $("#wrapper-tabla").toggle("fast");

        if(id != 0)
            action = `{{ url('/adm/familias/modelos/update/${id}') }}`;
        else
            action = `{{ url('/adm/familias/modelos/store') }}`;
        if(data !== null) {
            for(let x in window.pyrus.especificacion) {
                if(!$(`[name="${x}"]`).length) continue;
                if(x == "padre_id") {
                    window.padreID = data[x];
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
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
    };
    /** ------------------------------------- */
    erase = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/modelos/delete/${id}') }}`;
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

        for(let x in window.pyrus.especificacion) {
            if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                CKEDITOR.instances[x].setData('');
                continue;
            }
            if(window.pyrus.especificacion[x].TIPO == "TP_FILE")
                $(`#src-${x}`).attr("src","");
            $(`[name="${x}"]`).val("");
        }
        
        $("#padre_id").find("option.new").remove();
        $("#padre_id").attr("disabled",true);
    };
    /** ------------------------------------- */
    edit = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/categorias/edit/${id}') }}`;
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
        
        window.categorias.forEach(function(data) {
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