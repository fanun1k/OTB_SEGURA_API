<?php namespace App\Models;

use CodeIgniter\Model;

class AlarmsModel extends Model
{
    //Nombre de la tabla cambiar sobre la bdd
    protected $table='alarm';
    //El nombre del ID en la tabla
    protected $primaryKey= 'alarm_ID';
    //Last columnas que van a afectar
    protected $allowedFields= ['name','otb_ID'];
    
    public function get($id = null)//el metodo lo dejo
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['alarm_ID'=>$id])// solo cambiar aqui el nombre de la columna de la tabla
            ->first();
    }
}

?>