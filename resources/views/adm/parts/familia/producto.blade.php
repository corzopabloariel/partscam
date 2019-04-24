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
                        <div class="mt-3">
                            <button type="button" class="btn btn-dark text-uppercase d-block mx-auto" onclick="imageAdd(this)">Imagen<i class="fas fa-plus ml-2"></i></button>
                            <div class="row container-form-image"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-2" id="wrapper-tabla">
            <div class="card-body">
                <table class="table mb-0" id="tabla"></table>
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</section>
@push('scripts_distribuidor')
<script>
    const src = "{{ asset('images/general/no-img.png') }}";
    window.familias = @json($familias);
    window.prod = @json($prod);console.log(window.prod)
    window.productoimages = new Pyrus("productoimages", null, src);
    window.pyrus = new Pyrus("productos", {familia_id: {TIPO:"OP",DATA: window.familias},relaciones: {TIPO:"OP",DATA: window.prod}}, src);
    window.productos = @json($productos);
    /** ------------------------------------- */
    imageAdd = function(data = null) {
        if(window.countImage === undefined) window.countImage = 0;
        window.countImage ++;
        let imgAUX = "{{ asset('images/general/no-img.png') }}";
        let html = "";
        html += `<div class="col-12 col-md-4 my-2 position-relative">`;
            html += `<i onclick="$(this).parent().remove()" class="fas fa-times-circle text-danger position-absolute" style="top: 5px; right: 20px; cursor: pointer; z-index:2;"></i>`;
            html += `<input type="hidden" name="imageURL[]" value="0" />`;
            html += `${window.productoimages.formulario(window.countImage,"image")}`;
        html += `</div>`;
        $("#form .container-form-image").append(html);

        if(data !== null) {
            target = $("#form .container-form-image").find("> div:last-child");
            imageSRC = "{{ asset('/') }}" + data.image;
            
            $(`#orden_${window.countImage}`).val(data.orden);
            $(`#src-image_${window.countImage}`).attr("src",imageSRC);
            target.find(`input[type="hidden"]`).val(data.image);
        }
    }
    permite = function(e,letras) {
        let key = e.which,
            keye = e.keyCode,
            tecla = String.fromCharCode(key).toLowerCase();
        if (keye != 13) {
            if (letras.indexOf(tecla) == -1 && keye != 9 && (key == 37 || keye != 37) && (keye != 39 || key == 39) && keye != 8 && (keye != 46 || key == 46) || key == 161)
                e.preventDefault();
        }
    }
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
            for(let x in window.pyrus.especificacion) {
                if(!$(`[name="${x}"]`).length) continue;
                if(x == "familia_id") {
                    $(`[name="${x}"]`).val(data[x]).trigger("change");
                    continue;
                }
                if(x == "categoria_id") {
                    window.categoriaID = data[x];
                    continue;
                }
                if(x == "precio") {
                    $(`[name="${x}"]`).val(data[x].precio);
                    continue;
                }
                if(x == "stock") {
                    $(`[name="${x}"]`).val(data[x].cantidad);
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
            data.imagenes.forEach(function(i) {
                imageAdd(i);
            });
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
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
                    $("#tabla").find(`tr[data-id="${id}"]`).remove();
                })
        };
        promiseFunction();
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
        $("#stock,#precio").removeAttr("readonly");
        $(".container-form-image").html("");
        $("#padre_id").find("option.new").remove();
        $("#padre_id").attr("disabled",true);
    };
    /** ------------------------------------- */
    changeFamilia = function(t) {
        id = $(t).val();
        if(id === null || id == "") {
            $("#categoria_id").find("option.new").remove();
            $("#categoria_id").attr("disabled",true);
        } else {
            $(t).attr("disabled",true);
            let promise = new Promise(function (resolve, reject) {
                let url = `{{ url('/adm/familias/categorias/${window.pyrus.entidad}/familia_categoria/${id}') }}`;
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
                        $("#categoria_id").find("option.new").remove();
                        $("#categoria_id").attr("disabled",true);
                        if(data === null || data === undefined) return false;
                        if(Object.keys(data).length > 0) { 
                            $("#categoria_id").removeAttr("disabled");
                            for(let x in data)
                                $("#categoria_id").append(`<option class="new" value="${x}">${data[x]}</option>`);
                            if(window.categoriaID !== undefined) {
                                if(window.categoriaID != 0)
                                    $("#categoria_id").val(window.categoriaID).trigger("change");
                            }
                        }
                    })
            };
            promiseFunction();
        }
    };
    /** ------------------------------------- */
    edit = function(t, id, modal = null) {
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
                    $("#stock,#precio").attr("readonly",true);
                    $(t).removeAttr("disabled");
                    if(modal === null)
                        add($("#btnADD"),parseInt(id),data);
                    else {
                        action = `{{ url('/adm/familias/categorias/${window.pyrus.entidad}/updateModal/${id}') }}`;
                        html = "";
                        $("#form_modal").attr("action",action);
                        html += `<input type="hidden" value="${modal}" name="tipo_modal" />`;
                        if(modal == "stock") {
                            $("#modal").find(".modal-title").text("Agregar/Cambiar STOCK");

                            html += `<p class="text-center">${data.nombre}</p>`;
                            html += '<label>STOCK</label>';
                            html += `<input onkeypress="permite(event,'0123456789')" type="number" name="stock_modal" placeholder="STOCK" class="form-control text-right" value="${data.stock.cantidad}"/>`;
                            $("#modal").find(".modal-body").html(html);
                            $("#modal").modal("show");
                        } else {
                            $("#modal").find(".modal-title").text("Cambiar PRECIO");
                            html += `<p class="text-center">${data.nombre}</p>`;
                            html += '<label>PRECIO</label>';
                            html += `<input onkeypress="permite(event,'.,0123456789/')" type="text" name="precio_modal" placeholder="PRECIO" class="form-control text-right" value="${data.precio.precio}"/>`;
                            $("#modal").find(".modal-body").html(html);
                            $("#modal").modal("show");
                        }
                    }
                })
        };
        promiseFunction();
    };
    /** */
    stock = function(t, id) {
        edit(t,id,"stock");
    }
    precio = function(t, id) {
        edit(t,id,"precio");
    }
    submitModal = function(t) {
        let formElement = document.getElementById("form_modal");

        /*var xmlHttp = new XMLHttpRequest();
        xmlHttp.open( "POST", t.action );
        xmlHttp.onload = function() {
            alert(xmlHttp.response);
        }
        xmlHttp.send(new FormData(formElement));
        return false;*/
    }
    /** ------------------------------------- */
    init = function() {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());
        if($("#form .container-form .select__2").length) {
            
            $("#form .container-form #relaciones.select__2").select2({
                theme: "bootstrap",
                tags: "true",
            });
            $("#form .container-form #familia_id.select__2").select2({
                theme: "bootstrap",
                tags: "true",
                allowClear: true,
                placeholder: "Seleccione: FAMILIA",
            });
            $("#form .container-form #categoria_id.select__2").select2({
                theme: "bootstrap",
                tags: "true",
                allowClear: true,
                placeholder: "Seleccione: CATEGORÍA",
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
        
        window.productos.data.forEach(function(data) {
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
                tr += `<hr/>`;
                tr += `<button onclick="precio(this,${data.id})" class="btn btn-info" title="Cambiar PRECIO"><i class="fas fa-hand-holding-usd"></i></button>`;
                tr += `<button onclick="stock(this,${data.id})" class="btn btn-success" title="Agregar/Cambiar STOCK"><i class="fas fa-box-open"></i></button>`;
            tr += `</td>`;
            table.find("tbody").append(`<tr data-id="${data.id}">${tr}</tr>`);
        });
    }
    /** */
    init();
</script>
@endpush