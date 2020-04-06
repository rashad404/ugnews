<?php
$photos = $data["photos"];
?>

<div class="form-group" id="gallery-block">
    <div class="row " style="padding: 30px 10px">
        <h2>Qalereya</h2>
        <hr />
        <div class="form-group">
            <form action="<?= \Helpers\Url::to("admin/photos/upload")?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="image">Şəkil şəkil üçün başlıq</label>
                            <input type="text" class="form-control" id="title_az" name="title_az" placeholder="Basliq" maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="image">Şəkil seçin</label>
                        <div class="form-group">
                            <input type="file" id="image" name="image" class="form-control inline-block" required>
                        </div>
                        <input type="hidden" name="table_name" value="<?= $data["dataParams"]["cName"]?>">
                        <input type="hidden" name="row_id" value="<?= $data["result"]["id"] ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                        <input type="submit" name="submit" value="Yadda saxla" class="btn btn-success btn-sm" style="margin-top:20px">
                    </div>
                </div>
            </form>
        </div>
        <hr />
        <div class="clearfix"></div>

        <?php
            foreach ($photos as $photo){
                if($photo["status"]==1){
                    $status_btn = 'btn-success';
                    $status_text = 'Aktiv';
                    $status_title = 'Deaktiv et';
                }else{
                    $status_btn = 'btn-danger';
                    $status_text = 'Deaktiv';
                    $status_title = 'Aktiv et';

                }
                $status_btn = $photo["status"]==1?'btn-success':'btn-danger';
                ?>
                <div class="col-md-3" style="text-align: center;margin-bottom:20px">
                    <img src="<?= \Helpers\Url::filePath().$photo["thumb"]?>" class="img-responsive inline-block gallery-img-field">
                    <div class="clearfix"></div>
                    <?php if(!empty($photo['title_az'])){ ?>
                    <h5><?=$photo['title_az']?></h5>
                    <?php } else { ?>
                    <h5>Şəkil başlığı yoxdur</h5>
                    <?php } ?>
                    <a href="<?= \Helpers\Url::to("admin/photos/imagedelete/".$photo["id"])?>" class="btn btn-sm btn-danger gallery-button-text">Sil</a>
                    <a href="<?= \Helpers\Url::to("admin/photos/status/".$photo["id"])?>" title="<?= $status_title?>"  class="btn btn-sm <?= $status_btn?> gallery-button-text"><?= $status_text?></a>
                    <a href="<?= \Helpers\Url::to("admin/photos/position/".$photo["id"]."/up")?>" class="btn btn-success btn-sm gallery-button-text" title="Sol"><i class="fa fa-arrow-circle-left"></i></a>
                    <a href="<?= \Helpers\Url::to("admin/photos/position/".$photo["id"]."/down")?>" class="btn btn-success btn-sm gallery-button-text" title="Sağ"><i class="fa fa-arrow-circle-right"></i></a>
                </div>

                <?php
            }
        ?>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
    </div>
</div>
