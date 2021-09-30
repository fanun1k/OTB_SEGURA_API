<?php namespace App\Models;

use CodeIgniter\Model;

class OtbsModel extends Model
{
    //nOMBRE DE LA TABLA EN BDD
    protected $table='otb';
    protected $primaryKey= 'Otb_ID';
    //Nombre del id de la tabla
    protected $allowedFields= ['Name','State','Code']; // las columnas que vamos a afectar

    public function get($id = null) 
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['Otb_ID'=>$id]) 
            ->first();
    }
  public function InsertOtb($userId,$data)
  {
    $userModel=new UsersModel();
      
      $this->db->transStart(); //iniciamos la transaccion 
      $otbId=$this->insert(["Name" => $data["Name"],
                            "Code" => $data["Code"]]);  //Insertamos el nombre de la nueva OTB en BDD
      $userModel->update($userId,[
        "Otb_ID"=>$otbId,
        "Type"=>2]); //Actualizamos al usuario para desiganarle su ID de OTB y su Tipo para que sea Admin de esa OTB
      $this->db->transComplete(); // terminando la transaccion

      if($this->db->transStatus()===false){ //verificando el estado de la transaccion
        return false;
      }
      return $this->where('Otb_ID',$otbId)
      ->findAll();

  }
}

?>
