<div class="editLine">
	<div class="name">
		Обязательное
	</div>
	<div class="inp">
		<input type="hidden" name="set[required]" value="0">
		<input type="checkbox" name="set[required]" value="1">
	</div>
</div>
<table class="multy_node_table">
	<tr>
		<th>Название столбца</th>
		<th>Тип поля</th>
		<th>Справочник</th>
		<th>Удалить</th>
	</tr>
	{% for colIndex,col in cols %}
		<tr>
			<td><input value="{{col['name']}}" name="set[cols][{{colIndex}}][name]" type="text" name=""></td>
			<td>
				<select name="set[cols][{{colIndex}}][type]" onchange="el.emMultyNode.settings.changeType(this)">
					{% for colTypeCode,colType in colTypes %}
						<option {% if colTypeCode == col['type'] %}selected{% endif %} value="{{colTypeCode}}">{{colType}}</option>
					{% endfor %}
				</select>
			</td>
			<td class="centered tableCol">
				{% if col['type'] == 'select_from_table' %}
					<select name="set[cols][{{colIndex}}][table]" data-namemask="set[cols][#number#][table]">
						{% for fieldTableName,tableArr in tables %}
							<option  {% if fieldTableName == col['table'] %}selected{% endif %} value="{{fieldTableName}}">{{tableArr['table_name']}}({{fieldTableName}})</option>
						{% endfor %}
					</select>
				{% else %}
					- 
				{% endif %}
			</td>
			<td class="centered"><span onclick="el.emMultyNode.settings.removeCol(this)" class="delete icon deleteBtn"></span></td>
		</tr>
	{% endfor %}
	<tr>
		<td colspan="4" class="centered"><a href="javascript:void(0)" onclick="el.emMultyNode.settings.addCol(this)"><span class="icon addBtn"></span></a></td>
	</tr>
</table>
<div id="multyTableTPLS" style="display:none;">
	<div class="emptyColWrap">
		<table>
			<tbody class="emptyCol">
				<tr>
					<td><input type="text" name="set[cols][#num#][name]" data-namemask="set[cols][#number#][name]"></td>
					<td>
						<select name="set[cols][#num#][type]" data-namemask="set[cols][#number#][type]" onchange="el.emMultyNode.settings.changeType(this)">
							{% for colTypeCode,colType in colTypes %}
								<option  value="{{colTypeCode}}">{{colType}}</option>
							{% endfor %}
						</select>
					</td>
					<td class="centered tableCol">-</td>
					<td class="centered"><span onclick="el.emMultyNode.settings.removeCol(this)" class="delete icon deleteBtn"></span></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="allTablesSelect">
		<select name="set[cols][#num#][table]" data-namemask="set[cols][#number#][table]">
			{% for fieldTableName,tableArr in tables %}
				<option value="{{fieldTableName}}">{{tableArr['table_name']}}({{fieldTableName}})</option>
			{% endfor %}
		</select>
	</div>
</div>
<script>
	el.hlp.requireonce("{{baseUri}}fields/em_multy_node/src/js/init.js");
</script>