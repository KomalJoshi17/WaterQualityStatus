document.addEventListener('DOMContentLoaded', function() {
    // Fetch water quality data
    fetch('fetch_water_quality.php')
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                throw new Error(response.error || 'Failed to fetch water data');
            }
            if (response.data.water && response.data.water.length > 0) {
                initWaterQualityChart(response.data.water);
            }
        })
        .catch(error => handleError(error, 'waterChartContainer'));

    // Update air quality fetch
    fetch('fetch_air_quality.php')
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                throw new Error(response.error || 'Failed to fetch air data');
            }
            if (response.data && response.data.length > 0) {
                initAirQualityChart(response.data);
            } else {
                throw new Error('No air quality data available');
            }
        })
        .catch(error => handleError(error, 'airChartContainer'));
    // Update soil quality fetch
    fetch('fetch_soil_quality.php')
        .then(response => {
            console.log('Raw response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Parsed data:', data);
            if (!data.success) {
                throw new Error(data.error || 'Failed to fetch soil data');
            }
            if (data.data && data.data.length > 0) {
                updateSoilParameters(data.data);
                initSoilQualityPieChart(data.data);
            } else {
                throw new Error('No soil quality data available');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            handleError(error, 'soilChartContainer');
        });

    function initSoilQualityPieChart(data) {
        const ctx = document.getElementById('soilQualityPieChart').getContext('2d');
        
        // Prepare data for radar chart
        const states = data.map(item => item.state);
        const soilPH = data.map(item => item.soil_ph);
        const organicMatter = data.map(item => item.organic_matter);
        const nitrogenContent = data.map(item => item.nitrogen_matter);

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: states,
                datasets: [{
                    label: 'Soil pH',
                    data: soilPH,
                    borderColor: 'rgba(75, 192, 75, 1)',
                    backgroundColor: 'rgba(75, 192, 75, 0.2)',
                    borderWidth: 2,
                    pointRadius: 3
                }, {
                    label: 'Organic Matter (%)',
                    data: organicMatter,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    pointRadius: 3
                }, {
                    label: 'Nitrogen Content (%)',
                    data: nitrogenContent,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderWidth: 2,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        min: 0,
                        max: 9,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        angleLines: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        pointLabels: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            },
                            usePointStyle: true
                        }
                    },
                    title: {
                        display: true,
                        text: 'Soil Quality Parameters by State',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: 20
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
    }
});

// Use the same handleError function for both charts
function handleError(error, containerId) {
    console.error('Error:', error);
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `
            <div style="text-align: center; color: red; padding: 20px;">
                Error loading data: ${error.message}
            </div>`;
    }
}

function initAirQualityChart(data) {
    const ctx = document.getElementById('airQualityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => item.city_name),
            datasets: [{
                label: 'PM2.5 (µg/m³)',
                data: data.map(item => item.pm2_5),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }, {
                label: 'PM10 (µg/m³)',
                data: data.map(item => item.pm10),
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }, {
                label: 'AQI',
                data: data.map(item => item.aqi),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const city = data[context.parsed.dataIndex]?.city_name || '';
                            const value = context.parsed.y.toFixed(2);
                            return `${label} (${city}): ${value}`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Air Quality Parameters Across Cities',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 20
                }
            }
        }
    });
}

function initWaterQualityChart(data) {
    const ctx = document.getElementById('waterQualityChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(item => item.state),
            datasets: [{
                label: 'pH Level',
                data: data.map(item => item.pH_level),
                backgroundColor: '#B3E0FF',  // Light pastel blue
                borderColor: '#66B2FF',
                borderWidth: 1
            }, {
                label: 'Dissolved Oxygen (mg/L)',
                data: data.map(item => item.dissolved_oxygen),
                backgroundColor: '#99CCFF',  // Slightly darker pastel blue
                borderColor: '#3399FF',
                borderWidth: 1
            }, {
                label: 'Turbidity (NTU)',
                data: data.map(item => item.turbidity),
                backgroundColor: '#CCE5FF',  // Very light pastel blue
                borderColor: '#0066CC',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'category',
                    title: {
                        display: true,
                        text: 'States'
                    },
                    ticks: {
                        callback: function(value) {
                            return data[value].state;
                        },
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Water Quality Parameters Across Indian States'
                }
            }
        }
    });
    }

function updateSoilParameters(data) {
    // Calculate averages
    const avgPH = data.reduce((sum, item) => sum + item.soil_ph, 0) / data.length;
    const avgOM = data.reduce((sum, item) => sum + item.organic_matter, 0) / data.length;
    const avgNitrogen = data.reduce((sum, item) => sum + item.nitrogen_matter, 0) / data.length;

    // Update the display values
    document.querySelector('.parameter-card:nth-child(1) p').textContent = 
        `Current Average: ${avgPH.toFixed(1)} (Optimal: 6.0-7.5)`;
    document.querySelector('.parameter-card:nth-child(2) p').textContent = 
        `Current Average: ${avgOM.toFixed(1)}% (Ideal: > 3%)`;
    document.querySelector('.parameter-card:nth-child(3) p').textContent = 
        `Current Average: ${avgNitrogen.toFixed(2)}% (Required: 0.15-0.4%)`;
}

// Add this after your existing fetch calls
fetch('fetch_environmental_stats.php')
    .then(response => response.json())
    .then(response => {
        if (!response.success) {
            throw new Error(response.error || 'Failed to fetch environmental data');
        }
        if (response.data && response.data.length > 0) {
            initEnvironmentalStatsChart(response.data);
        } else {
            throw new Error('No environmental data available');
        }
    })
    .catch(error => handleError(error, 'environmentalChartContainer'));

