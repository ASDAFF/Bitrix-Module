<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/* @var $arParams array */
/* @var $arResult array */

// Модуль Макспотера
$moduleId = 'maxposter.api';
if (!CModule::IncludeModule($moduleId))
{
	ShowError(GetMessage('MAXPOSTER_API_MODULE_NOT_INSTALL'));
	return;
}

#var_dump($APPLICATION->GetCurPage());
#var_dump($APPLICATION->GetCurPageParam());
#$APPLICATION->AddChainItem("Это авто", '');

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
$arParams['VEHICLE_ID'] = (
    (ctype_digit($arParams['VEHICLE_ID']) && (0 < $arParams['VEHICLE_ID']))
    ? intval($arParams['VEHICLE_ID'])
    : $_REQUEST['VEHICLE_ID']
);
#var_dump($arParams);

$arUrlDefault = array(
    'URL_TEMPLATES_INDEX'   => '',
    'URL_TEMPLATES_VEHICLE' => 'VEHICLE_ID=#VEHICLE_ID#',
);
foreach ($arUrlDefault as $urlKey => $url) {
    if (!array_key_exists($urlKey, $arParams) || (0 >= strlen($arParams[$urlKey]))) {
        $arParams[$urlKey] = $APPLICATION->GetCurPage() . '?' . htmlspecialcharsbx($url);
    }
}

