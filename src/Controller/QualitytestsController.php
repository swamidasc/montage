<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\UsersHistory;
use Cake\ORM\TableRegistry;
use Cake\Network\Http\Client;
use Cake\Cache\Cache;
use Cake\Core\Configure;

require '../vendor/aws/aws-autoloader.php';
	    
use Aws\S3\S3Client;
	    
/**
 * Projects Qualitytests
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 */
class QualitytestsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
	{
		
		if ($this->Auth->user('user_type_id') > 3) {
			return $this->redirect("/projects");	
		}		
		
          $this->loadModel('Projects');
          $this->set('projects',$this->Projects->find('list',[ 'keyField' => 'id', 'valueField' => 'name'])->where(['group_id = ' => 1])->toArray());

          
          if (isset($this->request->data['projectid'])) {
	          // get the moderators assgined to the project

			  $this->set('tn',$this->Projects->find('list',[ 'keyField' => 'id', 'valueField' => 'table_name'])->where(['id = ' => $this->request->data['projectid']])->toArray());

			  			 $project = $this->Projects->get($this->request->data['projectid']);

			  			 if ($project->project_type_id == 2) {
				  			 $this->set('ptype',"video");
			  			 } else {
				  			 $this->set('ptype',"image");
			  			 }

                  $this->loadModel('UsersProjects');

                                   
                  $mods = $this->UsersProjects->find('list',[ 'keyField' => 'Users.id', 'valueField' => 'Users.email'])->where(['project_id = ' => $this->request->data['projectid'],'Users.user_type_id = ' => 5])->select(['Users.id','Users.email','Users.user_type_id'])->contain(['Users'])->toArray();
                  
                  
                  $mods[0] = "Next Available Moderator";
                  $this->set('usersprojects',$mods);

			  
	          $this->loadModel('Labels');       
			  $this->set('labels',$this->Labels->find('list',[ 'keyField' => 'statval', 'valueField' => 'name'])->where(['project_id = ' => $this->request->data['projectid']])->toArray());
	          
          }
          
          
    }
    /**
     * View method
     *
     * @param string|null $id Qualitytest id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
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
	    $qualitytest = $this->Qualitytests->newEntity();


		$this->loadModel('Projects');
		$projects = $this->Projects->find('list')->order(['name' => 'asc']);;
		
		 if ($this->request->is('post')) {
		
			 $qualitytest = $this->Qualitytests->patchEntity($qualitytest, $this->request->data);
		
	        if ($qualresult = $this->Qualitytests->save($qualitytest)) {
                $this->Flash->success(__('Please add moderators to test.'));
                return $this->redirect(['action' => 'edit',$qualresult->id]);
            } else {
                $this->Flash->error(__('The project could not be saved. Please, try again.'));
            }	
		
	
	     }
		 $this->set('projects', $projects);
		//print_r($projects);
    
    
        $this->set(compact('qualitytest'));
        $this->set('_serialize', ['qualitytest']);
        
    }

    /**
     * Edit method
     *
     * @param string|null $id Qualitytest id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
 	   	$images = array();
 	   	$theimages = array();
 	   	$qualitytest = $this->Qualitytests->get($id,['contain' => ['Qualityimages']]);

		$this->loadModel('Projects');

		// put in security so users can only moderate images for their project
        $project = $this->Projects->get($qualitytest->project_id, [
            'contain' => ['Users','Labels']
        ]);


		foreach ($project->labels as $label) { 
			$statuses[] = $label->name;
		}

		
		 if ($this->request->is(['patch', 'post', 'put'])) {	
		 	for ($i = 0; $i <= 4; $i++) {
		 		if (isset($this->request->data["iurl_".$i]) && $this->request->data["iurl_".$i] != "") {		 		
					$image['iurl'] = $this->request->data["iurl_".$i];
					$image['statusid'] = $this->request->data["status_".$i];
					$image['reason'] = $this->request->data["reason_".$i];		
					array_push($theimages,$image);		
				}				 
			}
		 
			if (count($theimages) > 0) {
				$this->request->data['qualityimages'] = $theimages;


		  		$qualimages = TableRegistry::get('Qualityimages');
		  		$query = $qualimages->query();
		  		$query->delete()->where(['qualitytest_id' => $qualitytest['id']])->execute();


			}
		
		 
			$qualitytest = $this->Qualitytests->patchEntity($qualitytest, $this->request->data());



			
			if ($this->Qualitytests->save($qualitytest)) {
                $this->Flash->success(__('The QC test has been saved.'));
               // return $this->redirect(['action' => 'index']);			
			} else {   
                $this->Flash->error(__('The QC test could not be updated. Please, try again.'));
            }		
		 }

        $this->set('statuses', $statuses);
		$this->set('project', $project);
  	    $this->set(compact('qualitytest'));
        $this->set('_serialize', ['qualitytest']);      
    }

    /**
     * Delete method
     *
     * @param string|null $id Qualitytest id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {    
        $this->request->allowMethod(['post', 'delete']);
        $qualitytest = $this->Qualitytests->get($id);
        if ($this->Qualitytests->delete($qualitytest)) {
        
        
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }        
        
            $userhistory = new UsersHistory;
          	$userhistory->record($this->Auth->user('id'),'deleted QC Teset: '.$qualitytest['name'],$ipaddress);        
        
        
            $this->Flash->success(__('The QC Test has been deleted.'));
        } else {
            $this->Flash->error(__('The QC Test could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);    
    
    }    


    public function upload($id = null)
    {    

		if (!empty($_FILES)) { 
			$tempFile = $_FILES['file']['tmp_name'];                   
			$targetPath = '/tmp/';  
			$targetName = md5(uniqid(rand())).'.jpg';
			$targetFile =  $targetPath.$targetName; 
			move_uploaded_file($tempFile,$targetFile); 
			$this->set('targetName',$targetName);
			
			$s3 = new S3Client([
				'region'  => 'us-east-2',
				'version' => 'latest',
				'credentials' => [
					'key'    => "AKIAIHIHF6SAMGSMHEZQ",
					'secret' => "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",
				]
		    ]);			

			$result = $s3->putObject([
			'Bucket' => 'wpqcimages',
			'Key'    => $targetName,
			'ContentType'  => 'image/jpeg',
			'SourceFile' => $targetFile,
			'ACL' => 'public-read'			
		]);
		
		unlink($targetFile);
		
		$url = "https://wpqcimages.s3.us-east-2.amazonaws.com/".$targetName;
		
		$this->set("newurl",$url);
		}	  
		  
        $this->layout = 'default';
    	    $this->render('upload'); 
    } 
     
}
