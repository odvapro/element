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
				<h2>
					Таблица {{ tableInfo['name'] }}
					<a
						class="titleBlock__tableLink"
						href="{{ tableEditor.getUrl(tableInfo['table']) }}"
						title="Перейти к таблице"
					><i class="fa fa-table" aria-hidden="true"></i></a>
				</h2>
				<span>{{ tableInfo['table'] }}</span>
			</div>
		</div>
	</div>
{% endblock %}
{% block tabsLine %}
	<div id="topTabs">
		<ul>
			<li class="act" onclick="el.tabs.show(this)">Поля таблицы</li>
			<li onclick="el.tabs.show(this)">Вкладки</li>
		</ul>
	</div>
{% endblock %}
{% block contentBoxAttributes %}class="withTabs"{% endblock %}
{% block content %}
	<div class="contWrap" style="width:900px;">
		<div class="tabCont cont_1 act">
			<form id="settingsForm">
				<table data-tablename="{{ tableInfo['table'] }}" class="settings" cellspacing='0'>
					<tr>
						<th>Настройки</th>
						<th>Наименование</th>
						<th>Тип поля</th>
						<th>Не показывать</th>
					</tr>
					{% for field in fields %}
						<tr data-fieldname="{{field['field']}}">
							<td class="centered">
								<div class="editLine">
									<a href="javascript:void(0)" onclick="el.settings.showFieldSetiings(this,'{{field['field']}}')">Открыть настройки</a>
									{#
										<a href="javascript:void(0)">Копировать</a>
										<a href="javascript:void(0)">Удалить</a>
									#}
								</div>
								<button class="elbutton dotts"><span class="icon buttonDotts"></span></button>
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
									<select
										onchange="el.settings.setFieldType(this,'{{ tableInfo['table'] }}','{{field['field']}}')"
									>
										{% if field['type'] not in EmTypesCodes %}
											<option value="notsys">{{field['type']}}</option>
										{% endif %}
										{% for EmTypeCode, EmType in EmTypes %}
											<option value="{{EmTypeCode}}" {% if field['type'] == EmTypeCode %}selected="true"{% endif %}>{{EmType['name']}}</option>
										{% endfor %}
									</select>
								{% else %}
									PRIMARY KEY
									<input
										type="hidden"
										name="tables[{{ tableInfo['table'] }}][fields][{{field['field']}}][type]"
										value="notsys"
									/>
								{% endif %}
							</td>
							<td>
								{% if field['key'] != "PRI" %}
									<input
										type="checkbox"
										name="tables[{{ tableInfo['table'] }}][fields][{{field['field']}}][hidden]"
										value="checked"
										onchange="el.settings.setFieldHidden(this,'{{ tableInfo['table'] }}','{{field['field']}}')"
										{% if field['hidden'] is defined and field['hidden'] == 1 %}
											checked="true"
										{% endif %}
									/>
								{% else %}
									<input
										type="checkbox"
										name="hidden"
										disabled="true"
									/>
								{% endif %}
							</td>
						</tr>
					{% endfor %}
					{% for field in additionalFields  %}
						<tr>
							<td class="centered">
								<div class="editLine">
									<a
										href="javascript:void(0)"
										onclick="el.settings.showFieldSetiings(this,'{{field['field']}}')"
									>Открыть настройки</a>
									<a
										href="javascript:void(0)"
										onclick="el.settings.deleteField(this,'{{field['field']}}')"
									>Удалить</a>
								</div>
								<button class="elbutton dotts"><span class="icon buttonDotts"></span></button>
							</td>
							<td>
								<span class="ename _ename">{{field['field']}}</span>
							</td>
							<td colspan="2">
								<i class="fa fa-code-fork" aria-hidden="true"></i>
								Связь один к многим
							</td>
						</tr>
					{% endfor %}
				</table>
			</form>
			<div class="addFieldLine">
				<button class="addField" onclick="el.settings.showAddFieldForm(this)">Добавить дополнительное поле</button>
			</div>
			<div class="clear"></div>
		</div>
		<div class="tabCont cont_2">
			{% include 'settings/tableTabs.volt' %}
			
		</div>
	</div>
	<div id="TPLS" style="display:none;">
		<div class="_addNodeForm">
			<div class="popupTopLine">
				<span class="name">Добавлене связи - один ко многим</span>
				<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
			</div>
			<form id="fieldSettings" onsubmit="return false;" method="post">
				<div class="popupContLine">
					<div class="editLine">
						<div class="name">Название поля</div>
						<div class="inp">
							<input type="text" name="fieldName">
						</div>
					</div>
				</div>
				<div class="popupBottomLine">
					<input type="hidden" name="tableName" value="{{ tableInfo['table'] }}">
					<button class="elbutton blue" onclick="return el.settings.addField(this);">Сохранить</button>
					<button class="elbutton gray" onclick="el.popup.hide();">Отмена</button>
				</div>
			</form>
		</div>
	</div>
{% endblock %}
{% block headerScripts %}
	<script src="//unpkg.com/vue/dist/vue.js"></script>
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script src="//unpkg.com/element-ui/lib/index.js"></script>
	<script src="//unpkg.com/element-ui/lib/umd/locale/ru-RU.js"></script>
	<script>ELEMENT.locale(ELEMENT.lang.ruRU)</script>
	<link rel="stylesheet" href="//unpkg.com/element-ui/lib/theme-chalk/index.css">
{% endblock %}