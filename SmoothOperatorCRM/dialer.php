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
$result = mysqli_query($connection, "SELECT * FROM jobs");
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
<table class="sample2">
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
            }
            //print_pre($row2);
        }
        $row['status'] = $highest;
        $row['progress'] = $row2['progress'];
        $row['busy'] = $row2['flags'];
        $row['total'] = $row2['maxcalls'];
        if ($row['total'] > 0) {
            $row['percentage_busy'] = round($row['busy']/$row['total']*100,2);
        } else {
            $row['percentage_busy'] = 0.00;
        }
    } else {
        /* Does not have a queue entry associated */
        echo "No Queue";
        $row['status'] = 0;
        $row['progress'] = 0;
        $row['busy'] = 0;
        $row['total'] = 0;
        $row['percentage_busy'] = 0.00;
    }
    if (!$header_printed) {
        $header_printed = true;
        echo "<tr>";
        foreach ($row as $field=>$value) {
            echo '<th style="background: #000;color: #fff; border: 1px solid #ccc"><center>'.ucfirst(str_replace("_"," ",$field))."</center></th>";
        }
        echo "</tr>";
    }
    echo "<tr>";
    foreach ($row as $field=>$value) {
        echo "<td>".$value."</td>";
    }
    echo "</tr>";
    //print_pre($row);
}
?>
</table>
<?t_updated and job_id = ".$row['id']);
        $temp_val = mysqli_fetch_assoc($result_num_count);        
        echo '<td>'.$temp_val['count(*)'].'</td>';
        
        $result_num_count = mysqli_query($connection, "SELECT count(*) from customers where date_sub(now(), interval 20 day) < last_updated and date_sub(now(), interval 14 day) > last_updated and job_id = ".$row[