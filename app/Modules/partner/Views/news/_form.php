<?php
use Models\LanguagesModel;

$item = $data["item"];
$lng = $data['lng'];
$languages = LanguagesModel::getLanguages();
$defaultLanguage = LanguagesModel::getDefaultLanguage();
?>
<script>
    $(function() {
        // $('.ui.dropdown').dropdown();
        $('.ui.dropdown').dropdown('set selected',<?=$features?>);
    });
</script>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form_box">
        <div class="row ">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="image"><?=$lng->get('Photo');?></label>
                    <div class="slim" style="height:225px!important;"
                         data-label="<?=$lng->get('Choose a photo');?>"
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
            <?php $dp_c=0;$dtp_c=0;foreach ($data['input_list'] as $value) :?>
                <?php if(!empty($value['name'])):?>
                    <?php if($value['key']=='notice_date'):?>
                        </div><div class="row">
                    <?php endif;?>

                    <?php
                    if(!$item){
                        $input_value = '';
                        if($value['type']=='datetime'){
        //                            $input_value = date('Y-m-d', time()).'T'.date('h:i', time());
                            $input_value = date('m/d/Y h:i A');
        //                            echo $input_value;
                        }
                        if($value['type']=='date'){
                            $input_value = date('m/d/Y');
                        }
                    }else{
                        if($value['type']=='datetime'){
                            $input_value = date('m/d/Y h:i A',$item[$value['key']]);
                        }
                        elseif($value['type']=='date'){
                            $input_value = strtotime($item[$value['key']]);
                            $input_value = date('m/d/Y',$input_value);
                        }else{
                            $input_value = $item[$value['key']];
                        }
                    }?>

                    <div class="col-sm-<?=($value['type']=='textarea')?'12':'4'?>">
                        <div class="form-group">
                            <label><strong><?=$lng->get($value['name'])?>:</strong></label><br/>
                            <?php if($value['type']=='select_box'):?>
                                <select name="<?=$value['key']?>" class="form-control ">
                                    <option value="0" <?=$item?'':'selected'?>><?=$lng->get('Not selected')?></option>
                                    <?php foreach($value['data'] as $data):?>
                                        <option <?=$item&&$item[$value['key']]==$data['key']?'selected':''?> value="<?=$data['key']?>" <?=$data['disabled']?>><?=$data['name']?></option>
                                    <?php endforeach;?>
                                </select>
                            <?php elseif($value['type']=='select2'):?>
                                <select name="<?=$value['key']?>" class="select2 form-control">
                                    <?php foreach($value['data'] as $data):?>
                                        <?php
                                            if($item&&$item[$value['key']]==$data['key']){
                                                $selected = 'selected';
                                            }elseif(!$item&&$data['default']==true){
                                                $selected = 'selected';
                                            }else{
                                                $selected = '';
                                            }
                                        ?>
                                        <option <?=$selected?> value="<?=$data['key']?>" <?=$data['disabled']?>><?=$lng->get($data['name'])?></option>
                                    <?php endforeach;?>
                                </select>
                            <?php elseif($value['type']=='datetime'):?>
                                <div class="form-group default_date default" style="margin-top: 0px!important;margin-bottom: 0px!important;">
                                    <div class='input-group date' id='datetimepicker<?=$dp_c?>'>
                                        <input style="padding: 5px 15px!important;" type='text' name="<?=$value['key']?>" value="<?=$input_value?>"/>
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <?php  $dp_c++;?>

                            <?php elseif($value['type']=='date'):?>
                                <div class="form-group default_date">
                                    <div class="input-group date" id="datepicker<?=$dtp_c?>">
                                        <input value="<?=$input_value?>" name="<?=$value['key']?>" type="text">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <?php  $dtp_c++;?>

                            <?php elseif($value['type']=='textarea'):?>
<!--                                <textarea id="summernote" class="form-control" name="--><?//=$value['key']?><!--">--><?//=$item?$item[$value['key']]:''?><!--</textarea>-->

