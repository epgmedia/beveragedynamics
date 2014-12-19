<?php

/*
 *
 */
header('Content-type: text/html; charset=utf-8');

echo "Beginning Conversion Process. <br />";

/**
 * Class wpToOxcyon
 */
class wpToOxcyon {
    /**
     * PDO
     * @param $dbh
     */
    protected $dbh;

    function __construct($dbh) {
        $this->dbh = $dbh;
    }
    /**
     * Get Data from database
     *
     * @param $sql
     * @param $value
     * @return array
     */
    public function getData( $sql, $value=null ) {
        // create array for search value
        // establish database connection
        $pdo = $this->dbh;
        // prepare database call
        $pdoObject  = $pdo->prepare( $sql );
        // check for errors
        if (!$pdoObject) {
            echo "\nPDO::errorInfo():\n";
            print_r($pdo->errorInfo());
        }
        // execute the database call
        $pdoObject->execute( $value );
        // return row data
        return $pdoObject->fetchAll( PDO::FETCH_ASSOC );
    }
}
function strip_html_tags( $text )
{
    $text = preg_replace(
        [
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<title[^>]*?>.*?</title>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            "/class\s*=\s*'[^\']*[^\']*'/"
        ],
        array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),
        $text );
    return $text;
}
// Include for DB connection?
include("init.inc.php");
// Establish new PDO
try {
    //$bevData = new PDO("mysql:host=127.0.0.1;dbname=beverage_data", 'Dev_User', 'Welcome2013');
    $bevData = new PDO(
        "mysql:host=localhost;dbname=beverage_data", 'test_user', 'Welcome2013');
} catch ( Exception $e ) {
    echo 'Connection failed: ' . $e->getMessage();
}
// New class instance
$db = new wpToOxcyon( $bevData );
$sql = 'SELECT ID, post_content, post_excerpt
    FROM wp_posts';
$data = $db->getData( $sql );
$tidy = new tidy;
$config = array(
    "bare"              => true,
    "clean"             => true,
    "DocType"           => "omit",
    "drop-font-tags"    => true,
    "drop-proprietary-attributes" => true,
    "merge-spans" => true,
    "word-2000" => true,
);
$find[] = '<span>';     // No Spans
$find[] = '</span>';    // No Spans
$find[] = '<html>';    // No HTML
$find[] = '</html>';    // No HTML
$find[] = '<body>';    // No Body
$find[] = '</body>';    // No Body
$find[] = "\n";         // Get rid of newlines for wordpress
$find[] = '®';          // Registered (remove working)
$find[] = 'Ã¢â‚¬Å“';    // left side double smart quote
$find[] = 'Ã¢â‚¬Â';   // right side double smart quote
$find[] = 'Ã¢â‚¬Ëœ';    // left side single smart quote
$find[] = 'Ã¢â‚¬â„¢';   // right side single smart quote
$find[] = 'â';          // single quote
$find[] = 'Ã¢â‚¬Â¦';    // elipsis
$find[] = 'Ã¢â‚¬â€';  // em dash
$find[] = 'Ã¢â‚¬â€œ';   // en dash
$find[] = 'Â';          // register
$find[] = 'â¢';       // tm

$replace[] = " "; // Span open
$replace[] = " "; // Span Close
$replace[] = " "; // html open
$replace[] = " "; // html Close
$replace[] = " "; // Body open
$replace[] = " "; // Body Close
$replace[] = " "; // newlines
$replace[] = '';  // Remove working (Reg)
$replace[] = '"';
$replace[] = '"';
$replace[] = "'";
$replace[] = "'";
$replace[] = "'"; // single quote
$replace[] = "...";
$replace[] = "-";
$replace[] = "-";
$replace[] = '®';
$replace[] = '™'; // tm

$i = 0;

foreach ($data as $row) {
    $id = $row["ID"];
    $oldData = $row["post_excerpt"];
    echo $oldData . "<br/>";
    $tidyData = $tidy->repairString( $oldData, $config, 'UTF8');
    $weirdData = str_replace($find, $replace, $tidyData);
    $newData =  strip_html_tags( $weirdData );
    $insert = array(
        ":post"     => $newData,
        ":postID"   => $id
    );
    $updateSQL = "UPDATE wp_posts SET post_excerpt = :post WHERE ID = :postID";
    //$db->getData($updateSQL, $insert);
    //$update = $db->getData($updateSQL, $insert);
    if ($update != 0) {
        $i++;
    }
}
echo "$i rows updated <br />";

echo "Conversion Complete.";