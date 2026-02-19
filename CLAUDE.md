# CLAUDE.md - Module Service

This file provides guidance to Claude Code when working with the `hanafalah/module-service` package.

## Module Overview

`module-service` is a Laravel package that provides service management functionality for healthcare facilities (clinics, puskesmas). It handles the creation, pricing, and organization of medical services with support for hierarchical structures, service items, and price components.

**Namespace:** `Hanafalah\ModuleService`

**Dependencies:**
- `hanafalah/laravel-support` - Base support utilities and traits
- `hanafalah/module-transaction` - Transaction management integration

## WARNING: BaseServiceProvider Usage

This module extends `Hanafalah\LaravelSupport\Providers\BaseServiceProvider`. When modifying the service provider:

1. **DO NOT** override the `boot()` method without calling `parent::boot()`
2. **DO NOT** modify the `registers()` call pattern - it auto-registers contracts, schemas, models, and data classes
3. The `dir()` method MUST return the correct path to the `src/` directory
4. The `migrationPath()` method returns `database_path()` - migrations are copied to the main app

**Critical:** The `registerMainClass()` method binds the main `ModuleService` class to the container. Breaking this binding will cause resolution failures across the application.

## Directory Structure

```
module-service/
├── assets/
│   ├── config/
│   │   └── config.php              # Module configuration
│   └── database/
│       └── migrations/             # Database migrations
├── src/
│   ├── Commands/                   # Artisan commands
│   ├── Concerns/                   # Traits for model integration
│   │   ├── HasService.php          # Add service capability to models
│   │   ├── HasServiceItem.php      # Add service item relations
│   │   ├── HasServicePrice.php     # Add service price relations
│   │   ├── HasModelService.php     # Model-service associations
│   │   └── HasPriceComponent.php   # Price component support
│   ├── Contracts/                  # Interfaces
│   │   ├── Data/                   # DTO contracts
│   │   └── Schemas/                # Schema contracts
│   ├── Data/                       # Data Transfer Objects (Spatie DTOs)
│   ├── Enums/                      # Enum definitions
│   │   ├── Service/Status.php      # ACTIVE, INACTIVE
│   │   └── ServiceItem/Flag.php    # Service item types
│   ├── Models/                     # Eloquent models
│   ├── Providers/                  # Additional service providers
│   ├── Resources/                  # API resources
│   ├── Schemas/                    # Business logic classes
│   ├── ModuleService.php           # Main package class
│   └── ModuleServiceServiceProvider.php  # Service provider
└── composer.json
```

## Core Models

### Service
The main service entity representing a healthcare service offering.

**Key Fields:**
- `id` (ULID) - Primary key
- `parent_id` - For hierarchical service structures
- `name` - Service name
- `service_code` - Auto-generated unique code
- `reference_id`, `reference_type` - Polymorphic reference to source entity
- `service_label_id` - Category/label reference
- `status` - ACTIVE or INACTIVE
- `price` - Service price (unsigned bigint)
- `cogs` - Cost of goods sold
- `margin` - Calculated profit margin
- `props` - JSON metadata

**Relationships:**
- `reference()` - Morphable reference to source entity
- `serviceLabel()` - BelongsTo ServiceLabel (Unicode model)
- `serviceItems()` / `serviceItem()` - Service components
- `servicePrices()` / `servicePrice()` - Price configurations
- `modelHasService()` - Pivot for model associations
- `paymentSummary()` - Payment summary reference

### ServiceItem
Individual components or sub-items within a service.

**Key Fields:**
- `id` (ULID) - Primary key
- `parent_id` - For nested items
- `service_id` - Parent service reference
- `reference_id`, `reference_type` - Polymorphic reference
- `name` - Item name
- `price` - Item price
- `props` - JSON metadata with `flag` for item type

**Flags (ServiceItem\Flag enum):**
- `MAIN_PACKAGE` - Primary service package
- `CATEGORY_PACKAGE` - Category-level grouping
- `SPECIAL_PACKAGE` - Special service packages
- `ADDITIONAL_PACKAGE` - Add-on items
- `ITEM_PACKAGE` - Individual item
- `POLI_PACKAGE` - Clinic department package
- `AGENT`, `COMPANY`, `PAYER`, `BOAT` - Payer/entity types

### ServicePrice
Price configuration for services and service items.

**Key Fields:**
- `id` (ULID) - Primary key
- `parent_id` - For hierarchical pricing
- `service_id` - Parent service reference
- `service_item_id`, `service_item_type` - Polymorphic item reference
- `price` - Price amount
- `cogs` - Cost of goods sold
- `margin` - Profit margin
- `tax` - Tax amount
- `props` - JSON metadata

### ModelHasService
Pivot model for many-to-many service associations.

**Key Fields:**
- `id` (ULID) - Primary key
- `service_id` - Service reference
- `model_id`, `model_type` - Polymorphic model reference

### ServiceLabel
Uses the `Unicode` model from laravel-support (stored in `unicodes` table). Used for categorizing services.

## Traits (Concerns)

### HasService
Add to models that should have an associated service automatically created.

```php
use Hanafalah\ModuleService\Concerns\HasService;

class MedicalProcedure extends Model
{
    use HasService;
}
```

**Behavior:**
- On `created`: Creates a Service record linked via morph relationship
- On `updated`: Updates the linked Service record
- On `deleting`: Deletes the linked Service record

**Configuration Required:**
Add your model's morph class to `config/module-service.php`:
```php
'is_using_services' => [
    'medical_procedure',
    // other model morph classes...
]
```

### HasServiceItem
Add to models that can be referenced as service items.

```php
use Hanafalah\ModuleService\Concerns\HasServiceItem;

class Medicine extends Model
{
    use HasServiceItem;
}
```

