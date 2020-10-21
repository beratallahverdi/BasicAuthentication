<?php 
class BasicAuthentication{
	private $pUsername = "USERNAME";
	private $pPwd = "PASSWORD";
	private $mUrl = "URL";
	private $authentication = "";
	public function __construct(){
		$this->authentication = "Basic ".base64_encode($this->pUsername.":".$this->pPwd);
	}
	/*
		GET XML Data from Server with Basic Authentication
	 */
	public function GetAllProductsByParts($index,$count){
		$link = "GetAllProductsByParts/";
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->mUrl.$link.$index."/".$count);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.$this->authentication));
		$sonuc= curl_exec($ch);
		$new = simplexml_load_string($sonuc);
		$con = json_encode($new);
		$a = json_decode($con,true);
		return $a;
	}
	/*
		GET Image from Server with Basic Authentication
	 */
	public function DownloadProductImageByCode($code,$folder="images/"){
		$link = "DownloadProductImage/ByCode/";
		if(strpos($code,"/"))
			$code = substr($code,strripos($code, "/"));
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->mUrl.$link.$code);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.$this->authentication));
		$sonuc= curl_exec($ch);
		curl_close($ch);
		$img = FCPATH.$folder.$code.".jpg";
		if(file_exists($img)){
			unlink($img);
		}
		$fp = fopen($img,'x');
		fwrite($fp, $sonuc);
		fclose($fp);
	}
}
?>