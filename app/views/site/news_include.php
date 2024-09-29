<?php

use Helpers\Url;
use Helpers\Format;

?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($data['list'] as $list) : ?>
        <div class="bg-white rounded-lg overflow-hidden shadow-md flex flex-col">
            <a href="<?= $list['slug'] ?>" class="block flex-grow">
                <?php if (!empty($list['thumb'])) : ?>
                    <img class="w-full h-48 object-cover" src="<?= Url::filePath() ?><?= $list['thumb'] ?>" alt="<?= $list['title'] ?>" />
                    <div class="p-4">
                        <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2"><?= Format::listTitle($list['title'], 70) ?></h2>
                    </div>
                <?php else : ?>
                    <div class="p-4">
                        <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-8"><?= Format::listTitle($list['text'], 300) ?></h2>
                    </div>
                <?php endif; ?>
            </a>

            <!-- Bottom Section: Category, Date, Channel Info, Views -->
            <div class="px-4 py-2 bg-gray-100 mt-auto">
                <div class="flex justify-between items-center text-sm mb-2">
                    <span class="text-orange-500 font-semibold">
                        <?= $lng->get(\Models\NewsModel::getCatName($list['cat'])) ?>
                    </span>
                    <span class="text-gray-500">
                        <?= date("d.m.Y", $list['publish_time']) ?>
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <?php $channel_info = \Models\ChannelsModel::getItem($list['channel']); ?>
                        <img class="w-8 h-8 rounded-full mr-2" src="<?= Url::filePath() ?><?= $channel_info['thumb'] ?>" alt="<?= $channel_info['name']; ?>" />
                        <a href="/<?= Format::urlTextChannel($channel_info['name_url']) ?>" class="text-sm font-semibold text-gray-800 hover:text-blue-600">
                            <?= $channel_info['name']; ?>
                        </a>
                    </div>
                    <div class="text-gray-600 hover:text-gray-800 flex items-center">
                        <?= $list['view'] ?> <i class="fas fa-signal ml-1"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="mt-8 flex justify-center">
    <?php echo $data["pagination"]->pageNavigation(); ?>
</div>
