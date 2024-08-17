<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../../index.php");
    exit();
}

include_once '../../conexion.php';
include_once '../../Controller/MedicoController.php';
$controller = new MedicoController();

$conexion = conectarse();

$query = "SELECT tbusuario.dniusuario, tbusuario.nombres, tbusuario.apellidos, tbusuario.contrasenia, tbusuario.correo, 
          tbusuario.telefono, tbusuario.fechanacimiento, tbrol.nombre, tbusuario.direccion 
          FROM tbusuario
          INNER JOIN tbrol
          ON tbrol.idrol = tbusuario.fk_idrol
          WHERE tbusuario.dniusuario = ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombreCompleto = $row['nombres'] . ' ' . $row['apellidos'];
    $nombre = $row['nombres'];
    $apellido = $row['apellidos'];
    $correo = $row['correo'];
    $rol = $row['nombre'];
} else {
    echo "Error: Usuario no encontrado";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'ID' => $_POST['ID'] ?? '',
        'Nombres' => $_POST['nombres'] ?? '',
        'Apellidos' => $_POST['apellidos'] ?? '',
        'DNI' => $_POST['DNI'] ?? '',
        'Direccion' => $_POST['direccion'] ?? '',
        'Correo' => $_POST['correo'] ?? '',
        'Telefono' => $_POST['telefono'] ?? '',
        'Sexo' => $_POST['sexo'] ?? '',
        'NumColegiatura' => $_POST['colegiatura'] ?? '',
        'FechaNacimiento' => $_POST['fecha_nac'] ?? '',
        'Activo' => $_POST['estado'] ?? 0
    ];

    if (isset($_POST['update'])) {
        // Actualizar doctor
        if ($controller->updateDoctor($data)) {
            header('Location: administrardoctor.php');
        } else {
            echo "Error al actualizar el doctor.";
        }
    } else {
        // Insertar nuevo doctor
        if ($controller->insertarDoctor($data)) {
            header('Location: administrardoctor.php');
        } else {
            echo "Error al registrar el doctor.";
        }
    }
}

