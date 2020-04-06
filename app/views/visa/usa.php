<?php
use Helpers\Url;
use Helpers\Format;
use Models\ProductsModel;
?>

<main class="main">
    <section xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid inner_background">
            <h3><i class="fa fa-id-card"></i> <?=$lng->get('USA visa')?> <?=$lng->get('support')?></h3>

        </div>
        <div class="container">
            <div class="row paddingTop40 paddingBottom40">

                <div class="col-md-4">
                    <?php $selected_menu='usa'; include 'menu.php'; ?>
                </div>

                <div class="col-md-8">
                    <div class="visa_content">
                        <div class="content_part">
                            <h5>Viza üçün tələb olunan sənədlər</h5>
                            <p>
                                <ul class="digit_style">
                                    <li><span>Xarici passport</span></li>
                                    <li><span>Viza rüsumunun ödənilməsini təsdiq edən sənəd</span></li>
                                    <li><span>Gediş-gəliş biletinin bronu</span></li>
                                    <li><span>İş yerindən/Təhsil müəssisəsindən arayış</span></li>
                                    <li><span>Bankdan arayış/Yaşayış xərclərinin ödənəcəyinin sübutu</span></li>
                                    <li><span>Mehmanxana sifarişinin təsdiqi/Dəvət məktubu</span></li>
                                    <li><span>Şəkil 5x5 (elektron)</span></li>
                                    <li><span>Doldurulmuş Ərizə forması (DS160)</span></li>
                                    <li><span>Şəxsiyyət vəsiqəsinin surəti</span></li>
                                </ul>
                            </p>
                        </div>
                        <div class="content_part">
                            <h5>Şəklin çəkilmə qaydası</h5>
                            <ul>
                                <li>Şəklin çəkilmə müddəti 6 aydan çox olmamalıdır</li>
                                <li>50-50 mm ölçüsündə</li>
                                <li>İşığı əks etdirməyən lövhə qarşısında</li>
                                <li>Şəklin fonu açıq rəngdə olmalıdır</li>
                                <li>Şəklin əsas hissəsini baş və çiyindən yuxarı nahiyə tutmalıdır, belə ki, şəkilin 70-80%-ni üz nahiyəsi təşkil etməlidir.</li>
                            </ul>
                        </div>
                        <div class="content_part">
                            <h5>Əməliyyat müddəti</h5>
                            <p>ABŞ Səfirliyindən Konsulluq görüşündən dərhal sonra Siz müraciətinizlə bağlı cavabı biləcəksiniz.</p>
                            <p>Müsbət cavab olduğu təqdirdə, 3 iş günü ərzində vizanız hazır olur.</p>
                            <p>İstisna hallarda, Səfirlik sizin müraciətə baxılması üçün "İnzibati prosses" həyata keçirə bilər.</p>
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
