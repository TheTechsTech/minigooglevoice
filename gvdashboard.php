<?php 
session_start();
$pagenumber = !empty($_GET['pid']) ? ($_GET['pid']) : '';
$commandline = !empty($_GET['command']) ? ($_GET['command']) : (!empty($_POST['action']) ? ($_POST['action']) : '' );
//require("validate.php");
require_once("header.php");
require("gvconfig.php");
require_once('googlevoice.php');
$google = new GoogleVoice($GmailAccount, $GmailPassword);
require_once('gvpagination.php');
if(($commandline == "Recorded") or ($commandline == "Voicemail")) { ?>
    <link rel="stylesheet" href="assets/mediaelementplayer.min.css" media="screen">
    <script src="assets/mediaelement-and-player.min.js"></script>
	<script>
    $(document).ready(function() {
        $('#audio-player').mediaelementplayer({
            alwaysShowControls: true,
            features: ['playpause','volume','progress'],
            audioWidth: 300,
            audioHeight: 40
        });
    });
</script>
<?php }
?>
<link type="text/css" rel="stylesheet" href="assets/<?php echo $theme; ?>/style.css" /> 
<script type="text/javascript" src="assets/global.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('button.call-button').click(function(){
		$("#quick-call").toggle({
		scrollTop: $("#context-menu").offset().top }).focus();
	});
	$('button.sms-button').click(function(){
		$("#quick-sms").toggle({
		scrollTop: $("#context-menu").offset().top }).focus();
	}); 
});
	
$(document).ready(function(){
    $("a.dropdown-select-button").click(function(){
        $("#menuselect").toggle();
    });
});

$(document).ready(function() {
  $('#check-all').click(function(){
    $("input:checkbox").attr('checked', true);
  });
  $('#uncheck-all').click(function(){
    $("input:checkbox").attr('checked', false);
  });
  $('#select-read').click(function(){  
    $('tr.read').find("input:checkbox").attr('checked', true); 
    $('tr.unread').find("input:checkbox").attr('checked', false);
  });
  $('#select-unread').click(function(){  
	$('tr.unread').find("input:checkbox").attr('checked', true);
	$('tr.read').find("input:checkbox").attr('checked', false);
  });
});

function gvdelete(RETURN) {
	var id=[];
    $('input.cb').each( function() {
		if($(this).attr('checked')) {
			id.push($(this).attr('value'));
		}
    });
    $.post('gvactions.php', { action: 'delete', messageId: id}, 
	function(data) { 
		notify(data);
		window.location.href = 'gvdashboard.php?command=' + RETURN;
	});
};
</script>
<?php
function TextLimit($str, $val=80){
	return ((strlen($str) <= $val) ? $str : substr($str,0,$val).'...' ) ;
}
	
if (!empty($commandline)) {
	unset($_SESSION['html']);
	unset($_SESSION['gvresults']);
	switch ($commandline) {
		case 'sendText' :
        $number = $_POST['to'];
        $message = $_POST['content'];		
		$numberToCall = "text";
        $action = $google->sms($number, $message);
		$commandline = "";
		break;
      case 'callNumber' :
		$numberToCall = $_POST['numberToCall'];
        $action = $google->call($_POST['numberToCall'], $forwardingPhone, $phoneType); 
		$commandline = "";
		break;
	  case 'cancelCall' :
		$numberToCall = "cancel";
        $action = $google->cancelcall($_POST['numberToCall'], $forwardingPhone, $phoneType); 		
		$commandline = "";
		break;
      case 'Search' :		
		$term = escapeshellarg(json_encode($_POST['TermToSearch']));
        $results = $google->messages($term);
        break;
      case 'Missed' :
        $results = $google->messages("Missed");
        break;
      case 'Placed' :
        $results = $google->messages("Placed");
        break;
      case 'Recieved' :
        $results = $google->messages("Recieved");
        break;
      case 'Inbox' :
        $results = $google->messages("Inbox");
        break;
      case 'All' :
        $results = $google->messages("All");
        break;
      case 'Messages' :
        $results = $google->messages("Messages");
        break;
      case 'Voicemail' :
		$results = $google->getvoicemail();
		break;
      case 'Recorded' :
		$results = $google->getrecorded();
		break;
      case 'Spam' :
		$results = $google->messages("Spam");
		break;
      case 'Trash' :
		$results = $google->messages("Trash");
		break;
	}
	$_SESSION['gvresults'] = $results;
} 
?>
<body class="main">
<div id="context-menu" class="context-menu">
	<div class="notify hide">
	 	<p class="message">
						<a href="" class="close action"><span class="replace">Close</span></a>
		</p>
	</div><!-- .notify -->	
