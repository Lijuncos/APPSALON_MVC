<h1 class="nombre-pagina">Crear Nueva Cita</h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>

<?php 
    include_once __DIR__ . '/../templates/barra.php';
?>

<div id="app">
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button> <!-- con "data-" podemos crear nuestros propios atributos aca creamos los data-paso para matchear con los id de las secciones para mostrarlas cuando clickemos el boton de c/u-->
        <button type="button" data-paso="2">Información cita</button> 
        <button type="button" data-paso="3">Resumen</button> 
    </nav>
    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>

    <div id="paso-2" class="seccion">
        <h2>Tus Datos y Cita</h2>
        <p class="text-center">Coloca tus datos y fecha de tu cita</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                type="text"
                id="nombre"
                placeholder= "Tu Nombre"
                value="<?php echo $nombre ?>"
                disabled
                />
            </div>

            <div class="campo">
                <label for="fecha">Fecha</label>
                <input 
                type="date"
                id="fecha"
                min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                />
            </div> <!--ese min es para que solo puedan reservar citas a partir del dia siguiente al que estemos // en la documentacion de php estan todas las letras con sus respectivos valores de lo que te traeria en fechas-->

            
            <div class="campo">
                <label for="hora">Hora</label>
                <input 
                type="time"
                id="hora"
                />
            </div>
            <input type="hidden" id="id" value="<?php echo $id; ?>">

        </form>
    </div>

    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">&laquo; Anterior</button> <!-- $laquo y &raquo son para las fleechitas hacia la izq y derecha en los botones -->
        <button id="siguiente" class="boton">Siguiente &raquo;</button>
    </div>
</div>

<?php 
    $script = "
    <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='build/js/app.js'></script>
    ";
?>