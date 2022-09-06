<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum PostStatus: string
{
    use Names;
    use Values;
    use Options;

    case PUBLISHED = 'published';
    case DRAFT = 'draft';
}
