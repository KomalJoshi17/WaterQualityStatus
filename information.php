<?php include 'header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="water_quality.css">
    <link rel="stylesheet" href="information.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container">
        <!-- Water Quality Section -->
        <div class="info-header" id="water-quality">
            <h1 class="font-bold">Water Quality Parameters</h1>
            <p>Analysis of key water quality indicators across all Indian states.</p>
        </div>

        <div class="parameters-grid">
            <!-- Water parameter cards -->
            <div class="parameter-card">
                <h3>pH Level</h3>
                <p>Measures water acidity/alkalinity. Ideal range: 6.5-8.5</p>
                <div class="parameter-icon">
                    <i class="fas fa-vial"></i>
                </div>
            </div>

            <div class="parameter-card">
                <h3>Dissolved Oxygen</h3>
                <p>Essential for aquatic life. Minimum required: 5 mg/L</p>
                <div class="parameter-icon">
                    <i class="fas fa-wind"></i>
                </div>
            </div>

            <div class="parameter-card">
                <h3>Turbidity</h3>
                <p>Water clarity measurement. Standard: < 5 NTU</p>
                <div class="parameter-icon">
                    <i class="fas fa-water"></i>
                </div>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="waterQualityChart"></canvas>
        </div>

        <!-- Air Quality Section -->
        <div class="info-header" id="air-quality">
            <h1 class="font-bold">Air Quality Parameters</h1>
            <p>Comprehensive air quality metrics for major cities in each state.</p>
        </div>

        <div class="parameters-grid">
            <!-- Air parameter cards -->
            <div class="parameter-card">
                <h3>PM2.5</h3>
                <p>Fine particulate matter. Safe level: < 50 µg/m³</p>
                <div class="parameter-icon">
                    <i class="fas fa-smog"></i>
                </div>
            </div>

            <div class="parameter-card">
                <h3>PM10</h3>
                <p>Coarse particulate matter. Safe level: < 100 µg/m³</p>
                <div class="parameter-icon">
                    <i class="fas fa-cloud"></i>
                </div>
            </div>

            <div class="parameter-card">
                <h3>AQI</h3>
                <p>Overall Air Quality Index. Good: 0-50</p>
                <div class="parameter-icon">
                    <i class="fas fa-wind"></i>
                </div>
            </div>
        </div>

        <!-- Replace the air quality chart container div with this -->
        <div id="airChartContainer" class="chart-container">
            <canvas id="airQualityChart"></canvas>
        </div>
        
        <!-- <div class="info-content-sections">
            <div class="info-content-box">
                <div class="content-details"> -->
                        <!-- After Air Quality Chart -->
                        <div class="info-header" id="soil-quality">
                            <h1 class="font-bold">Soil Quality Parameters</h1>
                            <p>Soil health indicators across different regions.</p>
                        </div>

                        <div class="parameters-grid">
                            <div class="parameter-card">
                                <h3>Soil pH</h3>
                                <p>Optimal range: 6.0-7.5</p>
                                <div class="parameter-icon">
                                    <i class="fas fa-flask"></i>
                                </div>
                            </div>

                            <div class="parameter-card">
                                <h3>Organic Matter</h3>
                                <p>Ideal: > 3%</p>
                                <div class="parameter-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                            </div>

                            <div class="parameter-card">
                                <h3>Nitrogen Content</h3>
                                <p>Required: 0.15-0.4%</p>
                                <div class="parameter-icon">
                                    <i class="fas fa-atom"></i>
                                </div>
                            </div>
                        </div>
                    <!-- </div> -->
                <!-- </div>
            </div>
        </div> -->
        <!-- Add this after your soil parameters section -->
        <div class="chart-container" id="soilChartContainer">
            <canvas id="soilQualityPieChart"></canvas>
        </div>

        <!-- <div class="info-content-sections">
            <div class="info-content-box">
                <div class="content-details"> -->
                    <div class="info-header" id="radiation-quality">
                        <h1 class="font-bold">Radiation Parameters</h1>
                        <p>Radiation levels and electromagnetic field measurements across states.</p>
                    </div>

                    <div class="parameters-grid">
                        <div class="parameter-card">
                            <h3>Gamma Radiation</h3>
                            <p>Safe level: < 0.5 μSv/h</p>
                            <div class="parameter-icon">
                                <i class="fas fa-radiation"></i>
                            </div>
                        </div>

                        <div class="parameter-card">
                            <h3>EMF Strength</h3>
                            <p>Safe level: < 0.8 μT</p>
                            <div class="parameter-icon">
                                <i class="fas fa-broadcast-tower"></i>
                            </div>
                        </div>

                        <div class="parameter-card">
                            <h3>Radon Level</h3>
                            <p>Safe level: < 100 Bq/m³</p>
                            <div class="parameter-icon">
                                <i class="fas fa-atom"></i>
                            </div>
                        </div>
                    </div>
                <!-- </div>
            </div>
        </div> -->

        <div class="chart-container" id="radiationChartContainer">
            <canvas id="radiationChart"></canvas>
        </div>

        <!-- Environmental Statistics Section -->
        <!-- Add this before the closing </div> of the container -->
        <!-- Replace the existing noise parameter cards section with this -->
        <!-- <div class="info-content-sections">
            <div class="info-content-box">
                <div class="content-details"> -->
                    <div class="info-header" id="noise-quality">
                        <h1 class="font-bold">Noise Quality Parameters</h1>
                        <p>Noise level measurements across different states.</p>
                    </div>

                    <div class="parameters-grid">
                        <div class="parameter-card">
                            <h3>Day Noise Level</h3>
                            <p>Safe level: ≤ 55 dB</p>
                            <div class="parameter-icon">
                                <i class="fas fa-sun"></i>
                            </div>
                        </div>

                        <div class="parameter-card">
                            <h3>Night Noise Level</h3>
                            <p>Safe level: ≤ 45 dB</p>
                            <div class="parameter-icon">
                                <i class="fas fa-moon"></i>
                            </div>
                        </div>

                        <div class="parameter-card">
                            <h3>Industrial Noise</h3>
                            <p>Safe level: ≤ 75 dB</p>
                            <div class="parameter-icon">
                                <i class="fas fa-industry"></i>
                            </div>
                        </div>
                    </div>
                <!-- </div>
            </div>
        </div> -->

        <!-- After the noise line chart container -->
        <div class="chart-container" id="noiseChartContainer">
            <canvas id="noiseChart"></canvas>
        </div>

        <div class="chart-container" id="noiseDonutContainer">
            <canvas id="noiseDonutChart"></canvas>
        </div>

        <!-- Existing environmental statistics section -->
        <!-- <div class="info-content-sections">
            <div class="info-content-box">
                <div class="content-details"> -->
                    <div class="info-header" id="environmental-status">
                        <h1 class="font-bold">Environmental Statistics</h1>
                        <p>Biodiversity and ecosystem health indicators across states.</p>
                    </div>

                    <div class="parameters-grid">
                        <div class="parameter-card">
                            <h3>Floral Diversity</h3>
                            <p>Optimal range: 3.5-4.5 index</p>
                            <div class="parameter-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                        </div>

                        <div class="parameter-card">
                            <h3>Faunal Density</h3>
                            <p>Healthy range: 60-80 per km²</p>
                            <div class="parameter-icon">
                                <i class="fas fa-paw"></i>
                            </div>
                        </div>

                        <div class="parameter-card">
                            <h3>Wetland Health</h3>
                            <p>Good condition: > 75%</p>
                            <div class="parameter-icon">
                                <i class="fas fa-water"></i>
                            </div>
                        </div>
                    </div>
                <!-- </div>
            </div>
        </div> -->

        <div class="chart-container" id="environmentalChartContainer">
            <canvas id="environmentalStatsChart"></canvas>
        </div>

        <!-- Lakes Analysis Section -->
