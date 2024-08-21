<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php include_once __DIR__ . "/../template/alertas.php"; ?>

<form action="/crear-cuenta" class="formulario-crear" method="POST">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input autocomplete="nombre" type="text" name="nombre" id="nombre" value="<?php echo s($usuario->nombre); ?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido" value="<?php echo s($usuario->apellido); ?>" >
    </div>

    <div class="campo">
        <label for="celular">Celular</label>
        <input type="tel" name="celular" id="celular" placeholder="Numero Celular" maxlength="10" value="<?php echo s($usuario->celular); ?>">
    </div>

    <div class="campo">
        <label for="email">E-Mail</label>
        <input type="email" name="email" id="email" placeholder="Correo Electronico" value="<?php echo s($usuario->email); ?>">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="campo">
        <label for="passwordConfir">Confirmar Password</label>
        <input type="password" name="passwordConfir" id="passwordConfir">
    </div>

    <input type="submit" class="boton" value="Crear Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta?  Inicia Sesion</a>
    <a href="/olvide">¿Olvidaste tu Password?</a>
</div>