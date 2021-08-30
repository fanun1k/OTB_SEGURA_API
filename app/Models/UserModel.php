<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    //Nombre de la tabla cambiar sobre la bdd
    protected $table='usuario';
    //El nombre del ID en la tabla
    protected $primaryKey= 'usuario_ID';
    //Last columnas que van a afectar
    protected $allowedFields= ['nombre_completo','nombre_usuario', 'contraseña', 'celular','carnet','estado', 'tipo'];
    
    public function get($id = null)//el metodo lo dejo
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['usuario_ID'=>$id])// solo cambiar aqui el nombre de la columna de la tabla
            ->first();
    }
}

?>