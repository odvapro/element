{% extends "editFields/em_string.volt" %}
{% block input %}
	<div class="filedEdit">
		<input name="field[{{fieldArr['field']}}]" type="checkbox" value="1" {% if element[fieldArr['field']] is defined and element[fieldArr['field']] == 1 %}checked{% endif %}/>
	</div>
{% endblock %}
