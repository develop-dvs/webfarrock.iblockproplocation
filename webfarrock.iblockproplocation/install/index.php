<?php

IncludeModuleLangFile(__FILE__);

Class webfarrock_iblockproplocation extends CModule {

    var $MODULE_ID = "webfarrock.iblockproplocation";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";
    var $PARTNER_URI;

    function webfarrock_iblockproplocation() {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = GetMessage('WEBFARROCK_IBLOCKPROP_LOCATION_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('WEBFARROCK_IBLOCKPROP_LOCATION_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = "webFarrock";
        $this->PARTNER_URI = "http://webfarrock.ru";
    }

    function InstallFiles() {
        CopyDirFiles(__DIR__ . "/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
    }

    function InstallDB($install_wizard = true) {
        RegisterModule($this->MODULE_ID);
        return true;
    }

    function UnInstallDB($arParams = Array()) {
        UnRegisterModule($this->MODULE_ID);
        return true;
    }

    function DoInstall() {

        if (!IsModuleInstalled("sale")) {
            global $APPLICATION;
            echo __DIR__;
            $APPLICATION->IncludeAdminFile(GetMessage("WEBFARROCK_IBLOCKPROP_LOCATION_INSTALL_TITLE"), __DIR__ . "/error.php");
            return false;
        } else {

            RegisterModuleDependences("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, "WebFarrockIblockPropLocation", "GetUserTypeDescription");
            RegisterModuleDependences("main", "OnUserTypeBuildList", $this->MODULE_ID, "WebFarrockUserPropLocation", "GetUserTypeDescription"); // MOD

            $this->InstallDB(false);
            $this->InstallFiles();
        }
    }

    function DoUninstall() {

        UnRegisterModuleDependences("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, "WebFarrockIblockPropLocation", "GetUserTypeDescription");
        RegisterModuleDependences("main", "OnUserTypeBuildList", $this->MODULE_ID, "WebFarrockUserPropLocation", "GetUserTypeDescription"); // MOD

        $this->UnInstallDB();
        $this->UnInstallFiles();
    }

}
