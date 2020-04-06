
<div class="col-lg-2 col-md-12">

    <div class="row half_box half_box_orange">
        <div class="col-sm-12">
            <div>
                <ul class="right_block">
                    <li><a href="../../balance/add_charge/<?= $item["id"];?>"><?=$lng->get('Add Charge')?></a></li>
                    <li><a href="../../balance/add_credit/<?= $item["id"];?>"><?=$lng->get('Add Credit')?></a></li>
                    <li><a href="../../balance/add_receipt/<?= $item["id"];?>"><?=$lng->get('Add Receipt')?></a></li>
                    <li><a target="_blank" href="../view_portal/<?= $item["id"];?>"><?=$lng->get('View Online Portal')?></a></li>
                    <li><a target="_blank" href="../update/<?= $item["id"];?>"><?=$lng->get('Edit info')?></a></li>
                    <li><a href="../view/<?= $item["id"];?>#sms_history"><?=$lng->get('Sms History')?></a></li>
                    <li><a href="../../leases/view/<?= $item["id"];?>"><?=$lng->get('Show Lease')?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>