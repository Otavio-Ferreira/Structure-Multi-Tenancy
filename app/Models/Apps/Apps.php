<?php

namespace App\Models\Apps;

use App\Models\Tenants\Tenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apps extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'controller', 'color', 'status'];
    protected $table = 'apps';

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_apps', 'apps_id', 'tenants_id');
    }
}
