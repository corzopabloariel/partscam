@extends('adm.adm')

@section('body')
    @include($view)
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $("body").on("click",".alert button.close", function() {
            $(this).closest(".alert").remove();
        });

        if($("#sidebar").find(`a[href="${window.url}"]`).data("link") == "u") {
            $("#sidebar").find(`a[href="${window.url}"]`).addClass("active");
            $("#sidebar").find(`a[href="${window.url}"]`).closest("ul").addClass("show");
            $("#sidebar").find(`a[href="${window.url}"]`).closest("ul").prev().attr("aria-expanded",true).parent().addClass("active");
        } else
            $("#sidebar").find(`a[href="${window.url}"]`).parent().addClass("active");
    });
</script>
@stack('scripts_distribuidor')
@endpush