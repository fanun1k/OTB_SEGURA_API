<?php namespace App\Controllers;

use App\Models\OtbsModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class RestUsers extends ResourceController
{
    protected $modelName = 'App\Models\UsersModel';
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

        $user=$this->model->find($id);

        if (!$user)
        {
            return $this->genericResponse(null,"el usuario no existe",500);
        }

        return $this->genericResponse($user,"",200);
    }

    public function create(){

        $otbModel=new OtbsModel();

        $idOtb=$otbModel->find($this->request->getPost('otbID'));

        if(!$idOtb){
            return $this-> genericResponse(null,'El ID no pertenece a una OTB existente',500);
        }
        
        if($this->validate('usersInsert')){

            $id=$this->model->insert([
                'name'=>$this->request->getPost('name'),
                'password'=>$this->request->getPost('password'),
                'cell_phone'=>$this->request->getPost('phone'),
                'ci'=>$this->request->getPost('ci'),
                'type'=>$this->request->getPost('type'),
                'otb_ID'=>$this->request->getPost('otbID'),
                'email'=>$this->request->getPost('email')
            ]);
            return $this-> genericResponse($this->model->find($id),null,200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);   
    }
    
    public function update($id=null){

        $data=$this->request->getRawInput();
        $user=$this->model->find($id);

        if (!$user)//si el id no existe devolvera un error
        {
            return $this->genericResponse(null,"el usuario no existe",500);
        }

        if(true){
            
            if (isset($data['name'])){
                $this->model->update($id,[
                    'name'=>$data['name']          
                ]);
            }

            if (isset($data['password'])){
                $this->model->update($id,[
                    'password'=>$data['password']
                ]);
            }
    
            if (isset($data['phone'])){
                $this->model->update($id,[
                    'cell_phone'=>$data['phone']
                ]);
            }
    
            if (isset($data['type'])){
                $this->model->update($id,[
                    'type'=>$data['type']
                ]);
            }

            return $this-> genericResponse($this->model->find($id),null,200);
        }
        
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
     
    }

    public function delete($id=null){

        $user=$this->model->find($id);

        if (!$user)
        {
            return $this->genericResponse(null,"el usuario no existe",500);
        }
        $this->model->delete($id);
        return $this-> genericResponse('El usuario fue eliminado',null,200);    
    }

    public function login()
    {
        $email=$this->request->getPost('Email');
        $password=$this->request->getPost('Password');

        //varibale Jsondata se llenara solo si recibio un json en lugar de un form-data
        $Jsondata=$this->request->getJSON(true); 

        //verificamos si llego un json, si es asi entonces las variables que utilizavamos
        // ahora pasaran a ser igual al valor del json
        if($Jsondata){

            $email=$Jsondata['Email'];
            $password=$Jsondata['Password'];
        }        
        $Userdata=$this->model->asArray()
        ->where(['email'=>$email])
        ->first();
     
        if($Userdata){
            if($password==$Userdata['password']){
                if($Userdata['state']==0){
                    return $this-> genericResponse(null,'Cuenta de usuario inhabilitada',401);
                }
                return $this-> genericResponse($Userdata,null,200);
            }
            else{
                return $this-> genericResponse(null,'Contraseña incorrecta',401);
            }
        }
        else{
            return $this-> genericResponse(null,'Usuario no registrado',401); 
        }  
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
            ),500);
        }
        if($code==401)
        {
            return $this->respond(array(
                "msj"=>$msj,
                "code"=>$code
            ),401);
        }
    }
    
}