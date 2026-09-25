<?

define("NO_KEEP_STATISTIC", true);
define('PUBLIC_AJAX_MODE', true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

$_POST = json_decode(file_get_contents("php://input"),true);

if($_POST['action'] == 'getDayEvents') {

	if(empty($_POST['iblock_id'])) die();

	$begin = $end = '';

	$begin_data = $_POST['days'][0];
	$begin_array = explode('_', $begin_data);
	$begin .= ($begin_array[0] + 1900).'-';
	$begin_array[1] = $begin_array[1] + 1;
	if($begin_array[1] < 10) {
		$begin .= '0';
	}
	$begin .= $begin_array[1].'-';
	if($begin_array[2] < 10) {
		$begin .= '0';
	}
	$begin .= $begin_array[2];

	$end_data = end($_POST['days']);
	$end_array = explode('_', $end_data);
	$end .= ($end_array[0] + 1900).'-';
	$end_array[1] = $end_array[1] + 1;
	if($end_array[1] < 10) {
		$end .= '0';
	}
	$end .= $end_array[1].'-';
	if($end_array[2] < 10) {
		$end .= '0';
	}
	$end .= $end_array[2];

	$begin_data = strtotime($begin);
	$end_data = strtotime($end);

	$data = array($_POST['days']);

	$calendar_filter = array('ACTIVE' => 'Y', 'IBLOCK_ID' => $_POST['iblock_id'], '<=PROPERTY_BEGIN' => $end, '>=PROPERTY_END' => $begin, '=PROPERTY_DUPLICATE_VALUE' => 'Y' );

	if(!empty($_POST['filter'])) {
		$filter_data = array();
		$filter_code = '';
		foreach($_POST['filter'] as $filter_value) {
			if($filter_value['selected']) {
				$filter_data[] = $filter_value['id'];
				$filter_code = $filter_value['code'];
			}
		}

		if(!empty($filter_data) and !empty($filter_code)) {
			$calendar_filter['PROPERTY_'.$filter_code] = $filter_data;
		}
	}

	$res = CIBlockElement::GetList(array('PROPERTY_BEGIN' => 'ASC'), $calendar_filter, false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE"));

	while($ob = $res->GetNextElement()){ 
		$arFields = $ob->GetFields();  
		$arProps = $ob->GetProperties();

		if(empty($arProps['BEGIN']) && empty($arProps['BEGIN']['VALUE']))
			continue;

		if(empty($arProps['END']) && empty($arProps['END']['VALUE']))
			continue;

		$event_begin = strtotime($arProps['BEGIN']['VALUE']);

		$event_end = strtotime($arProps['END']['VALUE']);

		$arProps['BEGIN']['VALUE'] = date('d.m.Y G:i', $event_begin);
		$arProps['END']['VALUE'] = date('d.m.Y G:i', $event_end);

		$event_begin_day = strtotime(date('Y-m-d', $event_begin));
		$event_end_day = strtotime(date('Y-m-d', $event_end));

		$number_of_days = round(($event_end_day - $event_begin_day) / (60 * 60 * 24));

		$event_data = array(
			'id' => $arFields['ID'],
			'time' => '',
			'name' => $arFields['NAME'],
			'description' => $arFields['~PREVIEW_TEXT'],
		);

		if(!empty($arFields['PREVIEW_PICTURE']))
			$event_data['image'] = CFile::GetPath($arFields['PREVIEW_PICTURE']);

		if(!empty($arProps['COLOR']) && !empty($arProps['COLOR']['VALUE']))
			$event_data['color'] = $arProps['COLOR']['VALUE'];

		if(!empty($arProps['FONT_COLOR']) && !empty($arProps['FONT_COLOR']['VALUE']))
			$event_data['font_color'] = $arProps['FONT_COLOR']['VALUE'];

		if(!empty($arProps['SIGN_UP']) && $arProps['SIGN_UP']['VALUE'] == "Y")
			$event_data['sign_up_form'] = true;

		unset($arProps['COLOR']);
		unset($arProps['SIGN_UP']);
		unset($arProps['FONT_COLOR']);

		foreach($arProps as $arProp) {
			if($arProp['PROPERTY_TYPE'] != 'S') continue;

			if(!empty($arProp['VALUE'])) {
				$event_data['props'][] = array(
					'name' => $arProp['NAME'],
					'value'=> $arProp['VALUE']
				);
			}
		}
		$current_day = $event_begin_day + (60 * 60 * 24);
		for ($i = 0; $i < $number_of_days; $i++) {
			if($current_day <= $end_data && $current_day >= $begin_data && $event_begin_day != $current_day) {
			    $day_key = (date('Y', $current_day) - 1900).'_'.(date('n', $current_day) - 1).'_'.date('j', $current_day);

			    $event_data['key'] = $event_data['id'].'_'.$day_key;

				$data[$day_key][] = $event_data;
			}
			$current_day = $current_day + (60 * 60 * 24);
		}
	}

	$calendar_filter = array('ACTIVE' => 'Y', 'IBLOCK_ID' => $_POST['iblock_id'], '>=PROPERTY_BEGIN' => $begin, '<=PROPERTY_BEGIN' => $end );

	if(!empty($_POST['filter'])) {
		$filter_data = array();
		$filter_code = '';
		foreach($_POST['filter'] as $filter_value) {
			if($filter_value['selected']) {
				$filter_data[] = $filter_value['id'];
				$filter_code = $filter_value['code'];
			}
		}

		if(!empty($filter_data) and !empty($filter_code)) {
			$calendar_filter['PROPERTY_'.$filter_code] = $filter_data;
		}
	}

	$res = CIBlockElement::GetList(array('PROPERTY_BEGIN' => 'ASC'), $calendar_filter, false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE"));

	while($ob = $res->GetNextElement()){ 
		$arFields = $ob->GetFields();  
		$arProps = $ob->GetProperties();

		if(empty($arProps['BEGIN']) && empty($arProps['BEGIN']['VALUE']))
			continue;

		$event_begin = strtotime($arProps['BEGIN']['VALUE']);

		$arProps['BEGIN']['VALUE'] = date('d.m.Y G:i', $event_begin);

		if(!empty($arProps['END']) && !empty($arProps['END']['VALUE'])) {
			$event_end = strtotime($arProps['END']['VALUE']);

			$arProps['END']['VALUE'] = date('d.m.Y G:i', $event_end);
		}

		$event_data = array(
			'id' => $arFields['ID'],
			'time' => date('G:i', $event_begin),
			'name' => $arFields['NAME'],
			'description' => $arFields['~PREVIEW_TEXT'],
		);

		if(!empty($arFields['PREVIEW_PICTURE']))
			$event_data['image'] = CFile::GetPath($arFields['PREVIEW_PICTURE']);

		if(!empty($arProps['COLOR']) && !empty($arProps['COLOR']['VALUE']))
			$event_data['color'] = $arProps['COLOR']['VALUE'];

		if(!empty($arProps['FONT_COLOR']) && !empty($arProps['FONT_COLOR']['VALUE']))
			$event_data['font_color'] = $arProps['FONT_COLOR']['VALUE'];

		if(!empty($arProps['SIGN_UP']) && $arProps['SIGN_UP']['VALUE'] == "Y")
			$event_data['sign_up_form'] = true;

		unset($arProps['COLOR']);
		unset($arProps['SIGN_UP']);
		unset($arProps['FONT_COLOR']);

		foreach($arProps as $arProp) {
			if($arProp['PROPERTY_TYPE'] != 'S') continue;

			if(!empty($arProp['VALUE'])) {
				$event_data['props'][] = array(
					'name' => $arProp['NAME'],
					'value'=> $arProp['VALUE']
				);
			}
		}

		$day_key = (date('Y', $event_begin) - 1900).'_'.(date('n', $event_begin) - 1).'_'.date('j', $event_begin);

		$event_data['key'] = $event_data['id'].'_'.$day_key;

		$data[$day_key][] = $event_data;
	}

	$array = array();
	foreach($_POST['days'] as $day) {
		if(!empty($data[$day])) {
			$array[$day] = $data[$day];
		} else {
			$array[$day] = array();
		}
	}

	echo '['.\Bitrix\Main\Web\Json::encode(array('events' => $array)).']';
} elseif ($_POST['action'] == 'newSignUp') {

	if(LANG_CHARSET != 'UTF-8') {
		foreach($_POST as $key=>$request) {
			if(!is_array($request)) {
				$_POST[$key] = iconv( "UTF-8", "CP1251", $request);
			} else {
				foreach($request as $key2=>$request2) {
					$_POST[$key][$key2] = iconv( "UTF-8", "CP1251", $request2);
				}
			}
		}
	}

	$eventFields = array(
		'NAME' => $_POST['data']['name'],
		'PHONE' => $_POST['data']['phone'],
		'EMAIL' => $_POST['data']['email'],
		'EVENT' => $_POST['event_name'].' ('.$_POST['event_id'].')',
	);
	CEvent::Send("INNOVA_CALENDAR_NEW_SIGN_UP", htmlspecialcharsbx($_POST['site_id']), $eventFields);
}

?>