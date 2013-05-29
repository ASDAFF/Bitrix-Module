<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Модуль Макспотера
$moduleId = 'maxposter.api';
if (!CModule::IncludeModule($moduleId))
{
    ShowError(GetMessage('MAXPOSTER_API_MODULE_NOT_INSTALL'));
    return;
}

$arDefaultUrlTemplates404 = array(
    'list'      => 'index.php',
    'vehicle'   => '/#VEHICLE_ID#/',
);
$arDefaultVariableAliases404 = array();
$arDefaultVariableAliases = array(
    'VEHICLE_ID' => 'VEHICLE_ID',
);
$arComponentVariables = array(
    'VEHICLE_ID',
);

if($arParams["SEF_MODE"] == "Y") {
    $arVariables = array();
    $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
    $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

    $componentPage = CComponentEngine::ParseComponentPath(
        $arParams["SEF_FOLDER"],
        $arUrlTemplates,
        $arVariables
    );
	CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
	$arResult = array(
		"FOLDER" => $arParams["SEF_FOLDER"],
		"URL_TEMPLATES" => $arUrlTemplates,
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
} else {
    $arVariables = array();

    $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
    CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

    $componentPage = "";
    if (isset($arVariables["VEHICLE_ID"]) && intval($arVariables["VEHICLE_ID"]) > 0) {
        $componentPage = "vehicle";
    } else {
        $componentPage = "list";
    }

    $arResult = array(
        "FOLDER" => "",
        "URL_TEMPLATES" => Array(
            "list"    => htmlspecialcharsbx($APPLICATION->GetCurPage()),
            "vehicle" => htmlspecialcharsbx($APPLICATION->GetCurPage())."?".$arVariableAliases["VEHICLE_ID"]."=#VEHICLE_ID#",
        ),
        "VARIABLES" => $arVariables,
        "ALIASES" => $arVariableAliases,
    );
}

$arResult['MAX_API_LOGIN']    = $arParams['MAX_API_LOGIN'];
$arResult['MAX_API_PASSWORD'] = $arParams['MAX_API_PASSWORD'];

$this->IncludeComponentTemplate($componentPage);
