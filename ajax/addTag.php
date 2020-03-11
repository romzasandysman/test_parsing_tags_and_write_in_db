<?php
session_start();

$arResult = [
    'success' => true
];

if (empty($_POST) && session_id() != $_POST['session-id'] && $_POST['ajax'] != 'Y') {
    $arResult['success'] = false;

    ob_clean();
    echo json_encode($arResult);
    die();
}

include_once '../vendor/autoload.php';

use ParsingTags\linkGetDataDB;
use \ParsingTags\linkParsingDb,
    \ParsingTags\DB\dbConfigConnection;

try {
    $linkParsing = new linkParsingDb($_POST['tag_data'], dbConfigConnection::$arConnectionData);
    $linkDataDB = new linkGetDataDB(dbConfigConnection::$arConnectionData);
} catch (\Exception $e){
    $arResult['success'] = false;
    $arResult['error'] = $e->getMessage();

    echo json_encode($arResult);
    die();
}

$tagID = $linkDataDB->getRowsOfTable($linkDataDB->getObTable('tags'),null, $linkParsing->getTag());
$tagID = $tagID ? current($tagID)[$linkParsing->getObTable('tags')->getColumnNameId()] : $linkParsing->insertRowInTable($linkParsing->getObTable('tags'), ['s',$linkParsing->getTag()]);

if ($tagID){
    if ($linkParsing->getDescOfTag()) {
        if (!$linkParsing->insertRowInTable($linkParsing->getObTable('descriptions'), ['is', $tagID, $linkParsing->getDescOfTag()])) {
            $arResult['success'] = false;
            $arResult['error'] = $linkParsing->arError;
        }
    }
    if (!$linkParsing->insertRowInTable($linkParsing->getObTable('contents'), ['is',$tagID, $linkParsing->getDataFromTag()])){
        $arResult['success'] = false;
        $arResult['error'] = $linkParsing->arError;
    };
}else{
    $arResult['success'] = false;
    $arResult['error'] = $linkParsing->arError;
};

echo json_encode($arResult);
die();
