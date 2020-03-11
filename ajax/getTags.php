<?php
session_start();
if (empty($_POST) && session_id() != $_POST['session-id'] && $_POST['ajax'] != 'Y') return;

include_once '../vendor/autoload.php';

use \ParsingTags\linkGetDataDB,
    \ParsingTags\DB\dbConfigConnection,
    \ParsingTags\HtmlViewTags\viewTags;

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

if ($arTags = $linkDataDB->getRowsOfTable($linkDataDB->getObTable('tags'))){
       $objViewTags = new viewTags('');
       $arResult['html'] = $objViewTags->showTags($objViewTags->prepareArTagsForShow($arTags, [
               $linkDataDB->getObTable('tags')->getColumnNameName() => 'NAME',
               $linkDataDB->getObTable('tags')->getColumnNameId() => 'ID',
            ]
        )
       );
}else{
    $arResult['success'] = false;
    $arResult['error'] = $linkDataDB->arError;
}


echo json_encode($arResult);
die();
