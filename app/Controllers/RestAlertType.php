<?php namespace App\Controllers;

use App\Models\AlertTypeModel;
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

        if($this->validate('alerts')){ 
            $id=$alertTypeModel->insert([
                'nombre_tipo_alerta'=>$this->request->getPost('name')
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
        if($this->validate('alerts')){
            
            $alertTypeModel->update($id,[
                'nombre_tipo_alerta'=>$data['name']            
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
