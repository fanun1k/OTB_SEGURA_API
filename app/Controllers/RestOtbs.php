<?php namespace App\Controllers;

use App\Models\OtbsModel;
use CodeIgniter\RESTful\ResourceController;

class RestOtbs extends ResourceController
{
    protected $modelName = 'App\Models\OtbsModel';
    protected $format  = 'json';
    
    public function index()
    {
        return $this->genericResponse($this->model->findAll(),"",200); 
    }

    public function show($id = null) 
    {
        if ($id == null) 
        {
            return $this->genericResponse(null,"El ID de la otb no fue encontrado",500); 
        }

        $otb=$this->model->find($id); 

        if (!$otb) 
        {
            return $this->genericResponse(null,"la otb no esta registrada",500); 
        }

        return $this->genericResponse($otb,"",200); 
    }

    public function create(){ 

        $otbModel =new OtbsModel(); 

        if($this->validate('otbsInsert')){

            $id=$otbModel->insert([
                'name'=>$this->request->getPost('Name')
            ]);

            return $this-> genericResponse($this->model->find($id),null,200);

        }
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);
        
    }

    public function update($id=null){

        $data=$this->request->getRawInput();
        $otb=$this->model->find($id);

        if (!$otb)
        {
            return $this->genericResponse(null,"la otb no existe",500);
        }

        if(isset($data['Name'])) {
            $this->model->update($id,[
                'name'=>$data['Name']            
            ]);
        }

        return $this-> genericResponse($this->model->find($id),null,200);
        
     
    }

    public function delete($id=null){ 

        $otb=$this->model->find($id); 

        if (!$otb) 
        {
            return $this->genericResponse(null,"la otb no existe",500); 
        }
        $this->model->delete($id);
        return $this-> genericResponse('La otb fue eliminada eliminada',null,200); 
    }
        
    private function genericResponse($data,$msj,$code)
    {
        if($code==200)
        {
            return $this->respond(array(
                "data"=>$data,
                "code"=>$code
            ));
        }
        if($code==500)
        {
            return $this->respond(array(
                "msj"=>$msj,
                "code"=>$code
            ));
        }
    }
    
}
