<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Video AWS</title>
<style>
html, body{
    height:100%;
}
body{
    margin:0;
    padding:0;
}
#player{
    width:100%;
    height:100vh;
}
</style>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>

</head>

<body>
<div id="player"></div>
  <script type="text/javascript" src="//cdn.jsdelivr.net/gh/clappr/clappr-level-selector-plugin@latest/dist/level-selector.min.js"></script>
  <script>
    var player = new Clappr.Player(
    {
        // source:"https://dnnzuzbuznubl.cloudfront.net/ajantaapdrops2211/live.m3u8",
        // source:"https://dnnzuzbuznubl.cloudfront.net/ajantaapdrops2211/live.m3u8",
        source:"https://dnnzuzbuznubl.cloudfront.net/testchannel/live.m3u8",
        parentId: "#player",
        plugins: [LevelSelector],
        levelSelectorConfig: {
          title: 'Quality',
          labels: {
              2: '720p', // 500kbps
              1: '480p', // 500kbps
              0: '360p', // 240kbps
          },
          labelCallback: function(playbackLevel, customLabel) {
              return customLabel;// + playbackLevel.level.height+'p'; // High 720p
          }
        },
        poster: "",
        width: "100%",
        height: "100%",
        mediacontrol: { buttons: "#00b2dd"}
    });
    
    //player.play();
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-93480057-11"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-93480057-11');
</script>

</body>
</html>