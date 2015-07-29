{% block fields %}
	{% for fieldName,fieldVal in settingFields %}
		<div class="editLine">
			<div class="name">
			{% if fieldsPulicNames[fieldName] is defined %}
				{{fieldsPulicNames[fieldName]}}
			{% else %}
				{{fieldName}}
			{% endif %}
			</div>
			<div class="inp">
				{% if fieldName in checkboxFields %}
					<input type="hidden" name="set[{{fieldName}}]" value="0"/>
					<input type="checkbox" name="set[{{fieldName}}]" value="1" {% if fieldVal == 1 %}checked{% endif %} />
				{% elseif fieldName in inputFields %}
					<input type="text" name="set[{{fieldName}}]" value="{{fieldVal}}"/>
				{% elseif fieldName == "fileTypes" %}
					<select name="set[{{fieldName}}][]" multiple>
						{% for fileType in fileTypes %}
							<option value="{{fileType}}" {% if fileType in fieldVal %}selected{% endif %}>{{fileType}}</option>
						{% endfor %}
					</select>
				{% elseif fieldName == "nodeTable" %}
					<select name="set[{{fieldName}}]" onchange="el.settings.fieldSettingsTableChange(this);">
						<option value="0">Выберите таблицу</option>
						{% for tableName,tableArr in tables %}
							<option value="{{tableName}}" {% if fieldVal == tableName %}selected{% endif %}>{{tableArr['table_name']}}({{tableName}})</option>
						{% endfor %}
					</select>
					<script type="text/javascript">el.config.tables = {{tablesJSON}};</script>
				{% elseif fieldName == "nodeField" %}
					<select name="set[{{fieldName}}]">
						{% if fieldVal != "" %}
							<option value="{{fieldVal}}">{{fieldVal}}</option>
						{% else %}
							<option value="0">Выберите поле (прежде выберите таблицу)</option>
						{% endif %}
					</select>
				{% endif %}
			</div>
		</div>
	{% endfor  %}
{% endblock %}