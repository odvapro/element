<div class="popupTopLine">
	<span class="name">Переименование таблицы</span>
	<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
</div>
<form id="fieldSettings" onsubmit="el.settings.table.save(this); return false;" method="post">
	<div class="popupContLine">
		<div class="editLine">
			<div class="name">Введите новое название таблицы</div>
			<div class="inp">
				<input data-autocompleteid="node" type="text" name="tableName" value="{{ tableInfo['name'] }}" placeholder="Введите новое название" />
			</div>
		</div>
	</div>
	<div class="popupBottomLine">
		<input type="hidden" name="tableCode" value="{{ tableInfo['table'] }}"/>
		<button class="elbutton blue" type="submit">Сохранить</button>
		<button class="elbutton gray" onclick="el.popup.hide(); return false;">Отмена</button>
	</div>
</form>
