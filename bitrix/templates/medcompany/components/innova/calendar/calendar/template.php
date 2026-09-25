<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?
$arResult['settings']['ajax'] = $templateFolder.'/ajax.php';
?>
<style>
.innova_calendar {
   display:  table-cell;
   height:  100px;
   vertical-align:  middle;
}

	.innova_calendar_popup_content_descripton a { 
		color: var(--color3);
}

.innova_calendar_filter_values {
    margin: 0 -5px 10px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.innova_calendar_filter_values .innova_calendar_filter_data {
    padding: 7px 14px;
    color: #000;
    font-weight: 400;
    border-radius: 10px;
    font-size: 16px;
    margin: 5px;
    transition: .5s;
    outline: none;
    cursor: pointer;
}

	.innova_calendar_filter_values .innova_calendar_filter_data.is-active {
		border: rgb(30, 89, 69)
	}

.innova_calendar_controls button, .innova_calendar_popup .innova_calendar_popup_content .innova_calendar_popup_content_inner .innova_calendar_popup_content_signup button {
    background-color: #1E5945 !important;
border-radius: 10px;
}
</style>
<div id="innova_calendar">
<innova-calendar :data='[]' :lang='<?=\Bitrix\Main\Web\Json::encode($arResult['lang'])?>' :settings='<?=\Bitrix\Main\Web\Json::encode($arResult['settings'])?>'></innova-calendar>
</div>