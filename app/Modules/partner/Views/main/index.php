<?php

use Core\Language;
use Helpers\Csrf;
use Helpers\Format;
use Helpers\OperationButtons;
use Helpers\Url;
use Helpers\Session;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\AptStatsModel;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\CalendarModel;
use Modules\partner\Models\CustomersModel;
use Modules\partner\Models\RoomsModel;
use Modules\partner\Models\ShowingsModel;
use Modules\partner\Models\TenantsModel;
use Modules\partner\Models\BalanceModel;
use Modules\partner\Models\ApplicationsModel;
$lng = new Language();
$lng->load('user');



$user_role = Session::get('partner_session_role');

?>

<!-- Main content -->
<section class="content">


<div class="clearBoth"></div>
</section>



<script>
    function copyFunction() {
        const $temp = $("<input>");
        $("body").append($temp);
        const copyText = event.target.innerHTML;
        $temp.val(copyText).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Copied: '+copyText);
    }
</script>