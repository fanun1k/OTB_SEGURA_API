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

    public function listusersbyotb($id){
        
        $otbModel=new OtbsModel();
        if ($id == null){
            return $this->genericResponse(null,"El ID no fue encontrado",500);
        }

        $otb = $otbModel->find($id);

        if(!$otb){
            return $this->genericResponse(null,"la otb no existe",500);
        }
        /*
        $otbID = $this->request->getPost('Otb_ID');
        
        $Jsondata=$this->request->getJSON(true);

        if  (!$Jsondata){
            $otbID = $Jsondata['Otb_ID'];
        }*/
        //$UsersData = $this->model->findAll();
        $UsersData = $this->model->where('Otb_ID', $otb['Otb_ID'])->findAll();
        

        return $this->genericResponse($UsersData,"",200);
    }

    public function create(){
        
        $otbModel=new OtbsModel();

        $idOtb=$otbModel->find($this->request->getPost('otbID'));
        $data = array('Name' => $this->request->getPost('Name'),
                       'Password' => $this->request->getPost('Password'), 
                       'Cell_phone'=>$this->request->getPost('Cell_phone'),
                       'Ci'=>$this->request->getPost('Ci'),
                       'Type'=>$this->request->getPost('Type'),
                       'Otb_ID'=>$this->request->getPost('Otb_ID'),
                       'Email'=>$this->request->getPost('Email'));

        if(!$idOtb){
            return $this-> genericResponse(null,'El ID no pertenece a una OTB existente',500);
        }

        if(!array_filter($data)){
            $data = $this->request->getJSON(true);
        }

        if($this->validate('usersInsert')){

            $id=$this->model->insert([
                'Name'=>$data['Name'],
                'Password'=>$data['Password'],
                'Cell_phone'=>$data['Cell_phone'],
                'Ci'=>$data['Ci'],
                'Type'=>$data['Type'],
                'Otb_ID'=>$data['Otb_ID'],
                'Email'=>$data['Email']
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
        
        $data2 = $this->request->getJSON(true);
        if ($data2){
            $data = $data2;
        }

        if (isset($data['Name'])){
            $this->model->update($id,[
                'Name'=>$data['Name']          
            ]);
        }

        if (isset($data['Password'])){
            $this->model->update($id,[
                'Password'=>$data['Password']
            ]);
        }

        if (isset($data['Cell_phone'])){
            $this->model->update($id,[
                'Cell_phone'=>$data['Cell_phone']
            ]);
        }

        if (isset($data['Type'])){
            $this->model->update($id,[
                'Type'=>$data['Type']
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
        
        if  ($user['State'] == 1){
            $this->model->update($id,[
                'State'=>0
            ]);
        }
        

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
        ->where(['Email'=>$email])
        ->first();
     print_r($Userdata);
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
                "data"=>array($data),
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
        if($code==401)
        {
            return $this->respond(array(
                "msj"=>$msj,
                "code"=>$code
            ));
        }
    }
    
}