{% if fieldVal is iterable %}
	{% for fVal in fieldVal %}
        <span class="colorTag"><a href="{{fVal['url']}}">{{fVal['name']}}</a></span>
    {% endfor %}
{% else %}
	{{fieldVal}}
{% endif %}