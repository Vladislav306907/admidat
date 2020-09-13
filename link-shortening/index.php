<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сокращатель ссылок");

$context = \Bitrix\Main\Application::getInstance()->getContext();
$server = $context->getServer();
$url = $server->getHttpHost();
?>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div id="form-generate">
			<label>Введите URL</label>
			<div class="input-group mb-3">
			  <div class="input-group-prepend">
			    <span class="input-group-text" id="basic-addon3"><?=$url?>/</span>
			  </div>
			  <input type="text" name="UF_LINK" class="form-control">
			</div>
			<label>Установить срок жизни ссылки (мин)</label>
			<div class="input-group mb-3">
			  <div class="input-group-prepend">
			    <div class="input-group-text">
			      <input type="checkbox" name="REQ_LIFETIME">
			    </div>
			  </div>
			  <input type="number" name="UF_LINK_LIFETIME" class="form-control">
			</div>
			<label>Получить ссылку на статистику переходов</label>
			<div class="input-group mb-3">
			  <div class="input-group-prepend">
			    <div class="input-group-text">
			      <input type="checkbox" name="UF_CONVERSION_STATISTICS">
			    </div>
			  </div>
			  <input type="text" disabled="" name="UF_CONVERSION_STATISTICS_LINK" class="form-control conversion">
			   <div class="input-group-append">
			    <button class="btn btn-outline-secondary btn-block" type="button" onclick="window.open(location.protocol+'//'+$('#form-generate .conversion').val(), '_blank');return false;">Перейти</button>
			</div>
			</div>
			<div class="input-group mb-3">
			    <button class="btn btn-outline-secondary btn-block" type="button" onclick="generateLink();return false;">Уменьшить</button>
			</div>
			<div class="input-group mb-3">
			  <div class="input-group-prepend">
			    <span class="input-group-text">Ваша короткая ссылка</span>
			  </div>
			  <input type="text" class="form-control short" disabled="" name="UF_SHORT_LINK">
			  <div class="input-group-append">
			    <button class="btn btn-outline-secondary btn-block" type="button" onclick="window.open(location.protocol+'//'+$('#form-generate .short').val(), '_blank');return false;">Перейти</button>
			</div>
			</div>
		</div>
		</div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>