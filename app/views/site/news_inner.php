<?php
use Helpers\Url;
use Helpers\Format;
use Helpers\Csrf;

$ad = $data['ad'];
$item = $data['item'];
$channel_info = \Models\ChannelsModel::getItem($item['channel']);
$subscribe_check = \Models\NewsModel::subscribeCheck($item['channel']);
$like_check = \Models\NewsModel::likeCheck($item['id']);
$dislike_check = \Models\NewsModel::dislikeCheck($item['id']);
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <article class="bg-white shadow-lg rounded-lg overflow-hidden">
            <header class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <img class="w-12 h-12 rounded-full object-cover" src="<?= Url::filePath() . $channel_info['thumb'] ?>" alt="<?= $channel_info['name'] ?>" />
                    <div class="flex-1">
                        <a href="/<?= Format::urlTextChannel($channel_info['name_url']) ?>" class="text-lg font-semibold text-gray-900 hover:underline"><?= $channel_info['name'] ?></a>
                        <p class="text-sm text-gray-500"><?= date("d.m.Y H:i", $item['publish_time']) ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-700"><?= number_format($channel_info['subscribers']) ?> <?= $lng->get('subscribers') ?></p>
                        <p class="text-sm text-gray-500"><?= number_format($item['view']) ?> <i class="fas fa-eye ml-1"></i></p>
                    </div>
                </div>
            </header>
            
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= $item['title'] ?> <span class="text-red-600"><?= $item['title_extra'] ?></span></h1>
                
                <?php if (!empty($item['image'])) : ?>
                    <img class="w-full h-auto mb-6 rounded-lg shadow-md" src="<?= Url::filePath() . $item['image'] ?>" alt="<?= $item['title'] ?>" />
                <?php endif; ?>
                
                <div class="prose max-w-none text-gray-700">
                    <?= html_entity_decode($item['text']) ?>
                </div>
            </div>
            
            <?php if (!empty($item['tags'])) : ?>
                <div class="px-6 py-4 border-t border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2"><?= $lng->get('Tags') ?>:</h2>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (explode(',', $item['tags']) as $tag) : ?>
                            <a href="/tags/<?= Format::urlTextTag($tag) ?>" class="px-3 py-1 bg-gray-200 text-sm font-medium text-gray-700 rounded-full hover:bg-gray-300 transition"><?= $tag ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <footer class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div class="news_inner_subscribe_area">
                <button 
                    id="subscribe_button" 
                    data-channel-id="<?= $item['channel'] ?>" 
                    class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> subscribe <?= ($subscribe_check === true) ? 'subscribed' : '' ?> px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <i class="fas fa-<?= ($subscribe_check === true) ? 'bell-slash' : 'bell' ?> mr-2"></i>
                    <span><?= $lng->get(($subscribe_check === true) ? 'Subscribed' : 'Subscribe') ?></span>
                </button>
            </div>
            <div class="news_inner_subscribe_area flex space-x-2">
                <button 
                    id="like_button" 
                    data-news-id="<?= $item['id'] ?>" 
                    class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> like <?= ($like_check === true) ? 'liked' : '' ?> px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    <i class="fas fa-thumbs-up mr-1"></i>
                    <span id="like_count"><?= $item['likes'] ?></span>
                </button>
                <button 
                    id="dislike_button" 
                    data-news-id="<?= $item['id'] ?>" 
                    class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> dislike <?= ($dislike_check === true) ? 'disliked' : '' ?> px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    <i class="fas fa-thumbs-down mr-1"></i>
                    <span id="dislike_count"><?= $item['dislikes'] ?></span>
                </button>
            </div>
            </footer>
        </article>
        
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4"><?= $lng->get('Share') ?>:</h2>
            <div class="flex flex-wrap gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" target="_blank">
                    <i class="fab fa-facebook-f mr-2"></i> Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?text=<?= urlencode(Format::listTitle($item['title'])) ?>&url=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-blue-500 transition focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" target="_blank">
                    <i class="fab fa-twitter mr-2"></i> Twitter
                </a>
                <a href="whatsapp://send?text=<?= urlencode(Format::listTitle($item['title']) . ' https://ug.news/' . $item['slug']) ?>" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                </a>
                <a href="mailto:?subject=<?= urlencode(Format::listTitle($item['title'])) ?>&body=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-envelope mr-2"></i> Email
                </a>
            </div>
        </div>
    </div>
    
    <div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <h2 class="text-xl font-semibold p-4 bg-gray-50 border-b border-gray-200"><?= $lng->get('Similar News') ?></h2>
            <div class="divide-y divide-gray-200">
                <?php 
                $c = 1;
                foreach ($data['list'] as $list) : 
                    $list_channel_info = \Models\ChannelsModel::getItem($list['channel']);
                    
                    if ($c == 2) : 
                ?>
                    <!-- Ad Item -->
                    <div class="p-4 bg-blue-50">
                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Reklam</span>
                        <a href="ads/click/<?= $ad['id'] ?>" target="_blank" class="mt-2 flex items-center group">
                            <img src="<?= Url::filePath() . $ad['thumb'] ?>" alt="" class="w-16 h-16 object-cover rounded-md mr-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition"><?= Format::listTitle($ad['title'], 20) ?></h3>
                                <p class="text-sm text-gray-600"><?= Format::listText($ad['text'], 50) ?></p>
                            </div>
                        </a>
                    </div>
                <?php 
                    endif;
                ?>
                <!-- News Item -->
                <a href="<?=$list['slug']?>" class="block p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="flex items-center">
                        <?php if (!empty($list['thumb'])) : ?>
                            <img src="<?= Url::filePath() . $list['thumb'] ?>" alt="" class="w-20 h-20 object-cover rounded-md mr-4">
                        <?php endif; ?>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 hover:text-blue-600 transition"><?= Format::listTitle($list['title'], 50) ?></h3>
                            <p class="text-sm text-gray-600 mt-1"><?= $list_channel_info['name'] ?></p>
                            <div class="flex items-center text-xs text-gray-500 mt-2">
                                <span class="mr-2"><?= number_format($list['view']) ?> <?= $lng->get('view') ?></span>
                                <span><i class="fas fa-clock mr-1"></i><?= date("H:i", $list['publish_time']) ?></span>
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

