<main class="bg-gradient-to-br from-blue-50 to-purple-100 py-12">
    <section class="container mx-auto px-4">
        <!-- Page Title -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-gray-800 mb-2">
                <?=$lng->get('Currency Exchange Rates')?>
            </h1>
            <p class="text-xl text-gray-600">
                <?=$lng->get(date('F')) . date(" d, Y") . ' | ' . $lng->get('Live Rates')?>
            </p>
        </div>

        <!-- Search and Filter -->
        <div class="mb-8 max-w-md mx-auto">
            <div class="relative">
                <input type="text" id="currency-search" placeholder="<?=$lng->get('Search currency')?>..." class="w-full px-4 py-2 rounded-full border-2 border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="absolute right-3 top-2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
            </div>
        </div>
        
        <!-- Currency List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="currency-grid">
            <?php foreach ($data['currencies'] as $currency): ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 currency-card" data-currency="<?= strtolower($currency['name'] . ' ' . $currency['code']) ?>">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-800">
                                <?= $currency['name'] ?>
                            </h2>
                            <span class="text-lg font-semibold text-blue-600"><?= $currency['code'] ?></span>
                        </div>
                        <div class="text-gray-600">
                            <p class="mb-2 flex justify-between">
                                <span class="font-medium"><?=$lng->get('Nominal')?>:</span>
                                <span><?= $currency['nominal'] ?></span>
                            </p>
                            <p class="flex justify-between items-center">
                                <span class="font-medium"><?=$lng->get('Rate')?>:</span>
                                <span class="text-2xl font-bold text-green-600"><?= number_format($currency['value'], 4) ?> AZN</span>
                            </p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('currency-search');
    const currencyGrid = document.getElementById('currency-grid');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const currencyCards = currencyGrid.getElementsByClassName('currency-card');

        Array.from(currencyCards).forEach(card => {
            const currencyText = card.dataset.currency;
            if (currencyText.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>