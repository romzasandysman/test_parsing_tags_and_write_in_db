<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */

namespace ParsingTags\DB\Tables;


abstract class table
{
    protected $tableName = '';
    protected $tableColumnNameId = '';
    protected $tableColumnNameName = '';
    protected $tableColumnForeignKey = '';
    public $arErrors = [];
    protected $dbConnection;
    protected $strInsert = '';
    protected $strShowAll = '';
    protected $strShowAllRecordsByForeignKey = '';
    protected $strGetRecordsByColumnName = '';


    public function __construct(\ParsingTags\DB\DBwork $dbConnection)
    {
        if (!$dbConnection){
            $this->showFatalErrors('empty db connection please check db exist and you connect to it');
            return;
        }

        $this->initQueriesTable();
        $this->dbConnection = $dbConnection;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function insert($value = [])
    {
        if (!$this->checkIncomingValue($value) || !$this->checkIncomingDataForInsert($value)){
            return false;
        }

        $result = $this->dbConnection->doQueryWithParamas($this->prepareInsert($value), $value);
        return $this->checkErrorAfterRequest($result, $this->strInsert);
    }

    private function prepareInsert($value)
    {
        $insert = $this->strInsert;

        if (is_array($value) && count($value) > 2){
            $simbvolQuestion = str_repeat(',?',count($value) - 1);
            $insert = str_replace(',?',$simbvolQuestion,$this->strInsert);
        }

        return $insert;
    }

    private function checkIncomingDataForInsert(&$arValues = [])
    {
        $bProcessingSuccess = true;

        foreach ($arValues as &$arValue){
            if (!$arValue){
                $this->arErrors[] = 'problem with value, it is empty';
                $bProcessingSuccess = false;
                break;
            }

            $arValue = htmlspecialchars($arValue);
        }

        return $bProcessingSuccess;
    }

    public function showAllRecords()
    {
        $result = $this->dbConnection->doQuery($this->strShowAll);
        return $this->checkErrorAfterRequest($result, $this->strShowAll);
    }

    public function getRecordsByForeignKey($valueOfForeignKey)
    {
        if (!$this->checkIncomingValue($valueOfForeignKey)){
            return false;
        }

        $result = $this->dbConnection->doQuery(str_replace('?', $valueOfForeignKey, $this->strShowAllRecordsByForeignKey));
        return $this->checkErrorAfterRequest($result, $this->strShowAllRecordsByForeignKey);
    }

    public function getRecordsByColumnName($valueColumnName)
    {
        if (!$this->checkIncomingValue($valueColumnName)){
            return false;
        }

        $result = $this->dbConnection->doQuery(str_replace('?', $valueColumnName, $this->strGetRecordsByColumnName));
        return $this->checkErrorAfterRequest($result, $this->strGetRecordsByColumnName);
    }

    public function getColumnNameName(){
        return $this->tableColumnNameName;
    }

    public function getColumnNameId(){
        return $this->tableColumnNameId;
    }

    private function checkIncomingValue($value)
    {
        if (!$value) {
            $this->arErrors[] = 'value name empty';
            return false;
        }

        return true;
    }

    private function checkErrorAfterRequest($result, $query)
    {
        if (!$result){
            $this->arErrors[] = 'problem with insert query: ' . $query ;
            return false;
        }else{
            return $result;
        }
    }

    private function initQueriesTable()
    {
        $this->strInsert = 'INSERT INTO '.$this->tableName." VALUES('',?)";
        $this->strShowAll = 'SELECT '.$this->tableColumnNameId.', '.$this->tableColumnNameName.' FROM '.$this->tableName;
        $this->strShowAllRecordsByForeginKey = 'SELECT '.$this->tableColumnNameId.', '.$this->tableColumnNameName.' FROM '.$this->tableName . ' WHERE '
            . $this->tableColumnForeignKey . ' = ?';
        $this->strGetRecordsByColumnName = 'SELECT '.$this->tableColumnNameId.', '.$this->tableColumnNameName.' FROM '.$this->tableName . ' WHERE '
            . $this->tableColumnNameName . " = '?'";
    }

    private function showFatalErrors($strError)
    {
        throw new \Exception($strError);
    }

    public function __destruct()
    {
       unset($this->dbConnection);
    }
}