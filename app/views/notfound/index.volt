{% extends "layouts/main.volt" %}
{% block content %}
	<div id="content" style="border:0px;">
		<div class="contWrap" style="width:900px;">
			<p class="centered">Страница не найдена</p> <br/>
			<p class="centered">
				<button onclick="window.location.href='{{baseUri}}'" class="elbutton blue">Главная</button>
				<button onclick="window.history.back()" class="elbutton blue">Назад</button>
			</p>
		</div>
	</div>
{% endblock %}