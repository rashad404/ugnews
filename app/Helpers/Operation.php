<?php

namespace Helpers;


use Modules\admin\Controllers\Categories;

class Operation
{

    public $className;
    public $tableName;


    protected function getClassName()
    {

        if(preg_match("/_/",$this->tableName)){
            $classNameArray = explode("_",$this->tableName);
            $className = implode("", array_map('ucfirst',$classNameArray));
        }else{
            $className = ucfirst($this->tableName);
        }
        return 'Modules\\'.MODULE_ADMIN.'\\Models\\'.$className.'Model';
    }

    public function move($id,$direction)
    {

        $class =  $this->getClassName() ;
        $model = $this->findModel($id);

        if($direction==='up')
        {
             if(strtoupper($class::$positionOrderBy) == 'ASC'){
                $eq = '<';
                $order = 'DESC';
            }else {
                $eq = '>';
                $order = 'ASC';
            }
        }
        else
        {
             if(strtoupper($class::$positionOrderBy) == 'ASC'){
                $eq = '>';
                $order = 'ASC';
            }else {
                $eq = '<';
                $order = 'DESC';
            }
        }

        $whereCondition='';
        if($class::$positionCondition && count($class::$positionConditionField)>0)
        {
            $condition= $class::$positionConditionField;
            foreach($condition as $cond)
            {
//                $whereCondition.=' AND '.$cond.'="'.$model[$cond].'" ';
            }

        }

        //echo 'SELECT * FROM '.$this->tableName.' WHERE `position`'.$eq.':position '.$whereCondition.' ORDER BY `position` '.$order; exit;
        $other = Database::get()->selectOne('SELECT * FROM '.$this->tableName.' WHERE `position`'.$eq.':position '.$whereCondition.' ORDER BY `position` '.$order,[':position'=>$model["position"]]);

        if(!$other) return false;
        $currentPosition=$model["position"];
        $model["position"] = $other["position"];
        $other["position"] = $currentPosition;

        echo $model["position"];exit;
        $modelUpdate = Database::get()->update($this->tableName,['position' => $model["position"]],["id" => $model["id"]]);
        $otherUpdate = Database::get()->update($this->tableName,['position' => $other["position"]],["id" => $other["id"]]);
        if($modelUpdate && $otherUpdate) {Session::setFlash('success','Sıralama dəyişdi'); return true; }
        else { Session::setFlash('error','Xəta baş verdi'); return false; }

    }


    public function getPositionForNew($id,$direction='',$update=false)
    {
        $class =  $this->getClassName();
        $model = $this->findModel($id);

        if($direction==='up')
        {
            if(strtoupper($class::$positionOrderBy) == 'DESC'){
                $eq = '<';
                $order = 'ASC';
            }else {
                $eq = '<';
                $order = 'DESC';
            }
        }
        else
        {
            if(strtoupper($class::$positionOrderBy) == 'ASC'){
                $eq = '>';
                $order = 'ASC';
            }else {
                $eq = '<';
                $order = 'DESC';
            }
        }

        $whereCondition='';
        if($class::$positionCondition && count($class::$positionConditionField)>0)
        {
            $condition= $class::$positionConditionField;
            foreach($condition as $cond)
            {
                $whereCondition.= $cond.'="'.$model[$cond].'" ';
            }

        } else {
            $whereCondition = ' 1=1 ';
        }
        //echo 'SELECT * FROM '.$this->tableName.' WHERE `position`'.$eq.':position '.$whereCondition.' ORDER BY `position` '.$order; exit;
        $other = Database::get()->selectOne('SELECT * FROM '.$this->tableName.' WHERE '.$whereCondition.' ORDER BY `position` '.$order);
        //var_dump($other); exit;
        if(!$other) $pos =  1;
        else {
            $new_position = $other["position"]+1;
            $pos =  $new_position;
        }

        //print_r($other); exit;

        Database::get()->update($this->tableName,['position' => $pos],["id" => $id]);
        return $pos;

    }


    public function getLastPosition($where = '')
    {
        if($where!=""){
            $where = "WHERE ".$where;
        }
        $other = Database::get()->selectOne('SELECT `position` FROM '.$this->tableName.' '.$where.' ORDER BY `position` DESC');
        if($other){
            return $other["position"];
        }else{
            return 0;
        }
    }

    public function deleteModel($ids=[])
    {
        $ids=implode(",",$ids);
        $ids=Security::safe($ids);
        $addWhere=$this->getSafeModeWhere();
        $count=Database::get()->count("SELECT count(id) FROM ".$this->tableName." where `id` in (".$ids.") $addWhere");
        if($count==0) return Session::setFlash('error','Bu məlumatı silmək olmaz');
        Database::get()->raw("DELETE FROM ".$this->tableName." where `id` in (".$ids.") $addWhere");
        Session::setFlash('success','Data has been deleted successfully');
    }


    public function statusModel($ids =[],$status = 1)
    {
        $addWhere = '';
        $class =  $this->getClassName();

        $ids = implode(',',$ids);
        $ids = Security::safe($ids);
        $status = intval($status);
        if($status==0 and  $class::$safeMode){
            foreach($class::$safeModeFields as $field){
                $addWhere.=' AND `'.$field.'`!=1 ';
            }
        }
        Database::get()->raw("UPDATE ".$this->tableName." SET `status`=$status WHERE `id` in (".$ids.") ".$addWhere);
        Session::setFlash('success','Data has been saved successfully');
        return true;
    }

    protected function getSafeModeWhere()
    {
        $class =  $this->getClassName();

        $where = '';
        if($class::$safeMode == true){
            foreach($class::$safeModeFields as $field){
                $where.= " AND `".$field."`=0";
            }
        }
        return $where;
    }

    public function getOrderBy()
    {
        $class = $this->getClassName();
        $order = ' `id` '.$class::$positionOrderBy;
        if($class::$positionEnable){
            $order = '`position` '.$class::$positionOrderBy;
        }

        return $order;
    }

    public function findModel($id)
    {
        if(!isset($id) || intval($id)==0){
            Session::setFlash('error','Səhifə tapılmadı');
            return Url::previous(MODULE_ADMIN."/".$this->tableName);
        }else{
            $row = Database::get()->selectOne('SELECT * FROM '.$this->tableName.' WHERE id=:id',[':id' => $id]);
            if(!$row){
                Session::setFlash('error','Məlumat tapılmadı');
                return Url::previous(MODULE_ADMIN."/".$this->tableName);
            }else{
                return $row;
            }
        }
    }


}