<!--          
        <div class="info-content-sections">
            <div class="info-content-box">
                <div class="content-details"> -->
                    <div class="info-header" id="lakes-analysis">
                        <h1 class="font-bold">Water Quality Analysis of 20 Major Lakes Worldwide</h1>
                        <p>Comprehensive analysis of water quality parameters across major lakes.</p>
                    </div>
                <!-- </div>
            </div>
        </div> -->

        <div class="min-h-screen w-full p-10 flex flex-wrap ">
            <!-- Rest of the lakes grid code -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 justify-center gap-20">
          
          <script>
            const lakes = [
      { name: "Caspian Sea", location: "Eurasia", waterQuality: "Moderate", ph: 8.2, img: "https://imgs.search.brave.com/6B2rnxvbGks9NxOfAGzahvWQskLkwqhJuzbJ1pmP6e0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvNDY4/MTQxNTM3L3Bob3Rv/L2Nhc3BpYW4tc2Vh/LXdpdGgtc3RvbmVz/LmpwZz9zPTYxMng2/MTImdz0wJms9MjAm/Yz1kX01PcmxYNEUy/YWFuUjcxN3RDNXdW/V3NmYllZSHlXRXBk/VXR0QnFXWXhjPQ", link: "https://en.wikipedia.org/wiki/Caspian_Sea" },
      { name: "Great Bear Lake", location: "Canada", waterQuality: "Excellent", ph: 7.5, img: "https://imgs.search.brave.com/7sHugfuN4-7ed_LFIK5GanxoVXTc9rtAsH8KEOxPZc0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mYWN0/cy5uZXQvd3AtY29u/dGVudC91cGxvYWRz/LzIwMjMvMDkvMTQt/YXN0b25pc2hpbmct/ZmFjdHMtYWJvdXQt/Z3JlYXQtYmVhci1s/YWtlLTE2OTQyMzE2/MjEuanBn", link: "https://en.wikipedia.org/wiki/Great_Bear_Lake" },
      { name: "Lake Baikal", location: "Russia", waterQuality: "Pristine", ph: 7.2, img: "https://imgs.search.brave.com/MMi3yjwrpAIRvFv-BI5lOY41APSKkFtt9sBIWbtTczM/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/bGFrZWJhaWthbC5v/cmcvd3AtY29udGVu/dC91cGxvYWRzLzIw/MjEvMDUvbGFrZS1i/YWlrYWwtcnVzc2lh/LTEwMjR4Njg1Lmpw/Zw", link: "https://en.wikipedia.org/wiki/Lake_Baikal" },
      { name: "Lake Erie", location: "USA/Canada", waterQuality: "Polluted", ph: 7.9, img: "https://imgs.search.brave.com/NqWOMxzN2dDTPe11jLFqYi1DJKhGNRVXqK1Y5OB6RJo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvMTQ5/MDg4MTcyMC9waG90/by9lcmllLTEuanBn/P3M9NjEyeDYxMiZ3/PTAmaz0yMCZjPVkt/NVZkSk14UkhQTUpx/bzRqcG85UUNIUW9R/dExid0tvZzRLZW5E/Q1FIblk9", link: "https://en.wikipedia.org/wiki/Lake_Erie" },
      { name: "Lake Huron", location: "USA/Canada", waterQuality: "Excellent", ph: 7.8, img: "https://imgs.search.brave.com/X8AgZIG5NQJrSCMdWiCPTGTv_iTLptzEGf2EZ1d_R7Q/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzAzLzk5Lzk4Lzk2/LzM2MF9GXzM5OTk4/OTY1MV80ZWdCNHo2/VXZ3N3ZzQjlDbzdI/dVJVYzJrZ3V4TXZJ/Ry5qcGc", link: "https://en.wikipedia.org/wiki/Lake_Huron" },
      { name: "Lake Michigan", location: "USA", waterQuality: "Good", ph: 8.1, img: "https://imgs.search.brave.com/Smn9D1ybs69DzBYwUK7uRQzIoKMdt6wyb4CruUni0Og/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzA5LzE5Lzg2LzA3/LzM2MF9GXzkxOTg2/MDcxNl9sRG1keW1t/ckljcnB4ZDFDeWVZ/U1Yzc0RabDNvZFl1/Ri5qcGc", link: "https://en.wikipedia.org/wiki/Lake_Michigan" },
      { name: "Lake Superior", location: "North America", waterQuality: "Excellent", ph: 7.5, img: "https://imgs.search.brave.com/RwUAPFpv1X0kKpbowtJNogBMTtBhoeWcQoad8U6Ccjo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvMTI0/ODY3MDUyMi9waG90/by9mcm96ZW4tbGFr/ZS1zdXBlcmlvci5q/cGc_cz02MTJ4NjEy/Jnc9MCZrPTIwJmM9/bFEwZFM4aTVzYWh0/aE8xbjdLT1N5eTIw/VDVaVUR3Umpta1FG/LWR6U2RLMD0", link: "https://en.wikipedia.org/wiki/Lake_Superior" },
      { name: "Lake Tanganyika", location: "Africa", waterQuality: "Good", ph: 8.3, img: "https://imgs.search.brave.com/Rgrv_NzwGWE4JsEAZ6_9TERLfam2-w2GB9quGDACkcE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvMTI0/MDYzMjYzMC9waG90/by9maXNoZXJtZW4t/bGVhdmUtZm9yLWEt/ZmlzaGluZy1leHBp/ZGl0aW9uLWF0LWxh/a2UtdGFuZ2FueWlr/YS1pbi1idWp1bWJ1/cmEtYnVydW5kaS1v/bi1tYXJjaC0xNi5q/cGc_cz02MTJ4NjEy/Jnc9MCZrPTIwJmM9/aGRMR1hweXMyWE1T/dVJzZGRwM0VPck8y/SkszMWZBVEh4SFFV/MGR5ZUQ1TT0", link: "https://en.wikipedia.org/wiki/Lake_Tanganyika" },
      { name: "Lake Titicaca", location: "Peru/Bolivia", waterQuality: "Fair", ph: 8.4, img: "https://imgs.search.brave.com/tQ7jWvZ39sUjoJ1eLxapL3_QlLGB7EcImI0NCDIA6s4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvMTQw/MTE0MzcxMS9waG90/by9sYW5kc2NhcGUt/b2YtbGFrZS10aXRp/Y2FjYS1pbi1wZXJ1/LmpwZz9zPTYxMng2/MTImdz0wJms9MjAm/Yz1rWl94SlQ1VDBW/ZUFTNFRiS0haOE43/VHdNYkJfdXdfLWFP/SzJkdTdXdjBzPQ", link: "https://en.wikipedia.org/wiki/Lake_Titicaca" },
      { name: "Lake Victoria", location: "Africa", waterQuality: "Good", ph: 7.3, img: "https://imgs.search.brave.com/yIcIH__RsM9c5a27U6kJl3of1uryXrvXtweKicY18fY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvMTA1/MzIwMjM3Mi9waG90/by9wZWFjZWZ1bC1p/bi1sYWtlLXZpY3Rv/cmlhLmpwZz9zPTYx/Mng2MTImdz0wJms9/MjAmYz1WUHVaMG9s/RmZFNmZTM1ZsbjJG/RDBTR3lGcm82aWda/dzhBZnBWYWtQMmpV/PQ", link: "https://en.wikipedia.org/wiki/Lake_Victoria" },
      { name: "Lake Geneva", location: "Switzerland/France", waterQuality: "Excellent", ph: 7.9, img: "https://imgs.search.brave.com/t8TNWQbrSgP92Yk3NqTU-c56tGJ9JS_9NRe7Boif3UY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvMTY0/NzY3NTk0L3Bob3Rv/L3RpdGljYWNhLmpw/Zz9zPTYxMng2MTIm/dz0wJms9MjAmYz1z/dXZsbDNYRkEtM1Nu/V2h1d0xoSFh5aXVw/SjZHQWRReW1vNFMt/empabGZnPQ", link: "https://en.wikipedia.org/wiki/Lake_Geneva" },
      { name: "Lake Champlain", location: "USA/Canada", waterQuality: "Good", ph: 7.6, img: "https://imgs.search.brave.com/gPacaJX1JXKA4c06qCsqqWNcHK54geZBxRxOaJohQac/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzA0LzQyLzgyLzYz/LzM2MF9GXzQ0Mjgy/NjMwM19Cd2x6a1k3/dlduT0lxektwcENw/NFptM2VYZ2VIR1Bu/Mi5qcGc", link: "https://en.wikipedia.org/wiki/Lake_Champlain" },
      { name: "Lake Chad", location: "Africa", waterQuality: "Poor", ph: 7.5, img: "https://imgs.search.brave.com/JnkwadjMnpL-eP_ErV5Mjud3_-99PCoyN96MrUEacsE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/YnJpdGFubmljYS5j/b20vNDUvMTkwNjQ1/LTEzMS02NDA4QkM4/MC9MYWtlLVlzeWst/Ym9keS13YXRlci1L/eXJneXpzdGFuLmpw/Zz93PTIwMCZoPTIw/MCZjPWNyb3A", link: "https://en.wikipedia.org/wiki/Lake_Chad" },
      { name: "Lake Winnipeg", location: "Canada", waterQuality: "Moderate", ph: 7.8, img: "https://imgs.search.brave.com/jrF5svuEWKgLQMWcnNSOKqoXPMixVKjKPcZWp-AGyaI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvMTU3/NTg4MjY4L3Bob3Rv/L2xha2Utd2lubmlw/ZWctbWFuaXRvYmEu/anBnP3M9NjEyeDYx/MiZ3PTAmaz0yMCZj/PUdCMzBha21PYUhM/N2xzbWt5bTlBR3Yt/ZDB2emM2Vm96aXpx/VWRNZ3M5ajQ9", link: "https://en.wikipedia.org/wiki/Lake_Winnipeg" },
      { name: "Lake Nicaragua", location: "Nicaragua", waterQuality: "Good", ph: 7.4, img: "https://imgs.search.brave.com/ZXff3XVMsSkWki4PA_HK2_MgtHqrMAG4oqw4Got14NQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy81/LzU5L0xha2VNYW5h/Z3VhX1RpcGl0YXBh/MS5qcGc", link: "https://en.wikipedia.org/wiki/Lake_Nicaragua" },
      { name: "Lake Ladoga", location: "Russia", waterQuality: "Excellent", ph: 7.7, img: "https://imgs.search.brave.com/qoTlV3vUnMMkq01Oq591o-lS-4LTHj_YjOfEvDHioZU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvOTk5/NTIzNjI4L3Bob3Rv/L3N1bnJpc2Utb24t/bGFrZS1sYWRvZ2Et/c3ByaW5nLTIwMTgu/anBnP3M9NjEyeDYx/MiZ3PTAmaz0yMCZj/PXdacVM4d1F3S25Z/bFdqNV9PdS0ycTg4/LUNUS0pYU0lqenIt/UFMxLWFiOWc9", link: "https://en.wikipedia.org/wiki/Lake_Ladoga" },
      { name: "Lake Tahoe", location: "USA", waterQuality: "Pristine", ph: 7.9, img: "https://imgs.search.brave.com/hjq-gQNRCENMpsI9fJtnhqmh1qFfyMSHdXQPktxHn1Y/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzAwLzUzLzQ2LzQx/LzM2MF9GXzUzNDY0/MTIzX2FoWkxLYlVp/aExIVTVpS2t0V3lL/aUl4SkVxUW1vZUZF/LmpwZw", link: "https://en.wikipedia.org/wiki/Lake_Tahoe" },
      { name: "Lake Okeechobee", location: "USA", waterQuality: "Poor", ph: 7.2, img: "https://imgs.search.brave.com/Exs7M8-povD3w5SL1XyUz1j1LCXmTM4I_ZT49hE40oc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMwMS5ueXQuY29t/L2ltYWdlcy8yMDIz/LzA3LzA1L211bHRp/bWVkaWEvMDBjbGkt/b2tlZWNob2JlZS1s/YWtlLWJxbWsvMDBj/bGktb2tlZWNob2Jl/ZS1sYWtlLWJxbWst/bW9iaWxlTWFzdGVy/QXQzeC5qcGc", link: "https://en.wikipedia.org/wiki/Lake_Okeechobee" },
      { name: "Lake Pontchartrain", location: "USA", waterQuality: "Moderate", ph: 7.3, img: "https://imgs.search.brave.com/WBT5Dc7-ULLTrbbclaz8EvQDBqXjsm8X3McCNZ1Ut7Q/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzAyLzgzLzM5LzY4/LzM2MF9GXzI4MzM5/NjgzNV9JeHFkRzNo/cDdYMHlac2xMVTVL/ak92aUVKN3MxcDd1/eS5qcGc", link: "https://en.wikipedia.org/wiki/Lake_Pontchartrain" },
      { name: "Lake Maracaibo", location: "Venezuela", waterQuality: "Poor", ph: 8.0, img: "https://imgs.search.brave.com/VZuXrqpwU-Jf8BtQqtwU4N5g_t1VnDkxKZqDg98fb80/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvMTI4/MTk3NTcxNC9waG90/by9sYWd1bmEtZGUt/c2luYW1haWNhLWF0/LWxha2UtbWFyYWNh/aWJvLXZlbmV6dWVs/YS0xOTY5LmpwZz9z/PTYxMng2MTImdz0w/Jms9MjAmYz04b292/NzM1VjFBeEhqTFdx/blFZVFd1S29HNGhF/UUdKZGlpNFBXZ29h/QzJRPQ", link: "https://en.wikipedia.org/wiki/Lake_Maracaibo" }
    ].sort((a, b) => a.name.localeCompare(b.name));
    
    
            const container = document.querySelector('.grid');
            lakes.forEach(lake => {
              const card = `
                <div class="p-4 rounded-lg shadow-lg w-72 text-center transform transition-transform duration-300 hover:scale-105">
                  <img src="${lake.img}" alt="${lake.name}" class="w-full h-48 object-cover rounded-lg">
                  <h2 class="text-lg font-bold mt-2">${lake.name}</h2>
                  <p class="text-gray-600">${lake.location}</p>
                  <p class="text-gray-800 font-semibold">Water Quality: ${lake.waterQuality}</p>
                  <p class="text-gray-800 font-semibold">pH Value: ${lake.ph}</p>
                  <a href="${lake.link}" class="text-[#f4a261] text-base flex items-center justify-center gap-1 mt-2">
                    <span class="underline">Read More</span> <i class="fas fa-arrow-right text-xs"></i>
                  </a>
                </div>
              `;
              container.innerHTML += card;
            });
          </script>
        </div> 
    </div>
    
    <script src="information.js"></script>