<!--                                <textarea id="summernote" name="--><?//=$value['key']?><!--">--><?//=$item?$item[$value['key']]:''?><!--</textarea>-->
                            <?php elseif($value['type']=='tags'):?>
                                <input class="tags_input" value="<?=$item?$item[$value['key']]:''?>" data-role="tagsinput" type="text" name="<?=$value['key']?>"/><br/>
                            <?php else: ?>
                                <input class="form-control admininput" type="<?=$value['type']?>" placeholder="" name="<?=$value['key']?>" value='<?=$item?$item[$value['key']]:''?>'>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endif;?>
            <?php endforeach;?>

        </div>

    </div>
    <div class="box-footer">
        <div class="col-xs-12">


            <!DOCTYPE html>
            <html>
            <head>
                <script src='https://cdn.tiny.cloud/1/ycixhg2pmyspjzcbhfduh2s53r2ctqjw8fqwrljbgrnlpypt/tinymce/5/tinymce.min.js' referrerpolicy="origin">
                </script>
                <script>
                    // tinymce.init({
                    //     selector: '#mytextarea',
                    //     content_css: '//www.tiny.cloud/css/codepen.min.css',
                    //     plugins: 'image code',
                    //     toolbar: 'undo redo | link image | code',
                    //     /* enable title field in the Image dialog*/
                    //     image_title: true,
                    //     /* enable automatic uploads of images represented by blob or data URIs*/
                    //     automatic_uploads: true,
                    //     /*
                    //       URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
                    //       images_upload_url: 'postAcceptor.php',
                    //       here we add custom filepicker only to Image dialog
                    //     */
                    //     file_picker_types: 'image',
                    //     /* and here's our custom image picker*/
                    //     file_picker_callback: function (cb, value, meta) {
                    //         var input = document.createElement('input');
                    //         input.setAttribute('type', 'file');
                    //         input.setAttribute('accept', 'image/*');
                    //
                    //         /*
                    //           Note: In modern browsers input[type="file"] is functional without
                    //           even adding it to the DOM, but that might not be the case in some older
                    //           or quirky browsers like IE, so you might want to add it to the DOM
                    //           just in case, and visually hide it. And do not forget do remove it
                    //           once you do not need it anymore.
                    //         */
                    //
                    //         input.onchange = function () {
                    //             var file = this.files[0];
                    //
                    //             var reader = new FileReader();
                    //             reader.onload = function () {
                    //                 /*
                    //                   Note: Now we need to register the blob in TinyMCEs image blob
                    //                   registry. In the next release this part hopefully won't be
                    //                   necessary, as we are looking to handle it internally.
                    //                 */
                    //                 var id = 'blobid' + (new Date()).getTime();
                    //                 var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    //                 var base64 = reader.result.split(',')[1];
                    //                 var blobInfo = blobCache.create(id, file, base64);
                    //                 blobCache.add(blobInfo);
                    //
                    //                 /* call the callback and populate the Title field with the file name */
                    //                 cb(blobInfo.blobUri(), { title: file.name });
                    //             };
                    //             reader.readAsDataURL(file);
                    //         };
                    //
                    //         input.click();
                    //     }
                    // });

                    tinymce.init({
                        selector: '#mytextarea',
                        content_css: '//www.tiny.cloud/css/codepen.min.css',
                        // plugins: 'image code',

                        height: 500,
                        plugins: [
                            "advlist autolink lists link image charmap print preview anchor",
                            "searchreplace visualblocks code fullscreen",
                            "insertdatetime media table paste imagetools wordcount"
                        ],
                            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",

                        /* without images_upload_url set, Upload tab won't show up*/
                        images_upload_url: '/partner/news/upload_image',
                        images_upload_base_path: '/Web/uploads/redactor/images',
                        images_upload_credentials: true
                    });


                    // tinymce.init({
                    //     selector: '#mytextarea',
                    //     height: 500,
                    //     plugins: [
                    //         "advlist autolink lists link image charmap print preview anchor",
                    //         "searchreplace visualblocks code fullscreen",
                    //         "insertdatetime media table paste imagetools wordcount"
                    //     ],
                    //     toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                    //     content_css: '//www.tiny.cloud/css/codepen.min.css'
                    // });
                </script>
            </head>

            <body>
            <form method="post">
    <textarea id="mytextarea" name="mytextarea">
      Hello, World!
    </textarea>
            </form>
            </body>
            </html>

            <div class="input-group pull-left">
                <input type="hidden" value="<?= \Helpers\Csrf::makeToken();?>" name="csrf_token">
                <button type="submit" class="btn btncolor secimetbtnadd">
                    <?=$lng->get('Save');?>
                </button>
            </div>
            <div class="input-group pull-right">
                <div class="pos-rel-top-6 ">
                    <label class="padyan15"><?=$lng->get('Status');?></label>
                    <input class="admin-switch" data-on-text="" data-off-text="" id="status" type="checkbox" name="status" value="1" <?php if($item && $item["status"]==0) echo ""; else echo "checked";?>>
                </div>
            </div>
        </div>
    </div>
</form>


<link rel="stylesheet" href="<?=\Helpers\Url::templatePartnerPath()?>css/select2.min.css" />
<script src="<?=\Helpers\Url::templatePartnerPath()?>js/select2.min.js"></script>


<link rel="stylesheet" href="<?=\Helpers\Url::templatePartnerPath()?>assets/datepicker/bootstrap-datetimepicker.min.css" />
<script src="<?=\Helpers\Url::templatePartnerPath()?>assets/datepicker/bootstrap-datetimepicker.min.js"></script>

<script>
    $(".select2.form-control").select2( {
        placeholder: "---",
        allowClear: true
    } );
</script>

<script>
    $(document).ready(function() {
        $(function() {
            <?php for ($i=0;$i<$dp_c;$i++):?>
            $('#datetimepicker<?=$i?>').datetimepicker();
            <?php endfor;?>
            <?php for ($i=0;$i<$dtp_c;$i++):?>
            $('#datepicker<?=$i?>').datetimepicker({
                format: 'L'
            });
            <?php endfor;?>

        });
    });
</script>
