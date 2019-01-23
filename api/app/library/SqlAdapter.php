<?php

abstract class SqlAdapter
{
	abstract protected function select();
	abstract protected function update();
	abstract protected function insert();
	abstract protected function delete();
	abstract protected function getTables();
	abstract protected function getTableSchema($tableName);
}

?>