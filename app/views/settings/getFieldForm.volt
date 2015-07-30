{% set checkboxFields = ['required','multiple','visualEditor'] %}
{% set inputFields = ['savePath'] %}
<div class="popupTopLine">
	<span class="name">Настройки поля - {{getFieldName}}</span>
	<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
</div>
<form id="fieldSettings" onsubmit="return false;" method="post">
	<div class="popupContLine">
		{{ partial("settingsForms/"~fieldFormType) }}
	</div>
	<div class="popupBottomLine">
		<input type="hidden" name="tableName" value="{{tableName}}"/>
		<input type="hidden" name="fieldName" value="{{getFieldName}}"/>
		<button class="elbutton blue" onclick="return el.settings.fieldSettingsSubmit(this);">Сохранить</button>
		<button class="elbutton gray" onclick="el.popup.hide();">Отмена</button>
	</div>
</form>
{{ partial("settingsForms/tpls") }}