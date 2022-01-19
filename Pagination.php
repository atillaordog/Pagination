<?php
class Pagination
{
	private $output = array();
	
	const LINK = 0;
	const NEXT = 1;
	const LAST = 2;
	const PREVIOUS = 3;
	const FIRST = 4;
	const DOTS = 5;
	
	private $data = array(
		'total' => 1, 
		'items_per_page' => 10, 
		'base_url' => 'http://www.cs.dev',  
		'current_page' => 1, 
		'links_to_show' => 3, 
		'page_name' => 'page',
		'first_string' => '<<',
		'prev_string' => '<',
		'dots_string' => '...',
		'next_string' => '>',
		'last_string' => '>>'
	);
	
	public function __construct(Array $data = array())
	{
		foreach ( $this->data as $key => $value )
		{
			if ( array_key_exists($key, $data) )
			{	
				$this->data[$key] = $data[$key];
			}
		}
	}	
	
	public function render()
	{
		$item = array('url' => '', 'type' => 0, 'nr' => 0);
		
		$pagenumber = ceil($this->data['total']/$this->data['items_per_page']);
		
		if($this->data['current_page'] > $pagenumber)
		{
			$this->data['current_page'] = $pagenumber;
		}
		
		if($this->data['current_page'] < 1)
		{
			$this->data['current_page'] = 1;
		}
		
		$base_url = explode('?', $this->data['base_url']);
		$base_url = $base_url[0];
		
		$query = array();
		$url_parts = parse_url($this->data['base_url']);
		
		if ( isset($url_parts['query']) )
		{
			parse_str($url_parts['query'], $query);
		}
		
		if($this->data['current_page'] > 1)
		{
			$query[$this->data['page_name']] = 1;
			$first = $base_url.'?'.http_build_query($query);
			
			$query[$this->data['page_name']] = $this->data['current_page']-1;
			$previous = $base_url.'?'.http_build_query($query);
		}
		else
		{
			$first = '';
			$previous = '';
		}
		
		$item['url'] = $first;
		$item['type'] = self::FIRST;
		$item['nr'] = $this->data['first_string'];
		array_push($this->output,$item);
			
		$item['url'] = $previous;
		$item['type'] = self::PREVIOUS;
		$item['nr'] = $this->data['prev_string'];
		array_push($this->output,$item);
		
		$query[$this->data['page_name']] = 1;
		$item['url'] = $base_url.'?'.http_build_query($query);
		$item['type'] = self::LINK;
		$item['selected'] = $this->data['current_page'] == 1;
		$item['nr'] = 1;
		array_push($this->output,$item);
		
		if($this->data['links_to_show']%2 == 0)
		{
			$linknumber = $this->data['links_to_show']-1;
		}
		else
		{
			$linknumber = $this->data['links_to_show'];
		}
		
		$leftright = floor($linknumber/2);
		
		if($this->data['current_page']+$leftright > $linknumber+1)
		{
			$item['url'] = '';
			$item['type'] = self::DOTS;
			$item['nr'] = $this->data['dots_string'];
			array_push($this->output,$item);
		}
		
		for( $i = $this->data['current_page']-$leftright; $i <= $this->data['current_page']+$leftright; $i++ )
		{
			if( $i > 1 && $i < $pagenumber ) 
			{
				$query[$this->data['page_name']] = $i;
				$item['url'] = $base_url.'?'.http_build_query($query);
				$item['type'] = self::LINK;
				$item['selected'] = $this->data['current_page'] == $i;
				$item['nr'] = $i;
				array_push($this->output,$item);
			}
		}
		
		if($this->data['current_page']+$leftright < $pagenumber)
		{
			$item['url'] = '';
			$item['type'] = self::DOTS;
			$item['nr'] = $this->data['dots_string'];
			array_push($this->output,$item);
		}
		
		if ( $pagenumber > 1 )
		{
			$query[$this->data['page_name']] = $pagenumber;
			$item['url'] = $base_url.'?'.http_build_query($query);
			$item['type'] = self::LINK;
			$item['nr'] = $pagenumber;
			$item['selected'] = $this->data['current_page'] == $pagenumber;
			array_push($this->output,$item);
		}
		
		if($this->data['current_page'] < $pagenumber)
		{
			$query[$this->data['page_name']] = $this->data['current_page']+1;
			$previous = $base_url.'?'.http_build_query($query);
			
			$query[$this->data['page_name']] = $pagenumber;
			$last = $base_url.'?'.http_build_query($query);
		}
		else
		{
			$previous = '';
			$last = '';
		}
		
		$item['url'] = $previous;
		$item['type'] = self::NEXT;
		$item['nr'] = $this->data['next_string'];
		array_push($this->output,$item);
			
		$item['url'] = $last;
		$item['type'] = self::LAST;
		$item['nr'] = $this->data['last_string'];
		array_push($this->output,$item);
		
		return $this->output;
	}
	
	/**
	 * PHP magic function used to set internal data from outside
	 */
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}
	
	/**
	 * Function for creating the data for pagination
	 * @param int $total the number of total elements
	 * @param int $current The current page we are on
	 * @param int $nr_per_page The size of pagination, or results per page - optional, default set to 20
	 * @return array This will hold the limit and offset
	 */
	public static function paginate($total = 0, $current = 0, $nr_per_page = 20)
	{
		
		$pagination = ( is_numeric($nr_per_page) && $nr_per_page > 0 )? $nr_per_page : 20;
		
		if ( $total > $pagination )
		{
			$nr_pages = ceil($total/intval($pagination));
		}
		else
		{
			$nr_pages = 1;
		}
		
		if ( $current > $nr_pages )
		{
			$current = $nr_pages;
		}
		
		if ( $current <= 1 )
		{
			$current = 1;
		}
		
		$data = array();
		$data['nr_pages'] = $nr_pages;
		$data['limit'] = $pagination;
		$data['offset'] = ($current-1)*$pagination;
		$data['pagination'] = $pagination;
		$data['current'] = $current;
		
		return $data;
	}
}
