<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Altas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<?php
        require_once('menu_principal.php');
    ?>
    <div style="border-top:10px; width:90%; margin:0 auto;">
<form  action="../controllers/procesar_altas.php" method="POST"class="row g-3">
  <div class="col-md-6">
    <label for="inputNumControl" class="form-label">Numero de Conntrol:</label>
    <input type="text" class="form-control" id="caja_num_control" name="caja_num_control" placeholder="Solo Numeros">
  </div>
  <div class="col-md-6">
    <label for="inputPassword" class="form-label">Nombre: </label>
    <input type="text" class="form-control" id="inputPassword">
  </div>
  <div class="col-12">
    <label for="inputPrimerAp" class="form-label">Primer Apellido: </label>
    <input type="text" class="form-control" id="inputPrimerAp" >
  </div>
  <div class="col-12">
    <label for="inputSegundoAp" class="form-label">Segundo Apellido: </label>
    <input type="text" class="form-control" id="inputSegundoAp">
  </div>
  <div class="col-md-6">
    <label for="inputEdad" class="form-label">Edad: </label>
    <input type="text" class="form-control" id="inputEdad">
  </div>
  <div class="col-md-6">
    <label for="inputSemestre" class="form-label">Semestre: </label>
    <input type="text" class="form-control" id="inputSemestre">
  </div>

  <div class="col-md-6">
    <label for="inputCarrera" class="form-label">Carrera</label>
    <input type="text" class="form-control" id="inputCarrera">
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">Agregar</button>
  </div>
</form>
</div>
</body>
</html>