{% block fieldName %}
	<div class="name">
		{{fieldArr['field']}}
		{% if (fieldArr['required'] is defined and fieldArr['required'] == 1 ) or fieldArr['null'] == "NO"  %}
			<span class="req">*</span>
		{% endif %}
	</div>
{% endblock %}
{% block input %}
	<div class="filedEdit">
		<input type="text" name="field[{{fieldArr['field']}}]" placeholder="{{fieldArr['field']}}" value="{% if element[fieldArr['field']] is defined %}{{element[fieldArr['field']]}}{% endif %}" />
	</div>
{% endblock %}
{% block TPLS %}{% endblock %}