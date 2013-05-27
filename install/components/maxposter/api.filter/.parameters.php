<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentParameters = array(
    "GROUPS" => array(
        "URL_TEMPLATES" => array(
            "NAME" => GetMessage("MAX_URL_TEMPLATES"),
        ),
    ),
    "PARAMETERS" => array(
        'MAX_API_LOGIN' => array(
            'PARENT'  => 'BASE',
            'NAME'    => GetMessage('MAX_API_LOGIN'),
            'TYPE'    => 'STRING',
            'DEFAULT' => '',
        ),
        'MAX_API_PASSWORD' => array(
            'PARENT'  => 'BASE',
            'NAME'    => GetMessage('MAX_API_PASSWORD'),
            'TYPE'    => 'STRING',
            'DEFAULT' => '',
        ),

        'URL_TEMPLATES_INDEX' => array(
            'PARENT'  => 'URL_TEMPLATES',
            'NAME'    => GetMessage('MAX_URL_TEMPLATES_INDEX'),
            'TYPE'    => 'STRING',
            'DEFAULT' => 'index.php',
        ),

        "SET_STATUS_404" => array(
            "PARENT"  => "ADDITIONAL_SETTINGS",
            "NAME"    => GetMessage("SET_STATUS_404"),
            "TYPE"    => "CHECKBOX",
            "DEFAULT" => "N",
        ),
        'SET_TITLE' => array(),
    ),
);
