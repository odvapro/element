<div class="editLine">
	<div class="name">Ключ</div>
	<div class="inp">
		<select name="set[nodeFromFiled]">
			<option disabled value="0">Выберите ключ привязки</option>
			{% for colArr in curTableCols %}
				<option
					value="{{colArr['field']}}"
					{% if settingFields['nodeFromFiled'] == colArr['field'] %}
						selected
					{% endif %}
				>{{colArr['field']}}</option>
			{% endfor %}
		</select>
	</div>
</div>
<div class="editLine">
	<div class="name">Таблица привязки</div>
	<div class="inp">
		<select name="set[nodeTable]" onchange="el.settings.oneManyNode.changeTable(this);">
			<option value="0">Выберите таблицу</option>
			{% for fieldTableName,tableArr in tables %}
				<option
					value="{{fieldTableName}}"
					{% if settingFields['nodeTable'] == fieldTableName %}selected{% endif %}
				>{{tableArr['table_name']}}({{fieldTableName}})</option>
			{% endfor %}
		</select>
	</div>
</div>
<div class="editLine">
	<div class="name">Поле привязки</div>
	<div class="inp">
		<select name="set[nodeField]">
			{% if settingFields['nodeField'] != "" %}
				<option value="{{ settingFields['nodeField'] }}">{{ settingFields['nodeField'] }}</option>
			{% else %}
				<option value="0">...</option>
			{% endif %}
		</select>
	</div>
</div>
<div class="editLine">
	<div class="name">Имя привязки</div>
	<div class="inp">
		<select name="set[nodeName]">
			{% if settingFields['nodeName'] != "" %}
				<option value="{{ settingFields['nodeName'] }}">{{ settingFields['nodeName'] }}</option>
			{% else %}
				<option value="0">...</option>
			{% endif %}
		</select>
	</div>
</div>

<script type="text/javascript">el.config.tables = {{tablesJSON}};</script>
<script src="{{baseUri}}fields/em_one_many_node/src/settings.js"></script>