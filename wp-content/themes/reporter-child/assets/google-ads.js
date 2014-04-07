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
    googletag.defineSlot('/35190362/BDX_ROS_728_Top', [[468, 60], [728, 90], [970, 90]], 'div-gpt-ad-1396889327311-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_728_Middle', [[468, 60], [728, 90], [970, 90]], 'div-gpt-ad-1396892756873-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_728_Bottom', [[468, 60], [728, 90], [970, 90]], 'div-gpt-ad-1396892793150-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_300_Top', [[300, 100], [300, 250], [300, 600]], 'div-gpt-ad-1396892666018-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_300_Middle', [[300, 100], [300, 250], [300, 600]], 'div-gpt-ad-1396892582309-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_300_Bottom', [[300, 100], [300, 250], [300, 600]], 'div-gpt-ad-1396890556483-0').addService(googletag.pubads());
    googletag.defineSlot('/35190362/BDX_ROS_Tower_Top', [[120, 90], [120, 240], [120, 600], [160, 600]], 'div-gpt-ad-1396894647499-0').addService(googletag.pubads());
    googletag.pubads().collapseEmptyDivs(true);
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
});