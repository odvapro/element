<div class="popupTopLine">
	<span class="name">Переименование поля</span>
	<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
</div>
<form id="fieldSettings" onsubmit="el.settings.fieldName.save(this); return false;" method="post">
	<div class="popupContLine">
		<div class="editLine">
			<div class="name">Введите новое название поля</div>
			<div class="inp">
				<input data-autocompleteid="node" type="text" name="fieldNewName" value="{{fieldNewName}}" placeholder="Введите новое название">
			</div>
		</div>	
	</div>
	<div class="popupBottomLine">
		<input type="hidden" name="tableName" value="{{tableName}}"/> 
		<input type="hidden" name="fieldName" value="{{fieldName}}"/>
		<button class="elbutton blue" type="submit">Сохранить</button>
		<button class="elbutton gray" onclick="el.popup.hide(); return false;">Отмена</button>
	</div>
</form>
