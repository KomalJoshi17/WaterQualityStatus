<?php
header('Content-Type: application/json');

try {
    $conn = new mysqli("localhost", "root", "", "water");
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT state, pm2_5, pm10, aqi FROM air_quality ORDER BY state";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $airData = [];
    while ($row = $result->fetch_assoc()) {
        $airData[] = [
            'city_name' => $row['state'],  // Changed from city_name to state
            'pm2_5' => floatval($row['pm2_5']),
            'pm10' => floatval($row['pm10']),
            'aqi' => floatval($row['aqi'])
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $airData
    ]);

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