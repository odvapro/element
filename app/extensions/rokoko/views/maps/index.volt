{% extends "layouts/main.volt" %}
{% block topBredcrumbs %}
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li class="last"><a href="#">Функциональные блоки Рокос</a></li>
{% endblock %}
{% block titleLine %}
	<div id="titleButtons">
		<div class="titleBlock">
			<div class="ttl">
				<h2>Функциональные блоки Рокос</h2>
			</div>
		</div>
		<div class="rightButtons">
			<button class="elbutton blue" >Добавить</button>
		</div>
	</div>
{% endblock %}
{% block tabsLine %}
	<div id="topTabs">
		<ul>
			<li class="act" onclick="el.tabs.show(this)">План 1го этажа</li>
			<li onclick="el.tabs.show(this)">План 2го этажа</li>
			<li onclick="el.tabs.show(this)">План 3го этажа</li>
			<li onclick="el.tabs.show(this)">Общий план</li>
		</ul>
	</div>
{% endblock %}
{% block contentBoxAttributes %}class="withTabs"{% endblock %}
{% block content %}
	<div class="contWrap" style="width:900px;">
		<div class="tabCont cont_1 act">
			<div id="demo"></div>
		</div>
		<div class="tabCont cont_2">
			карта 2
		</div>
		<div class="tabCont cont_3">
			карта 3
		</div>
		<div class="tabCont cont_4">
			Обищий план
		</div>
	</div>
{% endblock %}
{% block pageScripts %}
<script src="{{baseUri}}extensions/rokoko/src/js/d3/d3.min.js" charset="utf-8"></script>
<script src="{{baseUri}}extensions/rokoko/src/js/floorplan/d3.floorplan.min.js" charset="utf-8"></script>
<script src="{{baseUri}}extensions/rokoko/src/js/init.js" charset="utf-8"></script>
<link rel="stylesheet" href="{{baseUri}}extensions/rokoko/src/js/floorplan/d3.floorplan.css"/>
{% endblock %}