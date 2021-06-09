<?php
use Phalcon\Paginator\Exception;
// use Phalcon\Paginator\Adapter;

/**
 * Pagination using a PHP array as source of data
 *
 * <code>
 *
 * $paginator = new ElPagination(
 *     [
 *         "count"=> 1000,
 *         "limit" => 2,
 *         "page"  => $currentPage,
 *     ]
 * );
 *</code>
 */
class ElPagination
{
	var $_count = 1;
	var $_page  = 1;
	var $_limitRows = 1;

	/**
	 * Adapter constructor
	 *
	 * @param array $config
	 */
	public function __construct($config)
	{
		$this->_config    = $config;
		$this->_count     = $config['count'];
		$this->_page      = $config['page'];
		$this->_limitRows = $config['limit'];
	}

	/**
	 * Returns a slice of the resultset to show in the pagination
	 */
	public function getPaginate()
	{
		$config = $this->_config;

		$show    = (int) $this->_limitRows;
		$pageNumber = (int) $this->_page;

		if($pageNumber <= 0)
			$pageNumber = 1;

		$number = $this->_count;
		$roundedTotal = $number / floatval($show);
		$totalPages = (int) $roundedTotal;

		/**
		 * Increase total_pages if wasn't integer
		 */
		if($totalPages != $roundedTotal)
			$totalPages++;

		//Fix next
		if($pageNumber < $totalPages)
			$next = $pageNumber + 1;
		else
			$next = $totalPages;

		if($pageNumber > 1)
			$before = $pageNumber - 1;
		else
			$before = 1;

		$page = [
			'first'       => 1,
			'before'      => $before,
			'current'     => $pageNumber,
			'last'        => $totalPages,
			'next'        => $next,
			'total_pages' => $totalPages,
			'total_items' => $number,
			'limit'       => $this->_limitRows,
			'offset'      => $show*($pageNumber-1)
		];

		return $page;
	}
}
