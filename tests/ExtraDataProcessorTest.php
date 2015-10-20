<?php

namespace Creitive\Monolog\Processor;

use Creitive\Monolog\Processor\ExtraDataProcessor;

class ExtraDataProcessorTest extends \PHPUnit_Framework_TestCase
{
    protected $record = [
        'extra' => [],
    ];

    public function testProcessesExtraDataWhenInvoked()
    {
        $processor = new ExtraDataProcessor([
            'foo' => 'bar',
        ]);

        $record = $processor($this->record);

        $expected = [
            'extra' => [
                'foo' => 'bar',
            ],
        ];

        $this->assertEquals($expected, $record);
    }

    public function testAddsExtraData()
    {
        $processor = new ExtraDataProcessor([
            'foo' => 'bar',
        ]);

        $processor->addExtraData([
            'baz' => 'qux',
        ]);

        $record = $processor($this->record);

        $expected = [
            'extra' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ];

        $this->assertEquals($expected, $record);
    }

    public function testGetsExtraData()
    {
        $extraData = [
            'foo' => 'bar',
        ];

        $processor = new ExtraDataProcessor($extraData);

        $this->assertEquals($extraData, $processor->getExtraData());
    }

    public function testSetsExtraData()
    {
        $extraData = [
            'foo' => 'bar',
        ];

        $processor = new ExtraDataProcessor([
            'baz' => 'qux',
        ]);

        $processor->setExtraData($extraData);

        $record = $processor($this->record);

        $expected = [
            'extra' => [
                'foo' => 'bar',
            ],
        ];

        $this->assertEquals($expected, $record);
    }

    public function testRemovesExtraData()
    {
        $extraData = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $processor = new ExtraDataProcessor($extraData);

        $processor->removeExtraData(['foo']);

        $record = $processor($this->record);

        $expected = [
            'extra' => [
                'baz' => 'qux',
            ],
        ];

        $this->assertEquals($expected, $record);
    }
}
