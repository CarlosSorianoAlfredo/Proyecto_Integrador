<?php
include_once('../../database/conexion_bd_proyecto.php');

class AlumnoDAO {
    private $conexion;

   
    public function __construct() {
        $this->conexion = ConexionBDEscuela::getInstancia()->getConexion();
    }

    //=========================== METODOS ABCC (CRUD) ==========================
    //---------------------------- ALTAS ---------------------------------
    public function agregarAlumno($alumno) {
        $sql = "INSERT INTO alumno (Num_Control, Nombre, Primer_Apellido, Segundo_Apellido, Fecha_Nacimiento, Semestre, Id_carrera, enRiesgo, Id_tutor) 
                VALUES (:numControl, :nombre, :primerAp, :segundoAp, :fechaNacimiento, :semestre, :carrera, :enRiesgo, :idTutor)";
         $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(':numControl', $alumno->getNumControl());
        $stmt->bindValue(':nombre', $alumno->getNombre());
        $stmt->bindValue(':primerAp', $alumno->getPrimerAp());
        $stmt->bindValue(':segundoAp', $alumno->getSegundoAp());
        $stmt->bindValue(':fechaNacimiento', $alumno->getFechaNacimiento());
        $stmt->bindValue(':semestre', $alumno->getSemestre(), PDO::PARAM_INT);
        $stmt->bindValue(':carrera', $alumno->getCarrera(), PDO::PARAM_INT);
        $stmt->bindValue(':enRiesgo', $alumno->getEnRiesgo(), PDO::PARAM_STR);
        $stmt->bindValue(':idTutor', $alumno->getTutor(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    //---------------------------- BAJAS ---------------------------------
    public function eliminarAlumno($numControl) {
        $sql = "DELETE FROM alumno WHERE Num_Control = :numControl";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':numControl', $numControl);
        return $stmt->execute();
    }

    //---------------------------- CAMBIOS ---------------------------------
    public function actualizarAlumno($alumno) {
        $sql = "
            UPDATE alumno 
            SET 
                Nombre = :nombre,
                Primer_Apellido = :primerAp,
                Segundo_Apellido = :segundoAp,
                Fecha_Nacimiento = :fechaNacimiento,
                Semestre = :semestre,
                Id_carrera = :idCarrera,
                Id_tutor = :idTutor,
                enRiesgo = :enRiesgo
            WHERE 
                Num_Control = :numControl";
        
        $stmt = $this->conexion->prepare($sql);
    
        // Almacenar los valores en variables
        $nombre = $alumno->getNombre();
        $primerAp = $alumno->getPrimerAp();
        $segundoAp = $alumno->getSegundoAp();
        $fechaNacimiento = $alumno->getFechaNacimiento();
        $semestre = $alumno->getSemestre();
        $idCarrera = $alumno->getCarrera(); 
        $idTutor = $alumno->getTutor();
        $enRiesgo = $alumno->getEnRiesgo(); // 'Si' o 'No'
        $numControl = $alumno->getNumControl();
    
        // Usar las variables para bindParam
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':primerAp', $primerAp, PDO::PARAM_STR);
        $stmt->bindParam(':segundoAp', $segundoAp, PDO::PARAM_STR);
        $stmt->bindParam(':fechaNacimiento', $fechaNacimiento, PDO::PARAM_STR);
        $stmt->bindParam(':semestre', $semestre, PDO::PARAM_INT);
        $stmt->bindParam(':idCarrera', $idCarrera, PDO::PARAM_INT);
        $stmt->bindParam(':idTutor', $idTutor, PDO::PARAM_INT);
        
        // Actualización: Manejar 'enRiesgo' como cadena
        $stmt->bindParam(':enRiesgo', $enRiesgo, PDO::PARAM_STR); 
    
        $stmt->bindParam(':numControl', $numControl, PDO::PARAM_INT);
    
        return $stmt->execute();
    }
    

    public function obtenerAlumnoPorNumControl2($numControl) {
        try {
            // Consulta SQL para obtener la información del alumno
            $sql = "
                SELECT
                    Numero_Control,
                    Nombre_Alumno,
                    Primer_Apellido_Alumno,
                    Segundo_Apellido_Alumno,
                    Fecha_Nacimiento,
                    Semestre_Alumno,
                    Carrera,
                    Tutor,
                    Materias_y_Calificaciones
                FROM vista_informacion_alumno_calif
                WHERE Numero_Control = :numControl
            ";

            // Preparar la consulta
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':numControl', $numControl, PDO::PARAM_STR);

            // Ejecutar y obtener los datos
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return $resultado; // Devuelve un array asociativo con los datos del alumno
        } catch (PDOException $e) {
            // Manejar errores
            error_log("Error en obtenerAlumnoPorNumControl2: " . $e->getMessage());
            return null;
        }
    }
    //---------------------------- CONSULTAS ---------------------------------
    public function mostrarAlumnos($filtro = '') {
        $sql = "
            SELECT 
                a.Num_Control, 
                a.Nombre, 
                a.Primer_Apellido, 
                a.Segundo_Apellido, 
                a.Fecha_Nacimiento, 
                a.Semestre, 
                CASE
                    WHEN c.Nombre_carrera = 'Ingeniería en Sistemas Computacionales' THEN 'ISC'
                    WHEN c.Nombre_carrera = 'Ingeniería en Mecatrónica' THEN 'IM'
                    WHEN c.Nombre_carrera = 'Ingeniería en Industrias Alimentarias' THEN 'IIA'
                    WHEN c.Nombre_carrera = 'Licenciatura en Administración de Empresas' THEN 'LA'
                    WHEN c.Nombre_carrera = 'Licenciatura en Contaduría Pública' THEN 'LC'
                    ELSE 'Sin carrera'
                END AS Carrera,
                t.nombre AS Tutor, 
                t.primer_apellido AS TutorPrimerAp, 
                t.segundo_apellido AS TutorSegundoAp, 
                a.enRiesgo
            FROM 
                alumno a
            LEFT JOIN 
                carrera c ON a.Id_carrera = c.Id_carrera
            LEFT JOIN 
                tutor t ON a.Id_tutor = t.id_tutor";
        
        if (!empty($filtro)) {
            $sql .= " WHERE a.Nombre LIKE :filtro OR a.Primer_Apellido LIKE :filtro";
        }
        
        $stmt = $this->conexion->prepare($sql);
        
        if (!empty($filtro)) {
            $stmt->bindValue(':filtro', "%$filtro%", PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    
        // Creamos un array para almacenar los parámetros de la consulta
        $params = [];
    
        // Filtramos por número de control
        if (!empty($filtros['numControl'])) {
            $sql .= " AND alumno.Num_Control LIKE :numControl";
            $params[':numControl'] = $filtros['numControl'] . '%';
        }
        
        // Filtramos por nombre
        if (!empty($filtros['nombre'])) {
            $sql .= " AND alumno.Nombre LIKE :nombre";
            $params[':nombre'] = $filtros['nombre'] . '%';
        }
    
        // Filtramos por primer apellido
        if (!empty($filtros['primerAp'])) {
            $sql .= " AND alumno.Primer_Apellido LIKE :primerAp";
            $params[':primerAp'] = $filtros['primerAp'] . '%';
        }
    
        // Filtramos por segundo apellido
        if (!empty($filtros['segundoAp'])) {
            $sql .= " AND alumno.Segundo_Apellido LIKE :segundoAp";
            $params[':segundoAp'] = $filtros['segundoAp'] . '%';
        }
    
        // Filtramos por fecha de nacimiento
        if (!empty($filtros['fechaNacimiento'])) {
            $sql .= " AND alumno.Fecha_Nacimiento = :fechaNacimiento";
            $params[':fechaNacimiento'] = $filtros['fechaNacimiento'];
        }
    
        // Filtramos por semestre
        if (!empty($filtros['semestre'])) {
            $sql .= " AND alumno.Semestre = :semestre";
            $params[':semestre'] = (int)$filtros['semestre'];
        }
    
        // Filtramos por carrera
        if (!empty($filtros['carrera'])) {
            $sql .= " AND carrera.Nombre_carrera LIKE :carrera";
            $params[':carrera'] = '%' . $filtros['carrera'] . '%';
        }
    
        // Filtramos por tutor
        if (!empty($filtros['tutor'])) {
            $sql .= " AND CONCAT(tutor.Titulo, ' ', tutor.Nombre, ' ', tutor.Primer_Apellido, ' ', tutor.Segundo_Apellido) LIKE :tutor";
            $params[':tutor'] = '%' . $filtros['tutor'] . '%';
        }
    
        if (!empty($filtros['enRiesgo'])) {
            if ($filtros['enRiesgo'] === 'Sí') {
                $sql .= " AND alumno.enRiesgo = 'Sí'";  // Comparamos con 'Sí'
            } elseif ($filtros['enRiesgo'] === 'No') {
                $sql .= " AND alumno.enRiesgo = 'No'";  // Comparamos con 'No'
            }
        }
        
    
        // Preparar y ejecutar la consulta
        $stmt = $this->conexion->prepare($sql);
        
        if ($stmt->execute($params)) {
            return $stmt;  // Retornamos el resultado de la consulta
        } else {
            die("Error al ejecutar la consulta: " . implode(" - ", $stmt->errorInfo()));
        }
    }
    


    //---------------------------- OTROS MÉTODOS ---------------------------------
    public function obtenerTutores() {
        $sql = "SELECT id_tutor, nombre, primer_apellido, segundo_apellido, titulo FROM tutor";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerAlumnoPorNumeroDeControl($numeroDeControl) {
        $sql = "SELECT Num_Control, Nombre, Primer_Apellido, Id_carrera, Semestre 
                FROM alumno 
                WHERE Num_Control = :numeroDeControl"; // Usar Primer_Apellido correctamente
    
        $stmt = $this->conexion->prepare($sql); 
        $stmt->bindParam(':numeroDeControl', $numeroDeControl, PDO::PARAM_STR);
        $stmt->execute();
    
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($alumno) {
            return (object) [
                'getNumeroDeControl' => $alumno['Num_Control'],
                'getNombre' => $alumno['Nombre'],
                'getPrimerAp' => $alumno['Primer_Apellido'], // Columna corregida
                'getCarrera' => $alumno['Id_carrera'],
                'getSemestre' => $alumno['Semestre'],
            ];
        }
    
        return null;
    }
    

    // Método para obtener calificaciones por alumno
    public function obtenerCalificacionesPorAlumno($numeroDeControl) {
        try {
            $sql = "SELECT ID_asignatura, Puntaje 
                    FROM calificacion 
                    WHERE Numero_de_Control = :numeroDeControl"; // Cambiar a Num_Control
            
            $stmt = $this->conexion->prepare($sql); 
            $stmt->bindParam(':numeroDeControl', $numeroDeControl, PDO::PARAM_STR);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerCalificacionesPorAlumno: " . $e->getMessage());
            return [];
        }
    }
    
    
    
    public function obtenerCarreras() {
        $sql = "SELECT Id_carrera, Nombre_carrera FROM carrera";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimoNumControl() {
        $sql = "SELECT Num_Control FROM alumno ORDER BY Num_Control DESC LIMIT 1";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchColumn();
    }

    public function obtenerIdTutorPorNombre($nombreCompleto) {
        $sql = "SELECT id_tutor FROM tutor WHERE CONCAT(titulo,' ',nombre, ' ', primer_apellido, ' ', segundo_apellido) = :nombreCompleto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombreCompleto', $nombreCompleto, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn(); // Devuelve el primer valor (id_tutor)
    }
    public function obtenerIdCarreraPorAbreviatura($abreviatura) {
        $sql = "SELECT Id_carrera FROM carrera WHERE Abreviatura = :abreviatura";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':abreviatura', $abreviatura, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn(); // Devuelve el primer valor (Id_carrera)
    }
        
}
?>
