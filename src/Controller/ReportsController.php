<?php
namespace App\Controller;
use App\Controller;
use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class ReportsController extends AppController{
	
	public function initialize()
    {
        parent::initialize();
	   // $this->loadComponent('Paginator');
	   $this->connection = ConnectionManager::get('custom');
	}    
	
	public function selectData(){
		//$keyword = $this->request->Getquery('search');
		$keywordDate = $this->request->Getquery('date');
		//echo $keyword;
		echo $keywordDate;
		//$data = $this->connection->execute("select * from users;")->fetchAll("assoc");
		//$data = $this->connection->execute("select imgid,iurl, profane,pwords, m1date from image_queue_generic2 where status=1 and profane = 1 and m1date between( curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY) AND (curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY)")->fetchAll();
		$data = $this->connection->execute("select a.imgid, a.iurl, a.profane, a.pwords, CONVERT_TZ(a.m1date,'+00:00','+12:30'), b.email from image_queue_generic2 a JOIN musers AS b ON a.modby = b.id where a.status=1 and a.profane = 1 and CONVERT_TZ(a.m1date,'+00:00','+12:30') between( curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY) AND (curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY)")->fetchAll();
		//print_r($data);
		if($keywordDate){
			$data = $this->connection->execute("select a.imgid, a.iurl, a.profane, a.pwords,CONVERT_TZ(a.m1date,'+00:00','+12:30'), b.email from image_queue_generic2 a JOIN musers AS b ON a.modby = b.id where a.status=1 and a.profane = 1 and CONVERT_TZ(a.m1date,'+00:00','+12:30') like '%$keywordDate%'")->fetchAll();
			//$data = $this->connection->execute("select imgid, iurl, profane, pwords, m1date, modby from image_queue_generic2 where status=1 and profane = 1 and m1date like '%$keywordDate%' ")->fetchAll();
			$this->set("data",$data);
		}
		else{
			$this->set("data",$data);
		}
}


	public function index(){
		$this->autoRender = false;
		echo "this is test reports";
		$data = $this->connection->execute("select * from users;")->fetchAll("assoc");
		$this->set("data",$data);
		//$data = $this->loadModel('reports');
		//$result = $data->find('all');
		//$this->set('reports',$result);
}

public function dailyReport(){
	
	$this->set("name","Daily Reports");
	$this->set("OCR","OCRFalse Reports");

	$profiledata = array(
		"name"=>"ocr",
		"type"=>"Daily",
		"value" =>"Nudity"
	);
	$this->set("data",$profiledata);
}
}



