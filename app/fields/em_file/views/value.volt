{# Вывод значения в таблице
- Доступные переменные: fieldVal
#}
{% for aFile in fieldVal %}
	{% if aFile['type'] == 'image' %}
		<a href="{{aFile['path']}}" target="_blunk"><img src="{{aFile['sizes']['small']}}" alt="{{aFile['upName']}}"/></a>
	{% else %}
		<a href="{{aFile['path']}}" target="_blunk"><img src="{{baseUri}}img/fileIcon.png" alt="{{aFile['upName']}}"/></a>
	{% endif %}
{% endfor %}
