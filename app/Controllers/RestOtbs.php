<?php namespace App\Controllers;

use App\Models\OtbsModel;
use CodeIgniter\RESTful\ResourceController;

class RestOtbs extends ResourceController
{
    protected $modelName = 'App\Models\OtbsModel';
    protected $format  = 'json';
    
    public function index()
    {
        return $this->genericResponse($this->model->where('State', 1)->findAll(),"",200);
    }

    public function show($id = null) 
    {
        if ($id == null) 
        {
            return $this->genericResponse(null,"El ID de la otb no fue encontrado",500); 
        }

        $otb=$this->model->where('Otb_ID', $id)->findAll();

        if($otb && $otb[0]['State'] == 0){
            return $this->genericResponse(null,"La Otb esta inhabilitada", 401);
        }

        if (!$otb)
        {
            return $this->genericResponse(null,"La otb no esta registrada",500); 
        }

        return $this->genericResponse($otb,"",200); 
    }

    public function create(){ 

        $otbModel =new OtbsModel(); 

        $data = array('Name' => $this->request->getPost('Name'));

        if (!array_filter($data)){
            $data = $this->request->getJSON(true);
        }

        if($this->validate('otbsInsert')){

            $id=$otbModel->insert([
                'Name'=>$data['Name']
            ]);

            return $this-> genericResponse(null,"Otb creado",200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500);
        
    }

    public function update($id=null){

        $data=$this->request->getRawInput();
        $otb=$this->model->find($id);
        
        if (!$otb)
        {
            return $this->genericResponse(null,"la otb no existe",500);
        }

        $data2 = $this->request->getJSON(true);
        if ($data2){
            $data = $data2;
        }

        if(isset($data['Name'])) {
            $this->model->update($id,[
                'Name'=>$data['Name']            
            ]);
        }

        return $this-> genericResponse($this->model->find($id),null,200);
        
     
    }

    public function delete($id=null){ 

        $otb=$this->model->find($id); 

        if (!$otb) 
        {
            return $this->genericResponse(null,"la otb no existe",500); 
        }
        
        if  ($otb['State'] == 1){
            $this->model->update($id,[
                'State'=>0
            ]);
        }

        return $this-> genericResponse('La otb fue eliminada eliminada',null,200); 
    }
        
    private function genericResponse($data,$msj,$code)
    {
        if($code==200)
        {
            return $this->respond(array(
                "Data"=>$data,
                "Msj"=>$msj,
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
