<?php
session_start();
if (empty($_POST) && session_id() != $_POST['session-id'] && $_POST['ajax'] != 'Y') return;

include_once '../vendor/autoload.php';

use \ParsingTags\linkGetDataDB,
    \ParsingTags\DB\dbConfigConnection,
    \ParsingTags\HtmlViewTags\viewDataOfTags;

$arResult = [
    'success' => true
];

try {
    $linkDataDB = new linkGetDataDB(dbConfigConnection::$arConnectionData);
} catch (\Exception $e){
    $arResult['success'] = false;
    $arResult['error'] = $e->getMessage();

    echo json_encode($arResult);
    die();
}

$tagId = $_POST['tagID'];

if ($arContents = $linkDataDB->getRowsOfTable($linkDataDB->getObTable('contents'),$tagId)){
    $arDesc = $linkDataDB->getRowsOfTable($linkDataDB->getObTable('descriptions'), $tagId);
    $objViewDataTags = new viewDataOfTags('',
        $linkDataDB->getObTable('descriptions')->getColumnNameName(),
        $linkDataDB->getObTable('contents')->getColumnNameName()
    );
    $arResult['html'] = $objViewDataTags->showDataTags($objViewDataTags->prepareDataOfTag($arContents,$arDesc));
}else{
    $arResult['success'] = false;
    $arResult['error'] = $linkDataDB->arError;
}

echo json_encode($arResult);
die();