<?php

abstract class DataBaseAdapter
{
	abstract static protected function getTables();
	abstract static protected function getTableSchema($tableName);
	abstract static protected function getTableData($tableName);
}

?>