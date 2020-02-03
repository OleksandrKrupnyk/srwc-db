<?php


namespace zukr\pdf;


use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use zukr\base\Base;

class PdfWrapper
{
    /**
     * @var PdfWrapper
     */
    private static $obj;
    /**
     * @var Mpdf
     */
    public $mpdf;

    public function __construct()
    {
        try {
            $stylesheet = \file_get_contents(Base::$dir->getCSSDir() . '/print.css');
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            $this->mpdf = new Mpdf([
                'fontDir' => \array_merge($fontDirs, [
                    Base::$dir->getFontDir(),
                ]),
                'fontdata' => $fontData + [
                        'timesnewroman' => [
                            'R' => 'times.ttf',
                            'B' => 'timesbd.ttf',
                            'I' => 'timesi.ttf',
                            'BI' => 'timesbi.ttf',
                        ],
                        'unn' => [
                            'R' => 'unn.ttf'
                        ]
                    ],
                'default_font' => 'timesnewroman',
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => '12',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 10,
                'margin_header' => 9,
                'margin_footer' => 9,
                'orientation' => 'P',
            ]);
            $this->mpdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        } catch (MpdfException $e) {
            Base::$log->critical($e->getMessage());
            Go_page('error');
        }

    }

    /**
     * @return PdfWrapper
     */
    public static function getInstance()
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    /**
     * @param string $content
     * @param string $dest
     */
    public function getPdf(string $content, string $dest = Destination::INLINE)
    {
        try {
            // Write some HTML code:
            $this->mpdf->WriteHTML($content, HTMLParserMode::HTML_BODY);
            // Output a PDF file directly to the browser
            $this->mpdf->Output('TestPage', $dest);
        } catch (MpdfException $e) {
            Base::$log->critical($e->getMessage());
            Go_page('error');
        }
    }


}