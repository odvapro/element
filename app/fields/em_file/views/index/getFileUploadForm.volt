<div class="popupTopLine">
	<span class="name">Добавление файла - {{fieldName}}</span>
	<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
</div>
<div class="popupContLine">
	<div class="tabsLine">
		<div class="tab active" data-tab="1" onclick="el.popup.showTab(this)"> С компьютера </div>
		<div class="tab" data-tab="2" onclick="el.popup.showTab(this)"> По URL </div>
	</div>
	<div class="tabsCont">
		<div class="tabCont active" data-tab="1">
			<form id="fileUploadForm" onsubmit="return false;">
				<div class="editLine">
					<div class="name">Файл {% if fileTypes is defined %}({{fileTypes}}){% endif %}</div>
					<div class="inp">
						<input type="file" name="file" onchange="el.fileField.uplaodFile(event,'{{fieldName}}','{{tableName}}')" />
					</div>
				</div>
			</form>
		</div>
		<div class="tabCont" data-tab="2">
			<form onsubmit="el.fileField.uplaodFile(event,'{{fieldName}}','{{tableName}}','byUrl'); return false;">
				<div class="editLine">
					<div class="name">Файл {% if fileTypes is defined %}({{fileTypes}}){% endif %}</div>
					<div class="inp">
						<input id="fileUploadURL" type="text" name="file" />
					</div>
				</div>
				<div class="popupBottomLine">
					<button class="elbutton blue">Загрузить</button>
				</div>
			</form>
		</div>
	</div>
	<input type="hidden" name="uploadedPath" />
</div>