<div id="callingstrip" >
		<button class="call-button call" href="#quick-call"><span > Call </span></button>
		<button class="sms-button sms" href="#quick-sms"><span > SMS </span></button>
</div>
<div id="quick-sms" class="sms-dialog hide">
<a class="close action" href=""><span class="replace">close</span></a>
		<h2>Send a Text Message</h2>
		<form action="gvdashboard.php" method="post" class="sms-dialog-form form">
		<fieldset class="input-complex input-container">
				<label class="field-label left"><b>To:</b>
					<input class="small" name="to" type="text" placeholder="(555) 867 5309" value="" />
				</label>			
					<input type="hidden" name="action" value="sendText" />			
				<br class="clear" />
				<label class="field-label" ><b>Message:</b>
					<textarea class="sms-message" id="content" name="content" placeholder="Enter your message"></textarea>
				</label>
		</fieldset>
			<button class="send-sms-button sms-button"><span >Send SMS</span></button>
			<img class="sms-sending hide" src="assets/pcrt/images/ajax-loader.gif" alt="loading" />
		</form>
</div>
<div id="quick-call" class="call-dialog hide">
<a class="close action" href=""><span class="replace">close</span></a>
		<h2 >Make a Call</h2>
		<form action="gvdashboard.php" method="post" class="sms-dialog-form form">
		<fieldset class="input-complex input-container">
				<label class="field-label left"><b>To:</b>
					<input class="small" name="numberToCall" type="text" placeholder="(555) 867 5309" value="" />
				</label>	
					<input type="hidden" name="action" value="callNumber" />	
		</fieldset>				
			<button class="call-sms-buttons call-button"><span >Place Call</span></button>
			<img class="call-to-phone hide" src="assets/pcrt/images/ajax-loader.gif" alt="loading" />
		</form>