/************************** клиент, запрос к сервису **************************/
// Параметры запроса
$client = new maxCacheXmlClient(array(
    'api_version' => 1,
    'dealer_id'   => $dealerId,
    'password'    => $password,
    'cache_dir'   => $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . '/cache/maxposter/',
));
// Запрос к сервису / кешу
try {
    $client->setRequestThemeName($arParams['VEHICLE_ID']);
} catch (maxException $e) {
    CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404", "Y");
    ShowError(GetMessage('MAX_NOT_FOUND'));
    return;
}
$domXml = $client->getXml()->saveXML();
// при ошибке запроса
if ($client->getResponseThemeName() == 'error') {
    CHTTP::SetStatus("404 Not Found");
    ShowError(GetMessage('MAX_VEHICLE_NOT_FOUND'));
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

/* @var $vehicle CDataXMLNode */
$vehicle = $xml->SelectNodes('/response/vehicle');

// Формирование результатов
$arResult = array();
$arResult['ID']         = intval($arParams['VEHICLE_ID']);
$arResult['DEALER_ID']  = intval($vehicle->getAttribute('dealer_id'));
$arResult['MARK_ID']    = $xml->SelectNodes('/response/vehicle/mark')->getAttribute('mark_id');
$arResult['MARK_NAME']  = $xml->SelectNodes('/response/vehicle/mark')->textContent();
$arResult['MODEL_ID']   = $xml->SelectNodes('/response/vehicle/model')->getAttribute('model_id');
$arResult['MODEL_NAME'] = $xml->SelectNodes('/response/vehicle/model')->textContent();

// Заголовок на странице
if ($arParams['SET_TITLE']) {
    $appTitle = sprintf('%s %s', $arResult['MARK_NAME'], $arResult['MODEL_NAME']);
    $APPLICATION->SetTitle($appTitle);
    $APPLICATION->SetPageProperty('title', $appTitle);
}

// Основное
$arResult['YEAR']           = intval($xml->SelectNodes('/response/vehicle/year')->textContent());
$arResult['PRICE']          = $xml->SelectNodes('/response/vehicle/price/value')->textContent();
if ($pprice = $xml->SelectNodes('/response/vehicle/price/previous')) {
    $arResult['PRICE_OLD']  = $pprice->textContent();
}
$arResult['PRICE_UNIT']     = $xml->SelectNodes('/response/vehicle/price/value')->getAttribute('unit');
$arResult['PRICE_RUB']      = $xml->SelectNodes('/response/vehicle/price/value')->getAttribute('rub_price');
$arResult['PRICE_SPECIAL']  = ($xml->SelectNodes('/response/vehicle/price')->getAttribute('special') ? true : false);
$arResult['PRICE_NO_CUSTOMS'] = (bool) $xml->SelectNodes('/response/vehicle/price/without_customs');

$arResult['AVAILABILITY']   = $xml->SelectNodes('/response/vehicle/availability')->textContent();
$arResult['BODY_TYPE']      = $xml->SelectNodes('/response/vehicle/body/type')->textContent();
$arResult['BODY_COLOR']     = $xml->SelectNodes('/response/vehicle/body/color')->textContent();

$arResult['CONDITION']      = $xml->SelectNodes('/response/vehicle/condition')->textContent();

$arResult['DRIVE_TYPE']     = $xml->SelectNodes('/response/vehicle/drive/type')->textContent();

$arResult['DISTANCE']       = $xml->SelectNodes('/response/vehicle/mileage/value')->textContent();
$arResult['DISTANCE_UNIT']  = $xml->SelectNodes('/response/vehicle/mileage/value')->getAttribute('unit');
$arResult['DISTANCE_NO_RUSSIA'] = ($xml->SelectNodes('/response/vehicle/mileage/without_rus_mileage') ? true : false);

$arResult['ENGINE_TYPE']    = $xml->SelectNodes('/response/vehicle/engine/type')->textContent();
$arResult['ENGINE_VOLUME']  = $xml->SelectNodes('/response/vehicle/engine/volume')->textContent();
$arResult['ENGINE_POWER']   = (($power = $xml->SelectNodes('/response/vehicle/engine/power')) ? $power->textContent() : false);

$arResult['GEARBOX_TYPE']   = $xml->SelectNodes('/response/vehicle/gearbox/type')->textContent();
$arResult['PTS_OWNERS']     = $xml->SelectNodes('/response/vehicle/pts_owner_count')->textContent();

$arResult['WHEEL_PLACE']    = $xml->SelectNodes('/response/vehicle/steering_wheel/place')->textContent();

// Опции авто
$arResult['OPTIONS'] = array();
    $arOpt =& $arResult['OPTIONS'];
    if ($xml->SelectNodes('/response/vehicle/abs')) { $arOpt['ABS'] = true; }
    if ($xml->SelectNodes('/response/vehicle/asr')) { $arOpt['ASR'] = true; }
    if ($xml->SelectNodes('/response/vehicle/esp')) { $arOpt['ESP'] = true; }
    if ($xml->SelectNodes('/response/vehicle/parktronic')) { $arOpt['PARKTRONIC'] = true; }
    if ($airbag = $xml->SelectNodes('/response/vehicle/airbag')) { $arOpt['AIRBAG'] = $airbag->textContent(); }
    if ($xml->SelectNodes('/response/vehicle/alarm_system')) { $arOpt['ALARM_SYSTEM'] = true; }
    if ($xml->SelectNodes('/response/vehicle/central_lock')) { $arOpt['CENTRAL_LOCK'] = true; }
    if ($xml->SelectNodes('/response/vehicle/nav_system')) { $arOpt['NAVI'] = true; }
    if ($xml->SelectNodes('/response/vehicle/light_alloy_wheels')) { $arOpt['LIGHT_ALLOY_WHEELS'] = true; }
    if ($xml->SelectNodes('/response/vehicle/sensors/rain')) { $arOpt['SENSOR_RAIN'] = true; }
    if ($xml->SelectNodes('/response/vehicle/sensors/light')) { $arOpt['SENSOR_LIGHT'] = true; }
    if ($xml->SelectNodes('/response/vehicle/headlights/washer')) { $arOpt['LIGHTS_WASHER'] = true; }
    if ($xml->SelectNodes('/response/vehicle/headlights/xenon')) { $arOpt['LIGHTS_XENON'] = true; }
    if ($compartment = $xml->SelectNodes('/response/vehicle/compartment/decoration')) { $arOpt['COMPARTMENT'] = $compartment->textContent(); }
    if ($xml->SelectNodes('/response/vehicle/windows/tinted')) { $arOpt['WINDOWS_TINTED'] = true; }
    if ($xml->SelectNodes('/response/vehicle/hatch')) { $arOpt['HATCH'] = true; }
    if ($xml->SelectNodes('/response/vehicle/engine/gas_equipment')) { $arOpt['GAS'] = true; }
    if ($xml->SelectNodes('/response/vehicle/cruise_control')) { $arOpt['CRUISE_CONTROL'] = true; }
    if ($xml->SelectNodes('/response/vehicle/trip_computer')) { $arOpt['TRIP_COMPUTER'] = true; }
    if ($swPow = $xml->SelectNodes('/response/vehicle/steering_wheel/power')) { $arOpt['WHEEL_POWER'] = $swPow->textContent(); }
    if ($swAdj = $xml->SelectNodes('/response/vehicle/steering_wheel/adjustment')) { $arOpt['WHEEL_ADJUSTMENT'] = $swAdj->textContent(); }
    if ($xml->SelectNodes('/response/vehicle/steering_wheel/heater')) { $arOpt['WHEEL_HEATER'] = true; }
    if ($xml->SelectNodes('/response/vehicle/mirrors/power')) { $arOpt['MIRROR_POWER'] = true; }
    if ($xml->SelectNodes('/response/vehicle/mirrors/defroster')) { $arOpt['MIRROR_DEFROSTER'] = true; }
    if ($wPow = $xml->SelectNodes('/response/vehicle/windows/power')) { $arOpt['WINDOWS_POWER'] = $wPow->textContent(); }
    if ($xml->SelectNodes('/response/vehicle/seats/heater')) { $arOpt['SEAT_HEATER'] = true; }
    if ($seatDriver = $xml->SelectNodes('/response/vehicle/seats/driver_adjustment')) { $arOpt['SEAT_DRIVER'] = $seatDriver->textContent(); }
    if ($seatPassanger = $xml->SelectNodes('/response/vehicle/seats/passanger_adjustment')) { $arOpt['SEAT_PASSANGER'] = $seatPassanger->textContent(); }
    if ($climate = $xml->SelectNodes('/response/vehicle/climate_control')) { $arOpt['CLIMATE_CONTROL'] = $climate->textContent(); }
    if ($audio = $xml->SelectNodes('/response/vehicle/multimedia')) { $arOpt['MULTIMEDIA'] = $audio->textContent(); }

// Описание, контакты и проч.
$arResult['DESCRIPTION'] = '';
if ($description = $xml->SelectNodes('/response/vehicle/description')) {
    $description = $description->textContent();
    $description = mb_ereg_replace("\n{3,}", "\n\n", $description);
    $arResult['DESCRIPTION'] = str_replace(array("\n\n","\n"), array('</p><p>','<br>'), $description);
}
$arResult['INSPECTION'] = '';
if ($place = $xml->SelectNodes('/response/vehicle/inspection_place')) {
    $place = $place->textContent();
    $place = mb_ereg_replace("\n{3,}", "\n\n", $place);
    $arResult['INSPECTION'] = str_replace(array("\n\n","\n"), array('</p><p>','<br>'), $place);
}
$arResult['ADRESS'] = (($adress = $xml->SelectNodes('/response/vehicle/adress')) ? $adress->textContent() : '');

$phones = (($phones = $xml->SelectNodes('/response/vehicle/contact')) ? $phones->children() : array());
$arPhones = array();
foreach ($phones as $node) {
    if ('phone' != $node->name()) {
        continue;
    }
    $arPhones[] = $node->textContent()
        . (($ct = $node->getAttribute('from')) ? ' с ' . $ct . '-00' : '')
        . (($ct = $node->getAttribute('to')) ? ' до ' . $ct . '-00' : '')
    ;
}
$arResult['PHONES'] = $arPhones;

// Фотографии
$arResult['PHOTOS'] = array();
$localPath = rtrim($_SERVER["DOCUMENT_ROOT"], '/') . '/upload/maxposter/' . $arResult['DEALER_ID'] . '/' . $arResult['ID'] . '/original/';
$path1 = sprintf('%s/upload/maxposter/%d/%d/big/', rtrim($_SERVER["DOCUMENT_ROOT"], '/'), $arResult['DEALER_ID'], $arResult['ID']);
$path2 = sprintf('%s/upload/maxposter/%d/%d/small/', rtrim($_SERVER["DOCUMENT_ROOT"], '/'), $arResult['DEALER_ID'], $arResult['ID']);
$path3 = sprintf('%s/upload/maxposter/%d/%d/medium/', rtrim($_SERVER["DOCUMENT_ROOT"], '/'), $arResult['DEALER_ID'], $arResult['ID']);
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
$photos = $xml->SelectNodes('/response/vehicle/photos');
if ($photos) {
    foreach ($photos->children() as $i => $photo) {
        $fileName = $photo->getAttribute('file_name');
        $path = sprintf('http://maxposter.ru/photo/%d/%d/orig/%s', $arResult['DEALER_ID'], $arResult['ID'], $fileName);
        if (file_exists($localPath . $fileName)) {
            $arResult['PHOTOS'][] = $fileName;
            continue;
        }
        $from = fopen($path, 'rb', false);
        $to   = fopen($localPath . $fileName, 'wb', false);
        if (($bytes = stream_copy_to_stream($from, $to)) && ($bytes == @filesize($localPath . $fileName))) {
            $arResult['PHOTOS'][] = $fileName;
        }
        if (@file_exists($localPath . $fileName) && (10 > @filesize($localPath . $fileName))) {
            @unlink($localPath . $fileName);
        } else {
            @chmod($localPath . $fileName, 0666);
            $fileName1 = $path1 . $fileName;
            $fileName2 = $path2 . $fileName;
            $fileName3 = $path3 . $fileName;
            CFile::ResizeImageFile(
                $localPath . $fileName,
                $fileName1,
                $arSize1,
                BX_RESIZE_IMAGE_PROPORTIONAL,
                array(),
                80,
                false
            );
            CFile::ResizeImageFile(
                $localPath . $fileName,
                $fileName2,
                $arSize2,
                BX_RESIZE_IMAGE_PROPORTIONAL,
                array(),
                80,
                false
            );
            CFile::ResizeImageFile(
                $localPath . $fileName,
                $fileName3,
                $arSize3,
                BX_RESIZE_IMAGE_PROPORTIONAL,
                array(),
                80,
                false
            );
            @chmod($path1 . $fileName, 0666);
            @chmod($path2 . $fileName, 0666);
            @chmod($path3 . $fileName, 0666);
        }
        fclose($from);fclose($to);
    }
}

$this->IncludeComponentTemplate();
