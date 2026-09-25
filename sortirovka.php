<? require($_SERVER["DOCUMENT_ROOT"]. "/bitrix/header.php");


//Подключаем модуль работы с инфоблоками
  CModule::IncludeModule('iblock');


//Уточняем какой будем использовать инфоблок и элементы с каким свойством нам нужны
  $arFilter = array(
  'IBLOCK_ID' => 16
  );
  
//Получаем массив всех элементов
  $res = CIBlockSection::GetList(false, $arFilter, array('IBLOCK_ID','ID'));
  $bs = new CIBlockSection;


//Перебираем все элементы инфоблока и обвляем им сортирвку
  // $arFields = Array(
  //    "SORT" => 500
  // );  
  // $i = 0;
  // while($el = $res->GetNext()): 
  // echo $el['NAME'] . ' id= ' . $el['ID'] . ' sort= ' . $el['SORT'] . '<br>';
  //   $bs->Update($el['ID'], $arFields); 
  // echo $el['NAME'] . 'id= ' . $el['ID'] . ' sort= ' . $el['SORT'] . '<br><br><br>';
  // $i++;

  // endwhile;


 

require($_SERVER["DOCUMENT_ROOT"]. "/bitrix/footer.php"); 
?>

