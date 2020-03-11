<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */

namespace ParsingTags;


use ParsingTags\DB\Tables\table;

class linkGetDataDB extends linkParsingDb
{

    public function __construct($arDBConnection = [])
    {
        try {
            parent::__construct('',$arDBConnection,true);
        } catch (\Exception $e){
            throw  $e;
        }
    }

    public function getRowsOfTable(table $tableOfParsing, $foreginKey = null, $columnName = null)
    {
        $funcName = $foreginKey ? 'getRecordsByForeignKey' : 'getRecordsByColumnName';
        $value = $foreginKey ? : $columnName;

        if ($value){
            return $this->getRowsByColumn($tableOfParsing, $value, $funcName);
        } else{
            return $this->getAllRowsFromTable($tableOfParsing);
        }
    }

    private function getRowsByColumn(table $tableOfParsing, $columnValue, $funcGetBy)
    {
        if ($arRows = $tableOfParsing->$funcGetBy($columnValue)){
            return $arRows;
        }else{
            $this->setNewErrors($tableOfParsing->arErrors);
            return false;
        }
    }

    private function getAllRowsFromTable(table $tableOfParsing){
        if ($arRows = $tableOfParsing->showAllRecords()){
            return $arRows;
        }else{
            $this->setNewErrors($tableOfParsing->arErrors);
            return false;
        }
    }
}