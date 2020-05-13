<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Network\Http\Client;
use Cake\Network\Email\Email;

class VansShell extends Shell
{

  	public function initialize()
    {
        parent::initialize();
        $this->loadModel('Projects');
        $this->loadModel('Users');
    }

    public function main()
    {
    
     	$projects = $this->Projects->find('all', ['contain' => ['Users'],'conditions' => ['vansapac = 1']]);
     	
    	foreach($projects as $project) {
	    	
	    		if (strpos($project->table_name, 'dev') !== false) {
		    		continue;
		    	}
	        	
		    $http = new Client();
			$response = $http->get($project->endpoint, [
			'action' => "getStats",
			'tn' => $project->table_name
			]); 
		
			$stats = json_decode($response->body);
			if (isset($stats->pending) && $stats->pending > 0) {
				$subject = $stats->pending." shoes awaiting moderation";
				
				$message = "There are ".$stats->pending." shoes awaiting moderation<br>";
				$message .= "The longest wait is: ".$stats->longest."<br>";
				$message .= "<a href='https://montage.webfurther.com'>http://montage.webfurther.com</a>";
				
				foreach ($project->users as $moderator) {
					$email = new Email();
					$email->emailFormat('html');
					$email->transport('support');
					
					if ($moderator->email == "vanschinalegal@webpurify.com" || $moderator->email == "vanslegal1@webpurify.com" || $moderator->email == "vansecom1@webpurify.com" || $moderator->email == "vansecommanager@webpurify.com") {
						continue;
					}
					
					
					$email->from(['support@webpurify.com' => 'WebPurify Support'])->to($moderator->email)->subject($subject)->send($message);	
				}					
			}			
		}    
    }
}