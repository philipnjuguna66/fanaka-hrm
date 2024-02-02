<?php

namespace App\Services\Output;


use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class PdfOutPut
{
    public static function make( string $filePath,  string $fileName)
    {
        return (new self(filePath: $filePath, fileName:  $fileName));
    }
    public function __construct(private readonly string $filePath, private readonly string $fileName)
    {}

    public function output()  : void
    {

        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);

        Settings::setPdfRendererPath('.');

        $phpWord = IOFactory::load($this->filePath, 'Word2007');

        $phpWord->save(public_path("templates/results/". $this->fileName."_payslip.pdf"), 'PDF');

    }
}
