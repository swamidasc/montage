<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UsersTypes Controller
 *
 * @property \App\Model\Table\UsersTypesTable $UsersTypes
 */
class UsersTypesController extends AppController
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
    
    
        $this->set('usersTypes', $this->paginate($this->UsersTypes));
        $this->set('_serialize', ['usersTypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Users Type id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
    
    
    	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }    
    
    
        $usersType = $this->UsersTypes->get($id, [
            'contain' => []
        ]);
        $this->set('usersType', $usersType);
        $this->set('_serialize', ['usersType']);
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
    
    
    
        $usersType = $this->UsersTypes->newEntity();
        if ($this->request->is('post')) {
            $usersType = $this->UsersTypes->patchEntity($usersType, $this->request->data);
            if ($this->UsersTypes->save($usersType)) {
                $this->Flash->success(__('The users type has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The users type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('usersType'));
        $this->set('_serialize', ['usersType']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Users Type id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
    
    
       	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }
    
    
        $usersType = $this->UsersTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usersType = $this->UsersTypes->patchEntity($usersType, $this->request->data);
            if ($this->UsersTypes->save($usersType)) {
                $this->Flash->success(__('The users type has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The users type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('usersType'));
        $this->set('_serialize', ['usersType']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Users Type id.
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
        $usersType = $this->UsersTypes->get($id);
        if ($this->UsersTypes->delete($usersType)) {
            $this->Flash->success(__('The users type has been deleted.'));
        } else {
            $this->Flash->error(__('The users type could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
