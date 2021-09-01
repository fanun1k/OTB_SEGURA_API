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
                'name'=>$this->request->getPost('Name'),
                'password'=>$this->request->getPost('Password'),
                'cell_phone'=>$this->request->getPost('Phone'),
                'ci'=>$this->request->getPost('Ci'),
                'type'=>$this->request->getPost('Type'),
                'otb_ID'=>$this->request->getPost('OtbID'),
                'email'=>$this->request->getPost('Email')
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

        if (isset($data['Name'])){
            $this->model->update($id,[
                'name'=>$data['Name']          
            ]);
        }

        if (isset($data['Password'])){
            $this->model->update($id,[
                'password'=>$data['Password']
            ]);
        }

        if (isset($data['Phone'])){
            $this->model->update($id,[
                'cell_phone'=>$data['Phone']
            ]);
        }

        if (isset($data['Type'])){
            $this->model->update($id,[
                'type'=>$data['Type']
            ]);
        }

        return $this-> genericResponse($this->model->find($id),null,200);
     
    }

    public function delete($id=null){

        $user=$this->model->find($id);

        if (!$user)
        {
            return $this->genericResponse(null,"el usuario no existe",500);
        }
        
        if  ($user['state'] == 1){
            $this->model->update($id,[
                'state'=>0
            ]);
        }
        

        return $this-> genericResponse('El usuario fue eliminado',null,200);    
    }

    public function login()
    {
        $email=$this->request->getPost('Email');
        $password=$this->request->getPost('Password');

        $data=$this->model->asArray()
        ->where(['email'=>$email])
        ->first();
     
        if($data){
            if($password==$data['password']){
                return $this-> genericResponse($data,null,200);
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