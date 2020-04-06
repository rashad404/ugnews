<?php
use Models\FilterModel;
use Helpers\Session;
?>

    <div class="paddingTop20">
        <div class="left-sidebar default" id="left-sidebar">
            <h2 style="float: left"><?=$lng->get('Filter')?></h2><i class="fa fa-times mobile_filter_close visible-xs" style="float: right"></i>
            <div class="clearBoth"></div>
            <form action="" method="post">
                <?php FilterModel::getFilters(Session::get('cat'));?>

                <input name="filter" value="filter" type="hidden"/><br/><br/>
                <button type="submit" class="btn btn-primary"><?=$lng->get('Show')?></button>
                <a href="?reset_filter"><?=$lng->get('Reset filter')?></a>

            </form>
        </div>
    </div>