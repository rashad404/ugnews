<main class="bg-gray-100">
    <section class="py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                <?=$lng->get("Make Your Voice Heard")?>
            </h1>
            <p class="mt-5 text-xl text-gray-500">
                <?=$lng->get("Create your own news channel and make your voice heard by millions")?>
            </p>
            <div class="mt-8">
                <form action="login/partner+channels+index">
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <?=$lng->get("Let's Start")?>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="bg-indigo-700 py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <p class="text-xl text-indigo-100">
                <?=$lng->get("CreateChannelText1")?>
            </p>
        </div>
    </section>

    <section class="py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Ug.news <?=$lng->get("CreateChannelText2")?>
                    </h2>
                    <p class="mt-3 max-w-3xl text-lg text-gray-500">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    </p>
                </div>
                <div class="mt-8 lg:mt-0">
                    <div class="flex justify-center lg:justify-end">
                        <img class="w-64 h-64" src="<?=\Helpers\Url::templatePath()?>img/partner_logos/ug_news.svg" alt="logo"/>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <p class="text-xl text-gray-500">
                <?=$lng->get("CreateChannelText3")?>
            </p>
        </div>
    </section>
</main>