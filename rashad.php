<?php 


function safeText($value)
{
// Define a list of allowed HTML tags and attributes
$allowedTags = '<b><i><strong><em><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><p><br><img><blockquote><code><pre><table><thead><tbody><tr><th><td><div><span>';
$allowedAttributes = ' alt class id style target';

// Use strip_tags with the allowed tags and attributes
$value = strip_tags($value, $allowedTags . $allowedAttributes);

    return $value;

    // $value = self::safe($value);
    // $value = addslashes($value);
    // $value = htmlspecialchars($value);
    // return $value;
}

echo safeText('<b>testt</b><a href="https://test.com">testing</a>');

