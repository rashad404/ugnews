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
                        <p class="text-sm text-gray-500">
                            <?= number_format($item['view']) ?>
                            <svg class="inline-block w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        </p>
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
                    class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> subscribe <?= ($subscribe_check === true) ? 'subscribed' : '' ?> px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center"
                >
                
                        <?php if ($subscribe_check === true): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.964 8.964 0 0 0 2.3-5.542m3.155 6.852a3 3 0 0 0 5.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 0 0 3.536-1.003A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53" />
                            </svg>

                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>

                        <?php endif; ?>
                    <span><?= $lng->get(($subscribe_check === true) ? 'Subscribed' : 'Subscribe') ?></span>
                </button>
            </div>
            <div class="news_inner_subscribe_area flex space-x-2">
                <button 
                    id="like_button" 
                    data-news-id="<?= $item['id'] ?>" 
                    class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> like <?= ($like_check === true) ? 'liked' : '' ?> px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center"
                >
                    <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                    </svg>
                    <span id="like_count"><?= $item['likes'] ?></span>
                </button>
                <button 
                    id="dislike_button" 
                    data-news-id="<?= $item['id'] ?>" 
                    class="<?= ($data['userId'] > 0) ? '' : 'umodal_toggle' ?> dislike <?= ($dislike_check === true) ? 'disliked' : '' ?> px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center"
                >
                    <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z" />
                    </svg>
                    <span id="dislike_count"><?= $item['dislikes'] ?></span>
                </button>
            </div>
            </footer>
        </article>
        
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4"><?= $lng->get('Share') ?>:</h2>
            <div class="flex flex-wrap gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center" target="_blank">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M20 10.5C20 5.25329 15.5228 0.999999 10 0.999999C4.47715 1 0 5.25329 0 10.5C0 15.2349 3.65685 19.1251 8.4375 19.8541V13.2133H5.89844V10.5H8.4375V8.3907C8.4375 5.9917 9.93042 4.5782 12.2146 4.5782C13.3087 4.5782 14.4531 4.75618 14.4531 4.75618V7.10547H13.1921C11.9499 7.10547 11.5625 7.83557 11.5625 8.58428V10.5H14.3359L13.8926 13.2133H11.5625V19.8541C16.3431 19.1251 20 15.2349 20 10.5Z"/>
                    </svg>
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?text=<?= urlencode(Format::listTitle($item['title'])) ?>&url=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-blue-500 transition focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 flex items-center" target="_blank">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M20 3.89414C19.2563 4.21524 18.4637 4.43634 17.6375 4.53384C18.4875 4.02924 19.1363 3.23384 19.4412 2.29514C18.6488 2.75514 17.7738 3.08924 16.8412 3.26384C16.0887 2.47384 15.0162 1.98384 13.8462 1.98384C11.5763 1.98384 9.74875 3.81134 9.74875 6.08134C9.74875 6.39764 9.77625 6.70134 9.84375 6.99264C6.435 6.82924 3.41875 5.20764 1.3925 2.73764C1.03875 3.33014 0.83125 4.02924 0.83125 4.76964C0.83125 6.17124 1.5625 7.40634 2.6525 8.12554C1.99375 8.11294 1.3475 7.93014 0.8 7.62384C0.8 7.63644 0.8 7.65264 0.8 7.66884C0.8 9.66884 2.22125 11.3313 4.085 11.7063C3.75125 11.7938 3.3875 11.8363 3.01 11.8363C2.7475 11.8363 2.4825 11.8213 2.23375 11.7713C2.765 13.4013 4.2725 14.6013 6.065 14.6363C4.67 15.7363 2.89875 16.3738 0.98125 16.3738C0.645 16.3738 0.3225 16.3588 0 16.3163C1.81625 17.4963 3.96875 18.1488 6.29 18.1488C13.835 18.1488 17.96 11.9013 17.96 6.49384C17.96 6.32134 17.9538 6.15384 17.945 5.98634C18.7588 5.41134 19.4425 4.69514 20 3.89414Z"/>
                    </svg>
                    Twitter
                </a>
                <a href="whatsapp://send?text=<?= urlencode(Format::listTitle($item['title']) . ' https://ug.news/' . $item['slug']) ?>" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:offset-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 0C4.477 0 0 4.477 0 10c0 2.136.67 4.116 1.81 5.74L.07 20l4.33-1.73C5.9 19.34 7.893 20 10 20c5.523 0 10-4.477 10-10S15.523 0 10 0zm.002 16.123c-1.196 0-2.374-.3-3.425-.87l-2.396.958.975-2.37a6.03 6.03 0 01-.929-3.23c0-3.342 2.715-6.057 6.058-6.057 3.342 0 6.056 2.715 6.056 6.057 0 3.342-2.714 6.057-6.057 6.057zm3.634-4.543c-.181-.09-1.075-.532-1.242-.592-.167-.06-.288-.09-.41.09-.12.18-.47.59-.575.71-.106.12-.212.134-.393.044-.182-.09-.765-.282-1.457-.899-.539-.48-.902-1.072-1.008-1.252-.106-.18-.011-.277.08-.367.082-.08.181-.208.272-.312.09-.104.12-.178.18-.297.06-.12.03-.222-.015-.312-.045-.09-.408-1.007-.56-1.38-.151-.372-.317-.312-.43-.312-.113 0-.244-.016-.366-.016-.121 0-.32.046-.487.226-.167.18-.634.62-.634 1.511 0 .891.645 1.751.735 1.872.09.12 1.27 2.047 3.14 2.805 1.089.472 1.519.509 2.065.427.332-.05 1.022-.418 1.166-.823.144-.405.144-.752.1-.825-.043-.072-.164-.116-.345-.206z"/>
                    </svg>
                    WhatsApp
                </a>
                <a href="mailto:?subject=<?= urlencode(Format::listTitle($item['title'])) ?>&body=https://ug.news/<?= $item['slug'] ?>" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                    Email
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
                                <span class="mr-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <?= number_format($list['view']) ?> <?= $lng->get('view') ?>
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <?= date("H:i", $list['publish_time']) ?>
                                </span>
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
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
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
        const svg = btn.querySelector('svg');
        svg.innerHTML = isSubscribed
            ? '<path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />'
            : '<path d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.964 8.964 0 0 0 2.3-5.542m3.155 6.852a3 3 0 0 0 5.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 0 0 3.536-1.003A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53" />';
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