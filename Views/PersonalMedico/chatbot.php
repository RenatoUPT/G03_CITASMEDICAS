<?php
  session_start();

  if (!isset($_SESSION['userId'])) {
    header("Location: ../../index.php");
    exit();
  }

  include_once '../../conexion.php';
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
  <title>ChatBot - ESSALUD</title>
  <link rel="shortcut icon" type="image/png" href="../../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../../assets/css/styles.min.css" />
  <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 80px);
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.25rem;
        }

        .messages-box {
            flex-grow: 1;
            padding: 20px;
            background: white;
            overflow-y: auto;
        }

        .footer {
            padding: 10px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
        }

        .input-group {
            width: 100%;
            display: flex;
        }

        .input-group input {
            flex-grow: 1;
            border: 1px solid #ced4da;
            border-radius: 5px 0 0 5px;
            padding: 10px;
        }

        .input-group button {
            border: 1px solid #ced4da;
            border-left: 0;
            border-radius: 0 5px 5px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
  </style>
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
                <span class="hide-menu">Citas Medicas</span>
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
                <span class="hide-menu">Chat Bot - Medico</span>
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
                      <p class="mb-0 fs-3"><?php echo $nombreCompleto; ?></p>
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
        <div class="card">
            <div class="chat-container">
                <div class="header">
                    ChatBot EsSalud
                </div>
                <div class="messages-box" id="chat-container">
                    <!-- Aquí se mostrarán las preguntas y respuestas -->
                </div>
                <div style="display: flex; justify-content: center; align-items: center">
                  <span style="display: none" id="barra">
                    <img style="width: 286px" src="../../assets/images/barra.gif" alt="Procesando..." />
                  </span>
                </div>
                <div class="footer">                    
                    <div class="input-group">
                        <input id="input-question" class="form-control" type="text" placeholder="Escribe un mensaje...">
                        <button id="submit-button" class="submit-button">
                            Enviar
                        </button>
                    </div>
                </div>
            </div>
      </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/sidebarmenu.js"></script>
  <script src="../../assets/js/app.min.js"></script>
  <script src="../../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../../assets/js/dashboard.js"></script>
  <script>
        $(document).ready(function() {
            // Handler para enviar la pregunta al hacer Enter en el input
            $('#input-question').keypress(function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    var pregunta = $(this).val();
                    if (validarCampos(pregunta)) {
                        realizarPregunta(pregunta);
                    }
                }
            });

            // Handler para enviar la pregunta al hacer clic en el botón
            $('#submit-button').click(function() {
                var pregunta = $('#input-question').val();
                if (validarCampos(pregunta)) {
                    realizarPregunta(pregunta);
                }
            });
        });

        function validarCampos(pregunta) {
            if (pregunta === '') {
                alert('Ingresa una pregunta antes de enviarla.');
                return false;
            }
            return true;
        }

        function realizarPregunta(pregunta) {
            $("#barra").show();
            // Realizar la solicitud al servidor PHP
            $.ajax({
                type: "POST",
                url: "../ChatBot/chatAPI.php",
                data: {
                    mensaje: pregunta
                },
                success: function(respuesta) {
                    $("#barra").hide();
                    // Agregar la pregunta y respuesta al contenedor de chat
                    var preguntaHtml = `<strong>👨🏻Tu:</strong> ` + pregunta;
                    var respuestaHtml = '<strong>👨‍⚕️Asistente Bot:</strong> ' + respuesta;
                    // Obtén una referencia al elemento del div
                    var chatContainer = $('#chat-container');
                    chatContainer.append('<div class="text-end text-white bg-primary my-1 p-2 rounded">' + preguntaHtml + '</div>');
                    chatContainer.append('<div class="text-start bg-light my-1 p-2 rounded">' + respuestaHtml + '</div>');

                    // Limpiar el input y desplazarse al final del contenedor de chat
                    $('#input-question').val('');
                    chatContainer.scrollTop(chatContainer[0].scrollHeight);
                }
            });
        }
    </script>
</body>
</html>
