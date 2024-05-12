<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role AS ModelsRole;

class Role extends ModelsRole
{
  use HasFactory;
}