<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TenantUsersApps extends Model
{
    use HasUuids;

    protected $fillable = ['tenant_apps_id', 'tenant_user_id'];
}
