<?php
$arClasses = array(
    'maxOption'         => 'classes/maxOption.php',
    'maxXmlClient'      => 'classes/maxXmlClient.php',
    'maxCacheXmlClient' => 'classes/maxCacheXmlClient.php',
    'maxException'      => 'classes/maxException.php',
);

CModule::AddAutoloadClasses('maxposter.api', $arClasses);

function maxPrice($price, $unit = 'rub') {
    $prefix = $postfix = '';
    switch ($unit) {
        case 'usd':
            $prefix = '�&nbsp;';
            break;
        case 'eur';
            $prefix = '$&nbsp;';
            break;
        default:
            $postfix = '&nbsp;���.';
            break;
    }
    $price = str_replace(' ', '&nbsp;', number_format($price, 0, '.', ' '));

    return sprintf('%2$s%1$s%3$s', $price, $prefix, $postfix);
}

//IncludeModuleLangFile(__FILE__);
