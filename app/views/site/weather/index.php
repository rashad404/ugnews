<main class="bg-gradient-to-r from-blue-50 to-blue-100 py-8">
    <section>
        <div class="container mx-auto px-4">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-gray-800"><?=$lng->get('Weather App')?></h1>
                <p class="text-lg text-gray-600 mt-2"><?=date("d-m-Y")?> <?=$lng->get('Weather Forecast')?></p>
            </div>

            <!-- Current Weather Card -->
            <div class="mb-10">
                <?php 
                // Get the current weather data (for example, from Baku or another selected city)
                $currentWeather = $data['weather']; // Assuming $data['weather'] contains the current city weather data

                // Calculate the current time
                $currentTime = date('H:i');
                ?>

                <div class="bg-gradient-to-r from-blue-200 via-blue-300 to-blue-400 p-6 rounded-lg shadow-lg flex flex-col md:flex-row justify-between items-center relative">
                    <div>
                        <h2 class="text-3xl font-bold text-blue-900 mb-4"><?= $lng->get('Current Weather in') ?> <?= $lng->get($currentWeather['city_name']) ?></h2>
                        <ul class="text-gray-800 space-y-2">
                            <li><strong><?= $lng->get('Temperature') ?>:</strong> <?= round($currentWeather['temp']) ?> °C</li>
                            <li><strong><?= $lng->get('Feels Like') ?>:</strong> <?= round($currentWeather['feels_like']) ?> °C</li>
                            <li><strong><?= $lng->get('Humidity') ?>:</strong> <?= $currentWeather['humidity'] ?>%</li>
                            <li><strong><?= $lng->get('Wind Speed') ?>:</strong> <?= $currentWeather['wind_speed'] ?> m/s</li>
                            <li><strong><?= $lng->get('Weather Condition') ?>:</strong> <?= $lng->get($currentWeather['weather_description']) ?></li>
                        </ul>
                    </div>

                    <div class="mt-6 md:mt-0">
                        <img class="w-24 h-24" src="https://openweathermap.org/img/wn/<?= $currentWeather['weather_icon'] ?>@2x.png" alt="Weather Icon">
                        <p class="text-2xl text-gray-900 font-bold mt-2"><?= $lng->get($currentWeather['weather_main']) ?></p>
                    </div>
                </div>
            </div>

            <?php include "list.php";?>
        </div>
    </section>
</main>
