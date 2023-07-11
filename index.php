<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Song Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>

    <div class="container my-5">
        <h1 class="text-center">Song Details/Lyrics Extraction</h1>
        <div class="mb-3">
            <form action="" method="get">
                <label class="form-label">Enter Song Name</label>
                <input type="text" class="form-control" name="song" placeholder="Song Name"><br>
                <input type="submit" class="btn btn-warning" value="Submit" name="submit"><br>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>

<!-- === === === === === === === === === PHP CODE === === === === === === === === === === === === ===  -->

<?php
try {
    if (isset($_GET['song'])) {
        // Get the song name from the URL
        $song = $_GET['song'];

        // Replace spaces in the song name with %20
        $song = str_replace(' ', '%20', $song);

        // Make a request to the Genius API
        $url = "https://api.genius.com/search?q=$song";
        $access_token = "YOUR_ACCESS_TOKEN"; // Replace with your own Genius API access token

        $options = [
            'http' => [
                'header' => "Authorization: Bearer $access_token",
                'method' => 'GET'
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        // Parse the response to get the song ID
        $result = json_decode($response, true);
        $song_id = $result['response']['hits'][0]['result']['id'];
        $song_name = $result['response']['hits'][0]['result']['title'];
        $artist_name = $result['response']['hits'][0]['result']['artist_names'];
        $full_title = $result['response']['hits'][0]['result']['full_title'];
        $release_date = $result['response']['hits'][0]['result']['release_date_for_display'];
        $lyrics_url = $result['response']['hits'][0]['result']['url'];


        // Display the song details
        echo "
        <hr>
        <div class='container my-5'>
        <table class='table'>
        <thead>
        <h1 class='text-center'>Song's Details</h1>
        <tr>
            <th scope='col'>Song ID</th>
            <th scope='col'>Song Name</th>
            <th scope='col'>Artist Name</th>
            <th scope='col'>Full Title</th>
            <th scope='col'>Release Date</th>
            <th scope='col'>Lyrics URL</th>
        </tr>
     
        </thead>
        <tbody>
          <tr>
            <th scope='row'>$song_id </th>
            <td>$song_name</td>
            <td>$artist_name</td>
            <td>$full_title</td>
            <td>$release_date</td>
            <td>$lyrics_url</td>
          </tr>
        </tbody>
      </table>
      </div>";

        // Generate the updated code
        $embed_code = "<div id='rg_embed_link_$song_id' class='rg_embed_link' data-song-id='$song_id'>";
        $embed_code .= "Read <a href='https://genius.com/$song-lyrics'>{$song}</a> on Genius</div>";
        $embed_code .= "<script crossorigin src='//genius.com/songs/$song_id/embed.js'></script>";


        echo "<div class='container my-5'>
        <hr>
        <h1>Song Lyrics</h1>
        $embed_code
        </div>";
    }
}

//catch exception
catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}
?>
