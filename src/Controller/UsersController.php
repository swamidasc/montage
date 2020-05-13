<?php
namespace App\Controller;


use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Users;
use App\Model\Entity\UsersHistory;
use App\Model\Entity\Projects;
use Cake\Network\Http\Client;
use Cake\Core\Configure;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{


    public $paginate = [
        'limit' => 40,
        'order' => [
            'Users.email' => 'ASC'
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
    	//if ($this->Auth->user('user_type_id') == 5) {
	    //	  $this->Flash->error(__('You do not have permission to perform this action.')); 
		//	 return $this->redirect( ['controller' => 'Pages', 'action' => 'display', 'home']);
    	//}
    
    	if ($this->Auth->user('user_type_id') >= 2) {
  			$users = $this->Users->find('all', ['contain' => ['Groups','UsersTypes'],'conditions' => ['Users.user_type_id >= '.$this->Auth->user('user_type_id'),'Users.user_type_id != 6','Users.group_id = '.$this->Auth->user('group_id')]]);
		} elseif ($this->Auth->user('user_type_id') == 1) {
			$users = $this->Users->find('all', ['contain' => ['Groups','UsersTypes']]);
		}
        
        if (isset($this->request->params['active'])) {
        	$activeusers = array();
        	foreach ($users as $userkey => $user) {	        	
	        	if ($this->loggedIn($user->id)) {
		        	array_push($activeusers,$user->id);
	        	}
        	}
	       $this->set('active',1);
	       $this->set('activeusers',$activeusers);
        }
        
	    $this->set('users', $this->paginate($users));
        $this->set('_serialize', ['users']);		
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    
       	 if ($this->Auth->user('user_type_id') > 3) {   
    	 	$this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['action' => 'index']);
		 }
    
    
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
        
        	
        
            $user = $this->Users->patchEntity($user, $this->request->data);
            
  
            
            if ($this->Users->save($user)) {
 
             if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }
 
            
            	$userhistory = new UsersHistory;
            	$userhistory->record($this->Auth->user('id'),'created new user: '.$user['email'],$ipaddress);
            
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }


        $this->loadModel('Groups');
     
        $this->set(
			'groups', 
			$this->Groups->find('list'),[
			'keyField' => 'id',
			'valueField' => 'name']);
        
        $Userstypes = $this->loadModel('UsersTypes');
        
        if ($this->Auth->user('user_type_id') != 1) {
	    	$Userstypes = $Userstypes->find('list')->where(['id > ' => $this->Auth->user('user_type_id'),'id != ' => 6]);
	    } else {
		    $Userstypes = $Userstypes->find('list')->where(['id > ' => $this->Auth->user('user_type_id')]);
	    }
        
        $this->set('userstypes',$Userstypes);
    
     
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        
       	 if ($this->Auth->user('user_type_id') > 3 && $this->Auth->user('user_type_id') < 6 &&  $id != $this->Auth->user('id')) {   
    	 	$this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect("/projects"); 
		 }
    
        $user = $this->Users->get($id);
       
        
        if (($user['group_id'] != $this->Auth->user('group_id') || $user['user_type_id'] < $this->Auth->user('user_type_id')) && $this->Auth->user('user_type_id') != 1) {
    	 	$this->Flash->error(__('You do not have permissionto perform this action.')); 
			 return $this->redirect(['action' => 'index']);	        
        }

        // client users can't edit other users 
        if ($this->Auth->user('user_type_id') == 6 && $user['id'] != $this->Auth->user('id')) {
    	 	$this->Flash->error(__('You do not have permission to perform this action.')); 
				return $this->redirect("/projects");   	        
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
  
              if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }
            	$userhistory = new UsersHistory;
            	$userhistory->record($this->Auth->user('id'),'updated user: '.$user['email'],$ipaddress);            
                $this->Flash->success(__('The user has been saved.'));
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        
        $this->loadModel('groups');
        $this->set(
			'groups', 
			$this->groups->find('list'),[
			'keyField' => 'id',
			'valueField' => 'name']);
        
        $Userstype = $this->loadModel('UsersTypes');
        
        if ($this->Auth->user('user_type_id') != 1) {
	    	$userstypes = $Userstype->find('list')->where(['id >= ' => $this->Auth->user('user_type_id'),'id != ' => 6]);
	    } else {
		    $userstypes = $Userstype->find('list')->where(['id >= ' => $this->Auth->user('user_type_id')]);
	    }       
        
        $this->set('userstypes',$userstypes);        
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
  
        if ($this->Auth->user('user_type_id') > 3) {   
    	 	$this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['action' => 'index']);
		 }   
    
    
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        
        if (($user['group_id'] != $this->Auth->user('group_id') || $user['user_type_id'] < $this->Auth->user('user_type_id')) && $this->Auth->user('user_type_id') != 1) {
    	 	$this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['action' => 'index']);	        
        }        
        
        
        if ($this->Users->delete($user)) {
  
              if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }
  
        
            $userhistory = new UsersHistory;
			$userhistory->record($this->Auth->user('id'),'deleted user: '.$user['email'],$ipaddress);
        
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function filterGroup($id = null)
    {

		$users = $this->Users->find('all', [
			'conditions' => ['Users.group_id = '.$id]
		]);
        
	    $this->set('users', $this->paginate($users));
        $this->set('_serialize', ['users']);	
        $this->render('index');
    }
    
  	  
	public function login()
	{

		$this->request->addDetector(
			'chrome',
			['env' => 'HTTP_USER_AGENT', 'pattern' => '/Chrome/i']
		);

		$this->request->addDetector(
			'edge',
			['env' => 'HTTP_USER_AGENT', 'pattern' => '/Edge/i']
		);
		
		$this->request->addDetector(
			'msie',
			['env' => 'HTTP_USER_AGENT', 'pattern' => '/MSIE/i']
		);
		
		
		$this->request->addDetector(
			'iphone',
			['env' => 'HTTP_USER_AGENT', 'pattern' => '/iphone/i']
		);

    	if ($this->request->is('post')) {
        	$user = $this->Auth->identify();
            
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
	            $ipaddress = $this->request->clientIp();
            }     
            
    	
        	
			if ($user) {
				
				// only allow chrome
				if ($this->request->is('iphone')) {
				
				} else if(!$this->request->is('chrome') || $this->request->is('edge') || $this->request->is('msie')) {
					$this->Flash->error('Chrome Web Browser Is Required.');
					return $this->redirect("/users/login");
				}
			
				// if the user exists also add the Userstype name to the session:
				$this->loadModel('UsersTypes');
				$Userstype = $this->UsersTypes->get($user['user_type_id']);
				$user['Userstype'] = $Userstype['typename'];
				
				// moderators can only log in from the IPs
				if ($Userstype['id'] == 5) {
					$this->loadModel('Groups');
					$Group = $this->Groups->get($user['group_id'])->extract(['allowedips']);
					$Group['allowedips'] = trim(preg_replace('/\s+/','',$Group['allowedips']));
					//echo trim($Group['allowedips']);
					if ($Group['allowedips'] != "") {
						// only allow logins for these IPs
						$ips = preg_split("/,/",$Group['allowedips']);
						if (!in_array($ipaddress, $ips)) {
							$this->Flash->error('You cannot log in from your location. Please contact the Administrator.');
							$usershistory = new UsersHistory;
							$usershistory->record($user['id'],'bad login',$ipaddress);
							return $this->redirect("/users/login");
						} 
					}	
				
				}
				
            	$this->Auth->setUser($user);
             	$usershistory = new UsersHistory;         	
            	$usershistory->record($this->Auth->user('id'),'login',$ipaddress); 
            	
            	if ($_SERVER['HTTP_HOST'] == "montage.webfurther.com") {
					return $this->redirect("https://".$_SERVER['HTTP_HOST']."/projects");
				} else {
					return $this->redirect("http://".$_SERVER['HTTP_HOST']."/projects");
				}		
			}
			$this->Flash->error('Your username or password is incorrect.');
		}
		
		
		
	}  
	



	public function logout()
	{
    	$this->Flash->success('You are now logged out.');
    	
        $usershistory = new usershistory;
        
              if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }      
        
        $usershistory->record($this->Auth->user('id'),'logout',$ipaddress);  
        
        //also clear any locks they have on images.
            	
    	$this->clearImageLocks($this->Auth->user('id'));
		return $this->redirect($this->Auth->logout());
	}	
	


    /**
     * report method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function report($id)
    {
       	 if ($this->Auth->user('user_type_id') > 3) {   
    	 	$this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['action' => 'index']);
		 }

		 // if user is Administrstor then 
		 if ($this->Auth->user('user_type_id') != 1) {
			 
			 $this->loadModel('UsersProjects');

		 	$query = $this->UsersProjects->find('all', array(
		 		'conditions' => array('UsersProjects.user_id =' => $id,'UsersProjects.project_id = Projects.id'), 
		 		'contain' => ['Projects'],
		 		'fields'     => array('UsersProjects.project_id','Projects.name'),
		 		'order' => ['Projects.name' => 'ASC']
		 	));
		 	$userprojects = $query->toArray();			
		 } else {
		 	$this->loadModel('Projects');
		 	$query = $this->Projects->find('all');
		 	$userprojects = $query->toArray();	
		 }
		
	
		
		$this->set('userprojects',$userprojects);
		 
		 
		 if (isset($this->request->query['project_id'])) {
			 // generate the report.
			 
			 // 1. Get the project information\
			  $this->loadModel('Projects');
			  $project = $this->Projects->get($this->request->query['project_id']);
			 
			 
			 $project->endpoint = preg_replace('/index_(.*)\.php/','',$project->endpoint);
			 

			 // 2. Query get the stats from live
			 $http = new Client();
			 $response = $http->get($project->endpoint."reports.php", [
			 'project_id' => $this->request->query['project_id'],
			 'start' => $this->request->query['start'],
			 'end' => $this->request->query['end'],
			 'period' => $this->request->query['period'],
			 'tn' => $project->table_name,
			 'modid' => $id,
			 'action' => 'userReport'
			 ]);  			 
			 		
			 $reportData = json_decode($response->body);
			 $this->set('reportData',$reportData);
			 
		 }
    }	 
    
    
    /**
     * clearImageLocks method
     *
     * @param string|null $id user id.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function clearImageLocks($id = null)
    {
		$http = new Client();
		
		// get all the projects the user is working on.
		 $user = $this->Users->get($id,[
            'contain' => ['Projects']
        ]);
        
        if ($user->user_type_id == 1) {
	    	// get all projects
	    	$this->loadModel('Projects');
			$projects = $this->Projects->find('all');
        } else {
	        $projects = $user->projects;
        }
        
		foreach ($projects as $project) {
			$response = $http->get($project->endpoint, [
			'action' => "clearLock",
			'tn' => $project->table_name,
			'modid' => $id
			]); 
		}	

	}   
	
   /**
     * loggedIn method
     *
     * @param string|null $id user id.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function loggedIn($id = null)
    {
		 $this->loadModel('UsersHistory');
		 $query = $this->UsersHistory->find('all', [
		 	'conditions' => ['user_id =' => $id],
		    'order' => ['created' => 'DESC']
		 ]);
		 $row = $query->first();
		 if (!isset($row->description) || $row->description == "logout" || $row->description == "") {
			 return false;
		 } 
		return true;
	}   
	
	
   /**
     * sessionExpire method
     *
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function sessionExpire()
    {		
		$connection = ConnectionManager::get('default');
		$thesessions = $connection->execute('select CONVERT(data USING utf8) as sess_data, expires from sessions')->fetchAll('assoc');	
		foreach ($thesessions as $thesession) {
			//	print_r($thesession['sess_data']);
				//print_r(json_decode($thesession['sess_data']));
				//exit;
		}		
		exit;
	}  	

	
	
	
        
}
