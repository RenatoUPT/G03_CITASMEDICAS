<?php
  session_start();

  if (!isset($_SESSION['userId'])) {
    header("Location: ../../index.php");
    exit();
  }

  include_once '../../conexion.php';
  include_once '../../Model/MedicoModel.php';
  $cronogramaModel = new MedicoModel();
  $horarios = $cronogramaModel->listarHorarios();
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

  $stmt->close();
  $conexion->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cronograma del Paciente</title>
    <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../../assets/css/styles.min.css" />
    <!-- SCRIPT DE TAILWINDCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="dns-prefetch" href="//unpkg.com" />
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net" />
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="indexPaciente.php" class="text-nowrap logo-img">
            <img src="../../assets/images/logos/essalud_logo.jpg" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Perfil</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="indexPaciente.php" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Informacion del paciente</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Citas Médicas</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="agendarCita.php" aria-expanded="false">
                <span>
                  <i class="ti ti-book-2"></i>
                </span>
                <span class="hide-menu">Reservar cita médica</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="cronogramaPaciente.php" aria-expanded="false">
                <span>
                  <i class="ti ti-calendar-smile"></i>
                </span>
                <span class="hide-menu">Cronograma</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="doctorpaciente.php" aria-expanded="false">
                <span>
                  <i class="ti ti-cards"></i>
                </span>
                <span class="hide-menu">Doctores</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="especialidadpaciente.php" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Especialidades</span>
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
      </div>
    </aside>
    <div class="body-wrapper">
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
                      <p class="mb-0 fs-3"><?php echo $nombreCompleto; ?></p>
                    </a>                   
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <div class="container-fluid">
      <div class="container-fluid">
          <div class="card">
            <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Horario de Atencion</h5>
              <div class="row">
              <?php foreach ($horarios as $horario): ?>
                <div class="col-md-4">
                  <div class="card">
                    <center>
                    <img src="../../assets/images/cita.png" class="card-img-top" alt="..." style="width: 200px; height: 200px">
                    </center>
                    <div class="card-body">                    
                      <h5 class="card-title">Doctor(a): <?php echo $horario['Nombres'] . " " . $horario['Apellidos'];?></h5><br/>
                      <p class="card-text"><strong>Fecha de Atencion: <?php echo $horario['FechaAtencion'];?></strong></p>
                      <p class="card-text"><strong>Inicio de Atencion: <?php echo $horario['InicioAtencion'];?></strong></p>
                      <p class="card-text"><strong>Fin de Atencion: <?php echo $horario['FinAtencion'];?></strong></p>                
                    </div>                    
                  </div>
                </div>
                <?php endforeach; ?>               
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/sidebarmenu.js"></script>
  <script src="../../assets/js/app.min.js"></script>
  <script src="../../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../../assets/js/dashboard.js"></script>
</body>
</html>
