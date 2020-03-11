<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */

namespace ParsingTags\DB\Tables;


class tableDescriptions extends table
{
    protected $tableName = 'descriptions';
    protected $tableColumnNameId = 'id_description';
    protected $tableColumnNameName = 'description';

    public function __construct(\ParsingTags\DB\DBwork $dbConnection)
    {
        $this->setQueryForSelectAllQueryByIdTag();
        parent::__construct($dbConnection);
    }

    private function setQueryForSelectAllQueryByIdTag()
    {
        $this->strShowAllRecordsByForeignKey = 'select id_description, description from descriptions where id_tag = ?';
    }
}