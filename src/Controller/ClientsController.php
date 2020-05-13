<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\UsersHistory;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 */
class ClientsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
    
    	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }
    
    
        $this->set('clients', $this->paginate($this->Clients));
        $this->set('_serialize', ['clients']);
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
    
       	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 } 
    
    
        $client = $this->Clients->get($id, [
            'contain' => []
        ]);
        $this->set('client', $client);
        $this->set('_serialize', ['client']);
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
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }
    
    
        $client = $this->Clients->newEntity();
        if ($this->request->is('post')) {
            $client = $this->Clients->patchEntity($client, $this->request->data);
            if ($this->Clients->save($client)) {
            
                $userhistory = new UsersHistory;
            	$userhistory->record($this->Auth->user('id'),'created client: '.$client['companyname'],$_SERVER['HTTP_X_FORWARDED_FOR']);
            
                $this->Flash->success(__('The client has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The client could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('client'));
        $this->set('_serialize', ['client']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
    
         if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }
    
    
        $client = $this->Clients->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Clients->patchEntity($client, $this->request->data);
            if ($this->Clients->save($client)) {
            
                $userhistory = new UsersHistory;
            	$userhistory->record($this->Auth->user('id'),'edited client: '.$client['companyname'],$_SERVER['HTTP_X_FORWARDED_FOR']);
            
            
                $this->Flash->success(__('The client has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The client could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('client'));
        $this->set('_serialize', ['client']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
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
        $client = $this->Clients->get($id);
        if ($this->Clients->delete($client)) {
        
                $userhistory = new UsersHistory;
            	$userhistory->record($this->Auth->user('id'),'deleted client: '.$client['companyname'],$_SERVER['HTTP_X_FORWARDED_FOR']);        
        
        
            $this->Flash->success(__('The client has been deleted.'));
        } else {
            $this->Flash->error(__('The client could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
