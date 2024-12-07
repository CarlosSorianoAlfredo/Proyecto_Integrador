<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Alumnos</title>
    <link rel="stylesheet" href="../../styles/style_bajas.css"> 
</head>
<body>
    
    <?php
        require_once('menu_principal.php');
    ?>
    <h3>Listado de Alumnos</h3>
    <?php
        include('../controllers/controller_alumno.php');
        $alumnoDAO = new AlumnoDAO();
        $datos = $alumnoDAO->mostrarAlumnos('');
        if(mysqli_num_rows($datos) > 0){
            echo "<table>";
            echo '<thead>
                <tr>
                    <th>Num. Control</th>
                    <th>Nombre</th>
                    <th>Primer Ap.</th>
                    <th>Segundo Ap.</th>
                    <th>Edad</th>
                    <th>Semestre</th>
                    <th>Carrera</th>
                    <th>Acciones</th>
                </tr>
                </thead>';
            
            while($fila = mysqli_fetch_assoc($datos)){
                printf(
                    "<tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td class='action-buttons'>
                            <a href='' class='detalle'>Detalle</a>
                            <a href='' class='editar'>Editar</a>
                            <a href='../controllers/procesar_bajas.php?nc=%s' class='eliminar'>Eliminar</a>
                        </td>
                    </tr>",
                    $fila['Num_control'], $fila['Nombre'], $fila['Primer_Ap'], $fila['Segundo_Ap'],
                    $fila['Edad'], $fila['Semestre'], $fila['Carrera'], $fila['Num_control']
                );
            }
            
            echo '</table>';
        } else {
            echo "<p>No hay datos disponibles</p>";
        }
    ?>

</body>
</html>
