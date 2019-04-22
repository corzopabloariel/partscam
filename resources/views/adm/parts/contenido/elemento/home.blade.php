<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Contenido {{strtoupper($seccion)}}</h4>
    <p>Desde esta sección puede asignar qué elementos mostrar en la vista. Cada vista tiene características y elementos únicos. Seleccione estos en el selector SECCIONES.</p>
    <hr>
    <p class="mb-0"><strong>Elementos:</strong> slider, marcas, familias, buscador, ofertas y entrega</p>
</div>
<div id="wrapper-form" class="mt-2">
    <div class="card">
        <div class="card-body">
            <form id="form" novalidate class="pt-2" action="{{ url('/adm/contenido/' . $seccion . '/update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="container-form"></div>
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
    window.contenido = @json($contenido);
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
    init = function(callbackOK) {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());

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
    });
</script>
@endpush