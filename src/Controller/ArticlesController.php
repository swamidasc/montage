<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function index()
    {

	//	$data = $this->loadModel('reports');
	//	$result = $data->find('all');
	//	$this->set('reports',$result);
       // $this->loadComponent('Paginator');
        //$articles = $this->Paginator->paginate($this->Articles->find());
        $this->set('name','ramu');
    }

    public function view($slug = null)
    {
    $article = $this->Articles->findBySlug($slug)->firstOrFail();
    $this->set(compact('article'));
    }
}

?>
