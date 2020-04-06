<section class="content-header">
    <div class="headtext">
        <span><?= $data["page_title"]; ?></span>
    </div>
</section>
<section class="content">
    <div class="row pad-top-15">
        <div class="col-xs-12"><!-- /.box -->
            <form action="" method="GET">
                <div class="box">
                    <div class="box-body">
                        <?=$data['lng']->get("From");?>: <input type="date" name="dateFrom" value="<?=$data['postData']['dateFrom']?>" />
                        <?=$data['lng']->get("To");?>: <input type="date" name="dateTo" value="<?=$data['postData']['dateTo']?>" />

                        <input type="submit" name="submit" value="<?=$data['lng']->get("Show");?>"/><br/><br/>

                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th class="width-20"><?=$data['lng']->get("Action");?></th>
                                    <th class="admin-arrow-box width-20"><?=$data['lng']->get("Amount");?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2" class="width-20" style="text-align: center"><?=$data['lng']->get("Million");?></th>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Code sales");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['million_code']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Balance sales");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['million']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Total sales");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['million_total']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="width-20" style="text-align: center"><?=$data['lng']->get("E-manat");?></th>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Code sales");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['emanat_code']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Balance sales");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['emanat']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Total sales");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['emanat_total']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="width-20" style="text-align: center"><?=$data['lng']->get("Expressbank");?></th>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Withdrawals");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['withdrawals']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="width-20" style="text-align: center"><?=$data['lng']->get("Fees");?></th>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Transfer fees");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['transfer_fee']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Withdrawal fees");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['withdraw_fee']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Million Balance fees");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['million_balance_fee']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("E-manat Balance fees");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['emanat_balance_fee']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                                <tr>
                                    <td class="width-20"><?=$data['lng']->get("Express Card Order fees");?></td>
                                    <td class="admin-arrow-box width-20"><?=$data['stats']['express_card_order_fee']?> <?=DEFAULT_CURRENCY?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>