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
                        <?=$data['lng']->get("Search");?>: <input type="text" name="search" value="<?=$data['postData']['search']?>" />

                        <input type="submit" name="submit" value="<?=$data['lng']->get("Show");?>"/><br/><br/>
                        <div>
                            <span style="font-weight: bold;"><?=$data['lng']->get("Total")?>:</span> <span style="color:red;"><?=$data['sum_amount']?> <?=DEFAULT_CURRENCY?></span>
                        </div>
                        <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th class="width-20">#</th>
                                <th class="width-20"><?=$data['lng']->get("Mobile number");?></th>
                                <th class="width-20"><?=$data['lng']->get("Amount");?></th>
                                <th class="width-20"><?=$data['lng']->get("Step");?></th>
                                <th class="width-20"><?=$data['lng']->get("Time");?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["rows"]  as $row){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20"><?= $row["id"]?></td>
                                    <td class="admin-arrow-box width-20"><?= $row["phone"]?></td>
                                    <td class="admin-arrow-box width-20"><?= $row["amount"]?> <?=DEFAULT_CURRENCY?></td>
                                    <td class="admin-arrow-box width-20"><?= $row["step"]?></td>
                                    <td class="admin-arrow-box width-20"><?= date("d-m-Y H:i:s",$row["time"])?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php echo $data["pagination"]->pageNavigation('pagination')?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>