<?php
header('Content-Type: application/json');

try {
    $conn = new mysqli("localhost", "root", "", "water");
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM noise_quality ORDER BY state";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $noiseData = [];
    while ($row = $result->fetch_assoc()) {
        $noiseData[] = [
            'state' => $row['state'],
            'decibel_day' => floatval($row['decibel_day']),
            'decibel_night' => floatval($row['decibel_night']),
            'peak_noise_industrial' => floatval($row['peak_noise_industrial']),
            'traffic_noise_index' => floatval($row['traffic_noise_index'])
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $noiseData
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