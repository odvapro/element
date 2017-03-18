<?php
class IndexFController extends ControllerBase
{
	
	public function getMatrixAction()
	{
		$tableName  =  $this->request->getPost('tableName');
		$primaryKey =  $this->request->getPost('primaryKey');
		$id         =  $this->request->getPost('id');
		$fieldCode  =  $this->request->getPost('fieldCode');
		if(empty($tableName) || empty($primaryKey) || empty($id) || empty($fieldCode))
			$this->jsonResult(['success'=>false]);

		// достаем настройки данного поля 
		// достаем значение матрицы
		// передаем все во вьюху
		$fieldInfo = $this->tableEditor->getFieldInfo($tableName,$fieldCode);
		if(empty($fieldInfo['settings']['cols']))
			$this->jsonResult(['success'=>false]);
		unset($fieldInfo['settings']['cols']['#num#']);
		$this->view->setVar('cols',$fieldInfo['settings']['cols']);

		$primaryKey = ['field'=>$primaryKey,'value'=>$id];
		$element = $this->tableEditor->getElement($tableName,$primaryKey);
		if(empty($element) || empty($element[$fieldCode]))
			$this->jsonResult(['success'=>false]);

		$fieldValue = @json_decode($element[$fieldCode],true);
		$this->view->setVar('fieldValue',$fieldValue);

		ob_start();
		$this->view->render('index','getMatrix');
		$table  = ob_get_clean();

		$this->jsonResult(['table'=>$table,'success'=>true]);

	}
}