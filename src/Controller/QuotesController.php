<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 */
class QuotesController extends AppController
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
        $this->set('quotes', $this->paginate($this->Quotes));
        $this->set('_serialize', ['quotes']);
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
        $quote = $this->Quotes->get($id, [
            'contain' => []
        ]);
        $this->set('quote', $quote);
        $this->set('_serialize', ['quote']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $quote = $this->Quotes->newEntity();
        if ($this->request->is('post')) {
            $quote = $this->Quotes->patchEntity($quote, $this->request->data);
            if ($this->Quotes->save($quote)) {
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The quote could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('quote'));
        $this->set('_serialize', ['quote']);
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
        $quote = $this->Quotes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $quote = $this->Quotes->patchEntity($quote, $this->request->data);
            if ($this->Quotes->save($quote)) {
            
                        
                $this->Flash->success(__('The quote has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The quote could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('quote'));
        $this->set('_serialize', ['quote']);
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
    
        $this->request->allowMethod(['post', 'delete']);
        $quote = $this->Quotes->get($id);
        if ($this->Quotes->delete($quote)) {       
            $this->Flash->success(__('The quote has been deleted.'));
        } else {
            $this->Flash->error(__('The quote could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
