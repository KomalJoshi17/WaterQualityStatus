<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the most recent submission if coming directly from form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store all POST data in variables
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $state = $_POST['state'] ?? '';
    $ph_level = $_POST['ph_level'] ?? '';
    $dissolved_oxygen = $_POST['dissolved_oxygen'] ?? '';
    $turbidity = $_POST['turbidity'] ?? '';
    $pm25 = $_POST['pm25'] ?? '';
    $pm10 = $_POST['pm10'] ?? '';
    $aqi = $_POST['aqi'] ?? '';
    $soil_ph = $_POST['soil_ph'] ?? '';
    $organic_matter = $_POST['organic_matter'] ?? '';
    $nitrogen_content = $_POST['nitrogen_content'] ?? '';
    $gamma_radiation = $_POST['gamma_radiation'] ?? '';
    $emf_strength = $_POST['emf_strength'] ?? '';
    $radon_level = $_POST['radon_level'] ?? '';
    $day_noise = $_POST['day_noise'] ?? '';
    $night_noise = $_POST['night_noise'] ?? '';
    $industrial_noise = $_POST['industrial_noise'] ?? '';
    $floral_diversity = $_POST['floral_diversity'] ?? '';
    $faunal_density = $_POST['faunal_density'] ?? '';
    $wetland_health = $_POST['wetland_health'] ?? '';
    
    // Insert data into database
    $sql = "INSERT INTO help (
        name, location, state, ph_level, dissolved_oxygen, turbidity, 
        pm25, pm10, aqi, soil_ph, organic_matter, nitrogen_content, 
        gamma_radiation, emf_strength, radon_level, day_noise, 
        night_noise, industrial_noise, floral_diversity, 
        faunal_density, wetland_health, submission_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssdddddiddddddddddii",
        $name, $location, $state, 
        $ph_level, $dissolved_oxygen, $turbidity,
        $pm25, $pm10, $aqi, $soil_ph, $organic_matter, $nitrogen_content,
        $gamma_radiation, $emf_strength, $radon_level, $day_noise,
        $night_noise, $industrial_noise, $floral_diversity,
        $faunal_density, $wetland_health
    );
    
    $stmt->execute();
    $submission_id = $conn->insert_id;
    $stmt->close();
} else {
    // If accessed directly without POST data, get the most recent submission
    $submission_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($submission_id === 0) {
        // Get the most recent submission
        $result = $conn->query("SELECT id FROM help ORDER BY submission_date DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $submission_id = $row['id'];
        } else {
            die("No submissions found.");
        }
    }
}

// Fetch the submission data
$sql = "SELECT * FROM help WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $submission_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Submission not found.");
}

