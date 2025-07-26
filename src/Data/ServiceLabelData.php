<?php

namespace Hanafalah\ModuleService\Data;

use Hanafalah\LaravelSupport\Data\UnicodeData;
use Hanafalah\ModuleService\Contracts\Data\ServiceLabelData as DataServiceLabelData;

class ServiceLabelData extends UnicodeData implements DataServiceLabelData
{
    public static function before(array &$attributes){
        $attributes['flag'] ??= 'ServiceLabel';
        parent::before($attributes);
    }
}