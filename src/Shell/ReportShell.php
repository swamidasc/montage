<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Network\Http\Client;
use Cake\Network\Email\Email;

class ReportShell extends Shell
{

        public function initialize()
    {
        parent::initialize();
        $this->loadModel('Projects');
    }

    public function main()
    {

        $todayusa = date('Y-m-d');
        $projects = $this->Projects->find('all', ['conditions' => ['group_id = 1','hide = 0']]);

        $message = "";

        foreach($projects as $project) {
                $http = new Client();
                $response = $http->get($project->endpoint, [
                'action' => "projectDailyReport",
                'tn' => $project->table_name,
            ]);

            if ($project->project_type_id == 1) {
                $proj = "images";
            } else {
                $proj = "videos";
            }

            $total = json_decode($response->body);

           if (!isset($total->total)) {
                $grandtotal = 0;
           } else {
                $grandtotal = $total->total;
           }


            $message .= $project->name.": ".$grandtotal."\n";

        }

            $subject = "Montage Activity Report for ".$todayusa." IST";
            $email = new Email();
            $email->transport('support');
            $email->from(['support@webpurify.com' => 'WebPurify Support'])->to("jon@webpurify.com")->addTo('josh@webpurify.com')->addTo('ravi@webfurther.com')->subject($subject)->send($message);

    }

}