$data = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Function to get quality status based on value and thresholds
function getQualityStatus($value, $param) {
    switch ($param) {
        case 'ph_level':
            if ($value >= 6.5 && $value <= 8.5) return ['Good', 'green'];
            else if (($value >= 6.0 && $value < 6.5) || ($value > 8.5 && $value <= 9.0)) return ['Fair', 'orange'];
            else return ['Poor', 'red'];
        
        case 'dissolved_oxygen':
            if ($value >= 6.5) return ['Good', 'green'];
            else if ($value >= 4.5 && $value < 6.5) return ['Fair', 'orange'];
            else return ['Poor', 'red'];
            
        case 'turbidity':
            if ($value <= 5) return ['Good', 'green'];
            else if ($value > 5 && $value <= 10) return ['Fair', 'orange'];
            else return ['Poor', 'red'];
            
        case 'aqi':
            if ($value <= 50) return ['Good', 'green'];
            else if ($value > 50 && $value <= 100) return ['Moderate', 'lightgreen'];
            else if ($value > 100 && $value <= 150) return ['Unhealthy for Sensitive Groups', 'orange'];
            else if ($value > 150 && $value <= 200) return ['Unhealthy', 'red'];
            else if ($value > 200 && $value <= 300) return ['Very Unhealthy', 'purple'];
            else return ['Hazardous', 'maroon'];
            
        case 'soil_ph':
            if ($value >= 6.0 && $value <= 7.5) return ['Good', 'green'];
            else if (($value >= 5.5 && $value < 6.0) || ($value > 7.5 && $value <= 8.0)) return ['Fair', 'orange'];
            else return ['Poor', 'red'];
            
        case 'organic_matter':
            if ($value >= 3.0) return ['Good', 'green'];
            else if ($value >= 1.5 && $value < 3.0) return ['Fair', 'orange'];
            else return ['Poor', 'red'];
            
        case 'nitrogen_content':
            if ($value >= 0.15) return ['Good', 'green'];
            else if ($value >= 0.1 && $value < 0.15) return ['Fair', 'orange'];
            else return ['Poor', 'red'];
            
        case 'gamma_radiation':
            if ($value <= 0.3) return ['Safe', 'green'];
            else if ($value > 0.3 && $value <= 1.0) return ['Elevated', 'orange'];
            else return ['High', 'red'];
            
        case 'emf_strength':
            if ($value <= 0.5) return ['Low', 'green'];
            else if ($value > 0.5 && $value <= 2.5) return ['Moderate', 'orange'];
            else return ['High', 'red'];
            
        case 'radon_level':
            if ($value <= 2.0) return ['Safe', 'green'];
            else if ($value > 2.0 && $value <= 4.0) return ['Moderate', 'orange'];
            else return ['High', 'red'];
            
        case 'day_noise':
        case 'night_noise':
        case 'industrial_noise':
            if ($value <= 55) return ['Quiet', 'green'];
            else if ($value > 55 && $value <= 70) return ['Moderate', 'orange'];
            else return ['Loud', 'red'];
            
        case 'floral_diversity':
            if ($value >= 4.0) return ['High', 'green'];
            else if ($value >= 2.5 && $value < 4.0) return ['Moderate', 'orange'];
            else return ['Low', 'red'];
            
        case 'faunal_density':
            if ($value >= 80) return ['High', 'green'];
            else if ($value >= 60 && $value < 80) return ['Moderate', 'orange'];
            else return ['Low', 'red'];
            
        case 'wetland_health':
            if ($value >= 75) return ['Good', 'green'];
            else if ($value >= 50 && $value < 75) return ['Fair', 'orange'];
            else return ['Poor', 'red'];
            
        default:
            return ['Unknown', 'gray'];
    }
}

