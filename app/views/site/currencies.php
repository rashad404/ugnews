<main class="bg-gray-100 py-8"> 

    <section>
        <div class="container mx-auto px-4">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold text-gray-800"><?=$lng->get('Currencies')?></h1>
                <p class="text-lg text-gray-600 mt-2">
                    <?=date("d-m-Y") . ' ' . $lng->get('Exchange Rates')?>
                </p>
            </div>
            
            <!-- Currency List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($data['currencies'] as $currency): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                <?= $currency['name'] ?> (<?= $currency['code'] ?>)
                            </h2>
                        </div>
                        <div class="text-gray-600">
                            <p class="mb-2">
                                <span class="font-medium">Nominal:</span> <?= $currency['nominal'] ?>
                            </p>
                            <p>
                                <span class="font-medium"><?=$lng->get('Rate')?>:</span> <?= number_format($currency['value'], 4) ?> AZN
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
