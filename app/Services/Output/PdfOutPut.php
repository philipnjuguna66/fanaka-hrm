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

        $html = $htmlWriter->getWriterPart('Body')->write();

        $html =  str($html)
            ->prepend($this->style());

        // Load HTML into Dompdf

        $dompdf->loadHtml($html->toHtmlString());

        // Set paper size and orientation (optional)

        $dompdf->setPaper('A5', 'portrait');

        // Render PDF (output as a string)
        $dompdf->render();

        // Save the PDF to the specified file
        file_put_contents(
            public_path("templates/results/". $this->fileName .".pdf"),
            $dompdf->output()
        );


    }

    private function style()
    {
        ob_start();

        ?>
        <head>
            <meta charset="UTF-8" />
            <title><?= $this->fileName ?></title>
            <meta name="author" content="philip" />
            <style>
                * {
                    font-family: Arial;
                    font-size: 11pt;
                    font-weight: normal; /* Remove bold */
                }

                a.NoteRef {
                    text-decoration: none;
                }

                hr {
                    height: 1px;
                    padding: 0;
                    margin: 1em 0;
                    border: 0;
                    border-top: 1px solid #CCC;
                }

                table {
                    border: 0; /* Set border to 0px */
                    border-spacing: 0px;
                    width: 80%;
                    padding: 0.5px;
                    line-height: 0.4cm;
                }

                td {
                    border: 0 dashed #CCC;
                    padding-left: 1px;
                    padding-right: 1px;
                }

                .Normal {
                    margin-bottom: 8pt;
                }

                .Body Text {
                    font-family: 'Tahoma';
                    font-size: 8pt;
                    font-weight: normal; /* Remove bold */
                }

                .Body Text Char {
                    font-family: 'Tahoma';
                    font-size: 8pt;
                    font-weight: normal; /* Remove bold */
                }

                .Title {
                    font-family: 'Arial';
                    font-size: 8pt;
                    font-weight: normal; /* Remove bold */
                }

                .Title Char {
                    font-family: 'Arial';
                    font-size: 8pt;
                    font-weight: normal; /* Remove bold */
                }

                .header {
                    margin-bottom: 0pt;
                }

                .footer {
                    margin-bottom: 0pt;
                }
            </style>
        </head>

        <?php

        return ob_get_clean();

    }
}
