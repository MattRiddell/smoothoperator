<?
/* Start or continue a logged in session */
session_start();

/* Find out the current location */
$current_directory = dirname(__FILE__);
if (isset($override_directory)) {
    $current_directory = $override_directory;
}

/* Include VentureVoIP Functions */
require "functions/functions.php";

/* Set user level to no access by default */
$user_level = 0;

/* Create a session if one has not already been created */
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
    $_SESSION['initiated'] = true;
}

/* Confirm that the user is using the same browser as they logged in with */
if (isset($_SESSION['HTTP_USER_AGENT'])) {
    if (!($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))) {
        if (isset($_SESSION['user_name'])) {
            if (isset($_SESSION['user_level'])) {
                $user_level = $_SESSION['user_level'];
            }
        }
    }
}

/* If the language is not set, set it to English - there is no current */
/* support for setting languages, but I realise it may be necessary in */
/* the future                                                          */
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = "en";
}

/* Get the actual PHP page (regardless of directory) */
$full_path = $_SERVER['PHP_SELF'];
$exploded_path = explode("/",$full_path);
$this_page = $exploded_path[sizeof($exploded_path)-1];

/* If the user level is not set or if they have not logged in, and the */
/* current page is not login.php, send them to the login page.         */
if (!isset($user_level) || $user_level < 1) {
    if ($this_page != "login.php") {
        ?>
        <script type='text/javascript' src='js/jquery-1.3.2.min.js'></script>
        <script type='text/javascript' src='js/jquery-ui-1.7.3.custom.min.js'></script>
        <script>
        jQuery.noConflict();
        </script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/default.css?ver=6">
        
        <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.3.custom.css">
        <script>
        top.location.href = "login.php" ;
        </script>
        <?
        //redirect("login.php");
        exit(0);
    }
}

/* Connect to the database */
require "config/db_config.php";

/* Get a list of menu items for this user level */
$menu_items = get_links($user_level, $connection, 1);
$menu_names = $menu_items[0];
$menu_links = $menu_items[1];
$menu_ids = $menu_items[2];

/* By default nobody is allowed to access anything */
$allowed = false;

/* If the page name is in the list of menu items for this user, allow it */
if (isset($menu_links)) {
    for($i = 0;$i<sizeof($menu_links);$i++) {
        if ($this_page == $menu_links[$i]) {
            $allowed = true;
            $this_page_id = $menu_ids[$i];
        }
    }
}

/* Get a list of pages that this user has access to but have no menu item */
$undefined_links_array = get_links($user_level, $connection, 0);

/* If the page name is in the list of non-menu items for this user, allow it */
for($i = 0; $i < sizeof($undefined_links_array[1]);$i++) {
    if ($this_page == $undefined_links_array[1][$i]) {
        $allowed = true;
        $this_page_id = $undefined_links_array[2][$i];
    }
}

unset($submenu_links_array);
$submenu_links_array = get_links($user_level, $connection, 1, -1, true);

/* If the page name is in the list of sub-menu items for this user, allow it */
for($i = 0; $i < sizeof($submenu_links_array[1]);$i++) {
    //foreach($submenu_links as $link) {
    if ($this_page == $submenu_links_array[1][$i]) {
        $allowed = true;
        $this_page_id = $submenu_links_array[2][$i];
    }
}

if ($_SESSION['user_level'] > 99 && $this_page == "cdr.php") {
    $allowed = true;
}
if ($_SESSION['user_level'] > 99 && $this_page == "realtime.php") {
    $allowed = true;
}
if ($_SESSION['user_level'] > 99 && $this_page == "drawpie.php") {
    $allowed = true;
}
if ($_SESSION['user_level'] > 99 && $this_page == "dispositions.php") {
    $allowed = true;
}
if ($_SESSION['user_level'] > 99 && $this_page == "script_results.php") {
    $allowed = true;
}
unset($undefined_links_array);

/* Get a list of pages that this user has access to but have no menu item */
$undefined_links_array = get_links($user_level, $connection, 0, 1);

/* If the page name is in the list of non-menu items for this user, allow it */
for($i = 0; $i < sizeof($undefined_links_array[1]);$i++) {
    if ($this_page == $undefined_links_array[1][$i]) {
        $allowed = true;
        $this_page_id = $undefined_links_array[2][$i];
    }
}

