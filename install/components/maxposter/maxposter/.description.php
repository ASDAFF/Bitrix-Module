<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    "NAME"        => GetMessage("COMP_NAME"),
    "DESCRIPTION" => GetMessage("COMP_DESCR"),
    "ICON"        => "/images/icon.gif",
    "SORT"        => 10,
    "PATH"        => array(
        "ID"          => "MaxPoster",
        "NAME"        => GetMessage('MAX_NAME'),
    ),
    "CACHE_PATH"  => '', # Y or /path/to/cache
    "COMPLEX"     => "Y",
);
