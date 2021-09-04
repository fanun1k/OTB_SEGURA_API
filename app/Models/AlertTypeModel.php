<?php namespace App\Models;

use CodeIgniter\Model;

class AlertTypeModel extends Model
{
    protected $table='alert_type';
    protected $primaryKey= 'Alert_type_ID';
    protected $allowedFields= ['Name','State','Otb_ID'];

    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['Alert_type_ID'=>$id])
            ->first();
    }
}

?>