// Add this new function to generate recommendations based on parameter values
function getRecommendation($value, $param) {
    switch ($param) {
        case 'ph_level':
            if ($value < 6.5) return "Acidic water can damage plumbing and affect aquatic life. Consider adding limestone or dolomite to increase pH.";
            else if ($value > 8.5) return "Alkaline water may taste bitter and form scale. Consider adding food-grade acids or specialized pH reducers.";
            else return "pH level is within the optimal range. Continue regular monitoring.";
            
        case 'dissolved_oxygen':
            if ($value < 4.5) return "Low oxygen levels are dangerous for aquatic life. Improve aeration, reduce organic waste, or install water circulation systems.";
            else if ($value < 6.5) return "Moderate oxygen levels may stress some aquatic species. Consider improving water movement or reducing nutrient load.";
            else return "Oxygen levels are good. Maintain current conditions and monitor regularly.";
            
        case 'turbidity':
            if ($value > 10) return "High turbidity blocks sunlight and can harbor pathogens. Implement filtration systems, reduce soil erosion, or use settling techniques.";
            else if ($value > 5) return "Moderate turbidity may affect some aquatic organisms. Consider basic filtration or settling methods.";
            else return "Water clarity is good. Continue current management practices.";
            
        case 'pm25':
            if ($value > 35) return "Dangerous levels of fine particulates. Use air purifiers, wear masks outdoors, and reduce outdoor activities.";
            else if ($value > 12) return "Elevated PM2.5 levels may affect sensitive groups. Consider air filtration and limiting prolonged outdoor exposure.";
            else return "PM2.5 levels are acceptable. Continue monitoring air quality.";
            
        case 'pm10':
            if ($value > 150) return "Hazardous levels of coarse particulates. Limit outdoor activities, use air purifiers, and wear appropriate masks.";
            else if ($value > 50) return "Moderate PM10 levels may cause respiratory irritation. Consider air filtration and reducing dust sources.";
            else return "PM10 levels are within safe limits. Maintain current air quality measures.";
            
        case 'aqi':
            if ($value > 300) return "Hazardous air quality. Stay indoors, use air purifiers, and follow health advisories.";
            else if ($value > 200) return "Very unhealthy air quality. Minimize outdoor activities and use respiratory protection.";
            else if ($value > 150) return "Unhealthy air quality. Sensitive groups should avoid outdoor exertion.";
            else if ($value > 100) return "Air quality is unhealthy for sensitive groups. Consider limiting prolonged outdoor activities.";
            else if ($value > 50) return "Moderate air quality. Unusually sensitive people should consider reducing prolonged outdoor exertion.";
            else return "Good air quality. Continue regular monitoring.";
            
        case 'soil_ph':
            if ($value < 5.5) return "Soil is too acidic. Add agricultural lime to raise pH and improve nutrient availability.";
            else if ($value > 8.0) return "Soil is too alkaline. Add organic matter, sulfur, or acidifying fertilizers to lower pH.";
            else return "Soil pH is within optimal range for most plants. Maintain current soil management practices.";
            
        case 'organic_matter':
            if ($value < 1.5) return "Very low organic matter. Add compost, manure, or other organic amendments to improve soil structure and fertility.";
            else if ($value < 3.0) return "Moderate organic matter. Consider adding organic amendments to enhance soil health.";
            else return "Good organic matter content. Continue adding organic materials to maintain soil health.";
            
        case 'nitrogen_content':
            if ($value < 0.1) return "Nitrogen deficiency. Apply nitrogen-rich fertilizers or grow nitrogen-fixing cover crops.";
            else if ($value < 0.15) return "Moderate nitrogen levels. Consider supplemental nitrogen for nitrogen-demanding crops.";
            else return "Good nitrogen content. Monitor and maintain through crop rotation and balanced fertilization.";
            
        case 'gamma_radiation':
            if ($value > 1.0) return "High radiation levels. Consult radiation safety experts immediately and consider relocation.";
            else if ($value > 0.3) return "Elevated radiation levels. Investigate potential sources and consider mitigation measures.";
            else return "Radiation levels are within safe limits. Continue regular monitoring.";
            
        case 'emf_strength':
            if ($value > 2.5) return "High EMF levels. Identify and distance from EMF sources, consider shielding materials.";
            else if ($value > 0.5) return "Moderate EMF levels. Consider reducing exposure time and distance from major sources.";
            else return "EMF levels are low. Maintain awareness of new EMF sources.";
            
        case 'radon_level':
            if ($value > 4.0) return "High radon levels. Install radon mitigation systems and improve ventilation.";
            else if ($value > 2.0) return "Moderate radon levels. Consider radon testing and basic ventilation improvements.";
            else return "Radon levels are within safe limits. Test periodically, especially after renovations.";
            
        case 'day_noise':
            if ($value > 70) return "Loud daytime noise. Use soundproofing, noise barriers, or hearing protection.";
            else if ($value > 55) return "Moderate daytime noise. Consider noise reduction strategies for sensitive activities.";
            else return "Daytime noise levels are acceptable. Maintain current noise management practices.";
            
        case 'night_noise':
            if ($value > 55) return "Loud nighttime noise. Implement soundproofing, use white noise machines, or consider noise ordinances.";
            else if ($value > 45) return "Moderate nighttime noise. Consider basic soundproofing for sleeping areas.";
            else return "Nighttime noise levels are acceptable. Continue monitoring for new noise sources.";
            
        case 'industrial_noise':
            if ($value > 85) return "Dangerous industrial noise levels. Implement engineering controls, administrative controls, and personal hearing protection.";
            else if ($value > 70) return "High industrial noise. Use hearing protection and implement noise reduction strategies.";
            else return "Industrial noise is within acceptable limits. Continue monitoring and maintaining equipment.";
            
        case 'floral_diversity':
            if ($value < 2.5) return "Low biodiversity. Introduce native plant species, create habitat corridors, and reduce monoculture.";
            else if ($value < 4.0) return "Moderate biodiversity. Enhance with additional native species and habitat features.";
            else return "Good floral diversity. Maintain and protect existing ecosystems.";
            
        case 'faunal_density':
            if ($value < 60) return "Low animal population density. Create wildlife corridors, provide food and water sources, and reduce habitat fragmentation.";
            else if ($value < 80) return "Moderate animal density. Enhance habitat features and reduce human disturbances.";
            else return "Good faunal density. Continue habitat protection and monitoring.";
            
        case 'wetland_health':
            if ($value < 50) return "Poor wetland health. Restore hydrology, remove invasive species, and reduce pollution sources.";
            else if ($value < 75) return "Moderate wetland health. Enhance buffer zones and implement targeted restoration.";
            else return "Good wetland health. Maintain protection and monitoring efforts.";
            
        default:
            return "Continue regular monitoring and follow standard environmental practices.";
    }
}

