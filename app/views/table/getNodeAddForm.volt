<div class="popupTopLine">
	<span class="name">Добавление связи - {{fieldName}}</span>
	<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
</div>
<div class="popupContLine">
	<form id="nodeAddForm" onsubmit="return false;">
	<div class="editLine">
		<div class="name">Связь с таблицей - {{tableName}}</div>
		<div class="inp">
			<input autocomplete="off" data-autocompleteid="node" onkeyup="el.edit.autoComlete(this,'{{nodeTable}}','{{nodeField}}','{{nodeSearch}}')" type="text" name="nodetext" placeholder="Введите идентификатор"/>
			<input type="hidden" name="node">
		</div>
	</div>
</div>
<div class="popupBottomLine">
	<button onclick="el.edit.addNode(this, '{{fieldName}}', '{{tableName}}');" class="elbutton blue">Сохранить</button>
	<button onclick="el.popup.hide();" class="elbutton gray">Отмена</button>
</div>