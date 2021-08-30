<?php namespace App\Controllers;

use App\Models\AlertTypeModel;
use CodeIgniter\RESTful\ResourceController;

class RestAlertType extends ResourceController
{
    protected $modelName = 'App\Models\AlertTypeModel';//Usamos le modelo aparte de esta vista AlertTypeModel
    protected $format    = 'json';
    
    public function index()
    {
        return $this->genericResponse($this->model->findAll(),"",200); //rETORNA TODOS LOS REGISTROS DE TODOS LOS MODELOS
    }

    public function show($id = null) //Mostrar por id como: http://localhost:8080/OTB_SEGURA_API/restAlertType/1<- el numero xd
    {
        if ($id == null) //Si el id es = a null retornara  un error 500 con el mensaje "el id no fue enecontrado"
        {
            return $this->genericResponse(null,"El ID no fue encontrado",500); //Error 500: error desde la parte del servidor
        }

        $alertType=$this->model->find($id); //Supongo si es que id no se encuentra en la bdd

        if (!$alertType) //Si aletType es diferente de true
        {
            return $this->genericResponse(null,"la alerta no existe",500); //Error 500: error desde la parte del servidor
        }

        return $this->genericResponse($alertType,"",200); //si todo sale bien retornaremos el alertType con 200: Todo ok
    }

    //cambiar el create 
    //cambiamos el validate 
    public function create(){ //Crear

        $alertTypeModel =new AlertTypeModel(); //Creamos la alerta con ayuda del modelo aparte

        if($this->validate('alerts')){ //lo cambiamos segun 
            $id=$alertTypeModel->insert([
                'nombre_tipo_alerta'=>$this->request->getPost('name')//cambiamos el nombre de la tabla  1:19:00 del video
            ]);
            return $this-> genericResponse($this->model->find($id),null,200);
        }

        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
        
    }
    
    public function update($id=null){

        $alertTypeModel=new AlertTypeModel();
        $data=$this->request->getRawInput();
        $alertType=$this->model->find($id);

        if (!$alertType)
        {
            return $this->genericResponse(null,"la alerta no existe",500);
        }
        if($this->validate('alerts')){
            
            $alertTypeModel->update($id,[
                'nombre_tipo_alerta'=>$data['name']            
            ]);

            return $this-> genericResponse($this->model->find($id),null,200);
        }
        
        $validation= \Config\Services::validation();
        return $this->genericResponse(null,$validation->getErrors(),500); 
     
    }

    public function delete($id=null){ //Borrar

        $alertType=$this->model->find($id); //buscamos el alerttype

        if (!$alertType) // si es false(no se encuentra)
        {
            return $this->genericResponse(null,"la alerta no existe",500); //con ayuda del genericResponse enviamos el mensage de "la alerta no existe" mas el error 500
        }
        $this->model->delete($id);
        return $this-> genericResponse('El tipo de alerta fue eliminada',null,200); //Si se elimino de manera correcta enviamos el dato de "que se elimino la alerta " mas el 200 de todo ok
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
