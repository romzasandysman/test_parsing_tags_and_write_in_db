<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */

namespace ParsingTags;

use ParsingTags\DB\Tables\table;
use ParsingTags\Parsing\Main as parsingMain,
    ParsingTags\DB\DBwork,
    ParsingTags\DB\Tables\tableTags,
    ParsingTags\DB\Tables\tableContents,
    ParsingTags\DB\Tables\tableDescriptions;

class linkParsingDb
{
    private $tagFromIncomingData = '';
    private $descFromIncomingData = '';
    private $dataFromIncomingData = '';
    private $parserOfData;

    private $connection;
    private $tableTags;
    private $tableContents;
    private $tableDescriptions;

    public $arError = [];

    public function __construct($incomingData, $arDBConnection = [], $bOnlyDbInit = false)
    {
        if (!$incomingData && !$bOnlyDbInit){
            throw new \Exception('empty incoming data');
        }

        if (!$arDBConnection){
            throw new \Exception('empty data setting for connection');
        }

        if (!$bOnlyDbInit) {
            $this->parserOfData = new parsingMain($incomingData);
        }

        if (!$bOnlyDbInit && !$this->fillVarsOfIncomingData()){
            throw new \Exception('problem with connection to databse' . implode('; ', $this->arError));
        }

        if (!$this->initDBClasses($arDBConnection)){
            throw new \Exception('problem with connection to databse' . implode('; ', $this->arError));
        }
    }

    public function insertRowInTable(table $tableOfParsing, $arValuesForInsert)
    {
        if ($idOfInsertedRow = $tableOfParsing->insert($arValuesForInsert)){
            return $idOfInsertedRow;
        }else{
            $this->setNewErrors($tableOfParsing->arErrors);
            return false;
        }
    }

    public function getObTable($tableName)
    {
        return $this->{'table'.ucfirst($tableName)};
    }


    public function getTag()
    {
        return $this->tagFromIncomingData;
    }

    public function getDescOfTag()
    {
        return $this->descFromIncomingData;
    }

    public function getDataFromTag()
    {
        return $this->dataFromIncomingData;
    }

    protected function initDBClasses($arDataConnection)
    {
        try {
            $this->connection = new DBwork($arDataConnection['password'], $arDataConnection['name_user'], $arDataConnection['server_path'], $arDataConnection['db_name']);
        } catch (\Exception $e) {
            $this->arError[] = $e;
            return false;
        }

        if (!$this->connection->connectToDataBase())
        {
            $this->setNewErrors($this->connection->errors);
            return false;
        }

        $this->tableTags = new tableTags($this->connection);
        $this->tableContents = new tableContents($this->connection);
        $this->tableDescriptions = new tableDescriptions($this->connection);

        return true;
    }

    private function fillVarsOfIncomingData()
    {
        if ($this->parserOfData->checkThatDataHasRightFormat()) {
            $this->tagFromIncomingData = $this->parserOfData->getTagFromIncomingData();
            $this->descFromIncomingData = $this->parserOfData->getDescFromIncomingData();
            $this->dataFromIncomingData = $this->parserOfData->getDataFromIncomingData();
            return true;
        } else{
            $this->arError[] = 'incoming data has not right format';
            return false;
        }
    }

    protected function setNewErrors($arError)
    {
        if (!$arError){
            return false;
        }
        $this->arError[] = implode('; ', $arError);
    }

    public function __destruct()
    {
        unset($this->connection);
        unset($this->tableContents);
        unset($this->tableDescriptions);
        unset($this->tableTags);
        unset($this->parserOfData);
        unset($this->dataFromIncomingData);
        unset($this->descFromIncomingData);
        unset($this->tagFromIncomingData);
    }
}