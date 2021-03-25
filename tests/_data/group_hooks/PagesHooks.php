<?php
namespace Element\Hooks\Api;

class PagesHooks extends \Element\Hooks\HooksBase
{
	public function beforeSelectHook($request)
	{
		if (isset($request['call_error_by_group']) && !empty($request['call_error_by_group']))
			return false;
		return true;
	}
	public function afterSelectHook($request, $data)
	{
		$data['items'] = array_filter($data['items'], function($item) {
			return !empty($item['form']);
		});

		return $data;
	}
}
