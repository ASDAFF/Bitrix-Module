<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule("iblock");

$arIBlockType = CIBlockParameters::GetIBlockTypes();

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
        'URL_TEMPLATES_VEHICLE' => array(
            'PARENT'  => 'URL_TEMPLATES',
            'NAME'    => GetMessage('MAX_URL_TEMPLATES_VEHICLE'),
            'TYPE'    => 'STRING',
            'DEFAULT' => 'vehicle.php?VEHICLE_ID=#VEHICLE_ID#',
        ),

        "ADD_SECTIONS_CHAIN" => array(
            "PARENT"  => "ADDITIONAL_SETTINGS",
            "NAME"    => GetMessage("ADD_SECTIONS_CHAIN"),
            "TYPE"    => "CHECKBOX",
            "DEFAULT" => "Y",
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
