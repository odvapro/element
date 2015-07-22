{% extends "layouts/main.volt" %}
{% block topBredcrumbs %}
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li><a href="{{baseUri}}settings">Настройки</a></li>
	<li class="arr"><span class="icon topBreadcrumbArrow"></span></li>
	<li class="last"><a href="javascript:void(0)">Редактирование пользователя</a></li>
{% endblock %}
{% block titleLine %}
	<div id="titleButtons">
		<div class="titleBlock">
			<div class="ttl">
				<h2>{{user.name}}</h2>
				<span>{{user.login}}</span>
			</div>
		</div>
		<div class="rightButtons">
			<button class="elbutton blue" onclick="el.settings.saveUser();">Сохранить</button>
		</div>
	</div>
{% endblock %}
{% block content %}
	<div class="elementDetail">
		<form id="userForm" onsubmit="return false;">
		<input type="hidden" name="userId" value="{{user.id}}" />
		<div class="line">
			<div class="name">
				Имя <span class="req">*</span>
			</div>
			<div class="filedEdit">
				<input type="text" name="name" placeholder="Имя" value="{{user.name}}" />
			</div>
		</div>
		<div class="line">
			<div class="name">
				Логин <span class="req">*</span>
			</div>
			<div class="filedEdit">
				<input type="text" name="login" placeholder="Логин" value="{{user.login}}" />
			</div>
		</div>
		<div class="line">
			<div class="name">
				Email <span class="req">*</span>
			</div>
			<div class="filedEdit">
				<input type="text" name="email" placeholder="Email" value="{{user.email}}" />
			</div>
		</div>
		<div class="line">
			<div class="name">
				Пароль <span class="req">*</span>
			</div>
			<div class="filedEdit">
				<input type="password" name="password" placeholder="Пароль" value="" />
			</div>
		</div>
		<div class="line">
			<div class="name">
				Новый пароль 
			</div>
			<div class="filedEdit">
				<input type="password" name="newpassword" placeholder="Пароль" value="" />
			</div>
		</div>
		<div class="line">
			<div class="name">
				Повторить новый пароль 
			</div>
			<div class="filedEdit">
				<input type="password" name="repassword" placeholder="Пароль" value="" />
			</div>
		</div>
		</form>
	</div>
{% endblock %}