<?php get_header(); ?>
<?php
#some variables for the script to use
#if you have some reason to change these, do.  but wordpress can handle it

#the administrator email address, according to wordpress
$adminEmail = get_option('admin_email');
#gets your blog's url from wordpress
$website = get_bloginfo('url');
#sets the blog's name, according to wordpress
$websiteName = get_bloginfo('name');

if (!isset($_SERVER['HTTP_REFERER'])) {
    #politely blames the user for all the problems they caused

    #starts assembling an output paragraph
    $returnMessage = "tried going to ";
    $caseMessage = "All is not lost!";
} elseif (isset($_SERVER['HTTP_REFERER'])) {
    #this will help the user find what they want, and email me of a bad link

    #now the message says You clicked a link to...
    $returnMessage = "clicked a link to";
    #setup a message to be sent to me
    $failureMessage = "A user tried to go to $website"
        .$_SERVER['REQUEST_URI']." and received a 404 (page not found) error.";
    $failureMessage .= "It wasn't their fault, so try fixing it.";
    $failureMessage .= "They came from ".$_SERVER['HTTP_REFERER'];
    #email you about problem
    mail($adminEmail, "Bad Link To ".$_SERVER['REQUEST_URI'],
        $failureMessage, "From: $websiteName <noreply@$website>");
    #set a friendly message
    $caseMessage = "An email was sent to the administrator about this problem.";
}
?>
    <div class="row">

        <div id="content" class="content small-12 column <?php echo engine_content_position(); ?>">
            <h2 class="page-title">Uh oh...</h2>
            <p>You <?php echo $returnMessage; ?> <samp><?php echo $website.$_SERVER['REQUEST_URI']; ?></samp></p>
            <p>
                and it doesn't exist or we just can't find it right now.
            </p>
            <h3><strong><?php echo $caseMessage; ?></strong></h3>
            <p>
                You can click back and try again or try searching below for what you are looking for:
            </p>
            <?php include(TEMPLATEPATH . "/searchform.php"); ?>


        </div>
        <!-- /.content small-12 large-8 column -->

        <?php if( engine_content_position() != 'large-12' ) : ?>
            <div class="sidebar small-12 large-4 column" id="sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>

    </div>
    <!-- /.row -->

<?php get_footer(); ?>