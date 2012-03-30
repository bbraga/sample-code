<?php 
/**
* March 29th, 2011
* @author mail@brunobraga.eu 
* @copyright Bruno Braga - 2011 
* 
* Purpose, uses CURL to access youtube and scrap data from the site, and tries to get the flv stream information
*/

require dirname(__FILE__) . "/lib/YoutubeRetriever.class.php"; 
$youtubeRetriever = new YoutubeRetriever();

if(count($argv) != 2){
    die ('Incorrect Number of arguments supplied, please supply only Youtube URL as an argument.');
}

$youtubeUrl = $argv['1'];

// check we are getting a valid youtube URL
if($error = $youtubeRetriever->validateUrlAndSetVideoId($youtubeUrl)){
    die ($error);
}

$youtubeVideoId = $youtubeRetriever->getYoutubeVideoId($youtubeUrl);
$xmlOutput = "<?xml version=\"1.0\"?>\n"; 
$xmlOutput .= "<youtube>\n"; 

$youtubeMetadata = $youtubeRetriever->getYouTubeVideoMetadataAndFlvUrl($youtubeVideoId);
foreach($youtubeMetadata as $key => $val){
    // opening tag
    $xmlOutput .= "\t<$key>";     
    // value
    $xmlOutput .= $val;
    // closing tag
    $xmlOutput .= "\n\t</$key>\n";
}
$xmlOutput .= "</youtube>";
echo $xmlOutput;