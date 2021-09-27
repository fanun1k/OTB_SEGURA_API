<?php namespace App\Controllers;

use App\Models\OtbsModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\TokensModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
class RestUsers extends ResourceController
{
    protected $modelName = 'App\Models\UsersModel';
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
            
            $user=$this->model->where('User_ID', $id)->findAll();
            
            if($user && $user[0]['State'] == 0){
                return $this->genericResponse(null,"El usuario esta inhabilitado", 500);
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
        
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
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

        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
        if($this->validateToken($token)){

            $data=$this->request->getRawInput();
            $user=$this->model->where('User_ID', $id)->findAll();
            
            if (!$user)//si el id no existe devolvera un error
            {
                return $this->genericResponse(null,"el usuario no existe",500);
            }
            
            if($user && $user[0]['State'] == 0){
                return $this->genericResponse(null,"El usuario esta inhabilitado", 500);
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

        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
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
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
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

    public function RemoveAdmin(){
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $user_ID=$this->request->getPost('User_ID');
            $Jsondata=$this->request->getJSON(true);
    
            if($Jsondata){
    
                $user_ID=$Jsondata['User_ID'];
            }  
    
            $user=$this->model->find($user_ID);
            if ($user) {
                $this->model->update($user["User_ID"],[
                                        "Type"=>0]);
                return $this->genericResponse(null,'Se quito el modo de administrador',200);
            }
            return $this->genericResponse(null,'No se encontró al usuario',500);
        }else{
            return $this->genericResponse(null,"Token Invalido",401);
        }
    }

    public function RemoveOTB(){
        $token = ($this->request->header('Authorization')!=null)?$this->request->header('Authorization')->getValue():"";
        if($this->validateToken($token)){
            $user_ID=$this->request->getPost('User_ID');
            $Jsondata=$this->request->getJSON(true);
    
            if($Jsondata){
                $user_ID=$Jsondata['User_ID'];
            }  
    
            $user=$this->model->find($user_ID);
            if ($user) {
                $this->model->update($user["User_ID"],[
                    "Otb_ID"=>null,
                    "Type"=> 0
                ]);
                return $this-> genericResponse(null,'El usuario fue removido de la OTB',200);
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
                        return $this-> genericResponse(null,'Cuenta de usuario inhabilitado',500);
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
    public function recoveryPassword(){
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
                        return $this-> genericResponse(null,'Cuenta de usuario inhabilitado',500);
                    }
                     //restaurar contraseña
                    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $newPass=substr(str_shuffle($permitted_chars), 0, 10);
                        
                        $id=$this->model->update($Userdata["User_ID"],["Password"=>md5($newPass)]);
                        if($id)  {
                            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                            try {
                                //Server settings
                                $mail->SMTPDebug = 2;                                 // Enable verbose debug output
                                $mail->isSMTP();                                      // Set mailer to use SMTP
                                $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                                $mail->Username = 'emergencyproject2@gmail.com';                // SMTP username
                                $mail->Password = 'proyecto2021';                           // SMTP password
                                $mail->SMTPSecure = 'ssl';                            // Enable SSL encryption, TLS also accepted with port 587
                                $mail->Port = 465;                                    // TCP port to connect to
                            
                                //Recipients
                                $mail->setFrom('emergencyproject2@gmail.com', 'OTB SEGURA');
                                $mail->addAddress($email);     // Add a recipient
                                //Content
                                $mail->isHTML(true);                               // Set email format to HTML
                                $mail->Subject = 'Restaurar Contraseña';
                                $mail->Body    = 'Su contraseña fue restaurada correctamente, su nueva contraseña es: '.$newPass;
                            
                                $mail->send();
                                return $this-> genericResponse(null,"Se le envió un correo con su nueva contraseña",200);
                            } catch (Exception $e) {
                                return $this-> genericResponse(null,$mail->ErrorInfo,500);
                            }                           
                        }
                        return $this-> genericResponse(null,"Error al intentar cambiar la contraseña",500);
                    }
                else{
                    return $this-> genericResponse(null,'Los datos no coinciden con ninguna cuenta',500);
                }
            }
            else{
                return $this-> genericResponse(null,'Correo inexistente',500); 
            }
        }
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);
 

    }   

    public function uploadfile(){
        $id = $this->request->getPost('User_ID');
        $img = $this->request->getFile('Profile');
        
        if($this->validate('usersUploadProfile')){
            $newfilename = $id . '.png';
            
            if ($img->move("./uploads/", $newfilename, true)){
                return $this->genericResponse(null,'Imagen ingresada', 200);
            }
        }
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);
        
    }
}