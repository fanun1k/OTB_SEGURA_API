<?php namespace App\Controllers;

use App\Models\OtbsModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\TokensModel;

class RestUsers extends ResourceController
{
    protected $modelName = 'App\Models\UsersModel';
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
            
            $user=$this->model->where('User_ID', $id)->findAll();
            
            if($user && $user[0]['State'] == 0){
                return $this->genericResponse(null,"El usuario esta inhabilitado", 401);
            }

            if (!$user)
            {
                return $this->genericResponse(null,"El usuario no existe",500);
            }

            return $this->genericResponse($user,"",200);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
        
    }

    public function listusersbyotb($id){
        
        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
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

        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
        
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
                'Password'=> md5($data['Password']),
                'Cell_phone'=>$data['Cell_phone'],
                'Ci'=>$data['Ci'],
                'Email'=>$data['Email']
            ]);
            if (!$id){
                return $this-> genericResponse(null,"Error al insertar",500);
            }
            return $this-> genericResponse(null,"Usuario creado",200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);

    }
    
    public function update($id=null){

        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){

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

        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }

    public function delete($id=null){

        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
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
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        } 
    }
    public function setAdmin(){
        $token = ($this->request->getHeader('Authorization')!=null)?$this->request->getHeader('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $user_ID=$this->request->getPost('User_ID');
            $Jsondata=$this->request->getJSON(true);
    
            if($Jsondata){
    
                $user_ID=$Jsondata['User_ID'];
            }  
    
            $user=$this->model->find($user_ID);
            if ($user) {
                $this->model->update($user["User_ID"],[
                                        "Type"=>1]);
                return $this->genericResponse(null,'Usuario establecido con éxito',200);                   
            }
            return $this->genericResponse(null,'No se encontró al usuario',500);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
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
        if($this->validate('usersLogin')){
            $Userdata=$this->model
            ->where(['Email'=>$email])
            ->first();
            if($Userdata){
                if(md5($password)==$Userdata['Password']){
                    if($Userdata['State']==0){
                        return $this-> genericResponse(null,'Cuenta de usuario inhabilitada',500);
                    }
    
                    $tokenModel = new TokensModel();
                    $token = $this->createJWT($Userdata['Email'], $Userdata['Password']);
                    if(!$tokenModel->where(['User_ID' => $Userdata['User_ID']])->first())
                    {
                        $tokenModel->insert([
                            'Jwt' => $token,
                            'User_ID' => $Userdata['User_ID']
                        ]); 
                    }else{
                        $tokenModel->update($Userdata['User_ID'],[
                            'Jwt' => $token
                        ]);
                    }
                        
                    $Userdata = $Userdata + ['Token' => $token];
                    return $this-> genericResponse(array($Userdata),null,200);
                }
                else{
                    return $this-> genericResponse(null,'Contraseña incorrecta',500);
                }
            }
            else{
                return $this-> genericResponse(null,'Usuario no registrado',500); 
            }
        }
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);
    }
    public function RecoveryPassword(){
        $email=$this->request->getPost("Email");
        $ci=$this->request->getPost("Ci");
        $jsonData=$this->request->getJson(true);
        if ($jsonData) {
            $email=$jsonData["Email"];
            $ci=$jsonData["Ci"];
        }
        if($this->validate('recovery')){
            $Userdata=$this->model
            ->where(['Email'=>$email])
            ->first();
            if($Userdata){
                if($ci==$Userdata['Ci']){
                    if($Userdata['State']==0){
                        return $this-> genericResponse(null,'Cuenta de usuario inhabilitada',500);
                    }
                     //restaurar contraseña
                     $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                     $newPass=substr(str_shuffle($permitted_chars), 0, 10);
                    $to = $email;
                    $subject = "Restauración de contraseña OTB SEGURA";
                    $message = "su contraseña fue restablecida correctamente, su nueva contraseña es: " . $newPass;
                    $headers = "From: emergencyproject2@gmail.com" . "\r\n" . "CC: destinatarioencopia@email.com";

                    mail($to, $subject, $message, $headers);

                   
                    return $this-> genericResponse(null,"Se le enviará un correo con su nueva contraseña",200);
                }
                else{
                    return $this-> genericResponse(null,'Los datos no coinciden con ninguna cuenta',500);
                }
            }
            else{
                return $this-> genericResponse(null,'Los datos no coinciden con ninguna cuenta',500); 
            }
        }
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);

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