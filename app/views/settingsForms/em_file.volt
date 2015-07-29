{% extends "settingsForms/default.volt" %}
{% block fields %}
	<div class="editLine">
		<div class="name">Обязательное</div>
		<div class="inp">
			<input type="hidden" name="set[required]" value="0" />
			<input type="checkbox" name="set[required]" value="1" {% if settingFields['required'] == 1 %}checked{% endif %} />
		</div>
	</div>
	<div class="editLine">
		<div class="name">Множественное</div>
		<div class="inp">
			<input type="hidden" name="set[multiple]" value="0" />
			<input type="checkbox" name="set[multiple]" value="1" {% if settingFields['multiple'] == 1 %}checked{% endif %} />
		</div>
	</div>
	<div class="editLine">
		<div class="name">Где хранить файлы</div>
		<div class="inp">
			<input type="text" name="set[savePath]" value="{{settingFields['savePath']}}">
		</div>
	</div>
	<div class="editLine">
		<div class="name">Типы файлов</div>
		<div class="inp">
			<select name="set[fileTypes][]" multiple>
				{% for fileType in fileTypes %}
					<option value="{{fileType}}" {% if fileType in settingFields['fileTypes'] %}selected{% endif %}>{{fileType}}</option>
				{% endfor %}
			</select>
		</div>
	</div>
	<div class="editLine">
		<div class="name"><u>Копии изображений</u>&nbsp;&nbsp;&nbsp;<span class="icon addBtn"></span></div>
		<div class="inp">
			<label class="checkLab">
				<input type="checkbox" name="set[saveOriginalImage]" value="1" checked="" /> Не сохронять оригинал
			</label>
		</div>
	</div>
	<div class="editLine">
		<div class="inp imageSize">
			<input class="imsName" name="set[imageSizes][name]" type="text" placeholder="name" value="small" disabled readonly="true" />
			<input class="imsFix" name="set[imageSizes][fixed]" type="checkbox" checked="true" disabled readonly="true" />
			<input class="imsWidth" name="set[imageSizes][width]" type="text" placeholder="ширина" value="100" disabled readonly="true" />
			<input class="imsHeight" name="set[imageSizes][height]" type="text" placeholder="высота" value="100" disabled readonly="true" />
		</div>
	</div>
	<div class="editLine">
		<div class="inp imageSize">
			<input class="imsName" name="set[imageSizes][name]" type="text" placeholder="name" value="small" />
			<input class="imsFix" name="set[imageSizes][fixed]" type="checkbox" checked="true" />
			<input class="imsWidth" name="set[imageSizes][width]" type="text" placeholder="ширина" value="600" />
			<input class="imsHeight" name="set[imageSizes][height]" type="text" placeholder="высота" value="*" />
			<span class="icon addBtn"></span>
		</div>
	</div>
{% endblock %}