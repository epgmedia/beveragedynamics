<?php get_header();

$adminEmail = get_option('admin_email');
$website = get_bloginfo('url');
$websiteName = get_bloginfo('name');

if (!isset($_SERVER['HTTP_REFERER'])) {

    $caseMessage = "All is not lost!";

} elseif (isset($_SERVER['HTTP_REFERER'])) {
    // setup a message to be sent to me
    $failureMessage = "A user tried to go to\n\n" .
        $_SERVER['REQUEST_URI'] . "\n\n" .
        "And received a 404. \n\n" .
        "They came from\n\n" .
        $_SERVER['HTTP_REFERER'] .
        "\n\n";
    // email you about problem
    //mail($adminEmail, "Broken Link Notification - " . $_SERVER['REQUEST_URI'],
    //    $failureMessage, "From: $websiteName <noreply@$website>");
    // set a friendly message
    $caseMessage = "An email was sent to the administrator about this problem.";
}
?>
    <div class="row">

        <div id="content" class="content small-12 column <?php echo engine_content_position(); ?>">

            <div class="entry-content">

                <h2 class="page-title">Uh oh...</h2>
                <p>You attempted to reach</p>
                <p>
                    <samp><?php echo $website . $_SERVER['REQUEST_URI']; ?></samp>
                </p>
                <p>
                    and it doesn't exist or we just can't find it right now.
                </p>
                <h3>
                    <strong><?php echo $caseMessage; ?></strong>
                </h3>
                <p>
                    Try searching below to find what you are looking for, or click your browser's Back button and try again later.
                </p>
                <div class="entry-content-search">
                    <?php get_template_part("searchform"); ?>
                </div>

            </div>

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