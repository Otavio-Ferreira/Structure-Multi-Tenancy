<?php

use Stancl\Tenancy\Facades\Tenancy;

function tenant_route_url($name)
{
    return 'http://' . tenant()->domain->domain . ':8000/' . $name;
}
