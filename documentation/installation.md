# Установка

* [Требования](#requirements)
* [Установка через composer](#установка-через-composer)
* [Настройки сервера](#настройки-сервера)
* [Конфигурация](#конфигурация)
* [Авторизация](#авторизация)

## Требования {#requirements}
`composer` `php7` `phalcon`

## Установка через composer
Для установки в свой проект вполните команду в корне своего проекта
```
composer create-project dzantiev/element --no-dev
```

## Настройки сервера
### Apache
Убедиться в том что включен mod_rewrite

### Nginx
Убедитесь что на вашем сайте работает обработка php файлов.
В конфигурационный файл вашего сайта добавьте следующие строки.

```
if ( $uri ~ "^/element(.*)" ) {
	set $elementPrefix "/element/public";
	set $elementExecFile "/index.html";
	set $elementFile $1;
}

if ( $uri ~ "^/element/api(.*)" ) {
	set $elementPrefix "/element/api";
	set $elementExecFile "/index.php";
	set $elementFile $1;
}

if ( $uri ~ "^/element/public(.*)" ) {
	set $elementPrefix "/element/public";
	set $elementExecFile "/index.html";
	set $elementFile $1;
}

location /element/ {
	try_files $elementPrefix$elementFile $elementPrefix$elementFile/ $elementPrefix$elementExecFile?$args;
}
```

## Конфигурация
Перейти по адресу `<ваш домен>/element/`
Если открыли первый раз этот адрес откроется форма настройки подлключаения к базе данных
![Image of install form](/documentation/img/install.png)
Заполнить все поля и нажмите кнопку `Install`


## Авторизация
После вам откроется форма авторизации
![Image of auth form](/documentation/img/auth.png)
Логин `admin` пароль `adminpass`
После авторизации не забудьте сменить стандартный пароль

