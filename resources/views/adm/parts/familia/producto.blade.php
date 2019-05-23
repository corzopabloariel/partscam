<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            @if(!isset($sin))
            <button id="btnADD" onclick="add(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>
            @endif
            <form class="position-relative" action="" method="post">
                <input style="width: 350px;" value="{{ old('buscar') }}" type="text" name="buscar" class="form-control" placeholder="Buscador: Código"/>
                @csrf
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
                <div class="table-responsive">
                    <table class="table mb-0" id="tabla"></table>
                </div>
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</section>
@push('scripts_distribuidor')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script>
    const src = "{{ asset('images/general/no-img.png') }}";
    $(document).on("ready",function() {
        $(".ckeditor").each(function () {
            CKEDITOR.replace( $(this).attr("name") );
        });
    });
    window.familias = @json($familias);
    window.prod = @json($prod);console.log(window.prod)
    window.productoimages = new Pyrus("productoimages", null, src);
    window.pyrus = new Pyrus("productos", {familia_id: {TIPO:"OP",DATA: window.familias},relaciones: {TIPO:"OP",DATA: window.prod}}, src);
    window.productos = @json($productos);
    formatter = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    });
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
            console.log(data)
            $(`#relaciones option[value="${id}"]`).attr("disabled",true);
            for(let x in window.pyrus.especificacion) {
                if(!$(`#${x}`).length) continue;
                if(x == "relaciones") continue;
                if(x == "familia_id") {
                    $(`[name="${x}"]`).val(data[x]).trigger("change");
                    continue;
                }
                if(x == "categoria_id") {
                    window.categoriaID = data[x];
                    continue;
                }
                if(x == "modelo_id") {
                    window.modeloID = data[x].id;
                    continue;
                }
                if(x == "precio") {
                    if(data[x] !== null) {
                        p = data[x].precio.toFixed(2).toString();
                        console.log(p)
                        p = p.replace(".",",");
                        $(`[name="${x}"]`).val(p);
                        $(`[name="${x}"]`).focus();
                    }
                    continue;
                }
                if(x == "stock") {
                    if(data[x] !== null) {
                        if(data[x].cantidad !== null)
                            $(`[name="${x}"]`).val(data[x].cantidad);
                        
                    }
                    continue;
                }
                if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                    CKEDITOR.instances[`${x}`].setData(data[x]);
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
            
            Arr = [];
            data.productos.forEach(function(p) {
                Arr.push(p.id);
            });
            $("#relaciones").val(Arr).trigger("change");
        } else 
            $(`#relaciones option:disabled`).removeAttr("disabled");
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
    changeFamilia = function(t,tipo) {
        id = $(t).val();
        flag = false;
        console.log(id)
        if(id === null || id == "")
            flag = true;
        if(typeof id == "object" && !flag) {
            if( id.length == 0 )
                flag = true;
            else
                id = id[0];
        }
        if(flag) {
            $("#categoria_id").select2("destroy")
            $("#categoria_id").html("");
            $("#categoria_id").attr("disabled",true);
            
            $("#form .container-form #categoria_id.select__2").select2({
                tags: "true",
                allowClear: true,
                placeholder: "Seleccione: CATEGORÍA",
                width: "resolve"
            });
        } else {
            $(t).attr("disabled",true);
            let familiaID = 0, modeloID = 0; 
            if(tipo == 0) {
                familiaID = id;
            } else {
                familiaID = $("#familia_id").val();
                modeloID = id;
            }
            let promise = new Promise(function (resolve, reject) {
                let url = `{{ url('/adm/familias/categorias/${window.pyrus.entidad}/familia_categoria/${familiaID}/${modeloID}') }}`;
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
                        if(tipo == 0) {
                            $("#modelo_id").find("option.new").remove();
                            $("#modelo_id").attr("disabled",true);

                            if(data === null || data === undefined) return false;
                            if(Object.keys(data).length > 0) { 
                                $("#modelo_id").removeAttr("disabled");
                                $("#modelo_id").select2({
                                    tags: "true",
                                    placeholder: "Seleccione: MODELO",
                                    data: data,
                                    width: "resolve"
                                });
                                console.log(window.modeloID)
                                if(window.modeloID !== undefined) {
                                    if(typeof window.modeloID == "object") {
                                        if(Object.keys(window.modeloID).length > 0)
                                            $("#modelo_id").val(window.modeloID).trigger("change");
                                    } else {
                                        if(window.modeloID != 0)
                                            $("#modelo_id").val(window.modeloID).trigger("change");
                                    }
                                }
                            }
                        } else {
                            $("#categoria_id").select2("destroy")
                            $("#categoria_id").html("");
                            $("#categoria_id").attr("disabled",true);
                            
                            $("#form .container-form #categoria_id.select__2").select2({
                                tags: "true",
                                allowClear: true,
                                placeholder: "Seleccione: CATEGORÍA",
                                width: "resolve"
                            });

                            if(data === null || data === undefined) return false;
                            if(Object.keys(data).length > 0) { 
                                $("#categoria_id").removeAttr("disabled");
                                $("#categoria_id").select2({
                                    tags: "true",
                                    placeholder: "Seleccione: CATEGORÍA",
                                    data: data,
                                    width: "resolve"
                                });
                                
                                if(window.categoriaID !== undefined) {
                                    if(parseInt(window.categoriaID.id) != 0)
                                        $("#categoria_id").val(window.categoriaID.id).trigger("change");
                                }
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
                    if(data.precio !== null)
                        $("#precio").attr("readonly",true);
                    if(data.stock !== null)
                        $("#stock").attr("readonly",true);
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
        $("#precio").maskMoney({thousands:'.', decimal:',', allowZero:true, prefix: '$ '});
        if($("#form .container-form .select__2").length) {
            
            $("#form .container-form #relaciones.select__2").select2({
                theme: "bootstrap",
                tags: "true",
                width: "resolve"
            });
            $("#form .container-form #familia_id.select__2").select2({
                tags: "true",
                allowClear: true,
                placeholder: "Seleccione: FAMILIA",
                width: "resolve"
            });
            $("#form .container-form #categoria_id.select__2").select2({
                tags: "true",
                allowClear: true,
                placeholder: "Seleccione: CATEGORÍA",
                width: "resolve"
            });
            $("#modelo_id").select2({
                tags: "true",
                placeholder: "Seleccione: MODELO",
                width: "resolve"
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
                            td = formatter.format(td.precio);
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
                tr += `<div class="">`
                    tr += `<button onclick="edit(this,${data.id})" class="btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button>`;
                    tr += `<button onclick="erase(this,${data.id})" class="btn rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button>`;
                    tr += `<button onclick="precio(this,${data.id})" class="btn rounded-0 btn-info" title="Cambiar PRECIO"><i class="fas fa-hand-holding-usd"></i></button>`;
                    tr += `<button onclick="stock(this,${data.id})" class="btn rounded-0 btn-success" title="Agregar/Cambiar STOCK"><i class="fas fa-box-open"></i></button>`;
                tr += `</div>`;
            tr += `</td>`;
            table.find("tbody").append(`<tr data-id="${data.id}">${tr}</tr>`);
        });
    }
    /** */
    init();
</script>
@endpush