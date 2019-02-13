<?php
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;

class ElController extends ControllerBase
{
	/**
	 * delete method SQL
	 * @return json
	 */
	public function deleteAction()
	{
		$delete = $this->request->getPost('delete');

		if (empty($delete))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		$resultDelete = $this->eldb->delete($delete);

		if ($resultDelete === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		return $this->jsonResult(['success' => true, 'result' => $resultDelete]);
	}
	/**
	 * insert method SQL
	 * @return json
	 */
	public function insertAction()
	{
		$insert = $this->request->getPost('insert');

		if (empty($insert))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		$resultInsert = $this->eldb->insert($insert);

		if ($resultInsert === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		return $this->jsonResult(['success' => true, 'result' => $resultInsert]);
	}
	/**
	 * update method SQL
	 * @return json
	 */
	public function updateAction()
	{
		$update = $this->request->getPost('update');

		if (empty($update))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		$resultUpdate = $this->eldb->update($update);

		if ($resultUpdate === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		return $this->jsonResult(['success' => true, 'result' => $resultUpdate]);
	}
	/**
	 * select methos SQL
	 * @return json
	 */
	public function selectAction()
	{
		$select = $this->request->get('select');
		$page   = (!empty($select['page'])) ? $select['page'] : 1;
		$limit  = empty($this->request->get('limit')) ? 20 : $this->request->get('limit');

		if (empty($select))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		$resultSelect = $this->element->select($select);

		if ($resultSelect === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		$paginator = new PaginatorArray(
		[
			'data'  => $resultSelect,
			'limit' => $limit,
			'page'  => $page,
		]);

		$resultSelect = $paginator->getPaginate();

		return $this->jsonResult(['success' => true, 'result' => $resultSelect]);
	}
	/**
	 * Get Tables
	 * @return json
	 */
	public function getTablesAction()
	{
		$tables = $this->eldb->getTables();

		foreach ($tables as &$table)
		{
			$emViewsTable = EmViews::findByTable($table['code']);

			$table['tviews'] = $emViewsTable->toArray();
		}

		$this->jsonResult([
			'success' => true,
			'tables'  => $tables
		]);
	}
	/**
	 * Get Columns
	 * @return json
	 */
	public function getColumnsAction()
	{
		$tableName = $this->request->getPost('tableName');

		if (empty($tableName))
			return $this->jsonResult(['success' => false, 'message' => 'need require param tableName']);

		$tableColumns = $this->element->getColumns($tableName);
		if ($tableColumns === false)
			return $this->jsonResult(['success' => false, 'message' => 'table not found']);

		return $this->jsonResult(['success' => true, 'columns' => $tableColumns]);
	}
}
