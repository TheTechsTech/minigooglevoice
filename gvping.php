<?php
$command = (!empty($_GET['command']) ? ($_GET['command']) : "" );

function checkgoogle() {
	require_once("gvconfig.php");
	require_once('googlevoice.php');
	$google = new GoogleVoice($GmailAccount, $GmailPassword);
	$unreadCounts = $google->checksetting("UNREAD");
	if (($unreadCounts->unread) > 0) {
		echo "<a href=\"gvdashboard.php?command=Inbox\" class=\"notifybadge tooltip\" data-badge=\"".$unreadCounts->unread."\"><i class=\"fa fa-google faa-tada animated fa-3x \"></i><span>Unread Voice Messages.</span></a>";
	}
}

switch($command) {	
    case "checkgoogle":
		checkgoogle();
		break;
}
?>