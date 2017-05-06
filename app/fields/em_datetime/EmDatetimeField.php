<?php

class EmDatetimeField extends FieldBase
{
	public $EditFieldPath = 'em_datetime/view/field';
	public function getValue($fieldValue,$settings,$table = false)
	{
		if(!$table)
			return $fieldValue;

		if(empty($fieldValue))
			return false;

		$timeVal = strtotime($fieldValue);
		return date('d/m/Y H:i:s',$timeVal);
	}
}
