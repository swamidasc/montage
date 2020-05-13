<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Network\Http\Client;

class ClearShell extends Shell
{

  	public function initialize()
    {
        parent::initialize();
        $this->loadModel('Projects');
    }

    public function main()
    {
     	$projects = $this->Projects->find('all');
    	foreach($projects as $project) {
	        $http = new Client();
			$response = $http->get($project->endpoint, [
			'action' => "clearAllLocks",
			'tn' => $project->table_name
			]); 
		}    
    }
}

