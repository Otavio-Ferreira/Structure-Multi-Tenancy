<?php

namespace App\Models\Tenants;

use App\Models\Apps\Apps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Domain;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasUuids;

    protected $fillable = ["name", "tenancy_db_name", "tenancy_db_user", "tenancy_db_password", "tenancy_db_port", "tenancy_db_host", "data"];
    protected $table = 'tenants';

    public function domain() : HasOne
    {
        return $this->hasOne(Domain::class, "tenant_id", "id");
    }

    public function apps()
    {
        return $this->belongsToMany(Apps::class, 'tenant_apps', 'tenants_id', 'apps_id');
    }
}