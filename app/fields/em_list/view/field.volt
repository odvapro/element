{% extends "table/stringEditField.volt" %}
{% block input %}
	<div class="filedEdit" data-addfieldtpl="multynodeField_{{fieldArr['field']}}">
		<select name="field[{{fieldArr['field']}}]">
			{% for elem in fieldArr['settings']['cols'] if elem['code'] != '' %}
				<option
					{% if element is defined and element[fieldArr['field']] ==  elem['code'] %}
						selected
					{% endif %}
				value="{{elem['code']}}">{{elem['name']}}</option>
			{% endfor %}
		</select>
	</div>
{% endblock %}
