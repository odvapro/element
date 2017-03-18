{# Вывод значения в таблице
- Доступные переменные: fieldVal
- Доступные переменные: fieldCode
<span class="colorTag">212121</span>  <span class="colorTag">212121</span> <span class="colorTag">212121</span>
#}
{% if fieldVal  %}
	<div class="centered">
		<button title="показать матрицу" onclick="el.emMultyNode.getMatrix(this,'{{fieldCode}}')" class="elbutton dotts"><span class="icon buttonDotts"></span></button>
	</div>
{% else %}
	<div class="centered">–</div>
{% endif %}