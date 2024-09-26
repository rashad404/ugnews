<?php

use Helpers\Url;
use Helpers\Format;

?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($data['list'] as $list) : ?>
        <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <a href="<?= $list['slug'] ?>" class="block">
                <img class="w-full h-48 object-cover" src="<?= Url::filePath() ?><?= $list['thumb'] ?>" alt="<?= $list['title'] ?>" />
                <div class="p-4">
                    <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2"><?= Format::listTitle($list['title'], 70) ?></h2>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-orange-500 font-semibold">
                            <?= $lng->get(\Models\NewsModel::getCatName($list['cat'])) ?>
                        </span>
                        <span class="text-gray-500">
                            <?= date("d.m.Y", $list['publish_time']) ?>
                        </span>
                    </div>
                </div>
            </a>
            <div class="px-4 py-2 bg-gray-100 flex justify-between items-center">
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-sync-alt"></i> <!-- Refresh icon -->
                </button>
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="far fa-bookmark"></i> <!-- Bookmark icon -->
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="mt-8 flex justify-center">
    <?php echo $data["pagination"]->pageNavigation(); ?>
</div>