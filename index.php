<?php
// Go to a URL like
// http://shorturl.com/short?url=https://priteshgupta.com/2016/05/sticky-css-footer/
// To generate a URL like http://shorturl.com/GwDJ
$data = file_get_contents('data.json');
$json = json_decode($data, true);
$short_url = substr($_SERVER['REQUEST_URI'], 1);

if ($json[$short_url]) {
  // Cache the redirect in the browser
  header('HTTP/1.0 301 Moved Permanently');
  header("Location: $json[$short_url]");
  header('Cache-Control: private');
  header('Vary: User-Agent, Accept-Encoding');
} else if ($_GET['q'] === '/short' && $_GET['url']) {
  // Generate the unqiue string for the short url
  function generate_key() {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_key = '';

    // For this, I am using 4 characters as the length of my short URLs
    for ($i = 0; $i < 4; $i++) {
      // 61 = length of $chars - 1
      $random_key .= $chars[rand(0, 61)];
    }

    return $random_key;
  }

  if (!in_array($_GET['url'], array_values($json))) {
    $url_key = generate_key();

    while (in_array($random, array_keys($json))) {
      $url_key = generate_key();
    }

    $json[$url_key] = $_GET['url'];
    file_put_contents('data.json', json_encode($json, true));
  } else {
    $url_key = array_search($_GET['url'], $json);
  }

  echo "<input onClick='this.select();' readonly='true' value='http://shorturl.com/$url_key' />";
} else {
  echo 'Read: google.com';
}