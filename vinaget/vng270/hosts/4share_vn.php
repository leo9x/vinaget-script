<?php // Check account con thieu loai VIP

class dl_4share_vn extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://up.4share.vn/?control=manager", $cookie, "");
		if(stristr($data, 'Lý do: Share account!')) return array(true, "Your account is block until ".$this->lib->cut_str($data, 'bị khóa đến ',', Lý do: Share account! </b>'));
		else if(stristr($data, '<td>Loại tài khoản</td><td> <b>VIP FUNNY</b>') && stristr($data, '| Không giới hạn thời gian   |')) return array(true, "Account is VIP FUNNY. <br />You can download ".$this->lib->cut_str($data, '<br />Bạn còn được download <strong>','</strong> /Tổng số:'));
		else if(stristr($data, '<td>Loại tài khoản</td><td> <b>VIP FUNNY</b>') && stristr($data, '| Đã hết hạn VIP  FUNNY  <strong>')) return array(false, "Account Funny is expired!");
		else if(stristr($data, '<td>Loại tài khoản</td><td> <b>MEMBER </b>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://4share.vn/?control=login","","inputUserName={$user}&inputPassword={$pass}&submit=Login");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if (preg_match("%<a href='(.+)'><img src='\/images\/download.button.png'\/>%U", $data, $value) || preg_match("%<a href='(.+)'>DOWNLOAD </a>%U", $data, $value)) {
				$link = $value[1];
				$arr = explode('&',$link);
				$a = strlen($arr[2]);
				$str='';
				for($i=0;$i<$a;$i++)
					$str .= preg_replace("/([^a-zA-Z0-9\.\-\=])/", '_', $arr[2][$i]);
					$arr[2]=$str;
					$link = implode('&',$arr);
			return trim($link);
			}
			
  		elseif (stristr($data,"File not found"))   $this->error("dead", true, false, 2);
  		elseif (stristr($data,"FID Không hợp lệ"))  $this->error("Invalid FID", true, false, 2);		
  		elseif (stristr($data,"File đã bị xóa"))   $this->error("dead", true, false, 2);
  		elseif (stristr($data,"File có password download, hãy nhập password để download!"))  $this->error("Need Password. Plugin Doesn't Support Password", true, false, 2); 
  		elseif (stristr($data,"bị khóa đến")) 	 $this->error("blockAcc");
		
	
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 4Share.VN Download Plugin 
* Downloader Class By [FZ]
* Plugin By giaythuytinh176
* Date: 16.7.2013 
* Check account included - 18.7
*/
?>