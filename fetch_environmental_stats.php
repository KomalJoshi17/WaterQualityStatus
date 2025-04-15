<?php
header('Content-Type: application/json');

try {
    $conn = new mysqli("localhost", "root", "", "water");
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT state, Floral_Diversity, Faunal_Density, Wetland_Health FROM environmental_stats ORDER BY state";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $envData = [];
    while ($row = $result->fetch_assoc()) {
        $envData[] = [
            'state' => $row['state'],
            'Floral_Diversity' => floatval($row['Floral_Diversity']),
            'Faunal_Density' => floatval($row['Faunal_Density']),
            'Wetland_Health' => floatval($row['Wetland_Health'])
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $envData
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>