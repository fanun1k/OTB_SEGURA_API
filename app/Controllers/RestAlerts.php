<?php namespace App\Controllers;

use App\Models\AlertsModel;
use App\Models\AlertTypeModel;
use App\Models\OtbsModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class RestAlerts extends ResourceController
{
    protected $modelName = 'App\Models\AlertsModel';
    protected $format    = 'json';
    
    public function index()
    {
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
        if($this->validateToken($token)){
            return $this->genericResponse($this->model->where('State', 1)->findAll(),"",200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }

    public function show($id = null)
    {
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
        if($this->validateToken($token)){
            if ($id == null)
            {
                return $this->genericResponse(null,"El ID no fue encontrado",500);
            }
    
            $alert=$this->model->where('Otb_ID', $id);
            $alert = $alert->where('State', 1)->findAll();
    
            if($alert && $alert[0]['State'] == 0){
                return $this->genericResponse(null,"La alerta esta inhabilitado", 500);
            }
    
            if (!$alert)
            {
                return $this->genericResponse(null,"La alerta no existe",500);
            }
    
            return $this->genericResponse($alert,"",200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }
    
    public function alertsByUser($id = null,$idus=null)
    {
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";

        if($this->validateToken($token)){
            if ($id == null && $idus==null)
            {
                return $this->genericResponse(null,"El ID no fue encontrado",500);
            }
    
            $alert=$this->model->where('Otb_ID', $id);
            $alert = $alert->where('State', 1)->findAll();
            $alert=$alert->where('User_ID',$idus).findAll();
            if (!$alert)
            {
                return $this->genericResponse(null,"No se encontraron alertas",500);
            }
            return $this->genericResponse($alert,null,200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }


    public function create(){
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $alertTypeModel= new AlertTypeModel();
            $otbModel = new OtbsModel();
            $userModel = new UsersModel();
    
            $data = array('Longitude' => $this->request->getPost('Longitude'),
                            'Latitude'=> $this->request->getPost('Latitude'),
                            'Otb_ID' => $this->request->getPost('Otb_ID'),
                            'Alert_type_ID'=>$this->request->getPost('Alert_type_ID'),
                            'User_ID'=>$this->request->getPost('User_ID'));
    
            if(!array_filter($data)){
                $data = $this->request->getJSON(true);
            }
            
            $idOtb = $otbModel->find($data['Otb_ID']);
            $idUser = $userModel->find($data['User_ID']);
            $idTypeAlert = $alertTypeModel->find($data['Alert_type_ID']);
    
            if(!$idOtb){
                return $this-> genericResponse(null,'El ID no pertenece a una OTB existente',500);
            }
            if(!$idUser){
                return $this-> genericResponse(null,'El ID no pertenece a un Usuario existente',500);
            }
            if (!$idTypeAlert){
                return $this-> genericResponse(null,'El ID no pertenece a un tipo de alerta existente',500);
            }
    
            if($this->validate('alertsInsert')){
    
                $id=$this->model->insert([
                    'Longitude'=>$data['Longitude'],
                    'Latitude'=>$data['Latitude'],
                    'Otb_ID' => $data['Otb_ID'],
                    'Alert_type_ID'=>$data['Alert_type_ID'],
                    'User_ID'=>$data['User_ID']
                ]);
                return $this-> genericResponse(null,"Alerta Creada",200);
            }
    
            $validation= \Config\Services::validation();
            return $this->genericResponse(null,$validation->getErrors(),500);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
        
    }
    public function update($id=null){
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $data=$this->request->getRawInput();
            $alert=$this->model->find($id);
    
            if (!$alert)
            {          
                return $this->genericResponse(null,"La alerta no existe",500);
            }
    
            $data2 = $this->request->getJSON(true);
            if  ($data2){
                $data = $data2;
            }
    
            if (isset($data['Longitude'])){
                $this->model->update($id,[
                    'Longitude'=>$data['Longitude']            
                ]);
            }
            
            if (isset($data['Latitude'])){
                $this->model->update($id,[
                    'Latitude'=>$data['Latitude']            
                ]);
            }
    
            return $this-> genericResponse($this->model->find($id),null,200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }

    public function delete($id=null){
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $alert=$this->model->find($id);

            if (!$alert)
            {
                return $this->genericResponse(null,"La alerta no existe",500);
            }
    
            if ($alert['State'] == 1){
                $this->model->update($id,[
                    'State'=>0            
                ]);
            }
    
            return $this-> genericResponse('La alerta fue eliminada',null,200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
            
    }
    
}