<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full m-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900" id="umodal_title"><?= $lng->get('Login') ?></h2>
            <button class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeLoginModal()">
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
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    document.getElementById('loginModal').classList.remove('flex');
    document.body.style.overflow = '';
}

document.querySelectorAll('.umodal_toggle').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        openLoginModal();
    });
});

function handleAjaxRequest(url, button, successCallback) {
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        if (successCallback) {
            successCallback(data, button);
        }
    })
    .catch(error => console.error('Error:', error));
}

function handleSubscribe(button) {
    const channelId = button.getAttribute('data-channel-id');
    const isSubscribed = button.classList.contains('subscribed');
    const url = isSubscribed ? `/ajax/un_subscribe/${channelId}` : `/ajax/subscribe/${channelId}`;

    handleAjaxRequest(url, button, (data, btn) => {
        btn.classList.toggle('subscribed');
        btn.querySelector('i').classList.toggle('fa-bell');
        btn.querySelector('i').classList.toggle('fa-bell-slash');
        btn.querySelector('span').textContent = data;
    });
}

function handleLikeDislike(button, action) {
    const newsId = button.getAttribute('data-news-id');
    const isActive = button.classList.contains(`${action}d`);
    const url = isActive ? `/ajax/remove_${action}/${newsId}` : `/ajax/${action}/${newsId}`;

    handleAjaxRequest(url, button, (data, btn) => {
        btn.classList.toggle(`${action}d`);
        // Update the count
        const countSpan = btn.querySelector(`#${action}_count`);
        if (countSpan) {
            countSpan.textContent = parseInt(countSpan.textContent) + (isActive ? -1 : 1);
        }
    });
}

document.getElementById('subscribe_button').addEventListener('click', function() {
    if (!this.classList.contains('umodal_toggle')) {
        handleSubscribe(this);
    }
});

document.getElementById('like_button').addEventListener('click', function() {
    if (!this.classList.contains('umodal_toggle')) {
        handleLikeDislike(this, 'like');
    }
});

document.getElementById('dislike_button').addEventListener('click', function() {
    if (!this.classList.contains('umodal_toggle')) {
        handleLikeDislike(this, 'dislike');
    }
});
</script>