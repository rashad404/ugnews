<main class="bg-gray-100 py-8">
    <section>
        <div class="container mx-auto px-4">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold text-gray-800"><?=$lng->get('Namaz Times')?></h1>
                <p class="text-lg text-gray-600 mt-2"><?=date("d-m-Y")?> <?=$lng->get('Namaz Time')?></p>
            </div>

            <!-- Today's Namaz Times Card with Current Time -->
            <div class="mb-10">
                <?php 
                // Get today's date
                $today = date('j');
                $currentTime = date('H:i');

                // Find today's namaz times
                $todayNamaz = null;
                $nextPrayer = null;
                foreach ($data['namaz_times'] as $namaz) {
                    if ($namaz['day'] == $today) {
                        $todayNamaz = $namaz;
                        break;
                    }
                }

                // Determine the next prayer
                $prayerTimes = [
                    'İmsak' => $todayNamaz['imsak'],
                    'Subh' => $todayNamaz['fajr'],
                    'Sunrise' => $todayNamaz['sunrise'],
                    'Zohr' => $todayNamaz['dhuhr'],
                    'Asr' => $todayNamaz['asr'],
                    'Maghrib' => $todayNamaz['maghrib'],
                    'Isha' => $todayNamaz['isha'],
                ];

                foreach ($prayerTimes as $prayer => $time) {
                    $prayerTimestamp = strtotime($time);
                    $currentTimestamp = strtotime($currentTime);

                    if ($currentTimestamp < $prayerTimestamp) {
                        $nextPrayer = [$prayer, $time];
                        break;
                    }
                }

                // Fallback if no next prayer is found
                if ($nextPrayer === null) {
                    $nextPrayer = [array_keys($prayerTimes)[0], $prayerTimes[array_keys($prayerTimes)[0]]];
                }
                ?>

                <div class="bg-gradient-to-r from-blue-100 via-blue-200 to-blue-300 p-6 rounded-lg shadow-lg flex flex-col md:flex-row justify-between items-center relative">
                    <!-- Real-time Clock (Dynamic JavaScript) -->
                    <div class="absolute top-3 right-4 text-gray-800 font-semibold text-lg">
                        <span id="current-time"></span>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-blue-900 mb-4"><?= $lng->get('Today\'s Namaz Times') ?></h2>
                        <ul class="text-gray-800 space-y-2">
                            <li><strong><?= $lng->get('İmsak') ?>:</strong> <?= $todayNamaz['imsak'] ?></li>
                            <li><strong><?= $lng->get('Subh') ?>:</strong> <?= $todayNamaz['fajr'] ?></li>
                            <li><strong><?= $lng->get('Gün çıxır') ?>:</strong> <?= $todayNamaz['sunrise'] ?></li>
                            <li><strong><?= $lng->get('Zohr') ?>:</strong> <?= $todayNamaz['dhuhr'] ?></li>
                            <li><strong><?= $lng->get('Əsr') ?>:</strong> <?= $todayNamaz['asr'] ?></li>
                            <li><strong><?= $lng->get('Məğrib') ?>:</strong> <?= $todayNamaz['maghrib'] ?></li>
                            <li><strong><?= $lng->get('İşa') ?>:</strong> <?= $todayNamaz['isha'] ?></li>
                        </ul>
                    </div>

                    <div class="mt-6 md:mt-0">
                        <h3 class="text-xl font-semibold text-blue-900"><?= $lng->get('Next Prayer') ?></h3>
                        <p class="text-3xl text-gray-900 font-bold mt-2"><?= $nextPrayer[0] ?>: <?= $nextPrayer[1] ?></p>
                    </div>
                </div>
            </div>

            <!-- Namaz Times Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Gün')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('İmsak')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Subh')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Gün çıxır')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Zohr')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Əsr')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Məğrib')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('İşa')?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?=$lng->get('Midnight')?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($data['namaz_times'] as $namaz): ?>
                            <tr class="hover:bg-gray-100 transition-colors <?= $namaz['day'] == $today ? 'bg-yellow-100 font-bold' : '' ?>">
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['day'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['imsak'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['fajr'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['sunrise'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['dhuhr'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['asr'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['maghrib'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['isha'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $namaz['midnight'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<!-- JavaScript to Show Real-Time Clock -->
<script>
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const currentTime = hours + ':' + minutes;
    document.getElementById('current-time').textContent = currentTime;
}

// Update clock every minute
setInterval(updateClock, 1000 * 60);
updateClock(); // Initialize clock immediately on page load
</script>
