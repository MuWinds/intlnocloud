<?php
// +----------------------------------------------------------------------
// | Quotes [ 只为给用户更好的体验]**[我知道发出来有人会盗用,但请您留版权]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 零度            盗用不留版权,你就不配拿去!
// +----------------------------------------------------------------------
// | Date: 2019年08月20日
// +----------------------------------------------------------------------

//if(!defined('IN_CRONLITE'))exit();
class CACHE {
	public function save($value) {
		if (is_array($value)) $value = serialize($value);
		global $DB;
		$value = addslashes($value);
		return $DB->query("update `pay_config` set `v`='$value' where `k`='cache'");
	}
	public function pre_fetch(){
		global $_CACHE;
		$_CACHE=array();
		$_CACHE = $this->update();
		return $_CACHE;
	}
	public function update() {
		global $DB;
		$cache = array();
		$query = $DB->query('SELECT * FROM `pay_config` where 1');
		while($result = $query->fetch()){
			//if($result['k']=='cache') continue;
			$cache[ $result['k'] ] = $result['v'];
		}
		$this->save($cache);
		return $cache;
	}
	public function clear() {
		global $DB;
		return $DB->query("update `pay_config` set `v`='' where `k`='cache'");
	}
}

function saveSetting($k, $v){
	global $DB;
	$v = daddslashes($v);
	return $DB->query("REPLACE INTO `pay_config` SET `v`='$v',`k`='$k'");
}
