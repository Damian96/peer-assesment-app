<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $key The environment key to update
     * @param string $newValue The new value to set
     * @param string $delimeter
     * @return void
     */
    protected function updateDotEnv($key, $newValue, $delimeter = '')
    {
        $path = base_path('.env');
        // get old value from current env
        $oldValue = env($key);

        // was there any change?
        if ($oldValue === $newValue) {
            return;
        }

        // rewrite file content with changed data
        if (file_exists($path)) {
            // replace current value with new value
            file_put_contents(
                $path, str_replace(
                    $key . '=' . $delimeter . $oldValue . $delimeter,
                    $key . '=' . $delimeter . $newValue . $delimeter,
                    file_get_contents($path)
                )
            );
        }
    }
}
