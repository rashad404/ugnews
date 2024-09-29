<?php
use Helpers\Format;
use Helpers\Url;
?>

<main class="bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6"><?=$lng->get('TOP Channels')?></h1>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?=$lng->get('Channel')?></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?=$lng->get('Subscribers')?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $c=$data['startRow']+1; foreach ($data['list'] as $list): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $bgColor = match($c) {
                                        1 => 'bg-yellow-500',
                                        2 => 'bg-gray-400',
                                        3 => 'bg-yellow-700',
                                        default => 'bg-red-700'
                                    };
                                    ?>
                                    <span class="<?=$bgColor?> text-white text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        <?=$c?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php if (!empty($list['thumb'])): ?>
                                            <img class="h-10 w-10 rounded-full mr-3" src="<?=Url::filePath()?>/<?=$list['thumb']?>" alt="<?=$list['name']?>" />
                                        <?php endif; ?>
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="/<?=Format::urlTextChannel($list['name_url'])?>" class="hover:text-blue-600">
                                                <?=Format::listTitle($list['name'],30)?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?=number_format($list['subscribers'])?>
                                </td>
                            </tr>
                        <?php $c++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-center">
            <?php echo $data["pagination"]->pageNavigation('pagination')?>
        </div>
    </div>
</main>