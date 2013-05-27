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
        "VEHICLE_ID" => array(
            "PARENT"  => "BASE", # BASE | DATA_SOURCE
            "NAME"    => GetMessage('VEHICLE_ID'),
            "TYPE"    => 'INT',
            "DEFAULT" => '={$_REQUEST["VEHICLE_ID"]}',
        ),
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

        'SHOW_MARK_MODEL' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_MARK_MODEL'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'SHOW_COLOR' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_COLOR'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'SHOW_CUSTOMS' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_CUSTOMS'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'SHOW_CONDITION' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_CONDITION'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'SHOW_AVAILABILITY' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_AVAILABILITY'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'SHOW_PTS' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_PTS'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),

        'SHOW_DESCRIPTION' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_DESCRIPTION'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SHOW_INSPECTION' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_INSPECTION'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'SHOW_CONTACTS' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_CONTACTS'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'SHOW_ADRESS' => array(
            'PARENT'  => 'VISUAL',
            'NAME'    => GetMessage('SHOW_ADRESS'),
            'TYPE'    => 'CHECKBOX',
            'DEFAULT' => 'N',
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
