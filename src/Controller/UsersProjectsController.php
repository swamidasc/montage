<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UsersProjects Controller
 *
 * @property \App\Model\Table\UsersProjectsTable $UsersProjects
 */
class UsersProjectsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['User', 'Project']
        ];
        $this->set('usersProjects', $this->paginate($this->UsersProjects));
        $this->set('_serialize', ['usersProjects']);
    }

    /**
     * View method
     *
     * @param string|null $id Users Project id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usersProject = $this->UsersProjects->get($id, [
            'contain' => ['User', 'Project']
        ]);
        $this->set('usersProject', $usersProject);
        $this->set('_serialize', ['usersProject']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usersProject = $this->UsersProjects->newEntity();
        if ($this->request->is('post')) {
            $usersProject = $this->UsersProjects->patchEntity($usersProject, $this->request->data);
            if ($this->UsersProjects->save($usersProject)) {
                $this->Flash->success(__('The users project has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The users project could not be saved. Please, try again.'));
            }
        }
        $user = $this->UsersProjects->User->find('list', ['limit' => 200]);
        $project = $this->UsersProjects->Project->find('list', ['limit' => 200]);
        $this->set(compact('usersProject', 'user', 'project'));
        $this->set('_serialize', ['usersProject']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Users Project id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usersProject = $this->UsersProjects->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usersProject = $this->UsersProjects->patchEntity($usersProject, $this->request->data);
            if ($this->UsersProjects->save($usersProject)) {
                $this->Flash->success(__('The users project has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The users project could not be saved. Please, try again.'));
            }
        }
        $user = $this->UsersProjects->User->find('list', ['limit' => 200]);
        $project = $this->UsersProjects->Project->find('list', ['limit' => 200]);
        $this->set(compact('usersProject', 'user', 'project'));
        $this->set('_serialize', ['usersProject']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Users Project id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usersProject = $this->UsersProjects->get($id);
        if ($this->UsersProjects->delete($usersProject)) {
            $this->Flash->success(__('The users project has been deleted.'));
        } else {
            $this->Flash->error(__('The users project could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
