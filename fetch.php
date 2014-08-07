<?php
/**
 * @package		PaperRoll
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

$indexUrl = "http://lifehacker.com/tag/wallpaper-wednesday";
$scratchDir = "/Users/dennis/Pictures/Wallpaper Wednesday/";

$indexPage = doCurl($indexUrl);
preg_match("#og:description\" content=\"(.+?)\"#", $indexPage, $title);
$scratchDir .= $title[1] . "/";
if(!is_dir($scratchDir))
    mkdir($scratchDir, 0777, true);

preg_match_all("#entry-title.+?href=\"(.+?)\"#", $indexPage, $posts);
$latest = array_shift($posts[1]);

$latestPage = doCurl($latest);
preg_match_all("#transform-ku-xlarge\".+?src=\"(.+?)\"#", $latestPage, $imageMatches);
$images = array();
foreach($imageMatches[1] as $match){
    $images[] = "http://i.kinja-img.com/gawker-media/image/upload/".basename($match);
}
foreach($images as $image){
    echo "Fetching $image\n";
    file_put_contents($scratchDir . basename($image), doCurl($image));
}

exec('osascript -e "tell application \"System Events\" to set pictures folder of desktop 1 to \"'.$scratchDir.'\""');
exec('osascript -e "tell application \"System Events\" to set picture rotation of desktop 1 to true"');
exec('osascript -e "tell application \"System Events\" to set random order of desktop 1 to true"');
exec('osascript -e "tell application \"System Events\" to set change interval of desktop 1 to 3600"');

exec('osascript -e "tell application \"System Events\" to set pictures folder of desktop 2 to \"'.$scratchDir.'\""');
exec('osascript -e "tell application \"System Events\" to set picture rotation of desktop 2 to true"');
exec('osascript -e "tell application \"System Events\" to set random order of desktop 2 to true"');
exec('osascript -e "tell application \"System Events\" to set change interval of desktop 2 to 3600"');

function doCurl($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
    return curl_exec($ch);
}


?>
