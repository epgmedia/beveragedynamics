var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
    var gads = document.createElement('script');
    gads.async = true;
    gads.type = 'text/javascript';
    var useSSL = 'https:' == document.location.protocol;
    gads.src = (useSSL ? 'https:' : 'http:') +
    '//www.googletagservices.com/tag/js/gpt.js';
    var node = document.getElementsByTagName('script')[0];
    node.parentNode.insertBefore(gads, node);
})();

googletag.cmd.push(function() {
    googletag.defineSlot('/35190362/BDX_ROS_728_Top', [[468, 60], [728, 90]], 'div-gpt-ad-1395769760364-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_728_Middle', [[468, 60], [728, 90]], 'div-gpt-ad-1395769716433-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_728_Bottom', [[468, 60], [728, 90]], 'div-gpt-ad-1395769676079-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_300_Top', [[120, 600], [160, 300], [160, 600], [200, 200], [240, 400], [250, 250], [300, 100], [300, 250], [300, 600]], 'div-gpt-ad-1395769613001-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_300_Middle', [[120, 600], [160, 300], [160, 600], [250, 250], [300, 100], [300, 250], [300, 600]], 'div-gpt-ad-1395769555120-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_300_Bottom', [[120, 240], [120, 600], [160, 300], [160, 600], [240, 400], [250, 250], [300, 100], [300, 250], [300, 600]], 'div-gpt-ad-1395769285648-0').addService(googletag.pubads());
    googletag.pubads().collapseEmptyDivs(true);
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
});