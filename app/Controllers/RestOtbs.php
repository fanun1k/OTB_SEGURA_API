<?php namespace App\Controllers;

use App\Models\OtbsModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class RestOtbs extends ResourceController
{
    protected $modelName = 'App\Models\OtbsModel';
    protected $format  = 'json';
    
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
                return $this->genericResponse(null,"El ID de la otb no fue encontrado",500); 
            }

            $otb=$this->model->where('Otb_ID', $id)->findAll();

            if($otb && $otb[0]['State'] == 0){
                return $this->genericResponse(null,"La Otb esta inhabilitada", 401);
            }

            if (!$otb)
            {
                return $this->genericResponse(null,"La otb no esta registrada",500); 
            }

            return $this->genericResponse($otb,"",200); 
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }

    public function create(){ 

        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
            $otbModel =new OtbsModel();
            $userModel=new UsersModel();      
            $data = array('Name' => $this->request->getPost('Name'),
                        'User_ID'=>$this->request->getPost('User_ID'));


            if (!array_filter($data)){
                $data = $this->request->getJSON(true);
            }

            if($this->validate('otbsInsert')){
                if($this->validateToken($token)){
                    $existe=$userModel->find($data["User_ID"]);
                
                    if(!$existe){ 
                        return $this->genericResponse(null,"ID de usuario no encontrado",404);
                    }
                    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $res=$otbModel->InsertOtb($data["User_ID"],["Name"=>$data["Name"],"Code"=>substr(str_shuffle($permitted_chars), 0, 8)]);
                
                    if(!$res){               
                        return $this->genericResponse(null,"Error en la transacción",500);
                        
                    }

                    return $this->genericResponse(null,'Otb Creada',200);

                }else{
                    return $this->genericResponse(null,"Token Invalido",401);
                }
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
            $otb=$this->model->find($id);
            
            if (!$otb)
            {
                return $this->genericResponse(null,"la otb no existe",500);
            }

            $data2 = $this->request->getJSON(true);
            if ($data2){
                $data = $data2;
            }

            if(isset($data['Name'])) {
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
            $otb=$this->model->find($id); 

            if (!$otb) 
            {
                return $this->genericResponse(null,"la otb no existe",500); 
            }
            
            if  ($otb['State'] == 1){
                $this->model->update($id,[
                    'State'=>0
                ]);
            }

            return $this-> genericResponse('La otb fue eliminada eliminada',null,200); 
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
        if($code==404)
        {
            return $this->respond(array(
                "Msj"=>$msj,
                "Code"=>$code
            ));
        }
    }
    
}
