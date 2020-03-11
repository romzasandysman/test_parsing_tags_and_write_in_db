<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */
namespace UnitTests;

use ParsingTags\DB\Tables\table;
use PHPUnit\Framework\TestCase,
    ParsingTags\linkParsingDb;

class LinkParsingDbTest extends TestCase
{
    private $arDbConnectionData = ['password' => 'Info123!', 'name_user' => 'root', 'server_path' => 'localhost', 'db_name' => 'parsing_tags'];
    /**
     * @dataProvider stringsRightTagsProvider
     */
    public function testGetTableObjFromParsing($string){
        try {
            $objParsingDB = new linkParsingDb($string, $this->arDbConnectionData);
            $this->assertInstanceOf(table::class, $objParsingDB->getObTable('tags'));
        } catch (\Exception $e){
            print_r($e);
        }
    }

    public function stringsRightTagsProvider()
    {
        return [
            ['<div>datatatataerefsf dsfsdf</div>' , 'div', null,'datatatataerefsf dsfsdf'],
        ];
    }
}
