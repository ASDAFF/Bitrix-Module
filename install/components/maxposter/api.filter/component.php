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
$arUrlDefault = array(
    'URL_TEMPLATES_INDEX'    => '',
);
foreach ($arUrlDefault as $urlKey => $url) {
    if (!array_key_exists($urlKey, $arParams) || (0 >= strlen($arParams[$urlKey]))) {
        $arParams[$urlKey] = $APPLICATION->GetCurPage() . '?' . htmlspecialcharsbx($url);
    }
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
$client->setRequestThemeName('search_form');

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

/* @var $sf CDataXMLNode */
$arResult['FORM'] = array();
$sf = $xml->SelectNodes('/response/search_form');
if ($sf) {
    $sfFields = $sf->children();
    foreach ($sfFields as $sfField) {
        $sfFieldName = $sfField->getAttribute('name');
        $sfFieldName = mb_substr($sfFieldName, 6);
        $arResult['FORM'][$sfFieldName] = array(
            'NAME' => $sfFieldName,
            'ID'   => 'fs_' . strtr($sfFieldName, array('[' => '_', ']' => '_')),
        );
        switch ($sfField->name()) {
            case 'list':
                $arResult['FORM'][$sfFieldName]['TYPE'] = 'LIST';
                $sfFieldOpts = $sfField->children();
                foreach ($sfFieldOpts as $sfFieldOption) {
                    // для моделей
                    if ('optgroup' == $sfFieldOption->name()) {
                        foreach ($sfFieldOption->children() as $sfFieldOptg) {
                            $arResult['FORM'][$sfFieldName]['OPTIONS'][] = array(
                                'NAME'   => $sfFieldOptg->getAttribute('value'),
                                'VALUE'  => $sfFieldOptg->textContent(),
                                'GROUP'  => $sfFieldOption->getAttribute('label'),
                                'PARENT' => $sfFieldOption->getAttribute('mark_id'),
                            );
                        }
                    } else {
                        $arResult['FORM'][$sfFieldName]['OPTIONS'][] = array(
                            'NAME'  => $sfFieldOption->getAttribute('value'),
                            'VALUE' => $sfFieldOption->textContent(),
                        );
                    }
                }
                //var_dump($sfFieldOpts);
                break;
            case 'field':
                $arResult['FORM'][$sfFieldName]['TYPE']  = 'INPUT';
                $arResult['FORM'][$sfFieldName]['VALUE'] = $sfField->getAttribute('value');
                $arResult['FORM'][$sfFieldName]['SIZE']  = $sfField->getAttribute('size');
                break;
        }
        //var_dump($sfField);
    }
}

$arResult['URL'] = CComponentEngine::MakePathFromTemplate($arParams['URL_TEMPLATES_INDEX'], array());
$arResult['SELECTED'] = $filterParams;

$this->IncludeComponentTemplate();
