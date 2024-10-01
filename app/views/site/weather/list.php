            <!-- Weather Forecast Table for Multiple Cities -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('City')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Temperature')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Feels Like')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Humidity')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Wind Speed')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Weather Condition')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Icon')?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($data['all_weather'] as $weather): ?>
                            <tr class="hover:bg-gray-100 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/hava-haqqinda/<?= $weather['slug'] ?>" class="text-blue-500 hover:underline">
                                        <?= $lng->get($weather['city_name']) ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= round($weather['temp']) ?> °C</td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= round($weather['feels_like']) ?> °C</td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $weather['humidity'] ?>%</td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $weather['wind_speed'] ?> m/s</td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $lng->get($weather['weather_description']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img class="w-10 h-10" src="https://openweathermap.org/img/wn/<?= $weather['weather_icon'] ?>@2x.png" alt="Weather Icon">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>