<?php
use Models\LanguagesModel;
use Modules\partner\Models\ApplicationsModel;
use Helpers\Format;
use Helpers\Features;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\BedsModel;
$params = $data['params'];
$item = $data['item'];
$user_info = $data['user_info'];

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="header_info">
        <a href="../index"><?=$params['title']?></a> / <span style="font-weight: bold"><?= $item["first_name"];?> <?= $item["last_name"];?></span><br/>
    </div>
    <div>

    </div>
</section>

<section class="content">
    <div class="col-lg-10 col-md-12">

        <div class="row">

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Applied for')?></div>
                    <div class="half_box_body">
                        <ul>
                            <table class="default_vertical">
                                <tr>
                                    <td><?=$lng->get('Apartment')?>:</td>
                                    <td><?= ApartmentsModel::getName($item["apt_id"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Room')?>:</td>
                                    <td><?= RoomsModel::getName($item["room_id"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Bed')?>:</td>
                                    <td><?= BedsModel::getName($item["bed_id"])?></td>
                                </tr>
                                <tr>
                                    <td><?=$lng->get('Rent price')?>:</td>
                                    <td><?= DEFAULT_CURRENCY_SHORT.$item["price"]?></td>
                                </tr>
                            </table>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Personal Info')?></div>
                    <div class="half_box_body">

                        <table class="default_vertical">
                            <tr>
                                <td><?=$lng->get('First Name')?>:</td>
                                <td><?= $user_info["first_name"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Last Name')?>:</td>
                                <td><?= $user_info["last_name"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Phone')?>:</td>
                                <td><?= Format::phoneNumber($user_info["phone"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('E-mail')?>:</td>
                                <td><?= $user_info["email"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('SSN')?>:</td>
                                <td><?= $item["ssn"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Driver License')?>:</td>
                                <td><?= $item["dl"];?> <?= ApplicationsModel::getStateCode($item["dl_state"])?></td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Current Residential info')?></div>
                    <div class="half_box_body">
                        <table class="default_vertical">
                            <tr>
                                <td><?=$lng->get('Address')?>:</td>
                                <td><?= $item["current_address"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('City')?>:</td>
                                <td><?= $item["current_city"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('State')?>:</td>
                                <td><?= ApplicationsModel::getStateCode($item["current_state"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Zip code')?>:</td>
                                <td><?= $item["current_zip"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Country')?>:</td>
                                <td><?= Features::getCountries($item["current_country"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Rent')?>:</td>
                                <td><?=DEFAULT_CURRENCY_SHORT.$item["current_rent"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Duration')?>:</td>
                                <td><?=$item['current_month_from'];?>/<?=$item['current_year_from'];?> - <?=$item['current_month_to'];?>/<?=$item['current_year_to']?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Landlord name')?>:</td>
                                <td><?= $item["current_landlord_name"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Landlord phone')?>:</td>
                                <td><?= Format::phoneNumber($item["current_landlord_phone"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Landlord email')?>:</td>
                                <td><?= $item["current_landlord_email"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Move-out reason')?>:</td>
                                <td><?= $item["current_reason"]?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Previous Residential info')?></div>
                    <div class="half_box_body">
                        <table class="default_vertical">
                            <tr>
                                <td><?=$lng->get('Address')?>:</td>
                                <td><?= $item["previous_address"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('City')?>:</td>
                                <td><?= $item["previous_city"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('State')?>:</td>
                                <td><?= ApplicationsModel::getStateCode($item["previous_state"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Zip code')?>:</td>
                                <td><?= $item["previous_zip"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Country')?>:</td>
                                <td><?= Features::getCountries($item["previous_country"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Rent')?>:</td>
                                <td><?=DEFAULT_CURRENCY_SHORT.$item["previous_rent"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Duration')?>:</td>
                                <td><?=$item['previous_month_from'];?>/<?=$item['previous_year_from'];?> - <?=$item['previous_month_to'];?>/<?=$item['previous_year_to']?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Landlord name')?>:</td>
                                <td><?= $item["previous_landlord_name"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Landlord phone')?>:</td>
                                <td><?= Format::phoneNumber($item["previous_landlord_phone"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Landlord email')?>:</td>
                                <td><?= $item["previous_landlord_email"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Move-out reason')?>:</td>
                                <td><?= $item["previous_reason"]?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Employment / Income info')?></div>
                    <div class="half_box_body">
                        <table class="default_vertical">
                            <tr>
                                <td><?=$lng->get('Address')?>:</td>
                                <td><?= $item["employer_address"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('City')?>:</td>
                                <td><?= $item["employer_city"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('State')?>:</td>
                                <td><?= ApplicationsModel::getStateCode($item["employer_state"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Zip code')?>:</td>
                                <td><?= $item["employer_zip"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Country')?>:</td>
                                <td><?= Features::getCountries($item["employer_country"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Salary')?>:</td>
                                <td><?=DEFAULT_CURRENCY_SHORT.$item["salary"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Position')?>:</td>
                                <td><?=$item["position"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Duration')?>:</td>
                                <td><?=$item['worked_month_from'];?>/<?=$item['worked_year_from'];?> - <?=$item['worked_month_to'];?>/<?=$item['worked_year_to']?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Supervisor name')?>:</td>
                                <td><?= $item["employer_name"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Supervisor phone')?>:</td>
                                <td><?= Format::phoneNumber($item["employer_phone"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Supervisor email')?>:</td>
                                <td><?= $item["employer_email"]?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Extra Annual income')?>:</td>
                                <td><?= DEFAULT_CURRENCY_SHORT.$item["extra_income"]?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="half_box_with_title">
                    <div class="half_box_title"><?=$lng->get('Additional Info')?></div>
                    <div class="half_box_body">

                        <table class="default_vertical">
                            <tr>
                                <td><?=$lng->get('Smoking')?>:</td>
                                <td><?= Features::getSmoke($item["smoking"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Animals/Pets')?>:</td>
                                <td><?= Features::getAnimals($item["animals"])?></td>
                            </tr>
                            <tr>
                                <td><?=$lng->get('Notes')?>:</td>
                                <td><?= $item["note"]?></td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>




        </div>


    </div>

</section><!-- /.content -->