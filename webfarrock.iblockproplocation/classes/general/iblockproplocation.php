<?php

IncludeModuleLangFile(__FILE__);
// MOD
if (!class_exists("WebFarrockIblockPropLocation")) {

    trait WebFarrockPropLocation {

        // редактирование свойства в форме (главный модуль)
        function GetEditFormHTML($arUserField, $arHtmlControl) {
            return self::getEditHTML($arHtmlControl['NAME'], $arHtmlControl['VALUE'], false);
        }

        // редактирование свойства в списке (главный модуль)
        // TODO:
        function GetAdminListEditHTML($arUserField, $arHtmlControl) {
            return self::getViewHTML($arHtmlControl['NAME'], $arHtmlControl['VALUE'], true);
        }

        // представление свойства в списке (главный модуль, инфоблок)
        function GetAdminListViewHTML($arProperty, $value) {
            return self::getViewHTML('dcrm', $value['VALUE']);
        }

        // редактирование свойства в форме и списке (инфоблок)
        function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) {
            return $strHTMLControlName['MODE'] == 'FORM_FILL' ? self::getEditHTML($strHTMLControlName['VALUE'], $value['VALUE'], false) : self::getViewHTML($strHTMLControlName['VALUE'], $value['VALUE'])
            ;
        }

        //function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) {
        function getEditHTML($name, $value, $is_ajax = false) {

            if (!\CModule::IncludeModule('sale'))
                return false;

            global $APPLICATION;

            ob_start();

            $APPLICATION->IncludeComponent(
                    "webfarrock:sale.location.selector.search",
                    "search-in-admin",
                    array(
                        "COMPONENT_TEMPLATE" => "search",
                        "ID" => htmlspecialcharsbx($value),
                        "CODE" => "",
                        "INPUT_NAME" => htmlspecialcharsbx($name),
                        "PROVIDE_LINK_BY" => "id",
                        "JSCONTROL_GLOBAL_ID" => "",
                        "JS_CALLBACK" => "",
                        "SEARCH_BY_PRIMARY" => "Y",
                        "EXCLUDE_SUBTREE" => "",
                        "FILTER_BY_SITE" => "Y",
                        "SHOW_DEFAULT_LOCATIONS" => "Y",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000"
                    ),
                    false
            );

            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        }

        function getViewHTML($name, $value) {
            //function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName) {
            if (!\CModule::IncludeModule('sale'))
                return false;

            return \Bitrix\Sale\Location\Admin\LocationHelper::getLocationStringById($value);
        }

    }

    /**
     * Для пользовательских свойств
     */
    class WebFarrockUserPropLocation extends \CUserTypeInteger {

        use \WebFarrockPropLocation;

        static function GetUserTypeDescription() {
            return array(
                "USER_TYPE_ID" => "salelocation",
                "CLASS_NAME" => "WebFarrockUserPropLocation",
                "DESCRIPTION" => GetMessage('WEBFARROCK_IBLOCKPROP_LOCATION_PROP_NAME'),
                "BASE_TYPE" => "int",
            );
        }

    }

    /**
     * Для инфоблока
     */
    class WebFarrockIblockPropLocation {

        use \WebFarrockPropLocation;

        function GetUserTypeDescription() {
            return array(
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "salelocation",
                "DESCRIPTION" => \GetMessage('WEBFARROCK_IBLOCKPROP_LOCATION_PROP_NAME'),
                "GetPropertyFieldHtml" => array("WebFarrockIblockPropLocation", "GetPropertyFieldHtml"),
                "GetAdminListViewHTML" => array("WebFarrockIblockPropLocation", "GetAdminListViewHTML"),
                "GetPublicViewHTML" => array("WebFarrockIblockPropLocation", "GetAdminListViewHTML"),
            );
        }

    }

} // class exists

