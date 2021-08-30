<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    //Nombre de la tabla cambiar sobre la bdd
    protected $table='user';
    //El nombre del ID en la tabla
    protected $primaryKey= 'user_ID';
    //Last columnas que van a afectar
    protected $allowedFields= ['name','email', 'password', 'cell_phone','ci','state', 'type','otb_ID'];
    
    public function get($id = null)//el metodo lo dejo
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['user_ID'=>$id])// solo cambiar aqui el nombre de la columna de la tabla
            ->first();
    }
}

?>