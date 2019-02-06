<?php
/**
 * Abstract class for field type
 */
abstract class FieldBase extends Phalcon\Mvc\User\Plugin
{
	abstract function getInfoArray();
}