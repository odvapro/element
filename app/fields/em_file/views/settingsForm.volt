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
	<div class="name"><u>Копии изображений</u>&nbsp;&nbsp;&nbsp;<span onclick="el.settings.fieldFile.addImageSizeLine(this)" class="icon addBtn pointer"></span></div>
</div>
<div class="editLine">
	<div class="inp imageSize">
		<input class="imsName" name="set[imageSizes][0][name]" type="text" placeholder="name" value="small" readonly="true" />
		<input title="Фиксированное изображение" class="imsFix pointer" name="set[imageSizes][0][fixed]" type="hidden" value="1" />
		<input class="imsFix" name="set[imageSizes][0][fixed]" type="checkbox" value="1" checked="true" disabled="true" readonly="true" />
		<input class="imsWidth" name="set[imageSizes][0][width]" type="text" placeholder="ширина" value="50" readonly="true" />
		<input class="imsHeight" name="set[imageSizes][0][height]" type="text" placeholder="высота" value="50" readonly="true" />
	</div>
</div>
{% if settings['imageSizes'] is defined %}
	{% for isKey,imageSize in settings['imageSizes'] %}
		{% if isKey != 0 %}
			<div class="editLine">
				<div class="inp imageSize">
					<input class="imsName" name="set[imageSizes][{{isKey}}][name]" type="text" placeholder="name" value="{{imageSize['name']}}" />
					<input class="imsFix" name="set[imageSizes][{{isKey}}][fixed]" type="hidden" value="0" />
					<input title="Фиксированное изображение" class="imsFix pointer" name="set[imageSizes][{{isKey}}][fixed]" value="1" type="checkbox" {% if imageSize['fixed'] is defined and imageSize['fixed'] == 1 %}checked="true"{% endif %} />
					<input class="imsWidth" name="set[imageSizes][{{isKey}}][width]" type="text" placeholder="ширина" value="{{imageSize['width']}}" />
					<input class="imsHeight" name="set[imageSizes][{{isKey}}][height]" type="text" placeholder="высота" value="{{imageSize['height']}}" />
					<span onclick="el.settings.fieldFile.removeImageSizeLine(this)" class="icon deleteBtn"></span>
				</div>
			</div>
		{% endif %}
	{% endfor %}
{% endif %}
<div class="newImageSizesDelimetr"></div>
<div class="editLine">
	<button class="elbutton blue" onclick="return el.settings.fieldFile.reGenerate(this)">Перегенерировать картинки</button>
</div>

<div id="TPLS" style="display:none;">
	<div class="imageSizeTPL">
		<div class="editLine">
			<div class="inp imageSize">
				<input class="imsName" name="set[imageSizes][#key#][name]" type="text" placeholder="name"/>
				<input class="imsFix" name="set[imageSizes][#key#][fixed]" type="hidden" value="0" />
				<input class="imsFix" name="set[imageSizes][#key#][fixed]" value="1" type="checkbox" checked="true"/>
				<input class="imsWidth" name="set[imageSizes][#key#][width]" type="text" placeholder="ширина"  />
				<input class="imsHeight" name="set[imageSizes][#key#][height]" type="text" placeholder="высота" />
				<span onclick="el.settings.fieldFile.removeImageSizeLine(this)" class="icon deleteBtn"></span>
			</div>
		</div>
	</div>
</div>
<script src="{{baseUri}}fields/em_file/src/js/settings.js"></script>
