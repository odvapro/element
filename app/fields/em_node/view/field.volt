{% extends "table/stringEditField.volt" %}
{% block input %}
	<div class="filedEdit" data-fieldName="{{fieldArr['field']}}" data-multiple="{{fieldArr['multiple']}}">
		<div class="nodes">
			{% if element[fieldArr['field']] is defined and element[fieldArr['field']] != '' %}
				{% for node in element[fieldArr['field']] %}
					<div class="node">
						<span class="delete icon deleteBtn" onclick="el.edit.removeNode(this);"></span>
						<div class="noIcon">{{node['name']}}</div>
						<input type="hidden" name="field[{{fieldArr['field']}}][]" value="{{node['id']}}" />
					</div>
				{% endfor %}
			{% endif %}
			<button class="attachAdd" onclick="el.edit.getNodeAddForm(this,'{{fieldArr['field']}}');"><span class="icon addBtn"></span></button>
		</div>
	</div>
{% endblock %}