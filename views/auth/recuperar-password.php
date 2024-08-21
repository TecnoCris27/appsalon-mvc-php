<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuacion</p>

<?php include_once __DIR__ . "/../template/alertas.php"; ?>

<?php if($error) return; ?>
<form method="POST" class="formulario">
    <div class="campo">
        
        <input type="password" name="password" id="password">
        <label for="password">Password</label>
    </div>

    <div class="campo">
        
        <input type="password" name="passwordConfir" id="passwordConfir">
        <label for="passwordConfir">Confirmar Password</label>
    </div>

    <input type="submit" class="boton" value="Guardar Nueva Contraseña">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta?  Inicia Sesion</a>
    <a href="/olvide">¿Aun no tienes cuenta? Obtener una</a>
</div>