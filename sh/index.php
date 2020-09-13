<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader; 
use Bitrix\Main\Context;
use Bitrix\Highloadblock as HL;

Loader::includeModule("highloadblock"); 

$context = \Bitrix\Main\Application::getInstance()->getContext();
$server = $context->getServer();
$request = Context::getCurrent()->getRequest();
$link = $server->getHttpHost().$server->getRequestUri();

$hlblock = HL\HighloadBlockTable::getById(4)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass(); 

if($request->get("l")){
	$rsData = $entity_data_class::getList(array(
		"select" => array("*"),
		"filter" => array('UF_SHORT_LINK' => $link, '>UF_LINK_LIFETIME' => date('d.m.Y H:i:s'))
	))->fetch();
	if($rsData){
		$stat = json_decode($rsData['UF_STATISTICS'], true);
		$client = array(
			'DATA' => date('d.m.Y H:i:s'),
			'GEO' => 'Y',
			'BROWSER' => $request->getUserAgent(),
		);
		$stat[] = $client;
		$data = array(
			"UF_STATISTICS" => json_encode($stat)
		);
		$result = $entity_data_class::update($rsData['ID'],$data);
		LocalRedirect('/'.$rsData['UF_LINK'], false, bExternal);
	} else {
		echo "Данной короткой ссылки не существует, попробуйте указать другую ссылку";
	}
}
if($request->get("s")){
	$rsData = $entity_data_class::getList(array(
		"select" => array("*"),
		"filter" => array('UF_CONVERSION_STATISTICS' => $link, '>UF_LINK_LIFETIME' => date('d.m.Y H:i:s'))
	))->fetch();
	if($rsData){
		$stat = json_decode($rsData['UF_STATISTICS'], true);
		$count = 1;
		$table ='<table class="table">
		  <thead>
		    <tr>
		      <th scope="col">№</th>
		      <th scope="col">Дата</th>
		      <th scope="col">Гео</th>
		      <th scope="col">Браузер</th>
		    </tr>
		  </thead>
		  <tbody>';
		foreach ($stat as $value) {
			$table .= '<tr><th scope="row">'.$count.'</th><td>'.$value['DATA'].'</td><td>'.$value['GEO'].'</td><td>'.$value['BROWSER'].'</td>';
			$count++;
		}
		$table .= '</tbody>
			</table>';
		echo $table;
	} else {
		echo "Несуществующая ссылка для сбора статистики";
	}
}
?>
