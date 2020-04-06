<?php
use Models\LanguagesModel;

$item = $data["item"];
$lng = $data['lng'];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();
$user_info = $data['user_info'];
?>
<script>
    $(function() {
        // $('.ui.dropdown').dropdown();
        $('.ui.dropdown').dropdown('set selected',<?=$features?>);
    });
</script>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form_box">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="row half_box half_box_orange">
                    <div class="col-sm-10">
                        <h3>Create A New Maintenance Request</h3>
                        <div class="input_box">
                            <label class="radio_label"><strong>Is this issue urgent?</strong> <span style="color:red;">*</span> </label><br/>
                            <label class="radio_label"><input type="radio" name="urgent" value="1"> Yes</label><br/>
                            <label class="radio_label"><input type="radio" name="urgent" checked="checked" value="2"> No</label><br/>
                        </div>
                    </div>

                    <div class="col-sm-10">
                        <div class="input_box">
                            <label class="radio_label"><strong>Is this issue actively causing property damage or a threat to personal safety?</strong> <span style="color:red;">*</span></label><br/>
                            <label class="radio_label"><input type="radio" name="safety" value="1"> Yes</label><br/>
                            <label class="radio_label"><input type="radio" name="safety" checked="checked" value="2"> No</label><br/>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="input_box">
                            <label><strong>To resolve the issue as quickly as possible, do we have permission to enter the residence?</strong> <span style="color:red;">*</span></label><br/>
                            <label class="radio_label"><input type="radio" name="permission" checked="checked" value="1"> Yes</label><br/>
                            <label class="radio_label"><input type="radio" name="permission" value="2"> No</label><br/>
                            <label class="radio_label"><input type="radio" name="permission" value="3"> Entry not necessary</label>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="input_box">
                            <label><strong>Upload photo</strong></label><br/>
<!--                            <input type="file" name="photo" value="3">-->
                            <div class="slim" style="width:150px!important;height:130px!important;"
                                 data-label="Choose a photo"
                                 data-label-loading=""
                                 data-button-edit-label=""
                                 data-button-remove-label=""
                                 data-button-upload-label=""
                                 data-button-cancel-label="Cancel"
                                 data-button-confirm-label="Ok"
                                 data-rotation="90"
                                 data-size="9000,9000">
                                <?php if(!empty($item['image'])): ?>
                                    <img src="<?=\Helpers\Url::filePath().$item['image']?>">
                                <?php endif; ?>
                                <input type="file" name="image[]"  value="<?= empty($item['image']) ? '' : \Helpers\Url::filePath().$item['image'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="input_box">
                            <label><strong>Category</strong> <span style="color:red;">*</span></label><br/>
                            <select name="category" class="select2 form-control">
                                <?php
                                $categories = \Modules\user\Models\WorkordersModel::getCategories();
                                foreach($categories as $data):?>
                                    <option value="<?=$data['key']?>" <?=$data['disabled']?>><?=$data['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="input_box">
                            <label><strong>Location</strong> <span style="color:red;">*</span></label><br/>
                            <select name="location" class="select2 form-control">
                                <?php
                                $locations = \Modules\user\Models\WorkordersModel::getLocations();
                                foreach($locations as $data):?>
                                    <option value="<?=$data['key']?>" <?=$data['disabled']?>><?=$data['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="input_box">
                            <label><strong>What needs attention?</strong> <span style="color:red;">*</span></label><br/>
                            <textarea rows="10" name="text" placeholder="Please, describe the issue"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="input_box">
                            <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                            <button type="submit" class="btn btncolor ">
                                Submit Request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="row half_box half_box_green">
                    <div class="col-sm-10">
                        <h3>Date Created</h3>
                        <?=date('m/d/Y')?>
                    </div>
                </div>
                <div class="row half_box half_box_red">
                    <div class="col-sm-10">
                        <h3>My Contact Info</h3>
                        <?=$user_info['first_name']?> <?=$user_info['last_name']?><br/>
                        <?=\Modules\user\Models\ApartmentsModel::getFullAddress($user_info['apt_id'])?><br/>
                        <?=$user_info['phone']?><br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<link rel="stylesheet" href="<?=\Helpers\Url::templateModulePath()?>css/select2.min.css" />
<script src="<?=\Helpers\Url::templateModulePath()?>js/select2.min.js"></script>
<script>
    $(".select2.form-control").select2( {
        placeholder: "---",
        allowClear: true
    } );
</script>