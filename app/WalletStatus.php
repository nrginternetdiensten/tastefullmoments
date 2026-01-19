<?php

namespace App;

enum WalletStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Closed = 'closed';
}
