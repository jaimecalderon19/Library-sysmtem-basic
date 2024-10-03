<?php
    // Parámetros de la función para conectarnos a la base de datos MongoDB
    

    require 'vendor/autoload.php'; // Incluye el autoloader de Composer

    use MongoDB\Driver\ServerApi;

    $uri = 'mongodb+srv://juanito:X.$PXP5TawkGgVw@cluster1.lqohrfe.mongodb.net/?retryWrites=true&w=majority&appName=Cluster1';

// Set the version of the Stable API on the client
$apiVersion = new ServerApi(ServerApi::V1);

// Create a new client and connect to the server
$client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

try {
    // Send a ping to confirm a successful connection
    $client->selectDatabase('PBiblioteca')->command(['ping' => 1]); //Este permite probar la conexión
    $database = $client->selectDatabase('PBiblioteca');
    $collection = $database->selectCollection('Libros'); //Se le indica a dónde debe ir 
    echo "Pinged your deployment. You successfully connected to MongoDB!\n";
} catch (Exception $e) {
    printf($e->getMessage());
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $option = $_POST["option"] ?? '';
        if ($option) {
            if ($option == 1) {
                $ISBN = $_POST["ISBN"]??'';
                $NombreL = $_POST["NombreL"] ?? '';
                $Autor = $_POST["Autor"] ?? '';
                $Editorial = $_POST["Editorial"] ?? '';
                $Edicion = $_POST["Edicion"]??'';
                $Dewey = $_POST["Dewey"]??'';

        if ($ISBN && $NombreL && $Autor && $Editorial && $Edicion && $Dewey){

            $insertOneResult = $collection->insertOne([
                'ISBN' => $ISBN,
                'NombreLibro' => $NombreL,
                'Autor' => $Autor,
                'Editorial' => $Editorial,
                'Edicion' => $Edicion,
                'Dewey' => $Dewey
            ]);
            
            printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());
            
            var_dump($insertOneResult->getInsertedId());
        }
                
                }

      if ($option == 2) {

                $ISBN = $_POST["ISBN"] ?? '';
                $document = $collection->find(['ISBN' => $ISBN]);
                //var_dump ($document);
                echo "<table border='1'>
                    <tr>
                    <th>ISBN - Código</th>
                    <th>Nombre Libro</th>
                    <th>Autor</th>
                    <th>Editorial</th>
                    <th>Edición</th>
                    <th>Código Dewey</th>
                    </tr>";

                foreach ($document as $fila) {
                    echo "<tr>";
                    echo "<td>" . $fila["ISBN"] . "</td>";
                    echo "<td>" . $fila["NombreLibro"] . "</td>";
                    echo "<td>" . $fila["Autor"] . "</td>";
                    echo "<td>" . $fila["Editorial"] . "</td>";
                    echo "<td>" . $fila["Edicion"] . "</td>";
                    echo "<td>" . $fila["Dewey"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
             }
            }

            if ($option == 3) {
                $ISBN = $_POST["ISBN"] ?? '';
                if ($ISBN) {
                    $deleteResult = $collection->deleteOne(['ISBN' => $ISBN]);
                    printf("Deleted %d document(s)\n", $deleteResult->getDeletedCount());
                }
            }
    
            if ($option == 4) {
                $ISBN = $_POST['ISBN'] ?? '';
                $NombreL = $_POST['NombreLibro'] ?? '';
    
                if ($ISBN && $NombreL) {
                    $updateResult = $collection->updateOne(
                        ['ISBN' => $ISBN, 'NombreLibro' => $NombreL],
                        ['$set' => ['NombreLibro' => $NombreL]]
                    );

                    printf("Matched %d document(s)\n", $updateResult->getMatchedCount());
                    printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
                }
            }
    }
    
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Montaña Mágica</title>
    <style>
        body {
            background-image: url('https://pbs.twimg.com/media/FTSJFw_WYAAY93v?format=jpg&name=large');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #d7bde2; /* Morado claro */
            font-family: Arial, sans-serif;
            color: #000000; /* Texto negro */
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 28px;
            background-color: rgba(255, 255, 255, 0.8); /* Fondo de formulario semi-transparente */
            border-radius: 10px;
            box-shadow: 0 0 20px 8px rgba(186, 149, 206, 0.795); /* Sombra morada clara */
        }
        h3, h4, h5 {
            text-align: center;
            color: #c229eda2; /* Morado oscuro */
            text-shadow: 2px 2px 4px rgba(30, 29, 30, 0.463); /* Sombra morada */
        }

        /* Aumentar el tamaño de la fuente para los encabezados */
        h3, h5 {
            font-size: 24px;
            color: #ffffffac;
            text-shadow: 2px 2px 4px rgba(195, 0, 255, 0.463); /* Sombra morada */
        }

        select, .input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 2px solid #6c3483; /* Borde morado oscuro */
            border-radius: 5px;
            box-sizing: border-box;
            color: #e500dd; /* Texto color */
            background-color: #ffffff; /* Blanco */
        }
        select {
            height: 40px;
        }
        .input {
            max-width: 3900px; /* Mismo ancho que el campo de Dewey */
            height: 40px;
            padding: 10px;
            background-color: #ffffff; /* Blanco */
        }
        .input:focus {
            color: #e500dd; /* Texto color */
            background-color: #f0e8f9; /* Morado claro al enfocar */
            outline-color: #6c3483; /* Borde morado oscuro */
            box-shadow: -3px -3px 15px #6c3483; /* Sombra morada oscura */
            transition: .1s;
            transition-property: box-shadow;
        }

        input[type="date"] {
            width: 100%; /* Mismo ancho que el campo de Dewey */
            padding: 10px;
            margin-bottom: 10px;
            border: 2px solid #6c3483; /* Borde morado oscuro */
            border-radius: 5px;
            box-sizing: border-box;
            color: #e500dd; /* Texto color */
            background-color: #ffffff; /* Blanco */
            height: 40px;
        }
        
        input[type="submit"] {
            background-color: #6c3483; /* Morado oscuro */
            color: #ffffff; /* Texto blanco */
            cursor: pointer;
            transition: transform 0.3s;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
        }
        input[type="submit"]:hover {
            transform: scale(1.05);
            background-color: #4a235a; /* Morado más oscuro al pasar el ratón */
        }
        table {
            width: 30%;
            margin: 0 auto;
            color: white;

            border-collapse: collapse;
            margin-top: 20px;
            font-family: "Courier New", monospace;
            box-shadow: 0 0 20px 8px rgba(230, 59, 144, 0.4);
        }
        table th, table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h3>Find Your Book</h3>
    <h5>Bienvenido ayudante de La Montaña Mágica</h5>
    <form action="test.php" method="POST">
        <h4>Selecciona la acción que deseas realizar</h4>
        <select name="option" id="option">
            <option value="1">Insertar Libro</option>
            <option value="2">Consultar Libro</option>
            <option value="3">Eliminar Libro</option>
            <option value="4">Actualizar Libro</option>
        </select>
        <h4>Ingresa la información del libro</h4>
        <div>
            <input class="input" type="text" name="ISBN" placeholder="ISBN">
        </div>
        <div>
            <input class="input" type="text" name="NombreL" placeholder="Nombre Libro">
        </div>
        <div>
            <input class="input" type="text" name="Autor" placeholder="Autor">
        </div>
        <div>
            <input class="input" type="text" name="Editorial" placeholder="Editorial">
        </div>
        <div>
            <input type="date" name="Edicion">
        </div>
        <div>
            <input class="input" type="text" name="Dewey" placeholder="Código Dewey">
        </div>
        <input type="submit" value="Ingresar">
    </form>
</body>
</html>