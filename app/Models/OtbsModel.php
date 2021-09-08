<?php namespace App\Models;

use CodeIgniter\Model;

class OtbsModel extends Model
{
    //nOMBRE DE LA TABLA EN BDD
    protected $table='otb';
    protected $primaryKey= 'Otb_ID';
    //Nombre del id de la tabla
    protected $allowedFields= ['Name','State']; // las columnas que vamos a afectar

    public function get($id = null) 
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['Otb_ID'=>$id]) 
            ->first();
    }
  public function InsertOtb($userId,$data): bool{
    $userModel=new UsersModel();
      
      $this->db->transStart(); //iniciamos la transaccion 
      $otbId=$this->insert($data); //primera consulta
      $userModel->update($userId,["Otb_ID"=>$otbId]); //segunda consulta
      $this->db->transComplete(); // terminando la transaccion

      if($this->db->transStatus()===false){ //verificando el estado de la transaccion
        return false;
      }
      return true;

  }
}

?>