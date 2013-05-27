<?php

/* @var $APPLICATION CMain */
$APPLICATION->IncludeComponent(
    "maxposter:api.vehicle",
    "",
    Array(
         "VEHICLE_ID"               => $arResult['VARIABLES']['VEHICLE_ID'],
         "MAX_API_LOGIN"            => $arResult['MAX_API_LOGIN'],
         "MAX_API_PASSWORD"         => $arResult['MAX_API_PASSWORD'],
         "URL_TEMPLATES_INDEX"      => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['list'],
         "URL_TEMPLATES_VEHICLE"    => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['vehicle'],
         "SHOW_MARK_MODEL"          => "N",
         "SHOW_COLOR"               => "N",
         "SHOW_CUSTOMS"             => "N",
         "SHOW_CONDITION"           => "N",
         "SHOW_AVAILABILITY"        => "N",
         "SHOW_PTS"                 => "N",
         "SHOW_DESCRIPTION"         => "Y",
         "SHOW_INSPECTION"          => "Y",
         "SHOW_CONTACTS"            => "N",
         "SHOW_ADRESS"              => "N",
         "ADD_SECTIONS_CHAIN"       => $arParams['ADD_SECTIONS_CHAIN'],
         "SET_STATUS_404"           => $arParams['SET_STATUS_404'],
         "SET_TITLE"                => $arParams['SET_TITLE'],
    ),
    $component
);
