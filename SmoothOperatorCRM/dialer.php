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
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>'.$row['name'].'</td>';

        $result_num_count = mysqli_query($connection, "SELECT count(*) from customers where date_sub(now(), interval 6 day) < last_updated and job_id = ".$row['id']);
        $temp_val = mysqli_fetch_assoc($result_num_count);        
        echo '<td>'.$temp_$highest = -100;
        while ($row2 = mysql_fetch_assoc($result2)) {
            if ($row['status'] >$highest && $row['status'] != 104 && $row['status'] != 4) {
                $highest = $row['status'];
            }
        }
        $row['status'] = $highest;
    } else {
        /* Does not have a queue entry associated */
        echo "No Queue";
        $row['status'] = 0;
    }
    print_pre($row);
}t_updated and job_id = ".$row['id']);
        $temp_val = mysqli_fetch_assoc($result_num_count);        
        echo '<td>'.$temp_val['count(*)'].'</td>';
        
        $result_num_count = mysqli_query($connection, "SELECT count(*) from customers where date_sub(now(), interval 20 day) < last_updated and date_sub(now(), interval 14 day) > last_updated and job_id = ".$row[