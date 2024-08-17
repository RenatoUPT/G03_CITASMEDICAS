<?php
  session_start();

  if (!isset($_SESSION['userId'])) {
    header("Location: ../../index.php");
    exit();
  }

  include_once '../../conexion.php';
  include_once '../../Model/EspecialidadModel.php';
  
  $especialidadModel = new EspecialidadModel();
  $medicos = $especialidadModel->obtenerMedicos();
  $especialidades = $especialidadModel->obtenerEspecialidades();
  $especialidaddoctores = $especialidadModel->listarEspecialidad();
  
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administrar Especialidades</title>
  <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../../assets/css/styles.min.css" />
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
                <span class="hide-menu">Administrar <br/>Especialidades</span>
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
        <!-- End Sidebar navigation -->
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
              <h5 class="card-title fw-semibold mb-4">Administrar Especialidades</h5>
              </center>
              <div class="card">
                <div class="card-body">
                  <form action="../../Controller/EspecialidadController.php" method="POST">
                    <div class="mb-3">
                        <label for="medico" class="form-label">Medico:</label>
                        <select id="medico" name="medico" class="form-control">
                        <option>--Seleccione un Medico--</option>
                        <?php foreach ($medicos as $medico): ?>
                                        <option value="<?php echo $medico['ID']; ?>"><?php echo $medico['Nombres']; ?> <?php echo $medico['Apellidos']; ?> </option>
                                    <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                      <label for="especialidad" class="form-label">Especialidad:</label>
                      <select name="especialidad" id="especialidad" class="form-control">
                          <option>--Seleccione un Especialidad--</option>
                          <?php foreach ($especialidades as $especialidad): ?>
                                        <option value="<?php echo $especialidad['ID']; ?>"><?php echo $especialidad['Nombre']; ?> </option>
                                    <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_reg" class="form-label">Fecha de Registro:</label>
                        <input type="date" class="form-control" name="fecha_reg" id="fecha_reg"/>
                    </div>                   
                    <center> 
                      <button type="submit" name="registrar" class="btn btn-primary text-center">Registrar Especialidad</button>
                    </center>
                  </form>
                  </div>
                <div class="card-body">
                  <center>
                  <h5 class="card-title fw-semibold mb-4">Lista de Doctores y Especialidades</h5>
                  </center>
                  <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                            <table class="table text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Medico</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Especialidad</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Fecha de Registro</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Fecha de Modificacion</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Activo</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Accion</h6>
                                    </th>                      
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($especialidaddoctores as $especialidaddoctor): ?>
                                         <tr>
                                          <td> <?php echo $especialidaddoctor['Nombres'];?> <?php echo $especialidaddoctor['Apellidos'];?></td>
                                          <td> <?php echo $especialidaddoctor['Nombre'];?></td>
                                          <td> <?php echo $especialidaddoctor['FechaRegistro'];?></td>
                                          <td> <?php echo $especialidaddoctor['FechaModificacion'];?></td>
                                          <td> <?php
                                                if ($especialidaddoctor['Activo'] == 1) {
                                                    echo "Activo";
                                                } else {
                                                    echo "Inactivo";
                                                }
                                                ?>
                                          </td>
                                          <td> <button class="btn btn-primary detalle-button" data-especialidaddoctor='<?php echo json_encode($especialidaddoctor); ?>'>Editar</button></td>
                                        </tr>
                                      <?php endforeach; ?>      
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
  </div>
  <!-- Modal -->
<div class="modal fade" id="detalleEspecialidad" tabindex="-1" role="dialog" aria-labelledby="detalleEspecialidadLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleEspecialidadLabel">Detalle de Especialidad</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarEspecialidad" action="../../Controller/EspecialidadController.php" method="POST">
                <div class="modal-body">
                <input type="hidden" id="modalID" name="ID">
                    <div class="form-group">
                        <label for="medico">Médico</label>
                        <input type="text" class="form-control" id="modalMedico" name="medico" readonly>
                    </div>
                    <div class="form-group">
                        <label for="fecha_reg">Fecha de Registro</label>
                        <input type="text" class="form-control" id="modalFechaReg" name="fecha_reg" readonly>
                    </div>
                    <div class="form-group">
                        <label for="especialidad">Especialidad</label>
                        <select name="especialidad" id="especialidad" class="form-control">
                          <?php foreach ($especialidades as $especialidad): ?>
                                        <option value="<?php echo $especialidad['ID']; ?>"><?php echo $especialidad['Nombre']; ?> </option>
                                    <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha_modi">Fecha Modificación</label>
                        <input type="date" class="form-control" id="modalFechaModi" name="fecha_modi" require>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="modalEstado" name="estado" require>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <input type="hidden" name="accion" value="editar">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const detalleButtons = document.querySelectorAll(".detalle-button");

        detalleButtons.forEach(button => {
            button.addEventListener("click", function() {
                const especialidad = JSON.parse(this.getAttribute("data-especialidaddoctor"));

                document.getElementById("modalID").value = especialidad.ID;
                document.getElementById("modalMedico").value = especialidad.Nombres + ' ' + especialidad.Apellidos;
                document.getElementById("modalFechaReg").value = especialidad.FechaRegistro;
                document.getElementById("modalFechaModi").value = especialidad.FechaModificacion;
                document.getElementById("modalEstado").value = especialidad.Activo;

                const detalleEspecialidadModal = new bootstrap.Modal(document.getElementById("detalleEspecialidad"));
                detalleEspecialidadModal.show();
            });
        });
    });
</script>

</body>
</html>