<?php namespace App\Controllers;

use App\Models\CamerasModel;
use App\Models\OtbsModel;
use CodeIgniter\RESTful\ResourceController;

class RestCameras extends ResourceController
{
    protected $modelName = 'App\Models\CamerasModel';
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

            $camera=$this->model->where('Camera_ID', $id)->findAll();

            if($camera && $camera[0]['State'] == 0){
                return $this->genericResponse(null,"La camara esta inhabilitado", 401);
            }

            if (!$camera) 
            {
                return $this->genericResponse(null,"La camara no existe",500); 
            }

            return $this->genericResponse($camera,"",200); 
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
        
    }

    public function create(){

        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $otbModel=new OtbsModel();

            $data = array('Name' => $this->request->getPost('Name'),
                           'Otb_ID' => $this->request->getPost('Otb_ID'));
    
            if(!array_filter($data)){
                $data = $this->request->getJSON(true);
            }
    
            $idOtb=$otbModel->find($data['Otb_ID']);
            
            if(!$idOtb){
                return $this-> genericResponse(null,'El ID no pertenece a una OTB existente',500);
            }
    
            if($this->validate('camerasInsert')){
    
                $id=$this->model->insert([
                    'Name'=>$data['Name'],
                    'Otb_ID'=>$data['Otb_ID'],
                ]);
                return $this-> genericResponse(null,"Camara creada",200);
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
            $camera=$this->model->find($id);
    
            if (!$camera)
            {
                return $this->genericResponse(null,"La camara no existe",500);
            }
            $data2 = $this->request->getJSON(true);
            if ($data2){
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
            $camera=$this->model->find($id); 

            if (!$camera) 
            {
                return $this->genericResponse(null,"la alerta no existe",500);
            }
    
            if ($camera['State'] == 1){
                $this->model->update($id,[
                    'State'=>0
                ]);
            }
            return $this-> genericResponse('El tipo de alerta fue eliminada',null,200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
         
    }
        
    private function genericResponse($data,$msj,$code)
    {
        if($code==200)
        {
            return $this->respond(array(
                "Data"=>$data,
                "Msj"=>$msj,
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
        if($code==401)
        {
            return $this->respond(array(
                "Msj"=>$msj,
                "Code"=>$code
            ));
        }
    }
    
}
