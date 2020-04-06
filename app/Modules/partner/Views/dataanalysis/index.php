<?php

use Models\LanguagesModel;
use Helpers\Url;

$lng = $data['lng'];
$defaultLang = LanguagesModel::getDefaultLanguage();
?>

<section class="content-header">
    <div class="headtext">
        <span><?= $lng->get('Data Analysis') ?></span>
    </div>
</section>

<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->




            <div class="row">
                <div class="col-xs-6 col-md-3">
                    <div class="half_box_with_title">
                        <div class="half_box_body">
                            <div class="half_box_title">
                                <?= $lng->get('Occupancy Rate')?>
                            </div><br/>
                            <div class="data_rate">
                                <?=$data['occupancy_rate']?> %
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="half_box_with_title">
                        <div class="half_box_body">
                            <div class="half_box_title">
                                <?= $lng->get('Leases')?>
                            </div><br/>
                            <div class="data_rate">
                                <?php
                                    if($data['leases']>$data['leases_previous']){
                                        $icon = 'long-arrow-alt-up';
                                    }elseif($data['leases']<$data['leases_previous']){
                                        $icon = 'long-arrow-alt-down';
                                    }else{
                                        $icon = 'minus';
                                    }
                                ?>
                                <?=$data['leases']?> <i class="fas fa-<?=$icon?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="half_box_with_title">
                        <div class="half_box_body">
                            <div class="half_box_title">
                                <?= $lng->get('Applications')?>
                            </div><br/>
                            <div class="data_rate">
                                <?php
                                if($data['applications']>$data['applications_previous']){
                                    $icon = 'long-arrow-alt-up';
                                }elseif($data['applications']<$data['applications_previous']){
                                    $icon = 'long-arrow-alt-down';
                                }else{
                                    $icon = 'minus';
                                }
                                ?>
                                <?=$data['applications']?> <i class="fas fa-<?=$icon?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="half_box_with_title">
                        <div class="half_box_body">
                            <div class="half_box_title">
                                <?= $lng->get('Showings')?>
                            </div><br/>
                            <div class="data_rate">

                                <?php
                                if($data['applications']>$data['showings_previous']){
                                    $icon = 'long-arrow-alt-up';
                                }elseif($data['showings']<$data['showings_previous']){
                                    $icon = 'long-arrow-alt-down';
                                }else{
                                    $icon = 'minus';
                                }
                                ?>
                                <?=$data['showings']?> <i class="fas fa-<?=$icon?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="half_box_with_title">
                        <div class="half_box_body">
                            <div class="half_box_title">
                                <?= $lng->get('Gender Statistics')?>
                            </div><br/>
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="half_box_with_title">
                        <div class="half_box_body">
                            <div class="half_box_title">
                                <?= $lng->get('Earnings by Beds')?>
                            </div><br/>
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="half_box_with_title">
                        <div class="half_box_body">
                            <div class="half_box_title">
                                <?= $lng->get('Payments')?>
                            </div><br/>
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>


            </div>





        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->


<link rel="stylesheet" href="<?=Url::templatePartnerPath()?>mdb/css/mdb.min.css">

<script type="text/javascript" src="<?=Url::templatePartnerPath()?>mdb/js/mdb.min.js"></script>

<script>


    //line
    var oReq = new XMLHttpRequest(); // New request object
    oReq.onload = function() {

        var json = JSON.parse(this.responseText);
            var ctxL = document.getElementById("lineChart").getContext('2d');
            var myLineChart = new Chart(ctxL, {
                type: 'line',
                data: {
                    labels: json.list[0].labels,
                    datasets: [{
                        label: "Payments ($)",
                        data: json.list[0].payments,
                        backgroundColor: [
                            'rgba(105, 0, 132, .2)',
                        ],
                        borderColor: [
                            'rgba(200, 99, 132, .7)',
                        ],
                        borderWidth: 2
                    }
                    ]
                },
                options: {
                    responsive: true
                }
            });
    };
    oReq.open("get", "/partner/dataanalysis/getPayments", true);
    oReq.send();



    var oReq = new XMLHttpRequest(); // New request object
    oReq.onload = function() {
        var json = JSON.parse(this.responseText);
            var ctxP = document.getElementById("genderChart").getContext('2d');
            var myPieChart = new Chart(ctxP, {
                plugins: [ChartDataLabels],
                type: 'pie',
                data: {
                    labels: [
                        json.list[0].name,
                        json.list[1].name,
                        json.list[2].name],
                    datasets: [{
                        data: [
                            json.list[0].count,
                            json.list[1].count,
                            json.list[2].count],
                        backgroundColor: [
                            json.list[0].backgroundColor,
                            json.list[1].backgroundColor,
                            json.list[2].backgroundColor],
                        hoverBackgroundColor: [
                            json.list[0].hoverBackgroundColor,
                            json.list[1].hoverBackgroundColor,
                            json.list[2].hoverBackgroundColor
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            boxWidth: 10
                        }
                    },
                    plugins: {
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => {
                                    sum += data;
                                });
                                let percentage = (value * 100 / sum).toFixed(2) + "%";
                                return percentage;
                            },
                            color: 'white',
                            labels: {
                                title: {
                                    font: {
                                        size: '16'
                                    }
                                }
                            }
                        }
                    }
                }
            });
    };
    oReq.open("get", "/partner/dataanalysis/getGenderData", true);
    oReq.send();


    //bar
    var oReq = new XMLHttpRequest(); // New request object
    oReq.onload = function() {
        var json = JSON.parse(this.responseText);

            var ctxB = document.getElementById("barChart").getContext('2d');
            var myBarChart = new Chart(ctxB, {
                type: 'bar',
                data: {
                        labels: [
                            json.list[0].name,
                            json.list[1].name,
                            json.list[2].name],
                        datasets: [{
                            label: 'Earnings ($)',
                            data: [
                                json.list[0].count,
                                json.list[1].count,
                                json.list[2].count],
                            backgroundColor: [
                                json.list[0].backgroundColor,
                                json.list[1].backgroundColor,
                                json.list[2].backgroundColor],
                            borderColor: [
                                json.list[0].hoverBackgroundColor,
                                json.list[1].hoverBackgroundColor,
                                json.list[2].hoverBackgroundColor
                            ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
    };
    oReq.open("get", "/partner/dataanalysis/getBedEarningsData", true);
    oReq.send();

    //pie






</script>