<?php

use Models\LanguagesModel;
use Helpers\Url;
use Modules\partner\Models\TenantsModel;

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
            <canvas id="pieChart"></canvas>






        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->


<link rel="stylesheet" href="<?=Url::templatePartnerPath()?>mdb/css/mdb.min.css">

<script type="text/javascript" src="<?=Url::templatePartnerPath()?>mdb/js/mdb.min.js"></script>



<script>

    $(function () {
        var data = [
            {
                value: 300,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "Red"
            },
            {
                value: 50,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Green"
            },
            {
                value: 100,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "Yellow"
            }
        ];

        var option = {
            responsive: true,
        };

        // Get the context of the canvas element we want to select
        var ctx = document.getElementById("pieChart").getContext('2d');
        var myPieChart = new Chart(ctx).Pie(data,option);

        $("#myChart").on('mouseleave', function (){
            myPieChart.showTooltip(myPieChart.segments, true);
        });
    });



</script>