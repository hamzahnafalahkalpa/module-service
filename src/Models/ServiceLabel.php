<?php

namespace Hanafalah\ModuleService\Models;

use Hanafalah\LaravelSupport\Models\Unicode\Unicode;
use Hanafalah\ModuleService\Resources\ServiceLabel\{
    ViewServiceLabel, ShowServiceLabel
};

class ServiceLabel extends Unicode
{
    protected $table = 'unicodes';

    public function getViewResource(){
        return ViewServiceLabel::class;
    }

    public function getShowResource(){
        return ShowServiceLabel::class;
    }
}
