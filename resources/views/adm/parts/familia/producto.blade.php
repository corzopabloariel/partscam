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
                    <form onsubmit="event.preventDefault(); formSubmit(this);" id="form" novalidate class="pt-2" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="container-form"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                
                                <div class="row d-flex justify-content-center">
                                    <div class="col-12">
                                        <label class="mb-0" for="modelo_id">MODELOS</label>
                                        <select name="modelo_id[]" multiple class="select__2 w-100" style="width:100%;" id="modelo_id"></select>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center mt-3">
                                    <div class="col-12">
                                        <select onchange="changeFamilia(this,0)" name="familia_id" class="select__2 w-100" style="width:100%;" id="familia_id"></select>
                                    </div>
                                </div>
                                <div class="mt-2" id="categoriasHTML"></div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-dark text-uppercase d-block mx-auto" onclick="imageAdd(this)">Imagen<i class="fas fa-plus ml-2"></i></button>
                                    <div class="row container-form-image"></div>
                                </div>
                            </div>
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
    window.modelos = @json($modelos);
    window.categorias = @json($categorias);
    window.select2 = @json($select2);
    window.productoimages = new Pyrus("productoimages", null, src);
    window.productocategoria = new Pyrus("productoscategoria");
    
    window.pyrus = new Pyrus("productos", null, src);
    window.productos = @json($productos);
    formatter = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    });
    
    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let promise = new Promise(function (resolve, reject) {
            let formElement = document.getElementById(idForm);
            let request = new XMLHttpRequest();
            let formData = new FormData(formElement);
            
            formData.set("precio",$("#precio").maskMoney('unmasked')[0])
            
            request.responseType = 'json';
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "POST", url );
            xmlHttp.onload = function() {
                alertify.success(`Producto guardado`);
                resolve(xmlHttp.response);
            }
            xmlHttp.send( formData );
        });
        promiseFunction = () => {
            promise
                .then(function(data) {
                    //location.reload();
                }
        )};
        promiseFunction();
    }
    /** ------------------------------------- */
    imageAdd = function(data = null) {
        if(window.countImage === undefined) window.countImage = 0;
        window.countImage ++;
        let imgAUX = "{{ asset('images/general/no-img.png') }}";
        let html = "";
        html += `<div class="col-12 my-2 position-relative">`;
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
    function add(t, id = 0, data = null) {
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
            console.log(data);
            $(`#relaciones option[value="${id}"]`).attr("disabled",true);
            for(let x in window.pyrus.especificacion) {
                if(!$(`#${x}`).length) continue;
                if(x == "relaciones") continue;
                if(x == "familia_id") {
                    $(`[name="${x}"]`).val(data[x]).trigger("change");
                    continue;
                }
                if(x == "categoria_id") {
                    //window.categoriaID = data[x];
                    continue;
                }
                if(x == "modelo_id") {
                    //window.modeloID = data[x].id;
                    continue;
                }
                if(x == "precio") {
                    if(data[x] !== null) {
                        p = data[x].precio.toFixed(2).toString();
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
            window.dataCategorias = data.categorias;
            $("#orden").focus();
            //window.categoriasARR = data.categoria_id;
            //$("#relaciones").val(data.productos).trigger("change");
            $("#modelo_id").val(data.modelos).trigger("change");
        } else 
            $(`#relaciones option:disabled`).removeAttr("disabled");
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
    };
    /** ------------------------------------- */
    erase = function(t, id) {
        
        alertify.confirm("ATENCIÓN","¿Eliminar registro?",
            function() {
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
            },
            function() {
                $(t).removeAttr("disabled");
            }
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
    /** ------------------------------------- */
    remove = function(t) {
        add($("#btnADD"));

        if(window.dataCategorias !== undefined)
            delete window.dataCategorias;

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
    changeCategoria = function(t) {
        id = $(t).val();
        addFlag = true;
        if($(t).data("valor") === undefined)
            $(t).data("valor",id)
        else 
            addFlag = false;
        if(id == "") {
            //$("#categoriasHTML").html("");
            for( i = $(t).closest(".cat").index() + 1 ; i < window.categoriaArr.length ; i++)
                $(`[data-cat="${window.categoriaArr[i]}"]`).remove();
            
            for( i = $(t).closest(".cat").index() + 1 ; i < window.categoriaArr.length ; i++)
                window.categoriaArr.splice(i,1);
            return false;
        }
        if(!addFlag) {
            for( i = $(t).closest(".cat").index() + 1 ; i < window.categoriaArr.length ; i++)
                $(`[data-cat="${window.categoriaArr[i]}"]`).remove();
            
            for( i = $(t).closest(".cat").index() + 1 ; i < window.categoriaArr.length ; i++)
                window.categoriaArr.splice(i,1);
        }
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/categorias/categoria/${id}') }}`;
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
                    console.log(data);
                    $(t).removeAttr("disabled");
                    if(Object.keys(data).length > 1) {
                        if(window.categoriaIndex === undefined)
                            window.categoriaIndex = 0;
                            window.categoriaIndex ++;
                        window.categoriaArr.push(window.categoriaIndex);
                        html = `<div class="mt-2 cat" data-cat="${window.categoriaIndex}">` +
                                    `${window.productocategoria.formulario(window.categoriaIndex, "cat")}` +
                                `</div>`;
                        $("#categoriasHTML").append(html);
                        $("#categoriasHTML").find(`#cat_categoria_id_${window.categoriaIndex}`).removeAttr("disabled");
                        $("#categoriasHTML").find(`#cat_categoria_id_${window.categoriaIndex}`).select2({
                            data: data,
                            allowClear: true,
                            placeholder: "Seleccione: CATEGORÍA",
                            width: "resolve"
                        });
                        if(window.dataCategorias !== undefined) {
                            a = window.dataCategorias.shift();
                            $("#categoriasHTML").find(`#cat_categoria_id_${window.categoriaIndex}`).val(a).trigger("change");
                        }
                    }
                })
        };
        promiseFunction();
    }
    /** ------------------------------------- */
    changeFamilia = function(t,tipo) {
        id = $(t).val();
        if(id === null) return false;
        if(id == "") {
            $("#categoriasHTML").html("");
            window.categoriaArr = [];
            return false;
        }
        
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/familias/categorias/familia/${id}') }}`;
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
                    if(Object.keys(data).length > 1) {
                        if(window.categoriaArr === undefined)
                            window.categoriaArr = [];
                        if(window.categoriaIndex === undefined)
                            window.categoriaIndex = 0;
                        window.categoriaIndex ++;
                        window.categoriaArr.push(window.categoriaIndex);
                        html = `<div class="mt-2 cat" data-cat="${window.categoriaIndex}">` +
                                    `${window.productocategoria.formulario(window.categoriaIndex, "cat")}` +
                                `</div>`;
                        $("#categoriasHTML").append(html);
                        $("#categoriasHTML").find(`#cat_categoria_id_${window.categoriaIndex}`).removeAttr("disabled");
                        $("#categoriasHTML").find(`#cat_categoria_id_${window.categoriaIndex}`).select2({
                            data: data,
                            allowClear: true,
                            placeholder: "Seleccione: CATEGORÍA",
                            width: "resolve"
                        });
                        if(window.dataCategorias !== undefined) {
                            a = window.dataCategorias.shift();
                            $("#categoriasHTML").find(`#cat_categoria_id_${window.categoriaIndex}`).val(a).trigger("change");
                        }
                    }
                })
        };
        promiseFunction();
        
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
            u = "{{ url('/adm/familias/categorias/productos/select') }}";
            console.log(u)
            $("#relaciones.select__2").select2({
                width: "resolve",
                language: "es",
                ajax: {
                    url: u,
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                           results: data
                        };
                    },
                    cache: true
                }
            });
            $("#familia_id.select__2").select2({
                allowClear: true,
                placeholder: "Seleccione: FAMILIA",
                width: "resolve",
                data: window.select2.familias
            });
            $("#categoria_id.select__2").select2({
                allowClear: true,
                placeholder: "Seleccione: CATEGORÍA",
                width: "resolve",
                data: window.select2.categorias
            });
            $("#modelo_id").select2({
                placeholder: "Seleccione: MODELO",
                width: "resolve",
                data: window.select2.modelos
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
                if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_ENUM") {
                    if(window.pyrus.especificacion[c.COLUMN].ENUM !== undefined)
                        td = window.pyrus.especificacion[c.COLUMN].ENUM[td];
                    else {
                        if(window[c.COLUMN] !== undefined) {
                            if(window[c.COLUMN][td] !== undefined)
                                td = window[c.COLUMN][td];
                        }
                    }
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