<?php

use Gii\ModuleService\Models\Service;
use Gii\ModuleService\Models\ServicePrice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use Zahzah\LaravelSupport\Concerns\NowYouSeeMe;
    private $__table;

    public function __construct(){
        $this->__table = app(config('database.models.ServicePrice', ServicePrice::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()){
            Schema::create($table_name, function (Blueprint $table) {
                $service = app(config('database.models.Service', Service::class));

                $table->id();
                $table->foreignIdFor($service::class)
                    ->nullable()->index()
                    ->constrained($service->getTable(),$service->getKeyName(),'sci_si')
                    ->cascadeOnUpdate()->restrictOnDelete();
                $table->string("service_item_id",36);
                $table->string("service_item_type",50);
                $table->bigInteger("price");
                $table->boolean('current')->default(1)->nullable(false);
                $table->json('props')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['service_item_id','service_item_type'],'service_item_ref');
            });

            Schema::table($table_name, function (Blueprint $table) {
                $table->foreignIdFor($this->__table,'parent_id')->nullable()
                      ->after($this->__table->getKeyName())
                      ->index()->constrained()
                      ->cascadeOnUpdate()->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->__table->getTable());
    }
};
