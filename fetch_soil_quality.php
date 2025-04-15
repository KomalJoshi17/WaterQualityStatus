<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Test database connection
    $conn = new mysqli("localhost", "root", "", "water");
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Debug: Check if table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'soil_quality'");
    if ($checkTable->num_rows == 0) {
        throw new Exception("Table 'soil_quality' does not exist");
    }

    // Debug: Print query
    $sql = "SELECT state, soil_ph, organic_matter, nitrogen_matter FROM soil_quality ORDER BY state";
    error_log("Executing query: " . $sql);
    
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $soilData = [];
    while ($row = $result->fetch_assoc()) {
        $soilData[] = [
            'state' => $row['state'],
            'soil_ph' => floatval($row['soil_ph']),
            'organic_matter' => floatval($row['organic_matter']),
            'nitrogen_matter' => floatval($row['nitrogen_matter'])
        ];
    }

    // Debug: Check data count
    error_log("Found " . count($soilData) . " records");

    if (empty($soilData)) {
        throw new Exception("No data found in soil_quality table");
    }

    $response = [
        'success' => true,
        'data' => $soilData
    ];

    // Debug: Print response
    error_log("Response: " . json_encode($response));
    echo json_encode($response);

} catch (Exception $e) {
    error_log("Soil Quality Error: " . $e->getMessage());
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