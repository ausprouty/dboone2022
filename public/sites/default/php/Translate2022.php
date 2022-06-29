<?php
function translateGoogle(){
    use Google\Cloud\Translate\V2\TranslateClient;
    $translationServiceClient = new TranslationServiceClient();
    try {
        $contents = [];
        $targetLanguageCode = '';
        $formattedParent = $translationServiceClient->locationName('[PROJECT]', '[LOCATION]');
        $response = $translationServiceClient->translateText($contents, $targetLanguageCode, $formattedParent);
    } finally {
        $translationServiceClient->close();
    }
    writeLogDebug('translateGoogle-13', $response)
}