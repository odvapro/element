<?php
class EmSqlView extends TviewBase
{
	public function getWhere()
	{
		if($this->tView === false || empty($this->tView->filter))
			return '';
		return "WHERE {$this->tView->filter}";
	}
	public function getOrder()
	{
		if($this->tView === false || empty($this->tView->sort))
			return '';
		return "ORDER BY {$this->tView->sort}";
	}
	public function getColumns()
	{
		if($this->tView === false || empty($this->tView->columns))
			return ['*'];
		$primaryKey = $this->tableEditor->getPrimaryKey($this->tableInfo['table']);

		$needCols = explode(',',$this->tView->columns);
		$needCols = array_map(function($col){
			return trim($col);
		},$needCols);

		if(!in_array($primaryKey, $needCols))
			array_unshift($needCols, $primaryKey);

		return $needCols;
	}

	public function makeViewLogic()
	{
		$tableInfo        = $this->tableInfo;
		$db               = $this->di->get('db');
		$sqlWhere         = $this->getWhere();
		$sqlOrder         = $this->getOrder();
		$columns          = $this->getColumns();
		$sqlColumns       = implode(',', $columns);
		$sqlForPagination = "SELECT count(*) as elementcount FROM {$tableInfo['table']} {$sqlWhere} {$sqlOrder}";
		$sqlLimit         = $this->_setPaginationGetOffset($sqlForPagination);

		$sql         = "SELECT {$sqlColumns} FROM {$tableInfo['table']} {$sqlWhere} {$sqlOrder} {$sqlLimit}";
		$tableResult = $db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC);
		$fields      = $this->_getFieldsWithSettinsg();
		$this->view->setVar('fields',$fields);
		$result = $this->_applyFieldsSetingsOnResults($tableResult,$fields);
		$this->view->setVar('tableResult',$result);
		$primaryKey = $this->tableEditor->getPrimaryKey($this->tableInfo['table']);
		$this->view->setVar('primaryKey',$primaryKey);
	}

	private function _getFieldsWithSettinsg()
	{
		$fields      = $this->tableEditor->getTableFilelds($this->tableInfo['table']);
		$needColumns = $this->getColumns();
		$keyFields   = [];
		foreach ($fields as &$field)
		{
			$fieldType = $this->_getFieldType($field['field']);
			$fieldSettings = $this->_getFieldSettings($field['field']);
			if(!is_null($this->fields->{$fieldType}))
			{
				$this->fields->{$fieldType}->prolog($fieldSettings,true);
				$field['valueFieldPath'] = $this->fields->{$fieldType}->getValueFieldPath();
			}
			$keyFields[$field['field']] = $field;
		}

		if(count($needColumns) == 1 && $needColumns[0] == '*')
			return $fields;
		$retFields = [];
		foreach ($needColumns as $column)
			$retFields[] = $keyFields[$column];
		return $retFields;
	}

	private function _applyFieldsSetingsOnResults($result,$fields)
	{
		foreach($result as &$tRes)
			foreach($tRes as $fieldName => &$fieldVal)
			{
				$fieldType     = $this->_getFieldType($fieldName);
				$fieldSettings = $this->_getFieldSettings($fieldName);
				if(!is_null($this->fields->{$fieldType}))
					$fieldVal = $this->fields->{$fieldType}->getValue($fieldVal,$fieldSettings,true);
			}
		return $result;
	}

	private function _getFieldType($fieldCode)
	{
		static $fields = false;
		if($fields === false)
			$fields = $this->tableEditor->getTableFilelds($this->tableInfo['table']);
		foreach ($fields as $field)
			if($fieldCode == $field['field'])
				return $field['type'];
		return false;
	}

	private function _getFieldSettings($fieldCode)
	{
		static $fields = false;
		if($fields === false)
			$fields = $this->tableEditor->getTableFilelds($this->tableInfo['table']);
		foreach ($fields as $field)
			if($fieldCode == $field['field'])
				return (empty($field['settings']))?[]:$field['settings'];
		return [];
	}

	private function _setPaginationGetOffset($sql)
	{
		$db        = $this->di->get('db');
		$params    = $this->dispatcher->getParams();
		$pageIndex = array_search('page', $params);
		$page      = 1;
		if($pageIndex !== false)
			$page = $params[$pageIndex+1];

		$limit                               = 20;
		$tableResultCount                    = $db->fetchOne($sql, Phalcon\Db::FETCH_ASSOC );
		$pagination                          = [];
		$pagination['curPage']               = $page;
		$pagination['countOfPages']          = ceil($tableResultCount['elementcount']/$limit);
		$pagination['countOfElements']       = $tableResultCount['elementcount'];
		$pagination['countOfElementsOnPage'] = $limit;
		$pagination['fromPage']              = ( ($page - 3)<1 )?1:($page - 3);
		$pagination['toPage']                = ( ($page + 7)>$pagination['countOfPages'] )?$pagination['countOfPages']:($page + 7);
		$this->view->setVar('pagination',$pagination);

		$from     = $limit*(intval($page)-1);
		return "LIMIT {$from},{$limit}";
	}


}
