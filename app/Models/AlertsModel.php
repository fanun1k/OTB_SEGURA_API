<?php namespace App\Models;

use CodeIgniter\Model;

class AlertsModel extends Model
{
    protected $table='actividades';
    protected $primaryKey= 'actividades_ID';
    protected $allowedFields= ['fecha_actividades','longitud','latitud','estado'];

    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['actividades_ID'=>$id])
            ->first();
    }
}

?>