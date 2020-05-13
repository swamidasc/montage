<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Network\Http\Client;

class QuoteShell extends Shell
{

        public function initialize()
    {
        parent::initialize();
        $this->loadModel('Quotes');
    }

    public function main()
    {
        $http = new Client();
        $response = $http->get('http://quotes.rest/quote/random.json', [ 'api_key' => 'rcWpK4jJ2_pkfS_5ApkK_AeF' ]);

        $quote = json_decode($response->body());

        if ($quote->contents->quote) {
            $quote_patched['thequote'] = $quote->contents->quote;
            $quote_patched['author'] = $quote->contents->author;
            $this->Quotes->deleteAll(['id != ' => 0]);
            $newquote = $this->Quotes->newEntity();
            $newquote = $this->Quotes->patchEntity($newquote, $quote_patched);
            $this->Quotes->save($newquote);
        } else {
            exit;
        }
    }
}