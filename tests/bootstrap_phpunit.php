<?php
// ==============================================================
// own phpunit bootstrapper, needed to generate code coverage
// without touching the laravel sources
// taken from laravel/cli/tasks/test/*
// ==============================================================
// passthru('pwd');
// exit(-1);

// --------------------------------------------------------------
// Define the directory separator for the environment.
// --------------------------------------------------------------
define('DS', DIRECTORY_SEPARATOR);

// --------------------------------------------------------------
// Set the core Laravel path constants.
// --------------------------------------------------------------
require '../../paths.php';

// --------------------------------------------------------------
// Bootstrap the Laravel core.
// --------------------------------------------------------------
require path('sys') . 'core.php';

// --------------------------------------------------------------
// Start the default bundle.
// --------------------------------------------------------------
Laravel\Bundle::start(DEFAULT_BUNDLE);