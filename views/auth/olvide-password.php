<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuacion</p>

<?php include_once __DIR__ . "/../template/alertas.php"; ?>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <input type="email" name="email" id="email">
        <label for="email">Correo Electronico</label>
    </div>

    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta?  Inicia Sesion</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta?  Crear una</a>
</div>