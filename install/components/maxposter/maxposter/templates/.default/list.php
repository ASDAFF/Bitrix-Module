<?php

/* @var $APPLICATION CMain */
$APPLICATION->IncludeComponent(
    "maxposter:api.filter",
    "",
    Array(
         "MAX_API_LOGIN"            => $arResult['MAX_API_LOGIN'],
         "MAX_API_PASSWORD"         => $arResult['MAX_API_PASSWORD'],
         "URL_TEMPLATES_INDEX"      => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['list'],
         "SET_STATUS_404"           => $arParams['SET_STATUS_404'],
         "SET_TITLE"                => $arParams['SET_TITLE'],
    ),
    $component
);
?><br /><?php
$APPLICATION->IncludeComponent(
    "maxposter:api.list",
    "",
    Array(
         "MAX_API_LOGIN"            => $arResult['MAX_API_LOGIN'],
         "MAX_API_PASSWORD"         => $arResult['MAX_API_PASSWORD'],
         "URL_TEMPLATES_INDEX"      => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['list'],
         "URL_TEMPLATES_VEHICLE"    => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['vehicle'],
         "ADD_SECTIONS_CHAIN"       => $arParams['ADD_SECTIONS_CHAIN'],
         "SET_STATUS_404"           => $arParams['SET_STATUS_404'],
         "SET_TITLE"                => $arParams['SET_TITLE'],
    ),
    $component
);