{% extends "layouts/main.volt" %}
{% block topBredcrumbs %}
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li class="last"><a href="{{baseUri}}table/{{curTable['real_name']}}/">{{curTable['table_name']}}</a></li>
{% endblock %}
{% block contentBoxAttributes %} class="withFilter" {% endblock %}
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
	{{ partial('table/filter') }}
{% endblock %}
{% block content %}
	<div class="contWrap" style="width:{{tableWidth}}px;">
		<table class="elements _em_table" cellspacing='0' data-tablename="{{tableName}}" data-primarykey="{{primaryKey}}">
			<tr>
				<th class="centered"><button class="elbutton dotts"><span class="icon buttonDotts"></span></button></th>
				{% for fieldArr in tableInfo['fields'] %}
					<th data-code="{{fieldArr['field']}}">
						<i 
							class="fa  {{(fieldArr['typeInfo']['iconClass'])?fieldArr['typeInfo']['iconClass']:'fa-font'}} fieldType"
							aria-hidden="true"
						></i>
						<span class="fmane _ename">{{(fieldArr['ename'])?fieldArr['ename']:fieldArr['field']}}</span>
						<span onclick="el.table.openFieldSettings(this)" class="icon tablearrow"></span>
						<ul class="filter-menu">
							<li onclick="el.table.setsort(this,'desc')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i> Сортировать A → Z</li>
							<li onclick="el.table.setsort(this,'asc')"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Сортировать Z → A</li>
							<li><i class="fa fa-times" aria-hidden="true"></i> Скрыть колонку</li>
							<li onclick="el.settings.fieldName.showTableEdit(this)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Переименовать</li>
						</ul>
					</th>
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
										{% set fieldCode = fieldArr['field'] %}
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
					<td colspan="{{tableFieldsCount}}" class="centered">no data</td>
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