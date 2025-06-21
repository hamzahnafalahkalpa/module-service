<?php

namespace Hanafalah\ModuleService\Models;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Illuminate\Database\Eloquent\Relations\Relation;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\ModulePayment\Resources\PriceComponent\{ViewPriceComponent, ShowPriceComponent};
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PriceComponent extends BaseModel
{
    use HasUlids, HasProps;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'service_id', 'model_type', 'model_id', 
        'tariff_component_id', 'price', 'props'
    ];

    protected static function booted(): void
    {
        parent::booted();
        static::creating(function ($query) {
            if (!isset($query->service_id)) {
                $relation = Relation::morphMap()[$query->model_type];
                if (!isset($relation)) throw new \Exception('Relation not found');
                $relation = app($relation)->findOrFail($query->model_id);
                if (\method_exists($relation, 'service')) {
                    $service = $relation->service;
                    if (isset($service)) {
                        $query->service_id = $service->getKey();
                    }
                }
            }
        });
    }

    public function getViewResource(){return ViewPriceComponent::class;}

    public function getShowResource(){return ShowPriceComponent::class;}

    public function model(){return $this->morphTo();}
    public function component(){return $this->morphTo();}
    public function service(){return $this->belongsToModel('Service');}
}
