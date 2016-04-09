{% extends "layouts/main.volt" %}
{% block topBredcrumbs %}
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li class="last"><a href="{{baseUri}}table/{{curTable['real_name']}}/">{{curTable['table_name']}}</a></li>
{% endblock %}
{% block titleLine %}
	<div id="titleButtons">
		<div class="titleBlock">
			<div class="ttl">
				<h2>{{curTable['table_name']}}</h2>
				<span>{{curTable['real_name']}}</span>
			</div>
		</div>
		<div class="rightButtons">
			<button onclick="window.location.href='{{baseUri}}table/{{curTable['real_name']}}/add/'" class="elbutton blue">Добавить Элемент</button>
		</div>
	</div>
{% endblock %}
{% block content %}
	<div class="contWrap" style="width:{{tableWidth}}px;">
		<table class="elements" cellspacing='0' data-tablename="{{tableName}}" data-primarykey="{{primaryKey}}">
			<tr>
				<th class="centered"><button class="elbutton dotts"><span class="icon buttonDotts"></span></button></th>
				{% for fieldArr in tableInfo['fields'] %}
					<th>{{fieldArr['field']}} <span class="icon tablearrow"></span></th>
				{% endfor %}
			</tr>
			{% if tableResult %}
				{% for resLine in tableResult %}
					<tr data-id="{{resLine[primaryKey]}}">
						<td class="centered">
							<div class="editLine">
								<a href="{{baseUri}}table/{{curTable['real_name']}}/edit/{{resLine[primaryKey]}}">Редактировать</a>
								<a href="javascript:alert('не реализовано'); void(0);">Копировать</a>
								<a href="javascript:void(0);" onclick="el.edit.delete(this)">Удалить</a>
							</div>
							<button class="elbutton dotts"><span class="icon buttonDotts"></span></button>
						</td>
						{% for fieldArr in tableInfo['fields'] %}
							{% if resLine[fieldArr['field']] != "" %}
								<td>
									{% if fieldArr['valueFieldPath'] is defined  and  fieldArr['valueFieldPath'] != "" %}
										{#если есть шаблон вывода данного типа поля, выводим его в таком формате#}
										{% set fieldVal = resLine[fieldArr['field']] %}
										{{ partial(fieldArr['valueFieldPath']) }}
									{% else %}
										{{resLine[fieldArr['field']]}}
									{% endif %}
								</td>
							{% else %}
								<td class="centered">–</td>
							{% endif %}
						{% endfor %}
					</tr>
				{% endfor %}
			{% else %}
					<tr>
						<td class="centered">no data</td>
					</tr>
			{% endif %}
		</table>
		{% if  pagination['countOfPages'] > 1 %}
			<nav class="pagination">
				<ul>
					{% for page in pagination['fromPage']..pagination['toPage'] %}
						<li{% if page == pagination['curPage'] %} class="act"{% endif %}><a href="{{baseUri}}table/{{curTable['real_name']}}/{{page}}">{{page}}</a></li>
					{% endfor %}
					{% if pagination['toPage'] < pagination['countOfPages'] %}
						<li><button class="elbutton dotts" onclick="alert('выбор количества на странице');"><span class="icon buttonDotts"></span></button></li>
						<li><a href="{{baseUri}}table/{{curTable['real_name']}}/{{pagination['countOfPages']}}">{{pagination['countOfPages']}}</a></li>
					{% endif %}
				</ul>
			</nav>
		{% endif %}
		<div class="clear"></div>
	</div>
{% endblock %}