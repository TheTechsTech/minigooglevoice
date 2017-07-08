<?php
session_start();
$getID = !empty($_GET['messageId']) ? ($_GET['messageId']) : '';
//require("validate.php"); 
require_once("header.php");
require("gvconfig.php"); 
require_once('googlevoice.php');
?>
<link type="text/css" rel="stylesheet" href="assets/<?php echo $theme; ?>/style.css" /> 
<script type="text/javascript" src="assets/global.js"></script>
<?php
if(($_SESSION['pidcommand'] == "Recorded") or ($_SESSION['pidcommand'] == "Voicemail")) { ?>
	<script type='text/javascript' src="assets/jquery-ui-1.8.14.custom.min.js"></script>
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
<script type="text/javascript">
(function($){
  var code = $('.transcript').html();
  $('.txtarea').text(code);
})(jQuery);

$(function () {
    $("textarea").each(function () {
        this.style.height = (this.scrollHeight+10)+'px';
    });
});
</script>
<?php
$google = new GoogleVoice($GmailAccount, $GmailPassword);
$settingresults = $google->checksetting("SETTING");
$msgdetails = $google->getdetails($_SESSION['pidcommand'], $getID);
$getTexts = $google->history($_SESSION['pidcommand'], $getID);
$datetime = split(" ",$msgdetails->displayStartDateTime); 
//$receivedtime = date("F j, Y, g:i a", strtotime($datetime[0]." ".$msgdetails->displayStartTime) );
$receivedtime = date("F j, Y", strtotime($datetime[0]) );

function MakeUrls($str)
{
$out = str_replace("\n" , '<br>', $str);
$find=array('`((?:https?|ftp)://\S+[[:alnum:]]/?)`si','`((?<!//)(www\.\S+[[:alnum:]]/?))`si');
$replace=array('<a href="$1" target="_blank">$1</a>', '<a href="http://$1" target="_blank">$1</a>');
return preg_replace($find,$replace,$out);
}
?>
<body  id="content-container " class="main">
<div id="context-menu" class="context-menu">
	<div class="notify hide">
	 	<p class="message">
						<a href="" class="close action"><span class="replace">Close</span></a>
		</p>
	</div><!-- .notify -->		
</div>
<div class="content-main">

	<form id="message-details" class="form" action="gvactions.php?messageId=<?php echo $getID ?>" method="post">
	<div class="content-menu content-menu-top">
		<a href="gvdashboard.php?pid=<?php echo (!(empty($_GET['pid'])) ? $_GET['pid'] : 1 ) ?>" class="back-link">&laquo; Back to <?php echo $_SESSION['pidcommand'] ?></a>
		<ul class="details-menu menu-items-right">
			<li class="menu-item"><a href="javascript:void(0)" onclick="gvaction('archive','<?php echo $getID ?>')" class="submit-button link-button"><span>Archive</span></a></li>
			<li class="menu-item"><a href="javascript:void(0)" onclick="gvaction('star','<?php echo $getID ?>')" class="submit-button link-button"><span>Star</span></a></li>
			<li class="menu-item"><a hhref="javascript:void(0)" onclick="gvaction('unread','<?php echo $getID ?>')" class="submit-button link-button"><span>Mark Unread</span></a></li>
			<li class="menu-item"><a href="javascript:void(0)" onclick="gvaction('block','<?php echo $getID ?>')" class="submit-button link-button"><span>Block Caller</span></a></li>
			<li class="menu-item"><a href="javascript:void(0)" onclick="gvaction('delete','<?php echo $getID ?>','<?php echo $_SESSION['pidcommand'] ?>')" class="submit-button link-button"><span>Delete</span></a></li>
		</ul>
	</div><!-- .content-menu -->
	<div class="content-container">
		<div class="message-details-header">
			<p class="date-created">
				<?php echo $receivedtime ?></p>
			
			<table class="details-info">
				<tbody>
					<tr>
						<td><span class="call-from-label">To:</span></td>
						<td><span class="call-from-number"><?php echo $msgdetails->displayNumber ?></span>
							<a href="" class="quick-call-button"><span class="replace">Call</span></a>
							<div id="quick-call-popup <?php echo $msgdetails->phoneNumber; ?>" class="quick-call-popup hide" >
								<a href="" class="close action toggler"><span class="replace">close</span></a>	
								<p class="call-from-number"><?php echo $msgdetails->displayNumber; ?></p>
								<div class="caller-id-phone"><a href="gvdetails.php?messageId=<?php echo $msgdetails->id ?>" class="call">Call<span class="to hide"><?php echo $msgdetails->phoneNumber; ?></span> <span class="callerid hide"><?php echo $settingresults->primaryDidInfo->formattedNumber; ?></span><span class="from hide"><?php echo $settingresults->primaryDidInfo->phoneNumber; ?></span></a>
								</div>
							</div>
						</td>
					</tr>
			</table>
		</div><!-- .message-details-header -->		
		<div class="message-details-transcript">
			<h3><?php echo ((($_SESSION['pidcommand'] != "Recorded") and ($_SESSION['pidcommand'] != "Voicemail")) ? "Text Message" : "" ) ?></h3> 
			<div class="talk-content">
			<?php if (is_array($getTexts) and (($_SESSION['pidcommand'] != "Recorded") and ($_SESSION['pidcommand'] != "Voicemail"))) { 		
					foreach ($getTexts as $msghistory) {
						if ($msghistory->from == 'Me:' ) {?>
							<div class="transcript talk-bubble tri-right round right-top" style="float:right;"><p class="talktext"><?php echo "<font class=\"text10\" style=\"float:left;\">".$msghistory->from ."</font><br>". MakeUrls($msghistory->text) ."<br><font class=\"text10\" style=\"float:right;\">".$msghistory->time ."</font>" ?></p></div>
							<textarea class="txtarea" readonly style="border:none;resize:none" ></textarea>
						<?php } else { ?>
							<div class="transcript talk-bubble tri-right round btm-left-in" style="float:left;"><p class="talktext"><?php  echo "<font class=\"text10\" style=\"float:left;\">".$msghistory->from ."</font><br><b>". MakeUrls($msghistory->text) ."</b><br><font class=\"text10\" style=\"float:right;\">".$msghistory->time ."</font>" ?></p></div>	
							<textarea class="txtarea" readonly style="border:none;resize:none"></textarea>		
					<?php }
					} 
				} else {
					echo ($_SESSION['pidcommand'] == "Recorded") ? "<span class=\"audio-player\"><audio id=\"audio-player\" controls=\"controls\"><source src=\"googlerecorded/".$getID ."/".$getID .".mp3 \" type=\"audio/mp3\" ></audio></span>" : (($_SESSION['pidcommand'] == "Voicemail") ? "<span class=\"audio-player\"><audio id=\"audio-player\" src=\"googlevoicemail/".$getID ."/".$getID .".mp3 \" type=\"audio/mp3\" controls=\"controls\"></audio></span>" : "<div class=\"transcript talk-bubble tri-right btm-right-in\"><p><b> No Messages! </b></p></div><textarea class=\"txtarea\" readonly style=\"border:none;resize:none\" ></textarea>" );
				} ?>
			</div>
		</div><!-- .message-details-transcript -->
	</div><!-- .content-container -->
	</form>

		<div class="content-container">
		<div class="message-details-notes">
			<form id="reply-sms" name="reply-sms" action="gvactions.php" method="post">
				<h3><?php echo ((($_SESSION['pidcommand'] != "Recorded") and ($_SESSION['pidcommand'] != "Voicemail")) ? "Reply" : "Notes" ) ?></h3>
				<input type="hidden" name="from" value="<?php echo $settingresults->primaryDidInfo->phoneNumber; ?>" />
				<input type="hidden" name="to" value="<?php echo $msgdetails->phoneNumber; ?>" />
				<input type="hidden" name="action" value="<?php echo ((($_SESSION['pidcommand'] != "Recorded") and ($_SESSION['pidcommand'] != "Voicemail")) ? "sendText" : "addNote" ) ?>" />
				<input type="hidden" name="messageId" value="<?php echo $getID ?>" />
				<textarea id="content" name="<?php echo ((($_SESSION['pidcommand'] != "Recorded") and ($_SESSION['pidcommand'] != "Voicemail")) ? "content" : "messagenote") ?>"><?php echo $msgdetails->note; ?></textarea>
				<p class="count-desc"><span class="count"><?php echo ((($_SESSION['pidcommand'] != "Recorded") and ($_SESSION['pidcommand'] != "Voicemail")) ? 1600 : 512 ) ?></span> characters left</p>
				<button class="submit-button"><span><?php echo ((($_SESSION['pidcommand'] != "Recorded") and ($_SESSION['pidcommand'] != "Voicemail")) ? "Send SMS" : "Add Note") ?></span></button>
				<img class="loader hide" src="assets/i/ajax-loader.gif" alt="..." />
			</form>
			<ul id="message-details-notes-list">
							</ul>
		</div><!-- .message-details-notes -->
	</div><!-- .content-container -->
					
	<div class="content-menu content-menu-top">
		<a href="gvdashboard.php?pid=<?php echo (!(empty($_GET['pid'])) ? $_GET['pid'] : 1 ) ?>" class="back-link">&laquo; Back to <?php echo $_SESSION['pidcommand'] ?></a>

	</div><!-- .content-menu -->

</div><!-- .content-main -->
</body>
</html>
<?php
//require("footer.php"); 
?>
