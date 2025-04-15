<?php
header('Content-Type: application/json');

try {
    $conn = new mysqli("localhost", "root", "", "water");
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT state, gamma_radiation, emf_strength, radon_level FROM radiationdata ORDER BY state";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $radiationData = [];
    while ($row = $result->fetch_assoc()) {
        $radiationData[] = [
            'state' => $row['state'],
            'gamma_radiation' => floatval($row['gamma_radiation']),
            'emf_strength' => floatval($row['emf_strength']),
            'radon_level' => floatval($row['radon_level'])
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $radiationData
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