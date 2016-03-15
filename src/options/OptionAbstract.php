<?php

namespace Da\export\options;

use Yii;
use yii\base\Object;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;

abstract class OptionAbstract extends Object implements OptionInterface
{
    use GridViewTrait;

    /**
     * @var ActiveDataProvider dataProvider
     */
    public $dataProvider;

    /**
     * @var array of columns
     */
    public $columns;

    /**
     * @var bool whether to export footer or not
     */
    public $exportFooter = true;

    /**
     * @var int batch size to fetch the data provider
     */
    public $batchSize = 2000;

    /**
     * @var string file path
     */
    public $filePath;

    /**
     * @var string filename without extension
     */
    public $fileName;

    /**
     * @see ExportMenu target consts
     * @var string how the page will delivery the report
     */
    public $target;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->initColumns();
    }

    /**
     * Generate the row array
     *
     * @return array|void
     */
    protected function generateHeader()
    {
        if (empty($this->columns)) {
            return;
        }

        $rowArray = [];
        foreach ($this->columns as $column) {
            /** @var Column $column */
            $head = ($column instanceof DataColumn) ? $this->getColumnHeader($column) : $column->header;
            $rowArray[] = $head;
        }
        return $rowArray;
    }

    /**
     * Fetch data from the data provider and create the rows array
     *
     * @return array|void
     */
    protected function generateBody()
    {
        if (empty($this->columns)) {
            return;
        }

        $rows = [];
        $query = $this->dataProvider->query;
        foreach ($query->batch($this->batchSize) as $models) {
            /**
             * @var int $index
             * @var \yii\db\ActiveRecord $model
             */
            foreach ($models as $index => $model) {
                $key = $model->getPrimaryKey();
                $rows[] = $this->generateRow($model, $key, $index);
            }
        }

        return $rows;
    }

    /**
     * Generate the row array
     *
     * @param $model
     * @param $key
     * @param $index
     * @return array
     */
    protected function generateRow($model, $key, $index)
    {
        $row = [];
        foreach ($this->columns as $column) {
            $value = $this->getColumnValue($model, $key, $index, $column);
            $row[] = $value;
        }
        return $row;
    }

    /**
     * Get the column generated value from the column
     *
     * @param $model
     * @param $key
     * @param $index
     * @param $column
     * @return string
     */
    protected function getColumnValue($model, $key, $index, $column)
    {
        /** @var Column $column */
        if ($column instanceof ActionColumn || $column instanceof CheckboxColumn) {
            return '';
        } else if ($column instanceof DataColumn) {
            return $column->getDataCellValue($model, $key, $index);
        } else if ($column instanceof Column) {
            return $column->renderDataCell($model, $key, $index);
        }

        return '';
    }

    /**
     * generate footer row array
     *
     * @return array|void
     */
    protected function generateFooter()
    {
        if (!$this->exportFooter) {
            return;
        }

        if (empty($this->columns)) {
            return;
        }

        $rowsArray = [];
        foreach ($this->columns as $n => $column) {
            /** @var Column $column */
            $rowsArray[] = trim($column->footer) !== '' ? $column->footer : '';
        }
        return $rowsArray;
    }
}