<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(['src', 'test']);

$config = new PhpCsFixer\Config();

$rules = [
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    '@Symfony' => true,
    '@PHP74Migration' => true,
    '@PHP74Migration:risky' => true,
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,

    'date_time_immutable' => true,
    'final_class' => true,
    'protected_to_private' => true,

    'global_namespace_import' => [
        'import_constants' => true,
        'import_functions' => true,
        'import_classes' => true,
    ],

    // assertSame() requires same instance, which is not compatible with functional/immutable paradigm.
    'php_unit_strict' => false,

    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'no_multi_line',
    ],

    'trailing_comma_in_multiline' => [
        'elements' => [
            'arguments',
            'arrays',
            'match',
            'parameters',
        ],
    ],

    'ordered_imports' => [
        'sort_algorithm' => 'alpha',
        'imports_order' => [
            'class',
            'function',
            'const',
        ],
    ],

    'phpdoc_no_empty_return' => false,
    'single_line_empty_body' => true,
    'method_argument_space' => [
        'attribute_placement' => 'standalone',
        'keep_multiple_spaces_after_comma' => false,
        'on_multiline' => 'ensure_fully_multiline',
    ],
];

return $config->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true);