/* If we've reached here and still are not allowed, go to the index page */
if (!$allowed) {
    $_SESSION['messages'][] = "You have tried to access a page you are not permitted to access ".$this_page;
    header("Location: index.php");
    exit(0);
}

/* If we've made it this far, we're allowed to be viewing this page */

$config_values = $_SESSION['config_values'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?=stripslashes($config_values['site_name'])?></title>

<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/default.css?ver=6">
<link rel="stylesheet" type="text/css" href="css/uploadify.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.7.3.custom.css">


<script type="text/javascript" src="js/niftycube.js"></script>
<script type="text/javascript" src="js/prototype_1.6.1.js"> </script>
<script type="text/javascript" src="js/window.js"> </script>

<script type='text/javascript' src='js/jquery-1.3.2.min.js'></script>
<script type='text/javascript' src='js/jquery-ui-1.7.3.custom.min.js'></script>
<style>
.ui-datepicker { width: 17em; padding: .2em .2em 0; z-index: 9999 !important; }
div.thin_700px_box {border-radius: 10px}




div.thin_700px_box {border-radius: 8px;-moz-border-radius: 8px;}
div.thin_90perc_box {border-radius: 8px;-moz-border-radius: 8px;}
div.box {border-radius: 8px;-moz-border-radius: 8px;}
thin_700px_box {border-radius: 8px;-moz-border-radius: 8px;}
box_med {border-radius: 8px;-moz-border-radius: 8px;}
input.rounded {border-radius: 3px;-moz-border-radius: 8px;}
ul#nav a {border-radius: 10px}
<?
if (isset($rounded)) {
    foreach ($rounded as $item) {
        ?><?=$item?>{border-radius: 8px;-moz-border-radius: 8px;}<?
    }
}
if (isset($_SESSION['messages'])) {
    ?>
    div.messages{border-radius: 8px;-moz-border-radius: 8px;}
    <?
}
?>





</style>
<script>
jQuery.noConflict();
</script>
<script type="text/javascript" src="js/jeip.js"> </script>


<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript" src="js/jquery.uploadify.v2.1.0.min.js"></script>

<!--  Add this to have a specific theme-->
<link href="themes/alphacube.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript">
function hide_message(layer_ref){
    jQuery.post("clear_message.php", {queryString: layer_ref});
    if(document.all){ //IS IE 4 or 5 (or 6 beta)
        eval("document.all." +layer_ref+ ".style.display = none");
    }
    if (document.layers) { //IS NETSCAPE 4 or below
        document.layers[layer_ref].display = 'none';
    }
    if (document.getElementById &&!document.all) {
        hza = document.getElementById(layer_ref);
        hza.style.display = 'none';
    }
}

/*
NiftyLoad=function(){
    Nifty("div.thin_700px_box","large transparent");
    Nifty("div.box","large transparent");
    Nifty("thin_700px_box","large transparent");
    Nifty("box_med","large transparent");
    
    Nifty("input.rounded","large");
    Nifty("ul#nav a","small transparent top");
    <?
    if (isset($rounded)) {
        foreach ($rounded as $item) {
            ?>Nifty("<?=$item?>","large transparent");<?
        }
    }
    if (isset($_SESSION['messages'])) {
        ?>
        Nifty("div.messages","large transparent");
        <?
    }
    ?>
}

*/

function show_confirm(in_text, in_delete_text, confirm_url) {
    Dialog.confirm(in_text,
                   {className: "alphacube", width:400, okLabel: in_delete_text,
                   buttonClass: "myButtonClass",
                   id: "myDialogId",
                   cancel:function(win) {;},
                   ok:function(win) {window.location=confirm_url;}
                   }
                   );
}
function showWindow2(in_title, in_text) {
    win = new Window({className: "mac_os_x", title: in_title, width:200, height:150, destroyOnClose: true, recenterAuto:false});
    win.getContent().update(in_text);
    win.showCenter();
}
</script>
<?
/* If a page set extra head before calling header.php, include the contents */
if (isset($extra_head)) {
    echo $extra_head;
}
?>
</head>
<?
if ($this_page == "login.php") {
    ?>
    <body class="login_body">
    <?
} else {
    ?>
    <body>
    <?
}
?>
<center>
<?if ($this_page != "login.php") {?>
    <?
    if (!isset($_GET['nomenu'])) {
        ?>
    <div id="header">
    <center>
    <a href="index.php"><b><?=stripslashes($config_values['site_name'])?></b></a>
    </center>
    </div>
    <div id="navigation">
    <ul>
    <?
    for ($i = 0;$i < sizeof($menu_names);$i++) {
        //$menu_link_page_name = explode("?", );
        if ($this_page == 'show_page.php') {
            $compare_to = $this_page."?".$_SERVER['QUERY_STRING'];
        } else {
            $compare_to = $this_page;
        }
        
        echo '<li ';
        if ($compare_to == $menu_links[$i]) {
            echo 'class="activelink"';
        }
        echo '><a href="'.$menu_links[$i].'" class="page_menu"><span>'.$menu_names[$i].'</span></a></li>';
    }
    //echo '<div style="display: inline-block;color: #fff"  id="date_div">('.$_SESSION['calls'].' calls)</div> <span style="display: inline-block;color: #fff"  id="job_details"></span>';
    ?>
    
    </ul>
    </div>
        <?
    }
    ?>
    <script type="text/javascript">
    function check_calls() {
        
        
    }
    function draw_date() {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();
        if (minutes < 10) {
            minutes = "0"+minutes;
        }
        if (seconds < 10) {
            seconds = "0"+seconds;
        }
        
        eval("document.all.date_div.innerHTML = '"+hours + ":" + minutes + ":"+seconds+" (<?=$_SESSION['calls']?> calls)'");
        
        //eval("document.all.date_div.innerHTML = ' (<?=$_SESSION['calls']?> calls)'");
        
        
        
        <?/*new Ajax.Request('check_hangups.php?extension=<?=$_SESSION['extension']?>',
                         {
                         method:'get',
                         onSuccess: function(transport){
                         if (transport.responseText) {
                         hangup_dialog.dialog('open');
                         }
                         }
                         });*/?>
        
        new Ajax.Request('check_calls.php?extension=<?=$_SESSION['extension']?>',
                         {
                         method:'get',
                         onSuccess: function(transport){
                         if (transport.responseText) {
                         var response = transport.responseText;
                         //alert("Success! \n\n" + response);
                         <?
                         if ($_SESSION['popup_blocker'] == "1") {
                         ?>
                         window.open("get_customer.php?nomenu=1&pop=1&phone_number="+response);
                         <?
                         } else {
                         ?>
                         window.location = "get_customer.php?pop=1&phone_number="+response;
                         <?
                         }
                         ?>
                         }
                         }
                         });
        
        
        
        /*
         $('#dialog').html('some message');
         $('#dialog').dialog({
         autoOpen: true,
         show: "blind",
         hide: "explode",
         modal: true,
         open: function(event, ui) {
         $('#dialog').delay(3000).dialog('close');
         }
         });
         */
        new Ajax.Request('check_job.php?user_id=<?=$_SESSION['user_id']?>',
                         {
                         method:'get',
                         onSuccess: function(transport){
                         if (transport.responseText) {
                         var response = transport.responseText;
                         jQuery("#job_details").text("Job: "+response);
                         //alert("Success! \n\n" + response);
                         //window.location = "get_customer.php?pop=1&phone_number="+response;
                         }
                         }
                         });
        
        
        
        
        
        
    }
    setInterval(draw_date, 1000);
    setInterval(check_calls, 1000);
    </script>
    <?}
if ($this_page == "login.php") {?>
    <div id="login_outer" style="display: table; height: 100%; #position: relative; overflow: hidden;">
    <div id="login_middle" style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle;">
    
    <?} else {?>
        <div id="content" align="center">
        
        <?}


/* If we have any error messages, display them */
if (isset($_SESSION['messages'])) {
    foreach ($_SESSION['messages'] as $index=>$message) {?>
        <div class="messages" id = "message<?=$index?>" align="center" style="display: block;padding: 0px;">
        <a href="#" onclick="hide_message('message<?=$index?>')"><div class="messages" align="right" style="width: 99%;background: #fcc;margin:0px;">Close Message&nbsp;<img src="images/cross.png" border="0">&nbsp;</div></a>
        <?=$message.""?>
        </div>
        <?}
}

unset($links);
$links = get_links($user_level, $connection, 1, $this_page_id);
$link_names = $links[0];
$link_urls = $links[1];
$link_ids = $links[2];
$link_icons = $links[3];

if (sizeof($link_names) > 0) {
    ?>
    <div class="thin_700px_box">
    
    <?
    for ($i = 0;$i<sizeof($link_names);$i++) {
        echo '<a href="'.$link_urls[$i].'">';
        if (strlen($link_icons[$i]) > 0) {
            echo '<img src="images/'.$link_icons[$i].'" border="0">&nbsp;';
        }
        
        echo $link_names[$i].'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    ?>
    </div>
    <?
}
if ($this_page != "login.php") {
    $result_agents = mysqli_query($connection, "SELECT agent_num, pin FROM agent_nums, users WHERE agent_num = users.extension and users.id=".sanitize($_SESSION['user_id'])) or die(mysqli_error($connection));
    $row_agents = mysqli_fetch_assoc($result_agents);
    ?>
    <div id="site-bottom-bar" class="fixed-position">
    <div id="site-bottom-bar-frame">
    <div id="site-bottom-bar-content">
    <?
    echo '<div style="display: inline-block;color: #999"  id="agent_div">Agent Number: <b>'.$row_agents['agent_num']."</b> Pin: <b>".$row_agents['pin'].'</b></div> <div style="display: inline-block;color: #999"  id="date_div">'.@Date("H:i:s").' ('.$_SESSION['calls'].' calls)</div> <span style="display: inline-block;color: #999"  id="job_details">Job: ';
    include "check_job.php";
    echo '</span>&nbsp;';
    ?>
    <div id="pop_call_div" style="display:none"><center>
    Phone Number to pop: <br /><br /><input type="text" id="number_to_pop" style="width: 70%;" name="number_to_pop"><br />
    
    
    
    <?
    if ($_SESSION['popup_blocker'] == "1") {
        ?>
        <input type="button" onclick="window.open('get_customer.php?pop=1&nomenu=1&phone_number='+jQuery('#number_to_pop').val());window.location=index.php;" value="Pop">
        <?
    } else {
        ?>
        <input type="button" onclick="window.location='get_customer.php?pop=1&phone_number='+jQuery('#number_to_pop').val()" value="Pop">
        <?
    }
    ?>

    
    </div>
    <div id="appointment" style="display:none" title="Pending Appointment"><center>
    </div>
    <div id="appointment2" style="display:none" title="Pending Appointment"><center>
    </div>
    <div id="hangup" title="Hungup" style="display:none"><center><br />Call Ended...</div>
    <script>
    var hangup_dialog = jQuery('#hangup').dialog({
                                                 modal: true,
                                                 autoOpen: false,
                                                 show: 'slide',
                                                 hide: 'clip',
                                                 open: function(event, ui) {
                                                 setTimeout(function(){
                                                            jQuery('#hangup').dialog('close');
                                                            }, 1500);
                                                 }
                                                 });
    var appointment_dialog = jQuery('#appointment').dialog({
                                                           autoOpen: false,
                                                           minHeight: 20,
                                                           height: 20,
                                                           dialogClass: 'noTitleStuff',
                                                           resizable: false,
                                                           position: ['left','top']
                                                           });
    </script>

    <span style="color:#00f"><a href="#" onclick="jQuery('#pop_call_div').dialog({modal: true});">Pop Up Call</a></span>
    <?/*<span style="color:#00f"><a href="#" onclick="appointment_dialog.dialog('open')">Appointment</a></span>
    <span style="color:#00f"><a href="#" onclick="if (appointment_dialog.dialog('isOpen')===true) {alert('open');} else {alert('closed')}">Appointment</a></span>*/?>
    <?
    echo '&nbsp;<span id="status_bar" style="display: inline-block;color: #f00;font-weight: bold;"></span>';
    ?>
    </div>
    </div>
    </div>
    <?
}?>