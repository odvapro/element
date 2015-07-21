{% extends "layouts/enterForm.volt" %}
{% block content %} 
	<div id="enterForm">
		<div class="logoLine">
			<span class="logo"></span>
		</div>
		{% for error in errors %}
		    <p class="error">{{ error }}</p>
		{% endfor  %}
		<form action="" method="post">
			<label>
				<input type="text" name="login" placeholder="Логин" value="{{login}}" />
			</label>
			<label>
				<input type="password" name="password" placeholder="Пароль" value="{{password}}"/>
			</label>
			<label>
				<input type="submit" name="enter" value="Вход" />
			</label>
		</form>
	</div>
{% endblock %}