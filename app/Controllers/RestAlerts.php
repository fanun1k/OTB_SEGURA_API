<?php namespace App\Controllers;

use App\Models\AlertsModel;
use App\Models\AlertTypeModel;
use App\Models\OtbsModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class RestAlerts extends ResourceController
{
    protected $modelName = 'App\Models\AlertsModel';
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

        $alert=$this->model->find($id);

        if (!$alert)
        {
            return $this->genericResponse(null,"La alerta no existe",500);
        }

        return $this->genericResponse(array($alert),"",200);
    }

    public function create(){

        $alertTypeModel= new AlertTypeModel();
        $otbModel = new OtbsModel();
        $userModel = new UsersModel();

        $data = array('Longitude' => $this->request->getPost('Longitude'),
                        'Latitude'=> $this->request->getPost('Latitude'),
                        'Otb_ID' => $this->request->getPost('Otb_ID'),
                        'Alert_type_ID'=>$this->request->getPost('Alert_type_ID'),
                        'User_ID'=>$this->request->getPost('User_ID'));

        if(!array_filter($data)){
            $data = $this->request->getJSON(true);
        }
        
        $idOtb = $otbModel->find($data['Otb_ID']);
        $idUser = $userModel->find($data['User_ID']);
        $idTypeAlert = $alertTypeModel->find($data['Alert_type_ID']);

        if(!$idOtb){
            return $this-> genericResponse(null,'El ID no pertenece a una OTB existente',500);
        }
        if(!$idUser){
            return $this-> genericResponse(null,'El ID no pertenece a un Usuario existente',500);
        }
        if (!$idTypeAlert){
            return $this-> genericResponse(null,'El ID no pertenece a un tipo de alerta existente',500);
        }

        if($this->validate('alertsInsert')){

            $id=$this->model->insert([
                'Longitude'=>$data['Longitude'],
                'Latitude'=>$data['Latitude'],
                'Otb_ID' => $data['Otb_ID'],
                'Alert_type_ID'=>$data['Alert_type_ID'],
                'User_ID'=>$data['User_ID']
            ]);
            return $this-> genericResponse($this->model->find($id),null,200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
        
    }
    public function update($id=null){
       
        $data=$this->request->getRawInput();
        $alert=$this->model->find($id);

        if (!$alert)
        {          
            return $this->genericResponse(null,"La alerta no existe",500);
        }

        $data2 = $this->request->getJSON(true);
        if  ($data2){
            $data = $data2;
        }

        if (isset($data['Longitude'])){
            $this->model->update($id,[
                'Longitude'=>$data['Longitude']            
            ]);
        }
        
        if (isset($data['Latitude'])){
            $this->model->update($id,[
                'Latitude'=>$data['Latitude']            
            ]);
        }

        return $this-> genericResponse($this->model->find($id),null,200);

    }

    public function delete($id=null){

        $alert=$this->model->find($id);

        if (!$alert)
        {
            return $this->genericResponse(null,"La alerta no existe",500);
        }

        if ($alert['State'] == 1){
            $this->model->update($id,[
                'State'=>0            
            ]);
        }

        return $this-> genericResponse('La alerta fue eliminada',null,200);    
    }
        
    private function genericResponse($data,$msj,$code)
    {
        if($code==200)
        {
            return $this->respond(array(
                "Data"=>array($data),
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
    }
    
}