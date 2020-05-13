<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProjectsTypes Controller
 *
 * @property \App\Model\Table\ProjectsTypesTable $ProjectsTypes
 */
class ProjectsTypesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('projectsTypes', $this->paginate($this->ProjectsTypes));
        $this->set('_serialize', ['projectsTypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Projects Type id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
    
    	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }    
    
        $projectsType = $this->ProjectsTypes->get($id, [
            'contain' => []
        ]);
        $this->set('projectsType', $projectsType);
        $this->set('_serialize', ['projectsType']);
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
    
    
        $projectsType = $this->ProjectsTypes->newEntity();
        if ($this->request->is('post')) {
            $projectsType = $this->ProjectsTypes->patchEntity($projectsType, $this->request->data);
            if ($this->ProjectsTypes->save($projectsType)) {
                $this->Flash->success(__('The projects type has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The projects type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('projectsType'));
        $this->set('_serialize', ['projectsType']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Projects Type id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
    
    	 if ($this->Auth->user('user_type_id') != 1) {   
    	 	 $this->Flash->error(__('You do not have permission to perform this action.')); 
			 return $this->redirect(['controller' => 'Projects','action' => 'index']);
		 }    
    
        $projectsType = $this->ProjectsTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $projectsType = $this->ProjectsTypes->patchEntity($projectsType, $this->request->data);
            if ($this->ProjectsTypes->save($projectsType)) {
                $this->Flash->success(__('The projects type has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The projects type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('projectsType'));
        $this->set('_serialize', ['projectsType']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Projects Type id.
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
        $projectsType = $this->ProjectsTypes->get($id);
        if ($this->ProjectsTypes->delete($projectsType)) {
            $this->Flash->success(__('The projects type has been deleted.'));
        } else {
            $this->Flash->error(__('The projects type could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
