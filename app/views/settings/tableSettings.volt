<table data-tablename="{{relaTableName}}" class="settings" cellspacing='0'>
	<tr>
		<th class="tableName" colspan="2">
			<input type="text" onkeyup="el.settings.refreshTableName(this);" onblur="el.settings.hideEditTable(this);" name="tables[{{relaTableName}}][name]" value="{{tableDetail['table_name']}}"/>
			<span class="tableNamePlace">{{tableDetail['table_name']}}</span> <span onclick="el.settings.showEditTable(this);" class="icon editButton"></span>
		</th>
		<th class="righted" colspan="3">
			{{relaTableName}}
			<input type="hidden" name="tables[{{relaTableName}}][show]" value="0"/>
			<label onclick="el.settings.toggleShowTable(this)" class="showTable {%if tableDetail['show'] is defined and tableDetail['show'] == 1 %}checked{% endif %}">
				<i class="fa fa-eye" aria-hidden="true"></i>
				<input type="checkbox" {%if tableDetail['show'] is defined and tableDetail['show'] == 1 %}checked{% endif %} name="tables[{{relaTableName}}][show]" value="1"/>
			</label>
		</th>
	</tr>
	<tr class="black">
		<td>Настройки</td>
		<td>Наименование</td>
		<td>Тип поля</td>
		<td>Обязательное</td>
	</tr>
	{% for field in tableDetail['fields'] %}
		<tr data-fieldname="{{field['field']}}">
			<td class="centered">
				{% if field['type'] in EmTypesCodes %}
					<div class="editLine">
						<a href="javascript:void(0)" onclick="el.settings.showFieldSetiings(this,'{{field['field']}}')">Настройки</a>
						<a href="javascript:void(0)">Копировать</a>
						<a href="javascript:void(0)">Удалить</a>
					</div>
					<button class="elbutton dotts"><span class="icon buttonDotts"></span></button>
				{% else %}
					–
				{% endif %}
			</td>
			<td>
				<span class="ename _ename">{{(field['ename']!='')?field['ename']:field['field']}}</span>
				<span class="name">{{field['field']}}</span>
				<div onclick="el.settings.fieldName.showEdit(this)" class="nameEditButton">
					<i class="fa fa-pencil" aria-hidden="true"></i>
				</div>
			</td>
			<td>
				{% if field['key'] != "PRI" %}
					<select name="tables[{{relaTableName}}][fields][{{field['field']}}][type]">
						{% if field['type'] not in EmTypesCodes %}
							<option value="notsys">{{field['type']}}</option>
						{% endif %}
						{% for EmTypeCode, EmType in EmTypes %}
							<option value="{{EmTypeCode}}" {% if field['type'] == EmTypeCode %}selected="true"{% endif %}>{{EmType['name']}}</option>
						{% endfor %}
					</select>
				{% else %}
					PRIMARY KEY
					<input type="hidden" name="tables[{{relaTableName}}][fields][{{field['field']}}][type]" value="notsys">
				{% endif %}
			</td>
			<td class="centered">
				<input type="checkbox" name="tables[{{relaTableName}}][fields][{{field['field']}}][required]" {% if (field['required'] is defined and field['required'] == 1) or field['null'] == "NO" %}checked="true"{% endif %} />
			</td>
		</tr>
	{% endfor %}
</table>
<div class="addFieldLine">
{#
#todo
	<button class="addField">Добавить поле</button>
#}
</div>
<div class="clear"></div>