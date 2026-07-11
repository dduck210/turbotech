<?php

namespace Psr\Log;

/**
 * Stub for Intelephense to resolve undefined type warnings in PHPMailer
 * since psr/log is not vendored in this project.
 */
interface LoggerInterface
{
    public function emergency(string $message, array $context = []);
    public function alert(string $message, array $context = []);
    public function critical(string $message, array $context = []);
    public function error(string $message, array $context = []);
    public function warning(string $message, array $context = []);
    public function notice(string $message, array $context = []);
    public function info(string $message, array $context = []);
    public function debug(string $message, array $context = []);
    public function log(string $level, string $message, array $context = []);
}
