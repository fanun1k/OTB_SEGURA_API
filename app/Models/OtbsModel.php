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
}

?>