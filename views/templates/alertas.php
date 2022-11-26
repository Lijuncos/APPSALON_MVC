<?php 

foreach ($alertas as $key => $mensajes): // $key es la llave de error 
    foreach($mensajes as $mensaje):
?>
    <div class="alerta <?php echo $key; ?>"> <!-- con un echo a key vamos agregandole la clase de error al elemento -->
        <?php echo $mensaje; ?>
    </div> 

<?php
    endforeach;


endforeach;
?>