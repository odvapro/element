{% extends "layouts/main.volt" %}
{% block topBredcrumbs %}
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li class="last"><a href="{{baseUri}}settings/">Настройки</a></li>
{% endblock %}
{% block titleLine %}
	<div id="titleButtons">
		<div class="titleBlock">
			<div class="ttl">
				<h2>Настройки</h2>
			</div>
		</div>
		<div class="rightButtons">
			<button class="elbutton blue" onclick="el.settings.submit()">Сохранить</button>
		</div>
	</div>
{% endblock %}
{% block tabsLine %}
	<div id="topTabs">
		<ul>
			<li onclick="el.tabs.show(this)">Таблицы</li>
			<li class="act" onclick="el.tabs.show(this)">Пользователи</li>
			<li onclick="el.tabs.show(this)">Обновление</li>
		</ul>
	</div>
{% endblock %}
{% block contentBoxAttributes %}class="withTabs"{% endblock %}
{% block content %}
	<div class="contWrap" style="width:900px;">
		<form id="settingsForm" onsubmit="return false;" method="post">
			<div class="tabCont cont_1">
				{% for relaTableName,tableDetail in detailTables %}
					<table data-tablename="{{relaTableName}}" class="settings" cellspacing='0'>
						<tr>
							<th class="tableName" colspan="2">
								<input type="text" onkeyup="el.settings.refreshTableName(this);" onblur="el.settings.hideEditTable(this);" name="tables[{{relaTableName}}][name]" value="{{tableDetail['table_name']}}"/>
								<span class="tableNamePlace">{{tableDetail['table_name']}}</span> <span onclick="el.settings.showEditTable(this);" class="icon editButton"></span>
							</th>
							<th class="righted" colspan="3">{{relaTableName}}</th>
						</tr>
						<tr class="black">
							<td>Настройки</td>
							<td>Наименование</td>
							<td>Тип поля</td>
							<td>Обязательное</td>
							<td>Множественное</td>
						</tr>
						{% for field in tableDetail['fields'] %}
							<tr>
								<td class="centered">
									{% if field['type'] in EmTypes %}
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
								<td>{{field['field']}}</td>
								<td>
									{% if field['key'] != "PRI" %}
										<select name="tables[{{relaTableName}}][fields][{{field['field']}}][type]">
											{% if field['type'] not in EmTypes %}
												<option value="notsys">{{field['type']}}</option>
											{% endif %}
											<option value="em_int" {% if field['type'] == "em_int" %}selected="true"{% endif %}>Число</option>
											<option value="em_string" {% if field['type'] == "em_string" %}selected="true"{% endif %}>Строка</option>
											<option value="em_text" {% if field['type'] == "em_text" %}selected="true"{% endif %}>Текст</option>
											<option value="em_date" {% if field['type'] == "em_date" %}selected="true"{% endif %}>Дата</option>
											<option value="em_datetime" {% if field['type'] == "em_datetime" %}selected="true"{% endif %}>Дата и время</option>
											<option value="em_file" {% if field['type'] == "em_file" %}selected="true"{% endif %}>Файл</option>
											<option value="em_bool" {% if field['type'] == "em_bool" %}selected="true"{% endif %}>Флаг</option>
											<option value="em_node" {% if field['type'] == "em_node" %}selected="true"{% endif %}>Привязка к элементу</option>
										</select>
									{% else %}
										PRIMARY KEY
										<input type="hidden" name="tables[{{relaTableName}}][fields][{{field['field']}}][type]" value="notsys">
									{% endif %}
								</td>
								<td class="centered">
									<input type="checkbox" name="tables[{{relaTableName}}][fields][{{field['field']}}][required]" {% if (field['required'] is defined and field['required'] == 1) or field['null'] == "NO" %}checked="true"{% endif %} />
								</td>
								<td class="centered">
									<input type="checkbox" name="tables[{{relaTableName}}][fields][{{field['field']}}][multiple]" {% if field['multiple'] is defined and field['multiple'] == 1 %}checked="true"{% endif %} />
								</td>
							</tr>
						{% endfor %}
					</table>
					<div class="addFieldLine">
						<button class="addField">Добавить поле</button>
					</div>
					<div class="clear"></div>
				{% endfor  %}
			</div>
			<div class="tabCont cont_2 act">
				<table class="elements" cellspacing='0'>
					<tr>
						<th class="centered"><button class="elbutton dotts"><span class="icon buttonDotts"></span></button></th>
						<th>id</th>
						<th>Имя</th>
						<th>Логин</th>
						<th>Email</th>
					</tr>
					{% if users %}
						{% for user in users %}
							<tr>
								<td class="centered">
									<div class="editLine">
										<a href="{{baseUri}}settings/user/{{user.id}}">Редактировать</a>
										<a href="javascript:alert('не реализовано'); void(0);">Копировать</a>
										<a href="javascript:alert('не реализовано'); void(0);">Удалить</a>
									</div>
									<button class="elbutton dotts"><span class="icon buttonDotts"></span></button>
								</td>
								<td>{{user.id}}</td>
								<td style="line-height:20px;">
									<img class="rounded avatar" src="{{user_avatars[user.email]}}" alt=""/>
									{{user.name}}
								</td>
								<td>{{user.login}}</td>
								<td>{{user.email}}</td>
							</tr>
						{% endfor %}
					{% else %}
							<tr>
								<td class="centered">no data</td>
							</tr>
					{% endif %}
				</table>
			</div>
			<div class="tabCont cont_3">
				<br/>
				<div class="updateBox">
					<p class="centered">Текущая версия системы - {{currentSystemVersion}}</p> <br/>
					<p class="centered" id="refreshUpdates"></p><br/>
					<p class="centered">
						<button class="elbutton blue" onclick="el.settings.checkUpdates();">Проверить обновления</button>
						<button class="elbutton blue updatingbutton" style="display:none" onclick="el.settings.updateSystem();">Обновить</button>
					</p>
				</div>
			</div>
		</form>
	</div>
{% endblock %}