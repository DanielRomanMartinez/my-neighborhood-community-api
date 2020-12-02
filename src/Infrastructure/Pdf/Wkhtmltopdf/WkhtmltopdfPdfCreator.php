<?php

declare(strict_types=1);

namespace App\Infrastructure\Pdf\Wkhtmltopdf;

use App\Infrastructure\Storage\LocalStorage;
use App\Infrastructure\Template\TemplateInterface;
use App\Shared\Domain\PdfGenerator\PdfCreator;
use Knp\Snappy\Pdf;

final class WkhtmltopdfPdfCreator implements PdfCreator
{
    private const PDF_BIN = '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64';
    private const DEFAULT_MARGIN = '1in';
    private const UNIT_LENGTH = 'in';

    private TemplateInterface $template;
    private LocalStorage $storage;

    public function __construct(TemplateInterface $template, LocalStorage $storage)
    {
        $this->template = $template;
        $this->storage = $storage;
    }

    public function __invoke(
        string $htmlContent,
        string $outputFile,
        ?string $marginLeft,
        ?string $marginRight,
        ?string $marginTop,
        ?string $marginBottom,
        bool $showPagination
    ): void {
        $html = $this->renderHtml($htmlContent);

        $snappy = new Pdf($this->pdfBinary());
        $snappy->setOptions([
            'no-background'           => true,
            'page-size'               => 'Letter',
            'disable-smart-shrinking' => true,
            'margin-top'              => (!empty($marginTop)) ? $marginTop . self::UNIT_LENGTH : self::DEFAULT_MARGIN,
            'margin-right'            => (!empty($marginRight)) ? $marginRight . self::UNIT_LENGTH : self::DEFAULT_MARGIN,
            'margin-bottom'           => (!empty($marginBottom)) ? $marginBottom . self::UNIT_LENGTH : self::DEFAULT_MARGIN,
            'margin-left'             => (!empty($marginLeft)) ? $marginLeft . self::UNIT_LENGTH : self::DEFAULT_MARGIN,
            'no-header-line'          => true,
            'no-footer-line'          => true,
            'encoding'                => 'utf-8',
        ]);

        if ($showPagination) {
            $snappy->setOption('footer-center', 'Page [page] of [topage]');
            $snappy->setOption('footer-font-name', 'Liberation Serif');
            $snappy->setOption('footer-font-size', '12');
            $snappy->setOption('footer-spacing', '5');
        }

        $pdfContent = $snappy->getOutputFromHtml($html);
        $this->storage->uploadContent($outputFile, $pdfContent);
    }

    private function renderHtml(string $content): string
    {
        return $this->template->render('pdf/base.html.twig', [
            'content' => $content,
        ]);
    }

    private function pdfBinary(): string
    {
        return dirname(__DIR__) . '/../../../' . self::PDF_BIN;
    }
}
