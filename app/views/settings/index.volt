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
	</div>
{% endblock %}
{% block tabsLine %}
	<div id="topTabs">
		<ul>
			<li class="act" onclick="el.tabs.show(this)">Таблицы</li>
			<li onclick="el.tabs.show(this)">Пользователи</li>
			<li onclick="el.tabs.show(this)">Обновление</li>
		</ul>
	</div>
{% endblock %}
{% block contentBoxAttributes %}class="withTabs"{% endblock %}
{% block content %}
	<div class="contWrap" style="width:900px;">
		<form id="settingsForm" onsubmit="return false;" method="post">
			<div class="tabCont cont_1 act">
				<table class="settings" cellspacing='0'>
					<tr>
						<th class="centered"><button class="elbutton dotts"><span class="icon buttonDotts"></span></button></th>
						<th>Название</th>
						<th>Показывать в меню</th>
					</tr>
					{% for tableCode,tableDetail in detailTables %}
						<tr>
							<td class="centered">
								<div class="editLine">
									<a href="{{ baseUri }}settings/table/{{ tableCode }}/">Редактировать</a>
									<a href="javascript:alert('не реализовано'); void(0);">Сортировка</a>
									<a href="javascript:alert('не реализовано'); void(0);">Удалить</a>
								</div>
								<button class="elbutton dotts"><span class="icon buttonDotts"></span></button>
							</td>
							<td>
								<span data-table-code="{{ tableCode }}" class="ename _ename">{{ (tableDetail['table_name']!='')?tableDetail['table_name']:tableCode }}</span>
								<span class="name">{{ tableCode }}</span>
								<div onclick="el.settings.table.showEditPopup('{{ tableCode }}')" class="nameEditButton">
									<i class="fa fa-pencil" aria-hidden="true"></i>
								</div>
							</td>
							<td>
								<input
									type="checkbox"
									{% if tableDetail['show'] is defined and tableDetail['show'] != 0 %}checked{% endif %}
									onchange="el.settings.table.setShow(this,'{{ tableCode }}')"
								/>
							</td>
						</tr>
					{% endfor  %}
				</table>
			</div>
			<div class="tabCont cont_2">
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
										<a href="javascript:alert('не реализовано'); void(0);">Сортировка</a>
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