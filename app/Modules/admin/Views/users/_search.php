
<div class="row pad-top-15" style="<?php if(isset($data['page']) and $data['page']){ echo 'display:block;';} ?>">
    <div class="col-xs-12"><!-- /.box -->
        <div class="box nopadbot">
            <div class="box-body">
                <form action="search" method="POST" >
                    <div class="col-sm-1 pull-right nopad">
                        <div class="nopad">
                            <button type="submit" class="btn btncolor pull-right">
	                            <?=$lang->get('Search')?>
                                <i class="fa fa-search afa"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-3 pull-right searchinp">
                        <input type="text" name="word" id="word" value="<?= isset($_POST['word']) ? $_POST['word'] : '' ?>" placeholder="<?=$lang->get('Detailed Search')?>" class="form-control admininput pleft100">
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
