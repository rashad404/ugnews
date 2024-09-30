<?php
use Helpers\Url;
use Models\CountryModel;
?>
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6 md:justify-start md:space-x-10">
            <div class="flex justify-start lg:w-0 lg:flex-1">
                <a href="/">
                    <img class="h-8 w-auto sm:h-10" src="<?=Url::templatePath()?>/img/partner_logos/<?=$_PARTNER['header_logo']?>" alt="<?=PROJECT_NAME?> logo"/>
                </a>
            </div>
            <div class="-mr-2 -my-2 md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <nav class="hidden md:flex space-x-10">
                <?php foreach (array_slice($data['menus'], 0, 5) as $menu): ?>
                    <a href="<?=$menu['url']?>" class="text-base font-medium text-gray-500 hover:text-gray-900">
                        <?=$menu['title_' . $data['def_language']]?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
                <?php if ($userId > 0): ?>
                    <a href="/user_panel/profile" class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">
                        <?= $userInfo['first_name'] ?>
                    </a>
                    <a href="/user_panel/logout" class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <?= $lng->get('Logout') ?>
                    </a>
                <?php else: ?>
                    <a href="/login" class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">
                        <?= $lng->get('Sign in') ?>
                    </a>
                    <a href="/register" class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <?= $lng->get('Sign up') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on mobile menu state -->
    <div x-show="mobileMenuOpen" class="md:hidden" x-cloak>
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <?php foreach ($data['menus'] as $menu): ?>
                <a href="<?=$menu['url']?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <?=$menu['title_' . $data['def_language']]?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="flex items-center px-5">
                <?php if ($userId > 0): ?>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?= $userInfo['first_name'] ?></div>
                        <div class="text-sm font-medium text-gray-500"><?= $userInfo['email'] ?></div>
                    </div>
                <?php else: ?>
                    <a href="/login" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        <?= $lng->get('Sign in') ?>
                    </a>
                    <a href="/register" class="ml-4 block px-3 py-2 rounded-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <?= $lng->get('Sign up') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>