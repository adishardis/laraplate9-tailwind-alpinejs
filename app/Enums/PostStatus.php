<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Values;
use ArchTech\Enums\Options;

enum PostStatus: string
{
    use Names;
    use Values;
    use Options;

    case PUBLISHED = 'published';
    case DRAFT = 'draft';
}
