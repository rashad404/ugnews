<?php
use Models\CurrencyModel;
use Models\NamazTimesModel;
use Helpers\Url;
use Helpers\Format;

// Prepare the info list dynamically
$info_list = [
    ['Valyuta', "1 USD = " . ($usdRate ?? 'N/A') . " AZN", 'valyuta'],
    ['Hava haqqında', "Bakı " . ($bakuWeatherInfo ?? 'N/A') . " °C", 'hava-haqqinda'],
    // ['Hava', 'Bakı 13 °', 'tags/hava'],
    ['Namaz vaxtı', "Sübh: " . ($todayNamaz['fajr'] ?? 'N/A'), "namaz-vaxti"]
];

$tag_list = ($data['region'] == 16) 
    ? ['Bakı', 'Türkiyə', 'Hava', 'Neft qiyməti']
    : ['Bakı', 'New York', 'Oil price'];
?>

<!-- Info Boxes (Compact) -->
<div class="mt-4 flex flex-wrap justify-between items-center text-sm">
    <?php foreach ($info_list as $list): ?>
        <a href="<?= $list[2] ?>" class="mb-2 px-3 py-1 bg-white rounded-full shadow hover:bg-gray-50">
            <span class="font-medium text-gray-600"><?= $list[0] ?>:</span>
            <span class="text-gray-800"><?= $list[1] ?></span>
        </a>
    <?php endforeach; ?>
</div>

<!-- Featured Tags (Compact) -->
<div class="mt-4 flex flex-wrap gap-2">
    <?php foreach ($tag_list as $tag): ?>
        <a href="tags/<?= Format::urlTextTag($tag) ?>" class="px-3 py-1 bg-gray-200 text-sm font-medium text-gray-700 rounded-full hover:bg-gray-300 transition-colors duration-200">
            #<?= Format::shortText($tag, 20) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Channels Section (Compact) -->
<?php if ($data['current_page'] <= 1): ?>
    <div class="mt-8   px-4 py-6 sm:px-6 lg:px-8 bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">
                <?= $lng->get('Channels') ?>
            </h2>
            <a href="rating/channels" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                <?= $lng->get('TOP') ?> <i class="fas fa-chart-bar"></i>
            </a>
        </div>
        <div class="flex overflow-x-auto pb-2 -mx-4 sm:mx-0">
            <div class="flex-none px-4 sm:px-0 mr-4 lg:mr-16">
                <a href="create/channel" class="block w-20 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-2 shadow">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-600"><?= $lng->get('Create Channel') ?></span>
                </a>
            </div>
            <?php foreach ($data['channel_list'] as $list): ?>
                <div class="flex-none px-4 sm:px-0 mr-4 lg:mr-16">
                    <a href="/<?= Format::urlTextChannel($list['name_url']) ?>" class="block w-20 text-center">
                        <img src="https://new.ug.news/storage/<?= $list['image'] ?>" alt="<?= $list['name'] ?>" class="w-20 h-20 object-cover rounded-full mb-2 shadow">
                        <span class="text-xs text-gray-600"><?= Format::listTitle($list['name'], 20) ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Latest News -->
<div class="mt-8">
    <?php include 'news_include.php'; ?>
</div>

<!-- Local News (Compact) -->
<?php if ($data['current_page'] <= 1 && count($data['city_list_1']) > 0): ?>
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <?= $lng->get('Local News') ?>
        </h2>
        <div class="flex flex-wrap gap-2">
            <?php 
            $allCities = array_merge($data['city_list_1'], $data['city_list_2']);
            foreach (array_slice($allCities, 0, 12) as $list): 
            ?>
                <a href="/city/<?= $list['id'] ?>/<?= Format::urlText($list['name']) ?>" class="px-3 py-1 bg-gray-200 text-sm font-medium text-gray-700 rounded-full hover:bg-gray-300 transition-colors duration-200">
                    <?= Format::listTitle($list['name'], 20) ?>
                </a>
            <?php endforeach; ?>
            <?php if (count($allCities) > 12): ?>
                <button id="show-more-cities" class="px-3 py-1 bg-indigo-100 text-sm font-medium text-indigo-700 rounded-full hover:bg-indigo-200 transition-colors duration-200">
                    <?= $lng->get('More') ?> +
                </button>
            <?php endif; ?>
        </div>
        <?php if (count($allCities) > 12): ?>
            <div id="more-cities" class="hidden mt-2 flex flex-wrap gap-2">
                <?php foreach (array_slice($allCities, 12) as $list): ?>
                    <a href="/city/<?= $list['id'] ?>/<?= Format::urlText($list['name']) ?>" class="px-3 py-1 bg-gray-200 text-sm font-medium text-gray-700 rounded-full hover:bg-gray-300 transition-colors duration-200">
                        <?= Format::listTitle($list['name'], 20) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showMoreButton = document.getElementById('show-more-cities');
        const moreCities = document.getElementById('more-cities');

        if (showMoreButton && moreCities) {
            showMoreButton.addEventListener('click', function() {
                moreCities.classList.toggle('hidden');
                showMoreButton.textContent = moreCities.classList.contains('hidden') 
                    ? '<?= $lng->get('More') ?> +' 
                    : '<?= $lng->get('Less') ?> -';
            });
        }
    });
</script>