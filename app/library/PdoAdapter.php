<?php

abstract class PdoAdapter
{
	abstract protected function select($requestParams);
	abstract protected function update($requestParams);
	abstract protected function insert($requestParams);
	abstract protected function delete();
	abstract protected function getTables();
	abstract protected function getColumns($tableName);
}

?>