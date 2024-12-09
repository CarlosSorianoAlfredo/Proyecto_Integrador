<?php
include '../../backend/scripts/auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Alumnos</title>
    <link rel="stylesheet" href="../../frontend/styles/style_bajas_cambios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Usamos aqui Jquery-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .modal-wide {
            max-width: 600px;
        }
    </style>
</head>
<body>
<?php require_once('menu_principal.php'); ?>

<div class="container mt-4">
    <!-- Mostrar mensajes de éxito o error -->
    <?php
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        $alert_class = 'alert-info';

        if (strpos($mensaje, 'ACTUALIZADO') !== false) {
            $alert_class = 'alert-success';
        } elseif (strpos($mensaje, 'Error') !== false) {
            $alert_class = 'alert-danger';
        } else {
            $alert_class = 'alert-warning';
        }

        echo "<div class='alert {$alert_class} alert-dismissible fade show' role='alert'>
                <strong>¡Aviso!</strong> {$mensaje}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
        unset($_SESSION['mensaje']);
    }
    ?>

    <h3>Listado de Alumnos</h3>
    <?php
    include('../../backend/controllers/controller_alumno.php');
    include('../../backend/controllers/controller_tutor.php');
    $alumnoDAO = new AlumnoDAO();
    $datos = $alumnoDAO->mostrarAlumnos('');
    $tutores = $alumnoDAO->obtenerTutores();
    $carreras = $alumnoDAO->obtenerCarreras();

    if (count($datos) > 0) { // Usamos count() ya que fetchAll() devuelve un array
        echo "<table class='table table-striped'>";
        echo '<thead>
                <tr>
                    <th>Num. Control</th>
                    <th>Nombre</th>
                    <th>Primer Ap.</th>
                    <th>Segundo Ap.</th>
                    <th>Fecha de Nac.</th>
                    <th>Semestre</th>
                    <th>Carrera</th>
                    <th>Tutor</th>
                    <th>En Riesgo</th>
                    <th>Acciones</th>
                </tr>
              </thead>
              <tbody>';
        
        foreach ($datos as $fila) {
            echo "<tr>
                    <td>{$fila['Num_Control']}</td>
                    <td>{$fila['Nombre']}</td>
                    <td>{$fila['Primer_Apellido']}</td>
                    <td>{$fila['Segundo_Apellido']}</td>
                    <td>{$fila['Fecha_Nacimiento']}</td>
                    <td>{$fila['Semestre']}</td>
                    <td>" . (!empty($fila['Carrera']) ? $fila['Carrera'] : 'Sin carrera') . "</td>
                    <td>" . (!empty($fila['Tutor']) ? $fila['Tutor'] . " " . $fila['TutorPrimerAp'] . " " . $fila['TutorSegundoAp'] : 'Sin tutor asignado') . "</td>
                    <td>" . ($fila['enRiesgo'] === 'Si' ? 'Si' : 'No') . "</td>
                    <td class='action-buttons'>
                        <a href='detalle_alumno.php?nc=" . urlencode($fila['Num_Control']) . "' class='detalle btn btn-info btn-sm'>Detalle</a>
                        <a href='#modalEditar' class='editar btn btn-warning btn-sm' data-bs-toggle='modal'
                            data-control='{$fila['Num_Control']}'
                            data-nombre='{$fila['Nombre']}'
                            data-primer-ap='{$fila['Primer_Apellido']}'
                            data-segundo-ap='{$fila['Segundo_Apellido']}'
                            data-fecha='{$fila['Fecha_Nacimiento']}'
                            data-semestre='{$fila['Semestre']}'
                            data-carrera='" . (!empty($fila['Carrera']) ? $fila['Carrera'] : '') . "'
                           data-en-riesgo='" . ($fila['enRiesgo'] === 'Si' ? 'Si' : 'No') ."'>Editar</a>
                        <a href='#' class='eliminar btn btn-danger btn-sm' 
                            data-control='{$fila['Num_Control']}'
                            data-nombre='{$fila['Nombre']}'
                            data-primer-ap='{$fila['Primer_Apellido']}'
                            data-segundo-ap='{$fila['Segundo_Apellido']}'
                            data-fecha='{$fila['Fecha_Nacimiento']}'
                            data-semestre='{$fila['Semestre']}'
                            data-carrera='" . (!empty($fila['Carrera']) ? $fila['Carrera'] : '') . "'
                            data-tutor-id='" . (!empty($fila['Tutor_id']) ? $fila['Tutor_id'] : '') . "'
                            data-en-riesgo='" . ($fila['enRiesgo'] == '1' ? 'Sí' : 'No') . "'>
                            Eliminar
                        </a>
                    </td>
                  </tr>";
        }
        
        echo '</tbody></table>';
    } else {
        echo "<p>No hay datos disponibles.</p>";
    }
    

    ?>
