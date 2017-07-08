<?php
session_start();
$getaction = !empty($_POST['action']) ? ($_POST['action']) : '' ;
$getID = !empty($_POST['messageId']) ? ($_POST['messageId']) : '' ;
//require("validate.php"); 
require("gvconfig.php");
require_once('googlevoice.php');
$google = new GoogleVoice($GmailAccount, $GmailPassword);

if (!empty($getaction)) {
	switch ($getaction) {
      case 'sendText' :
        $number = $_POST['to'];
        $message = $_POST['content'];
        $results = $google->sms($number, $message);
		break;
      case 'callNumber' :
        $results = $google->call($_POST['numberToCall'], $forwardingPhone, $phoneType); 
		break;
	  case 'cancelCall' :
        $results = $google->cancelcall($_POST['numberToCall'], $forwardingPhone, $phoneType);
		break;
      case 'addNote' :
        $results = $google->addNote($_SESSION['pidcommand'],$_POST['messageId'],$_POST['messagenote']); 
		break;
      case 'removeNote' :
        $results = $google->actions($_SESSION['pidcommand'],"unNoted",$_POST['messageId']); 
		break;
      case 'star' :
        $results = $google->actions($_SESSION['pidcommand'],"Starred", $_POST['messageId']); 
		break;
      case 'unStar' :
        $results = $google->actions($_SESSION['pidcommand'],"unStarred", $_POST['messageId']); 
		break;
      case 'markRead' :
        $results = $google->actions($_SESSION['pidcommand'],"markRead", $_POST['messageId']); 
		break;
      case 'unread' :
        $results = $google->actions($_SESSION['pidcommand'],"markUnread", $_POST['messageId']); 
		break;
      case 'archive' :
        $results = $google->actions($_SESSION['pidcommand'],"Archived", $_POST['messageId']); 
		break;
      case 'unArchive' :
        $results = $google->actions($_SESSION['pidcommand'],"unArchived", $_POST['messageId']); 
		break;
      case 'block' :
        $results = $google->actions($_SESSION['pidcommand'],"Blocked", $_POST['messageId']);     
		break;
      case 'unblock' :
        $results = $google->actions($_SESSION['pidcommand'],"unBlocked", $_POST['messageId']);     
		break;
	  case 'delete' :
        $results = $google->actions($_SESSION['pidcommand'],"Deleted", $_POST['messageId']); 
		break;
	}
}
if (!empty($results))
	echo $results;
?>
