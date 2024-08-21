<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesion con tus datos</p>

<?php include_once __DIR__ . "/../template/alertas.php"; ?>

<form action="/" class="formulario" method="POST">
    <div class="campo">
        <input type="email" name="email" id="email">
        <label for="email">Email</label>
    </div>

    <div class="campo">
        <input type="password" name="password" id="password">
        <label for="password">Password</label>
    </div>

    <input type="submit" class="boton" value="Iniciar Sesion">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aun no tienes una cuenta?  Crear una</a>
    <a href="/olvide">¿Olvidaste tu Password?</a>
</div>
