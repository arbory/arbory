<?php

if (! function_exists('vite_asset')) {
    function vite_asset($path, $manifestDirectory)
    {
        $manifestPath = public_path($manifestDirectory . '/manifest.json');

        if (! file_exists($manifestPath)) {
            throw new Exception('The Vite manifest file does not exist.');
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (! isset($manifest[$path])) {
            throw new Exception("Unable to locate Vite asset: {$path}.");
        }

        return asset($manifestDirectory . '/' . $manifest[$path]['file']);
    }
}
