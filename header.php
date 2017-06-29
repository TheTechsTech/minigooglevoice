<!DOCTYPE html>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="assets/fa/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/fa/css/font-awesome-animation.min.css">
<link type="text/css" rel="stylesheet" href="assets/global.css" />
<script type='text/javascript' src="assets/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
                $.get('gvping.php?command=checkgoogle', function(data) {
                $('#checkgoogle').html(data);
                });
        setInterval(function() {
                $.get('gvping.php?command=checkgoogle', function(data) {
                $('#checkgoogle').html(data);
                });
        }, 30000);
});
</script>
<title>Mini Google Voice - Addon App</title>
</head>

<body  id="content-container">
<div style="float:left;"><h2><a href="gvdashboard.php" class="notifybarlink"><i class="fa fa-google fa-lg"></i> Voice</a></h2></div>
<div id="checkgoogle" >
</div>
</body>