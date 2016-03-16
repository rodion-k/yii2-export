<?php

namespace tests;

use Da\export\ExportMenu;
use Da\export\queue\beanstalkd\BeanstalkdQueueStoreAdapter;
use tests\dummy\TestController;
use Yii;

class ExportMenuTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testWidget()
    {
        Yii::$app->controller = new TestController('test', Yii::$app);

        $actual = ExportMenu::widget([]);
        $expected = file_get_contents(__DIR__ . '/data/test-form.bin');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenQueueNameIsNotSet()
    {
        ExportMenu::widget([
            'target' => ExportMenu::TARGET_QUEUE,
            'queueConfig' => [

            ]
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenQueueAdapterIsNotSet()
    {
        ExportMenu::widget([
            'target' => ExportMenu::TARGET_QUEUE,
            'queueConfig' => [
                'queueName' => 'test'
            ]
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenQueueMessageIsNotSet()
    {
        ExportMenu::widget([
            'target' => ExportMenu::TARGET_QUEUE,
            'queueConfig' => [
                'queueName' => 'test',
                'queueAdapter' => BeanstalkdQueueStoreAdapter::className(),
            ]
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenQueueAdapterIsSetWrong()
    {
        ExportMenu::widget([
            'target' => ExportMenu::TARGET_QUEUE,
            'queueConfig' => [
                'queueName' => 'test',
                'queueAdapter' => 'test',
            ]
        ]);
    }
}