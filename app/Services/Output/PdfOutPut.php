<?php

namespace App\Services\Output;


use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\Writer\HTML;


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

        // Load the Word document using PhpWord
        $phpWord = IOFactory::load($this->filePath);

        // Set Dompdf as the PDF renderer
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath('.');

        // Create Dompdf options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf();


        $htmlWriter = new HTML($phpWord);



        // Load HTML into Dompdf
        $dompdf->loadHtml($htmlWriter->getContent());

        // Set paper size and orientation (optional)

        $dompdf->setPaper('A5', 'portrait');

        // Render PDF (output as a string)
        $dompdf->render();

        // Save the PDF to the specified file
        file_put_contents(
            public_path("templates/results/". $this->fileName ."_payslip.pdf"),
            $dompdf->output()
        );


    }
}
