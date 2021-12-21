<?php
define("GOOGLE_APPLICATION_CREDENTIALS", './google/TranslationbyCom-63a4cd822907.json');
// also added to google/auth/src/CredentialsLoader.php line 69
myRequireOnce ('vendor/autoload.php');

use Google\Cloud\Translate\V3\TranslationServiceClient;
// from https://github.com/googleapis/google-cloud-php/tree/master/Translate
$translationClient = new TranslationServiceClient();
$content[] = rawurldecode($_GET['text']);
$targetLanguage = $_GET['target'];
$response = $translationClient->translateText(
    $content,
    $targetLanguage,
    TranslationServiceClient::locationName('translation-by-com', 'global')
);
// only expecting one.
foreach ($response->getTranslations() as $key => $translation) {
    $text= $translation->getTranslatedText();
    // get rid of opening and closing quotes
    $text = substr($text, 1);
    $output = substr($text, -1);
}
//
// write log
//
$debug = "\n\nHERE IS JSON_ENCODE OF DATA THAT IS NOT ESCAPED\n";
$debug .= json_encode($output, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . "\n";
$fh = fopen('logs/Translate.txt', 'w');
fwrite($fh, $debug);
fclose($fh);

// return response
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
echo json_encode($output,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
die();
