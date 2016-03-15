<?php

namespace Da\export\options;

use Yii;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Da\export\ExportMenu;

class XlsxOption extends OptionAbstract
{
    public $extension = '.xlsx';

    /**
     * @inheritdoc
     */
    public function process()
    {
        //XLSX object initialization
        $spoutObject = WriterFactory::create(Type::XLSX);
        switch ($this->target) {
            case ExportMenu::TARGET_SELF:
            case ExportMenu::TARGET_BLANK:
                Yii::$app->controller->layout = false;
                $spoutObject->openToBrowser($this->filePath . $this->extension);
                break;
            case ExportMenu::TARGET_QUEUE:
            default:
                Yii::$app->controller->layout = false;
                $spoutObject->openToBrowser($this->filePath . $this->extension);
                break;
        }

        //header
        $headerRow = $this->generateHeader();
        if (!empty($headerRow)) {
            $spoutObject->addRow($headerRow);
        }

        //body
        $bodyRows = $this->generateBody();
        foreach ($bodyRows as $row) {
            $spoutObject->addRow($row);
        }

        //footer
        $footerRow = $this->generateFooter();
        if (!empty($footerRow)) {
            $spoutObject->addRow($footerRow);
        }

        $spoutObject->close();
    }
}