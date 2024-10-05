<?php
use Models\FilterModel;
use Helpers\Session;
?>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?= $lng->get('Filter') ?>
        </h3>
    </div>
    <div class="border-t border-gray-200">
        <form action="" method="post" class="divide-y divide-gray-200">
            <?php FilterModel::getFilters(Session::get('category_id')); ?>
            <div class="px-4 py-4 sm:px-6">
                <button type="submit" name="filter" value="filter" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <?= $lng->get('Show') ?>
                </button>
                <a href="?reset_filter" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <?= $lng->get('Reset filter') ?>
                </a>
            </div>
        </form>
    </div>
</div>