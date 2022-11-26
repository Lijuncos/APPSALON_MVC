<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>

    <div class="contenedor-app">
        <div class="imagen"></div>
        <div class="scrollbar"> <!-- esto lo agregue para que la barra este pegada a la derecha y no ligada al width del 95% de la clase app-->
            <div class="app">
                <?php echo $contenido; ?>
            </div>
        </div>
        
    </div>

    <?php 
        echo $script ?? ''; // lo ponemos asi para que en las paginas que no detecte la variable script solo sea un string vacio, para que no tengamos errores de que esa variable no existe
    ?>
            
</body>
</html>