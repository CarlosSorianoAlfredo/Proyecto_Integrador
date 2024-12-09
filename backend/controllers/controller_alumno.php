<?php
include_once('../../database/conexion_bd_proyecto.php');
include_once(__DIR__ . '/../models/model_alumno.php');

class AlumnoDAO {
    private $conexion;

    public function __construct() {   
        $this->conexion = new ConexionBDEscuela(); 
    }

    //===========================METODOS ABCC(CRUD)====================================

    //----------------------------ALTAS---------------------------------
    public function agregarAlumno($alumno) {
        $numControl = $alumno->getNumControl();
        $nombre = $alumno->getNombre();
        $primerAp = $alumno->getPrimerAp();
        $segundoAp = $alumno->getSegundoAp();
        $fechaNacimiento = $alumno->getFechaNacimiento();
        $semestre = $alumno->getSemestre();
        $carrera = $alumno->getCarrera();
        $enRiesgo = $alumno->getEnRiesgo();
        $idTutor = $alumno->getTutor(); 

        $sql = "INSERT INTO alumno (Num_Control, Nombre, Primer_Apellido, Segundo_Apellido, Fecha_Nacimiento, Semestre, Id_carrera, enRiesgo, Id_tutor) 
        VALUES ('$numControl', '$nombre', '$primerAp', '$segundoAp', '$fechaNacimiento', $semestre, $carrera, '$enRiesgo', " . ($idTutor ? $idTutor : "NULL") . ")";

    
        $res = mysqli_query($this->conexion->getConexion(), $sql);
        return $res;
    }

    //----------------------------BAJAS---------------------------------
    public function eliminarAlumno($nc) {
        $sql = "DELETE FROM alumno WHERE Num_Control = '$nc'";
        $res = mysqli_query($this->conexion->getConexion(), $sql);
        return $res;
    }

