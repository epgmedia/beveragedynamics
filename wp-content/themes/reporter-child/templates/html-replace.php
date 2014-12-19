<?php
/*
 *
 */
/**
 * @param $replaceString
 * @param $newString
 */
function epg_sql_table_replace($replaceString, $newString) {

    echo "<h2>In these fields, <kbd>{$replaceString}</kbd> has been replaced with <kbd>{$newString}</kbd></h2>";
    $t = 0;
    while ($table = mysql_fetch_row($replaceString)) {
        echo '<table style="margin-bottom:20px;">';
        $fields_result = mysql_query("SHOW COLUMNS FROM " . $table[0]);
        if (!$fields_result) {
            echo '<tr><td colspan="2">Could not run query: ' . mysql_error() . '</td></tr>';
            exit;
        }
        $i = 0;
        if (mysql_num_rows($fields_result) > 0) {
            echo "<tr><td colspan='2'><strong>Table: {$table[0]}</strong></td></tr>";
            while ($field = mysql_fetch_assoc($fields_result)) {
                if (stripos($field['Type'], "VARCHAR") !== false || stripos($field['Type'], "TEXT") !== false) {
                    echo '<tr>';
                    echo '<td style="padding-right:10px;">' . $field['Field'] . '</td>';
                    $sql = "UPDATE " . $table[0] .
                        " SET " . $field['Field'] . " = replace(" . $field['Field'] . ", '$string_to_replace', '$newString')";
                    mysql_query($sql);
                    echo '<td>' . mysql_affected_rows() . ' records updated.</td>';
                    echo '</tr>';
                    $i = $i + mysql_affected_rows();
                }

            }
        }
        echo '<tr><td colspan="2">' . $i . ' total records updated.</td></tr>';
        echo "</table>";
        $t = $t + $i;
    }

    echo "$t changes made to the database";

}

$host               = "localhost";
$username           = "";
$password           = "";
$database           = "";
$string1            = '';
$string2            = '';
$nString1           = '';

// Connect to database server
mysql_connect($host, $username, $password);

// Select database
mysql_select_db($database);

// List all tables in database
$sql = "SHOW TABLES FROM " . $database;
$tables_result = mysql_query($sql);

if (!$tables_result) {
    echo "Database error, could not list tables\n\nMySQL error: " . mysql_error();
    exit;
}

epg_sql_table_replace($string1, $nString1);

epg_sql_table_replace($string2, $nString1);

mysql_free_result($tables_result);