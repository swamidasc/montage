<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\UsersHistory;
use Cake\ORM\TableRegistry;
use Cake\Network\Http\Client;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\I18n\Time;


/**
 * Projects Controller
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 */
class ProjectsController extends AppController
{


    public $paginate = [
        'limit' => 25,
        'order' => [
            'Projects.name' => 'ASC'
        ]
    ];
    
    
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }    


    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
    
    if ($this->Auth->user('user_type_id') == 1) {
		// Das1: after successfull login if user type id 1 then it executes 
		 $projects = $this->Projects->find('all', ['contain' => ['ProjectsTypes','Clients','Groups'],'order' => ['Projects.project_type_id' => 'DESC']]);
	} elseif ($this->Auth->user('user_type_id') == 2) {
		$projects = $this->Projects->find('all', ['contain' => ['ProjectsTypes'],'conditions' => ['Projects.group_id = '.$this->Auth->user('group_id')],'order' => ['Projects.project_type_id' => 'DESC']]);   	
	} elseif ($this->Auth->user('user_type_id') > 2) {
		$projects = $this->Projects->find('all', ['contain' => ['ProjectsTypes','Users'],'order' => ['Projects.project_type_id' => 'DESC' ]]);
		
		$projects = $projects->matching('Users', function ($q) {
			return $q->where(['Users.id' => $this->Auth->user('id')]);
		});		
	}
	
		$procclient = array();

		foreach($projects as $projkey => $project) {
						
			if ($project->hide == 1) {
				continue;
			}	
			
			if ($project->vansapac == 1) {
				$this->set('vansapac', $project->id);
				if ($this->Auth->user('user_type_id') == 5) {
					$this->set('grouplang',"cz");
				}
			}
			
			if ($project->project_type_id == 3) {
				$this->set('livestream', 1);
			}
			
			
			// for vans project asia
			if ($project->multiclient == 1 && $this->Auth->user('user_type_id') == 6) {	
				$project->client_escalate = $this->Auth->user('id');
				$procclient[$project->id] = $this->Auth->user('id');
			} 
					
	        $http = new Client();
			$response = $http->get($project->endpoint, [
				'action' => 'getStats',
				'overdue' => $project->return_limit,
				'tn' => $project->table_name,
				'ce' => $project->client_escalate,
				'ut' => $this->Auth->user('user_type_id')
			]);  
									
			$response = json_decode($response->body);		
			
			
			if ($project->project_type_id != 3  && $project->project_type_id != 5) {
				$stats[$project->id]['pending'] = $response->pending;
				$stats[$project->id]['moderated'] = $response->moderated;
				$stats[$project->id]['submitted'] = $response->submitted;
				$stats[$project->id]['escalated'] = $response->escalated;
				$stats[$project->id]['overdue'] = $response->overdue;
				
				
				
				
				if ($response->timelength) {
					$stats[$project->id]['timelength'] = $response->timelength;
				} else {
					$stats[$project->id]['timelength'] = "00:00:00";
				}
				if ($response->overtime) {
					$stats[$project->id]['overtime'] = $response->overtime;
				}
				if ($response->modtotaltime) {
					$stats[$project->id]['modtotaltime'] = $response->modtotaltime;
				}
				$stats[$project->id]['longest'] = $response->longest;
				
				
				if ($project->id == 122 && $this->Auth->user('user_type_id') == 5) {
					$stats[$project->id]['pending'] = "NA";
					$stats[$project->id]['longest'] = "NA";
					$stats[$project->id]['overdue'] = "NA";
				}				
				
				
			} else {
				$stats[$project->id]['unmonitored'] = $response->unmonitored;
				$stats[$project->id]['monitored'] = $response->monitored;
				$stats[$project->id]['completed'] = $response->completed;
				$stats[$project->id]['submitted'] = $response->submitted;
				$stats[$project->id]['escalated'] = $response->escalated;
				$stats[$project->id]['overdue'] = $response->overdue;
				if ($response->timelength) {
					$stats[$project->id]['timelength'] = $response->timelength;
				} else {
					$stats[$project->id]['timelength'] = "00:00:00";
				}
				if ($response->overtime) {
					$stats[$project->id]['overtime'] = $response->overtime;
				}
				if ($response->modtotaltime) {
					$stats[$project->id]['modtotaltime'] = $response->modtotaltime;
				}
				$stats[$project->id]['longest'] = $response->longest;	
				setcookie('live_'.$project->id, $response->unmonitored);		
			}
		}	
	
		$this->set('apiURL',$project->endpoint);
		//echo 'swamidas';
		//echo $project->endpoint;
	//print_r($stats);
		// $stats will gives the result of all 
		if (isset($stats)) {
        	$this->set('stats', $stats);
        }
        
        $this->set('procclient',$procclient);
        $this->set('projects', $this->paginate($projects));
        $this->set('_serialize', ['projects']);
    }

    /**
     * View method
     *
     * @param string|null $id Project id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => ['Users','Clients','Groups', 'ProjectsTypes','Users.UsersTypes']
        ]);
        
      	// make sure user has permission to edit the project
		$usersprojectsTable = TableRegistry::get('UsersProjects');
		$userproject = $usersprojectsTable->find()->where(['user_id' => $this->Auth->user('id'),'project_id' => $id])->first();
		
		// moderator not assigned to project	
		if (($this->Auth->user('user_type_id') == 5 && !$userproject) || ($this->Auth->user('user_type_id') == 3  && !$userproject) || ($this->Auth->user('user_type_id') == 2 && $project['group_id'] != $this->Auth->user('group_id')) || ($this->Auth->user('user_type_id') == 6 && !$userproject)) {
			$this->Flash->error(__('You do not have permission to perform this action.')); 
		     return $this->redirect(['action' => 'index']);		
		}      
        
        $this->set('project', $project);
        $this->set('_serialize', ['project']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	$this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['action' => 'index']);
		 }
    	
        $project = $this->Projects->newEntity();
        
        if ($this->request->is('post')) {

//			print_r($this->request->data);
// here all new project form data will sent 


			$status = array();
        	$thestatus = array();
        
			foreach ($this->request->data as $fieldname=>$fieldvalue) {
				if (preg_match('/statname/',$fieldname)) {
					$thisstatus = preg_split('/_/',$fieldname);
					$status['name'] = $fieldvalue;
					$status['statval'] = $this->request->data["statval_".$thisstatus[1]];
					$status['color'] = $this->request->data["statcolor_".$thisstatus[1]];
					array_push($thestatus,$status);
				}
			}
			$this->request->data['labels'] = $thestatus;
        	
        //	$this->request->data['users']['_ids'] = array_merge($this->request->data['moderators']['_ids'],$this->request->data['users']['_ids']);       
   
          
            $project = $this->Projects->patchEntity($project, $this->request->data);
            
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }
            
            $userhistory = new UsersHistory;
            $userhistory->record($this->Auth->user('id'),'created new project: '.$project['name'],$ipaddress);   
            
            if ($projresult = $this->Projects->save($project)) {
                $this->Flash->success(__('Please add project managers and moderators.'));
                return $this->redirect(['action' => 'edit',$projresult->id]);
            } else {
                $this->Flash->error(__('The project could not be saved. Please, try again.'));
            }
        }

        $this->loadModel('Clients');
        $this->set(
			'clients', 
			$this->Clients->find('list'),[
			'keyField' => 'id',
			'valueField' => 'name']);

        
        $this->loadModel('Groups');
        $this->set(
			'groups', 
			$this->Groups->find('list'),[
			'keyField' => 'id',
			'valueField' => 'name']);
        

        $this->loadModel('ProjectsTypes');
        $this->set(
			'projectstypes', 
			$this->ProjectsTypes->find('list'),[
			'keyField' => 'id',
			'valueField' => 'name']);
			
        //$groupmanagers = $this->Projects->Users->find('list')->where(['user_type_id = ' => 3]);
	    //$moderators = $this->Projects->Users->find('list')->where(['user_type_id = ' => 5]);
        $this->set(compact('project'));
        $this->set('_serialize', ['project']);
        
    }

    /**
     * Edit method
     *
     * @param string|null $id Project id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $project = $this->Projects->get($id,['contain' => ['Labels' => ['sort' => ['Labels.id' => 'ASC']],'Groups','ProjectsTypes']]);


		// make sure user has permission to edit the project
		$usersprojectsTable = TableRegistry::get('UsersProjects');
		$userproject = $usersprojectsTable->find()->where(['user_id' => $this->Auth->user('id'),'project_id' => $id])->first();
		
		// moderator not assigned to project	
		if (($this->Auth->user('user_type_id') == 5 && !$userproject) || ($this->Auth->user('user_type_id') == 3  && !$userproject) || ($this->Auth->user('user_type_id') == 2 && $project['group_id'] != $this->Auth->user('group_id')) || ($this->Auth->user('user_type_id') == 6 && !$userproject) || $this->Auth->user('user_type_id') == 7) {
			$this->Flash->error(__('You do not have permission to perform this action.')); 
		     return $this->redirect(['action' => 'index']);		
		}


        
        if ($this->request->is(['patch', 'post', 'put'])) {

         	$status = array();
        	$thestatus = array();


				if (isset($this->request->data['defimages']) && $this->Auth->user('user_type_id') > 2) {
					
						$usersprojectsTable = TableRegistry::get('UsersProjects');
						$userproject = $usersprojectsTable->find()->where(['user_id' => $this->Auth->user('id'),'project_id' => $id])->first();
					
						$userproject->images = $this->request->data["defimages"];
						print_r('test data:',$userproject);
						$usersprojectsTable->save($userproject);
		
						if ($this->Auth->user('user_type_id') <= 4) {
							$project['images'] = $this->request->data['defimages'];
							$this->Projects->save($project);
						}
						
						if ($this->Auth->user('user_type_id') != 1) {
							$this->Flash->success(__('Your Settings Have Been Updated.')); 
							return $this->redirect(['action' => 'edit',$id]);
						}
				} elseif (isset($this->request->data['defimages']) && $this->Auth->user('user_type_id') == 1) {
					$project['images'] = $this->request->data['defimages'];
				}
				
				foreach ($this->request->data as $fieldname=>$fieldvalue) {


				if (preg_match('/statname/',$fieldname)) {
					$thisstatus = preg_split('/_/',$fieldname);
					
					if (isset($this->request->data["labeldefault"]) && $thisstatus[1] == $this->request->data["labeldefault"]) {
						$status['def'] = 1;	
					} else {
						$status['def'] = 0;	
					}
					
					if (isset($this->request->data["tag_".$thisstatus[1]])) {
						$status['tag'] = $this->request->data["tag_".$thisstatus[1]];
					}
					$status['name'] = $fieldvalue;
					$status['statval'] = $this->request->data["statval_".$thisstatus[1]];
					$status['color'] = $this->request->data["statcolor_".$thisstatus[1]];
					$status['sort'] = $this->request->data["sort_".$thisstatus[1]];
					$status['confirm'] = $this->request->data["confirm_".$thisstatus[1]];
					$status['pinginplace'] = $this->request->data["pinginplace_".$thisstatus[1]];
					$status['tag'] = $this->request->data["tag_".$thisstatus[1]];					
					
					
					array_push($thestatus,$status);
				}
			}
			
			if (count($thestatus) > 0) {
				$this->request->data['labels'] = $thestatus;
			}
               
          if (isset($this->request->data['users']) || isset($this->request->data['moderators']) || isset($this->request->data['clientusers'])) {	  		
			  $this->request->data['users']['_ids'] = array_merge($this->request->data['moderators']['_ids'],$this->request->data['users']['_ids']); 
			  
			  if (isset($this->request->data['clientusers']['_ids']) && $this->request->data['clientusers']['_ids'] != "") {
				  $this->request->data['users']['_ids'] = array_merge($this->request->data['users']['_ids'],$this->request->data['clientusers']['_ids']);
			  }		
			  
			  // QC for big Project 
			  if ($id == 142 || $id == 143 || $id == 155 || $id == 122 || $id == 158 || $id == 162) {
			  	$this->request->data['users']['_ids'][] = 191;
			  }
			  	  			  
			  
			  	  
		  }

		  	$project['updated'] = date("Y-m-d H:i:s");			  	
		  	$project = $this->Projects->patchEntity($project, $this->request->data());
		           
		  	// need to remove the existing labels
		  	if (count($thestatus) > 0) {
		  		$projectslabel = TableRegistry::get('Labels');
		  		$query = $projectslabel->query();
		  		$query->delete()->where(['project_id' => $project['id']])->execute(); 
		  	}
            if ($this->Projects->save($project)) {
            
              if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			  } else {
	            $ipaddress = $this->request->clientIp();
              }
            
            	$userhistory = new UsersHistory;
            	$userhistory->record($this->Auth->user('id'),'updated new project: '.$project['name'],$ipaddress);             
            
                $this->Flash->success(__('The project has been saved.'));
               // return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The project could not be saved. Please, try again.'));
            }
        }
        
          $this->loadModel('Clients');
        $this->set(
			'clients', 
			$this->Clients->find('list'),[
			'keyField' => 'id',
			'valueField' => 'name']);

        
        $this->loadModel('Groups');
        $this->set(
			'groups', 
			$this->Groups->find('list'),[
			'keyField' => 'id',
			'valueField' => 'name']);
        

        $this->loadModel('ProjectsTypes');
        $this->set(
			'projectstypes', 
			$this->ProjectsTypes->find('list'),[
			'keyField' => 'id',
			'valueField' => 'name']);


        $this->loadModel('UsersProjects');
        $this->set('usersprojects',$this->UsersProjects->find('list',['keyField' => 'user_id'])->where(['project_id = ' => $project['id']])->select(['user_id'])->toArray());
  
        $groupmanagers = $this->Projects->Users->find('list')->where(['OR' => [['user_type_id = ' => 3],['user_type_id = ' => 4]],'group_id = ' => $project['group_id']])->select(['id','email'])->toArray();
		$moderators = $this->Projects->Users->find('list')->where(['user_type_id = ' => 5,'group_id = ' => $project['group_id']])->select(['id','email'])->toArray();
        
        $clientusers = $this->Projects->Users->find('list')->where(['user_type_id = ' => 6,'OR'	 => [['client = ' => $project['client_id']],['client = ' => 0]]])->select(['id','email'])->toArray();
        
        
        if ($this->Auth->user('user_type_id') != 1) {
	     	$usersprojectsTable = TableRegistry::get('UsersProjects');
			$userproject = $usersprojectsTable->find()->where(['user_id' => $this->Auth->user('id'),'project_id' => $id])->first(); 
			$this->set('defimages',$userproject['images']);			
        }
       
        $this->set(compact('project','groupmanagers','moderators','clientusers'));
        $this->set('_serialize', ['project']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Project id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
       	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	$this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['action' => 'index']);
		 }
    
    
        $this->request->allowMethod(['post', 'delete']);
        $project = $this->Projects->get($id);
        if ($this->Projects->delete($project)) {
        
        
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }        
        
            $userhistory = new UsersHistory;
          	$userhistory->record($this->Auth->user('id'),'deleted project: '.$project['name'],$ipaddress);        
        
        
            $this->Flash->success(__('The project has been deleted.'));
        } else {
            $this->Flash->error(__('The project could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    
    /**
     * moderate method
     *
     * @param string|null $id Project id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function moderate($id = null)
    {    
		$this->set('tag_project',0);
    	$role = $this->Auth->user('user_type_id') ;
    
    	if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1]  != "escalated" && $this->Auth->user('user_type_id') == 6) {
	    	return $this->redirect("/projects");
    	}   	
    
        $project = $this->Projects->get($id, [
            'contain' => ['Users','ProjectsTypes','Labels' => ['sort' => ['Labels.sort' => 'ASC','Labels.id' => 'ASC']]]
        ]);
     

			if ($project->vansapac == 1) {
				$this->set('vansapac', $project->id);
				if ($this->Auth->user('user_type_id') == 5) {
					$this->set('grouplang',"cz");
				}
			}       

    	if (isset($this->request->pass[1]) && strlen($this->request->pass[1]) == 32 && $project->fixafter == 1) {
			// reset the image have imcheck set to current admin 
        $http = new Client();
		$response = $http->get($project->endpoint, [
			'action' => 'resetImage',
			'tn' => $project->table_name,
			'modid' => $this->Auth->user('id'),
			'imgid' => $this->request->pass[1]								
		]);   			
		
		// send admin to the moderation page
		return $this->redirect("/projects/moderate/".$id."/escalated");
    	
    	}

  		// for vans project asia
		if ($project->multiclient == 1 && $this->Auth->user('user_type_id') == 6) {
			$project->client_escalate = $this->Auth->user('id');
			$procclient[$project->id] = $this->Auth->user('id');
		} 


		if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1]  == "escalated") {
			// release any image locks this user may have
			$http = new Client();
			$response2 = $http->get($project->endpoint, [
				'action' => 'clearEscalatedLock',
				'tn' => $project->table_name,
				'ce' => $project->client_escalate
			]);     		 
		}


    	// make sure user has permission to edit the project
		$usersprojectsTable = TableRegistry::get('UsersProjects');
		$userproject = $usersprojectsTable->find()->where(['user_id' => $this->Auth->user('id'),'project_id' => $id])->first();
		
		// moderator not assigned to project	
		if (($this->Auth->user('user_type_id') == 5 && !$userproject) || ($this->Auth->user('user_type_id') == 3  && !$userproject) || ($this->Auth->user('user_type_id') == 2 && $project['group_id'] != $this->Auth->user('group_id')) || ($this->Auth->user('user_type_id') == 6 && !$userproject) || ($this->Auth->user('user_type_id') == 7 && !$userproject)) {
			$this->Flash->error(__('You do not have permission to perform this action.')); 
		     return $this->redirect(['action' => 'index']);		
		}

        
        $this->set('apiURL', $project->endpoint);
		
		foreach ($project->users as $thisuser) {
			if ($thisuser->id == $this->Auth->user('id')) {
				$defimages = $thisuser->_joinData['images'];
			}
		}
    
		if (!isset($defimages) || $project->id) { 
			$defimages = $project['images'];
		}
		
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }		

      //  $userhistory = new UsersHistory;
       // $userhistory->record($this->Auth->user('id'),'started moderating: '.$project['name'],$ipaddress);        
        
        $http = new Client();
		//echo 'testcase';
		//echo $project->endpoint;
		$responseStats = $http->get($project->endpoint, [
			'action' => 'getStats',
			'tn' => $project->table_name,
			'modid' => $this->Auth->user('id'),
			'ce' => $project->client_escalate,
			'ut' => $role
		]);      

		$statsData = json_decode($responseStats->body);

		//echo ('swamidas----> here we are getting Submitted|Moderated|Pending|Escalated|Longest Wait');
	//	echo $responseStats->body;
	


		if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1]  == "escalated") {
			$getaction = "getEscalated";
	    	$this->loadModel('Users');
	    } else {
	    
	    if ($project->project_type_id != 5) {
		 	$http = new Client();
		 	$response2 = $http->get($project->endpoint, [
				'action' => 'clearLock',
				'tn' => $project->table_name,
				'modid' => $this->Auth->user('id')
			]);	    
		}
		    $getaction = "getPending";
	    }       
	    
	    
	    if (isset($project->aim_nudity) && $project->aim_nudity == 1) {
		    $aim_nudity = 1;
	    } else {
		    $aim_nudity = 0;
	    }

		        
        $http = new Client();
        
        
        if ($_REQUEST['sleep']) {
	        sleep($_REQUEST['sleep']);
        }
        
        if ($project->project_type_id == 5 && !$_REQUEST['formultiple']) {
	        
	    } else {

			
			$response = $http->get($project->endpoint, [
			'action' => $getaction,
			'tn' => $project->table_name,
			'o' => "asc",
			'modid' => $this->Auth->user('id'),
			'l' => $defimages,
			'ce' => $project->client_escalate,
			'ut' => $role,
			'aim_nudity' => $aim_nudity										
		]);        

		$projectData = json_decode($response->body);
	//	echo 'swamidas--> here we are getting first frame images details';
		//echo $response->body;
		//print_r($projectData);
		}
		if ($project->project_type_id != 3 && $project->project_type_id != 5) { 
			// don't need the 2nd page for live streams
			$http = new Client();
			$response2 = $http->post($project->endpoint, [
				'action' => $getaction,
				'tn' => $project->table_name,
				'o' => "asc",
				'modid' => $this->Auth->user('id'),
				'l' => $defimages,
				'ce' => $project->client_escalate,
				'ut' => $role,
				'aim_nudity' => $aim_nudity	 			
			]);        
        
			$projectData2 = json_decode($response2->body);
		//	echo 'swamidas--> here we are getting second frame images details';
			//print_r($projectData2);
		//	echo $response2->body;
		}
		// first frame details looping start
		foreach ($projectData as $data) {
			
			$images[$data->id]['iurl'] = $data->iurl;
			$images[$data->id]['imgid'] = $data->imgid;	

			if ($aim_nudity	== 1) {
				$images[$data->id]['aim_nudity'] = $data->aim_nudity;	
			}				
			
			if (isset($data->status)) {
				$images[$data->id]['status'] = $data->status;
			}
			
			if (isset($data->m2date)) {
				$m2dateME = new Time($data->m2date, 'America/Los_Angeles');
				$m2dateME->setTimezone('Asia/Taipei');   
				$images[$data->id]['m2date'] = $m2dateME;
			}
			
			
			if (isset($data->memo)) {
				$images[$data->id]['memo'] = $data->memo;
			}

			if (isset($data->blink)) {
				$images[$data->id]['blink'] = $data->blink;
			}
			

			if (isset($data->thumb)) {
				$images[$data->id]['thumb'] = $data->thumb;
			}


			if (isset($data->title)) {
				$images[$data->id]['title'] = $data->title;
			}

			
			if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1]  == "escalated" && $this->Auth->user('user_type_id') != 6) {
				$moderator = $this->Users->get($data->modby);
				$images[$data->id]['mod'] = $moderator->email; 
			}
		}
		// first frame details looping end	
		if ($project->project_type_id != 3  && $project->project_type_id != 5) { 
			//print_r($projectData2);
			// second frame details looping start
			foreach ($projectData2 as $data2) {	
				$images2[$data2->id]['iurl'] = $data2->iurl;
				$images2[$data2->id]['imgid'] = $data2->imgid;	
				if ($aim_nudity	== 1) {
					$images2[$data2->id]['aim_nudity'] = $data2->aim_nudity;	
				}	

				if (isset($data2->m2date)) {
					$m2dateME2 = new Time($data2->m2date, 'America/Los_Angeles');
					$m2dateME2->setTimezone('Asia/Taipei');   
					$images2[$data2->id]['m2date'] = $m2dateME2;
				}
							
				if (isset($data2->status)) {
					$images2[$data2->id]['status'] = $data2->status;
				}	
				
			
				if (isset($data2->blink)) {
					$images2[$data2->id]['blink'] = $data2->blink;
				}				
					
				if (isset($data2->memo)) {
					$images2[$data2->id]['memo'] = $data2->memo;
				}		
				
				if (isset($data2->thumb)) {
					$images2[$data2->id]['thumb'] = $data2->thumb;
				}


				if (isset($data2->title)) {
					$images2[$data2->id]['title'] = $data2->title;
				}				
							
				if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1]  == "escalated" && $this->Auth->user('user_type_id') != 6) {
					$moderator = $this->Users->get($data2->modby);
					$images2[$data2->id]['mod'] = $moderator->email; 
				}					
			}

			// second frame details looping end	
		}
		
		foreach ($project->labels as $labkey => $labels) {
			if ($project->client_escalate && $project->client_escalate > 0) {
				if ($this->Auth->user('user_type_id') == 6 && ($labels['statval'] == -2 || $labels['statval'] == -3)) {
					unset($project->labels[$labkey]);
				} else if ($this->Auth->user('user_type_id') > 3 && $this->Auth->user('user_type_id') != 6 && $labels['statval'] == -10) {
					//unset($project->labels[$labkey]);
				}
			}
			if ($labels['def'] == 1) {
				$imgdef = $labels;
			} else if (!isset($imgdef)) {
				$imgdef['name'] = "pending";
				$imgdef['color'] = "#ffffff";
				$imgdef['statval'] = 0;
			}
			
			
			if ($labels['confirm']) {
				$confirm[$labels['statval']] = $labels['name'];
			}
			
			if ($labels['tag'] == 1) {
				$this->set('tag_project',$labels['tag']);
			} else if ($project->grid == 1) {
				$this->set('tag_project',1);
			}
			
			if (isset($imgdef)) {
				$this->set('imgdef',$imgdef);
			}
		} 		
		$project->labels = array_values($project->labels);

		$this->set('confirm',$confirm);
		$this->set('stats',$statsData);	
		
		if (isset($images)) {
    		$this->set('images',$images);
    		$this->set('page1count',count($images));
    	} else {
	    	$this->set('page1count',0);
    	}
    	if (isset($images2)) {
			$this->set('images2',$images2);
			$this->set('page2count',count($images2));
		} else {
			$this->set('page2count',0);
		}
		
		if (!isset($images) && !isset($images2)) {
		
		
		// show all the images that are locked for admins.
		if ($this->Auth->user('user_type_id') <= 3) {
		 	$http = new Client();
		 	$LockedReponse = $http->get($project->endpoint, [
				'action' => 'getLocked',
				'tn' => $project->table_name,
				'uid' => $this->Auth->user('id')
			]); 		
			
			
			$lockedarray = json_decode($LockedReponse->body);
			
			if (count($lockedarray) > 0) {
					$this->loadModel('Users');
				foreach ($lockedarray as $lockeduser) {		
					$moderator = $this->Users->get($lockeduser->modid);
					$lockedimages[$lockeduser->modid]['email'] = $moderator->email;
					$lockedimages[$lockeduser->modid]['total'] = $lockeduser->total;
				}
				$this->set('locked',$lockedimages);
			} 
		}
	
		}
		
        if (count($images) == 0 && $this->Auth->user('group_id') == 1 && $this->Auth->user('user_type_id') != 6) {
	        $quotes = TableRegistry::get('Quotes');
	        $thequote = $quotes->find()->first();
	        $this->set('quote',$thequote->thequote);
	        $this->set('quoteauthor',$thequote->author);
        }


		$this->set('role',$role);
        $this->set('project', $project);
        $this->set('_serialize', ['project']);
        $this->set('thisuser',$this->Auth->user('id'));
        $this->layout = 'moderate';
        if ($project->project_type_id == 1) {
        	// image
        	$this->render('moderate');
        } elseif ($project->project_type_id == 2) {
        	// video
          	$this->render('moderatevideo');      	
        }  elseif ($project->project_type_id == 3) {
        	// video
          	$this->render('moderatelivevideo');      	
        } elseif ($project->project_type_id == 4) {
        	// video
          	$this->render('moderatepage');      	
        }   elseif ($project->project_type_id == 5) {
       	 	// video
         	if ($_REQUEST['formultiple']) {
		 		$this->layout = 'moderateformultiple';
	          	$this->render('moderatelivevideo');
        		} else {     	 	
        			$this->layout = 'moderatelivemultiple';
				$this->render('moderatelivevideomultiple');      	
        			
        		}
        }
         
    }    
    
    
	public function NextModerate($id = null)
    {
    	$this->set('tag_project',0);
    	$role = $this->Auth->user('user_type_id');
    
    	// put in security so users can only moderate images for their project
        $project = $this->Projects->get($id, [
            'contain' => ['Users','ProjectsTypes','Labels' => ['sort' => ['Labels.sort' => 'ASC','Labels.id' => 'ASC']]]
        ]);
        
        
		if ($project->vansapac == 1) {
			$this->set('vansapac', $project->id);
			if ($this->Auth->user('user_type_id') == 5) {
				$this->set('grouplang',"cz");
			}
		}      
        
  	    if (isset($project->aim_nudity) && $project->aim_nudity == 1) {
		    $aim_nudity = 1;
	    } else {
		    $aim_nudity = 0;
	    }


  		// for vans project asia
		if ($project->multiclient == 1 && $this->Auth->user('user_type_id') == 6) {
			$project->client_escalate = $this->Auth->user('id');
			$procclient[$project->id] = $this->Auth->user('id');
		} 

	          
     	// make sure user has permission to edit the project
		$usersprojectsTable = TableRegistry::get('UsersProjects');
		$userproject = $usersprojectsTable->find()->where(['user_id' => $this->Auth->user('id'),'project_id' => $id])->first();
		
		// moderator not assigned to project	
		if (($this->Auth->user('user_type_id') == 5 && !$userproject) || ($this->Auth->user('user_type_id') == 3  && !$userproject) || ($this->Auth->user('user_type_id') == 2 && $project['group_id'] != $this->Auth->user('group_id')) || ($this->Auth->user('user_type_id') == 6 && !$userproject) || ($this->Auth->user('user_type_id') == 7 && !$userproject)) {
			$this->Flash->error(__('You do not have permission to perform this action.')); 
		     return $this->redirect(['action' => 'index']);		
		}       
        
 
 		foreach ($project->users as $thisuser) {
			if ($thisuser->id == $this->Auth->user('id')) {
				$defimages = $thisuser->_joinData['images'];
			}
		}
    
		if (!isset($defimages) || $project->id) { 
			$defimages = $project['images'];
		}  
		             

	    if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1]  == "escalated") {
	    	$getaction = "getEscalated";
	    	$this->loadModel('Users');
	    } else {
		    $getaction = "getPending";
	    }     
	    
        $http = new Client();
		$response = $http->get($project->endpoint, [
			'action' => $getaction,
			'tn' => $project->table_name,
			'o' => "asc",
			'modid' => $this->Auth->user('id'),
			'l' => $defimages,
			'ce' => $project->client_escalate,
			'ut' => $role,
			'aim_nudity' => $aim_nudity				
		]);        
        
		$projectData = json_decode($response->body);		
		foreach ($projectData as $data) {
			//if (!isset($data->status)) {
				$images[$data->id]['iurl'] = $data->iurl;
				$images[$data->id]['imgid'] = $data->imgid;		
				if ($aim_nudity	== 1) {
					$images[$data->id]['aim_nudity'] = $data->aim_nudity;	
				}	
				
				if (isset($data->m2date)) {
					$m2dateME2 = new Time($data->m2date, 'America/Los_Angeles');
					$m2dateME2->setTimezone('Asia/Taipei');   
					$images[$data->id]['m2date'] = $m2dateME2;
				}				
										
				if (isset($data->status)) {
					$images[$data->id]['status'] = $data->status;
				}
				if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1]  == "escalated" && $this->Auth->user('user_type_id') != 6) {
					$moderator = $this->Users->get($data->modby);
					$images[$data->id]['mod'] = $moderator->email; 
				}	
				if (isset($data->memo)) {
					$images[$data->id]['memo'] = $data->memo;
				}


			if (isset($data->thumb)) {
				$images[$data->id]['thumb'] = $data->thumb;
			}


			if (isset($data->title)) {
				$images[$data->id]['title'] = $data->title;
			}	
				
							
			//}
		}
		
		foreach ($project->labels as $labels) {
			if ($project->client_escalate == 1) {
				if ($this->Auth->user('user_type_id') == 6 && ($labels['statval'] == -2 || $labels['statval'] == -3)) {
					unset($project->labels[$labkey]);
				} else if ($this->Auth->user('user_type_id') > 3 && $this->Auth->user('user_type_id') != 6 && $labels['statval'] == -10) {
					unset($project->labels[$labkey]);
				}
			}
			if ($labels['def'] == 1) {
				$imgdef = $labels;
			} else if (!isset($imgdef)) {
				$imgdef['name'] = "pending";
				$imgdef['color'] = "#ffffff";
				$imgdef['statval'] = 0;
			}
			
			if ($labels['tag'] == 1) {
				$this->set('tag_project',$labels['tag']);
			} else if ($project->grid == 1) {
				$this->set('tag_project',1);
			}
						
			if (isset($imgdef)) {
				$this->set('imgdef',$imgdef);
			}
		} 
		
		$project->labels = array_values($project->labels);
				
    	$this->set('images',$images);
		$this->set('nextPagecount',count($images));
        $this->set('project', $project);
        $this->set('_serialize', ['project']);
        $this->set('role',$role);
        //$this->layout = 'moderate';
        //$this->render('next');
        
        
        if ($project->project_type_id == 1) {
        	// image
        	$this->render('next');
        } elseif ($project->project_type_id == 2) {
        	// video
          	$this->render('nextvideo');      	
        }  elseif ($project->project_type_id == 4) {
        	// video
          	$this->render('nextpage');      	
        }
        
    }   
    
    
    /**
     * review method
     *
     * @param string|null $id Project id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function review($id = null)
    {
		    
    	$theLabels = array();
    	$this->set('tag_project',0);
    	$taglabels = array();
    	$findtags = array();

    	// put in security so users can only moderate images for their project
        $project = $this->Projects->get($id, [
            'contain' => ['Users','ProjectsTypes','Labels']
        ]);
       // print_r($project);
     	// make sure user has permission to edit the project
		$usersprojectsTable = TableRegistry::get('UsersProjects');
		$userproject = $usersprojectsTable->find()->where(['user_id' => $this->Auth->user('id'),'project_id' => $id])->first();
		
		// moderator not assigned to project	
		if (($this->Auth->user('user_type_id') == 5 && !$userproject) || ($this->Auth->user('user_type_id') == 3  && !$userproject) || ($this->Auth->user('user_type_id') == 2 && $project['group_id'] != $this->Auth->user('group_id')) || ($this->Auth->user('user_type_id') == 6 && !$userproject) || ($this->Auth->user('user_type_id') == 7 && !$userproject)) {
			$this->Flash->error(__('You do not have permission to perform this action.')); 
		     return $this->redirect(['action' => 'index']);		
		}       

    	$theLabels[0] = "Pending";
    	
    	
    	if (($this->Auth->user('user_type_id') <= 4 || $this->Auth->user('user_type_id') == 7) && $project->fixafter == 1) {
	    	$this->set('allowfix',1);
    	}
 
    	
		foreach ($project->labels as $label) {
			$theLabels[$label->statval] = $label->name;		
			if ($label['tag'] == 1 || $tag_project == 1) {
				$tag_project = 1;
				$this->set('tag_project',$tag_project);
				$taglabels[$label->statval] = $label->statval;
			} 			

		}
		
		
		if ($project->project_type_id == 3) {
			$theLabels[15] = "Moderation Started";	
		}


		if ($tag_project == 1) {
			$taglabels[2] = 2;
		}	


		if ($project->client_escalate == 1) {
    		$theLabels[1000] = "Moderated by Client";
    	}		

		arsort($taglabels);

		$this->set('taglabels',$taglabels);		
		$this->set('labels',$theLabels);

		$this->loadModel('UsersProjects');
        $groupmanagers = $this->Projects->Users->find('list')->where(['user_type_id = ' => 3,'group_id = ' => $project['group_id']])->select(['id','email'])->toArray();
		$moderators = $this->Projects->Users->find('list')->where(['user_type_id = ' => 5,'group_id = ' => $project['group_id']])->select(['id','email'])->toArray();
		$qcs = $this->Projects->Users->find('list')->where(['user_type_id = ' => 7])->select(['id','email'])->toArray();
		$automod[3] = "Auto Moderated";
 

		if ($_REQUEST['search'] == 1) {	
				
			if ($_REQUEST['imgid'] == "" && $_REQUEST['vidid'] == "" && $_REQUEST['customid'] == "" && $_REQUEST['iurl'] == "" && $_REQUEST['bydate'] == 0) {
				$this->Flash->error(__('Please select search criteria.'));
				$this->set('mods',compact('groupmanagers','moderators','automod','qcs'));
				$this->set('project', $project);
				$this->render('review');	
			}
			
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }				
			
			if (isset($_REQUEST['timezone'])) {
				$timezone = $_REQUEST['timezone'];
			} else {
				$timezone = "PT";
			}
			
			$userhistory = new UsersHistory;
			$userhistory->record($this->Auth->user('id'),'Ran a report: '.$project['name'],$ipaddress);			
		
			if (isset($_REQUEST['imgid'])) {
				$imgid = trim($_REQUEST['imgid']);
			} else {
				$imgid = "";
			}
			
			if (isset($_REQUEST['vidid'])) {
				$imgid = trim($_REQUEST['vidid']);
			} else if ($imgid == "") {
				$imgid = "";
			}			


			if (isset($_REQUEST['customid'])) {
				$customid = trim($_REQUEST['customid']);
			} else if ($imgid == "") {
				$customid = "";
			}	

			

			if (isset($_REQUEST['filter_by']) && $project->project_type_id == 1) {
			
				if ($tag_project == 1 && $_REQUEST['filter_by'] > 1 && ($project['id'] == 129 && $project['id'] == 154)) { 
				
					$tagval = $_REQUEST['filter_by'];
					$findtags[] = $tagval;
					foreach ($taglabels as $tagkey => $taglabel) {
						if ($taglabel == $tagval) {
							continue;
						}
						$findtags[] = $tagkey + $tagval;
					}
					
					$thestatus = implode($findtags,",");
					 
				} else {
					$thestatus = $_REQUEST['filter_by'];
				}				
			} else if (isset($_REQUEST['filter_by']) && $project->project_type_id == 2) {
				$thestatus = $_REQUEST['filter_by'];		
			} else  {
				$thestatus = "";
			}
			
			if (isset($_REQUEST['iurl'])) {
				$iurl = trim($_REQUEST['iurl']);
			} else {
				$iurl = "";
			}
			
			
			if ($_REQUEST['modby']) {
				$modby = $_REQUEST['modby'];
			} else {
				$modby = "";
			}
			
			
			if ($_REQUEST['bydate'] == 1) {
				
					$start = $_REQUEST['startdate']['year']."-".$_REQUEST['startdate']['month']."-".$_REQUEST['startdate']['day']." ".$_REQUEST['startdate']['hour'].":".$_REQUEST['startdate']['minute'];

					$end = $_REQUEST['enddate']['year']."-".$_REQUEST['enddate']['month']."-".$_REQUEST['enddate']['day']." ".$_REQUEST['enddate']['hour'].":".$_REQUEST['enddate']['minute'];
													
			}			

			if (isset($_REQUEST['cachevalue']) && !$_REQUEST['page']) {

        		$http = new Client();
				$response = $http->get($project->endpoint, [
					'action' => "findImage",
					'tn' => $project->table_name,
					'iurl' => $iurl,
					'imgid' => $imgid,
					'startdate' => $start,
					'enddate' => $end,
					'modby' => $modby,
					'customid' => $customid,
					'status' => $thestatus,
					'timezone' => $timezone
				]);  
					
			$imageData = json_decode($response->body);	
			
			
		//	print_r($imageData);
							
			$users = TableRegistry::get('Users');
			
			if (isset($imageData)) {
				foreach ($imageData as $imkey => $imdata) {
					
					if ($imdata->modby != 0) {
					
						if (!isset($modemail[$imdata->modby])) {
							$query = $users->find('all', [
								'conditions' => ['Users.id =' => $imdata->modby]						
							]);
							$moderator = $query->first();
							if (isset($moderator->email)) {
								$modemail[$imdata->modby] = $moderator->email;
							}
						}
						if (!isset($modemail[$imdata->modby])) {
							$imageData[$imkey]->modemail = $imdata->modby . " (Moderator Not Found)";
						} else {
							$imageData[$imkey]->modemail = $modemail[$imdata->modby];
						}
					}
				}
			}
			
			
			} else {
				 $imageData = Cache::read($_REQUEST['cachevalue'],'short');
			}
			

			if (isset($_REQUEST['cachevalue'])) {
					Cache::write($_REQUEST['cachevalue'], $imageData,'short');
			}		
			$this->set('images', $imageData);	    
		} 



		unset($project['startdate']);
		unset($project['enddate']);
		
 		$this->set('timezone',$timezone);
		$this->set('mods',compact('groupmanagers','moderators','automod','qcs'));
		$this->set('project', $project);
    	$this->render('review');
	} 

     /**
     * clearAllLocks method
     *
     * @param string|null $id Project id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function clearAllLocks()
    {
    	$projects = $this->Projects->find('all', ['conditions' => ['project_type_id = 1']]);
    	
    	foreach($projects as $project) {
	        $http = new Client();
			$response = $http->get($project->endpoint, [
			'action' => "clearAllLocks",
			'tn' => $project->table_name
			]); 
		}    
    	exit;
    } 
    
     /**
     * clearAllLocksbyModID method
     *
     * @param string|null $id Project id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function clearlocksbymodid($id,$modid)
    {
   
    	   $project = $this->Projects->get($id, [
            'contain' => ['Users','ProjectsTypes','Labels']
			]);
			
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }			
			
			$userhistory = new UsersHistory;
			$userhistory->record($this->Auth->user('id'),'cleared locks: '.$project['name'],$ipaddress);			
    
		 	$http = new Client();
		 	$response2 = $http->get($project->endpoint, [
				'action' => 'clearLock',
				'tn' => $project->table_name,
				'modid' => $modid
			]);
			
			return $this->redirect(['action' => 'moderate', $project->id]);   
    }     



     /**
     * profanity method
     *
     * @param string|null $text text to chech.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function profanity($id = null)
    {    
        // put in security so users can only moderate images for their project
        $project = $this->Projects->get($id);
    
    	if (!$project->profanity_key) {
	    	$api_key = "07efa9710a009bce7e2dbf01aa2cb126";
    	} else {
	    	$api_key = $project->profanity_key;
    	}
    
		if ($id == 126) {
			$profurl = "https://vans-api.webpurify.net/services/rest/";
		} else {
    		$profurl = "http://api1.webpurify.com/services/rest/";
    	}
		 $http = new Client();
		 $response = $http->get($profurl,[
			'api_key' => $api_key,
			'method' => "webpurify.live.check",
			'format' => "json",
			'text' => $_REQUEST['text']]); 
			
		header('Content-Type: application/json');	
		echo $response->body;
		exit;
    }  
    
    
     /**
     * profanity method
     *
     * @param string|null $text text to chech.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function ocr($id = null)
    {   	   	    
	    	$OCRurl = "http://ocr-api.webpurify.com:8000/ocr/api/v1.0";
		 $http = new Client();
		 $response = $http->get($OCRurl,[
			'key' => '65c2c396bf1ad2191990d50edef3b65d',
			'url' => $_REQUEST['url']
		 ]); 	
		header('Content-Type: application/json');	
		echo $response->body;
		exit;
    }      


     /**
     * clearQueue method
     *
     * @param string|null $id Project id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function resetQueue($id = null)
    {
        // put in security so users can only moderate images for their project
        $project = $this->Projects->get($id);
 	    
 	    $http = new Client();
 		$response = $http->get($project->endpoint, [
			'action' => "resetQueue",
			'tn' => $project->table_name
		]);    	
    	return $this->redirect(['action' => 'index']);	
    } 
    
    
    public function keepSession() {
	    exit;
    }

    
}
