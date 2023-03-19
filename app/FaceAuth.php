<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FaceAuth extends Model
{
    protected $guarded = [];
    protected $appends = ['date_time'];

    public function getDateTimeAttribute(){
        return (new Carbon($this->created_at))->format('d/m/y h:m:s');
    }

}
