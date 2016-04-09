{# Вывод значения в таблице
- Доступные переменные: fieldVal
<span class="colorTag">212121</span>  <span class="colorTag">212121</span> <span class="colorTag">212121</span>
#}
{% if fieldVal is iterable %}
	{% for fVal in fieldVal %}
        <span class="colorTag">{{fVal['name']}}</span>
    {% endfor %}
{% else %}
	{{fieldVal}}
{% endif %}