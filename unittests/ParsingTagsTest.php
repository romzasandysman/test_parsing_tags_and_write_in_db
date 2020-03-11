<?php
namespace UnitTests;

use \PHPUnit\Framework\TestCase,
    \ParsingTags\Parsing\Main;

class ParsingTagsTest extends TestCase{

    /**
     * @dataProvider stringsRightTagsProvider
     */
    public function testCheckThatDataHasRightFormat($stringTag){
        $objParser = new Main($stringTag);
        $this->assertTrue($objParser->checkThatDataHasRightFormat());
    }

    /**
     * @dataProvider stringsNotRightTagsProvider
     */
    public function testCheckThatDataHasNotRightFormat($stringTag){
        $objParser = new Main($stringTag);
        $this->assertFalse($objParser->checkThatDataHasRightFormat());
    }

    /**
     * @dataProvider stringsRightTagsProvider
     */
    public function testGetTagFromIncomingData($stringTag,$expectedTag){
        $objParser = new Main($stringTag);
        $this->assertEquals($expectedTag,$objParser->getTagFromIncomingData());
    }

    /**
     * @dataProvider stringsRightTagsProvider
     */
    public function testGetDescFromIncomingData($stringTag,$expectedTag,$expectedDesc){
        $objParser = new Main($stringTag);
        $this->assertEquals($expectedDesc,$objParser->getDescFromIncomingData());
    }

    /**
     * @dataProvider stringsRightTagsProvider
     */
    public function testGetDataFromIncomingData($stringTag,$expectedTag,$expectedDesc, $expectedData){
        $objParser = new Main($stringTag);
        $this->assertEquals($expectedData,$objParser->getDataFromIncomingData());
    }

    public function stringsRightTagsProvider()
    {
        return [
            ['<div>datatatataerefsf dsfsdf</div>' , 'div', null,'datatatataerefsf dsfsdf'],
            ['<div>фывфв фывфвфв фывфв</div>' , 'div', null,'фывфв фывфвфв фывфв'],
            ['<delivery:for delivery data>datatatataerefsf dsf sdf</delivery>', 'delivery','for delivery data','datatatataerefsf dsf sdf'],
            ['<delay:description>datatatataerefsfdsfsdf</delay>', 'delay','description','datatatataerefsfdsfsdf'],
            ['<right>datatatataerefsfdsfsdf
                    sfsdfsfsfsdfsfsdf
                    sdfsdfsdfsdf
                    sdfsfsfffff
                    sdfsdfsdf
                </right>', 'right',null,'datatatataerefsfdsfsdf
                    sfsdfsfsfsdfsfsdf
                    sdfsdfsdfsdf
                    sdfsfsfffff
                    sdfsdfsdf
                ']
        ];
    }

    public function stringsNotRightTagsProvider()
    {
        return [
            ['<div datatatataerefsf dsfsdf<div>'],
            ['<delivery:>datatat ataerefsf dsfsdf</delivery>'],
            ['<delay>datatatataerefsfdsfsdf<delay>'],
            ['<delay>datatata
taerefsfdsfsdf
sdfsfsdffff<delay>'],
            ['<right></right>']
        ];
    }
}