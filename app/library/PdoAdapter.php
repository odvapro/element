<?php

abstract class PdoAdapter
{
	abstract protected function select($requestParams);
	abstract protected function update($requestParams);
	abstract protected function insert($requestParams);
	abstract protected function delete($requestParams);
	abstract protected function count($requestParams);
	abstract protected function getTables();
	abstract protected function getColumns($tableName);
	abstract protected function getLastInsertId();
}

?>