<?php namespace App\Models;

use CodeIgniter\Model;

class CamerasModel extends Model
{
    protected $table='camera';
    protected $primaryKey= 'Camera_ID';
    protected $allowedFields= ['Name','State','Otb_ID'];

    public function get($id = null)
    {
        if($id===null){
            return $this->findAll();
        }
        return $this->asArray()
            ->where(['Camera_ID'=>$id])
            ->first();
    }
}

?>