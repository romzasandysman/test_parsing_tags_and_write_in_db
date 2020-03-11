<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */

namespace ParsingTags\DB;

class DBwork
{
    private $DB_NAME;
    private $nameOfUserBase;
    private $passwordOfUserBase;
    private $serverNameOfBase;
    private $obMysqli;
    public $errors;
    private $bHasConnectionToDB;

    public function __construct($passwordOfUserBase = '', $nameOfUserBase = 'root', $serverNameOfBase = 'localhost', $dbName = '')
    {
        $this->nameOfUserBase = $nameOfUserBase;
        $this->passwordOfUserBase = $passwordOfUserBase;
        $this->serverNameOfBase = $serverNameOfBase;
        $this->DB_NAME = $dbName;

        if (!$this->connectWithMysql()){
            throw new \Exception(implode('; ',$this->errors));
        };
    }

    private function connectWithMysql()
    {
        $this->obMysqli = new \mysqli($this->serverNameOfBase, $this->nameOfUserBase, $this->passwordOfUserBase);

        if ($this->obMysqli->connect_error) {
            $this->errors[] = $this->obMysqli->connect_error;
            return false;
        }
        return true;
    }

    public function connectToDataBase()
    {
        if ($this->errors) return false;

        $bHasDataBase = $this->obMysqli->select_db($this->DB_NAME);

        if (!$bHasDataBase) {
            if (!$this->obMysqli->query('CREATE DATABASE ' . $this->DB_NAME . ';') === TRUE) {
                $this->errors[] = $this->obMysqli->error;
                return false;
            }
        }
        $this->obMysqli->select_db($this->DB_NAME);
        $this->bHasConnectionToDB = true;
        return true;
    }

    public function doQueryWithParamas($strQuery, $arParam = array())
    {
        $resultQuery = array();
        $obPrepare = $this->obMysqli->prepare($strQuery);

        if (!$obPrepare) {
            return $resultQuery;
        }

        $ref = new \ReflectionClass('mysqli_stmt');
        $method = $ref->getMethod("bind_param");
        $method->invokeArgs($obPrepare, $this->refValues($arParam));

        if (!$obPrepare->execute()) {
            return $resultQuery;
        }

        $getData = $obPrepare->get_result();
        if (!$getData) {
            if ($obPrepare->insert_id){
                return $obPrepare->insert_id;
            }elseif (!$obPrepare->errno){
                return true;
            }
        }
        $resultQuery = $getData->fetch_array(MYSQLI_ASSOC);
        $obPrepare->close();

        return $resultQuery;
    }

    public function doQuery($strQuery)
    {
        if (!$obPrepare = $this->obMysqli->query($strQuery)) {
            $this->errors[] = $this->obMysqli->error;
            return false;
        };

        if (is_bool($obPrepare)) {
            return $obPrepare;
        }

        $resultQuery = array();

        while ($row = $obPrepare->fetch_array(MYSQLI_ASSOC)) {
            $resultQuery[] = $row;
        }
        $obPrepare->close();

        return $resultQuery;
    }

    public function __destruct()
    {
        if ($this->bHasConnectionToDB) {
            $this->obMysqli->close();
        }
    }

    private function refValues($arr)
    {
        if (strnatcmp(phpversion(), '5.3') >= 0) {
            $refs = array();
            foreach ($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }

}