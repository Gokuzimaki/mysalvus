$(document).ready(function(){
  $("#jquery_jplayer_1").jPlayer({
    ready: function () {
      var data = $.ajax({
        url: "getsong.php",
        async: false
       }).responseText;
      
        var string = data.split('|');
      $(this).jPlayer("setMedia", {
        mp3: string[0]
      }).jPlayer("play");
                
      $('#artist').html(string[1]);
      $('#songname').html(string[2]);
    },
    ended: function (event) {  
      var data = $.ajax({
        url: "getsong.php",
        async: false
       }).responseText;
      
        var string = data.split('|');
      $(this).jPlayer("setMedia", {
        mp3: string[0]
      }).jPlayer("play");
                
      $('#artist').html(string[1]);
      $('#songname').html(string[2]);
      },
    swfPath: "js",
    supplied: "mp3"
  });
});