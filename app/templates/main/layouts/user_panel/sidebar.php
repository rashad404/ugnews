<?php
use Helpers\Url;
use Models\BankCardsModel;
$menu_list[] = ['name'=>'Payments', 'url'=>'user/payments','icon'=>'payment'];
$menu_list[] = ['name'=>'Logs', 'url'=>'user/logs/transfer','icon'=>'transaction'];
$menu_list[] = ['name'=>'Add funds', 'url'=>'user/add_funds','icon'=>'add-payment'];
$menu_list[] = ['name'=>'Transfer', 'url'=>'user/transfer','icon'=>'transfer-money'];
$menu_list[] = ['name'=>'Withdrawal', 'url'=>'user/withdrawal','icon'=>'funds'];
$menu_list[] = ['name'=>'Statements', 'url'=>'user/statements','icon'=>'progress-report'];
$menu_list[] = ['name'=>'My cards', 'url'=>'user/my_cards','icon'=>'cards'];
$menu_list[] = ['name'=>'Order card', 'url'=>'user/order_card','icon'=>'cards'];
$menu_list[] = ['name'=>'Profile', 'url'=>'user/profile','icon'=>'user'];
$menu_list[] = ['name'=>'Logout', 'url'=>'user/logout','icon'=>'sign-out-alt'];

?>
<div class="col-lg-3 col-md-4">
    <div class="sidebar _background-white">
        <div class="sidebar__block">
            <ul class="nav nav-tabs sidebar__tabs" role="tablist">
                <?php foreach ($menu_list as $menu): ?>


                    <?php if($menu['name']=='My cards' or $menu['name']=='Order card'){?>
                        <li class="sidebar__item _sidebar__item-padding-top">
                    <?php }else{ ?>
                        <li class="sidebar__item">
                    <?php } ?>

                            <a class="sidebar__link" href="<?=$menu['url']?>" role="tab">
                                <svg class="sidebar__icon" viewBox="0 0 100 100">
                                    <use xlink:href="<?= Url::templatePath()?>sprite/symbol/sprite.svg#<?=$menu['icon']?>" class="icon"></use>
                                </svg>
                                <?=$lng->get($menu['name'])?>
                            </a>
                        </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>