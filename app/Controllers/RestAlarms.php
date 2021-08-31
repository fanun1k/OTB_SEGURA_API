<?php namespace App\Controllers;

use App\Models\AlarmsModel;
use CodeIgniter\RESTful\ResourceController;

class RestAlarms extends ResourceController
{
    protected $modelName = 'App\Models\AlarmsModel';
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

        $alarm=$this->model->find($id);

        if (!$alarm)
        {
            return $this->genericResponse(null,"La alarma no existe",500);
        }

        return $this->genericResponse($alarm,"",200);
    }

    public function create(){
 
        if($this->validate('alarmsInsert')){
            $id=$this->model->insert([
                'name'=>$this->request->getPost('name'),
                'otb_ID'=>$this->request->getPost('otbID')
            ]);
            return $this-> genericResponse($this->model->find($id),null,200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
        
    }
    /*
    public function update($id=null){

        $userModel=new UserModel();
        //$otbModel = new OtbModel();
        $data=$this->request->getRawInput();
        $user=$this->model->find($id);
        //$otbModelID = $this->model->find($data['otbID']);

        if (!$user)
        {
            return $this->genericResponse(null,"el usuario no existe",500);
        }
        if($this->validate('alerts')){
            
            $alertTypeModel->update($id,[
                'nombre_tipo_alerta'=>$data['name']            
            ]);

            return $this-> genericResponse($this->model->find($id),null,200);
        }
        
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
     
    }*/

    public function delete($id=null){

        $alarm=$this->model->find($id);

        if (!$alarm)
        {
            return $this->genericResponse(null,"La alarma no existe",500);
        }
        $this->model->delete($id);
        return $this-> genericResponse('La alarma fue eliminado',null,200);    
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