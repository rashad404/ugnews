<?php

use Helpers\Url;
use Helpers\Format;

$ad = $data['ad'];
$item = $data['item']; // Assuming this is available in the data array
$channel_info = \Models\ChannelsModel::getItem($item['channel']);
$subscribe_check = \Models\NewsModel::subscribeCheck($item['channel']);
$like_check = \Models\NewsModel::likeCheck($item['id']);
$dislike_check = \Models\NewsModel::dislikeCheck($item['id']);
?>

<main class="container mx-auto px-4 py-24">
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-2/3">
            <article class="bg-white shadow-lg rounded-lg overflow-hidden">
                <header class="p-4 border-b">
                    <div class="flex items-center space-x-4">
                        <img class="w-12 h-12 rounded-full" src="<?= Url::filePath() . $channel_info['thumb'] ?>" alt="<?= $channel_info['name'] ?>" />
                        <div>
                            <a href="/<?= Format::urlTextChannel($channel_info['name_url']) ?>" class="text-lg font-semibold hover:underline"><?= $channel_info['name'] ?></a>
                            <p class="text-sm text-gray-500"><?= date("d.m.Y H:i", $item['publish_time']) ?></p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-sm font-medium"><?= $channel_info['subscribers'] ?> <?= $lng->get('subscribers') ?></p>
                            <p class="text-sm"><?= $item['view'] ?> <i class="fas fa-signal"></i></p>
                        </div>
                    </div>
                </header>
                
                <div class="p-4">
                    <h1 class="text-2xl font-bold mb-4"><?= $item['title'] ?> <span class="text-red-500"><?= $item['title_extra'] ?></span></h1>
                    
                    <?php if (!empty($item['image'])) : ?>
                        <img class="w-full h-auto mb-4 rounded" src="<?= Url::filePath() . $item['image'] ?>" alt="<?= $item['title'] ?>" />
                    <?php endif; ?>
                    
                    <div class="prose max-w-none">
                        <?= html_entity_decode($item['text']) ?>
                    </div>
                </div>
                
                <?php if (!empty($item['tags'])) : ?>
                    <div class="p-4 border-t">
                        <h2 class="text-lg font-semibold mb-2"><?= $lng->get('Tags') ?>:</h2>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach (explode(',', $item['tags']) as $tag) : ?>
                                <a href="/tags/<?= Format::urlTextTag($tag) ?>" class="px-3 py-1 bg-gray-200 text-sm rounded-full hover:bg-gray-300 transition"><?= $tag ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <footer class="p-4 border-t flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button id="subscribe_button" channel_id="<?= $item['channel'] ?>" class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition <?= ($subscribe_check === true) ? 'bg-gray-500 hover:bg-gray-600' : '' ?>">
                            <i class="fas fa-<?= ($subscribe_check === true) ? 'bell-slash' : 'bell' ?> mr-2"></i>
                            <?= $lng->get(($subscribe_check === true) ? 'Subscribed' : 'Subscribe') ?>
                        </button>
                    </div>
                    <div class="flex space-x-2">
                        <button id="like_button" news_id="<?= $item['id'] ?>" class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition <?= ($like_check === true) ? 'bg-green-600' : '' ?>">
                            <i class="fas fa-thumbs-up"></i>
                        </button>
                        <button id="dislike_button" news_id="<?= $item['id'] ?>" class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition <?= ($dislike_check === true) ? 'bg-red-600' : '' ?>">
                            <i class="fas fa-thumbs-down"></i>
                        </button>
                    </div>
                </footer>
            </article>
            
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4"><?= $lng->get('Share') ?>:</h2>
                <div class="flex flex-wrap gap-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition" target="_blank">
                        <i class="fab fa-facebook-f mr-2"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?= Format::listTitle($item['title']) ?>&url=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-blue-400 text-white rounded hover:bg-blue-500 transition" target="_blank">
                        <i class="fab fa-twitter mr-2"></i> Twitter
                    </a>
                    <a href="whatsapp://send?text=<?= Format::listTitle($item['title']) ?> https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                    <a href="mailto:?subject=<?= Format::listTitle($item['title']) ?> &body=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        <i class="fas fa-envelope mr-2"></i> Email
                    </a>
                </div>
            </div>
        </div>
        
        <div class="lg:w-1/3">
    <div class="bg-gray-100 rounded-lg overflow-hidden shadow-md">
        <h2 class="text-2xl font-bold p-4 text-gray-800 border-b"><?= $lng->get('Similar News') ?></h2>
        <div class="space-y-4 p-4">
            <?php 
            $c = 1;
            foreach ($data['list'] as $list) : 
                $list_channel_info = \Models\ChannelsModel::getItem($list['channel']);
                
                if ($c == 2) : 
            ?>
                <!-- Ad Item -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-blue-100">
                    <div class="p-4">
                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Reklam</span>
                        <a href="ads/click/<?= $ad['id'] ?>" target="_blank" class="mt-2 flex items-center">
                            <img src="<?= Url::filePath() . $ad['thumb'] ?>" alt="" class="w-16 h-16 object-cover rounded-md mr-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800"><?= Format::listTitle($ad['title'], 20) ?></h3>
                                <p class="text-sm text-gray-600"><?= Format::listText($ad['text'], 50) ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php 
                endif;
            ?>
            <!-- News Item -->
            <a href="<?=$list['slug']?>" class="block bg-white rounded-lg shadow-sm overflow-hidden transition duration-300 ease-in-out transform hover:scale-105">
                <div class="flex items-center p-4">
                    <?php if (!empty($list['thumb'])) : ?>
                        <img src="<?= Url::filePath() . $list['thumb'] ?>" alt="" class="w-20 h-20 object-cover rounded-md mr-4">
                    <?php endif; ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 line-clamp-2"><?= Format::listTitle($list['title'], 50) ?></h3>
                        <p class="text-sm text-gray-500 mt-1"><?= $list_channel_info['name'] ?></p>
                        <div class="flex items-center text-xs text-gray-400 mt-2">
                            <span class="mr-2"><?= $list['view'] ?> <?= $lng->get('view') ?></span>
                            <span><i class="fas fa-calendar mr-1"></i><?= date("H:i", $list['publish_time']) ?></span>
                        </div>
                    </div>
                </div>
            </a>
            <?php 
            $c++;
            endforeach; 
            ?>
        </div>
    </div>
</div>
    </div>
</main>

<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold" id="umodal_title"><?= $lng->get('Login') ?></h2>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeLoginModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <hr class="mb-4" />
        <div id="loginModalContent">
            <?php require $data['modal_url']; ?>
        </div>
    </div>
</div>

<script>
function openLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
    document.getElementById('loginModal').classList.add('flex');
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    document.getElementById('loginModal').classList.remove('flex');
}

// Replace umodal_toggle class functionality
document.querySelectorAll('.umodal_toggle').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        openLoginModal();
    });
});
</script>