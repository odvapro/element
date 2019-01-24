<?php


class IndexController extends ControllerBase
{
	public function indexAction()
	{
		return $this->jsonResult(['success' => true]);
	}

	/**
	 * Action для отображения Not Found страницы
	 * @return view
	 */
	public function notfoundAction()
	{
		$this->response->setStatusCode(404, 'Not Found');
	}
}
