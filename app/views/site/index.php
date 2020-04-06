<?php
use Helpers\Url;
use Models\ProductsModel;
use Helpers\Format;
use Models\TextsModel;
?>

<main class="main">

    <?php
    $company_list[] = ['name'=>'NAR Housing', 'logo'=> 'nar_housing.png', 'url'=>'https://narhousing.com', 'info'=>'Co-living, Student Housing'];
    $company_list[] = ['name'=>'WebsiteCA', 'logo'=> 'websiteca.jpg', 'url'=>'https://websiteca.com', 'info'=>'Website Development, Applications'];
    $company_list[] = ['name'=>'Lord Housing', 'logo'=> 'lord_housing.jpg', 'url'=>'https://lordhousing.com', 'info'=>'Student Housing Company'];


    ?>
<!--    Why Us?-->
    <div class="page_title paddingTop40 paddingBottom40">
        <h2>
            <?=$lng->get('Our Community Businesses')?>
        </h2>
    </div>
    <div class="container paddingBottom20">
        <div class="row">
            <?php foreach($company_list as $List):?>
            <div class="col-sm-4">
                <div class="why_us">
                    <img src="<?=Url::templatePath()?>img/partner_logos/<?=$List['logo']?>" alt="<?=$List['name']?>"/>
                    <div class="why_us_body">
                        <h5 class="sub-title">
                            <a href="<?=$List['url']?>" target="_blank"><?=$lng->get($List['name'])?></a>
                        </h5>
                        <div class="why_us_text">
                            <?=$lng->get($List['info'])?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>

        </div>
        </div>
    </div>

</main>
