{% extends "table/stringEditField.volt" %}
{% block input %}
	<div class="filedEdit" data-addfieldtpl="multynodeField_{{fieldArr['field']}}">
		<pre>
		{{dump(element[fieldArr['field']])}}
		{{dump(fieldArr['settings']['cols'])}}
		</pre>
		<div class="fieldEditorBlock">
			<input type="text"  value="test"/>
		</div>
		<table class="elements em_multy_node" cellspacing="0">
			{% if fieldArr['settings']['cols'] is defined %}
				<tr>
					{% for colKey,col in fieldArr['settings']['cols'] if colKey != '#num#'  %}
						<th>{{col['name']}}</th>
					{% endfor %}
					<th class="centered">удалить</th>
				</tr>
			{% endif %}
			{% for fieldLine in element[fieldArr['field']] %}
				<tr>
					<td>1</td>
					<td class="edittd ">name</td>
					<td>selct value</td>
					<td class="centered"><span class="delete icon deleteBtn"></span></td>
				</tr>
			{% endfor %}
			<tr>
				<td colspan="4" class="centered"><a href="javascript:void(0)" onclick="el.emMultyNode.field.addNodeLine(this)"><span class="icon addBtn"></span></a></td>
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
						{% for colKey,col in fieldArr['settings']['cols'] if colKey != '#num#'  %}
							<td data-type="{{col['type']}}">
								{{col['name']}}
								<input type="hidden" name="" data-namemask="field[{{fieldArr['field']}}][#number#][{{colKey}}]" />
							</td>
						{% endfor %}
						<td class="centered"><span onclick="el.emMultyNode.field.removeNodeLine(this)" class="delete icon deleteBtn"></span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
{% endblock %}
