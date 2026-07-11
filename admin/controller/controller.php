<?php
function render(string $path, array $data = [])
{
    extract($data);
    $view = __DIR__ . "/../view/" . $path . ".php";
    include_once $view;
}
