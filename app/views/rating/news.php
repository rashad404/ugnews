<?php
use Helpers\Format;
use Helpers\Url;
?>


<h1 class="text-3xl font-bold text-gray-900 mb-6"><?=$lng->get('TOP News')?></h1>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <?=$lng->get('Rank')?>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <?=$lng->get('News title')?>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <?=$lng->get('View')?>
                    </th>
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
                        <td class="px-6 py-4">
                            <a href="<?=$list['slug']?>" class="text-sm text-gray-900 hover:text-blue-600 transition duration-150 ease-in-out">
                                <?=Format::listTitle($list['title'], 60)?>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?=number_format($list['view'])?>
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