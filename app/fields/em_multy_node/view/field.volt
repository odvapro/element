{% extends "table/stringEditField.volt" %}
{% block input %}
	<div class="filedEdit">
		<pre>
		{{dump(element[fieldArr['field']])}}
		</pre>
		<!-- <div class="fieldEditorBlock">
			<input type="text"  value="test"/>
		</div> -->
		<table class="elements" cellspacing="0">
			{% if fieldArr['settings']['cols'] is defined %}
				<tr>
					{% for col in fieldArr['settings']['cols'] %}
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
				<td colspan="4" class="centered"><a href="#"><span class="icon addBtn"></span></a></td>
			</tr>
		</table>
	</div>
	<div class="filedEdit" data-fieldName="{{fieldArr['field']}}" data-multiple="{{fieldArr['multiple']}}">
	</div>
{% endblock %}