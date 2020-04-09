<?php
use Helpers\Format;
$item = $data['item'];

$lng = $data['lng'];
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $item['title']; ?></span>
    </div>
</section>

<section class="">

    <div class="row">
        <div class="col-md-12">
            <div class="house_rules">
                <?=Format::getText($item['text'],100000)?>
            </div>
        </div>
    </div>
</section><!-- /.content -->
