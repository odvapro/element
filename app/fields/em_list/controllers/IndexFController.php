<?php

class IndexFController extends ControllerBase
{
	/**
	 * Сохранить выбранный элемент списка как значение поля
	 */
	public function saveSelectedItemAction()
	{
		$fieldCode       = $this->request->getPost('fieldCode');
		$tableCode       = $this->request->getPost('tableCode');
		$primaryKey      = $this->request->getPost('primaryKey');
		$primaryKeyValue = $this->request->getPost('primaryKeyValue');
		$selectedValue   = $this->request->getPost('selectedValue');

		if (empty($fieldCode) || empty($tableCode) || empty($primaryKey) || empty($primaryKeyValue))
			return $this->jsonResult(['success' => false, 'message' => 'required fields in not found']);

		$updateValue = [
			'table' => $tableCode,
			'set' => [
				$fieldCode . " = '" . $selectedValue . "'"
			],
			'where' => [
				'operation' => 'and',
				'fields' =>
				[
					[
						'code' => $primaryKey,
						'operation' => 'IS',
						'value' => $primaryKeyValue
					],
				]
			]
		];

		$updateResult = $this->eldb->update($updateValue);

		$this->jsonResult(['success' => true]);
	}
}