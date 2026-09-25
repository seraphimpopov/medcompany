<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Iblock\SectionTable;

Loader::includeModule('iblock');


/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


//$element = \Bitrix\Iblock\Elements\ElementCatalogTable::getByPrimary($elementId, [
//  'select' => ['ID', 'NAME', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'ARTNUMBER'],
//])->fetch();