    //----------------------------CAMBIOS---------------------------------
    public function actualizarAlumno($alumno) {
        $numControl = $alumno->getNumControl();
        $nombre = $alumno->getNombre();
        $primerAp = $alumno->getPrimerAp();
        $segundoAp = $alumno->getSegundoAp();
        $fechaNacimiento = $alumno->getFechaNacimiento();
        $semestre = $alumno->getSemestre();
        $carrera = $alumno->getCarrera();
        $enRiesgo = $alumno->getEnRiesgo();
        $idTutor = $alumno->getTutor(); // Tutor puede ser null
    
        // Construir la consulta SQL
        $sql = "UPDATE alumno SET 
                    Nombre = '$nombre', 
                    Primer_Apellido = '$primerAp', 
                    Segundo_Apellido = '$segundoAp', 
                    Fecha_Nacimiento = '$fechaNacimiento', 
                    Semestre = $semestre, 
                    Id_carrera = '$carrera', 
                    enRiesgo = '$enRiesgo', 
                    Id_tutor = " . ($idTutor ? $idTutor : "NULL") . " 
                WHERE Num_Control = '$numControl'";
    
        // Registrar la consulta para depuración (opcional)
        error_log("Consulta SQL: $sql");
    
        // Ejecutar la consulta
        $res = mysqli_query($this->conexion->getConexion(), $sql);
    
        // Verificar si hubo errores en la ejecución
        if (!$res) {
            error_log("Error en la consulta SQL: " . mysqli_error($this->conexion->getConexion()));
            return false; // O lanza una excepción si es necesario
        }
        return $res;
    }
    
    
    //----------------------------CONSULTAS---------------------------------
    /*public function mostrarAlumnos($filtro = "") {
        $sql = "SELECT * FROM alumno";
        if (!empty($filtro)) {
            $sql .= " WHERE Nombre LIKE '%" . mysqli_real_escape_string($this->conexion->getConexion(), $filtro) . "%'";
        }
        $res = mysqli_query($this->conexion->getConexion(), $sql);
        return $res;
    }*/  
        public function mostrarAlumnos($filtro = "") {
            $sql = "SELECT 
                        a.Num_Control, 
                        a.Nombre, 
                        a.Primer_Apellido, 
                        a.Segundo_Apellido, 
                        a.Fecha_Nacimiento, 
                        a.Semestre, 
                        c.abreviatura AS Carrera,  
                        t.nombre AS Tutor, 
                        a.enRiesgo 
                    FROM alumno a
                    LEFT JOIN carrera c ON a.Id_carrera = c.Id_carrera
                    LEFT JOIN tutor t ON a.Id_tutor = t.id_tutor";
            
            if (!empty($filtro)) {
                $sql .= " WHERE a.Nombre LIKE '%" . mysqli_real_escape_string($this->conexion->getConexion(), $filtro) . "%'";
            }
            
            $res = mysqli_query($this->conexion->getConexion(), $sql);
            return $res;
        }
        public function mostrarAlumnosFiltros($filtros) {
            $sql = "SELECT 
            alumno.Num_Control, 
            alumno.Nombre, 
            alumno.Primer_Apellido, 
            alumno.Segundo_Apellido, 
            alumno.Fecha_Nacimiento, 
            alumno.Semestre, 
            carrera.Nombre_carrera AS Carrera, 
            CONCAT(tutor.Titulo, ' ', tutor.Nombre, ' ', tutor.Primer_Apellido, ' ', tutor.Segundo_Apellido) AS Tutor, 
            alumno.enRiesgo AS EnRiesgo 
        FROM alumno
        LEFT JOIN carrera ON alumno.Id_carrera = carrera.Id_carrera
        LEFT JOIN tutor ON alumno.Id_tutor = tutor.Id_tutor
        WHERE 1=1";

            if (!empty($filtros['numControl'])) {
                $sql .= " AND alumno.Num_Control LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['numControl']) . "%'";
            }
            if (!empty($filtros['nombre'])) {
                $sql .= " AND alumno.Nombre LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['nombre']) . "%'";
            }
            if (!empty($filtros['primerAp'])) {
                $sql .= " AND alumno.Primer_Apellido LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['primerAp']) . "%'";
            }
            if (!empty($filtros['segundoAp'])) {
                $sql .= " AND alumno.Segundo_Apellido LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['segundoAp']) . "%'";
            }
            if (!empty($filtros['fechaNacimiento'])) {
                $sql .= " AND alumno.Fecha_Nacimiento = '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['fechaNacimiento']) . "'";
            }
            if (!empty($filtros['semestre'])) {
                $sql .= " AND alumno.Semestre = " . (int)$filtros['semestre'];
            }
           // Filtrar por carrera (Nombre)
           if (!empty($filtros['carrera'])) {
            $sql .= " AND carrera.Nombre_carrera LIKE '%" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['carrera']) . "%'";
        }
        

    // Filtrar por tutor (Nombre completo)
    if (!empty($filtros['tutor'])) {
        $sql .= " AND CONCAT(tutor.Titulo, ' ', tutor.Nombre, ' ', tutor.Primer_Apellido, ' ', tutor.Segundo_Apellido) LIKE '%" . 
            mysqli_real_escape_string($this->conexion->getConexion(), $filtros['tutor']) . "%'";
    }
    
            if (!empty($filtros['enRiesgo'])) {
                $sql .= " AND alumno.enRiesgo = '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['enRiesgo']) . "'";
            }
            
        
            $res = mysqli_query($this->conexion->getConexion(), $sql);
            if (!$res) {
                die("Error en la consulta: " . mysqli_error($this->conexion->getConexion()));
            }
        
