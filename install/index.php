<?php
/**
 *
 */
global $MESS;
#IncludeModuleLangFile(__FILE__);
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include (GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

class maxposter_api extends CModule
{
    public $MODULE_ID           = 'maxposter.api';
    public $MODULE_VERSION      = '1.0';
    public $MODULE_VERSION_DATE = '';
    public $MODULE_NAME         = 'Maxposter API';
    public $MODULE_DESCRIPTION  = '';
    public $PARTNER_NAME        = 'maxposter_mod';
    public $PARTNER_URI         = 'http://maxposter.ru';


    public function __construct()
    {
        require(dirname(__FILE__) . '/version.php');
        $this->MODULE_VERSION       = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE  = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME          = GetMessage('maxposter_api_MODULE_NAME');
        $this->MODULE_DESCRIPTION   = GetMessage('maxposter_api_MODULE_DESC');
        $this->PARTNER_NAME         = GetMessage('maxposter_api_MODULE_PRTN');
    }

    public function DoInstall()
    {
        if (!check_bitrix_sessid()) {
            return false;
        }
        $modPath = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $this->MODULE_ID . "/install/components";
        $comPath = $_SERVER["DOCUMENT_ROOT"]."/bitrix/components";
        CopyDirFiles($modPath, $comPath, true, true);

        CheckDirPath($_SERVER["DOCUMENT_ROOT"]."/bitrix/images/maxposter/", true, true);
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $this->MODULE_ID . "/install/images",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/maxposter",
            true,
            true
        );
        RegisterModule($this->MODULE_ID);

        LocalRedirect("settings.php?lang=" . LANGUAGE_ID . '&mid=' . $this->MODULE_ID);
        return true;
    }

    public function DoUninstall()
    {
        if (!check_bitrix_sessid()) {
            return false;
        }
        UnRegisterModule($this->MODULE_ID);
        $modPath = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $this->MODULE_ID . "/install/components";
        $comPath = $_SERVER["DOCUMENT_ROOT"]."/bitrix/components";
        DeleteDirFiles($modPath, $comPath);
        DeleteDirFiles(
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $this->MODULE_ID . "/install/images",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/maxposter"
        );

        LocalRedirect("partner_modules.php?lang=" . LANGUAGE_ID);
        return true;
    }
}
