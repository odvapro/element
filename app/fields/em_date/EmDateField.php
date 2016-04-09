<?php

class EmDateField extends FieldBase
{
	public $EditFieldPath = 'em_date/view/field';

	public function getValue($fieldValue,$settings,$table = false)
	{
		if($table)
		{
			if(!empty($fieldValue))
			{
				$timeVal = strtotime($fieldValue);
				return date('d/m/Y',$timeVal);
			}
			else
				return false;
		}
		else
			return $fieldValue;
	}
}