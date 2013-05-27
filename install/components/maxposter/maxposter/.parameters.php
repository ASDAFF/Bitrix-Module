<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
		"VARIABLE_ALIASES" => array(
			"VEHICLE_ID" => array(
                "NAME"      => GetMessage("VEHICLE_ID"),
                "DEFAULT"   => "VEHICLE_ID",
            ),
		),
		"SEF_MODE" => array(
			"list" => array(
				"NAME"      => GetMessage("MAX_URL_TEMPLATES_INDEX"),
				"DEFAULT"   => "",
				"VARIABLES" => array(),
			),
			"vehicle" => array(
				"NAME"      => GetMessage("MAX_URL_TEMPLATES_VEHICLE"),
				"DEFAULT"   => "#VEHICLE_ID#/",
				"VARIABLES" => array("VEHICLE_ID"=>"VEHICLE_ID"),
			),
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