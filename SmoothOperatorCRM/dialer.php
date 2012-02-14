<?
require "header.php";
?>
<div class="thin_700px_box">
<br />
From here you can run lists of numbers through the SmoothTorque dialer.<br />
<br />
The way this is done is by setting up rules for which numbers you would like to call<br />
<br />
<?
/*
 Dialing rules 
 */
$re$rounded[] = "div.thin_700px_box";esult = mysqli_query($coif (isset($_GET['start_campaign'])) {
    ?>
    <div id="starting" style="display: none" title="Campaign Starting">
    <center>
    <br />Please wait...starting your campaign.
    <br />
    <br />
    <div id="progress"></div>
    <br />
    <span id="start_status"></span>
    </center>
    </div>
    <script>
    jQuery("#starting").dialog({modal: true});
    </script>
    <?
    /* We now have a campaign id we'd like to use and a list id of phone numbers 
     we'd like to call.  Take the following actions:
     
     1. Connect to SmoothOperator
     2. Grab the numbers we'd like to call from the list
     3. Add a customer interraction for each number to say they are being sent 
     to SmoothTorque for dialling
     4. Connect to SmoothTorque
     5. Delete any existing numbers from that campaign
     6. Insert the new numbers
     7. Start the campaign     
     */
    
    $result = mysqli_query($connection, "SELECT distinct cleaned_number, id FROM customers WHERE list_id = ".sanitize($_GET['list_id']));
    
    <th>14-20 days</th>';
    echo '<th>21-27 days</th>';
    echo '<th>4 weeks+</th>';
    echo '<th>Run Dialer</th>';
    echo '<th>Edit Rules</th>';
    echo '</tr>';    $result_x = mysql_query("DELETE FROM SineDialer.number WHERE campaignid = ".sanitize($_GET['start_campaign']));
    
    $total = mysqli_num_rows($result);
    $i = 0;
    ?>
    <script>
    jQuery("#progress").progressbar({value: 0});
    </script>
    <?
    while ($row = mysqli_fetch_assoc($result)) {
        $result_x = mysql_query("REPLACE INTO SineDialer.number (campaignid, phonenumber, status, random_sort) VALUES (".sanitize($_GET['start_campaign']).",".sanitize($row['cleaned_number']).",'new',".sanitize(rand(0,99999999)).")") or die(mysql_error());
        $result2_x = mysqli_query($connection, "INSERT INTO SmoothOperator.interractions (contact_date_time, notes, customer_id) VALUES (NOW(), 'Sent for dialing', ".$row['id'].")");
        $i++;
        $perc = round($i/$total*100);
        //$perc = 30;
        if ($i % 30) {
            ?>
            <script>
            jQuery("#start_status").text("Moving <?=$row['cleaned_number']?> to dialer");
            
            jQuery( "#progress" ).progressbar( "option", "value", <?=$perc?> );
            </script>
            <?
            flush();
        }
        //usleep(10000);
        //sleep(1);
        
    }
    ?>
    <script>
    jQuery("#starting").dialog("close");
    </script>
    <?
    require "footer.php";
    exit(0);
}connection, "SELECT * FROM jobs");
if (mysqli_num_rows($result) == 0) {
    echo "Before you create rules to dial some jobs you will need to create jobs.";
} else {
    echo '<table class="sample">';
    echo '<tr><th>Job Name</th>';
    echo '<th>0-6 days</th>';
    echo '<th>7-13 days</th>';
    echo '<th>14-20 days</th>';
    echo '<th>21-27 days</th>';
    echo '<th>4 weeks+</th>';
    echo '<th>Run Dialer</th>';
    echo '<th>Edit Rules</th>';
    echo '</tr>';
    while ($row = mysqli_fetcid, name_assoc($result)) {
        echo '<tr>';
        echo '<td>'.$row['name'].'</td>';

        $result_num_count = mysq?>
<script>
var $the_dialog;
jQuery(document).ready(function() {
                       $the_dialog = jQuery('#start_campaign_dialog').dialog({
                                                                             autoOpen: false,
                                                                             modal: true,
                                                                             buttons: {
                                                                             Ok: function() {
                                                                             var list_id = jQuery("#lists_to_run").val();
                                                                             var campaign_id = jQuery("#campaign_id").val();
                                                                             //alert(list_id+" "+campaign_id);
                                                                             window.location = "dialer.php?start_campaign="+campaign_id+"&list_id="+list_id;
                                                                             }
                                                                             }
                                                                             });
                       });
function show_start(campaign_id, title) {
    jQuery('#start_campaign_dialog').dialog("option","title","Start "+title);
    jQuery("#campaign_id").val(campaign_id);
    $the_dialog.dialog('open');
}
</script>
<div class="thin_700px_box">
<table class="sample2" width="100%">
<tbody>
<?
$header_printed = falsesqli_query($connection, "SELECT count(*) from customers where date_sub(now(), interval 6 day) < last_updated and job_id = ".$row['id']);
        $temp_val = mysqli_fetch_assoc($result_num_count);        
        echo '<td>'.$temp_$highest = -100;
        while ($row2 = mysql_fetch_assoc($result2)) {
            /* A status of 1 means about to start, 2 means about to stop.      */
            /* Once processed it will add 100, so 101 means started, 102 means */
            /* stopped.  3 and 103 are for changing agent numbers.             */
            if ($row2['status'] >$highest && $row2['status'] != 103 && $row2['status'] != 3) {
                $highest = $row2['status'];
                $row['progress'] = $row2['progress'];
                $row['busy'] = $row2['flags'];
                $row['total'] = $row2['maxcalls'];
            }
            //print_pre($row2);
        }
        $row['status'] = $highest;
        if ($row['total'] > 0) {
            $row['percentage_busy'] = round($row['busy']/$row['total']*100,2);
        } else {
            $row['percentage_busy'] = 0.00;
        }
    } else {
        /* Does not have a queue entry associated */
        //echo "No Queue";
        $row['progress'] = 0;
        $row['busy'] = 0;
        $row['total'] = 0;
        $row['status'] = 0;
        $row['percentage_busy'] = 0.00;
    }
    
    $result_x = mysqli_query($connection, "SELECT name FROM jobs WHERE id = ".($row['id']-100000));
    $row_x =mysqli_fetch_assoc($result_x);
    $row['name'] = $row_x['name'];
    
    if (!$header_printed) {
        $header_printed = true;
        echo "<tr>";
        foreach ($row as $field=>$value) {
            if ($field == "progress") {
                echo '<th><center>Dialed</center></th>';
            } else if ($field == "id") {
            } else {
                echo '<th><center>'.ucfirst(str_replace("_"," ",$field))."</center></th>";
            }
            
        }
        echo "</tr>";
    }
    echo "<tr>";
    if ($row['status'] == 1 && $row['status'] == 101) {
        // Campaign is running
        $style=' style="background: #cfc"';
    } else {
        // Campaign is not running
        $style=' style="background: #fff"';
    }
    //$row['percentage_busy']=30;
    foreach ($row as $field=>$value) {
        if ($field == "status") {
            switch ($value) {
                case 1:
                case 101:
                    // Running
                    echo '<td '.$style.'>Running&nbsp;<a href="dialer.php?stop='.$row['id'].'"><img src="images/control_stop_blue.png" alt="Stop Campaign" border="0" valign="middle"></a></td>';
                    break;
                case 103:
                case 104:
                case 3:
                case 4:
                case -1:
                case 102:
                case 0:
                case 2:
                default:
                    // Not running
                    echo '<td '.$style.'><center><a href="#" onclick="show_start('.$row['id'].',\''.$row['name'].'\');return false;">Not Running&nbsp;<img src="images/control_play_blue.png" alt="Stop Campaign" border="0" valign="middle"></a></center></td>';
                    break;
            }
        } else if ($field == "id") {
            
        } else if ($field == "percentage_busy") {
            echo "<td $style><center>";
            ?>
            <div id="perc_busy_<?=$row['id']?>" style="height: 10px; width: 150px"></div>
            <script>
            jQuery("#perc_busy_<?=$row['id']?>").progressbar({value: <?=$value?>});
            </script>
            <?
            echo "</center></td>";
            /*} else if ($field == "progress") {
             echo "<td $style><center>";
             ?>
             <div id="progress_<?=$row['id']?>" style="height: 10px; width: 150px"></div>
             <script>
             jQuery("#progress_<?=$row['id']?>").progressbar({value: <?=$value?>});
             </script>
             <?
             echo "</center></td>";*/
        } else {
            echo "<td $style><center>".$value."</center></td>";
        }
    }
    echo "</tr>";
    //print_pre($row);
}
?>
</tbody>
</table>
</div>
<div id="start_campaign_dialog" style="display: none">
<center>
<br />
Please select a list to run:<br />
<br />
<?t_updated and job_id = ".$row['id']);
        $temp_val = mysqli_fetch_assoc($result_num_count);        
        echo '<td>'.$temp_val['count(*)'].'</td>';
        
        $result_num_count = mysqli_query($connection, "SELECT count(*) from customers where date_sub(now(), inecho '<select name="list_to_run" id="lists_to_run">';
    while ($row = mysqli_fetch_assoc($result)) {
        //        print_pre($row);
        echo '<option value="'.$row['list_id'].'">'.$row['name'].' ('.$row['count'].' numbers)</option>';
    }
    echo '</select>';
    ?>
    <input type="hidden" name="campaign_id" id="campaign_id" value="x">
    <?
}
echo "</center></div>";
require "footer.php";
?>