<?php


namespace ParsingTags\htmlviewtags;


class viewDataOfTags
{
    private $templateOfDataTags;
    private $strDescNameColumn;
    private $strContentNameColumn;

    public function __construct($srcTempLateOfDataTags = '', $descNameColumn = '', $contentNameColumn = '')
    {
        if (!$srcTempLateOfDataTags){
            $this->setDefaultTemplateOfTags();
        }else{
            $this->templateOfDataTags = $srcTempLateOfDataTags;
        }

        $this->strDescNameColumn = $descNameColumn;
        $this->strContentNameColumn = $contentNameColumn;
    }

    public function showDataTags($arTagData){
        if (empty($arTagData)) return null;

        $htmlTags = '<div class="row">
            <div class="col-md-4">№Данных</div>
            <div class="col-md-4">Описание</div>
            <div class="col-md-4">Данные</div>
</div>';
        foreach ($arTagData as $key => $arTagDatum){
            $htmlTags .= str_replace(['#NUMBER_DATA#','#DESCRIPTION#','#CONTENT#'], [$key, $arTagDatum['DESCRIPTION'],$arTagDatum['CONTENT']], $this->templateOfDataTags);
        }

        return $htmlTags;
    }

    public function prepareDataOfTag($arContents, $arDesc = []){
        if (!$arContents) return null;

        $arReturn = [];
        foreach ($arContents as $key => $arContent){
            $arReturn[$key]['CONTENT'] = $arContent[$this->strContentNameColumn];
            if ($arDesc[$key]){
                $arReturn[$key]['DESCRIPTION'] = $arDesc[$key][$this->strDescNameColumn];
            }else{
                $arReturn[$key]['DESCRIPTION'] = '';
            }
        }

        return $arReturn;
    }

    private function setDefaultTemplateOfTags (){
        $this->templateOfDataTags = '
            <div class="row">
                <div class="col-md-4">#NUMBER_DATA#</div>
                <div class="col-md-4">
                      #DESCRIPTION#
                </div>
                <div class="col-md-4">
                     #CONTENT# 
                </div>
            </div>
        ';
    }
}