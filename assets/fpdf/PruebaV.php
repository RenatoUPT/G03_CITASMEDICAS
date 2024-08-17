<?php

require('./fpdf.php');
include_once '../../conexion.php'; // Incluir archivo de conexión

class PDF extends FPDF
{
    private $citas;
    private $orientation;

    // Constructor para pasar datos de citas
    function __construct($citas, $orientation = 'P')
    {
        parent::__construct($orientation); // 'P' para retrato (vertical), 'L' para paisaje (horizontal)
        $this->citas = $citas;
        $this->orientation = $orientation;
    }

    // Cabecera de página
    function Header()
    {
        $this->Image('logo.png', 10, 10, 40); // Logo de la empresa
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(45);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(180, 15, utf8_decode('ESSALUD - CALANA - TACNA'), 0, 1, 'C');

        $this->SetTextColor(103);

        // Título de la tabla
        $this->SetFillColor(36, 113, 163); // Color azul
        $this->SetTextColor(255, 255, 255); // Color blanco
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE DE CITAS MÉDICAS"), 0, 1, 'C', 1);
        $this->Ln(7);

        // Encabezados de la tabla
        $this->SetFillColor(36, 113, 163); // Color azul
        $this->SetTextColor(255, 255, 255); // Color blanco
        $this->SetDrawColor(255, 255, 255); // Borde blanco
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(35, 10, utf8_decode('Especialidad'), 1, 0, 'C', 1);
        $this->Cell(60, 10, utf8_decode('Médico'), 1, 0, 'C', 1);
        $this->Cell(80, 10, utf8_decode('Paciente'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(45, 10, utf8_decode('Inicio de Atención'), 1, 0, 'C', 1);
        $this->Cell(27, 10, utf8_decode('Estado'), 1, 1, 'C', 1);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');

    }

    // Método para generar el contenido de la tabla de citas
    function generarTablaCitas()
    {
        if ($this->citas) {
            while ($cita = $this->citas->fetch_assoc()) {
                $this->Cell(35, 10, utf8_decode($cita['NombreEspecialidad']), 1, 0, 'C');
                $this->Cell(60, 10, utf8_decode($cita['MedicoNombre'] . ' ' . $cita['MedicoApellido']), 1, 0, 'C');
                $this->Cell(80, 10, utf8_decode($cita['NombrePaciente'] . ' ' . $cita['ApellidoPaciente']), 1, 0, 'C');
                $this->Cell(30, 10, utf8_decode($cita['FechaAtencion']), 1, 0, 'C');
                $this->Cell(45, 10, utf8_decode($cita['InicioAtencion']), 1, 0, 'C');
                $this->Cell(27, 10, utf8_decode($cita['Estado']), 1, 1, 'C');
            }
        } else {
            $this->Cell(210, 10, utf8_decode("No hay citas médicas registradas."), 1, 1, 'C');
        }
    }
}

// Conexión a la base de datos
$conexion = conectarse();

// Consulta para obtener datos de citas médicas
$query = "SELECT citas.ID, especialidades.Nombre AS NombreEspecialidad, 
                 medicos.Nombres AS MedicoNombre, medicos.Apellidos AS MedicoApellido, 
                 pacientes.Nombres AS NombrePaciente, pacientes.Apellidos AS ApellidoPaciente, 
                 citas.FechaAtencion, citas.InicioAtencion, citas.Estado, citas.Activo
          FROM citas
          INNER JOIN especialidades ON especialidades.ID = citas.EspecialidadID
          INNER JOIN medicos ON medicos.ID = citas.MedicoID
          INNER JOIN pacientes ON pacientes.ID = citas.PacienteID
          WHERE citas.Estado = 'Programada' AND citas.Activo = 1";
$resultado = mysqli_query($conexion, $query);

// Crear PDF en formato horizontal si la tabla no cabe en una sola página vertical
$pdf = new PDF($resultado, 'L'); // 'L' para paisaje (horizontal)
$pdf->AddPage();

// Generar tabla de citas médicas
$pdf->generarTablaCitas();

$pdf->Output('Reporte_Citas_Medicas.pdf', 'I'); // 'I' para visualizar en el navegador, 'D' para descargar directamente
