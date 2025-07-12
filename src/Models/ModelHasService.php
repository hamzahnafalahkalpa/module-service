<?php

namespace Hanafalah\ModuleService\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class ModelHasService extends BaseModel
{
    use HasUlids, HasProps, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'string';
    protected $list  = ["id", "service_id", "reference_id", "reference_type"];
    protected $show  = [];

    public function reference()
    {
        return $this->morphTo(__FUNCTION__, $this->getTableName() . '.reference_type', $this->getTableName() . '.reference_id');
    }
    public function service()
    {
        return $this->belongsToModel('Service');
    }
}
