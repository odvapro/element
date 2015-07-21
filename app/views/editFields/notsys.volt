{% extends "editFields/em_string.volt" %}
{% block input %}
	<div class="filedEdit">
	{% if fieldArr['key'] == "PRI" and fieldArr['extra'] == "auto_increment"  %}
		<div class="nodes">
		{% if element[fieldArr['field']] is defined %}
			<div class="node">
				<div class="noIcon">{{element[fieldArr['field']]}}</div>
			</div>
		<input type="hidden" name="field[{{fieldArr['field']}}]" value="{{element[fieldArr['field']]}}" />
		{% endif %}
		<div class="node">
			<div class="noIcon">PRIMARY KEY auto_increment</div>
		</div>
		</div>
	{% else %}
		<input type="text" name="field[{{fieldArr['field']}}]" placeholder="{{fieldArr['field']}}" value="{% if element[fieldArr['field']] is defined %}{{element[fieldArr['field']]}}{% endif %}" />
	{% endif %}
	</div>
{% endblock %}