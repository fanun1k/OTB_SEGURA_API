<?php namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    //Nombre de la tabla sobre la bdd
    protected $table='user';
    //El nombre del ID en la tabla
    protected $primaryKey= 'User_ID';
    //Las columnas que van a ser afectadas
    protected $allowedFields= ['Name','Email', 'Password', 'Cell_phone','Ci','State', 'Type','Otb_ID'];
    
    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['User_ID'=>$id])
            ->first();
    }
}

?>