<?php
class IndexFController extends ControllerBase
{
	/**
	 * Shows node adding form
	 * @var $_POST['fieldName'] string
	 * @var $_POST['tableName'] string
	 * @return JSON
	 */
	public function getNodeAddFormAction()
	{
		if(!$this->request->isAjax())
			return $this->jsonResult(['result'=>'error','msg'=>'только ajax']);

		$fieldName = $this->request->getPost('fieldName');
		$tableName = $this->request->getPost('tableName');
	
		// get current field settings
		$fieldDesc = EmTypes::find([
			'conditions' => "table = ?0 AND field = ?1",
			'bind' => [$tableName,$fieldName],
		]);
		
		if(!count($fieldDesc))
			return $this->jsonResult(['result'=>'error','msg'=>'настройки поля не найдены']);

		$fieldDesc = $fieldDesc[0];
		if($fieldDesc->type != "em_node")
			return $this->jsonResult(['result'=>'error','msg'=>'настройки поля не найдены']);

		$settings   = (!empty($fieldDesc->settings))?json_decode($fieldDesc->settings,true):[];
		// поле по которому привязвается другой элемент - id например
		$nodeField  = (!empty($settings['nodeField']))?$settings['nodeField']:false;
		$this->view->setVar('nodeField',$nodeField);
		// таблица из которой берутся эелементы
		$nodeTable  = (!empty($settings['nodeTable']))?$settings['nodeTable']:false;
		$this->view->setVar('nodeTable',$nodeTable);
		// поле которому ведется поиск - name например
		$nodeSearch = (!empty($settings['nodeSearch']))?$settings['nodeSearch']:false;
		$this->view->setVar('nodeSearch',$nodeSearch);

		$this->view->setVar('settings',$settings);
		$this->view->setVar('fieldName',$fieldName);
		$this->view->setVar('tableName',$tableName);

		return $this->jsonResult([
			'result' => 'success',
			'form' => $this->view->getRender('index','getNodeAddForm')
		]);
	}


	/**
	 * Поиск полей для ватокомплита, работает только при ajax запросах
	 * @var $_POST['nodeTable'] string таблица привязки
 	 * @var $_POST['nodeField'] string поле привязки - например id
 	 * @var $_POST['nodeSearch'] string поле по которому ищется привязываемый элемент - например name
	 * @return JSON
	 */
	public function autoCompleteAction()
	{
		if(!$this->request->isAjax())
			return $this->jsonResult(['result'=>'error','msg'=>'only xhttp requests']);

		$nodeField  = $this->request->getPost('nodeField');
		$nodeTable  = $this->request->getPost('nodeTable');
		$nodeSearch = $this->request->getPost('nodeSearch');
		$q          = $this->request->getPost('q');

		if(empty($nodeField) || empty($nodeTable))
			return $this->jsonResult(['result'=>'error','msg'=>'некорректные настройки']);

		$db       = $this->di->get('db');
		$limit    = 7;
		$from     = 0;
		$sqlWhere = $nodeTable;
		$nodeSearchSQL = (!empty($nodeSearch))?$nodeSearch." LIKE '%".$q."%'":'';
		$tableResult = $db->fetchAll(
			"SELECT * FROM ".$sqlWhere." WHERE ".$nodeSearchSQL." LIMIT $from,$limit",
			Phalcon\Db::FETCH_ASSOC
		);
		$result = [];
		foreach ($tableResult as $key => $tRes)
		{
			$resEl         = [];
			$resEl['id']   = $tRes[$nodeField];
			$resEl['name'] = $tRes[$nodeSearch];
			$result[]      = $resEl;
		}
		return $this->jsonResult(['result'=>'success','elements'=>$result]);
	}
}
