<?php

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]]);
    $services->set(MethodArgumentSpaceFixer::class)
        ->call('configure', [[
            'on_multiline' => 'ensure_fully_multiline',
        ]]);
    $services->set(NativeFunctionInvocationFixer::class)
        ->call('configure', [[
            'scope' => 'namespaced',
            'include' => ['@compiler_optimized']
        ]]);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src']);

    $parameters->set(Option::SKIP, [
        // skip paths with legacy code
        __DIR__ . '/src/Migrations',
        __DIR__ . '/tests'
    ]);
};
