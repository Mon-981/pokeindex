<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>DEWS 09 - Tarea</title>
        <link rel="stylesheet" href="/DWES/DWES09_tarea/stylesheet.css">
        <link href="https://fonts.cdnfonts.com/css/pokemon-solid" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    </head>
    <body>
        <?php
        //Haremos la solicitud con CURL
        //Creamos la variable (CURL HANDLE) que contendra el recurso curl devuelto por curl init
        $ch = curl_init();
        
        //url del endpoint de la api (poke api)
        $url= 'https://pokeapi.co/api/v2/pokemon?limit=60';
        
        /**
         * 
         * Marca las opciones para la sesion actual.
         * Parametros: 
         * ch: variable con el recurso devuelto por curl init
         * Opcion CURLOPT_URL, indica que lo que le vamos a pasar es una url
         * url: url al endpoint de la poke api
         * 
         * Devuelve true o false
         */
        
        curl_setopt($ch, CURLOPT_URL, $url);
        
        /**
         * CURLOPT_RETURNTRANSFER permite almacenar el resultado en una variable en vez de imprimirlo en pantalla
         * 
         */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        /**
         * Ejecuta la conexion
         */
        $conex = curl_exec($ch);
        
        /**
         * Verificamos que no existan errores de conexion, 
         * en caso de haberlos imprimimos un mensaje
         */
        if(curl_errno($ch)){
            $mensaje_error = curl_error($ch);
            echo 'Error al conectarnos'.$mensaje_error;
        }
        /**
         * Cerramos la sesion de curl
         */
        curl_close($ch);
        
        /**
         * Varibale para almacenar los datos de los pokemon
         * lo obtenemos en formato JSON
         */
        $datos = json_decode($conex, true);
        
        //CREAMOS EL HTML
        if (!isset($_GET['pokemons'])){
            echo '<h1>Selector de Pokemons</h1>';
        }
        if (isset($_GET['pokemons'])){
            $chp = curl_init();
            $url_pokemon = 'https://pokeapi.co/api/v2/pokemon/' . $_GET['pokemons'];
            curl_setopt($chp, CURLOPT_URL, $url_pokemon);
            curl_setopt($chp, CURLOPT_RETURNTRANSFER, true);
            $conexP = curl_exec($chp);
            if(curl_errno($chp)){
                $mensaje_error = curl_error($chp);
                echo 'Error al conectarnos'.$mensaje_error;
            }
            curl_close($chp);
            $datosP = json_decode($conexP, true);
            echo '<h1>'. strtoupper($datosP['name']).'</h1>';
            $img = $datosP['sprites']['other']['official-artwork']['front_default'];
            echo '<img src="'.$img.'" width=220px><br>';  
            echo '<p><strong>Exp: </strong>'. strtoupper($datosP['base_experience']).'</p>';
            echo '<p><strong>Altura: </strong>'. strtoupper($datosP['height']).'</p>';
            echo '<p><strong>Peso: </strong>'. strtoupper($datosP['weight']).'</p>';
            echo '<p><strong>Habilidades: </strong></p>';
            echo '<ul>';
            foreach ($datosP['abilities'] as $habilidades){
                $habil = $habilidades['ability']['name'];
                $hab = explode('-', $habil);
                $habilidad = '';
                foreach ($hab as $h) {
                    $habilidad .= $h . " ";
                }
                echo '<li>'.$habilidad.'</li>';                
            }
            echo '</ul>';
        }
       
        ?>
        <form method='get'>
            <select id='pokemons' name='pokemons' onchange="this.form.submit()">
                <option>Elige tu Pokemon</option>
                <?php
                    foreach($datos['results'] as $resultados){
                        $nombres = $resultados['name']; 
                        echo '<option value="'. $nombres.'">'.$nombres.'</option>';                    
                    }                
                    ?>
            </select>
        </form>
    </body>
</html>
