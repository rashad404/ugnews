<?php
namespace Modules\admin\Controllers;

use Core\Controller;
use Modules\admin\Models\PhotoUploaderModel;
/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Photo_uploader extends Controller
{
    // Index page
    public function add($id){
        PhotoUploaderModel::addImage($id);
    }
    public function delete($id){
        PhotoUploaderModel::deleteImage($id);
    }
    public function rotate($id){
        PhotoUploaderModel::rotateImage($id);
    }

}