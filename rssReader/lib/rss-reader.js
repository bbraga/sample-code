/**
* March 29th, 2011
* @author mail@brunobraga.eu 
* @copyright Bruno Braga - 2011 
*/
rssReader = {
    init : function () {
        $('#rss_button').click(function(){
            rssReader.load($('#feed_url').val());
        });    
    },
    load : function (feedUrl){
        $('#feed').rssfeed(feedUrl, {
            limit: 5
        });
    }
};

$(document).ready(function() {
    rssReader.init();
});