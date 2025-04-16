<?php

require_once 'vendor/autoload.php';
require_once 'config/bootstrap.php';

use App_citations\Entities\Citation;

$citation = new Citation();

var_dump($citation->getCreatedAt());

$meta = $entityManager->getClassMetadata(\App_citations\Entities\Citation::class);
var_dump($meta->fieldMappings['createdAt']);
