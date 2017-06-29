<?php

/**
 * pagination class
 *
 * A basic class for creating easy pagination lists
 *
 * @version 	0.1
 * @author 		Christian Weber <christian@cw-internetdienste.de>
 * @link		http://www.cw-internetdienste.de
 * 
 * freely distributable under the MIT Licence
 *
 */

class pagination {
	// display options
	private $tag_wrapper			=	'div';
	private $tag_item				=	'span';
	private $activeclass			=	'active current';
	private $activelink				=	true;
	private	$attributes;
	
	// jumper options (first / last page link)
	private	$jumpers				=	true;		
	private $jumper_first_text		=	'&laquo;';
	private $jumper_first_title		=	'Go to first page';
	private $jumper_last_text		=	'&raquo;';
	private $jumper_last_title		=	'Go to last page';
	
	// steps options (prev / next page link)
	private $steps					=	true;
	private $steps_back_text		=	'Prev';
	private $steps_back_title		=	'Go to previous page';
	private $steps_next_text		=	'Next';
	private $steps_next_title		=	'Go to next page';
	
	// link options
	private $link_url				=	'?pid=##ID##';
	private $link_text				=	'##ID##';
	private $link_title				=	'Go to page ##ID##';
	
	// internal options
	private $itemcount				=	1;
	private $items_per_page			=	10;
	private $maxpages				=	10;
	private $currentpage			=	1;
	private $totalpages;
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param mixed $itemcount
	 * @param int $currentpage (default: 1)
	 * @param array $options (default: array())
	 * @return int
	 */
	public function __construct($itemcount,$currentpage=1,$options=array()) {
		if(!$itemcount 		|| 	!is_numeric($itemcount))		{	return false;	}
		$this->itemcount	=	(int)(($itemcount >= 1) ? $itemcount:1);
		
		if(!$currentpage	||	!is_numeric($currentpage))	{	return false;	}
		$this->currentpage	=	(int)(($currentpage >= 1) ? $currentpage:1);
		
		if(is_array($options) && count($options) > 0) {
			foreach($options as $var => $val) {
				if(property_exists('pagination',$var)) {
					$this->$var	=	$val;
				}
			}
		}
	}
	
	/**
	 * set_page function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return int
	 */
	public function set_page($id) {
		if(!$id || !is_numeric($id) || $id <= 0) {	return false; }
		$this->currentpage	=	(int)$id;
		return true;
	}
	
	/**
	 * get_page function.
	 * 
	 * @access public
	 * @return int
	 */
	public function get_page() {
		return (int)$this->currentpage;
	}
	
	/**
	 * set_itemcount function.
	 * 
	 * @access public
	 * @param mixed $amount
	 * @return int
	 */
	public function set_itemcount($amount) {
		if(!$amount || !is_numeric($amount) || $amount <= 0) {	return false;	}
		$this->itemcount	=	(int)$amount;
		return true;
	}
		
	/**
	 * get_itemcount function.
	 * 
	 * @access public
	 * @return int
	 */
	public function get_itemcount() {
		return (int)$this->itemcount;
	}
	
	/**
	 * set_items_per_page function.
	 * 
	 * @access public
	 * @param mixed $amount
	 * @return int
	 */
	public function set_items_per_page($amount) {
		if(!$amount || !is_numeric($amount) || $amount <= 0) {	return false;	}
		$this->items_per_page	=	(int)$amount;
		return true;
	}
	
	/**
	 * get_items_per_page function.
	 * 
	 * @access public
	 * @return int
	 */
	public function get_items_per_page() {
		return (int)$this->items_per_page;
	}
	
	/**
	 * set_jumpers function.
	 * 
	 * @access public
	 * @param mixed $bool
	 * @return int
	 */
	public function set_jumpers($bool) {
		$this->jumpers	=	(bool)($bool) ? true:false;
	}
	
	/**
	 * set_wrapper_tag function.
	 * 
	 * @access public
	 * @param mixed $tag
	 * @return int
	 */
	public function set_wrapper_tag($tag) {
		if(!$tag || !is_string($tag) || empty($tag) || trim($tag) === '') {	return false;	}
		$this->tag_wrapper	=	(string)str_replace(array('<','>'),'',$tag);
		return true;
	}
	
	/**
	 * get_wrapper_tag function.
	 * 
	 * @access public
	 * @return int
	 */
	public function get_wrapper_tag() {
		return (string)$this->tag_wrapper;
	}
	
	/**
	 * set_item_tag function.
	 * 
	 * @access public
	 * @param mixed $tag
	 * @return int
	 */
	public function set_item_tag($tag) {
		if(!$tag || !is_string($tag) || empty($tag) || trim($tag) === '') {	return false;	}
		$this->tag_item	=	(string)str_replace(array('<','>'),'',$tag);
		return true;
	}
	
	/**
	 * get_item_tag function.
	 * 
	 * @access public
	 * @return int
	 */
	public function get_item_tag() {
		return (string)$this->tag_item;
	}
	
	/**
	 * set_active_class function.
	 * 
	 * @access public
	 * @param mixed $class
	 * @return int
	 */
	public function set_active_class($class)	{
		if(!$class || !is_string($class) || empty($class) || trim($class) == '') {	return false;	}
		$this->activeclass 	=	(string)$class;
		return true;
	}
	
	/**
	 * get_active_class function.
	 * 
	 * @access public
	 * @return int
	 */
	public function get_active_class() {
		return (string)$this->activelcass;
	}
	
