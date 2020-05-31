<?php


namespace Models;


use Exception;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;
use Google_Client;

class Statistic
{
    public static function aboutUsers($table, $data)
    {

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/../secret.json');
        /*  SEND TO GOOGLE SHEETS */
        $client = new Google_Client;
        try{
            $client->useApplicationDefaultCredentials();
            $client->setApplicationName("Something to do with my representatives");
            $client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
            if ($client->isAccessTokenExpired()) {
                $client->refreshTokenWithAssertion();
            }

            $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
            ServiceRequestFactory::setInstance(
                new DefaultServiceRequest($accessToken)
            );
            // Get our spreadsheet
            $spreadsheet = (new SpreadsheetService)
                ->getSpreadsheetFeed()
                ->getByTitle($table);

            // Get the first worksheet (tab)
            $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
            $worksheet = $worksheets[0];


            $listFeed = $worksheet->getListFeed();
            $listFeed->insert($data);

        }catch(Exception $e){
            echo $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getCode;
        }

        /*  SEND TO GOOGLE SHEETS */
    }
}