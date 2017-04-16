<?php

namespace Dipsycat\DBAL;

class EnumStatusType extends EnumType {
    protected $name = 'status';
    protected $values = [
        'open',
        'in progress',
        'closed'
    ];
}
