#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Mapping as ORM;

$argv = $_SERVER['argv'];
array_shift($argv); // remove script name

$hasTimestamps = false;
if (in_array('--timestamps', $argv)) {
    $hasTimestamps = true;
    $argv = array_filter($argv, fn($v) => $v !== '--timestamps');
    $argv = array_values($argv); // Re-index
}

if (count($argv) < 1) {
    echo "Usage: php bin/make-entity.php NomEntity champ:type [...]\n";
    echo "Option: --timestamps => ajoute createdAt & updatedAt\n";
    exit(1);
}

$entityName = ucfirst(array_shift($argv));
$fields = [];

foreach ($argv as $arg) {
    if (!str_contains($arg, ':')) {
        echo "Invalid field format: $arg\n";
        exit(1);
    }

    [$name, $type] = explode(':', $arg);
    $fields[] = ['name' => $name, 'type' => $type];
}

$namespace = "App_citations\\Entities";
$classCode = "<?php\n\nnamespace $namespace;\n\nuse Doctrine\ORM\Mapping as ORM;\n\n";
$classCode .= "#[ORM\Entity]\nclass $entityName\n{\n";
$classCode .= "    #[ORM\\Id]\n    #[ORM\\GeneratedValue]\n    #[ORM\\Column(type: 'integer')]\n    private ?int \$id = null;\n\n";

// Champs normaux
foreach ($fields as $field) {
    $phpType = match ($field['type']) {
        'string' => 'string',
        'int', 'integer' => 'int',
        'bool', 'boolean' => 'bool',
        'float' => 'float',
        default => 'string',
    };

    $doctrineType = match ($field['type']) {
        'string' => "type: 'string', length: 255",
        'int', 'integer' => "type: 'integer'",
        'bool', 'boolean' => "type: 'boolean'",
        'float' => "type: 'float'",
        default => "type: 'string'",
    };

    $name = $field['name'];
    $classCode .= "    #[ORM\\Column($doctrineType)]\n    private $phpType \$$name;\n\n";
}

// Timestamps
if ($hasTimestamps) {
    $classCode .= "    #[ORM\\Column(type: 'datetime_immutable')]\n    private \\DateTimeImmutable \$createdAt;\n\n";
    $classCode .= "    #[ORM\\Column(type: 'datetime_immutable', nullable: true)]\n    private ?\\DateTimeImmutable \$updatedAt = null;\n\n";
}

// Getters/setters
$classCode .= "    public function getId(): ?int\n    {\n        return \$this->id;\n    }\n\n";

foreach ($fields as $field) {
    $phpType = match ($field['type']) {
        'string' => 'string',
        'int', 'integer' => 'int',
        'bool', 'boolean' => 'bool',
        'float' => 'float',
        default => 'string',
    };

    $name = $field['name'];
    $camel = ucfirst($name);

    $classCode .= "    public function get$camel(): $phpType\n    {\n        return \$this->$name;\n    }\n\n";
    $classCode .= "    public function set$camel($phpType \$$name): self\n    {\n        \$this->$name = \$$name;\n        return \$this;\n    }\n\n";
}

// Getters/setters timestamps
if ($hasTimestamps) {
    $classCode .= "    public function getCreatedAt(): \\DateTimeImmutable\n    {\n        return \$this->createdAt;\n    }\n\n";
    $classCode .= "    public function setCreatedAt(\\DateTimeImmutable \$createdAt): self\n    {\n        \$this->createdAt = \$createdAt;\n        return \$this;\n    }\n\n";

    $classCode .= "    public function getUpdatedAt(): ?\\DateTimeImmutable\n    {\n        return \$this->updatedAt;\n    }\n\n";
    $classCode .= "    public function setUpdatedAt(?\\DateTimeImmutable \$updatedAt): self\n    {\n        \$this->updatedAt = \$updatedAt;\n        return \$this;\n    }\n\n";
}

$classCode .= "}\n";

// Enregistrement
$targetDir = __DIR__ . '/../app/entities';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

file_put_contents("$targetDir/$entityName.php", $classCode);
echo "✅ Entité $entityName générée dans src/Entity/$entityName.php\n";

if ($hasTimestamps) {
    echo "⏱️ Timestamps (createdAt, updatedAt) ajoutés.\n";
}
