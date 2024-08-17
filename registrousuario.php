<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro de usuario</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <h2>Crea una cuenta en nuestro centro de salud..!</h2><br/>
                <form method="POST" action="Controller/RegistroController.php">
                  <div class="mb-3">
                    <label for="dniusuario" class="form-label">DNI:</label>
                    <input type="number" class="form-control" id="dniusuario" name="dniusuario" required>
                  </div>
                  <div class="mb-3">
                    <label for="nombreusuario" class="form-label">Nombres completos:</label>
                    <input type="text" class="form-control" id="nombreusuario" name="nombreusuario" required>
                  </div>
                  <div class="mb-3">
                    <label for="apellidousuario" class="form-label">Apellidos Completos:</label>
                    <input type="text" class="form-control" id="apellidousuario" name="apellidousuario" required>
                  </div>
                  <div class="mb-3">
                    <label for="direccionusuario" class="form-label">Dirección:</label>
                    <input type="text" class="form-control" id="direccionusuario" name="direccionusuario" required>
                  </div>
                  <div class="mb-3">
                    <label for="telefonousuario" class="form-label">Teléfono:</label>
                    <input type="number" class="form-control" id="telefonousuario" name="telefonousuario" required>
                  </div>
                  <div class="mb-3">
                    <label for="fechanacimientousuario" class="form-label">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="fechanacimientousuario" name="fechanacimientousuario" required>
                  </div>
                  <div class="mb-3">
                    <label for="sexousuario" class="form-label">Sexo:</label>
                    <select class="form-control" id="sexousuario" name="sexousuario" required>
                        <option value="">Seleccione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="correousuario" class="form-label">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="correousuario" name="correousuario" required>
                  </div>
                  <div class="mb-4">
                    <label for="contrasenia" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Crear cuenta</button>
                </form>
		<center>
		<label>Si ya tienes cuenta..<a href="index.php">Iniciar Sesion</a></label>
		</center>	
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
