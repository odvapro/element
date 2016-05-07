{% extends "table/stringEditField.volt" %}
{% block input %}
	<div class="filedEdit" data-addfieldtpl="multynodeField_{{fieldArr['field']}}">
		<div class="fieldEditorBlock" style="display:none;">
			<input onblur="el.emMultyNode.field.hideEditing(this)" onchange="el.emMultyNode.field.save(this)" class="editinp input" type="text"  value=""/>
			<select onblur="el.emMultyNode.field.hideEditing(this)" onchange="el.emMultyNode.field.save(this)" class="editinp select_from_table" name="" id="">
				<option >test</option>
				<option >test2</option>
			</select>
			<textarea onblur="el.emMultyNode.field.hideEditing(this)" onchange="el.emMultyNode.field.save(this)" class="editinp textarea" name="" id="" cols="30" rows="10"></textarea>
		</div>
		<table class="elements em_multy_node" cellspacing="0">
			{# вывод стольбцов #}
			{% if fieldArr['settings']['cols'] is defined %}
				<tr>
					{% for colKey,col in fieldArr['settings']['cols'] if colKey != '#num#'  %}
						<th>{{col['name']}}</th>
					{% endfor %}
					<th class="centered">удалить</th>
				</tr>
			{% endif %}
			{# вывод значиений #}
			{% for fieldIndex,fieldLine in element[fieldArr['field']] %}
				<tr>
					{% for colKey,col in fieldArr['settings']['cols'] if colKey != '#num#'  %}
						<td data-type="{{col['type']}}" onclick="el.emMultyNode.field.edit(this)">
							<span class="value">{{fieldLine[col['name']]}}</span>
							<input type="hidden" value="{{fieldLine[col['name']]}}" name="field[{{fieldArr['field']}}][{{fieldIndex}}][{{col['name']}}]" data-namemask="field[{{fieldArr['field']}}][#number#][{{col['name']}}]" />
						</td>
					{% endfor %}
					<td class="centered"><span onclick="el.emMultyNode.field.removeNodeLine(this)" class="delete icon deleteBtn"></span></td>
				</tr>
			{% endfor %}
			<tr>
				<td colspan="{{fieldArr['settings']['cols']|length}}" class="centered"><a href="javascript:void(0)" onclick="el.emMultyNode.field.addNodeLine(this)"><span class="icon addBtn"></span></a></td>
			</tr>
		</table>
	</div>
	<div class="filedEdit" data-fieldName="{{fieldArr['field']}}" data-multiple="{{fieldArr['multiple']}}">
	</div>
	<div id="TPLS" style="display:none;">
		<div class="multynodeField_{{fieldArr['field']}}">
			<table>
				<tbody>
					<tr>
						{# вывод шаблона новой линии #}
						{% for colKey,col in fieldArr['settings']['cols'] if colKey != '#num#'  %}
							<td data-type="{{col['type']}}" onclick="el.emMultyNode.field.edit(this)">
								<span class="value"></span>
								<input type="hidden" name="" data-namemask="field[{{fieldArr['field']}}][#number#][{{col['name']}}]" />
							</td>
						{% endfor %}
						<td class="centered"><span onclick="el.emMultyNode.field.removeNodeLine(this)" class="delete icon deleteBtn"></span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
{% endblock %}
