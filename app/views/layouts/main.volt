<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Element v 0.1</title>
		<!--[if lt IE 9]>
			<script src="{{baseUri}}js/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="{{baseUri}}css/style.css" />
	</head>
	<body>
		<aside id="sidebar">
			<div id="logo">
				<a href="{{baseUri}}"><span class="icon logoTop"></span></a>
			</div>
			<nav>
				<ul>
					{% for relaTableName,table in tables %}
						<li{% if table['classes'] is defined %} class="{{table['classes']}}" {% endif  %}><a href="{{baseUri}}table/{{relaTableName}}/"><span class="icon sidebarTable"></span>{{table['table_name']}}</a></li>
					{% endfor  %}
					<li class="settings{% if controllerName == "settings" %} act{% endif  %}"><a href="{{baseUri}}settings/"><span class="icon sidebarSettings"></span>НАСТРОЙКИ</a></li>
				</ul>
			</nav>
		</aside>
		<section id="contentBlock">
			<div id="contFixed">
				<div id="topNavLine">
					<nav class="navigation">
						<ul>
							<li><a href="{{baseUri}}">Главная</a></li>
							{% block topBredcrumbs %}{% endblock %}
						</ul>
					</nav>
					<nav class="userbar">
						<ul>
							<li><a href="javascript:void(0);">Михаил</a></li>
							<li class="cirdel"><span class="icon topUserCircDelim"></span></li>
							<li class="logout"><a href="{{baseUri}}auth/logout/">Выйти <span class="icon topUserLogOutArr"></span></a></li>
						</ul>
					</nav>
				</div>
				{% block titleLine %}{% endblock %}
				{% block tabsLine %}{% endblock %}
			</div>
			<div id="content" {% block contentBoxAttributes %}{% endblock %}>
				{% block content %}{% endblock %}
			</div>
		</section>
		<div class="alert error"><span class="msg"></span> <span onclick="el.message.close();" class="icon closeButton"></span></div>
		<div id="popupWrap"><div class="popupCont"></div></div>
		<script type="text/javascript" src="{{baseUri}}js/jquery.js"></script>
		<script type="text/javascript" src="{{baseUri}}js/init.js"></script>
		<script type="text/javascript">
			el.config.baseUri = '{{baseUri}}';
			el.init();
		</script>
		{% block pageScripts %}{% endblock %}
	</body>
</html>