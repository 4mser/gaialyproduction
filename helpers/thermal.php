<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('get_temperatures_from_layers')) {
    function get_temperatures_from_layers($layerId)
    {
        $temperatures = [];
        $jsonPath = uploads_path('layers/' . $layerId . '.json');
        if (!file_exists($jsonPath)) {
            Log::error('get_temperatures_from_layers() File not found: ' . $jsonPath);
            return $temperatures;
        }
        $thermoData = file_get_contents($jsonPath);
        $thermoData = json_decode($thermoData, 1);
        if (!is_array($thermoData)) {
            Log::error('get_temperatures_from_layers() > Invalid JSON: ' . $jsonPath);
            return $temperatures;
        }
        $temperatures = $thermoData;
        return $temperatures;
    }
}

if (!function_exists('min_temp')) {
    function min_temp(array $temperatures)
    {
        if (count($temperatures))
            return floatval(number_format(min(array_map('min', $temperatures)), 1));

        return 0;
    }
}

if (!function_exists('max_temp')) {
    function max_temp(array $temperatures)
    {
        if (count($temperatures))
            return floatval(number_format(max(array_map('max', $temperatures)), 1));

        return 0;
    }
}

if (!function_exists('avg_temp')) {
    function avg_temp(array $temperatures)
    {
        if (count($temperatures)) {
            $sum = array_sum(array_map('array_sum', $temperatures));
            return floatval(number_format($sum / (count($temperatures) * count($temperatures[0])), 1));
        }
        return 0;
    }
}

if (!function_exists('dji_camera_models_supported')) {
    function dji_camera_models_supported()
    {
        return [
            'ZH20N',
            'ZH20T',
            'MAVIC2-ENTERPRISE-ADVANCED',
            'M3T',
            'M30T',
            'XT S',
        ];
    }
}

if (!function_exists('dji_has_support')) {
    function dji_has_support($cameraModel)
    {
        return in_array($cameraModel, dji_camera_models_supported());
    }
}
