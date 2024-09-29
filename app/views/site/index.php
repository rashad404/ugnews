<?php
use Models\CurrencyModel;
use Models\NamazTimesModel;
use Helpers\Url;
use Helpers\Format;

// Prepare the info list dynamically
$info_list = [
    ['Valyuta', "1 USD = " . ($usdRate ?? 'N/A') . " AZN", 'valyuta'],
    ['Hava', 'Bakı 13 °', 'tags/hava'],
    ['Namaz vaxtı', "Sübh: " . ($todayNamaz['fajr'] ?? 'N/A'), "namaz-vaxti"]
];

if ($data['region'] == 16) {
    $tag_list = [
        'Bakı', 'Türkiyə', 'Hava', 'Neft qiyməti'
    ];
} else {
    $tag_list = [
        'Bakı', 'New York', 'Oil price'
    ];
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Info Boxes -->
    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($info_list as $list): ?>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        <?= $list[0] ?>
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        <?= $list[1] ?>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="<?= $list[2] ?>" class="font-medium text-indigo-600 hover:text-indigo-500"> View all <span class="sr-only"><?= $list[0] ?></span></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Channels Section -->
    <?php if ($data['current_page'] <= 1): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">
                <?= $lng->get('Channels') ?>
                <a href="rating/channels" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">(<?= $lng->get('TOP') ?> <i class="fas fa-chart-bar"></i>)</a>
            </h2>
            <div class="mt-6 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 xl:gap-x-8">
                <div class="group">
                    <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                        <a href="create/channel" class="relative w-full h-full flex items-center justify-center hover:bg-gray-300 transition-colors duration-200">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </a>
                    </div>
                    <h3 class="mt-4 text-sm text-gray-700 text-center">
                        <?= $lng->get('Create Your Channel') ?>
                    </h3>
                </div>
                <?php foreach ($data['channel_list'] as $list): ?>
                    <div class="group">
                        <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                            <img src="<?= Url::filePath() ?>/<?= $list['thumb'] ?>?aas" alt="<?= $list['name'] ?>" class="w-full h-full object-center object-cover group-hover:opacity-75">
                        </div>
                        <h3 class="mt-4 text-sm text-gray-700">
                            <a href="/<?= Format::urlTextChannel($list['name_url']) ?>">
                                <?= Format::listTitle($list['name'], 50) ?>
                            </a>
                        </h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Featured Tags -->
        <div class="mt-12">
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900"><?= $lng->get('Featured') ?></h2>
            <div class="mt-6 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                <?php foreach ($tag_list as $tag): ?>
                    <a href="tags/<?= Format::urlTextTag($tag) ?>" class="group">
                        <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center text-2xl font-semibold text-gray-900 group-hover:bg-gray-300 transition-colors duration-200">
                                #<?= Format::shortText($tag, 20) ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Latest News -->
    <div class="mt-12">
        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">
            <?= $lng->get('Latest News') ?>
            <a href="rating/news" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">(<?= $lng->get('TOP') ?> <i class="fas fa-chart-bar"></i>)</a>
        </h2>
            <?php include 'news_include.php'; ?>
    </div>

    <!-- Local News -->
    <?php if ($data['current_page'] <= 1 && count($data['city_list_1']) > 0): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">
                <?= $lng->get('Local News') ?>
            </h2>
            <div class="mt-6 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                <?php foreach ($data['city_list_1'] as $list): ?>
                    <a href="/city/<?= $list['id'] ?>/<?= Format::urlText($list['name']) ?>" class="group">
                        <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center text-lg font-medium text-gray-900 group-hover:bg-gray-300 transition-colors duration-200">
                                <?= Format::listTitle($list['name'], 50) ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php if (count($data['city_list_2']) > 0): ?>
                <div id="more-cities" class="hidden mt-6 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                    <?php foreach ($data['city_list_2'] as $list): ?>
                        <a href="/city/<?= $list['id'] ?>/<?= Format::urlText($list['name']) ?>" class="group">
                            <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                                <div class="w-full h-full flex items-center justify-center text-lg font-medium text-gray-900 group-hover:bg-gray-300 transition-colors duration-200">
                                    <?= Format::listTitle($list['name'], 50) ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 text-center">
                    <button id="show-more-cities" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <?= $lng->get('Show More') ?>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showMoreButton = document.getElementById('show-more-cities');
        const moreCities = document.getElementById('more-cities');

        if (showMoreButton && moreCities) {
            showMoreButton.addEventListener('click', function() {
                moreCities.classList.toggle('hidden');
                showMoreButton.textContent = moreCities.classList.contains('hidden') 
                    ? '<?= $lng->get('Show More') ?>' 
                    : '<?= $lng->get('Show Less') ?>';
            });
        }
    });
</script>