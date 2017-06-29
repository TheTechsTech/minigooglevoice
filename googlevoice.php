<?php

class GoogleVoice {

	private $_login;
	private $_pass;
    private $msg_messages;
    public $status;
  
  public function __construct($login, $pass) {	  
		$this->_login = $login;
		$this->_pass = urlencode($pass);
	}
	
	public function messages($googleitem)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
        $this->msg_messages = shell_exec("./gvmessages.py $Email $Passwd $googleitem");
        return json_decode($this->msg_messages);
    }
	
	public function getvoicemail()
    {
        $Email = $this->_login; 
		$Passwd = $this->_pass;
        $this->msg_messages = shell_exec("./gvvoicemail-mp3.py $Email $Passwd");
        return json_decode($this->msg_messages); 
    }
	public function getrecorded()
    {
        $Email = $this->_login; 
		$Passwd = $this->_pass;
        $this->msg_messages = shell_exec("./gvrecorded-mp3.py $Email $Passwd");
        return json_decode($this->msg_messages); 
    }
	public function getmessages($googleitem, $phonesearch)
    {
        $Email = $this->_login; 
		$Passwd = $this->_pass;
        $this->msg_messages = shell_exec("./gvgetmessages.py $Email $Passwd $googleitem $phonesearch");
        return json_decode($this->msg_messages); 
    }
		
	public function getdetails($googleitem, $msgID)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
        $this->msg_messages = shell_exec("./gvdetails.py $Email $Passwd $googleitem $msgID");
        return json_decode($this->msg_messages);
    }
	
	public function history($googleitem, $msgID)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
        $this->msg_messages = shell_exec("./gvhistory.py $Email $Passwd $googleitem $msgID");
        return json_decode($this->msg_messages);
    }		
	
	public function checksetting($googleitem)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
        $this->msg_messages = shell_exec("./gvsettingcheck.py $Email $Passwd $googleitem");
        return json_decode($this->msg_messages);
    }	
	
	public function actions($gvfolder, $googleitem, $msgID)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
		$dataID = escapeshellarg(json_encode($msgID));
        $this->msg_messages = shell_exec("./gvactions.py $Email $Passwd $gvfolder $googleitem $dataID");
        return $this->msg_messages;
    }	
	
	public function addNote($gvfolder, $msgID, $note)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
		$text = escapeshellarg(json_encode($note));
        $this->msg_messages = shell_exec("./gvaddNote.py $Email $Passwd $gvfolder $msgID $text");
        return $this->msg_messages;
    }	
	
	public function call($to_phonenumber, $from_phonenumber, $type_phone)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
		$phonenumber = preg_replace('#[^0-9]#','',strip_tags($to_phonenumber));
        $this->status = shell_exec("./gvcall.py $Email $Passwd $phonenumber $from_phonenumber $type_phone");
        return $this->status;
    }
	
	public function cancelcall($to_phonenumber, $from_phonenumber, $type_phone)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
		$phonenumber = preg_replace('#[^0-9]#','',strip_tags($to_phonenumber));
        $this->status = shell_exec("./gvcallcancel.py $Email $Passwd $phonenumber $from_phonenumber $type_phone");
        return $this->status;
    }

    public function sms($to_phonenumber, $smstxt)
    {
        $Email = $this->_login;
		$Passwd = $this->_pass;
		$text = escapeshellarg(json_encode($smstxt));
        $this->status = shell_exec("./gvsendsms.py $Email $Passwd $to_phonenumber $text");
        return $this->status;
    }
}
