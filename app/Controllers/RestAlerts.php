<?php namespace App\Controllers;

use App\Models\AlertsModel;
use App\Models\AlertTypeModel;
use CodeIgniter\RESTful\ResourceController;

class RestAlerts extends ResourceController
{
    protected $modelName = 'App\Models\AlertsModel';
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

        $alert=$this->model->find($id);

        if (!$alert)
        {
            return $this->genericResponse(null,"la alerta no existe",500);
        }

        return $this->genericResponse($alert,"",200);
    }

    public function create(){

        $alertModel=new AlertsModel();

        
        if($this->validate('activitysInsert')){
            $id=$alertModel->insert([
                'Longitude'=>$this->request->getPost('Longitude'),
                'Latitude'=>$this->request->getPost('Latitude'),
                'Alert_type_ID'=>$this->request->getPost('AlertID'),
                'User_ID'=>$this->request->getPost('UserID')
            ]);
            return $this-> genericResponse($this->model->find($id),null,200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
        
    }
    public function update($id=null){
       
        $data=$this->request->getRawInput();
        $alert=$this->model->find($id);

        if (!$alert)
        {          
            return $this->genericResponse(null,"la alerta no existe",500);
        }

        if($this->validate('alerts')){
            $this->model->update($id,[
                'Name'=>$data['Name']            
            ]);

            return $this-> genericResponse($this->model->find($id),null,200);
        }
        
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);      
    }

    public function delete($id=null){

        $alert=$this->model->find($id);

        if (!$alert)
        {
            return $this->genericResponse(null,"la alerta no existe",500);
        }
        $this->model->delete($id);
        return $this-> genericResponse('La alerta fue eliminada',null,200);    
    }
        
    private function genericResponse($data,$msj,$code)
    {
        if($code==200)
        {
            return $this->respond(array(
                "Data"=>array($data),
                "Code"=>$code
            ));
        }
        if($code==500)
        {
            return $this->respond(array(
                "Msj"=>$msj,
                "Code"=>$code
            ));
        }
    }
    
}