<?php 
// Get the current weather data (for example, from Baku or another selected city)
$currentWeather = $data['weather']; // Assuming $data['weather'] contains the current city weather data

// Calculate the current time
$currentTime = date('H:i');

function getTemperatureColor($temp) {
    if ($temp < 0) return 'text-blue-600';
    if ($temp < 10) return 'text-green-600';
    if ($temp < 20) return 'text-yellow-600';
    if ($temp < 30) return 'text-orange-600';
    return 'text-red-600';
}
?>


<main class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Title -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-white mb-2">
                <?=$lng->get('Weather Forecast')?>
            </h1>
            <p class="text-xl text-blue-100">
                <?=$lng->get(date('F')) . date(" d, Y")?> | <?=$lng->get('Real-time Weather Insights')?>
            </p>
        </div>

        <!-- Main Weather Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="p-6 flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2"><?= $lng->get($currentWeather['city_name']) ?></h2>
                    <div class="flex items-center">
                        <span class="text-5xl font-bold text-blue-600"><?= round($currentWeather['temp']) ?>°C</span>
                        <img class="w-20 h-20 ml-4" src="https://openweathermap.org/img/wn/<?= $currentWeather['weather_icon'] ?>@2x.png" alt="Weather Icon">
                    </div>
                    <p class="text-xl text-gray-600"><?= $lng->get($currentWeather['weather_description']) ?></p>
                </div>
                <div class="mt-4 md:mt-0 grid grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-thermometer-half text-red-500 mr-2"></i>
                        <span><?= $lng->get('Feels like') ?> <?= round($currentWeather['feels_like']) ?>°C</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-tint text-blue-500 mr-2"></i>
                        <span><?= $currentWeather['humidity'] ?>% <?= $lng->get('Humidity') ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-wind text-gray-500 mr-2"></i>
                        <span><?= $currentWeather['wind_speed'] ?> m/s <?= $lng->get('Wind') ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-compress-arrows-alt text-purple-500 mr-2"></i>
                        <span><?= $currentWeather['pressure'] ?> hPa</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- City Weather Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($data['all_weather'] as $weather): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= $lng->get($weather['city_name']) ?></h3>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-3xl font-bold <?= getTemperatureColor($weather['temp']) ?>"><?= round($weather['temp']) ?>°C</span>
                            <img class="w-16 h-16" src="https://openweathermap.org/img/wn/<?= $weather['weather_icon'] ?>@2x.png" alt="Weather Icon">
                        </div>
                        <p class="text-gray-600 mb-2"><?= $lng->get($weather['weather_description']) ?></p>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="flex items-center">
                                <i class="fas fa-thermometer-half text-red-500 mr-1"></i>
                                <span><?= round($weather['feels_like']) ?>°C</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tint text-blue-500 mr-1"></i>
                                <span><?= $weather['humidity'] ?>%</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-wind text-gray-500 mr-1"></i>
                                <span><?= $weather['wind_speed'] ?> m/s</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-compress-arrows-alt text-purple-500 mr-1"></i>
                                <span><?= $weather['pressure'] ?> hPa</span>
                            </div>
                        </div>
                    </div>
                    <a href="/hava-haqqinda/<?= $weather['slug'] ?>" class="block bg-blue-500 text-white text-center py-2 hover:bg-blue-600 transition duration-300">
                        <?= $lng->get('View Detailed Forecast') ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<script>
    // Function to dynamically update time
    function updateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString();
    }

    // Update time every second
    setInterval(updateTime, 1000);

    // Initialize time on page load
    updateTime();
</script>