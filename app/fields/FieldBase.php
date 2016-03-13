<?php
/**
* Абстрактный класс типа поля
*/
abstract class FieldBase extends Phalcon\Mvc\User\Plugin
{
	/**
	 * Подбор данных для формы настроек
	 * обязательно должен передать в вьюху параметр  formPath
	 * @param  array $settings насфтройки поля подгруженные из БД
	 * @param  array  $params   параметры (таблицы сайта, поля формы настроек) 
	 * @return [type]           [description]
	 */
	public function getSettings($settings, array $params){}
}