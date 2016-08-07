<div class="editLine">
	<div class="name">
		Обязательное
	</div>
	<div class="inp">
		<input type="hidden" name="set[required]" value="0">
		<input type="checkbox" name="set[required]" value="1">
	</div>
</div>
<table class="list_table">
	<tr>
		<th>Код</th>
		<th>Название</th>
		<th>По умолчанию</th>
		<th>Удалить</th>
	</tr>
	{% for colIndex,col in cols %}
		<tr>
			<td><input 
				type="text"
				value="{{col['code']}}"
				name="set[cols][{{colIndex}}][code]"
				data-namemask="set[cols][#number#][code]">
			</td>
			<td><input
				type="text"
				value="{{col['name']}}"
				name="set[cols][{{colIndex}}][name]"
				data-namemask="set[cols][#number#][name]">
			</td>
			<td><input
				type="radio"
				value="{{colIndex}}"
				name="set[default]">
			</td>
			<td class="centered"><span onclick="el.emListFiled.settings.removeLine(this)" class="delete icon deleteBtn"></span></td>
		</tr>
	{% endfor %}
	<tr>
		<td colspan="4" class="centered"><a href="javascript:void(0)" onclick="el.emListFiled.settings.addLine(this)"><span class="icon addBtn"></span></a></td>
	</tr>
</table>
<div id="multyTableTPLS" style="display:none;">
	<div class="emptyColWrap">
		<table>
			<tbody class="emptyCol">
				<tr>
					<td><input
						type="text"
						name="set[cols][#num#][code]"
						data-namemask="set[cols][#number#][code]">
					</td>
					<td><input
						type="text"
						name="set[cols][#num#][name]"
						data-namemask="set[cols][#number#][name]">
					</td>
					<td><input
						type="radio"
						value="#number#"
						name="set[default]">
					</td>
					<td class="centered"><span onclick="el.emListFiled.settings.removeLine(this)" class="delete icon deleteBtn"></span></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
{{ assets.outputJs() }}