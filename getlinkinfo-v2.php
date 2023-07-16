<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

// Get the URL from the query string
$url = $_GET['url'];

// Set the desired location
$location = 'US';

// Set the user agent header to simulate a normal device
$userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';

// Set additional headers
$headers = [
    "Accept-Language: en-US,en;q=0.9",
];

// Initialize the cURL session
$ch = curl_init();

// Set cURL options for retrieving the response headers
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 50); // Increase the maximum number of redirects
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_REFERER, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);

// Execute the cURL request to retrieve the response headers
$response = curl_exec($ch);
// echo '<pre>' , var_dump($response) , '</pre>';

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    exit;
}

// Get the content type from the response headers
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

// Close the cURL session
curl_close($ch);

// Check if the URL points to an image file based on the content type
if (strpos($contentType, 'image') !== false) {
    // Create a new cURL session for fetching the image data
    $ch = curl_init();

    // Set cURL options for fetching the image data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 50); // Increase the maximum number of redirects
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_REFERER, $url);

    // Execute the cURL request to fetch the image data
    $imageData = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        exit;
    }

    // Close the cURL session
    curl_close($ch);

    // Extract the filename from the URL
    $filename = basename($url);

    // Use Imagick to get image details
    $imagick = new Imagick();
    $imagick->readImageBlob($imageData);

    // Retrieve the image dimensions and size
    $width = $imagick->getImageWidth();
    $height = $imagick->getImageHeight();
    $resolution = $width . 'x' . $height;

    // Calculate the file size
    $fileSize = strlen($imageData);
    if ($fileSize >= 1048576) {
        $fileSize = round($fileSize / 1048576, 2) . ' MB';
    } elseif ($fileSize >= 1024) {
        $fileSize = round($fileSize / 1024, 2) . ' KB';
    } else {
        $fileSize .= ' Bytes';
    }

    // Decode the title using html_entity_decode
    $filename = html_entity_decode($filename, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Encase the image information in <linkinfo> tags
    $result = "<linkinfo>{$filename} ({$resolution}) {$fileSize}</linkinfo>";
	//$result .= '<img src="' . {$url} . '">';
	$result .= "<br>";
	$result .= '<img src="'. $url . '" style="width: 250px;">';
} else {
    // Initialize the cURL session for fetching the page content
    $ch = curl_init();

    // Set cURL options for fetching the page content
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_REFERER, $url);

    // Execute the cURL request to fetch the page content
    $response = curl_exec($ch);
	
	// Show the page that has been curl'd
	// echo '<pre>' , var_dump($response) , '</pre>';
    
	// Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        exit;
    }

    // Close the cURL session
    curl_close($ch);

// Find the title using regular expressions based on the website
if (strpos($url, 'discord.gg') !== false) {
    // Filter for Discord
    preg_match('/<meta\s+property="og:title"\s+content="([^"]+)"\s*\/?>/i', $response, $matches);
    $title = '';
    if (isset($matches[1])) {
        $title = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
} elseif (strpos($url, 'reddit.com') !== false) {
    // Filter for Reddit
    preg_match('/<shreddit-title[^>]*title="([^"]+)"[^>]*>/', $response, $matches);
    $title = '';
    if (isset($matches[1])) {
        $title = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
} elseif (strpos($url, 'aliexpress.com') !== false) {
    	// Filter for Aliexpress
    	preg_match('/"title"\s*:\s*"([^"]+)"/', $response, $matches);
    	$title = '';
    	if (isset($matches[1])) {
        	$json = json_decode($matches[1], true);
        	if (isset($json['title'])) {
            	$title = $json['title'];
        	}
    	}
	} else {
    	// Filter for other websites (with <title> or <h1> tags)
    	preg_match('/<title[^>]*>([^<]*)<\/title>|<h1[^>]*>([^<]*)<\/h1>/i', $response, $matches);
		//echo '<pre>' , var_dump($matches) , '</pre>';
    }
    // Check if the $matches array has at least one non-empty match
    if (isset($matches[1]) && strlen($matches[1]) > 0) {
        // Decode the title using html_entity_decode
        $title = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Encase the title in <linkinfo> tags
        $result = "<linkinfo>{$title}</linkinfo>";
    } elseif (isset($matches[2]) && strlen($matches[2]) > 0) {
        // Decode the title using html_entity_decode
        $title = html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Encase the title in <linkinfo> tags
        $result = "<linkinfo>{$title}</linkinfo>";
    } else {
        // Set a default title if no match was found
        $result = "No Title Found";
    }
}

// Output the result
echo '<body style="background: black;color: white; font-family: Consolas;">';
echo "<title>Website Title Grabber v2.0</title>";
echo '<prefix style="color: #8cd6d6;">Title: </prefix>';
echo $result;
echo "<br>";
echo "<br>";
echo "Brought to by Moodkiller";
echo "<br>";
echo "Ver 2.0";
echo "<br>";
echo '<img src="https://i.giphy.com/media/BOPrq7m5jYS1W/giphy.webp" style="width: 175px;">';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Output</title>
    <style>
        .debug-section {
            margin-bottom: 10px;
        }

        .debug-button {
            cursor: pointer;
            padding: 5px 10px;
            background-color: #eaeaea;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .debug-content {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
    </style>
    <script>
        function toggleDebugContent() {
            var debugContent = document.getElementById('debug-content');
            debugContent.style.display = (debugContent.style.display === 'none') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="debug-section">
        <button class="debug-button" onclick="toggleDebugContent()">Toggle Debug Output</button>
        <div id="debug-content" class="debug-content">
            <h4>preg_match Output:</h4>
            <?php
            // preg_match('/<title[^>]*>([^<]*)<\/title>|<h1[^>]*>([^<]*)<\/h1>/i', $response, $matches);
            var_dump($matches);
            ?>

            <h4>cURL Response:</h4>
            <pre><?php echo htmlspecialchars($response); ?></pre>
        </div>
    </div>

    <!-- Rest of your HTML content -->

</body>
</html>

