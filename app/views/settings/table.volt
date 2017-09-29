{% extends "layouts/main.volt" %}
{% block topBredcrumbs %}
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li><a href="{{baseUri}}settings/">Настройки</a></li>
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li class="last"><a href="{{baseUri}}settings/">Таблица {{ tableInfo['name'] }}</a></li>
{% endblock %}
{% block titleLine %}
	<div id="titleButtons">
		<div class="titleBlock">
			<div class="ttl">
				<h2>Таблица {{ tableInfo['name'] }}</h2>
				<span>{{ tableInfo['table'] }}</span>
			</div>
		</div>
		<div class="rightButtons">
			<button class="elbutton blue" onclick="el.settings.submit()">Сохранить</button>
		</div>
	</div>
{% endblock %}
{% block content %}
	<div class="contWrap" style="width:900px;">
		<form id="settingsForm">
			<table data-tablename="{{ tableInfo['table'] }}" class="settings" cellspacing='0'>
				<tr>
					<th>Настройки</th>
					<th>Наименование</th>
					<th>Тип поля</th>
				</tr>
				{% for field in fields %}
					<tr data-fieldname="{{field['field']}}">
						<td class="centered">
							{% if field['type'] in EmTypesCodes %}
								<div class="editLine">
									<a href="javascript:void(0)" onclick="el.settings.showFieldSetiings(this,'{{field['field']}}')">Настройки</a>
									<a href="javascript:void(0)">Копировать</a>
									<a href="javascript:void(0)">Удалить</a>
								</div>
								<button class="elbutton dotts"><span class="icon buttonDotts"></span></button>
							{% else %}
								–
							{% endif %}
						</td>
						<td>
							<span class="ename _ename">{{(field['ename']!='')?field['ename']:field['field']}}</span>
							<span class="name">{{field['field']}}</span>
							<div onclick="el.settings.fieldName.showEdit(this)" class="nameEditButton">
								<i class="fa fa-pencil" aria-hidden="true"></i>
							</div>
						</td>
						<td>
							{% if field['key'] != "PRI" %}
								<select name="tables[{{ tableInfo['table'] }}][fields][{{field['field']}}][type]">
									{% if field['type'] not in EmTypesCodes %}
										<option value="notsys">{{field['type']}}</option>
									{% endif %}
									{% for EmTypeCode, EmType in EmTypes %}
										<option value="{{EmTypeCode}}" {% if field['type'] == EmTypeCode %}selected="true"{% endif %}>{{EmType['name']}}</option>
									{% endfor %}
								</select>
							{% else %}
								PRIMARY KEY
								<input type="hidden" name="tables[{{ tableInfo['table'] }}][fields][{{field['field']}}][type]" value="notsys">
							{% endif %}
						</td>
					</tr>
				{% endfor %}
			</table>
		</form>
		<div class="addFieldLine">
			{#
				#todo
				<button class="addField">Добавить поле</button>
			#}
		</div>
		<div class="clear"></div>
	</div>
{% endblock %}