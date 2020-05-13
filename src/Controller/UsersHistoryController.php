<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UsersHistory Controller
 *
 * @property \App\Model\Table\UsersHistoryTable $UsersHistory
 */
class UsersHistoryController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $this->set('usersHistory', $this->paginate($this->UsersHistory));
        $this->set('_serialize', ['usersHistory']);
    }

    /**
     * View method
     *
     * @param string|null $id Users History id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usersHistory = $this->UsersHistory->get($id, [
            'contain' => ['Users']
        ]);
        $this->set('usersHistory', $usersHistory);
        $this->set('_serialize', ['usersHistory']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usersHistory = $this->UsersHistory->newEntity();
        if ($this->request->is('post')) {
            $usersHistory = $this->UsersHistory->patchEntity($usersHistory, $this->request->data);
            if ($this->UsersHistory->save($usersHistory)) {
                $this->Flash->success(__('The users history has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The users history could not be saved. Please, try again.'));
            }
        }
        $users = $this->UsersHistory->Users->find('list', ['limit' => 200]);
        $this->set(compact('usersHistory', 'users'));
        $this->set('_serialize', ['usersHistory']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Users History id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usersHistory = $this->UsersHistory->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usersHistory = $this->UsersHistory->patchEntity($usersHistory, $this->request->data);
            if ($this->UsersHistory->save($usersHistory)) {
                $this->Flash->success(__('The users history has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The users history could not be saved. Please, try again.'));
            }
        }
        $users = $this->UsersHistory->Users->find('list', ['limit' => 200]);
        $this->set(compact('usersHistory', 'users'));
        $this->set('_serialize', ['usersHistory']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Users History id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usersHistory = $this->UsersHistory->get($id);
        if ($this->UsersHistory->delete($usersHistory)) {
            $this->Flash->success(__('The users history has been deleted.'));
        } else {
            $this->Flash->error(__('The users history could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * LoggedIn method
     *
     * @param string|null $id Users Id.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */    
    public function loggedIn($id = null) {
	   
		 $query = $this->find('all', [
		 	'conditions' => ['userid = ' $id],
		 	'order' => ['created' => 'DESC']
		 ]);	     
	     
	    print_r($query);
	    
    }
    
}
