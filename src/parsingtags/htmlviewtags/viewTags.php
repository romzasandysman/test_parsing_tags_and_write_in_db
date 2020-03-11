<?php


namespace ParsingTags\HtmlViewTags;


class viewTags
{
    private $templateOfTags;

    public function __construct($srcTempLateOfTags = '')
    {
        if (!$srcTempLateOfTags){
            $this->setDefaultTemplateOfTags();
        }else{
            $this->templateOfTags = $srcTempLateOfTags;
        }
    }

    public function showTags($arTags){
        if (empty($arTags)) return null;

        $htmlTags = '';
        foreach ($arTags as $arTag){
            $htmlTags .= str_replace(['#ID_TAG#','#NAME_TAG#'], [$arTag['ID'], $arTag['NAME']], $this->templateOfTags);
        }

        return $htmlTags;
    }

    public function prepareArTagsForShow($arTags, $arNewKeysOfAr = []){
        if (empty($arTags)) return null;

        $arReturn = [];
        foreach ($arTags as $key => $arTag){
            foreach ($arTag as $columnName => $value) {
                $arReturn[$key][$arNewKeysOfAr[$columnName]] = $value;
            }
        }

        return $arReturn;
    }

    private function setDefaultTemplateOfTags (){
        $this->templateOfTags = '
            <div class="row">
                <div class="col-md-4">
                      <a data-target="#tagModal" data-toggle="modal" data-tag-id="#ID_TAG#" href="#tagModal">#ID_TAG#</a>      
                </div>
                <div class="col-md-4">
                      <a data-target="#tagModal" data-toggle="modal" data-tag-id="#ID_TAG#" href="#tagModal">#NAME_TAG#</a>      
                </div>
            </div>
        ';
    }
}