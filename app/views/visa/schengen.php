<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h3><i class="fa fa-id-card"></i> <?=$lng->get('Schengen visa')?> <?=$lng->get('support')?></h3>

        </div>
        <div class="container">
            <div class="row paddingTop40 paddingBottom40">

                <div class="col-md-4">
                    <?php $selected_menu='schengen'; include 'menu.php'; ?>
                </div>

                <div class="col-md-8">
                    <div class="visa_content">
                        <div class="content_part">
                            <h5>Viza üçün tələb olunan sənədlər</h5>
                            <p>
                                <ul class="digit_style">
                                    <li><span>Xarici passport</span></li>
                                    <li><span>Gediş-gəliş biletinin bronu</span></li>
                                    <li><span>İş yerindən/Təhsil müəssisəsindən arayış</span></li>
                                    <li><span>Bankdan arayış/Yaşayış xərclərinin ödənəcəyinin sübutu</span></li>
                                    <li><span>Son 6 ay üzrə bank hesabınızda olan əməliyyatların çıxarışı</span></li>
                                    <li><span>Mehmanxana sifarişinin təsdiqi/Dəvət məktubu</span></li>
                                    <li><span>Şəkil 3.5x4.5 sm</span></li>
                                    <li><span>Ərizə forması</span></li>
                                    <li><span>Şəxsiyyət vəsiqəsi və passportun surəti</span></li>
                                </ul>
                                <h4>Lazım ola biləcək digər sənədlər</h4>
                                <ul class="digit_style">
                                    <li><span>Nikah şəhadətnaməsi, 18 yaşına çatmamış uşağınızın (və ya uşaqlarınızın) doğum şəhadətnaməsi</span></li>
                                    <li><span>Orta və ya ali təhsil müəssisələrində oxuyan şagird və tələbələr viza müraciəti etdikləri zaman təhsil aldıqları müəssisədən həmin yerdə təhsil almaları barədə arayış təqdim etməlidirlər</span></li>
                                    <li><span>Macarıstan, Yunanıstan səfirlikləri Elektron Hökumət Portalından rəsmi iş yeri barədə çıxarış tələb edir</span></li>
                                    <li><span>Adınıza daşınan və ya daşınmaz əmlakların olması barədə (kupça) çıxarışlar (olması üstünlükdür).</span></li>
                                </ul>
                                Qeyd:
                                Passport və şəxsiyyət vəsiqələrin surətləri istisna olmaqla, digər sənədlər ingilis dilində və ya ingilis dilində tərcümə olunmuş və notarial qaydada təsdiq olunmuş şəkildə olunmalıdır.
                            </p>
                        </div>
                        <div class="content_part">
                            <h5>Şəklin çəkilmə qaydası</h5>
                            <ul>
                                <li>Şəklin çəkilmə müddəti 6 aydan çox olmamalıdır</li>
                                <li>3.5 sm x 4.5 sm ölçüsündə</li>
                                <li>İşığı əks etdirməyən lövhə qarşısında</li>
                                <li>Şəklin fonu açıq rəngdə olmalıdır</li>
                                <li>Şəklin əsas hissəsini baş və çiyindən yuxarı nahiyə tutmalıdır, belə ki, şəkilin 70-80%-ni üz nahiyəsi təşkil etməlidir.</li>
                            </ul>
                        </div>
                        <div class="content_part">
                            <h5>Əməliyyat müddəti</h5>
                            <p>
                                Sizin müraciətinizin cavabı və ya sizə viza verilmə prosesi 3-14 iş günü müddətində olur.
                            </p>
                        </div>
                        <div class="content_part">
                            <h5>Xüsusi qeydlər</h5>
                            <ul>
                                <li>Fly.az şirkəti turistlərə vizanın verilməsinə tam zəmanət vermir.</li>
                                <li>Vizanın verilməsi bir başa Konsulluq xidmətinin ixtiyarındadır.</li>
                                <li>Vizanın verilməsi və ya imtina ediməsi konsulluq tərəfindən qərara alınır.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
