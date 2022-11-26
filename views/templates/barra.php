<div class="barra">
    <p>Hola: <?php echo $nombre ?? ''; ?></p> <!-- esto es para mostrar el nombre del usuario cuando se loguee, la variable nombre la tenemos del $_SESSION en CitaController que a su vez viene de la instancia de usuario de LoginController -->

    <a class="boton" href="/logout">Cerrar Sesión</a>
</div>

<?php if(isset($_SESSION['admin'])) { ?><!-- verificamos que se muestre solo cuadno el usuario logueado es un admin(por ende al loguearse entra al panel de administracion) -->
    <div class="barra-servicios">
        <a href="/admin" class="boton">Ver Citas</a>
        <a href="/servicios" class="boton">Ver Servicios</a>
        <a href="/servicios/crear" class="boton">Nuevo Servicio</a>

    </div>
<?php } ?>