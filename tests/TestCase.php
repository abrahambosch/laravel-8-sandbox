<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function tempPath()
    {
        $dir = storage_path('app/temp');
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        return $dir;
    }
}
