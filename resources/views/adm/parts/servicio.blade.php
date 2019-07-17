<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container-fluid">
        <div>
            <button id="btnADD" onclick="add(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>
        </div>
        <div style="display: none;" id="wrapper-form" class="mt-2">
            <div class="card">
                <div class="card-body">
                    <button onclick="remove(this)" type="button" class="close position-absolute" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="container-form"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-2" id="wrapper-tabla">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0" id="tabla"></table>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts_distribuidor')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script>
    $(document).on("ready",function() {
        $(".ckeditor").each(function () {
            CKEDITOR.replace( $(this).attr("name") );
        });
    });
    const src = "{{ asset('images/general/no-img.png') }}";
    window.pyrus = new Pyrus("servicios", null, src);
    window.elementos = @json($servicios);

    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let promise = new Promise(function (resolve, reject) {
            let formElement = document.getElementById(idForm);
            let request = new XMLHttpRequest();
            let formData = new FormData(formElement);
            formData.append("ATRIBUTOS",JSON.stringify(window.pyrus.objetoSimple));
            if(window.pyrus.objeto.JSON !== undefined)
                formData.append("JSON",JSON.stringify(window.pyrus.objeto.JSON));

            for(let x in window.pyrus.especificacion) {
                if(window.pyrus.especificacion[x].EDITOR === undefined) continue;
                if(window.pyrus.objeto.JSON !== undefined) {
                    if(window.pyrus.objeto.JSON[x] !== undefined) {
                        for(let i in window.pyrus.objeto.JSON[x])
                            formData.set(`${x}_${i}`, CKEDITOR.instances[`${x}_${i}`].getData());
                        continue;
                    } else {
                        if(CKEDITOR.instances[`${x}`] !== undefined)
                            formData.set(x,CKEDITOR.instances[`${x}`].getData());
                    }
                } else {
                    if(CKEDITOR.instances[`${x}`] !== undefined)
                        formData.set(x,CKEDITOR.instances[`${x}`].getData());
                }
            }
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
                        location.reload();
                    } else 
                        alertify.error("Ocurrió un error en el guardado. Reintente");
                }
        )};
        alertify.warning("Espere. Guardando contenido");
        promiseFunction();
    }
    
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
            action = `{{ url('/adm/${window.pyrus.entidad}/update/${id}') }}`;
        else
            action = `{{ url('/adm/${window.pyrus.entidad}/store') }}`;
        if(data !== null) {
            for(let x in window.pyrus.especificacion) {
                if(window.pyrus.especificacion[x].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${data[x]}') }}?t=${date.getTime()}`;
                    $(`#src-${x}`).attr("src",img);
                    continue;
                }
                if(window.pyrus.objeto.JSON !== undefined) {
                    if(window.pyrus.objeto.JSON[x] !== undefined) {
                        for(let i in window.pyrus.objeto.JSON[x])
                            CKEDITOR.instances[`${x}_${i}`].setData(data[x][i]);
                        continue;
                    }
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
        alertify.confirm("ATENCIÓN","¿Eliminar registro?",
            function(){
                let promise = new Promise(function (resolve, reject) {
                    let url = `{{ url('/adm/${window.pyrus.entidad}/delete/${id}') }}`;
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

        for(let x in window.pyrus.especificacion) {
            if(window.pyrus.objeto.JSON !== undefined) {
                if(window.pyrus.objeto.JSON[x] !== undefined) {
                    for(let i in window.pyrus.objeto.JSON[x])
                        CKEDITOR.instances[`${x}_${i}`].setData("");
                    continue;
                }
            }
            if(window.pyrus.especificacion[x].TIPO == "TP_FILE")
                $(`#src-${x}`).attr("src","");
            $(`[name="${x}"]`).val("");
        }
    };
    /** ------------------------------------- */
    edit = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/${window.pyrus.entidad}/edit/${id}') }}`;
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

        let columnas = window.pyrus.columnas();
        let table = $("#tabla");
        columnas.forEach(function(e) {
            if(!table.find("thead").length) 
                table.append('<thead class="thead-dark"></thead>');
            table.find("thead").append(`<th class="${e.CLASS}" style="width:${e.WIDTH}">${e.NAME}</th>`);
        });
        table.find("thead").append(`<th class="text-uppercase text-center" style="width:150px">acción</th>`);

        window.elementos.forEach(function(data) {
            let tr = "";
            if(!table.find("tbody").length) 
                table.append("<tbody></tbody>");
            columnas.forEach(function(c) {
                td = data[c.COLUMN] === null ? "" : data[c.COLUMN];
                if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${td}') }}?t=${date.getTime()}`;
                    td = `<img class="w-100" src="${img}" onerror="this.src='${src}'"/>`;
                }
                if(c.COLUMN == "link") {
                    if(td != "")
                        td = `<iframe class="w-100 h-100" src="https://www.youtube.com/embed/${td}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                }
                if(window.pyrus.objeto.JSON !== undefined) {
                    if(window.pyrus.objeto.JSON[c.COLUMN] !== undefined) {
                        tdAux = "";
                        for(let idioma in window.pyrus.objeto.JSON[c.COLUMN]) {
                            if(td[idioma] !== null) 
                                tdAux += `<fieldset><legend>${window.pyrus.objeto.JSON[c.COLUMN][idioma]}</legend>${td[idioma]}</fieldset>`;
                        }
                        td = tdAux;
                    }
                }
                tr += `<td class="${c.CLASS}">${td}</td>`;
            });
            tr += `<td class="text-center">` +
                    `<button class="btn btn-info rounded-0" disabled><i class="fas fa-eye"></i></button>` +
                    `<button onclick="edit(this,${data.id})" class="btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button>` +
                    `<button onclick="erase(this,${data.id})" class="btn rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button>` +
                    `</td>`;
            table.find("tbody").append(`<tr data-id="${data.id}">${tr}</tr>`);
        });
    }
    /** */
    init();
</script>
@endpush