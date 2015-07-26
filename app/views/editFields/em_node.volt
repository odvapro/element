{% extends "editFields/em_string.volt" %}
{% block input %}
	<div class="filedEdit" data-fieldName="{{fieldArr['field']}}" data-multiple="{{fieldArr['multiple']}}">
		<div class="nodes">
			{% if element[fieldArr['field']] is defined and element[fieldArr['field']] != '' %}
				{% for node in element[fieldArr['field']] %}
					<div class="node">
						<span class="delete icon deleteBtn" onclick="el.edit.removeNode(this);"></span>
						<div class="noIcon">{{node}}</div>
						<input type="hidden" name="field[{{fieldArr['field']}}][]" value="{{node}}" />
					</div>
				{% endfor %}
			{% endif %}
			<button class="attachAdd" onclick="el.edit.getNodeAddForm(this,'{{fieldArr['field']}}');"><span class="icon addBtn"></span></button>
		</div>
	</div>
{% endblock %}
