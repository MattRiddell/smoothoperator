<?
/* The system configuration works like this.  You have a table in MySQL called */
/* config.  This table has a primary key of parameter and a field called value */
/* There is also another table which contains the strings to describe these    */
/* config values.  This table is called static_text.  It has three fields, the */
/* language (i.e. en_gb etc), the config parameter it is describing and        */
/* the text that you would like to use to describe it.                         */

/* This means that if you need to add a configurable parameter to the site you */
/* can just add it to the database as well as a description for it and it will */
/* be available for usage.                                                     */

require "header.php";

if (isset($_GET['save'])) {
    foreach ($_POST as $field=>$value) {
        $sql = "UPDATE config set value = ".sanitize($value)." WHERE parameter = ".sanitize($field);
        //echo $sql;
        $result = mysql_query($sql);
        $config_values[$field] = $value;
        if (!$result) {
            $messages[] = "There was an error saving $field: $value to MySQL: ".mysql_error();
        }
    }
    $_SESSION['messages'] = $messages;
    $_SESSION['config_values'] = $config_values;
    redirect("config.php",0);
    require "footer.php";
    exit(0);
}
$sql = "SELECT * FROM config, static_text WHERE config.parameter = static_text.parameter and static_text.language = ".sanitize($_SESSION['language']);
$result = mysql_query($sql) or die(mysql_error());

echo '<form action="config.php?save=1" method="post">';
while ($row = mysql_fetch_assoc($result)) {
    echo $row['description'].': <input type="text" name="'.$row['parameter'].'" value="'.$config_values[$row['parameter']].'"><br />';
}
echo '<input type="submit" value = "Save Changes">';
echo '</form>';

require "footer.php";
?>
