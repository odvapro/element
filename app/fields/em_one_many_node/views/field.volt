{% extends "table/stringEditField.volt" %}
{% block fieldName %}
	<div class="name">
		<span class="ename">
			{{ fieldArr['field']  }}
		</span>
		<span class="tname">{{fieldArr['field']}}</span>
	</div>
{% endblock %}
{% block input %}
	<div class="filedEdit" data-fieldName="{{fieldArr['field']}}">
		<table class="elements em_multy_node" cellspacing="0">
			{# вывод стольбцов #}
			<tr>
				<th>Название</th>
				<th>Редактировать</th>
			</tr>
			{# вывод значиений #}
			{% if element is defined %}
				{% for nodeLine in element[fieldArr['field']]['results'] %}
					<tr>
						<td class="centered">{{ nodeLine['name'] }}</td>
						<td class="centered"><a href="{{ nodeLine['url'] }}">Редаактировать</a></td>
					</tr>
				{% endfor %}
				<tr>
					<td colspan="2"class="centered">
						<a
							href="{{ element[fieldArr['field']]['addUrl'] }}"
						><span class="icon addBtn"></span></a>
					</td>
				</tr>
			{% endif %}
		</table>
	</div>
{% endblock %}