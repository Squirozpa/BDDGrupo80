<!DOCTYPE html>
<html>
<head>
    <title>Propuesta de Clases</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2, h3 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 5px 0;
        }
        .summary {
            margin-top: 20px;
        }
        .summary p {
            margin: 5px 0;
        }
    </style>
</head>
<body>



<?php


function propose_classes($id_estudiante) {
  $db = pg_connect("host=localhost port=5432 dbname=grupo80 user=grupo80 password=grupo80");

  // Verificar si el estudiante está activo en 2024-2
  $result = pg_query_params($db, "SELECT * FROM estudiantes WHERE id_estudiante = $1 AND ultima_carga = '2024-2'", array($id_estudiante));
  if (pg_num_rows($result) == 0) {
    return "Student is not active in 2024-2.";
  }

  // Encuentra el plan de estudios del estudiante
  $student_plan_result = pg_query_params($db, "SELECT codigo_plan FROM estudiantes WHERE id_estudiante = $1", array($id_estudiante));
  $student_plan = pg_fetch_result($student_plan_result, 0, 'codigo_plan');

  // Encuentra los cursos aprobados por el estudiante
  $passed_courses = pg_query_params($db, "SELECT codigo_asignatura FROM notas WHERE id_estudiante = $1 AND nota >= 4", array($id_estudiante));
  $passed_courses_array = pg_fetch_all_columns($passed_courses, 0);

  // Encuentra los cursos disponibles en 2025-1 con el plan de estudios del estudiante
  $courses_2025_1 = pg_query_params($db, "SELECT id_asignatura FROM planeacion WHERE periodo = '2025-1' AND codigo_plan = $1", array($student_plan));
  $courses_2025_1_array = pg_fetch_all_columns($courses_2025_1, 0);

  // Filtrar los cursos que el estudiante puede tomar
  $proposed_courses = [];
  foreach ($courses_2025_1_array as $course_code) {
    // $prerequisites_result = pg_query_params($db, "SELECT prerequisite_course FROM prerequisitos WHERE course_code = $1", array($course_code));
    // $prerequisites = pg_fetch_all_columns($prerequisites_result, 0);

    // // Verificar si pasó todos los prerequisitos
    // $all_prerequisites_passed = true;
    // foreach ($prerequisites as $prerequisite) {
    //   if (!in_array($prerequisite, $passed_courses_array)) {
    //     $all_prerequisites_passed = false;
    //     break;
    //   }
    // }

    // if ($all_prerequisites_passed) {
    //   $proposed_courses[] = $course_code;
    // }

    $proposed_courses[] = $course_code;
  }

  pg_close($db);
  return $proposed_courses;
}

// Mostrar la propuesta de clases si se envió un ID de estudiante


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id_estudiante'])) {
    $id_estudiante = $_POST['id_estudiante'];
    $proposed_courses = propose_classes($id_estudiante);
    echo "<h2>Propuesta de Clases para el estudiante $id_estudiante</h2>";
    echo "<ul>";
    foreach ($proposed_courses as $course) {
      echo "<li>$course</li>";
    }
    echo "</ul>";
  } else {
    echo "Por favor, ingrese un ID de estudiante.";
  }
} else {
  echo '<form method="POST">';
  echo '<label for="id_estudiante">Ingrese el ID del estudiante:</label>';
  echo '<input type="text" name="id_estudiante" id="id_estudiante">';
  echo '<button type="submit">Enviar</button>';
  echo '</form>';
}

// // Example usage
// $id_estudiante = 12345; // Replace with actual student ID
// $proposed_courses = propose_classes($id_estudiante);
// print_r($proposed_courses);

?>

</body>
</html>