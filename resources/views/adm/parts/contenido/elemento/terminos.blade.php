
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
    window.pyrus = new Pyrus("terminos", null, src);
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
        setTimeout(() => {
            callbackOK.call(this);
        }, 50);
    }
    /** */
    init(function() {
        $(`[name="titulo_es"]`).val(window.contenido.data.CONTENIDO.titulo.es);
        CKEDITOR.instances[`texto_es`].setData(window.contenido.data.CONTENIDO.texto.es);
    });
</script>
@endpush