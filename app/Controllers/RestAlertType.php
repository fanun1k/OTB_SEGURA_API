<?php namespace App\Controllers;

use App\Models\AlertTypeModel;
use App\Models\OtbsModel;
use CodeIgniter\RESTful\ResourceController;

class RestAlertType extends ResourceController
{
    protected $modelName = 'App\Models\AlertTypeModel';
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

        $alertType=$this->model->find($id);

        if (!$alertType) 
        {
            return $this->genericResponse(null,"La alerta no existe",500); 
        }

        return $this->genericResponse(array($alertType),"",200); 
    }

    public function create(){

        $otbModel=new OtbsModel();

        $data = array('Name' => $this->request->getPost('Name'),
                       'Otb_ID' => $this->request->getPost('Otb_ID'));

        if(!array_filter($data)){
            $data = $this->request->getJSON(true);
        }

        $idOtb=$otbModel->find($data['Otb_ID']);
        
        if(!$idOtb){
            return $this-> genericResponse(null,'El ID no pertenece a una OTB existente',500);
        }

        if($this->validate('alertsTypeInsert')){

            $id=$this->model->insert([
                'Name'=>$data['Name'],
                'Otb_ID'=>$data['Otb_ID'],
            ]);
            return $this-> genericResponse($this->model->find($id),null,200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
        
    }
    
    public function update($id=null){

        $data=$this->request->getRawInput();
        $alertType=$this->model->find($id);

        if (!$alertType)
        {
            return $this->genericResponse(null,"El tipo de alerta no existe",500);
        }
        $data2 = $this->request->getJSON(true);
        if ($data2){
            $data = $data2;
        }
            
        if(isset($data['Name'])){
            $this->model->update($id,[
                'Name'=>$data['Name']         
            ]);
        }
        

        return $this-> genericResponse($this->model->find($id),null,200);
        
    }

    public function delete($id=null){ 

        $alertType=$this->model->find($id); 

        if (!$alertType) 
        {
            return $this->genericResponse(null,"La alerta no existe",500);
        }

        if ($alertType['State'] == 1){
            $this->model->update($id,[
                'State'=>0 
            ]);
        }
        return $this-> genericResponse('El tipo de alerta fue eliminada',null,200); 
    }
        
    private function genericResponse($data,$msj,$code)
    {
        if($code==200)
        {
            return $this->respond(array(
                "Data"=>$data,
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
