<?php
/**
 * @var \CMain $APPLICATION
 * @var \CUser $USER
 */

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Highloadblock as HL;
use Bitrix\Highloadblock\HighloadBlockTable;

global $APPLICATION, $USER, $DB;

if (!\defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$data = [];
$errors = [];

if (\count($errors) === 0) {
    switch ($_POST['ACTION']) {
        case 'GETLIST':

            try {
                Loader::includeModule("highloadblock");
            } catch (LoaderException $e) {
            }

            $hlBl = HighloadBlockTable::getList([
                'filter' => ['=NAME' => 'FormEventList']
            ])->fetch()['ID'];

            $hlBlock = HL\HighloadBlockTable::getById($hlBl)->fetch();

            $entity = HL\HighloadBlockTable::compileEntity($hlBlock);

            $entityDataClass = $entity->getDataClass();

            $rsData = $entityDataClass::getList(array(
                "select" => array("*"),
                "order" => array("ID" => "ASC"),
                "filter" => array()
            ));

            while ($arData = $rsData->Fetch()) {
                $list[] = $arData;
            }

            $data['LIST'] = $list;
            $data['USER_ID'] = $USER->GetID();

            break;

        case 'GETBITRIXEVENTS':

            $bitrixEvents = $DB->Query('SELECT * FROM b_vkolesnev_formevent_event_by_user ORDER BY CREATED_AT DESC LIMIT 1');
            while ($arEvent = $bitrixEvents->Fetch()) {
                $list[] = $arEvent;
            }

            $data['LIST'] = $list;
            $data['USER_ID'] = $USER->GetID();

            break;

        case 'GETUSERID':

            global $USER;

            $data['USER_ID'] = $USER->GetID();

            break;

        default:
            $errors[] = 'Неизвестное действие';
    }
}

echo \json_encode([
    'SUCCESS' => count($errors) === 0,
    'ERRORS' => $errors,
    'DATA' => $data,
]);
