<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Network\Http\Client;
use Cake\Network\Email\Email;

class AlertShell extends Shell
{

  	public function initialize()
    {
        parent::initialize();
        $this->loadModel('Projects');
    }

    public function main()
    {
    
     	$projects = $this->Projects->find('all', ['contain' => ['Groups'],'conditions' => ['return_limit > 0','send_alerts = 1','vansapac = 0']]);
    	foreach($projects as $project) {
		    $http = new Client();
			$response = $http->get($project->endpoint, [
			'action' => "overdue",
			'tn' => $project->table_name,
			'overdue' => $project->return_limit
			]); 
		
			$overdues = json_decode($response->body);
			if (isset($overdues->overdue) && $overdues->overdue > 0) {
			
				if ($project->project_type_id == 1) {
					$proj = "images";
				} else {
					$proj = "videos";
				}
				
				$tlimit = $project->return_limit / 60;
			
				$subject = "Overage Alert: ".$project->name;
				
				$message = "There are ".$overdues->overdue." unmoderated ".$proj." in the queue over the ".$tlimit. " minute limit";
			
			
				$email = new Email();
				$email->transport('support');
				$email->from(['support@webpurify.com' => 'WebPurify Support'])->to($project->group['contactemail'])->subject($subject)->send($message);						
			}			
		}    
    }
}