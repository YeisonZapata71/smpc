$OutputEncoding = [System.Text.Encoding]::UTF8
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
$data = Get-Content -Path "data.json" -Raw -Encoding UTF8 | ConvertFrom-Json
$sectores = [ordered]@{}
$sector_counter = 1

foreach ($item in $data) {
    if (-not $sectores.Contains($item.Ejercicio)) {
        $sectores[$item.Ejercicio] = $sector_counter
        $sector_counter++
    }
}

$sql = "`r`n`r`n-- Inserción de datos migrados desde data.json`r`n`r`n"
$sql += "INSERT INTO sectores (id, nombre) VALUES `r`n"
$sector_values = @()
foreach ($key in $sectores.Keys) {
    $id = $sectores[$key]
    $name = $key -replace "'", "\'"
    $sector_values += "($id, '$name')"
}
$sql += ($sector_values -join ",`r`n") + ";`r`n`r`n"

$sql += "INSERT INTO ejercicios (id, nombre, sector_id) VALUES `r`n"
$ejercicio_values = @()
foreach ($item in $data) {
    $id = $item.Sector
    $name = $item.Tipo -replace "'", "\'"
    $sector_id = $sectores[$item.Ejercicio]
    $ejercicio_values += "($id, '$name', $sector_id)"
}
$sql += ($ejercicio_values -join ",`r`n") + ";`r`n"

Add-Content -Path "database.sql" -Value $sql -Encoding UTF8
Write-Host "Migración completada"
