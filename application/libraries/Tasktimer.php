<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
LOGIC
startDateTime
processDateTime
finishDateTime

delayTime = processDateTime - startDateTime
processtime = finishDateTime - processDateTime
cycleTime = finishDateTime - startDateTime


[when start]
processDateTimeTemp = processDateTime || NOW 

[when stop or Finish]
processRealTime = processTime + (NOW - processDateTimeTemp)
*/
class Tasktimer extends Model{
	var aDbField=array();
	
	function Tasktimer(){
		$this->CI =& get_instance();
		parent::Model();
		$this->setup('tttasktime','id');
		
		$this->aDbField = array ("id", "srefid", "suserid", "scategory", "dstarttime", "dprocesstime", "dfinishtime", "dprocesstemptime", "dprocessrealtime");
    }
	
	function _UserAlter($sRefId,$sCategory='',$sUserId=''){
		/*
		 - update first user for the task
		 - create clone record if user already exist 
		*/
	}

	function setTimerStart($sRefId, $sCategory='', $sUserId='', $dTime=date('Y-m-d h:i:s')){
		/*
		 - if already started, blank 
		*/
		
		$aCriteria['srefid'] = $sRefId;
		if ($sCategory!='') $aCriteria['scategory']=$sCategory;
		if ($sUserId!='') $aCriteria['suserid']=$sUserId;
		
		$taskTimer=$this->db->get_where('tttasktimer', $aCriteria);
		if ($taskTimer->num_rows()==0) {
			$this->load->model('Util_model');
			$uId = Util_model::getUuid();
			$this->db->set('id', $uId);
			$this->db->set('suserid', $sUserId);
			$this->db->set('scategory', $sCategory);
			$this->db->set('dStartDateTime', $dTime);
			$this->db->set('dcreatedate', 'current_timestamp', FALSE);
			$this->db->set('dlastupdate', 'current_timestamp', FALSE);
			$this->db->insert('tttasktime');
		}
		
		$this->_UserAlter($sRefId,$sCategory,$sUserId);
	}
	
	function setTimerProcess($sRefId, $sCategory, $sUserId){
		/*
		 - if delay time = null then set delay time
		 - if processing time = null then set processing time
		 - if processing time temp = null then set processint time temp		 
		*/
		// update
		$this->db->set('suserid', $sUserId);
		$this->db->set('scategory', $sCategory);
		$this->db->set('dlastupdate','current_timestamp', FALSE);
		$this->db->where('srefid', $sRefId);
		$this->db->update('tttasktime');
		$this->_UserAlter($sRefId,$sCategory,$sUserId);
	}
	
	function setTimerProcessPause($sRefId,$sCategory,$sUserId){
		/*
		 - processing time = processing time + (current time - processing time temp)
		 - clear processing time temp 
		*/
		$this->_UserAlter($sRefId,$sCategory,$sUserId);
	}
	
	function setTimerFinish($sRefId,$sCategory,$sUserId){
		/*		 
		 - set finish time
		 - processing time = processing time + (current time - processing time)
		 - clear processing time temp 
		 - cycle time = finish time - start time
		*/
		$this->_UserAlter($sRefId,$sCategory,$sUserId);
	}
	
	function getTimerDelay($sRefId,$table='',$model=''){
		
	}
	
	function getTimerProcess($sRefId,$table='',$model=''){
		
	}
	
	function getTimerProcessReal($sRefId,$table='',$model=''){
		
	}
	
	function getTimerCycle($sRefId,$table='',$model=''){
		
	}
	
	function getList(){
		$this->getTotal();
	}
	
	function getListJoin(){
		$this->getTotal();
	}
	
	function getTotal(){
		
	}	
}
?>