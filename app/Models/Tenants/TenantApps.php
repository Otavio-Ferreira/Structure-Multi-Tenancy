<?php

namespace App\Models\Tenants;

use App\Models\Apps\Apps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantApps extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['tenants_id', 'apps_id'];
    
    // public function tenants() : HasMany{
    //     return $this->HasMany(Tenant::class, "tenants_id", "id");
    // }

    // public function apps() : HasMany{
    //     return $this->hasMany(Apps::class, "id", "apps_id");
    // }
}
