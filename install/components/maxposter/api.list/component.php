<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/* @var $arParams array */
/* @var $arResult array */
/* @var $APPLICATION CMain */
/* @var $this CBitrixComponent */

// Модуль Макспотера
$moduleId = 'maxposter.api';
if (!CModule::IncludeModule($moduleId))
{
	ShowError(GetMessage('MAXPOSTER_API_MODULE_NOT_INSTALL'));
	return;
}

// Параметры модуля
$dealerId = COption::GetOptionString($moduleId, 'MAX_API_LOGIN');
$password = COption::GetOptionString($moduleId, 'MAX_API_PASSWORD');

/**************************** параметры компонента ****************************/
if (!empty($arParams['MAX_API_LOGIN'])) {
    $dealerId = $arParams['MAX_API_LOGIN'];
}
if (!empty($arParams['MAX_API_PASSWORD'])) {
    $password = $arParams['MAX_API_PASSWORD'];
}
// TODO: вынести в настройки, сильно потом, не нужно учить Битрикс плохому :)
$arParams['REQUEST_THEME'] = 'vehicles';
#var_dump($arParams);

$arUrlDefault = array(
    'URL_TEMPLATES_INDEX'    => '',
    'URL_TEMPLATES_ORDER_BY' => 'OB=#OB#&OD=#OD#',
    'URL_TEMPLATES_VEHICLE'  => 'VEHICLE_ID=#VEHICLE_ID#',
);
foreach ($arUrlDefault as $urlKey => $url) {
    if (!array_key_exists($urlKey, $arParams) || (0 >= strlen($arParams[$urlKey]))) {
        $arParams[$urlKey] = $APPLICATION->GetCurPage() . '?' . htmlspecialcharsbx($url);
    }
}

/***************************** входящие параметры *****************************/
/**
 * TODO:
 * - сортировка по всем возможным полям, брать из клиента
 * - фильтрация (учитывать настройки, напр. только спец.цена)
 * - постраничная навигация
 */
$arParams['PAGE'] = (1 < ($page = $_REQUEST['PAGE']) ? intval($page) : 1);

$arParams['ORDER_BY']        = !empty($_REQUEST['OB']) ? $_REQUEST['OB'] : false;
$arParams['ORDER_DIRECTION'] = !empty($_REQUEST['OD']) ? mb_strtolower($_REQUEST['OD']) : false;
// возможные варианты сортировок
$orderBy = array(
    'D' => 'date',
    'N' => 'name',
    'P' => 'price',
    'Y' => 'year',
    'M' => 'distance',
);
if (!array_key_exists($arParams['ORDER_BY'], $orderBy)) {
    $arParams['ORDER_BY'] = false;
} else {
    $arParams['ORDER_BY'] = $orderBy[$arParams['ORDER_BY']];
}
if (!in_array($arParams['ORDER_DIRECTION'], array('asc', 'desc'))) {
    $arParams['ORDER_DIRECTION'] = false;
}

$filterParams = (array_key_exists('FS', $_REQUEST) ? $_REQUEST['FS'] : array());

/************************** клиент, запрос к сервису **************************/
// Параметры запроса
$client = new maxCacheXmlClient(array(
    'api_version' => 1,
    'dealer_id'   => $dealerId,
    'password'    => $password,
    'cache_dir'   => $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . '/cache/maxposter/',
));
// Запрос к сервису / кешу
$client->setRequestThemeName($arParams['REQUEST_THEME']);
// TODO: настройки и параметры
$client->setGetParameters(array(
    'page'      => $arParams['PAGE'],
    'page_size' => 20,
));
// TODO: ловить входящие данные
$searchParams = array();
if ($arParams['ORDER_BY']) {
    $searchParams['order_by'] = $arParams['ORDER_BY'];
}
if ($arParams['ORDER_DIRECTION']) {
    $searchParams['order_direction'] = $arParams['ORDER_DIRECTION'];
}
$searchParams = array_merge($searchParams, $filterParams);
$client->setRequestParams(array('search' => $searchParams));

$domXml = $client->getXml()->saveXML();
// при ошибке запроса
if ($client->getResponseThemeName() == 'error') {
    CHTTP::SetStatus("404 Not Found");
    // TODO: получать message из ответа сервера
    ShowError(GetMessage('MAX_NOT_FOUND'));
    return;
}

if (mb_strtolower(SITE_CHARSET) != 'utf-8') {
    $data = iconv('utf-8', SITE_CHARSET, $domXml);
} else {
    $data = $domXml->saveXML();
}
$xml = new CDataXML();
// Лучше бы через
// $xml->Load('/path/to/file');
$xml->LoadString($data);

// получаем данные
/**
 * В минимальном варианте:
 * - фото
 * - марка
 * - модель
 * - год выпуска
 * + объём двигателя
 * - пробег
 * - цена
 */
