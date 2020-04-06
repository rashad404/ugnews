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

                        <select name="action">
                            <?php foreach ($data['actions'] as $key => $value):
                                if ($postData['action'] == $key) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                ?>
                                <option <?= $selected ?> value="<?= $key ?>"><?= $value[2] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="submit" name="submit" value="<?=$data['lng']->get("Show");?>"/><br/><br/>
                        <div>
                            <span style="font-weight: bold;"><?=$data['lng']->get("Total")?>:</span> <span style="color:red;"><?=$data['sum_amount']?> <?=DEFAULT_CURRENCY?></span>
                        </div>
                        <table class="table table-striped table-responsive">
                            <thead>
                            <tr>
                                <th class="width-20">#</th>
                                <th class="width-20"><?=$data['lng']->get("Mobile number");?></th>
                                <th class="width-20"><?=$data['lng']->get("Action");?></th>
                                <th class="width-20"><?=$data['lng']->get("Info");?></th>
                                <th class="width-20"><?=$data['lng']->get("Amount");?></th>
                                <th class="width-20"><?=$data['lng']->get("Time");?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data["rows"]  as $row){ ?>
                                <tr>
                                    <td class="admin-arrow-box width-20"><?= $row["id"]?></td>
                                    <td class="admin-arrow-box width-20"><?= $row["phone"]?></td>
                                    <td class="admin-arrow-box width-20">
                                        <?php if(!empty($row["sub_action"])){?>
                                            <?= $data['lng']->get("Logs-".$row["sub_action"])?>
                                        <?php }else{?>
                                            <?= $data['lng']->get("Logs-".$row["action"])?>
                                        <?php }?>
                                    </td>
                                    <?php if(!empty($row["object_phone"])){?>
                                        <td class="admin-arrow-box width-20"><?= $row["object_phone"]?></td>
                                    <?php }else{?>
                                        <td class="admin-arrow-box width-20"><?= $row["object"]?></td>
                                    <?php }?>
                                    <td class="admin-arrow-box width-20"><?= $row["amount"]?> <?=DEFAULT_CURRENCY?></td>
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