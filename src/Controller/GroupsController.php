<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\UsersHistory;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 */
class GroupsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('groups', $this->paginate($this->Groups));
        $this->set('_serialize', ['groups']);
    }

    /**
     * View method
     *
     * @param string|null $id Group id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
    
    	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }    
    
    
        $group = $this->Groups->get($id, [
            'contain' => ['Projects', 'Users']
        ]);
        $this->set('group', $group);
        $this->set('_serialize', ['group']);
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
			 
		 }    
    
    
        $group = $this->Groups->newEntity();
        if ($this->request->is('post')) {
            $group = $this->Groups->patchEntity($group, $this->request->data);
            if ($this->Groups->save($group)) {
            
                $userhistory = new UsersHistory;
                
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }                 
                
                
                
            	$userhistory->record($this->Auth->user('id'),'created group: '.$group['name'],$ipaddress);
            
            
                $this->Flash->success(__('The group has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The group could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('group'));
        $this->set('_serialize', ['group']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Group id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
    
    	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }    
    
    
        $group = $this->Groups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
  
            $group = $this->Groups->patchEntity($group, $this->request->data);
            if ($this->Groups->save($group)) {
 
 
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }
				
 
                 $userhistory = new UsersHistory;
            	$userhistory->record($this->Auth->user('id'),'updated group: '.$group['name'],$ipaddress);           
            
            
                $this->Flash->success(__('The group has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The group could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('group'));
        $this->set('_serialize', ['group']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Group id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
    
    	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }    
    
    
        $this->request->allowMethod(['post', 'delete']);
        $group = $this->Groups->get($id);
        if ($this->Groups->delete($group)) {
        
                $userhistory = new UsersHistory;
                
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	          	  $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
	           	 $ipaddress = $this->request->clientIp();
			   	}                 
                
                
            	$userhistory->record($this->Auth->user('id'),'deleted group: '.$group['name'],$ipaddress);        
        
        
        
            $this->Flash->success(__('The group has been deleted.'));
        } else {
            $this->Flash->error(__('The group could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * editips method
     *
     * @param string|null $id Group id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function editips($id = null)
    {
    
    	 if ($this->Auth->user('user_type_id') > 4) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }    
    
		 $groupid = $this->Auth->user('group_id');
    
        $group = $this->Groups->get($groupid, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
  
            $group = $this->Groups->patchEntity($group, $this->request->data);
            if ($this->Groups->save($group)) {
 
 
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
	            $ipaddress = $this->request->clientIp();
            }
				
 
                 $userhistory = new UsersHistory;
            	$userhistory->record($this->Auth->user('id'),'updated ips: '.$group['name'],$ipaddress);           
            
            
                $this->Flash->success(__('The settings has been saved.'));
                return $this->redirect(['controller' => 'Projects','action' => 'index']);
            } else {
                $this->Flash->error(__('The settings could not be saved. Please, try again.'));
                return $this->redirect(['controller' => 'Projects','action' => 'index']);
            }
        }
        $this->set(compact('group'));
        $this->set('_serialize', ['group']);
        
    }

    
    
}