	/**
	 * set_attributes function.
	 * 
	 * @access public
	 * @param array $attributes (default: array())
	 * @return int
	 */
	public function set_attributes($attributes=array()) {
		if(!is_array($attributes)) {	return false;	}
		$this->attributes	=	$attributes;
		return true;
	}
	
	/**
	 * get_attributes function.
	 * 
	 * @access public
	 * @return int
	 */
	public function get_attributes() {
		return $this->attributes;
	}
	
	/**
	 * render function.
	 * 
	 * @access public
	 * @return int
	 */
	public function render() {
		$this->calculate();
		
		$pages		=	$this->totalpages;
		if($pages > $this->maxpages) {
			$pages	=	$this->maxpages;
		}
		$viewable	=	(int)($this->maxpages/2);
		$start		=	1;
		$end		=	$pages;
		
		if($this->currentpage > $viewable) {
			$start	=	$this->currentpage-$viewable;
			$end	=	$this->currentpage+$viewable-1;
		}
		
		if($this->currentpage > ($this->totalpages-$viewable)) {
			$end	=	$this->currentpage+($this->totalpages-$this->currentpage);
			$start	=	($this->totalpages - $this->maxpages)+1;
		}
		
		if($start 	< 1) {	$start	=	1;	}
		if($end 	>	$this->totalpages)	{	$end	=	$this->totalpages;	}
		
		echo '<'.$this->tag_wrapper;
		if(isset($this->attributes['wrapper']) && count($this->attributes['wrapper']) > 0) {
			foreach($this->attributes['wrapper'] as $key => $item) {
				echo ' '.$key.'="'.$item.'"';
			}
		}
		echo '>' . PHP_EOL;
		
		if($this->jumpers	===	true && $this->currentpage != 1) {
			echo '<span class="first"><a href="'.$this->render_text($this->link_url,1).'" title="'.$this->render_text($this->jumper_first_title,1).'">'.$this->render_text($this->jumper_first_text,1).'</a></span>' . PHP_EOL;
		}
		
		if($this->steps	===	true && $this->currentpage > 1) {
			echo '<span class="previous"><a href="'.$this->render_text($this->link_url,($this->currentpage-1)).'" title="'.$this->render_text($this->steps_back_title,($this->currentpage-1)).'">'.$this->render_text($this->steps_back_text,($this->currentpage-1)).'</a></span>' . PHP_EOL;
		}
		
		for($i=$start;$i<=$end;$i++) {
			$active =	false;
			echo '<'.$this->tag_item;
			
			if(isset($this->attributes['item']) && count($this->attributes['item']) > 0) {
				foreach($this->attributes['item'] as $key => $item) {
					if($key === 'class' && $this->currentpage === $i) {
						$classexists	=	true;
						$item.=' '.$this->activeclass;
						$active 	=	true;
					}
					echo ' '.$key.'="'.$this->render_text($item,$i).'"';
				}
			}
			
			if($this->currentpage === $i && !isset($classexists)) {
				echo ' class="'.$this->activeclass.'"';
				$active 	=	true;
			}
			
			echo '>';
			
			if($active == false || $this->activelink == true) {
				echo '<a href="'.$this->render_text($this->link_url,$i).'" title="'.$this->render_text($this->link_title,$i).'">';
			}
			
			echo $this->render_text($this->link_text,$i);
			
			if($active == false || $this->activelink == true) {
				echo '</a>';
			}
			
			echo '</'.$this->tag_item.'>' . PHP_EOL;
		}
		
		if($this->steps	===	true && $this->currentpage < $this->totalpages) {
			echo '<span class="next"><a href="'.$this->render_text($this->link_url,($this->currentpage+1)).'" title="'.$this->render_text($this->steps_next_title,($this->currentpage+1)).'">'.$this->render_text($this->steps_next_text,($this->currentpage+1)).'</a></span>' . PHP_EOL;
		}
		
		if($this->jumpers	===	true && $this->currentpage != $this->totalpages ) {
			echo '<span class="last"><a href="'.$this->render_text($this->link_url,$this->totalpages).'" title="'.$this->render_text($this->jumper_last_title,$this->totalpages).'">'.$this->render_text($this->jumper_last_text,$this->totalpages).'</a></span>' . PHP_EOL;
		}
		
		echo '</'.$this->tag_wrapper.'>' . PHP_EOL;
	}
	
	/**
	 * fetch function.
	 * 
	 * @access public
	 * @return int
	 */
	public function fetch() {
		ob_start();
		$this->render();
		$data	=	ob_get_contents();
		ob_end_clean();
		return $data;
	}
	
	/**
	 * calculate function.
	 * 
	 * @access private
	 * @return int
	 */
	private function calculate() {
		$pages	=	(int)($this->itemcount/$this->items_per_page);
		if($this->itemcount%$this->items_per_page !== 0) {	$pages++;	}
		$this->totalpages	=	(int)$pages;
		
		if($this->currentpage < 1) {	$this->currentpage =	1;	}
		if($this->currentpage > $this->totalpages)	{	$this->currentpage	=	$this->totalpages;	}
	}
	
	/**
	 * render_text function.
	 * 
	 * @access private
	 * @param mixed $txt
	 * @param mixed $var
	 * @param string $tag (default: '##ID##')
	 * @return int
	 */
	private function render_text($txt,$var,$tag='##ID##') {
		if(!$txt || !is_string($txt) || empty($txt) || trim($txt) === '') {	return 'ERROR';	}
		if(!$var || empty($var) || trim($var) === '') {	return 'ERROR';	}
		if(!$tag || !is_string($tag) || empty($tag) || trim($tag) === '') {	return 'ERROR';	}
		
		return str_replace($tag,$var,$txt);
	}
} 
?>
