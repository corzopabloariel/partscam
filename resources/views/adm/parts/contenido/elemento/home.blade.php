<div id="wrapper-form" class="mt-2">
    <div class="card">
        <div class="card-body">
        <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/contenido/' . $seccion . '/update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-success text-uppercase px-5 mx-auto d-flex align-items-center mb-5">editar contenido<i class="fas fa-pencil-alt ml-2"></i></button>

                <button onclick="addIcono(this);" type="button" class="btn btn-dark text-uppercase px-5 mx-auto d-flex align-items-center mb-2">ícono suelto<i class="fas fa-plus ml-2"></i></button>
                <div id="wrapper-iconos" class="row"></div>
                <hr>                
                <div class="container-form"></div>
                <hr>
                <button onclick="addRepuesto(this);" type="button" class="btn btn-dark text-uppercase px-5 mx-auto d-flex align-items-center mb-2">ícono repuesto<i class="fas fa-plus ml-2"></i></button>
                <div id="wrapper-repuestos" class="row"></div>
            </form>
        </div>
    </div>
</div>

@push('scripts_distribuidor')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script>
    $(document).on("ready",function() {
        $(".ckeditor").each(function () {
            CKEDITOR.replace( $(this).attr("name") );
        });
    });

    const src = "{{ asset('images/general/no-img.png') }}";
    window.pyrus = new Pyrus("home", null, src);
    window.pyrus_repuestos = new Pyrus("home_repuestos", null, src);
    window.pyrus_icono = new Pyrus("home_iconos", null, src);
    window.pyrus_repuesto = new Pyrus("home_repuesto", null, src);
    window.contenido = @json($contenido);
    
    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let promise = new Promise(function (resolve, reject) {
            let formElement = document.getElementById(idForm);
            let request = new XMLHttpRequest();
            let formData = new FormData(formElement);
            formData.append("ATRIBUTOS",JSON.stringify(window.pyrus.objetoSimple));
            formData.append("ATRIBUTOS_REPUESTOS",JSON.stringify(window.pyrus_repuestos.objetoSimple));
            formData.append("ATRIBUTOS_ICONO",JSON.stringify(window.pyrus_icono.objetoSimple));
            formData.append("ATRIBUTOS_REPUESTO",JSON.stringify(window.pyrus_repuesto.objetoSimple));
            if(window.pyrus.objeto.JSON !== undefined)
                formData.append("JSON",JSON.stringify(window.pyrus.objeto.JSON));

            for(let x in CKEDITOR.instances)
                formData.set(x,CKEDITOR.instances[`${x}`].getData());
            
            request.responseType = 'json';
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "POST", url );
            xmlHttp.onload = function() {
                resolve(xmlHttp.response);
            }
            xmlHttp.send( formData );
        });
        promiseFunction = () => {
            promise
                .then(function(data) {
                    if(parseInt(data) == 1) {
                        alertify.success("Contenido guardado");
                        //location.reload();
                    } else 
                        alertify.error("Ocurrió un error en el guardado. Reintente");
                }
        )};
        alertify.warning("Espere. Guardando contenido");
        promiseFunction();
    };
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

    removeData = function(t, tt) {
        $(t).closest(`.${tt}`).remove();
    }
    
    addIcono = function(t, value = null) {
        let target = $(`#wrapper-iconos`);
        let html = "";

        if(window.icono === undefined) window.icono = 0;
        window.icono ++;

        html += '<div class="col-12 col-md-6 mt-3 numero position-relative">';
            html += '<div class="bg-light p-2 border">';
                html += window.pyrus_icono.formulario(window.icono,"icono");
                html += `<input type="hidden" name="removeIcono[]" value="0"/>`;
                html += `<i onclick="removeData(this,'numero');" style="line-height:14px; cursor: pointer; right: 5px; top: -10px; padding: 5px;" onclick="$(this).closest('.tel').remove()" class="fas fa-times position-absolute text-white bg-danger rounded-circle"></i>`;
            html += '</div>';
        html += '</div>';
        target.append(html);
        for(let x in window.pyrus_icono.especificacion) {
            if(window.pyrus_icono.especificacion[x].EDITOR === undefined) continue;
            if(window.pyrus_icono.objeto.JSON !== undefined) {
                if(window.pyrus_icono.objeto.JSON[x] !== undefined) {
                    for(let i in window.pyrus_icono.objeto.JSON[x])
                        CKEDITOR.replace( document.querySelector( `#icono_${x}_${window.icono}_${i}` ) );
                }
            } else {
                if($(`#icono_${x}_${window.icono}`).length)
                    CKEDITOR.replace( document.querySelector( `#icono_${x}_${window.icono}` ) );
            }
        }
        if(value !== null) {
            for(let x in window.pyrus_icono.especificacion) {
                if(window.pyrus_icono.especificacion[x].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${value[x]}') }}?t=${date.getTime()}`;
                    $(`#src-icono_${x}_${window.icono}`).attr("src",img);
                    continue;
                }
                if($(`#icono_${x}_${window.icono}`).length)
                    $(`#icono_${x}_${window.icono}`).val(value[x]);
            }
        }
    };
    addRepuesto = function(t, value = null) {
        let target = $(`#wrapper-repuestos`);
        let html = "";

        if(window.repuesto === undefined) window.repuesto = 0;
        window.repuesto ++;

        html += '<div class="col-12 col-md-6 col-lg-4 mt-3 numero position-relative">';
            html += '<div class="bg-light p-2 border">';
                html += window.pyrus_repuesto.formulario(window.repuesto,"repuesto");
                html += `<input type="hidden" name="removeRepuesto[]" value="0"/>`;
                html += `<i onclick="removeData(this,'numero');" style="line-height:14px; cursor: pointer; right: 5px; top: -10px; padding: 5px;" onclick="$(this).closest('.tel').remove()" class="fas fa-times position-absolute text-white bg-danger rounded-circle"></i>`;
            html += '</div>';
        html += '</div>';
        target.append(html);
        if(value !== null) {
            for(let x in window.pyrus_repuesto.especificacion) {
                if(window.pyrus_repuesto.especificacion[x].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${value[x]}') }}?t=${date.getTime()}`;
                    $(`#src-repuesto_${x}_${window.repuesto}`).attr("src",img);
                    continue;
                }
                if($(`#repuesto_${x}_${window.repuesto}`).length)
                    $(`#repuesto_${x}_${window.repuesto}`).val(value[x]);
            }
        }
    };
    /** ------------------------------------- */
    init = function(callbackOK) {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());
        $("#form .container-form").append(`<div class="mt-2">${window.pyrus_repuestos.formulario()}</div>`);

        $('#page').val(window.contenido.data.PAGE).trigger("change");
        setTimeout(() => {
            callbackOK.call(this);
        }, 50);
    }
    /** */
    init(function() {
        for(let x in window.pyrus.especificacion) {
            if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                console.log(CKEDITOR.instances[`${x}_es`])
                CKEDITOR.instances[`${x}_es`].setData(window.contenido.data.CONTENIDO[x]["es"]);
                continue;
            }
            if(window.pyrus.especificacion[x].TIPO == "TP_FILE") {
                date = new Date();
                img = `{{ asset('${window.contenido.data.CONTENIDO[x]}') }}?t=${date.getTime()}`;
                $(`#src-${x}`).attr("src",img);
                continue;
            }
            //$(`[name="${x}"]`).val(window.contenido.data.CONTENIDO[x]["es"]);
        }
        for(let x in window.pyrus_repuestos.especificacion) {
            if(CKEDITOR.instances[`${x}`] !== undefined) {
                CKEDITOR.instances[`${x}`].setData(window.contenido.data.repuestos[x]);
                continue;
            }
            if(window.pyrus_repuestos.especificacion[x].TIPO == "TP_FILE") {
                date = new Date();
                img = `{{ asset('${window.contenido.data.repuestos[x]}') }}?t=${date.getTime()}`;
                $(`#src-${x}`).attr("src",img);
                continue;
            }
            $(`[name="${x}"]`).val(window.contenido.data.repuestos[x]);   
        }
        window.contenido.data.iconos.forEach( function(i) {
            addIcono(null,i);
        });
        window.contenido.data.repuestos.repuesto.forEach( function(r) {
            addRepuesto(null,r);
        });
    });
</script>
@endpush