$arVehicles = array();
$i = 0;
foreach ($xml->SelectNodes('/response/vehicles')->children() as $vNode) {
    /* @var CDataXMLNode $vNode */
    $vehicle = $vNode->__toArray();
    $arVehicles[$i] = array();
    $arVehicles[$i]['VEHICLE_ID'] = $vehicle['@']['vehicle_id'];
    $arVehicles[$i]['DEALER_ID']  = $vehicle['@']['dealer_id'];
    $arVehicles[$i]['URL_TO_VEHICLE'] = CComponentEngine::MakePathFromTemplate($arParams['URL_TEMPLATES_VEHICLE'], array('VEHICLE_ID' => $arVehicles[$i]['VEHICLE_ID']));
    // Фото
    if (array_key_exists('photo', $vehicle['#']) && array_key_exists('0', $vehicle['#']['photo'])) {
        $arVehicles[$i]['PHOTO'] = $vehicle['#']['photo']['0']['@']['file_name'];
        $localPath = rtrim($_SERVER["DOCUMENT_ROOT"], '/') . '/upload/maxposter/' . $arVehicles[$i]['DEALER_ID'] . '/' . $arVehicles[$i]['VEHICLE_ID'] . '/original/';
        $path1 = sprintf('%s/upload/maxposter/%d/%d/big/', rtrim($_SERVER["DOCUMENT_ROOT"], '/'), $arVehicles[$i]['DEALER_ID'], $arVehicles[$i]['VEHICLE_ID']);
        $path2 = sprintf('%s/upload/maxposter/%d/%d/small/', rtrim($_SERVER["DOCUMENT_ROOT"], '/'), $arVehicles[$i]['DEALER_ID'], $arVehicles[$i]['VEHICLE_ID']);
        $path3 = sprintf('%s/upload/maxposter/%d/%d/medium/', rtrim($_SERVER["DOCUMENT_ROOT"], '/'), $arVehicles[$i]['DEALER_ID'], $arVehicles[$i]['VEHICLE_ID']);
        $arSize1 = array(
            'width'  => '255',
            'height' => '191',
        );
        $arSize2 = array(
            'width'  => '77',
            'height' => '58',
        );
        $arSize3 = array(
            'width'  => '100',
            'height' => '75',
        );
        CheckDirPath($localPath, true);
        CheckDirPath($path1, true);
        CheckDirPath($path2, true);
        CheckDirPath($path3, true);
        $path = sprintf('http://maxposter.ru/photo/%d/%d/orig/%s', $arVehicles[$i]['DEALER_ID'], $arVehicles[$i]['VEHICLE_ID'], $arVehicles[$i]['PHOTO']);
        if (!file_exists($localPath . $arVehicles[$i]['PHOTO']) or ('Y' == $_GET['clear_cache'])) {
            $from = fopen($path, 'rb', false);
            $to   = fopen($localPath . $arVehicles[$i]['PHOTO'], 'wb', false);
            $bytes = stream_copy_to_stream($from, $to);

            if (@file_exists($localPath . $arVehicles[$i]['PHOTO']) && (10 > @filesize($localPath . $arVehicles[$i]['PHOTO']))) {
                @unlink($localPath . $arVehicles[$i]['PHOTO']);
                unset($arVehicles[$i]['PHOTO']);
            } elseif ($bytes != @filesize($localPath . $arVehicles[$i]['PHOTO'])) {
                @unlink($localPath . $arVehicles[$i]['PHOTO']);
                unset($arVehicles[$i]['PHOTO']);
            } else {
                @chmod($localPath . $arVehicles[$i]['PHOTO'], 0666);
                foreach (array(1,2,3) as $n) {
                    $fileName = ${'path' . $n} . $arVehicles[$i]['PHOTO'];
                    CFile::ResizeImageFile(
                        $localPath . $arVehicles[$i]['PHOTO'],
                        $fileName,
                        ${'arSize' . $n},
                        BX_RESIZE_IMAGE_PROPORTIONAL,
                        array(),
                        80,
                        false
                    );
                    @chmod(${'path' . $n} . $arVehicles[$i]['PHOTO'], 0666);
                }
            }
            fclose($from);fclose($to);
        }
    }
    // Марка и модель
    $arVehicles[$i]['MARK']['ID']    = $vehicle['#']['mark']['0']['@']['mark_id'];
    $arVehicles[$i]['MARK']['NAME']  = $vehicle['#']['mark']['0']['#'];
    $arVehicles[$i]['MODEL']['ID']   = $vehicle['#']['model']['0']['@']['model_id'];
    $arVehicles[$i]['MODEL']['NAME'] = $vehicle['#']['model']['0']['#'];
    // год выпуска
    $arVehicles[$i]['YEAR'] = $vehicle['#']['year']['0']['#'];
    // пробег
    $arVehicles[$i]['DISTANCE']      = $vehicle['#']['mileage']['0']['#']['value']['0']['#'];
    $arVehicles[$i]['DISTANCE_UNIT'] = $vehicle['#']['mileage']['0']['#']['value']['0']['@']['unit'];
    // цена
    $arVehicles[$i]['PRICE']         = $vehicle['#']['price']['0']['#']['value']['0']['#'];
    $arVehicles[$i]['PRICE_UNIT']    = $vehicle['#']['price']['0']['#']['value']['0']['@']['unit'];
    $arVehicles[$i]['PRICE_RUB']     = $vehicle['#']['price']['0']['#']['value']['0']['@']['rub_price'];
    // спец.цена и старая цена
    // Остальное:
    // объём двигателя
    $arVehicles[$i]['ENGINE_VOLUME'] = $vehicle['#']['engine']['0']['#']['volume']['0']['#'];

    $i++;
}
$arResult['VEHICLES'] = $arVehicles;