</div>
<!-- Modal de Edición -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Alumno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditar" method="POST" action="../../backend/controllers/procesar_cambios.php">
                    <div class="mb-2">
                        <label for="numControl" class="form-label">Num. Control</label>
                        <input type="text" class="form-control form-control-sm" id="numControl" name="numControl" readonly>
                    </div>
                    <div class="mb-2">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control form-control-sm" id="nombre" name="nombre">
                    </div>
                    <div class="mb-2">
                        <label for="primerAp" class="form-label">Primer Apellido</label>
                        <input type="text" class="form-control form-control-sm" id="primerAp" name="primerAp">
                    </div>
                    <div class="mb-2">
                        <label for="segundoAp" class="form-label">Segundo Apellido</label>
                        <input type="text" class="form-control form-control-sm" id="segundoAp" name="segundoAp">
                    </div>
                    <div class="mb-2">
                        <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control form-control-sm" id="fechaNacimiento" name="fechaNacimiento">
                    </div>
                    <div class="mb-2">
                        <label for="semestre" class="form-label">Semestre</label>
                        <input type="number" class="form-control form-control-sm" id="semestre" name="semestre" min="1" max="12">
                    </div>
                    <div class="mb-2">
                        <label for="carrera" class="form-label">Carrera</label>
                        <select class="form-select form-control-sm" id="carrera" name="carrera">
                            <option value="ISC">ISC</option>
                            <option value="IM">IM</option>
                            <option value="IIA">IIA</option>
                            <option value="LA">LA</option>
                            <option value="LC">LC</option>
                        </select>

                    </div>

                    <div class="mb-2">
                        <label for="tutor" class="form-label">Tutor</label>
                        <select class="form-select form-control-sm" id="tutor" name="tutor">
                        <option value="" disabled selected>Seleccione un tutor</option>
                        <?php foreach ($tutores as $tutor): ?>
                        <option value="<?= $tutor['titulo'] . ' ' . $tutor['nombre'] . ' ' . $tutor['primer_apellido'] . ' ' . $tutor['segundo_apellido'] ?>">
                        <?= $tutor['titulo'] . ' ' . $tutor['nombre'] . ' ' . $tutor['primer_apellido'] . ' ' . $tutor['segundo_apellido'] ?>
                        </option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="enRiesgo" class="form-label">En Riesgo</label>
                        <select class="form-select form-control-sm" id="enRiesgo" name="enRiesgo">
                    <option value="No">No</option>
                    <option value="Si">Si</option>
                </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    const carreras = {
    'ISC': 'Ingeniería en Sistemas Computacionales',
    'IM': 'Ingeniería en Mecatrónica',
    'IIA': 'Ingeniería en Industrias Alimentarias',
    'LA': 'Licenciatura en Administración de Empresas',
    'LC': 'Licenciatura en Contaduría Pública'
};
//jQuery se utiliza para manejar eventos y manipular el DOM
$(document).on('click', '.editar', function () {
    const numControl = $(this).data('control');
    const nombre = $(this).data('nombre');
    const primerAp = $(this).data('primer-ap');
    const segundoAp = $(this).data('segundo-ap');
    const fecha = $(this).data('fecha');
    const semestre = $(this).data('semestre');
    const carrera = $(this).data('carrera') || '';
    const tutorId = $(this).data('tutor-id') || ''; // Asegúrate de que sea el ID del tutor
    const enRiesgo = $(this).data('en-riesgo');



    // Asignar los valores a los campos del modal
    $('#numControl').val(numControl);
    $('#nombre').val(nombre);
    $('#primerAp').val(primerAp);
    $('#segundoAp').val(segundoAp);
    $('#fechaNacimiento').val(fecha);
    $('#semestre').val(semestre);
    $('#carrera').val(carrera);
    $('#tutor').val(tutorId); // Seleccionar tutor por ID
    $('#enRiesgo').val(enRiesgo === 'Si' ? 'Si' : 'No');

    $('#tutor option').each(function () {
        const tutorText = $(this).text().toLowerCase();
        const tutorIdLower = tutorId.toLowerCase();

        if (tutorText.includes(tutorIdLower)) {
            $(this).prop('selected', true);
        }
    });
});

$(document).on('click', '.eliminar', function (e) {
    e.preventDefault();
    console.log("Botón de eliminar presionado");
    const alumno = {
        numControl: $(this).data('control'),
        nombre: $(this).data('nombre'),
        primerAp: $(this).data('primer-ap'),
        segundoAp: $(this).data('segundo-ap'),
        fecha: $(this).data('fecha'),
        semestre: $(this).data('semestre'),
        carrera: $(this).data('carrera'),
        tutorId: $(this).data('tutor-id'),
        enRiesgo: $(this).data('en-riesgo')
    };

    $.post('../../backend/controllers/guardar_memento.php', alumno, function () {
        const restoreButton = $(`
            <div id="restore-alert" class="alert alert-warning">
                Alumno eliminado. 
                <button id="restore-button" class="btn btn-success btn-sm">Deshacer</button>
            </div>
        `);
        $('body').prepend(restoreButton);

        $('#restore-button').on('click', function () {
            $.post('../../backend/controllers/restaurar_memento.php', function () {
                alert('Alumno restaurado exitosamente.');
                $('#restore-alert').remove();
            });
        });
    });
});


    $(document).ready(function () {
        setTimeout(function () {
            $(".alert").fadeOut("slow", function () {
                $(this).remove();
            });
        }, 5000);
    });
</script>
</body>
</html>
