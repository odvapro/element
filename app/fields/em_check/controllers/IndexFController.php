<?php

class IndexFController extends ControllerBase
{
	/**
	 * Изменить стратус чекбокса
	 */
	public function changeStatusAction()
	{
		$fieldCode       = $this->request->getPost('fieldCode');
		$tableCode       = $this->request->getPost('tableCode');
		$status          = $this->request->getPost('status');
		$primaryKey      = $this->request->getPost('primaryKey');
		$primaryKeyValue = $this->request->getPost('primaryKeyValue');

		if (empty($fieldCode) || empty($tableCode) || empty($primaryKey) || empty($primaryKeyValue))
			return $this->jsonResult(['success' => false, 'message' => 'required fields in not found']);

		if (empty($status))
			$status = 0;

		$updateField = [
			'table' => $tableCode,
			'set' => [
				$fieldCode . ' = ' . $status
			],
			'where' => [
				'operation' => 'and',
				'fields' =>
				[
					[
						'code' => $primaryKey,
						'operation' => 'IS',
						'value' => $primaryKeyValue
					]
				]
			]
		];

		$updateResult = $this->eldb->update($updateField);

		if (!$updateField)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		$this->jsonResult(['success' => true]);
	}
}