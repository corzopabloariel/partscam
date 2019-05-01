<!DOCTYPE html>
<html>
<body>
	<h2>PARTSCAM</h2>
	<h3>Consulta de producto</h3> 
	<p>Enviado desde la web</p>
	<br>
	<br>
	<h3>Datos del contacto</h3>
	<ul>
		<li><strong>Nombre:</strong> {{$nombre}}</li>
		<li><strong>Email:</strong> <a href="mailto:{{$email}}">{{$email}}</a></li>
	</ul>
    <br>
    <h4>Mensaje:</h4>
    <p>{{$mensaje}}</p>
    <br>
	<h3>Producto</h3>
    <ul>
        <li><strong>Cantidad:</strong> {{$cantidad}}</li>
        <li><strong>Nombre:</strong> {{$producto["familia"]}} {{$producto["nombre"]}}</li>
    </ul>
</body>
</html>