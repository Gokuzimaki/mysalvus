$(document).ready(function () {
// http://feeds.reuters.com/reuters/oddlyEnoughNews - reuters news
// http://newsrss.bbc.co.uk/rss/newsonline_world_edition/front_page/rss.xml - BBC world news
// http://muyiwaafolabi.com/feeds/rss/franklyspeakingwithmuyiwaafolabi.xml - Muyiwas Blog
  $('div[appdata-name=bbcnewshold][appdata-assist=none]').rssfeed('http://newsrss.bbc.co.uk/rss/newsonline_world_edition/front_page/rss.xml', {
    limit: 3
  });
  $('div[appdata-name=bbcnewsradio][appdata-assist=none]').rssfeed('http://newsrss.bbc.co.uk/rss/newsonline_world_edition/front_page/rss.xml', {
    limit: 3
  });
  $('div[appdata-name=bbcnewshold][appdata-assist=ticker]').rssfeed('http://newsrss.bbc.co.uk/rss/newsonline_world_edition/front_page/rss.xml',{
    snippets: true,
    animation: "fade"
  }, function(e) {
		$(e).find('div.rssBody').vTicker({
			showItems: 1,
      speed:700,
      pause:10000
		});
	});
  $('div[appdata-name=marketwatchnewshold][appdata-assist=ticker]').rssfeed('http://www.marketwatch.com/rss/marketpulse?format=xml',{
    snippets: true,
    animation: "fade"
  }, function(e) {
    $(e).find('div.rssBody').vTicker({
      showItems: 1,
      speed:700,
      pause:10000
    });
  });
// http://www.marketwatch.com/rss/marketpulse
  $('div[appdata-name=reutersnewshold]').rssfeed('http://feeds.reuters.com/reuters/businessNews',{
    	snippets: true
  	}, function(e) {
		$(e).find('div.rssBody').vTicker({
			showItems: 1
		});
  	});
});