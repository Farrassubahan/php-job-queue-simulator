<?php

require_once __DIR__ . '/JobProcessor/GenerateReportJob.php';
require_once __DIR__ . '/JobProcessor/SendEmailJob.php';

class ProcessorFactory
{
    public static function make(string $type)
    {
        switch ($type) {

            case 'heavy_task':
                return new GenerateReportJob();

            case 'light_task':
                return new SendEmailJob();

            default:
                throw new Exception("Processor tidak ditemukan untuk job type: {$type}");
        }
    }
}
