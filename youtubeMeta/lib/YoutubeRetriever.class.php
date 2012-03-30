<?php 
/**
* March 29th, 2011
* @author mail@brunobraga.eu 
* @copyright Bruno Braga - 2011 
* Purpose, uses CURL to access youtube and scrap data from the site, and tries to get the flv stream information
*/
class YoutubeRetriever {
    const youtubeVideoURL = 'http://www.youtube.com/watch?v=';    
    const youtubeFeedData = 'http://gdata.youtube.com/feeds/api/videos/';
    protected $youtubeVideoId;
    
    /**
    * Class construct
    * 
    */
    public function YoutubeRetriever() {
        /* lets do some sanity check for modules we require 
        *  curl and simpleXML
        */
        if (!function_exists('curl_init')) {
            die ('For this to work you must enable the cURL extension!');
        }

        if(!class_exists('SimpleXMLElement')) {
            die ('For this to work you must enable SimpleXML!');
        }        
    }
    public function getYouTubeVideoMetadataAndFlvUrl($youtubeVideoId) {
        $youtubeUrl = YoutubeRetriever::youtubeVideoURL . $youtubeVideoId;
        $youtubeHtmlData = $this->establishCurlConnectionAndRetrieveData($youtubeUrl);
        $metaData = array();
        if($flvUrl = $this->retrieveFlvUrl($youtubeHtmlData)){
            $metaData['flvUrl'] = $flvUrl;
        } 
        // lets compile our information into a return array
        $metaData = array_merge(
            $this->retrieveMetadata($youtubeVideoId), 
            $metaData
        );
        return $metaData;
    }
    
    /**
    * Establishes a Curl Connection to the given URL and returns any data
    * 
    * @param string $url
    * @return string
    */
    private function establishCurlConnectionAndRetrieveData($url) {
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_URL, $url);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        $htmlData = curl_exec($curlObj);
        curl_close($curlObj); 
        return $htmlData;      
    }

    /**
    * Parses html data, and retrieves the first flv stream url from a youtubes page data
    * 
    * @param string $url
    * @return string flv
    */    
    private function retrieveFlvUrl($data) {
        //first lets try and break up the content into an array list that we can parse    
        parse_str($data, $output);
        //check if the key that holds the stream urls info exists
        //cleanup and return a valid flv
        if(!empty($output["amp;fmt_stream_map"])){
            $flvUrlsUnparsed = $output["amp;fmt_stream_map"];
            $flvUrlsList = explode("|",$flvUrlsUnparsed);  
            if (!empty($flvUrlsList[1])){
                return $flvUrlsList[1];
            }
        }
        // if we made it this far, then request was not successful, or something odd happened
        return false;
    }

    /**
    * Retrieves the xml feed provided by gdata on youtube for a specific video id
    * parses incoming xml data and formats as an array for further use to output later
    * 
    * @param mixed $youtubeVideoId
    * @return mixed
    */
    private function retrieveMetadata($youtubeVideoId) {
        $youtubeFeedUrl = YoutubeRetriever::youtubeFeedData . $youtubeVideoId;
        $youtubeFeed = file_get_contents($youtubeFeedUrl);
        $youtubeXML = new SimpleXMLElement($youtubeFeed);
        return array(
            'title' => (string)$youtubeXML->title,
            'description' => (string)$youtubeXML->content,
        );
    }    
    
    /**
    * Validates and sets the class video url on success
    * else returns error for output
    * 
    * @param mixed $url
    */
    public function validateUrlAndSetVideoId($url) {
        $error = false;
        if (!empty($url) && strpos($url, YoutubeRetriever::youtubeVideoURL) !== 0) {
            $error = "Invalid Youtube URL, format should be in the form of: \n" . YoutubeRetriever::youtubeVideoURL . "<video_id>";
        }else{
            $videoId = $this->getVideoId($url);
            if(empty($videoId)) {
                $error = "Please supply a valid video id!\n";    
            }            
        }
        if($error === false){
            $this->setYoutubeVideoId($videoId);
        }
        return $error;
    }
    
    /**
    * Private function that returns a video id from a provided youtube url
    * 
    * @param mixed $youtubeUrl
    * @return mixed
    */
    private function getVideoId($youtubeUrl) {
        return $youtubeVideoId = str_ireplace(YoutubeRetriever::youtubeVideoURL, '', $youtubeUrl);
    }
    
    /**
    * Youtube Video Id Setter
    * 
    * @param mixed $videoId
    */
    public function setYoutubeVideoId($videoId) {
        $this->youtubeVideoId = $videoId;
    }
    
    /**
    * Youtube Video Id Getter
    * 
    */
    public function getYoutubeVideoId() {
        return $this->youtubeVideoId;
    }
}


