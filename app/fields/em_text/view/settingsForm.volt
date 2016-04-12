<div class="editLine">
	<div class="name">Обязательное</div>
	<div class="inp">
		<input type="hidden" name="set[required]" value="0" />
		<input type="checkbox" name="set[required]" value="1" {% if settingFields['required'] == 1 %}checked{% endif %} />
	</div>
</div>
<div class="editLine">
	<div class="name">Редактор</div>
	<div class="inp">
		<label>
			<input type="radio" name="set[visualEditor]" value="0" {% if settingFields['visualEditor'] is not defined or settingFields['visualEditor'] == 0 %}checked{% endif %} /> 
			нет
		</label><br/>
		<label> 
			<input type="radio" name="set[visualEditor]" value="1" {% if settingFields['visualEditor'] is defined and settingFields['visualEditor'] == 1 %}checked{% endif %} />
			визуальный редактор
		</label><br/>
		<label> 
			<input type="radio" name="set[visualEditor]" value="2" {% if settingFields['visualEditor'] is defined and settingFields['visualEditor'] == 2 %}checked{% endif %} />
			редактор кода
		</label> 
	</div>
</div>

