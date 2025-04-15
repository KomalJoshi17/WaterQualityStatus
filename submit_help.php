<?php
// Start output buffering
ob_start();

// Set headers
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Create response array
$response = ['status' => 'error', 'message' => ''];

try {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'project');
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Validate required fields
    $required = ['name', 'location', 'state'];
    $missing = array_filter($required, fn($field) => empty($_POST[$field]));
    
    if (!empty($missing)) {
        throw new Exception("Missing required fields: " . implode(', ', $missing));
    }

    // Prepare SQL
    $sql = "INSERT INTO help (
        name, location, state, ph_level, dissolved_oxygen, turbidity, 
        pm25, pm10, aqi, soil_ph, organic_matter, nitrogen_content, 
        gamma_radiation, emf_strength, radon_level, day_noise, 
        night_noise, industrial_noise, floral_diversity, 
        faunal_density, wetland_health, submission_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters with correct data types
    // Add debug logging
    error_log("POST data: " . print_r($_POST, true));

    // Prepare variables for binding
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $state = $_POST['state'] ?? '';
    $ph_level = isset($_POST['ph_level']) ? (float)$_POST['ph_level'] : 0.0;
    $dissolved_oxygen = isset($_POST['dissolved_oxygen']) ? (float)$_POST['dissolved_oxygen'] : 0.0;
    $turbidity = isset($_POST['turbidity']) ? (float)$_POST['turbidity'] : 0.0;
    $pm25 = isset($_POST['pm25']) ? (float)$_POST['pm25'] : 0.0;
    $pm10 = isset($_POST['pm10']) ? (float)$_POST['pm10'] : 0.0;
    $aqi = isset($_POST['aqi']) ? (int)$_POST['aqi'] : 0;
    $soil_ph = isset($_POST['soil_ph']) ? (float)$_POST['soil_ph'] : 0.0;
    $organic_matter = isset($_POST['organic_matter']) ? (float)$_POST['organic_matter'] : 0.0;
    $nitrogen_content = isset($_POST['nitrogen_content']) ? (float)$_POST['nitrogen_content'] : 0.0;
    $gamma_radiation = isset($_POST['gamma_radiation']) ? (float)$_POST['gamma_radiation'] : 0.0;
    $emf_strength = isset($_POST['emf_strength']) ? (float)$_POST['emf_strength'] : 0.0;
    $radon_level = isset($_POST['radon_level']) ? (float)$_POST['radon_level'] : 0.0;
    $day_noise = isset($_POST['day_noise']) ? (float)$_POST['day_noise'] : 0.0;
    $night_noise = isset($_POST['night_noise']) ? (float)$_POST['night_noise'] : 0.0;
    $industrial_noise = isset($_POST['industrial_noise']) ? (float)$_POST['industrial_noise'] : 0.0;
    $floral_diversity = isset($_POST['floral_diversity']) ? (float)$_POST['floral_diversity'] : 0.0;
    $faunal_density = isset($_POST['faunal_density']) ? (int)$_POST['faunal_density'] : 0;
    $wetland_health = isset($_POST['wetland_health']) ? (int)$_POST['wetland_health'] : 0;

    // Update bind_param with variables instead of direct $_POST access
    $bindResult = $stmt->bind_param(
        "sssdddddiddddddddddii",
        $name, $location, $state, 
        $ph_level, $dissolved_oxygen, $turbidity,
        $pm25, $pm10, $aqi, $soil_ph, 
        $organic_matter, $nitrogen_content,
        $gamma_radiation, $emf_strength, $radon_level,
        $day_noise, $night_noise, $industrial_noise,
        $floral_diversity, $faunal_density, $wetland_health
    );

    // Add more detailed error logging
    if (!$bindResult) {
        error_log("Bind error: " . $stmt->error);
        throw new Exception("Parameter binding failed: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    // Success response
    $response = [
        'status' => 'success',
        'message' => 'Environmental data saved successfully',
        'insert_id' => $stmt->insert_id
    ];

} catch (Exception $e) {
    http_response_code(500);
    $response['message'] = $e->getMessage();
} finally {
    // Clean output buffer and send JSON
    ob_end_clean();
    echo json_encode($response);
    
    // Close connections
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>