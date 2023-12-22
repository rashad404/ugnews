<?php
/**
 * database Helper
 *
 * @author David Carr - dave@daveismyname.com
 * @version 2.1
 * @date June 27, 2014
 * @date updated Sept 19, 2015
 */

namespace Helpers;

use Core\Logger;
use \PDO;


class Database extends PDO
{

    protected static $instances = array();


    public static function get($group = false)
    {
        // Determining if exists or it's not empty, then use default group defined in config
        $group = !$group ? array (
            'type' => DB_TYPE,
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'pass' => DB_PASS
        ) : $group;

        // Group information
        $type = $group['type'];
        $host = $group['host'];
        $name = $group['name'];
        $user = $group['user'];
        $pass = $group['pass'];

        // ID for database based on the group information
        $id = "$type.$host.$name.$user.$pass";

        // Checking if the same
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }

        try {
            // I've run into problem where
            // SET NAMES "UTF8" not working on some hostings.
            // Specifiying charset in DSN fixes the charset problem perfectly!
            $instance = new Database("$type:host=$host;dbname=$name;charset=utf8", $user, $pass);
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $instance->exec("set names utf8");
            // Setting Database into $instances to avoid duplication
            self::$instances[$id] = $instance;

            return $instance;
        } catch (\PDOException $e) {
            //in the event of an error record the error to ErrorLog.html
            Logger::newMessage($e);
            Logger::customErrorMsg();
        }
    }

    public function raw($sql)
    {
        return $this->query($sql);
    }

    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC, $class = '')		//FETCH_OBJ
    {
        $stmt = $this->prepare($sql);
        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }

        $stmt->execute();

        if ($fetchMode === PDO::FETCH_CLASS) {
            return $stmt->fetchAll($fetchMode, $class);
        } else {
            return $stmt->fetchAll($fetchMode);
        }
    }

    public function selectOne($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC, $class = '')		//FETCH_OBJ
    {
        $stmt = $this->prepare($sql);
        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }

        $stmt->execute();
        if ($fetchMode === PDO::FETCH_CLASS) {
            return $stmt->fetch($fetchMode, $class);
        } else {
            return $stmt->fetch($fetchMode);
        }
    }

    public function count($sql, $array = [])		//FETCH_OBJ
    {
        $stmt = $this->prepare($sql);
        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }
        $stmt->execute();

        $return =$stmt->fetch(PDO::FETCH_ASSOC);

        foreach($return as $r){
            return intval($r);
        }
     }

    public function insert($table, $data)
    {
        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));

        $stmt = $this->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->lastInsertId();
    }

    public function insertMultiple($table, $keys, $values)
    {

        $values = preg_replace('/\\\/','\\\\\\\\',$values);
        $stmt = $this->prepare("INSERT INTO $table ($keys) VALUES $values");

        $stmt->execute();
        return $this->lastInsertId();
    }

    public function update($table, $data, $where)
    {
        ksort($data);

        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :field_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :where_$key";
            } else {
                $whereDetails .= " AND $key = :where_$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":field_$key", $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete($table, $where, $limit = 1)
    {
        ksort($where);

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }

        $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function truncate($table)
    {
        return $this->exec("TRUNCATE TABLE $table");
    }


    public function createTable($table_name, $data){
        $fields = array();
        $query = "CREATE TABLE IF NOT EXISTS  `".$table_name."` ( `id` int(11) AUTO_INCREMENT, ";
        foreach($data as $item){
            if(!empty($item['sql_type'])) {
                $fields[] = "`" . $item['key'] . "` " . $item['sql_type'] . ' NOT NULL';
            }
        }
        $fields = implode(', ',$fields);
        $query .= $fields.", PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;";
//        echo $query;
        self::raw($query);
    }
}
