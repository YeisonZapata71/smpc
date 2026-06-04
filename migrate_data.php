<?php
$json = file_get_contents('data.json');
$data = json_decode($json, true);

$sectores = [];
$ejercicios = [];
$sector_id_counter = 1;

foreach ($data as $item) {
    $sectorName = $item['Ejercicio']; // Ejercicio in JSON is the sector name
    if (!isset($sectores[$sectorName])) {
        $sectores[$sectorName] = $sector_id_counter;
        $sector_id_counter++;
    }
}

$sql = "\n\n-- Inserción de datos migrados desde data.json\n\n";
$sql .= "INSERT INTO sectores (id, nombre) VALUES \n";
$sector_values = [];
foreach ($sectores as $nombre => $id) {
    $nombre_esc = addslashes($nombre);
    $sector_values[] = "($id, '$nombre_esc')";
}
$sql .= implode(",\n", $sector_values) . ";\n\n";

$sql .= "INSERT INTO ejercicios (id, nombre, sector_id) VALUES \n";
$ejercicio_values = [];
foreach ($data as $item) {
    $id = $item['Sector']; // Sector in JSON is actually the exercise ID
    $nombre = $item['Tipo'];
    $sector_id = $sectores[$item['Ejercicio']];
    
    $nombre_esc = addslashes($nombre);
    $ejercicio_values[] = "($id, '$nombre_esc', $sector_id)";
}
$sql .= implode(",\n", $ejercicio_values) . ";\n";

file_put_contents('database.sql', $sql, FILE_APPEND);
echo "Migración completada. Datos añadidos a database.sql\n";
?>
