{% extends "layouts/main.volt" %}
{% block topBredcrumbs %}
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li><a href="{{ tableEditor.getUrl(curTable['real_name']) }}">{{curTable['table_name']}}</a></li>
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li class="last"><a href="javascript:void(0)">Редактирование элемента</a></li>
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
			<button class="elbutton blue" onclick="el.edit.save();">Сохранить</button>
			<button class="elbutton gray" onclick="el.edit.delete(this,true)">Удалить</button>
		</div>
	</div>
{% endblock %}
{% block tabsLine %}
	<div id="topTabs">
		<ul>
			<li class="act" onclick="el.tabs.show(this)">Основное</li>
			{% for tab in tabs %}
				<li onclick="el.tabs.show(this)">{{ tab.name }}</li>
			{% endfor %}
		</ul>
	</div>
{% endblock %}
{% block contentBoxAttributes %}class="withTabs"{% endblock %}
{% block content %}
	<form id="elementForm" onsubmit="return false;">
		<div class="tabCont cont_1 act">
			<div class="elementDetail">
				<input type="hidden" name="editMode" value="update" />
				<input type="hidden" name="primaryKey" value="{{primaryKey}}" />
				<input type="hidden" name="tableName" value="{{curTable['real_name']}}" />
				{% for fieldArr in tableInfo['fields'] %}
					{% if fieldArr['tab'] is not empty %}{% continue %}{% endif %}
					{% if fieldArr['hidden'] is defined and fieldArr['hidden'] == 1 %}{% continue %}{% endif %}
					<div class="line">
						{% set formMode = "update" %}
						{{ partial(fieldArr['formPath']) }}
					</div>
				{% endfor %}
				{# Additional fields block #}
				{% if tableInfo['additionalFields']|length > 0 %}
					<div class="line">
						<div class="name">
							<span class="ename">СВЯЗИ</span>
						</div>
					</div>
				{% endif %}
				{% for fieldArr in tableInfo['additionalFields'] %}
					<div class="line">
						{% set formMode = "update" %}
						{{ partial(fieldArr['formPath']) }}
					</div>
				{% endfor %}
			</div>
		</div>
		{% for tabIndex,tab in tabs %}
			<div class="tabCont cont_{{ (tabIndex+2) }}">
				<div class="elementDetail">
					{% for fieldArr in tableInfo['fields'] %}
						{% if fieldArr['tab'] != tab.id %}{% continue %}{% endif %}
						<div class="line">
							{% set formMode = "update" %}
							{{ partial(fieldArr['formPath']) }}
						</div>
					{% endfor %}
				</div>
			</div>
		{% endfor %}
	</form>
{% endblock %}
{% block pageScripts %}
	{# TODO  перенести в авто подключение #}
	<script type="text/javascript" src="{{baseUri}}js/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="{{baseUri}}js/ckeditor/init.js"></script>

	<link rel="stylesheet" href="{{baseUri}}fields/em_text/src/codemirror/lib/codemirror.css">
	<link rel="stylesheet" href="{{baseUri}}fields/em_text/src/codemirror/theme/neo.css">
	<script src="{{baseUri}}fields/em_text/src/codemirror/lib/codemirror.js"></script>
	<script src="{{baseUri}}fields/em_text/src/codemirror/addon/hint/show-hint.js"></script>
	<script src="{{baseUri}}fields/em_text/src/codemirror/addon/hint/xml-hint.js"></script>
	<script src="{{baseUri}}fields/em_text/src/codemirror/addon/hint/html-hint.js"></script>
	<script src="{{baseUri}}fields/em_text/src/codemirror/mode/xml/xml.js"></script>
	<script src="{{baseUri}}fields/em_text/src/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="{{baseUri}}fields/em_text/src/codemirror/addon/selection/active-line.js"></script>
	<script src="{{baseUri}}fields/em_text/src/init.js"></script>
	{# -TODO  перенести в авто подключение #}


	<link rel="stylesheet" type="text/css" href="{{baseUri}}js/datetimepicker/jquery.datetimepicker.css"/ >
	<script src="{{baseUri}}js/datetimepicker/jquery.datetimepicker.js"></script>
	<script src="{{baseUri}}js/datetimepicker/init.js"></script>
{% endblock %}