<?php

namespace Modules\Permission\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Permission
{
    public function __construct(public string $name)
    {
    }
}
