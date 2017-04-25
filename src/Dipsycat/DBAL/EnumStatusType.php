<?php

namespace Dipsycat\DBAL;

class EnumStatusType extends EnumType {
    protected $name = 'enumstatus';
    protected $values = [
        'open',
        'in progress',
        'closed'
    ];
}
