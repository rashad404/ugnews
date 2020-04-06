<?php
/**
 * PHP Pagination Class
 *
 */

namespace Helpers;

use Core\Language;
/**
 * Split records into multiple pages.
 */
class Pagination
{
    public $countRows=0;
    public $limitRow=1;
    public $maxPage=1;
    public $currentPage=1;
    public $startRow=0;
    public $showButtonCount = 3; //tek reqemler
    public $options = [12, 15, 25, 50, 100, 'all' => 'Hamısı'];
    public $limit = 12;

    public function getDefaultLimit()
    {
//        $options = $this->options;
//        if(isset($_GET["limit"]) and (in_array(Security::safe($_GET["limit"]),$options) OR Security::safe($_GET["limit"]=='all'))){
//            $limit = Security::safe($_GET["limit"]);
//        }else{
//            $limit = $options[0];
//        }
        $limit = $this->limit;
        $this->limitRow  = $limit;
        return $limit;
    }

    public function getMaxPage()
    {
        $maxPage = ceil($this->countRows/$this->limitRow);
        if($maxPage == 0) $maxPage = 1;

        $this->maxPage = $maxPage;
        return $maxPage;
    }



    public function getPage()
    {
        if(isset($_GET["page"]) and intval($_GET["page"])>0) $page=intval($_GET["page"]);
        else $page = 1;
        if($page>$this->maxPage) $page=$this->maxPage;
        $this->currentPage = $page;
        return $page;
    }


    public function getStartRow()
    {
        $startRow = $this->currentPage*$this->limitRow-$this->limitRow;

        $this->startRow = $startRow;
        return $startRow;
    }

    public function getLimitSql($countRows)
    {
        $limitRow = $this->getDefaultLimit();
        $this->countRows = $countRows;
        if(intval($limitRow)>0){
            $this->getMaxPage();
            $this->getPage();
            $startRow = $this->getStartRow();
            $return = " LIMIT $startRow,$limitRow";
        }else $return = '';

        return $return;
    }

    public function pageNavigation($custom_pagination = 'pagination')
    {

        $l = new Language(); $l->load('app');

        $show=3;
        $return = '<ul class="'.$custom_pagination.'">';

        if($this->currentPage>$this->showButtonCount+1) {
            $return .= '<li><a href="' . Url::to(Url::addFullUrl(["page" => 1])) . '">1</a></li>';
        }
        if($this->currentPage>1) {
            $return .= '<li><a href="' . Url::to(Url::addFullUrl(["page" => ($this->currentPage - 1)])) . '"> « </a></li>';
        }
        for($i=$this->currentPage-$this->showButtonCount;$i<=$this->currentPage+$this->showButtonCount;$i++)
        {
            if($i==$this->currentPage) {
                $class='class="active"'; $href='javascript:void(0);';
            }
            else {
                $class=''; $href=Url::to(Url::addFullUrl(["page" => $i]));
            }
            if($this->maxPage>1)
            if($i>0 && $i<=$this->maxPage)
                $return.= '<li '.$class.'><a href="'.$href.'">'.$i.'</a></li>';
        }
        if($this->currentPage<$this->maxPage)
            $return.= '<li><a href="'.Url::to(Url::addFullUrl(["page" => ($this->currentPage+1)])).'"> » </a></li>';
        if($this->currentPage<$this->maxPage-$this->showButtonCount && $this->maxPage>1)
            $return.= '<li><a href="'.Url::to(Url::addFullUrl(["page" => $this->maxPage])).'"> '.$this->maxPage.'</a></li>';
        $return.='</ul>';

        return $return;
    }

    public function getPrevious()
    {
        if($this->currentPage>1) {
            return Url::addFullUrl(["page" => ($this->currentPage-1)]);
        } else {
            return false;
        }
    }

    public function getNext()
    {
        if($this->currentPage<$this->maxPage) {
            return Url::addFullUrl(["page" => ($this->currentPage+1)]);
        } else {
            return false;
        }
    }


    public static function getCountData($countRows=0,$startRow=0,$limitRow=0)
    {
        if($limitRow>$countRows or intval($limitRow)==0) $limitRow=$countRows;
        if($limitRow+$startRow>$countRows) $limitRow=$countRows;
        $startRow++;
        if($countRows==0) {$startRow=0; $limitRow=0;}

        $return =  'Ümumi məlumatların sayı : <b>'.$countRows.'</b>, göstərilir: <b>'.$startRow.'-'.($startRow+$limitRow-1).'</b>';
        return $return;
    }


    public function getLimitSelector()
    {
        $options = $this->options;
        if(isset($_GET["limit"]) and intval($_GET["limit"])>0) $limit = intval($_GET["limit"]);
        elseif(isset($_GET["limit"]) and Security::safe($_GET["limit"])=='all') $limit = 'all';
        else $limit = $options[0];

        $optionsField = '';
        foreach($options as $k=>$v){
            $selected='';
            $key=$v;
            if(!is_numeric($k)) $key=$k;
            if($key == $limit) $selected   = 'selected="selected"';
            $optionsField.='<option '.$selected.' value="'.Url::to(Url::addFullUrl(["limit" => $key])).'">'.$v.'</option>';
        }

        $return = '<b class="text-info">Hər səhifədə göstər</b> <select class="padding-4 border-radius-5"  onchange="location = this.value;">
                    '.$optionsField.'
                </select>';
        return $return;
    }
}
