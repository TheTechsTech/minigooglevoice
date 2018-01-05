<?php
//require("validate.php");
$filename = "gvconfig.php";
require("$filename");
$gettheme = (isset($_POST['sitetheme']) ? $_POST['sitetheme'] : "$theme" );
$getphone = (isset($_POST['forwardingPhone']) ? $_POST['forwardingPhone'] : "$forwardingPhone" );
$getphonetype = (isset($_POST['phoneType'.$getphone]) ? $_POST['phoneType'.$getphone] : "$phoneType" );
$getemail = (isset($_POST['accountemail']) ? $_POST['accountemail'] : "$GmailAccount" );
$getpassword = (isset($_POST['accountpassword']) ? $_POST['accountpassword'] : "$GmailPassword" );
$getpages = (isset($_POST['perpage']) ? $_POST['perpage'] : "$gvperpage" );
$configurationData = file_get_contents($filename);
if ((empty($configurationData)) or (isset($_POST['gvcnnect'])) or (isset($_POST['gvupdate']))) {
	$configurationData = "<?php \n";
	$configurationData .= "\$mysmsgateway = \"google\"".";\n";
	$configurationData .= "\$mysmsprefix = \"\"".";\n";
	$configurationData .= "\$theme = \"$gettheme\"".";\n";
	$configurationData .= "\$forwardingPhone = \"$getphone\"".";\n";
	$configurationData .= "\$phoneType = \"$getphonetype\"".";\n";
	$configurationData .= "\$GmailAccount = \"$getemail\"".";\n";
	$configurationData .= "\$GmailPassword = \"$getpassword\"".";\n";
	$configurationData .= "\$gvperpage = \"$getpages\"".";\n";
	$configurationData .= "?>\n";
	file_put_contents($filename, $configurationData, LOCK_EX);
} 
require("$filename");
require_once("header.php"); 
?>
<link type="text/css" rel="stylesheet" href="assets/<?php echo $theme; ?>/style.css" />
<body  class="main" >
<div id="context-menu" class="context-menu">
</div>
<div class="main content-menu-top"  >
<?php
require_once('googlevoice.php');
$google = new GoogleVoice($GmailAccount, $GmailPassword);
$settingresults = $google->checksetting("SETTING");
$phoneresults = $google->checksetting("PHONES");
echo "<table style=\"font-size:12px;font-style:bolder;width=\"auto\" ><tr><td width=100%><a href=\"gvdashboard.php?command=Inbox\" class=\"fa fa-inbox fa-lg linkbuttonmedium linkbuttongray radiusleft\"> Inbox </a> ";
echo "<a href=\"gvdashboard.php?command=Messages\" class=\"fa fa-comments fa-lg linkbuttonmedium linkbuttongray\"> Messages </a> ";
echo "<a href=\"gvdashboard.php?command=Voicemail\" class=\"fa fa-headphones fa-lg linkbuttonmedium linkbuttongray\"> Voicemail </a> ";
echo "<a href=\"gvdashboard.php?command=Recorded\" class=\"fa fa-file-sound-o fa-lg linkbuttonmedium linkbuttongray\"> Recorded </a> ";
echo "<a href=\"gvdashboard.php?command=Missed\" class=\"fa fa-phone-square fa-lg linkbuttonmedium linkbuttongray linkbuttongray\"> Missed </a> ";
echo "<a href=\"gvdashboard.php?command=Placed\" class=\"fa fa-phone-square fa-lg linkbuttonmedium linkbuttongray linkbuttongray\"> Placed </a> ";
echo "<a href=\"gvdashboard.php?command=Received\" class=\"fa fa-phone-square fa-lg linkbuttonmedium linkbuttongray linkbuttongray\"> Received </a> ";
echo "<a href=\"gvdashboard.php?command=Spam\" class=\"fa fa-bug fa-lg linkbuttonmedium linkbuttongray linkbuttongray\"> Spam </a> ";
echo "<a href=\"gvdashboard.php?command=Trash\" class=\"fa fa-trash fa-lg linkbuttonmedium linkbuttongray radiusright\"> Trash </a> ";
echo "<span class=\"sizeme16 boldme\" style=\"float:right;\"> <a class=\"fa fa-wrench fa-lg\" href=\"gvsettingcheck.php\" > Setting </a> </span></td></tr></table>";
?>
</div>
<table class="items-grid" >
<div class="info notice" style="float:right;width:300px;">
			<form action="gvsettingcheck.php" method="post" class="login form" accept-charset='UTF-8'><b>Google Voice Login:</b>
			<br>
				<input type="hidden" name="gvcnnect" value="1"/>
				<label class="field-label left"><b>Email: </b>
					<input class="medium" name="accountemail" type="text" value="<?php echo $GmailAccount; ?>" maxlength="50" required="required"/>
				</label><br>
				<label class="field-label left"><b>Password: </b>
					<input class="medium" name="accountpassword" type="password" value="<?php echo $GmailPassword; ?>" maxlength="50" required="required"/>
				</label><br>
			<button class="submit-button" type="submit"><span> Connect </span></button>
			</form>
			<h3>Once you get this set up you'll need to perform these two additional steps.</h3>After first logging into your Google account with a browser using the same IP address as accessing your Web server: <br><br>(1) <a href="https://www.google.com/settings/security/lesssecureapps" target="_blank">Enable Less Secure Apps</a>  and <br>(2) <a href="https://accounts.google.com/DisplayUnlockCaptcha" target="_blank">Activate the Google Voice Reset Procedure</a> <br><br>Now promptly send an SMS message from your Web server. Once these steps are done click on the links above to display messages in your Google Voice Account.
