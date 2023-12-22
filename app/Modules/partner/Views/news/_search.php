<?php
use Helpers\Csrf;
?>
<div class="row pad-top-15" style="<?php if(isset($data['page']) and $data['page']){ echo 'display:block;';} ?>">
    <div class="col-12"><!-- /.box -->
        <div class="box nopadbot">
            <div class="box-body">
                <form action="<?=\Helpers\Url::to('partner/news/index')?>" method="POST" >
                    <input type="hidden" value="<?= Csrf::makeToken();?>" name="csrf_token">
                    <div class="col-sm-1 pull-right nopad">
                        <div class="nopad">
                            <button type="submit" class="btn btncolor pull-right">
                                <?=$lng->get('Search')?>
                                <i class="fa fa-search afa"></i>
                            </button>
                        </div>
                    </div>
                <div class="col-sm-3 pull-right searchinp">
                    <input type="text" name="search" id="search" value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>" placeholder="<?=$lng->get('Detailed search')?>" class="form-control admininput pleft100">
                </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->