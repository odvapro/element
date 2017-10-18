<?php
/**
 * Conroller for table views manipulations
 */
class TviewController extends ControllerBase
{
	/**
	 * Add table view action
	 * @return  html page
	 */
	public function addAction()
	{
		$tViewName = $this->request->getPost('tViewName');
		$tableName = $this->request->getPost('tableName');
		if(empty($tViewName) || empty($tableName))
			return $this->jsonResult(['success'=>false]);
		$newTview = new EmViews();
		$newTview->name  = $tViewName;
		$newTview->table = $tableName;
		$newTview->type  = 'em_sql';
		$newTview->save();
		return $this->jsonResult(['success'=>true,'url'=>$newTview->getUrl()]);
	}

	/**
	 * Delete table view action
	 * @return  json
	 */
	public function deleteAction()
	{
		$viewId = $this->request->getPost('viewId');
		if(empty($viewId))
			return $this->jsonResult(['success'=>false]);
		$tView = EmViews::findFirst($viewId);
		if(!$tView)
			return $this->jsonResult(['success'=>false]);
		$url = "/table/{$tView->table}/";
		$tView->delete();
		return $this->jsonResult(['success'=>true,'url'=>$url]);
	}

	/**
	 * Save table view
	 * @return json
	 */
	public function saveAction()
	{
		$columns = $this->request->getPost('columns');
		$filter  = $this->request->getPost('filter');
		$sort    = $this->request->getPost('sort');
		$viewid  = $this->request->getPost('viewid');
		if(empty($viewid))
			return $this->jsonResult(['success'=>false]);
		$tView = EmViews::findFirst($viewid);
		if(!$tView)
			return $this->jsonResult(['success'=>false]);

		$tView->filter  = $filter;
		$tView->sort    = $sort;
		$tView->columns = $columns;
		$tView->save();
		return $this->jsonResult(['success'=>true]);
	}

	/**
	 * Rename table view
	 * @return json
	 */
	public function renameAction()
	{
		$viewId    = $this->request->getPost('viewId');
		$tViewName = $this->request->getPost('tViewName');
		$tView     = EmViews::findFirst($viewId);
		if(!$tView)
			return $this->jsonResult(['success'=>false]);
		$tView->name = $tViewName;
		if(!$tView->update())
			return $this->jsonResult(['success'=>false]);

		return $this->jsonResult(['success'=>true]);
	}
}
