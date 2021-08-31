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
                'longitude'=>$this->request->getPost('longitude'),
                'latitude'=>$this->request->getPost('latitude'),
                'alert_type_ID'=>$this->request->getPost('alertID'),
                'user_ID'=>$this->request->getPost('userID')
            ]);
            return $this-> genericResponse($this->model->find($id),null,200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
        
    }
    public function update($id=null){

        $alertModel=new AlertsModel();
        $alertTypeModel=new AlertTypeModel();
        $data=$this->request->getRawInput();

        $tipoAlertaModelID=$this->model->find($data['alertID']);
        $alert=$this->model->find($id);
        $usuarioModelID=$this->model->find($data['userID']);

        if (!$alert)
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