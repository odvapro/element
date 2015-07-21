{% extends "editFields/em_string.volt" %}
{% block input %}
	<div class="filedEdit">
		<input name="field[{{fieldArr['field']}}]" readonly="true" class="datetimepicker" type="text" value="{% if element[fieldArr['field']] is defined %}{{element[fieldArr['field']]}}{% endif %}" /><span class="datepickerIcon icon calendarIcon"></span>
	</div>
{% endblock %}