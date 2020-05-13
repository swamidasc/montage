<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProjectsCriterias Controller
 *
 * @property \App\Model\Table\ProjectsCriteriasTable $ProjectsCriterias
 */
class CriteriasController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Projects']
        ];
        $this->set('Criterias', $this->paginate($this->Criterias));
        $this->set('_serialize', ['Criterias']);
    }

    /**
     * View method
     *
     * @param string|null $id Projects Status id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $projectsCriteria = $this->Criterias->get($id, [
            'contain' => ['Projects']
        ]);
        $this->set('projectsCriteria', $projectsCriteria);
        $this->set('_serialize', ['projectsCriteria']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $projectsCriteria = $this->Criterias->newEntity();
        if ($this->request->is('post')) {
            $projectsCriteria = $this->Criterias->patchEntity($projectsCriteria, $this->request->data);
            if ($this->Criterias->save($projectsCriteria)) {
                $this->Flash->success(__('The projects status has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The projects status could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Criterias->Projects->find('list', ['limit' => 200]);
        $this->set(compact('projectsCriteria', 'projects'));
        $this->set('_serialize', ['projectsCriteria']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Projects Criteria id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $projectsCriteria = $this->Criterias->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $projectsCriteria = $this->Criterias->patchEntity($projectsCriteria, $this->request->data);
            if ($this->Criterias->save($projectsCriteria)) {
                $this->Flash->success(__('The projects status has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The projects status could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Criterias->Projects->find('list', ['limit' => 200]);
        $this->set(compact('projectsCriteria', 'projects'));
        $this->set('_serialize', ['projectsCriteria']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Projects Criteria id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $projectsCriteria = $this->Criterias->get($id);
        if ($this->Criterias->delete($projectsCriteria)) {
            $this->Flash->success(__('The projects status has been deleted.'));
        } else {
            $this->Flash->error(__('The projects status could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
