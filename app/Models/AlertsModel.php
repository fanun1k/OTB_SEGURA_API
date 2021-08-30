<?php namespace App\Models;

use CodeIgniter\Model;

class AlertsModel extends Model
{
    protected $table='alert';
    protected $primaryKey= 'alert_Id';
    protected $allowedFields= ['date','longitude','latitude','state'];

    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['alert_Id'=>$id])
            ->first();
    }
}

?>