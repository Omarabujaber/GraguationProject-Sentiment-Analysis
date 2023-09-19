<?php
function fetchBackgroundImage($query) {
    $apiKey = 'wibvoZWV7QmYpNLfMWJbr4s0WG_0v4od8bkDdnFq7Rs';  
    $query = urlencode($query);
    
    
    $url = "https://api.unsplash.com/search/photos?query=$query&client_id=$apiKey&per_page=1&orientation=landscape";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (isset($data['results'][0]['urls']['full'])) {
        return $data['results'][0]['urls']['full'];
    } else {
        return 'default_background.jpg';  
    }
}
?>
