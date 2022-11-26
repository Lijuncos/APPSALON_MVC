<h1 class="nombre-pagina">Panel de Administración</h1>

<?php 
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
            type="date"
            id="fecha"
            name="fecha"
            value="<?php echo $fecha; ?>"
            > <!-- le ponemos la fecha del dia actual al input(la variable fecha viene de AdminController y la iniciamos con la fecha del dia actual) -->
        </div>
    </form>
</div>

<?php 
    if(count($citas) === 0) { // count cuenta los elementos en el arreglo de $citas
        echo "<h2>No hay Citas en esta fecha</h2>"; // y en caso de que no haya elementos ejecutamos este h2
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
            $idCita = 0; // para que no marque que no esta definida
            foreach($citas as $key => $cita) {
                if($idCita !== $cita->id) { // como en la BD tenemos citasservicios con varios registros de la misma cita(id) pero con dif servicio se nos mostraban muchas citas 
                //(con esta comprobacion validamos si en cada iteracion el id cambio, en caso de que el id sea igual no ejecuta el siguiente codigo, hasta que el id sea dif y ahi si lo ejecuta)
                    
                $total = 0; // creamos la variable dentro del if para que este solo una vez, si no el foreach la reiniciaria cada vez en 0
        ?>
        <li>
            <p>ID: <span><?php echo $cita->id ?></span></p>
            <p>Hora: <span><?php echo $cita->hora ?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente ?></span></p>
            <p>Email: <span><?php echo $cita->email ?></span></p>
            <p>Teléfono: <span><?php echo $cita->telefono ?></span></p>

            <h3>Servicios</h3> <!-- ponemos el h3 dentro del if para que lo imprima solo una vez -->
        <?php 
            $idCita = $cita->id; // esto podria estar antes del li tambien, no importa que le asignemos el valor al final porque la primera vez siempre lo queremos imprimir(suplantando el valor que le pusimos por default $idCita = 0; para que no marque undefined)
            // y ya ahi $idCita se queda con el id del primer registro y empieza a comprar con los id de las siguientes citas hasta que sea dif
            
        } //Fin del if 
        
            $total += $cita->precio; // por fuera del if cada vez que se itere un servicio le vamos a ir sumando su precio al total
        
        ?>
            <p class="servicio"><?php echo $cita->servicio . " " . "$" . $cita->precio; ?></p> <!-- fuera del if ponemos los servicios asi los muestra a todos por mas que tengan el mismo id(de cita) -->
            <!-- ELIMINAMOS EL <li> DE CIERRE PARA QUE HTML LO CIERRE AUTOMATICAMENTE, NO SE PORQUE PERO SI NO EL PRIMER SERVICIO TENIA UNA SEPARACION CON EL SEGUNDO SERVICIO-->
            
            
            <!-- esto es para identificar cuando estamos en el ultimo servicio y poder sacar el total de los servicios para que aparezcan al final de cada cita a modo de resumen -->
            <?php
                $actual = $cita->id; // esto va identificando el id actual del objeto que vamos iterando
                $proximo = $citas[$key + 1]->id ?? 0; // en este caso usamos citas(que es el global con todas las citas) y utilizamos el $key que son las columnas en la base de datos y le sumamos +1 (de esta forma obtenemos el id del siguiente elemento en el que estamos)

                if(esUltimo($actual, $proximo)) { ?>
                    <p class="total">Total: <span>$ <?php echo $total; ?></span></p>
                    
                    <!-- ELIMINAR CITA -->
                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>"> <!-- creamos un boton oculto que selecciona el id de la cita que queremos eliminar y se manda al POST de /api/eliminar que es el action-->

                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
            
            <?php }

            } // fin del foreach ?>
    </ul>

</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>";
?>