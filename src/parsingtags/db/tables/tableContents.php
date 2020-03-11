<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */

namespace ParsingTags\DB\Tables;


class tableContents extends table
{
    protected $tableName = 'contents';
    protected $tableColumnNameId = 'id_content';
    protected $tableColumnNameName = 'content';

    public function __construct(\ParsingTags\DB\DBwork $dbConnection)
    {
        $this->setQueryForSelectAllQueryByIdTag();
        parent::__construct($dbConnection);
    }

    private function setQueryForSelectAllQueryByIdTag()
    {
        $this->strShowAllRecordsByForeignKey = 'select id_content,content from contents where id_tag = ?';
    }
}