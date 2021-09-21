<?php namespace App\Controllers;

use App\Models\AlarmsModel;
use App\Models\OtbsModel;
use CodeIgniter\RESTful\ResourceController;

class RestAlarms extends ResourceController
{
    protected $modelName = 'App\Models\AlarmsModel';
    protected $format    = 'json';
    
    public function index()
    {
        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
            return $this->genericResponse($this->model->where('State', 1)->findAll(),"",200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }

    public function show($id = null)
    {
        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
            if ($id == null)
            {
                return $this->genericResponse(null,"El ID no fue encontrado",500);
            }
    
            $alarm=$this->model->where('Alarm_ID', $id)->findAll();
    
            if($alarm && $alarm[0]['State'] == 0){
                return $this->genericResponse(null,"La alarma esta inhabilitado", 500);
            }
    
            if (!$alarm)
            {
                return $this->genericResponse(null,"La alarma no existe",500);
            }
    
            return $this->genericResponse($alarm,"",200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
        
    }

    public function create(){
        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $otbModel = new OtbsModel();
            $data = array('Name' => $this->request->getPost('Name'),
                            'Otb_ID' => $this->request->getPost('Otb_ID'));
    
            if (!array_filter($data)){
                $data = $this->request->getJSON(true);
            }
            
            $idOtb=$otbModel->find($data['Otb_ID']);
            if(!$idOtb){
                return $this-> genericResponse(null,'El ID no pertenece a una OTB existente',500);
            }
    
            if($this->validate('alarmsInsert')){
                $id=$this->model->insert([
                    'Name'=>$data['Name'],
                    'Otb_ID'=>$data['Otb_ID']
                ]);
                return $this-> genericResponse(null,"Alarma creada",200);
            }
    
            $validation= \Config\Services::validation();
            return $this->genericResponse(null,$validation->getErrors(),500); 
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
        
    }
    
    public function update($id=null){
        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $data=$this->request->getRawInput();

            $user=$this->model->find($id); //Buscamos el id que nos llego
    
            if (!$user) //Si el id no existe devolvera un error
            {
                return $this->genericResponse(null,"el usuario no existe",500);
            }
    
            $data2 = $this->request->getJSON(true);
    
            if($data2){
                $data = $data2;
            }
    
            if(isset($data['Name'])){
                $this->model->update($id,[
                    'Name'=>$data['Name']            
                ]);
            }
    
            return $this-> genericResponse($this->model->find($id),null,200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }

    public function delete($id=null){

        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $alarm=$this->model->find($id);

            if (!$alarm)
            {
                return $this->genericResponse(null,"La alarma no existe",500);
            }
    
            if  ($alarm['State'] == 1){
                $this->model->update($id,[
                    'State'=>0
                ]);
            }
    
            return $this-> genericResponse('La alarma fue eliminado',null,200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }
    
}