</div>	
</div>
<div class="main inbox-menu content-menu-top" >
<?php
echo "<table style=\"font-size:12px;font-style:bolder;width=\"auto\" ><tr><td width=100%><a href=\"gvdashboard.php?command=Inbox\" class=\"fa fa-inbox fa-lg linkbuttonmedium linkbuttongray radiusleft\"> Inbox </a> ";
echo "<a href=\"gvdashboard.php?command=Messages\" class=\"fa fa-comments fa-lg linkbuttonmedium linkbuttongray\"> Messages </a> ";
echo "<a href=\"gvdashboard.php?command=Voicemail\" class=\"fa fa-headphones fa-lg linkbuttonmedium linkbuttongray\"> Voicemail </a> ";
echo "<a href=\"gvdashboard.php?command=Recorded\" class=\"fa fa-file-sound-o fa-lg linkbuttonmedium linkbuttongray\"> Recorded </a> ";
echo "<a href=\"gvdashboard.php?command=Missed\" class=\"fa fa-phone-square fa-lg linkbuttonmedium linkbuttongray linkbuttongray\"> Missed </a> ";
echo "<a href=\"gvdashboard.php?command=Placed\" class=\"fa fa-phone-square fa-lg linkbuttonmedium linkbuttongray linkbuttongray\"> Placed </a> ";
echo "<a href=\"gvdashboard.php?command=Recieved\" class=\"fa fa-phone-square fa-lg linkbuttonmedium linkbuttongray linkbuttongray\"> Recieved </a> ";
echo "<a href=\"gvdashboard.php?command=Spam\" class=\"fa fa-bug fa-lg linkbuttonmedium linkbuttongray linkbuttongray\"> Spam </a> ";
echo "<a href=\"gvdashboard.php?command=Trash\" class=\"fa fa-trash fa-lg linkbuttonmedium linkbuttongray radiusright\"> Trash </a> ";
echo "<span class=\"sizeme16 boldme\" style=\"float:right;\"> <a class=\"fa fa-wrench fa-lg\" href=\"gvsettingcheck.php\" > Setting </a> </span></td></tr></table>";
?>
</div>
<?php
if (!empty($commandline))
{
	echo "<h2 ".(($theme=="griffin") ? "style=\"color:#cccccc;font-variant: small-caps;\"" : "style=\"font-variant: small-caps;\"").">".$commandline."</h2>"; 
	$_SESSION['pidcommand'] = $commandline;
} elseif (isset($_SESSION['pidcommand'])){
	echo "<h2 ".(($theme=="griffin") ? "style=\"color:#cccccc;font-variant: small-caps;\"" : "style=\"font-variant: small-caps;\"").">".$_SESSION['pidcommand']."</h2>";
} else {
	echo "<h2></h2>";
}
if (!(empty($_SESSION['gvresults'])) and (isset($_SESSION['pidcommand']))) {
	$pagecounter = 0;
	$itemsperpage = $gvperpage;
	$total_pages = (((count($_SESSION['gvresults']) > $itemsperpage) ? count($_SESSION['gvresults']) : $itemsperpage));
	$attributes  				=	array();
	$attributes['wrapper']		=	array('class'=>'pagination');
	$attributes['item']			=	array('class'=>'num');
	$options					=	array();
	$options['attributes']		=	$attributes;
	$options['items_per_page']	=	$itemsperpage;;
	$options['maxpages']		=	$total_pages;
	$Paginations = new pagination($total_pages,((isset($_GET['pid'])) ? $_GET['pid']:1),$options);
?>				


		<div class="main content-menu content-menu-top">
			<ul class="inbox-menu menu-items-left">
				<li class="menu-item"><a href="javascript:void(0)" class="dropdown-select-button link-button"><span>Select</span></a>
					<ul id="menuselect" class="hide">
						<li><a id="check-all" href="javascript:void(0);">Select All</a></li>
						<li><a id="uncheck-all" href="javascript:void(0);">Select None</a></li>
						<li><a id="select-read" href="javascript:void(0);">Select Read</a></li>
						<li><a id="select-unread" href="javascript:void(0);">Select Unread</a></li>
					</ul>
				</li>
				<li class="menu-item"><a href="javascript:void(0)" onclick="gvdelete('<?php echo ($_SESSION['pidcommand']); ?>')" class="delete-button link-button"><span>Delete</span></a></li>
			</ul>
			<form action="gvdashboard.php" method="post" style="float:right;">
						<input type="text" value="" name="TermToSearch" maxlength="50" placeholder="search"/> 						
						<input type="hidden" value="Search" name="action"/> 
						<button class="submit-button" type="submit"> <span>Search</span></button></form>						
		</div>
	<table border=0 class="items-grid">
	<tbody>
<?php
	$offset = ((isset($_GET['pid'])) ? $_GET['pid']:1) * $itemsperpage;
	$start = $offset;
	if ($start <= $itemsperpage)
		$start = 0;
	if ((isset($_SESSION['html'][$start])) and (!empty($pagenumber))){
		for ($i = 0; $i < $itemsperpage; $i++)
		{
			if (isset($_SESSION['html'][$start])){				
				echo $_SESSION['html'][$start];
			}
			$start++;
		}
	} else {
		foreach ($_SESSION['gvresults'] as $item) {
			$datetime = split(" ",$item->displayStartDateTime); 
			$receivedtime = $datetime[0]." ".$item->displayStartTime ;
			$callerPhone = $item->displayNumber;
			$_SESSION['html'][$pagecounter] = "<tr rel=". $item->id ." class=\"message-row ". (($item->messageText != "") ? 'sms-type' : 'call-type' ) ." ". (($item->isRead) ? 'read' : 'unread' )."\">";
			$_SESSION['html'][$pagecounter] .= "<td class=\"message-select\"><div style=\"padding: 6px\">";
			$_SESSION['html'][$pagecounter] .= "<input type=\"checkbox\" class=\"cb\" name=\"messageId[]\" value=\"". $item->id ."\" /></div></td>";
			$_SESSION['html'][$pagecounter] .= "<td class=\"message-caller message-details-link\">";
			$_SESSION['html'][$pagecounter] .= "<span class=\"phone-number\" >".$callerPhone."</span>";
			if($item->displayNumber != "") {
				$_SESSION['html'][$pagecounter] .= "<a href=\"\" class=\"quick-call-button\"><span class=\"replace\">".$callerPhone."</span></a>";
				$_SESSION['html'][$pagecounter] .= "<a href=\"\" class=\"quick-sms-button\"><span class=\"replace\">".$callerPhone."</span></a>";
				$_SESSION['html'][$pagecounter] .= "<div id=\"quick-call-popup\" class=\"quick-call-popup call hide\">";
				$_SESSION['html'][$pagecounter] .= "<a href=\"\" class=\"close action toggler\"><span class=\"replace\">close</span></a>";
				$_SESSION['html'][$pagecounter] .= "<span class=\"call-to-phone\">". $item->displayNumber ."</span>";
				$_SESSION['html'][$pagecounter] .= "<div class=\"caller-id-phone\">";
				$_SESSION['html'][$pagecounter] .= "<a href=\"gvactions.php\" class=\"call\">Call<span class=\"to hide\">". $item->phoneNumber ."</span>";
				$_SESSION['html'][$pagecounter] .= "</a></div></div>";
				$_SESSION['html'][$pagecounter] .= "<div id=\"quick-sms-popup\" class=\"quick-sms-popup hide\">";
				$_SESSION['html'][$pagecounter] .= "<a href=\"\" class=\"close action sms-toggler\"><span class=\"replace\">close</span></a>";
				$_SESSION['html'][$pagecounter] .= "<input class=\"sms-message\" type=\"text\" name=\"content\" />";
				$_SESSION['html'][$pagecounter] .= "<span class=\"count\">160</span>";
				$_SESSION['html'][$pagecounter] .= "<button class=\"send-button\" rel=".$item->id."><span>Send</span></button>";
				$_SESSION['html'][$pagecounter] .= "<img class=\"sending-sms-loader hide\" 	src=\"assets/i/ajax-loader.gif\" alt=\"...\" />";
				$_SESSION['html'][$pagecounter] .= "<span class=\"sms-to-phone hide\">". $item->phoneNumber ."</span>"; 
				$_SESSION['html'][$pagecounter] .= "<span class=\"from-phone hide\">". $callerPhone ."</span>";
				$_SESSION['html'][$pagecounter] .= "</div>";
			}
				$_SESSION['html'][$pagecounter] .= "</td><td class=\"message-caller message-details-link\" >"; 	
			if (($_SESSION['pidcommand'] != "Recorded") and ($_SESSION['pidcommand'] != "Voicemail")) {			
				$_SESSION['html'][$pagecounter] .= "<a href=\"gvdetails.php?messageId=". $item->id ."\"><span class=\"transcript\" >".((TextLimit($item->messageText)!="") ? TextLimit($item->messageText) : (($_SESSION['pidcommand']=="Missed") ? 'Missed Call...' : '...' ))."</span></a>";
			} elseif ($_SESSION['pidcommand'] == "Recorded") {			
				$_SESSION['html'][$pagecounter] .= "<a href=\"gvdetails.php?messageId=". $item->id ."\"><span class=\"transcript\">Transcript: ".$item->note."</span></a><span class=\"audio-player\"><audio id=\"audio-player\"  controls=\"controls\"><source src=\"googlerecorded/".$item->id."/".$item->id.".mp3 \" type=\"audio/mp3\" ></audio></span>";				
			} elseif ($_SESSION['pidcommand'] == "Voicemail") {			
				$_SESSION['html'][$pagecounter] .= "<a href=\"gvdetails.php?messageId=". $item->id ."\"><span class=\"transcript\" >Transcript: ".$item->note."</span></a><span class=\"audio-player\"><audio id=\"audio-player\" src=\"googlevoicemail/".$item->id."/".$item->id.".mp3 \" type=\"audio/mp3\" controls=\"controls\"></audio></span>";				
			}
			$_SESSION['html'][$pagecounter] .= "</td><td class=\"message-timestamp message-details-link\">";
			$_SESSION['html'][$pagecounter] .= "<div class=\" \">".$item->relativeStartTime."</div>";
			$_SESSION['html'][$pagecounter] .= "</td></tr>";
			$pagecounter++;
			}
		for ($i = 0; $i < $itemsperpage; $i++)
			{
				if (isset($_SESSION['html'][$i]))
					echo $_SESSION['html'][$i];
			}
	}
?>
		</tbody>
		</table>
		<div class="content-menu content-menu-top">
			<?php 
				$Paginations->render();
			?>	
		</div>
<?php
	} else { 
?>		
			<div class="content-section">
				<div class="messages-blank">
			<form class="form" action="gvdashboard.php" method="post" >
				<input type="text" value="" name="TermToSearch" maxlength="30" placeholder="search"/>
				<input type="hidden" value="Search" name="action"/> 
				<button class="submit-button" type="submit"><span> Search</span></button></form>
				<?php if (!empty($action)) {					
						echo "<h2>".$action."</h2>"; 
						if (($numberToCall != "cancel") and ($numberToCall != "text")) { ?>
					<div id="cancel-call" class="call-dialog">
						<h2 >Cancel Call</h2>
						<form action="gvdashboard.php" method="post" class="sms-dialog-form form">
							<input type="hidden" name="numberToCall" type="text" value="<?php echo $numberToCall; ?>" />
							<input type="hidden" name="action" value="cancelCall" />	
							<button class="call-sms-buttons call-button"><span >Cancel</span></button>
							<img class="call-to-phone hide" src="assets/pcrt/images/ajax-loader.gif" alt="loading" />
						</form>
					</div>					
				<?php 	}
					} ?>
				</div>
			</div>		
<?php
	}
?>
</body>
</html>
<?php
//require("footer.php"); 
?>
