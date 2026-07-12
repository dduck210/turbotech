<?php

/**
 * Escape a value for safe HTML output. The single authoritative XSS
 * defense point for every view — call at echo time, never on write.
 */
function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}
