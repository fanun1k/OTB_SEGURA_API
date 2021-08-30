<?php namespace App\Models;

use CodeIgniter\Model;

class AlertTypeModel extends Model
{
    protected $table='alert_type';
    protected $primaryKey= 'alert_type_ID';
    protected $allowedFields= ['name','state','otb_ID'];

    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['alert_type_ID'=>$id])
            ->first();
    }
}

?>