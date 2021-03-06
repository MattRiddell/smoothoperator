<?
require "header.php";
function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}
if (!isset($_POST['jobid'])) {
    echo "<br />";
    box_start(500);
    ?><center>
    <h3>Search for contact rate</h3>
        <form action = "report_contact_rate.php" method="post">
        Job Name: <select name="jobid">
        <?
        $result = mysqli_query($connection, "SELECT id, name FROM jobs");
    while ($row = mysqli_fetch_assoc($result)) {
        print_pre($row);
        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    ?>
    </select><br />
    Time Period:<select name="range" id="range">
    <option value="alltime">All Time</option>
    <?/*<option value="today">Today</option>
    <option value="date">Select Date</option>*/?>
    </select><br />
    <div id="dateselect" style="display:none">
    <p>From Date:
    <input type="text" id="from_date" name="from_date" style="width: 200px">
    <br />
    To Date:
    <input type="text" id="to_date"  name="to_date" style="width: 200px">
    <br />
    
    </div>
    <input type="submit" value="Display Results"><br />
    <br />
    </form>
    <script>
    jQuery(function() {
           jQuery( "#from_date" ).datepicker({
                                             dateFormat : 'yy-mm-dd'
                                             });
           jQuery( "#to_date" ).datepicker({
                                           dateFormat : 'yy-mm-dd'
                                           });
           
           });
    jQuery(function() {    // Makes sure the code contained doesn't run until
           //     all the DOM elements have loaded
           
           jQuery('#range').change(function(){
                                   //alert("x");
                                   //alert($('#range').val());
                                   if (jQuery('#range').val() == "date") {
                                   jQuery('#dateselect').show();
                                   } else {
                                   jQuery('#dateselect').hide();
                                   
                                   }
                                   
                                   });
           });
    </script>
    
    <?
    box_end();
    require "footer.php";
    exit(0);
}
$result = mysqli_query($connection, "SELECT name FROM jobs WHERE id = ".sanitize($_POST['jobid']));
$name = mysqli_result($result,0,0);

/* This is a two step process. Step one 
 *
 */


$sql = "select * from campaign_stats where campaign_id = ".(100000+$_POST['jobid'])." and report_date = curdate() order by report_time desc limit 1";
$result = mysqli_query($connection, $sql);
$total = 0;
$answered = 0;
while ($row = mysqli_fetch_assoc($result)) {
    //print_pre($row);
    $total += $row['new'];
    $total += $row['answered'];
    $total += $row['busy'];
    $total += $row['congested'];
    $total += $row['amd'];
    $total += $row['unknown'];
    $total += $row['pressed1'];
    $total += $row['hungup'];
    $total += $row['timeout'];
    
    $answered += $row['answered'];
    $answered += $row['amd'];
    $answered += $row['pressed1'];
    foreach ($row as $field=>$value) {
        if ($field != "report_date" && $field != "report_time" && $field != "campaign_id"&& $field != "answered"&& $field != "pressed1"&& $field != "amd"&& $field != "pressed1") {
            $string .= ucwords($field).": ".$value.", ";
        }
    }
    $string = substr($string,0,strlen($string)-2);
}
//echo $answered."/".$total."<br />";

$sql = "select count(*), text, not_contacted from customer_dispositions, job_dispositions where customer_dispositions.job_id = ".sanitize($_POST['jobid'])." and customer_dispositions.disposition = job_dispositions.id group by disposition";
$result = mysqli_query($connection, $sql);
$total_disp = 0;
$not_answered_disp = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $total_disp += $row['count(*)'];
    if ($row['not_contacted'] == 1) {
        $not_answered_disp += $row['count(*)'];
    }
    //print_pre($row);
}
//echo $not_answered_disp;

//exit(0);

switch ($_POST['range']) {
    case "today":
        $sql = "select count(*), text from customer_dispositions, job_dispositions where customer_dispositions.job_id = ".sanitize($_POST['jobid'])." and customer_dispositions.disposition = job_dispositions.id and date(contact_date_time) = curdate() group by disposition";
        $title = 'Dispositions for '.ucwords($name).' Today';
        break;
    case "date":
        $sql = "select count(*), text from customer_dispositions, job_dispositions where customer_dispositions.job_id = ".sanitize($_POST['jobid'])." and customer_dispositions.disposition = job_dispositions.id and date(contact_date_time) between ".sanitize($_POST['from_date'])." and ".sanitize($_POST['to_date'])." group by disposition";
        $title = 'Dispositions for '.ucwords($name).' Between '.$_POST['from_date'].' and '.$_POST['to_date'];
        break;
    default:
        $sql = "select count(*), text from customer_dispositions, job_dispositions where customer_dispositions.job_id = ".sanitize($_POST['jobid'])." and customer_dispositions.disposition = job_dispositions.id group by disposition";
        $title = 'Dispositions for '.ucwords($name).' All Time';
        break;
}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages: ["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
    
    var data = new google.visualization.DataTable();
    
    
    var data = google.visualization.arrayToDataTable([
                                                     ['Disposition', 'Count'],
                                                     <?
                                                     //$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                                                     //while ($row = mysqli_fetch_assoc($result)) {
                                                     echo "['Contacted', ".($answered-$not_answered_disp)."],\n";
                                                     echo "['Dispositioned Not Contacted', ".($not_answered_disp)."],\n";
                                                     echo "['Dialer Not Contacted', ".($total-$answered)."],\n";
                                                     //}
                                                     ?>
                                                     
                                                     ]);
    
    
    
    
    var options = {
    title: '<?=$title?>'
    };
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    
    
    
    
    // The select handler. Call the chart's getSelection() method
    function selectHandler() {
        var sel=chart.getSelection(); 
//        alert("Rowselected:"+sel[0].row);
        if (sel[0].row == 0) {
            //alert("Contacted");
        } else if (sel[0].row == 1) {
            //alert("Disposition Not Contacted");
        } else if (sel[0].row == 2) {
            alert("<?=$string?>");
        }

    }
    
    // Listen for the 'select' event, and call my function selectHandler() when
    // the user selects something on the chart.
    google.visualization.events.addListener(chart, 'select', selectHandler);

    
    
    
    
    chart.draw(data, options);
}
</script>
<div id="chart_div" style="width: 900px; height: 500px;"></div>
<?
require "footer.php";
?>