$addParams = array();
foreach ($filterParams as $name => $value) {
    if (is_array($value)) {
        foreach ($value as $vname => $vvalue) {
            if (!$vvalue) {
                continue;
            }
            $strValue = sprintf('[%s]=%s', $vname, $vvalue);
            $tmpQuery = sprintf('FS[%s]%s', $name, $strValue);
            $addParams[] = $tmpQuery;
        }
    } else {
        if (!$value) {
            continue;
        }
        $value = sprintf('=%s', $value);
        $tmpQuery = sprintf('FS[%s]%s', $name, $value);
        $addParams[] = $tmpQuery;
    }
}
$addParams = implode('&', $addParams);
$addParams = htmlspecialchars($addParams ? '&' . $addParams : '');

foreach ($orderBy as $short => $orderField) {
    $arResult['URL']['ORDER_BY_' . mb_strtoupper($orderField) . '_ASC']  = CComponentEngine::MakePathFromTemplate($arParams['URL_TEMPLATES_ORDER_BY'], array('OB' => $short, 'OD' => 'ASC')) . $addParams;
    $arResult['URL']['ORDER_BY_' . mb_strtoupper($orderField) . '_DESC'] = CComponentEngine::MakePathFromTemplate($arParams['URL_TEMPLATES_ORDER_BY'], array('OB' => $short, 'OD' => 'DESC')) . $addParams;
}

// total="1" current="1" items_total="11" items_per_page="1000"
$pager = $xml->SelectNodes('/response/pager');
$arResult['PAGER']['CURRENT']   = $arParams['PAGE'];
$arResult['PAGER']['TOTAL']     = $pager->getAttribute('total');
$arResult['PAGER']['ITEMS']     = $pager->getAttribute('items_total');
$arResult['PAGER']['PER_PAGE']  = $pager->getAttribute('items_per_page');
$arResult['PAGER']['LINKS']     = 5;

$appParams = urldecode($APPLICATION->getCurParam());
$addParams = array();
foreach ($filterParams as $name => $value) {
    if (is_array($value)) {
        foreach ($value as $vname => $vvalue) {
            if (!$vvalue) {
                continue;
            }
            $strValue = sprintf('[%s]=%s', $vname, $vvalue);
            $tmpQuery = sprintf('FS[%s]%s', $name, $strValue);
            if (false === mb_strpos($appParams, $tmpQuery)) {
                $addParams[] = $tmpQuery;
            }
        }
    } else {
        if (!$value) {
            continue;
        }
        $value = sprintf('=%s', $value);
        $tmpQuery = sprintf('FS[%s]%s', $name, $value);
        if (false === mb_strpos($appParams, $tmpQuery)) {
            $addParams[] = $tmpQuery;
        }
    }
}

if (1 > ($arResult['PAGER']['CURRENT'] - ceil(($arResult['PAGER']['LINKS'] - 1) / 2))) {
    $pStart = 1;
} else {
    $pStart = $arResult['PAGER']['CURRENT'] - ceil(($arResult['PAGER']['LINKS'] - 1) / 2);
}
$addParams = implode('&', $addParams);
$addParams = ($addParams ? '&' . $addParams : '');
$arResult['PAGER']['PAGES']['FIRST'] = urldecode($APPLICATION->GetCurPageParam($addParams, array('PAGE')));
for ($i=$pStart;$i <= $arResult['PAGER']['TOTAL'] && $i < $pStart + $arResult['PAGER']['LINKS'];$i++) {
    $arResult['PAGER']['PAGES']['NUM'][$i] = urldecode($APPLICATION->GetCurPageParam(($i != 1 ? 'PAGE=' . $i . $addParams : $addParams), array('PAGE')));
}
$arResult['PAGER']['PAGES']['LAST'] = urldecode($APPLICATION->GetCurPageParam('PAGE=' . $arResult['PAGER']['TOTAL'] . $addParams, array('PAGE')));

/*
ob_start();
$this->IncludeComponentTemplate('pager');
$arResult["NAV_STRING"] = ob_get_contents();
ob_end_clean();
*/

$this->IncludeComponentTemplate();