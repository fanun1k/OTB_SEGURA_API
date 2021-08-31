<?php namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    //Nombre de la tabla sobre la bdd
    protected $table='user';
    //El nombre del ID en la tabla
    protected $primaryKey= 'user_ID';
    //Las columnas que van a ser afectadas
    protected $allowedFields= ['name','email', 'password', 'cell_phone','ci','state', 'type','otb_ID'];
    
    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['user_ID'=>$id])
            ->first();
    }
}

?>