<?php
if ($arParams['SEF_MODE'] == 'Y') {
    $arDefaultUrlTemplates404 = array(
        "list" => "",
        "detail" => "#ELEMENT_ID#/",
    );

    $componentPage = '';
    if (isset($_REQUEST['ELEMENT_ID'])) {
        $componentPage = 'detail';
    } else {
        $componentPage = 'list';
    }

    $APPLICATION->includeComponent(
        "seraphimpopov:news." . $componentPage,
        "",
        $arParams,
        $component
    );
}
?>
