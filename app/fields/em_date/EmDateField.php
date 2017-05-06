<?php

class EmDateField extends FieldBase
{
	public $EditFieldPath = 'em_date/view/field';

	public function getValue($fieldValue,$settings,$table = false)
	{
		if(!$table) return $fieldValue;

		if(empty($fieldValue))
			return false;
		
		$timeVal = strtotime($fieldValue);
		return date('d/m/Y',$timeVal);
	}
}