// Function to get overall environmental status
function getOverallStatus($data) {
    $categories = [
        'water' => ['ph_level', 'dissolved_oxygen', 'turbidity'],
        'air' => ['pm25', 'pm10', 'aqi'],
        'soil' => ['soil_ph', 'organic_matter', 'nitrogen_content'],
        'radiation' => ['gamma_radiation', 'emf_strength', 'radon_level'],
        'noise' => ['day_noise', 'night_noise', 'industrial_noise'],
        'biodiversity' => ['floral_diversity', 'faunal_density', 'wetland_health']
    ];
    
    $categoryStatus = [];
    
    foreach ($categories as $category => $params) {
        $goodCount = 0;
        $fairCount = 0;
        $poorCount = 0;
        
        foreach ($params as $param) {
            list($status, $color) = getQualityStatus($data[$param], $param);
            
            if ($color == 'green' || $color == 'lightgreen') {
                $goodCount++;
            } else if ($color == 'orange' || $color == 'yellow') {
                $fairCount++;
            } else {
                $poorCount++;
            }
        }
        
        if ($poorCount > 0) {
            $categoryStatus[$category] = ['Poor', 'red'];
        } else if ($fairCount > 0) {
            $categoryStatus[$category] = ['Fair', 'orange'];
        } else {
            $categoryStatus[$category] = ['Good', 'green'];
        }
    }
    
    return $categoryStatus;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Results - Water Quality Analysis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="water_quality.css">
    <style>
        /* Analysis Results Specific Styles */
        .results-container {
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .results-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .results-header h2 {
            color: #1e4976;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .results-header p {
            color: #666;
            font-size: 16px;
        }
        
        .results-meta {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        
        .meta-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .meta-value {
            font-size: 16px;
            font-weight: 600;
            color: #1e4976;
        }
        
        .results-sections {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }
        
        .result-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .section-title {
            color: #1e4976;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .parameter-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .parameter-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        
        .parameter-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .parameter-item .tooltip {
            visibility: hidden;
            width: 220px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 10px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -110px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .parameter-item .tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }
        
        .parameter-item:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }
        
        .parameter-name {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .parameter-value {
            font-size: 18px;
            font-weight: 600;
            color: #1e4976;
            margin-bottom: 5px;
        }
        
        .parameter-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }
        
        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .action-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .primary-btn {
            background: #1e4976;
            color: white;
        }
        
        .primary-btn:hover {
            background: #0a2647;
            transform: translateY(-2px);
        }
        
        .secondary-btn {
            background: #f8f9fa;
            color: #666;
        }
        
        .secondary-btn:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .results-meta,
            .results-sections {
                grid-template-columns: 1fr;
            }
            
            .parameter-grid {
                grid-template-columns: 1fr;
            }
            
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <a href="water_quality.php" class="brand-text">WaterQual</a>
        </div>
        <div class="nav-links">
            <a href="water_quality.php"><i class="fas fa-home"></i> Home</a>
            <a href="information.php"><i class="fas fa-info-circle"></i> Information</a>
            <a href="help.php" class="active"><i class="fas fa-question-circle"></i> Help Center</a>
            <a href="ebooks.php"><i class="fas fa-book"></i> E-Books</a>
            <a href="blog.php"><i class="fas fa-blog"></i> Blog</a>
        </div>
        <div class="nav-auth">
            <?php if(isset($_SESSION['user_name'])): ?>
                <div class="user-dropdown">
                    <button class="dropbtn">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </button>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            <?php else: ?>
                <a href="login.html" class="login-btn">Login</a>
                <a href="signup.html" class="signup-btn">Sign In</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="results-container">
        <div class="results-header">
            <h2><i class="fas fa-chart-bar"></i> Environmental Analysis Results</h2>
            <p>Detailed analysis of the submitted environmental parameters</p>
        </div>
        
        <div class="results-meta">
            <div class="meta-item">
                <span class="meta-label">Name</span>
                <span class="meta-value"><?php echo htmlspecialchars($data['name']); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Location</span>
                <span class="meta-value"><?php echo htmlspecialchars($data['location']); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">State</span>
                <span class="meta-value"><?php echo htmlspecialchars($data['state']); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Submission Date</span>
                <span class="meta-value"><?php echo date('F j, Y, g:i a', strtotime($data['submission_date'])); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Report ID</span>
                <span class="meta-value">#<?php echo $data['id']; ?></span>
            </div>
        </div>
        
        <div class="results-sections">
            <!-- Water Quality Section -->
            <div class="result-section">
                <h3 class="section-title"><i class="fas fa-tint"></i> Water Quality Parameters</h3>
                <div class="parameter-grid">
                    <?php 
                    $waterParams = [
                        'ph_level' => 'pH Level',
                        'dissolved_oxygen' => 'Dissolved Oxygen (mg/L)',
                        'turbidity' => 'Turbidity (NTU)'
                    ];
                    
                    foreach ($waterParams as $param => $label) {
                        $value = $data[$param];
                        list($status, $color) = getQualityStatus($value, $param);
                        echo '<div class="parameter-item">';
                        echo '<div class="parameter-name">' . $label . '</div>';
                        echo '<div class="parameter-value">' . $value . '</div>';
                        echo '<div class="parameter-status" style="background-color: ' . $color . ';">' . $status . '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Air Quality Section -->
            <div class="result-section">
                <h3 class="section-title"><i class="fas fa-wind"></i> Air Quality Parameters</h3>
                <div class="parameter-grid">
                    <?php 
                    $airParams = [
                        'pm25' => 'PM2.5 (μg/m³)',
                        'pm10' => 'PM10 (μg/m³)',
                        'aqi' => 'Air Quality Index'
                    ];
                    
                    foreach ($airParams as $param => $label) {
                        $value = $data[$param];
                        list($status, $color) = getQualityStatus($value, $param);
                        echo '<div class="parameter-item">';
                        echo '<div class="parameter-name">' . $label . '</div>';
                        echo '<div class="parameter-value">' . $value . '</div>';
                        echo '<div class="parameter-status" style="background-color: ' . $color . ';">' . $status . '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Soil Quality Section -->
            <div class="result-section">
                <h3 class="section-title"><i class="fas fa-seedling"></i> Soil Quality Parameters</h3>
                <div class="parameter-grid">
                    <?php 
                    $soilParams = [
                        'soil_ph' => 'Soil pH',
                        'organic_matter' => 'Organic Matter (%)',
                        'nitrogen_content' => 'Nitrogen Content (%)'
                    ];
                    
                    foreach ($soilParams as $param => $label) {
                        $value = $data[$param];
                        list($status, $color) = getQualityStatus($value, $param);
                        echo '<div class="parameter-item">';
                        echo '<div class="parameter-name">' . $label . '</div>';
                        echo '<div class="parameter-value">' . $value . '</div>';
                        echo '<div class="parameter-status" style="background-color: ' . $color . ';">' . $status . '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Radiation Section -->
            <div class="result-section">
                <h3 class="section-title"><i class="fas fa-radiation"></i> Radiation Parameters</h3>
                <div class="parameter-grid">
                    <?php 
                    $radiationParams = [
                        'gamma_radiation' => 'Gamma Radiation (μSv/h)',
                        'emf_strength' => 'EMF Strength (μT)',
                        'radon_level' => 'Radon Level (pCi/L)'
                    ];
                    
                    foreach ($radiationParams as $param => $label) {
                        $value = $data[$param];
                        list($status, $color) = getQualityStatus($value, $param);
                        echo '<div class="parameter-item">';
                        echo '<div class="parameter-name">' . $label . '</div>';
                        echo '<div class="parameter-value">' . $value . '</div>';
                        echo '<div class="parameter-status" style="background-color: ' . $color . ';">' . $status . '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Noise Section -->
            <div class="result-section">
                <h3 class="section-title"><i class="fas fa-volume-up"></i> Noise Parameters</h3>
                <div class="parameter-grid">
                    <?php 
                    $noiseParams = [
                        'day_noise' => 'Day Noise Level (dB)',
                        'night_noise' => 'Night Noise Level (dB)',
                        'industrial_noise' => 'Industrial Noise Level (dB)'
                    ];
                    
                    foreach ($noiseParams as $param => $label) {
                        $value = $data[$param];
                        list($status, $color) = getQualityStatus($value, $param);
                        echo '<div class="parameter-item">';
                        echo '<div class="parameter-name">' . $label . '</div>';
                        echo '<div class="parameter-value">' . $value . '</div>';
                        echo '<div class="parameter-status" style="background-color: ' . $color . ';">' . $status . '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Environmental Status Section -->
            <div class="result-section">
                <h3 class="section-title"><i class="fas fa-leaf"></i> Environmental Status</h3>
                <div class="parameter-grid">
                    <?php 
                    $envParams = [
                        'floral_diversity' => 'Floral Diversity (1-5)',
                        'faunal_density' => 'Faunal Density (per km²)',
                        'wetland_health' => 'Wetland Health (0-100)'
                    ];
                    
                    foreach ($envParams as $param => $label) {
                        $value = $data[$param];
                        list($status, $color) = getQualityStatus($value, $param);
                        echo '<div class="parameter-item">';
                        echo '<div class="parameter-name">' . $label . '</div>';
                        echo '<div class="parameter-value">' . $value . '</div>';
                        echo '<div class="parameter-status" style="background-color: ' . $color . ';">' . $status . '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add any JavaScript functionality here if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Accordion functionality
            const accordionItems = document.querySelectorAll('.accordion-item');
            
            accordionItems.forEach(item => {
                const header = item.querySelector('.accordion-header');
                
                header.addEventListener('click', () => {
                    // Close all other items
                    accordionItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });
                    
                    // Toggle current item
                    item.classList.toggle('active');
                });
            });
            
            // Open the first accordion by default
            if (accordionItems.length > 0) {
                accordionItems[0].classList.add('active');
            }
            
            // Add hover effect for recommendation items
            const recommendationItems = document.querySelectorAll('.recommendation-item');
            
            recommendationItems.forEach(item => {
                item.addEventListener('mouseenter', () => {
                    item.style.transform = 'translateY(-3px)';
                    item.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
                    item.style.transition = 'all 0.3s ease';
                });
                
                item.addEventListener('mouseleave', () => {
                    item.style.transform = 'translateY(0)';
                    item.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.05)';
                });
            });
        });
    </script>
</body>
</html>

</div>
    </div>
    
    <!-- Add the summary section here, before the actions div -->
    <div class="summary-container">
        <div class="summary-header">
            <h2><i class="fas fa-clipboard-check"></i> Environmental Assessment Summary</h2>
            <p>Overview of environmental conditions and recommendations</p>
        </div>
        
        <div class="overall-status">
            <h3>Overall Status by Category</h3>
            <div class="status-grid">
                <?php
                $categoryStatus = getOverallStatus($data);
                $categoryIcons = [
                    'water' => 'fas fa-tint',
                    'air' => 'fas fa-wind',
                    'soil' => 'fas fa-seedling',
                    'radiation' => 'fas fa-radiation',
                    'noise' => 'fas fa-volume-up',
                    'biodiversity' => 'fas fa-leaf'
                ];
                $categoryNames = [
                    'water' => 'Water Quality',
                    'air' => 'Air Quality',
                    'soil' => 'Soil Quality',
                    'radiation' => 'Radiation Levels',
                    'noise' => 'Noise Levels',
                    'biodiversity' => 'Biodiversity'
                ];
                
                foreach ($categoryStatus as $category => $status) {
                    echo '<div class="status-item">';
                    echo '<div class="status-icon"><i class="' . $categoryIcons[$category] . '"></i></div>';
                    echo '<div class="status-details">';
                    echo '<div class="status-name">' . $categoryNames[$category] . '</div>';
                    echo '<div class="status-indicator" style="background-color: ' . $status[1] . ';">' . $status[0] . '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        
        <div class="recommendations">
            <h3>Detailed Recommendations</h3>
            <div class="accordion">
                <?php
                $sections = [
                    'water' => [
                        'title' => 'Water Quality Recommendations',
                        'icon' => 'fas fa-tint',
                        'params' => [
                            'ph_level' => 'pH Level',
                            'dissolved_oxygen' => 'Dissolved Oxygen',
                            'turbidity' => 'Turbidity'
                        ]
                    ],
                    'air' => [
                        'title' => 'Air Quality Recommendations',
                        'icon' => 'fas fa-wind',
                        'params' => [
                            'pm25' => 'PM2.5',
                            'pm10' => 'PM10',
                            'aqi' => 'Air Quality Index'
                        ]
                    ],
                    'soil' => [
                        'title' => 'Soil Quality Recommendations',
                        'icon' => 'fas fa-seedling',
                        'params' => [
                            'soil_ph' => 'Soil pH',
                            'organic_matter' => 'Organic Matter',
                            'nitrogen_content' => 'Nitrogen Content'
                        ]
                    ],
                    'radiation' => [
                        'title' => 'Radiation Safety Recommendations',
                        'icon' => 'fas fa-radiation',
                        'params' => [
                            'gamma_radiation' => 'Gamma Radiation',
                            'emf_strength' => 'EMF Strength',
                            'radon_level' => 'Radon Level'
                        ]
                    ],
                    'noise' => [
                        'title' => 'Noise Management Recommendations',
                        'icon' => 'fas fa-volume-up',
                        'params' => [
                            'day_noise' => 'Day Noise Level',
                            'night_noise' => 'Night Noise Level',
                            'industrial_noise' => 'Industrial Noise'
                        ]
                    ],
                    'biodiversity' => [
                        'title' => 'Biodiversity Recommendations',
                        'icon' => 'fas fa-leaf',
                        'params' => [
                            'floral_diversity' => 'Floral Diversity',
                            'faunal_density' => 'Faunal Density',
                            'wetland_health' => 'Wetland Health'
                        ]
                    ]
                ];
                
                foreach ($sections as $sectionKey => $section) {
                    echo '<div class="accordion-item ' . ($categoryStatus[$sectionKey][1] == 'red' ? 'active' : '') . '">';
                    echo '<div class="accordion-header">';
                    echo '<i class="' . $section['icon'] . '"></i> ' . $section['title'];
                    echo '<span class="status-indicator" style="background-color: ' . $categoryStatus[$sectionKey][1] . ';">' . $categoryStatus[$sectionKey][0] . '</span>';
                    echo '<i class="fas fa-chevron-down"></i>';
                    echo '</div>';
                    echo '<div class="accordion-content">';
                    
                    foreach ($section['params'] as $param => $label) {
                        $value = $data[$param];
                        list($status, $color) = getQualityStatus($value, $param);
                        $recommendation = getRecommendation($value, $param);
                        
                        echo '<div class="recommendation-item">';
                        echo '<div class="recommendation-header">';
                        echo '<span class="param-name">' . $label . ': ' . $value . '</span>';
                        echo '<span class="param-status" style="background-color: ' . $color . ';">' . $status . '</span>';
                        echo '</div>';
                        echo '<div class="recommendation-text">' . $recommendation . '</div>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        
        <div class="summary-conclusion">
            <h3>Conclusion</h3>
            <p>This environmental assessment provides a snapshot of current conditions at <?php echo htmlspecialchars($data['location']); ?>, <?php echo htmlspecialchars($data['state']); ?>. The recommendations above are general guidelines based on standard environmental practices. For specific concerns, please consult with environmental specialists or relevant regulatory authorities.</p>
            <p>Regular monitoring and proactive management are key to maintaining and improving environmental quality. We recommend reassessing these parameters periodically to track changes and evaluate the effectiveness of any implemented measures.</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="help.html" class="action-btn">
                <i class="fas fa-arrow-left"></i> Back to Form
            </a>
            <a href="#" class="action-btn primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </a>
            <a href="water_quality.html" class="action-btn">
                <i class="fas fa-home"></i> Go to Home
            </a>
        </div>
    </div>
                
    <style>
    .summary-container {
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .summary-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .summary-header h2 {
            color: #1e4976;
            font-size: 28px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .summary-header p {
            color: #666;
            font-size: 16px;
        }
        
        /* Overall Status Styles */
        .overall-status {
            margin-bottom: 40px;
        }
        
        .overall-status h3 {
            color: #1e4976;
            font-size: 22px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .status-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }
        
        .status-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .status-icon {
            font-size: 24px;
            color: #1e4976;
            width: 50px;
            height: 50px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-details {
            flex: 1;
        }
        
        .status-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .status-indicator {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            color: white;
        }
        
        /* Recommendations Styles */
        .recommendations {
            margin-bottom: 40px;
        }
        
        .recommendations h3 {
            color: #1e4976;
            font-size: 22px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .accordion {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .accordion-item {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .accordion-header {
            background: #f8f9fa;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            font-weight: 600;
            color: #333;
        }
        
        .accordion-header i:first-child {
            margin-right: 10px;
            color: #1e4976;
        }
        
        .accordion-content {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        
        .accordion-item.active .accordion-content {
            padding: 20px;
            max-height: 1000px;
        }
        
        .recommendation-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .recommendation-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .recommendation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .param-name {
            font-weight: 600;
            color: #333;
        }
        
        .param-status {
            padding: 3px 10px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
        }
        
        .recommendation-text {
            color: #555;
            line-height: 1.6;
            font-size: 15px;
        }
        
        /* Summary Conclusion Styles */
        .summary-conclusion {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .summary-conclusion h3 {
            color: #1e4976;
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        .summary-conclusion p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .summary-conclusion p:last-child {
            margin-bottom: 0;
        }
        
        /* Action Buttons Styles */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        
        .action-btn {
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            color: #1e4976;
            background: white;
            border: 2px solid #1e4976;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .action-btn.primary {
            background: #1e4976;
            color: white;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .status-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .status-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Accordion functionality
            const accordionHeaders = document.querySelectorAll('.accordion-header');
            
            accordionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const accordionItem = this.parentElement;
                    accordionItem.classList.toggle('active');
                });
            });
            
            // Open accordion items with red status by default
            const redItems = document.querySelectorAll('.accordion-item[class*="active"]');
            redItems.forEach(item => {
                item.querySelector('.accordion-content').style.maxHeight = 
                    item.querySelector('.accordion-content').scrollHeight + 'px';
                item.querySelector('.accordion-content').style.padding = '20px';
            });
        });
    </script>

<footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="water_quality.php"><i class="fas fa-home"></i> Home</a>
                <a href="information.php"><i class="fas fa-info-circle"></i> Information</a>
                <a href="help.php"><i class="fas fa-question-circle"></i> Help Center</a>
                <a href="ebooks.php"><i class="fas fa-book"></i> E-Books</a>
                <a href="blog.php"><i class="fas fa-blog"></i> Blog</a>
            </div>

            <div class="footer-section">
                <h3>Resources</h3>
                <a href="information.php#water-quality"><i class="fas fa-tint"></i> Water Quality Data</a>
                <a href="information.php#air-quality"><i class="fas fa-chart-bar"></i> Air Quality Data</a>
                <a href="information.php#soil-quality"><i class="fas fa-flask"></i> Soil Analysis</a>
                <a href="information.php#radiation-quality"><i class="fas fa-radiation"></i> Radiation Data</a>
                <a href="information.php#environmental-status"><i class="fas fa-leaf"></i> Environmental Status</a>
                <a href="information.php#noise-quality"><i class="fas fa-volume-up"></i> Noise Pollution</a>
                <a href="information.php#lakes-analysis"><i class="fas fa-water"></i> 20 Main River Water Quality</a>
            </div>

            <div class="footer-section">
                <h3>Contact Us</h3>
                <a href="mailto:support@waterqual.com"><i class="fas fa-envelope"></i> support@waterqual.com</a>
                <a href="tel:+91-123-456-7890"><i class="fas fa-phone"></i> +91 123-456-7890</a>
                <a href="#"><i class="fas fa-map-marker-alt"></i> New Delhi, India</a>
                <p><i class="far fa-clock"></i> Mon - Fri, 9:00 - 18:00</p>
            </div>

            <div class="footer-section">
                <h3>Select Language</h3>
                <select class="language-select">
                    <option value="en">English</option>
                    <option value="hi">Hindi</option>
                    <option value="pu">Punjabi</option>
                </select>

                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://x.com/"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                </div>

                <h3>Mobile App</h3>
                <div class="app-buttons">
                    <a href="https://play.google.com/" class="app-btn">
                        <i class="fas fa-play"></i> Play Store
                    </a>
                    <a href="https://www.apple.com/" class="app-btn">
                        <i class="fab fa-apple"></i> App Store
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="copyright">All rights reserved</p>
            <div class="footer-links">
                <a href="privacy.php"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
                <a href="services.php"><i class="fas fa-file-contract"></i> Terms of Service</a>
                <a href="sitemap.php"><i class="fas fa-sitemap"></i> Sitemap</a>
            </div>
        </div>
    </footer>

</body>
</html>