{# 
	TODOO2 - сделать достование по id 
	сейчас там где есть привязка с другой таблицей, выводится привязанный id
	а не название
#}
<div class="popupTopLine">
	<span class="name">Значание поля</span>
	<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
</div>
<div class="popupContLine">
	<table class="multy_node_table">
		<tbody>
			<tr>
				{% for col in cols %}
					<th>{{col['name']}}</th>
				{% endfor %}
			</tr>
			{% for fieldValElemnt in fieldValue %}
				<tr>
					{% for fElemnt in fieldValElemnt %}
						<td>{{fElemnt}}</td>
					{% endfor %}
				</tr>
			{% endfor %}
		</tbody>
	</table>
</div>