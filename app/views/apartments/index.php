<?php
use Helpers\Url;
use Helpers\Format;
use Models\ApartmentsModel;

?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row inner_background">
                <div class="col-sm-12">
                    <h1 class="page_title"><?=$data['page_title']?></h1>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="container-fluid">
                        <div class="row paddingTop20 paddingBottom40">
                            <div class="col-sm-3">
                                <div class="forum_sidebar apartment_search_box">
                                    <div class="forum_sidebar_title_mob">
                                        <div style="float: left"><?=$lng->get('Filter')?></div>
                                        <i class="fa fa-times forum_sidebar_close visible-xs" style="float: right"></i>
                                        <div class="clearBoth"></div>
                                    </div>
                                    <form action="" method="post">
                                        <button type="submit" class="btn apartment_button" style="width: 100%"><i class="fa fa-sync-alt"></i> <?=$lng->get('Show')?></button>
                                        <h4><?=$lng->get('Monthly rent')?>:</h4>
                                        <div class="apartment_search_box_subarea">
                                            <div class="range_values">
                                                <span id="spanRange1"><?=DEFAULT_CURRENCY_SHORT?><?=$postData['price_min']?></span>
                                                <span id="spanRange2" style="float:right;"><?=DEFAULT_CURRENCY_SHORT?><?=$postData['price_max']?><?=($postData['price_max']==$data['filter_max'])?'+':''?></span>
                                            </div>
                                            <div class="range">
                                                <div class="clearBoth"></div>
                                                <div class="range_line">
                                                    <div class="range_inputs">
                                                        <input name="price_min" type="range" value="<?=$postData['price_min']?>" min="0" max="<?=$data['filter_max']?>" id="range1" class="range1"/>
                                                        <input name="price_max" type="range" value="<?=$postData['price_max']?>" min="10" max="<?=$data['filter_max']?>" id="range2" class="range2"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearBoth"></div>
                                        <div class="default">
                                            <div class="apartment_search_box_subarea">
                                                <h4><?=$lng->get('Area')?>:</h4>
                                                <?php foreach ($data['location_list'] as $list):?>
                                                    <label class="checkbox"><?=$list['title_'.$data['def_language']]?>
                                                        <input type="checkbox" <?=(in_array($list['id'], $postData['countries']))?'checked':''?> name="countries[<?=$list['id']?>]" value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="apartment_search_box_subarea">
                                                <h4><?=$lng->get('Gender')?>:</h4>
                                                <?php foreach ($data['category_list'] as $list):?>
                                                    <label class="checkbox"><?=$list['title_'.$data['def_language']]?>
                                                        <input type="checkbox" <?=(in_array($list['id'], $postData['categories']))?'checked':''?> name="categories[<?=$list['id']?>]" value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="apartment_search_box_subarea">
                                                <h4><?=$lng->get('Room type')?>:</h4>
                                                <?php foreach ($data['room_types'] as $list):?>
                                                    <label class="checkbox"><?=$list['name']?>
                                                        <input type="checkbox" <?=(in_array($list['id'], $postData['room_types']))?'checked':''?> name="room_types[<?=$list['id']?>]" value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="apartment_search_box_subarea">
                                                <h4><?=$lng->get('Amenities')?>:</h4>
                                                <?php foreach ($data['feature_list'] as $list):?>
                                                    <label class="checkbox"><?=$list['title_'.$data['def_language']]?>
                                                        <input type="checkbox" <?=(in_array($list['id'], $postData['features']))?'checked':''?> name="features[<?=$list['id']?>]" value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <input type="hidden" name="filter"/>
                                        <button type="submit" class="btn apartment_button" style="width: 100%"><i class="fa fa-sync-alt"></i> <?=$lng->get('Show')?></button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-sm-9">
                                <div class="default filter_buttons">
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <form action="" method="post">
                                                <?=$lng->get('Order')?>:
                                                <select name="products_order" style="width:140px"onchange="this.form.submit()">
                                                    <option value="last"<?=($data['products_order']=='last')?'selected':''?>><?=$lng->get('Recent')?></option>
                                                    <option value="low_price" <?=($data['products_order']=='low_price')?'selected':''?>><?=$lng->get('Price: Low to High')?></option>
                                                    <option value="high_price" <?=($data['products_order']=='high_price')?'selected':''?>><?=$lng->get('Price: High to Low')?></option>
                                                </select>
                                                <a class="add_listing" style="display: none" href="apartments/add" title="<?=$lng->get('Add New Listing')?>"><?=$lng->get('Add New Listing')?></a>
                                            </form>
                                        </div>
                                        <div class="col-xs-4">
                                            <button class="forum_sidebar_toggle btn default_button"><?=$lng->get('Filter')?> <i class="fa fa-caret-down"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                        $list_count = count($data['list']);
                                        if($list_count>0):
                                        foreach ($data['list'] as $list):
                                    ?>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <a href="apartments/<?=$list['bed_id']?>/<?=Format::urlText($list['title_'.$data['def_language']])?>">
                                            <div class="apt_box">
                                                <div class="apt_img">
                                                    <img src="<?=Url::filePath()?>/<?=$list['image']?>" alt="" />
                                                    <?php
                                                        if($list['room_name']=='Quad'){
                                                            $bg_color = '#0064eb';
                                                        }elseif($list['room_name']=='Double'){
                                                            $bg_color = '#14cc94';
                                                        }elseif($list['room_name']=='Private'){
                                                            $bg_color = '#f2a024';
                                                        }else{
                                                            $bg_color = 'gray';
                                                        }
                                                        if($list['price']<=700){
                                                            $rating = '4.2';
                                                        }elseif($list['price']<=800){
                                                            $rating = '4.7';
                                                        }elseif($list['price']<=1000){
                                                            $rating = '4.9';
                                                        }else{
                                                            $rating = '5';
                                                        }
                                                    ?>
                                                    <div class="apt_room_type" style="background-color: <?=$bg_color?>"><?=$list['room_name']?> <?=$lng->get('room')?></div>
                                                </div>
                                                <div class="apt_box_body">
                                                    <h5><?=Format::listText($list['title_'.$data['def_language']],22)?></h5>
                                                    <div class="location"><i class="fas fa-map-marker-alt"></i> <?=Format::listText($list['address'], 30)?></div>
                                                    <div class="stars">
                                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                                        <span class="star_point"><?=$rating?></span>
                                                    </div>
                                                    <div class="info">
                                                        <div class="row">
                                                            <div class="col-xs-4">
                                                                <div class="sub_title"><?=$lng->get('Price')?></div>
                                                                <div class="text"><?=DEFAULT_CURRENCY_SHORT?><?=Format::full_digits($list['price'])?></div>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <div class="sub_title"><?=$lng->get('Model')?></div>
                                                                <div class="text"><?=Format::shortText(ApartmentsModel::getModelName($list['apt_model']),7)?></div>
                                                            </div><div class="col-xs-4">
                                                                <div class="sub_title"><?=$lng->get('Gender')?></div>
                                                                <div class="text"><?=ApartmentsModel::getCategoryName($list['category'])?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                        endforeach;
                                        endif;
                                    ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    var range1 = document.getElementById("range1");
    var range2 = document.getElementById("range2");
    var spanRange1 = document.getElementById("spanRange1");
    var spanRange2 = document.getElementById("spanRange2");
    range1.onchange = function() {
        spanRange1.innerHTML = '<?=DEFAULT_CURRENCY_SHORT;?>'+this.value;
    }
    range2.onchange = function() {
        if(this.value=='2000'){
            spanRange2.innerHTML = '<?=DEFAULT_CURRENCY_SHORT;?>'+this.value;
        }else {
            spanRange2.innerHTML = '<?=DEFAULT_CURRENCY_SHORT;?>'+this.value;
        }
    }
</script>
