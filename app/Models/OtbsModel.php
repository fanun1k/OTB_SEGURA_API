<?php namespace App\Models;

use CodeIgniter\Model;

class OtbsModel extends Model
{
    //nOMBRE DE LA TABLA EN BDD
    protected $table='otb';
    protected $primaryKey= 'otb_ID';
    //Nombre del id de la tabla
    protected $allowedFields= ['nombre','estado']; // las columnas que vamos a afectar

    public function get($id = null) //el metodo lo dejo
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['otb_ID'=>$id]) //solo cambio esto - el nombre de la columna de la tabla
            ->first();
    }
}

?>