            return $res;
        }
        
        
        
    /*public function mostrarAlumnosFiltros($filtros) {
        $sql = "SELECT * FROM alumno WHERE 1=1";
        if (!empty($filtros['numControl'])) {
            $sql .= " AND Num_Control LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['numControl']) . "%'";
        }
        if (!empty($filtros['nombre'])) {
            $sql .= " AND Nombre LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['nombre']) . "%'";
        }
        if (!empty($filtros['primerAp'])) {
            $sql .= " AND Primer_Apellido LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['primerAp']) . "%'";
        }
        if (!empty($filtros['segundoAp'])) {
            $sql .= " AND Segundo_Apellido LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['segundoAp']) . "%'";
        }
        if (!empty($filtros['fechaNacimiento'])) {
            $sql .= " AND Fecha_Nacimiento = '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['fechaNacimiento']) . "'";
        }
        if (!empty($filtros['semestre'])) {
            $sql .= " AND Semestre = " . (int)$filtros['semestre'];
        }
        if (!empty($filtros['carrera'])) {
            $sql .= " AND Carrera LIKE '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['carrera']) . "%'";
        }
        if (!empty($filtros['enRiesgo'])) {
            if (!empty($filtros['enRiesgo'])) {
                $sql .= " AND EnRiesgo = '" . mysqli_real_escape_string($this->conexion->getConexion(), $filtros['enRiesgo']) . "'";
            }
            
        }
        if (!empty($filtros['idTutor'])) {
            $sql .= " AND id_tutor = " . (int)$filtros['idTutor'];
        }
        
        $res = mysqli_query($this->conexion->getConexion(), $sql);
        return $res;
    }*/

    //----------------------------OBTENER TUTORES----------------------------
    public function obtenerTutores() {
        $sql = "SELECT id_tutor, nombre, primer_apellido,segundo_apellido,titulo FROM tutor";
        $res = mysqli_query($this->conexion->getConexion(), $sql);

        $tutores = [];
        while ($fila = mysqli_fetch_assoc($res)) {
            $tutores[] = $fila;
        }
        return $tutores;
    }
     //----------------------------OBTENER CARRERAS----------------------------
    public function obtenerCarreras() {
        $sql = "SELECT Id_carrera, Nombre_carrera FROM carrera";
        $res = mysqli_query($this->conexion->getConexion(), $sql);
    
        $carreras = [];
        while ($fila = mysqli_fetch_assoc($res)) {
            $carreras[] = $fila;
        }
        return $carreras;
  
    }
    public function obtenerAlumnoPorNumeroDeControl($numeroDeControl) {
        $sql = "SELECT * FROM alumno WHERE Num_Control = '$numeroDeControl'";
        $res = mysqli_query($this->conexion->getConexion(), $sql);
    
        if ($fila = mysqli_fetch_assoc($res)) {
            // Crear y devolver un objeto Alumno basado en los datos obtenidos
            return new Alumno(
                $fila['Num_Control'],
                $fila['Nombre'],
                $fila['Primer_Apellido'],
                $fila['Segundo_Apellido'],
                $fila['Fecha_Nacimiento'],
                $fila['Semestre'],
                $fila['Id_carrera'],
                $fila['Id_tutor'],
                $fila['enRiesgo'] 
            );
        }
        return null; // Devuelve null si no se encuentra el alumno
    }
    public function obtenerIdCarreraPorAbreviatura($abreviatura) {
        $sql = "SELECT Id_carrera FROM carrera WHERE TRIM(LOWER(abreviatura)) = TRIM(LOWER('$abreviatura'))";
        $result = mysqli_query($this->conexion->getConexion(), $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['Id_carrera'];
        }
        return null; // No encontrado
    }
    
    public function obtenerIdTutorPorNombre($nombreCompleto) {
        $sql = "SELECT Id_tutor 
                FROM tutor 
                WHERE TRIM(LOWER(CONCAT(titulo, ' ', nombre, ' ', primer_apellido, ' ', segundo_apellido))) = TRIM(LOWER('$nombreCompleto'))";
        $result = mysqli_query($this->conexion->getConexion(), $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['Id_tutor'];
        }
        return null; 
    }

    public function obtenerUltimoNumControl() {
    $conexion = $this->conexion->getConexion();  

    $query = "SELECT num_control FROM alumno ORDER BY num_control DESC LIMIT 1";
    $result = mysqli_query($conexion, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['num_control'];
    } else {
        return null;
    }
}

    
    public function obtenerCalificacionesPorAlumno($numeroDeControl) {
        $sql = "SELECT c.ID_asignatura, a.Nombre_asignatura, c.Puntaje
                FROM calificacion c
                INNER JOIN asignatura a ON c.ID_asignatura = a.ID_asignatura
                WHERE c.Numero_de_control = ?";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("s", $numeroDeControl);
        $stmt->execute();
        $result = $stmt->get_result();
        $calificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $calificaciones[] = $row;
        }
        $stmt->close();
        return $calificaciones;
    }
}
?>
