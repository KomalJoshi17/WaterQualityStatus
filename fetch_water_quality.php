<?php
header('Content-Type: application/json');

try {
    $conn = new mysqli("localhost", "root", "", "water");
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Fetch water quality data
    $sql = "SELECT state_name as state, ph_level as pH_level, dissolved_oxygen, turbidity FROM water_quality";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $waterData = [];
    while ($row = $result->fetch_assoc()) {
        $waterData[] = $row;
    }

    $response = [
        'success' => true,
        'data' => [
            'water' => $waterData
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