</div>
<div  <?php echo (($theme=="griffin") ? "style=\"color:#cccccc;\"" : "") ?>>
	<h3>Theme</h3>
<form action="gvsettingcheck.php" method="post" class="form">

	<fieldset class="input-container" style="border:none;">
		<label for="site-theme" class="field-label"><b>Choose a theme:</b>
			<select name="sitetheme"  id="site-theme" class="medium">
<option value="griffin" <?php echo (($theme=="griffin") ? "selected=\"selected\"" : "") ?>>Griffin</option>
<option value="mandala" <?php echo (($theme=="mandala") ? "selected=\"selected\"" : "") ?>>Mandala</option>
<option value="default" <?php echo (($theme=="default") ? "selected=\"selected\"" : "") ?>>Default</option>
<option value="barbosa" <?php echo (($theme=="barbosa") ? "selected=\"selected\"" : "") ?>>Barbosa</option>
</select>		</label>
	</fieldset>
	
	<h3>Phones</h3>
	<fieldset class="input-container" style="border:none;">
	<label for="forwardingPhone" class="field-label"><b>Choose your forwarding phone for outgoing calls:</b>
	<br>
	<?php 
	if (is_array($phoneresults)){
		$i = 0;
		foreach ($phoneresults as $phoneitem) { ?>
		<label for="phone-<?php echo $i; ?>" class="field-label-inline">
			<input required type="radio" name="forwardingPhone" value="<?php echo $phoneitem->phoneNumber; ?>"  <?php echo (($forwardingPhone==$phoneitem->phoneNumber) ? "checked=\"checked\"" : "") ?> required="required" /><?php echo $phoneitem->formattedNumber; ?>
			<input type="hidden" name="phoneType<?php echo $phoneitem->phoneNumber; ?>" value="<?php echo $phoneitem->type; ?>" />
		</label>
	<?php $i++;
		}
	} ?>
	</label>
	</fieldset>
	<label ><b>Messages per page: </b>
	<input type="text" name="perpage" value="10" maxlength="2"/></label><br>
	<input type="hidden" name="gvupdate" value="1"/>
	<button class="submit-button" type="submit"><span>Update</span></button>
	<?php
	echo "<div><b>Your Voice Number: </b>".$settingresults->primaryDidInfo->formattedNumber;
	echo "  <b>Credits: </b>".$settingresults->credits;
	echo "<br><b>SMS Notifications: </b><br>";
	if (is_array($settingresults->smsNotifications)) {
		foreach ($settingresults->smsNotifications as $smsAddress) {
			echo " <ul><b>Address: </b>".$smsAddress->address."";
			echo ($smsAddress->active=="true") ? ' <b>Active: </b> Yes</ul>' : ' <b>Active: </b> No</ul>' ;
		}
	} else {
		echo " <ul><b>Adress: </b>".$settingresults->smsNotifications->address;
		echo ($settingresults->smsNotifications->active=="true") ? '  <b>Active: </b> Yes</ul>' : ' <b>Active: </b> No</ul>' ;		
	}	
	echo "<b>Email Notification Address: </b>".$settingresults->emailNotificationAddress."</div>";
?>	
	<h3>Phone Setting</h3>

	<thead>
        <tr class="items-head">
			<th>Name</th>
			<th>Number</th>
			<th>SMS Forwarding</th>
			<th>Voicemail Forwarding</th>
			<th>Carrier</th>
		</tr>
	</thead>
	<tbody>
	<?php  
	if (is_array($phoneresults)){
		foreach ($phoneresults as $phoneitem) { ?>
		<tr class="items-row">
			<td><?php echo $phoneitem->name; ?></td>
			<td><?php echo $phoneitem->formattedNumber; ?></td>
			<td style="text-align:center;"><?php echo (($phoneitem->smsEnabled=="true") ? 'Yes' : 'No' ); ?></td>
			<td style="text-align:center;"><?php echo (($phoneitem->redirectToVoicemail=="true") ? 'Yes' : 'No' ); ?></td>
			<td style="text-align:center;"><?php echo $phoneitem->carrier; ?></td>
		</tr>	
	<?php }
	} ?>

	</tbody>
<!-- .items-grid -->	
</form>
</div><!-- #settings-theme -->
</table>
</body>
</html>
<?php
//require("footer.php"); 
?>