**Note:** Prevents deletion if the model is used in a service item.

### HasServicePrice
Add to models that need service price relationships.

### HasModelService
Add to models that can be associated with multiple services.

## Schemas (Business Logic)

### Service Schema
Located at `src/Schemas/Service.php`. Implements service CRUD operations.

**Key Method:**
```php
public function prepareStoreService(ServiceData $service_dto): Model
```

This method:
1. Creates or updates a Service record
2. Processes nested `service_prices` array
3. Processes nested `service_items` array
4. Calculates and sets margin based on price and COGS
5. Fills props with reference and label data

### ServiceItem Schema
Located at `src/Schemas/ServiceItem.php`.

**Key Method:**
```php
public function prepareStoreServiceItem(ServiceItemData $service_item_dto): Model
```

### ServicePrice Schema
Located at `src/Schemas/ServicePrice.php`.

## Data Transfer Objects

All DTOs extend `Hanafalah\LaravelSupport\Supports\Data` and use Spatie's laravel-data package.

### ServiceData
```php
$serviceData = ServiceData::from([
    'name' => 'Consultation',
    'service_label_id' => $labelId,
    'price' => 50000,
    'cogs' => 30000,
    'reference_type' => 'medical_procedure',
    'reference_id' => $procedureId,
    'service_items' => [...],
    'service_prices' => [...],
    'props' => ['custom_field' => 'value']
]);
```

### ServiceItemData
```php
$itemData = ServiceItemData::from([
    'name' => 'Medicine X',
    'price' => 10000,
    'service_id' => $serviceId,
    'reference_type' => 'medicine',
    'reference_id' => $medicineId,
    'props' => ['flag' => 'ITEM']
]);
```

## Configuration

### config/module-service.php

```php
return [
    'namespace' => 'Hanafalah\\ModuleService',
    'app' => [
        'contracts' => [
            // Custom contract bindings
        ],
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts',
        'schema' => 'Schemas',
        'database' => 'Database',
        'data' => 'Data',
        'resource' => 'Resources',
        'migration' => '../assets/database/migrations'
    ],
    'database' => [
        'models' => [
            // Custom model bindings
        ]
    ],
    'commands' => [
        Commands\InstallMakeCommand::class
    ],
    'is_using_services' => [
        // Model morph classes that auto-create services
    ]
];
```

## Usage Examples

### Creating a Service
```php
use Hanafalah\ModuleService\Contracts\Schemas\Service as ServiceContract;
use Hanafalah\ModuleService\Data\ServiceData;

$serviceSchema = app(ServiceContract::class);

$serviceData = ServiceData::from([
    'name' => 'General Consultation',
    'service_label_id' => $labelId,
    'price' => 100000,
    'cogs' => 60000,
    'reference_type' => 'consultation_type',
    'reference_id' => $consultationTypeId,
]);

$service = $serviceSchema->prepareStoreService($serviceData);
```

### Using HasService Trait
```php
// In your model
class ConsultationType extends Model
{
    use HasService;

    protected $fillable = ['name', 'description'];
}

// When creating, service is auto-created
$type = ConsultationType::create(['name' => 'Pediatric Consultation']);
$service = $type->service; // Access the linked Service
```

### Querying Services
```php
use Hanafalah\ModuleService\Models\Service;

// Get active services with items
$services = Service::where('status', 'ACTIVE')
    ->with(['serviceItems', 'serviceLabel'])
    ->get();

// Get services by reference type
$consultations = Service::where('reference_type', 'consultation_type')
    ->get();
```

## Database Migrations

Migrations are located in `assets/database/migrations/`:

1. `0001_01_01_000012_create_services_table.php` - Main services table
2. `0001_01_01_000013_create_services_item_table.php` - Service items table
3. `0001_01_01_000032_create_service_prices_table.php` - Service prices table
4. `0001_01_01_000032_create_model_has_services_table.php` - Pivot table

## API Resources

- `ViewService` / `ShowService` - Service resources
- `ViewServiceItem` / `ShowServiceItem` - Service item resources
- `ViewServiceLabel` / `ShowServiceLabel` - Label resources
- `ViewServicePrice` / `ShowServicePrice` - Price resources

## Common Patterns

### Service with Nested Items
```php
$serviceData = ServiceData::from([
    'name' => 'Health Check Package',
    'price' => 500000,
    'service_items' => [
        [
            'name' => 'Blood Test',
            'price' => 100000,
            'reference_type' => 'lab_test',
            'reference_id' => $bloodTestId,
            'props' => ['flag' => 'ITEM_PACKAGE']
        ],
        [
            'name' => 'X-Ray',
            'price' => 150000,
            'reference_type' => 'radiology',
            'reference_id' => $xrayId,
            'props' => ['flag' => 'ITEM_PACKAGE']
        ]
    ]
]);
```

### Hierarchical Services
```php
// Parent service
$parentService = Service::create([
    'name' => 'Outpatient Services',
    'reference_type' => 'department',
    'reference_id' => $departmentId
]);

// Child service
$childService = Service::create([
    'parent_id' => $parentService->id,
    'name' => 'General Consultation',
    'reference_type' => 'consultation',
    'reference_id' => $consultationId
]);
```

## Integration with Wellmed

This module integrates with:
- **module-transaction** - For payment and billing integration
- **EMR modules** - Medical procedures create services automatically
- **POS modules** - Service pricing for point of sale
- **Pharmacy modules** - Medicine items as service components

## Octane Considerations

When running under Laravel Octane:
- Service instances are resolved fresh per request via contracts
- No static state is stored in Schema classes
- Model relationships use dynamic resolution via `belongsToModel()`, `hasManyModel()` helpers
