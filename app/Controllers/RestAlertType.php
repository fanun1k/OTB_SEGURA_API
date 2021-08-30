<?php namespace App\Controllers;

use App\Models\AlertTypeModel;
use App\Models\OtbsModel;
use CodeIgniter\RESTful\ResourceController;

class RestAlertType extends ResourceController
{
    protected $modelName = 'App\Models\AlertTypeModel';
    protected $format    = 'json';
    
    public function index()
    {
        return $this->genericResponse($this->model->findAll(),"",200); 
    }

    public function show($id = null) 
    {
        if ($id == null) 
        {
            return $this->genericResponse(null,"El ID no fue encontrado",500); 
        }

        $alertType=$this->model->find($id);

        if (!$alertType) 
        {
            return $this->genericResponse(null,"la alerta no existe",500); 
        }

        return $this->genericResponse($alertType,"",200); 
    }

    public function create(){ 

        $alertTypeModel =new AlertTypeModel(); 
        $obt_Model=new OtbsModel();

        if($this->validate('alerts')){ 
            if(!$obt_Model->find($this->request->getPost('otb_ID'))){
                return $this-> genericResponse(null,'el ID de otb no existe',500);
            }
            $id=$alertTypeModel->insert([
                'name'=>$this->request->getPost('name'),
                'otb_ID'=>$this->request->getPost('otb_ID'),
            ]);
            return $this-> genericResponse($this->model->find($id),null,200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
        
    }
    
    public function update($id=null){

        $alertTypeModel=new AlertTypeModel();
        $data=$this->request->getRawInput();
        $alertType=$this->model->find($id);

        if (!$alertType)
        {
            return $this->genericResponse(null,"la alerta no existe",500);
        }
        if($this->validate('alertsUpdate')){
            
            $alertTypeModel->update($id,[
                'name'=>$data['name'],
                'state'=>$data['state']           
            ]);

            return $this-> genericResponse($this->model->find($id),null,200);
        }
        
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
     
    }

    public function delete($id=null){ 

        $alertType=$this->model->find($id); 

        if (!$alertType) 
        {
            return $this->genericResponse(null,"la alerta no existe",500);
        }
        $this->model->delete($id);
        return $this-> genericResponse('El tipo de alerta fue eliminada',null,200); 
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
