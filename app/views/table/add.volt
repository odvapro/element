{% extends "layouts/main.volt" %}
{% block topBredcrumbs %}
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li><a href="{{ tableEditor.getUrl(curTable['real_name']) }}">{{curTable['table_name']}}</a></li>
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li class="last"><a href="{{baseUri}}table/{{curTable['real_name']}}/add/">Добавление элемента</a></li>
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
		</div>
	</div>
{% endblock %}
{% block content %}
	<div class="elementDetail">
		<form id="elementForm" onsubmit="return false;">
		<input type="hidden" name="editMode" value="add" />
		<input type="hidden" name="tableName" value="{{curTable['real_name']}}" />
		{% for fieldArr in tableInfo['fields'] %}
			<div class="line">
				{% set formMode = "add" %}
				{{ partial(fieldArr['formPath']) }}
			</div>
		{% endfor %}
		</form>
	</div>
{% endblock %}
{% block pageScripts %}
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