# Установка

## Требования
composer php7 phalcon

## Установка через composer
Для установки в свой проект вполните команду в корне своего проекта
```
composer create-project dzantiev/element
```
Настройка под разные конфигурации сервера

## Apache
Убедиться в том что включен mod_rewrite
<видео>

## Nginx
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
---
<видео>

## Настройка конфигурации
Перейти по адресу <ваш домен>/element/
Заполнить все поля
Проверить установить

-- все