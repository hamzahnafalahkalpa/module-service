<?php

use Hanafalah\ModuleService\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Hanafalah\ModuleService\Models\PriceComponent;

return new class extends Migration
{
    use Hanafalah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table;

    public function __construct()
    {
        $this->__table = app(config('database.models.PriceComponent', PriceComponent::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()) {
            Schema::create($table_name, function (Blueprint $table) {
                $service = app(config('database.models.Service', Service::class));

                $table->ulid('id')->primary();
                $table->foreignIdFor($service::class)->nullable(true)
                    ->index()->constrained()->restrictOnDelete()->cascadeOnUpdate();

                $table->string('model_type', 50)->nullable(false);
                $table->string('model_id', 36)->nullable(false);
                $table->string('component_type', 50)->nullable(false);
                $table->string('component_id', 36)->nullable(false);

                $table->unsignedBigInteger('price')->nullable(false)->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['model_type', 'model_id'], 'pc_model');
                $table->index(['component_type', 'component_id'], 'pc_component');
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
