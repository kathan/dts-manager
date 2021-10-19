<?php
class Paginator{
	var $start;//
	var $next_start;//
	var $next_count;
	var $end;//
	var $total;//
	var $db_result;//
	var $limit;//
	var $prev_count;
	var $prev_start;
	
	function __construct($result, $start=1, $limit=20){
		$this->db_result = $result;
		if($result){
			$this->total = $this->db_result->rowCount();
		}
		
		$this->limit = $limit;
		if($this->total == 0){
			$this->start = 0;
		}elseif($start == 0){
			$this->start = 1;
		}else{
			$this->start = $start;
		}
		$this->next_start = $this->start + $this->limit;
		($this->start + $this->limit) > $this->total ? $this->end = $this->total: $this->end = $this->start + $this->limit - 1;
		if($this->next_start == null){
			$this->next_count = null;
		}else{
			($this->next_start + $this->limit) > $this->total ? $this->next_count = $this->total - $this->end : $this->next_count = $this->limit;
		}
		$this->start > $this->limit ? $this->prev_start = $this->start - $this->limit : $this->prev_start = 1;
		$this->start - $this->limit < 1 ? $this->prev_start = 1 : '';
		$this->prev_count = $this->limit;
	}
	
	function get(){
		$a = [];
		$a['start'] = $this->start;
		$a['next_start'] = $this->next_start;
		$a['next_count'] = $this->next_count;
		
		$a['prev_start'] = $this->prev_start;
		$a['prev_count'] = $this->prev_count;
		
		$a['end'] = $this->end;
		$a['total'] = $this->total;
		$a['limit'] = $this->limit;
		$pages = floor($this->total / $this->limit);
		$a['last_page_start'] = ($pages * $this->limit) +1;
		return $a;
	}
	
	function fastFoward($re){
		$count = 1;
		while($count < $this->start && ($row = $re->fetch())){
			$count++;
		}
	}

	function to_array($re){
		$this->fastFoward($re);
		$ary = [];
		$i = $this->start-1;
		$count=0;
		while($count < $this->limit && ($row = $re->fetch(PDO::FETCH_ASSOC, null))){
			if($count >= $this->limit){
				break;
			}
			$ary[] = $row;
			$i++;
			$count++;
		}

		return $ary;
	}
	
}
?>