<?php namespace App\Models;

use CodeIgniter\Model;

class AlertsModel extends Model
{
    protected $table='alert';
    protected $primaryKey= 'Alert_ID';
    protected $allowedFields= ['Date','Longitude','Latitude','State', 'Otb_ID', 'Alert_type_ID', 'User_ID'];

    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['Alert_ID'=>$id])
            ->first();
    }
}

?>