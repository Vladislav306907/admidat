<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader; 
use Bitrix\Main\Context;
use Bitrix\Highloadblock as HL;

Loader::includeModule("highloadblock"); 

$request = Context::getCurrent()->getRequest();
$UF_LINK = $request->getPost('UF_LINK');
$UF_LINK_LIFETIME = $request->getPost('UF_LINK_LIFETIME');
$REQ_LIFETIME = $request->getPost('REQ_LIFETIME');
$UF_CONVERSION_STATISTICS = $request->getPost('UF_CONVERSION_STATISTICS');

$context = \Bitrix\Main\Application::getInstance()->getContext();
$server = $context->getServer();
$url = $server->getHttpHost(); 

$GLOBALS['permitted_chars'] = '0123456789abcdefghijklmnopqrstuvwxyz';
 
function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}

$UF_SHORT_LINK = $url.'/sh/?l='.generate_string($GLOBALS['permitted_chars'], 6);
$UF_CONVERSION_STATISTICS = ($UF_CONVERSION_STATISTICS == 'true') ? $url.'/sh/?s='.generate_string($GLOBALS['permitted_chars'], 6) : '';

$hlbl = 4;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 

$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList(array(
	"select" => array("*"),
	"filter" => array()
	));
while ($res = $rsData->fetch()) {
	$arShort[$res['ID']]['UF_SHORT_LINK'] = $res['UF_SHORT_LINK'];
	$arShort[$res['ID']]['UF_CONVERSION_STATISTICS'] = $res['UF_CONVERSION_STATISTICS'];
}

function searchRandShortLink($link, $arShort) {
	foreach ($arShort as $key => $value) {
		if($value['UF_SHORT_LINK'] == $link) {
			$UF_SHORT_LINK = $url.'/sh/?l='.generate_string($GLOBALS['permitted_chars'], 6);
			return searchRandShortLink($UF_SHORT_LINK, $arShort);
		}
	}
}
searchRandShortLink($UF_SHORT_LINK, $arShort);
function searchRandConvLink($link, $arShort) {
	foreach ($arShort as $key => $value) {
		if($value['UF_CONVERSION_STATISTICS'] == $link) {
			$UF_CONVERSION_STATISTICS = $url.'/sh/?s='.generate_string($GLOBALS['permitted_chars'], 6);
			return searchRandConvLink($UF_SHORT_LINK, $arShort);
		}
	}
}
searchRandConvLink($UF_CONVERSION_STATISTICS, $arShort);
$data = array(
	"UF_LINK" => $UF_LINK,
	"UF_LINK_LIFETIME" => ($REQ_LIFETIME == 'true') ? FormatDate("d.m.Y H:i:s", time()+$UF_LINK_LIFETIME*60) : '',
	"UF_SHORT_LINK" => $UF_SHORT_LINK,
	"UF_CONVERSION_STATISTICS" => $UF_CONVERSION_STATISTICS
);
$result = $entity_data_class::add($data);
if($result->getErrorMessages()){
	print_r(json_encode($result->getErrorMessages()));
} else {
	print_r(json_encode($data));
}
?>