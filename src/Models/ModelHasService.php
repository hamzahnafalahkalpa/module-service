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
    protected $primaryKey = 'id';
    protected $list  = ["id", "service_id", "model_id", "model_type"];
    protected $show  = [];

    public function model(){return $this->morphTo();}
    public function service(){return $this->belongsToModel('Service');}
}
