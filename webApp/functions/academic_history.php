<?php

$db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

// Get the student's academic history

$student_id = intval($_GET['student_id']); // Assuming student_id is passed as a query parameter

$query = "SELECT * FROM notas WHERE student_id = $1";
$result = pg_query_params($db, $query, array($student_id));

if (!$result) {
  echo "An error occurred.\n";
  exit;
}

$history = pg_fetch_all($result);

if ($history) {
  foreach ($history as $record) {
    echo "Course: " . htmlspecialchars($record['course']) . "<br>";
    echo "Grade: " . htmlspecialchars($record['grade']) . "<br>";
    echo "Semester: " . htmlspecialchars($record['semester']) . "<br><br>";
  }
} else {
  echo "No academic history found for the student.";
}






function generateStudentAcademicHistory($student_id) {
  global $db;

  // Query to get the academic history grouped by period
  $query = "
    SELECT 
      periodo_asignatura, 
      codigo_asignatura, 
      asignatura, 
      nota, 
      CASE 
        WHEN nota >= 4.0 THEN 'Aprobado' 
        WHEN nota < 4.0 THEN 'Reprobado' 
        ELSE 'Vigente' 
      END AS calificacion
    FROM 
      notas
    WHERE 
      student_id = $1
    ORDER BY 
      periodo_asignatura ASC, codigo_asignatura ASC
  ";
  $result = pg_query_params($db, $query, array($student_id));

  if (!$result) {
    echo "An error occurred.\n";
    exit;
  }

  $history = pg_fetch_all($result);

  if ($history) {
    $current_period = null;
    $courses_approved = 0;
    $courses_failed = 0;
    $courses_current = 0;
    $total_courses_approved = 0;
    $total_courses_failed = 0;
    $total_courses_current = 0;
    $total_grades = 0;
    $total_courses = 0;

    foreach ($history as $record) {
      if ($current_period !== $record['periodo_asignatura']) {
        if ($current_period !== null) {
          // Print summary for the previous period
          $pps = $total_grades / $total_courses;
          echo "<strong>Resumen del periodo $current_period:</strong><br>";
          echo "Cursos aprobados: $courses_approved<br>";
          echo "Cursos reprobados: $courses_failed<br>";
          echo "Cursos vigentes: $courses_current<br>";
          echo "Promedio del periodo (PPS): " . number_format($pps, 2) . "<br><br>";
        }

        // Reset counters for the new period
        $current_period = $record['periodo_asignatura'];
        $courses_approved = 0;
        $courses_failed = 0;
        $courses_current = 0;
        $total_grades = 0;
        $total_courses = 0;

        echo "<h3>Periodo: $current_period</h3>";
      }

      echo "Curso: " . htmlspecialchars($record['asignatura']) . "<br>";
      echo "Nota: " . htmlspecialchars($record['nota']) . "<br>";
      echo "Calificación: " . htmlspecialchars($record['calificacion']) . "<br><br>";

      // Update counters
      if ($record['calificacion'] === 'Aprobado') {
        $courses_approved++;
        $total_courses_approved++;
      } elseif ($record['calificacion'] === 'Reprobado') {
        $courses_failed++;
        $total_courses_failed++;
      } else {
        $courses_current++;
        $total_courses_current++;
      }

      $total_grades += floatval($record['nota']);
      $total_courses++;
    }

    // Print summary for the last period
    if ($current_period !== null) {
      $pps = $total_grades / $total_courses;
      echo "<strong>Resumen del periodo $current_period:</strong><br>";
      echo "Cursos aprobados: $courses_approved<br>";
      echo "Cursos reprobados: $courses_failed<br>";
      echo "Cursos vigentes: $courses_current<br>";
      echo "Promedio del periodo (PPS): " . number_format($pps, 2) . "<br><br>";
    }

    // Print total summary
    $ppa = ($total_courses_approved + $total_courses_failed + $total_courses_current) > 0 ? 
         ($total_grades / ($total_courses_approved + $total_courses_failed + $total_courses_current)) : 0;
    echo "<strong>Resumen total:</strong><br>";
    echo "Cursos aprobados: $total_courses_approved<br>";
    echo "Cursos reprobados: $total_courses_failed<br>";
    echo "Cursos vigentes: $total_courses_current<br>";
    echo "Promedio total (PPA): " . number_format($ppa, 2) . "<br><br>";

    // Determine student status
    $student_status = $total_courses_current > 0 ? 'Vigente' : ($total_courses_approved > 0 ? 'Licenciado o titulado' : 'No vigente');
    echo "<strong>Estado del estudiante:</strong> $student_status<br>";
  } else {
    echo "No academic history found for the student.";
  }
}

?>