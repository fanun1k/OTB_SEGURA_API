<?php namespace App\Models;

use CodeIgniter\Model;

class AlertTypeModel extends Model
{
    protected $table='tipo_alerta';
    protected $primaryKey= 'tipo_alerta_ID';
    protected $allowedFields= ['nombre_tipo_alerta','estado'];

    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['tipo_alerta_ID'=>$id])
            ->first();
    }
}

?>