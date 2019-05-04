<!DOCTYPE html>
<html>
<body>
	<h2>PARTSCAM</h2>
	<h3>Contacto</h3> 
	<p>Enviado desde la web</p>
	<br>
	<br>
	<h3>Datos del contacto</h3>
	<ul>
        @if(is_null($empresa))
        <li><strong>Nombre y Apellido / Empresa:</strong> {{$nombre}}</li>
        @else
        <li><strong>Nombre y Apellido / Empresa:</strong> {{$empresa}}</li>
        @endif
        <li><strong>Télefono:</strong> {{$telefono}}</li>
		<li><strong>Email:</strong> <a href="mailto:{{$email}}">{{$email}}</a></li>
	</ul>
    <br>
    <h4>@if(is_null($empresa)) Mensaje @else Consulta @endif:</h4>
    <p>{{$mensaje}}</p>
    <br>
    @if(!empty($marca))
        <p><strong>MARCA:</strong> {{$marca}}</p>
    @endif
    @if(!empty($modelo))
        <p><strong>MODELO:</strong> {{$modelo}}</p>
    @endif
    @if(!empty($anio))
        <p><strong>AÑO:</strong> {{$anio}}</p>
    @endif
</body>
</html>