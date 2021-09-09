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
        return $this->genericResponse($this->model->where('State', 1)->findAll(),"",200);
    }

    public function show($id = null)
    {
        if ($id == null)
        {
            return $this->genericResponse(null,"El ID no fue encontrado",500);
        }
        
        $user=$this->model->where('User_ID', $id)->findAll();
        
        if($user && $user[0]['State'] == 0){
            return $this->genericResponse(null,"El usuario esta inhabilitado", 401);
        }

        if (!$user)
        {
            return $this->genericResponse(null,"El usuario no existe",500);
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

        $UsersData = $this->model->where('Otb_ID', $otb['Otb_ID']);
        $UsersData = $UsersData->where('State', 1)->findAll();

        return $this->genericResponse($UsersData,"",200);
    }

    public function create(){
        
        $data = array('Name' => $this->request->getPost('Name'),
                       'Password' => $this->request->getPost('Password'), 
                       'Cell_phone'=>$this->request->getPost('Cell_phone'),
                       'Ci'=>$this->request->getPost('Ci'),
                       'Email'=>$this->request->getPost('Email'));

        if(!array_filter($data)){
            $data = $this->request->getJSON(true);
        }

        if($this->validate('usersInsert')){
            
            $id=$this->model->insert([
                'Name'=>$data['Name'],
                'Password'=>$data['Password'],
                'Cell_phone'=>$data['Cell_phone'],
                'Ci'=>$data['Ci'],
                'Email'=>$data['Email']
            ]);

            return $this-> genericResponse(null,"Usuario creado",200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);   
    }
    
    public function update($id=null){

        $data=$this->request->getRawInput();
        $user=$this->model->where('User_ID', $id)->findAll();
        
        if (!$user)//si el id no existe devolvera un error
        {
            return $this->genericResponse(null,"el usuario no existe",500);
        }
        
        if($user && $user[0]['State'] == 0){
            return $this->genericResponse(null,"El usuario esta inhabilitado", 401);
        }
        
        $data2 = $this->request->getJSON(true);
        if ($data2){
            $data = $data2;
        }

        if ($this->validate('usersUpdate')){

            $userModel = $this->model->update($id,[
                'Name'=>$data['Name'],
                'Password'=>$data['Password'],
                'Cell_phone'=>$data['Cell_phone']
            ]);
            
            return $this-> genericResponse(null,"Usuario modificado",200);
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
        $Userdata=$this->model
        ->where(['Email'=>$email])
        ->first();
        if($Userdata){
            if($password==$Userdata['Password']){
                if($Userdata['State']==0){
                    return $this-> genericResponse(null,'Cuenta de usuario inhabilitada',401);
                }
                return $this-> genericResponse(array($Userdata),null,200);
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
            /*return json_encode(array('data'=>$data,
                                'code'=>$code));*/
            return $this->respond(array(
                "Data"=>$data,
                "Msj" => $msj,
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