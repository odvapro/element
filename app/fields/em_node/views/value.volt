{# Вывод значения в таблице
- Доступные переменные: fieldVal
<span class="colorTag">212121</span>  <span class="colorTag">212121</span> <span class="colorTag">212121</span>
#}
{% if fieldVal is iterable %}
	{% for fVal in fieldVal %}
        <span class="colorTag"><a href="{{fVal['url']}}">{{fVal['name']}}</a></span>
    {% endfor %}
{% else %}
	{{fieldVal}}
{% endif %}