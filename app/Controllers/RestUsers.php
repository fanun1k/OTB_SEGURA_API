<?php namespace App\Controllers;

use App\Models\OtbsModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class RestUsers extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
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
    
    /*public function update($id=null){

       
        $data=$this->request->getRawInput();

        $user=$this->model->find($id);//buscamos el id que nos llego
        if (!$user)//si el id no existe devolvera un error
        {
            return $this->genericResponse(null,"el usuario no existe",500);
        }

        if(){
        }

        if($this->validate('alerts')){
            
            $alertTypeModel->update($id,[
                'name'=>$data['name']            
            ]);

            return $this-> genericResponse($this->model->find($id),null,200);
        }
        
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
     
    }*/

    public function delete($id=null){

        $user=$this->model->find($id);

        if (!$user)
        {
            return $this->genericResponse(null,"el usuario no existe",500);
        }
        $this->model->delete($id);
        return $this-> genericResponse('El usuario fue eliminado',null,200);    
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