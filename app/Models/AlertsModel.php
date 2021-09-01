<?php namespace App\Models;

use CodeIgniter\Model;

class AlertsModel extends Model
{
    protected $table='alert';
    protected $primaryKey= 'Alert_Id';
    protected $allowedFields= ['Date','Longitude','Latitude','State'];

    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['Alert_Id'=>$id])
            ->first();
    }
}

?>