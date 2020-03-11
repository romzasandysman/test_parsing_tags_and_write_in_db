<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */

namespace ParsingTags\Parsing;

use ParsingTags\Parsing\TagsRegTmpls;

class Main
{
    private $strIncomingData;
    private $modeWorKErrors;
    private $bIncomingDataWithDesc;
    public $arErrors = [];

    public function __construct($strTagWithData = null, $hiddenModeErrors = false)
    {
        $this->modeWorKErrors = $hiddenModeErrors;

        if (!$strTagWithData){
            $this->showErrors('incoming data is empty, please create obj with not empty str');
            return;
        }


        $this->strIncomingData = $strTagWithData;
        $this->bIncomingDataWithDesc = $this->isTagWithDesc();
    }

    public function showStoredData(){
        return $this->strIncomingData;
    }

    public function checkThatDataHasRightFormat(){
        return preg_match(TagsRegTmpls::$tmplTagWithDesc, $this->strIncomingData) || preg_match(TagsRegTmpls::$tmplTagWithoutDesc, $this->strIncomingData);
    }

    public function getTagFromIncomingData(){
        if ($this->bIncomingDataWithDesc){
            return str_replace('<','',preg_replace(TagsRegTmpls::$tmplGetTagWithDesc, '', $this->strIncomingData));
        }else{
            return str_replace('<','',preg_replace(TagsRegTmpls::$tmplGetTagWithoutDesc, '', $this->strIncomingData));
        }
    }

    public function getDescFromIncomingData(){
        if ($this->bIncomingDataWithDesc){
            $strReplacedWithBegin = preg_replace(TagsRegTmpls::$tmplGetTagDesc, '', $this->strIncomingData);
            return preg_replace(TagsRegTmpls::$tmplGetTagDescReplaceBegin, '', $strReplacedWithBegin);
        }else{
            return null;
        }
    }

    public function getDataFromIncomingData(){
        return preg_replace(TagsRegTmpls::$tmplGetTagData, '', $this->strIncomingData);
    }

    private function isTagWithDesc(){
        return preg_match(TagsRegTmpls::$tmplTagWithDesc, $this->strIncomingData);
    }

    private function showErrors($strError){
        if (!$this->modeWorKErrors) {
            throw new \Exception($strError);
        }else{
            $this->arErrors[$strError];
        }
    }

    public function __destruct()
    {
        unset($this->strIncomingData);
    }
}