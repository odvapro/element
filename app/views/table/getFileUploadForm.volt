<div class="popupTopLine">
	<span class="name">Добавление файла - {{fieldName}}</span>
	<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
</div>
<div class="popupContLine">
	<form id="fileUploadForm" onsubmit="return false;">
	<div class="editLine">
		<div class="name">Файл {% if fileTypes is defined %}({{fileTypes}}){% endif %}</div>
		<div class="inp">
			<input type="file" name="file" onchange="el.edit.uplaodFile(event,'{{fieldName}}','{{tableName}}')" />
			<input type="hidden" name="uploadedPath" />
		</div>
	</div>
</div>