$doctores = $controller->listarDoctores();

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $doctorID = $_GET['edit'];
    $doctorDetails = $controller->obtenerDetalleDoctor($doctorID);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administrar Doctores</title>
  <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../../assets/css/styles.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="indexPersonal.php" class="text-nowrap logo-img">
            <img src="../../assets/images/logos/essalud_logo.jpg" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Perfil</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="indexPersonal.php" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Informacion del personal</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Mantenimiento</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="administrarcitas.php" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Citas Médicas</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="cronogramaPersonal.php" aria-expanded="false">
              <span>
                  <i><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-smile" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12zm12 -4v4m-8 -4v4m-4 4h16m-9.995 3h.01m3.99 0h.01" />
                  <path d="M10.005 17a3.5 3.5 0 0 0 4 0" />
                  </svg></i>
                </span>
                <span class="hide-menu">Administrar Cronograma</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="administrardoctor.php" aria-expanded="false">
                <span>
                  <i class="ti ti-cards"></i>
                </span>
                <span class="hide-menu">Administrar Doctores</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="administrarespecialidad.php" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Administrar Especialidades</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">IA</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="chatbot.php" aria-expanded="false">
                <span>
                  <i class="ti ti-login"></i>
                </span>
                <span class="hide-menu">Chat Bot - Médico</span>
              </a>
            </li>
            <br/>
            <center>
            <li class="sidebar-item">
              
              <a class="btn btn-danger" href="../../index.php" aria-expanded="false">               
                <span class="hide-menu">Cerrar Sesion</span>
              </a>
            </li>
            </center>
          </ul>
        </nav>
        <!-- End Sidebar navigation-->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="../../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3"><?php echo $nombreCompleto ?></p>
                    </a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <div class="container-fluid">
          <div class="card">
            <div class="card-body">
              <center>
              <h5 class="card-title fw-semibold mb-4">Registrar Doctor</h5>
              </center>
              <div class="card">
                <div class="card-body">
                <form method="POST" action="administrardoctor.php">
            <div class="mb-3">
                <label for="nombres" class="form-label">Nombres Completos:</label>
                <input type="text" class="form-control" id="nombres" name="nombres" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos Completos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
            <div class="mb-3">
                <label for="DNI" class="form-label">Documento de Identidad (DNI):</label>
                <input type="number" class="form-control" id="DNI" name="DNI" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electronico:</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Telefono:</label>
                <input type="number" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo:</label>
                <select class="form-control" id="sexo" name="sexo" required>
                    <option value="">--Selecciona un Genero--</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="colegiatura" class="form-label">Numero de Colegiatura:</label>
                <input type="text" class="form-control" id="colegiatura" name="colegiatura" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nac" class="form-label">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="">--Selecciona un Estado--</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <center>
                <button type="submit" class="btn btn-primary text-center">Registrar Doctor</button>
            </center>
        </form>                  
                </div>
                <div class="card-body">
                  <center>
                  <h5 class="card-title fw-semibold mb-4">Lista de Doctores</h5>
                  </center>
                  <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                            <table class="table text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nombres</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Apellidos</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">DNI</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Direccion</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Correo</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Telefono</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Sexo</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Colegiatura</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Fecha de Nacimiento</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Estado</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Acciones</h6>
                                    </th>                                
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                  $doctores = $controller->listarDoctores();
                                  if ($doctores) {
                                      while ($doctor = $doctores->fetch_assoc()) {
                                          echo "<tr>";
                                          echo "<td>" . $doctor['Nombres'] . "</td>";
                                          echo "<td>" . $doctor['Apellidos'] . "</td>";
                                          echo "<td>" . $doctor['DNI'] . "</td>";
                                          echo "<td>" . $doctor['Direccion'] . "</td>";
                                          echo "<td>" . $doctor['Correo'] . "</td>";
                                          echo "<td>" . $doctor['Telefono'] . "</td>";
                                          echo "<td>" . $doctor['Sexo'] . "</td>";
                                          echo "<td>" . $doctor['NumColegiatura'] . "</td>";
                                          echo "<td>" . $doctor['FechaNacimiento'] . "</td>";
                                          echo "<td>" . ($doctor['Activo'] ? 'Activo' : 'Inactivo') . "</td>";
                                          echo "<td><a href='administrardoctor.php?edit=" . $doctor['ID'] . "' class='btn btn-primary edit-button'>Editar</a></td>";
                                          echo "</tr>";
                                      }
                                  }
                                  ?>           
                                </tbody>
                            </table>
                            </div>
                        </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <?php if (isset($doctorDetails)): ?>
  <!-- Modal -->
  <div class="modal fade" id="editDoctorModal" tabindex="-1" role="dialog" aria-labelledby="editDoctorModalLabel" aria-hidden="true" style="display:block;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDoctorModalLabel">Editar Doctor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editDoctorForm" method="POST" action="administrardoctor.php">
            <input type="hidden" id="editDoctorID" name="ID" value="<?php echo $doctorDetails['ID']; ?>">
            <input type="hidden" name="update" value="1">
            <div class="mb-3">
              <label for="editNombres" class="form-label">Nombres Completos:</label>
              <input type="text" class="form-control" id="editNombres" name="nombres" value="<?php echo $doctorDetails['Nombres']; ?>">
            </div>
            <div class="mb-3">
              <label for="editApellidos" class="form-label">Apellidos Completos:</label>
              <input type="text" class="form-control" id="editApellidos" name="apellidos" value="<?php echo $doctorDetails['Apellidos']; ?>">
            </div>
            <div class="mb-3">
              <label for="editDireccion" class="form-label">Dirección:</label>
              <input type="text" class="form-control" id="editDireccion" name="direccion" value="<?php echo $doctorDetails['Direccion']; ?>">
            </div>
            <div class="mb-3">
              <label for="editCorreo" class="form-label">Correo Electronico:</label>
              <input type="email" class="form-control" id="editCorreo" name="correo" value="<?php echo $doctorDetails['Correo']; ?>">
            </div>
            <div class="mb-3">
              <label for="editTelefono" class="form-label">Telefono:</label>
              <input type="number" class="form-control" id="editTelefono" name="telefono" value="<?php echo $doctorDetails['Telefono']; ?>">
            </div>
            <div class="mb-3">
              <label for="editSexo" class="form-label">Sexo:</label>
              <select class="form-control" id="editSexo" name="sexo">
                <option value="">--Selecciona un Genero--</option>
                <option value="M" <?php if ($doctorDetails['Sexo'] == 'M') echo 'selected'; ?>>Masculino</option>
                <option value="F" <?php if ($doctorDetails['Sexo'] == 'F') echo 'selected'; ?>>Femenino</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="editColegiatura" class="form-label">Numero de Colegiatura:</label>
              <input type="text" class="form-control" id="editColegiatura" name="colegiatura" value="<?php echo $doctorDetails['NumColegiatura']; ?>">
            </div>
            <div class="mb-3">
              <label for="editFechaNac" class="form-label">Fecha de Nacimiento:</label>
              <input type="date" class="form-control" id="editFechaNac" name="fecha_nac" value="<?php echo $doctorDetails['FechaNacimiento']; ?>">
            </div>
            <div class="mb-3">
              <label for="editEstado" class="form-label">Estado:</label>
              <select class="form-control" id="editEstado" name="estado">
                <option value="1" <?php if ($doctorDetails['Activo'] == 1) echo 'selected'; ?>>Activo</option>
                <option value="0" <?php if ($doctorDetails['Activo'] == 0) echo 'selected'; ?>>Inactivo</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
        $('#editDoctorModal').modal('show');
    });
  </script>
  <?php endif; ?>
</body>
</html>