function initEnvironmentalStatsChart(data) {
    const ctx = document.getElementById('environmentalStatsChart').getContext('2d');
    new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Floral Diversity',
                data: data.map((item, index) => ({
                    x: item.state,  // Changed from data.indexOf(item) to item.state
                    y: item.Floral_Diversity
                })),
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                pointRadius: 8,
                pointHoverRadius: 10
            }, {
                label: 'Faunal Density',
                data: data.map((item, index) => ({
                    x: item.state,  // Changed from data.indexOf(item) to item.state
                    y: item.Faunal_Density
                })),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 8,
                pointHoverRadius: 10
            }, {
                label: 'Wetland Health',
                data: data.map((item, index) => ({
                    x: item.state,  // Changed from data.indexOf(item) to item.state
                    y: item.Wetland_Health
                })),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                pointRadius: 8,
                pointHoverRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'category',  // Added type category
                    title: {
                        display: true,
                        text: 'States'
                    },
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Environmental Parameters'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const state = data[context.parsed.x]?.state || '';
                            const value = context.parsed.y.toFixed(2);
                            return `${label} (${state}): ${value}`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Environmental Statistics Distribution',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 20
                }
            }
        }
    });
}

function updateSoilParameters(data) {
    // Calculate averages
    const avgPH = data.reduce((sum, item) => sum + item.soil_ph, 0) / data.length;
    const avgOM = data.reduce((sum, item) => sum + item.organic_matter, 0) / data.length;
    const avgNitrogen = data.reduce((sum, item) => sum + item.nitrogen_matter, 0) / data.length;

    // Update the display values
    document.querySelector('.parameter-card:nth-child(1) p').textContent = 
        `Current Average: ${avgPH.toFixed(1)} (Optimal: 6.0-7.5)`;
    document.querySelector('.parameter-card:nth-child(2) p').textContent = 
        `Current Average: ${avgOM.toFixed(1)}% (Ideal: > 3%)`;
    document.querySelector('.parameter-card:nth-child(3) p').textContent = 
        `Current Average: ${avgNitrogen.toFixed(2)}% (Required: 0.15-0.4%)`;
}

// Add this after your existing fetch calls
fetch('fetch_radiation_data.php')
    .then(response => response.json())
    .then(response => {
        if (!response.success) {
            throw new Error(response.error || 'Failed to fetch radiation data');
        }
        if (response.data && response.data.length > 0) {
            initRadiationChart(response.data);
        } else {
            throw new Error('No radiation data available');
        }
    })
    .catch(error => handleError(error, 'radiationChartContainer'));

function initRadiationChart(data) {
    const ctx = document.getElementById('radiationChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => item.state),
            datasets: [{
                label: 'Gamma Radiation',
                data: data.map(item => item.gamma_radiation),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: false
            }, {
                label: 'EMF Strength',
                data: data.map(item => item.emf_strength),
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: false
            }, {
                label: 'Radon Level',
                data: data.map(item => item.radon_level),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'States'
                    },
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Measurement Values'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const state = data[context.parsed.x]?.state || '';
                            const value = context.parsed.y.toFixed(2);
                            return `${label} (${state}): ${value}`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Radiation Parameters Distribution',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 20
                }
            }
        }
    });
}

// Add this to your existing fetch calls
fetch('fetch_noise_data.php')
    .then(response => response.json())
    .then(response => {
        if (!response.success) {
            throw new Error(response.error || 'Failed to fetch noise data');
        }
        if (response.data && response.data.length > 0) {
            initNoiseCharts(response.data);
        } else {
            throw new Error('No noise data available');
        }
    })
    .catch(error => handleError(error, 'noiseChartContainer'));

function initNoiseCharts(data) {
    // Line Chart
    const ctxLine = document.getElementById('noiseChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: data.map(item => item.state),
            datasets: [{
                label: 'Day Noise Level (dB)',
                data: data.map(item => parseFloat(item.decibel_day)),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }, {
                label: 'Night Noise Level (dB)',
                data: data.map(item => parseFloat(item.decibel_night)),
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }, {
                label: 'Industrial Noise (dB)',
                data: data.map(item => parseFloat(item.peak_noise_industrial)),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Noise Levels Across States'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'States'
                    },
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Decibel Level (dB)'
                    }
                }
            }
        }
    });

    // Donut Chart
    const ctxDonut = document.getElementById('noiseDonutChart').getContext('2d');
    const avgDayNoise = data.reduce((sum, item) => sum + parseFloat(item.decibel_day), 0) / data.length;
    const avgNightNoise = data.reduce((sum, item) => sum + parseFloat(item.decibel_night), 0) / data.length;
    const avgIndustrialNoise = data.reduce((sum, item) => sum + parseFloat(item.peak_noise_industrial), 0) / data.length;

    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Day Noise', 'Night Noise', 'Industrial Noise'],
            datasets: [{
                data: [avgDayNoise, avgNightNoise, avgIndustrialNoise],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Average Noise Levels Distribution'
                }
            }
        }
    });

    // Update parameter cards
    const cards = document.querySelectorAll('.parameter-card');
    cards[0].querySelector('p').textContent = `Current Average: ${avgDayNoise.toFixed(1)} dB (Standard: ≤ 55 dB)`;
    cards[1].querySelector('p').textContent = `Current Average: ${avgNightNoise.toFixed(1)} dB (Standard: ≤ 45 dB)`;
    cards[2].querySelector('p').textContent = `Current Average: ${avgIndustrialNoise.toFixed(1)} dB (Limit: ≤ 75 dB)`;
}
