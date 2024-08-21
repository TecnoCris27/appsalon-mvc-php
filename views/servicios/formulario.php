<div class="campo">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" placeholder="Nombre Servicio" name="nombre" value="<?php echo $servicio->nombre; ?>">
</div>

<div class="campo">
    <label for="precio">Precio</label>
    <input type="money_format" id="precio" name="precio" value="<?php echo $servicio->precio; ?>">
</div>