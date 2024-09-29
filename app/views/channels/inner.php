<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
$subscribe_check = \Models\NewsModel::subscribeCheck($item['id']);
?>

<main class="bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="relative h-48 sm:h-64 md:h-80">
                <img src="<?=Url::uploadPath().$item['thumb']?>?a" alt="<?=$item['name']?>" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-6">
                    <h1 class="text-3xl font-bold text-white mb-2"><?=$item['name']?></h1>
                    <p class="text-sm text-gray-300 mb-2">https://ug.news/<?=strtolower($item['name_url'])?></p>
                    <div class="flex items-center justify-between">
                        <span class="text-white"><?=number_format($item['subscribers'])?> <?=$lng->get('subscribers')?></span>
                        <button 
                            id="subscribe_button" 
                            channel_id="<?=$item['id']?>" 
                            class="<?=($data['userId']>0)?'':'umodal_toggle'?> px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 <?=($subscribe_check===true)?'bg-gray-600 hover:bg-gray-700':''?>"
                        >
                            <i class="fas fa-<?=($subscribe_check===true)?'bell-slash':'bell'?> mr-2"></i>
                            <?=$lng->get(($subscribe_check===true)?'Subscribed':'Subscribe')?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <?php include_once 'app/views/site/news_include.php';?>
        </div>
    </div>
</main>

<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full m-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900" id="umodal_title"><?=$lng->get('Login')?></h2>
            <button class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeLoginModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <hr class="mb-4" />
        <div id="loginModalContent">
            <?php require $data['modal_url'];?>
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

document.getElementById('subscribe_button').addEventListener('click', function() {
    if (this.classList.contains('umodal_toggle')) return;
    const channelId = this.getAttribute('channel_id');
    fetch(`/api/subscribe/${channelId}`, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.innerHTML = `<i class="fas fa-${data.subscribed ? 'bell-slash' : 'bell'} mr-2"></i>${data.subscribed ? '<?=$lng->get('Subscribed')?>' : '<?=$lng->get('Subscribe')?>'}`;
                this.classList.toggle('bg-blue-600');
                this.classList.toggle('bg-gray-600');
            }
        });
});
</script>