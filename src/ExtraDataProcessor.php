<?php

namespace Creitive\Monolog\Processor;

/**
 * Injects arbitrary extra data in all records.
 *
 * @author Miloš Levačić <milos@levacic.net>
 */
class ExtraDataProcessor
{
    /**
     * The currently configured extra data.
     *
     * @var array
     */
    protected $extraData = [];

    /**
     * Create a new processor instance.
     *
     * @param array $extraData Extra data to be added
     */
    public function __construct(array $extraData = [])
    {
        $this->setExtraData($extraData);
    }

    /**
     * Magic method for instance invokation as a function.
     *
     * Merges the passed record's `extra` entry with the configured extra data
     * (overwriting existing keys), and returns the record.
     *
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['extra'] = $this->appendExtraFields($record['extra']);

        return $record;
    }

    /**
     * Adds the extra data into the passed array.
     *
     * @param array $extra
     * @return array
     */
    private function appendExtraFields(array $extra)
    {
        foreach ($this->extraData as $key => $value) {
            $extra[$key] = $value;
        }

        return $extra;
    }

    /**
     * Sets all extra data.
     *
     * @param array $extraData
     */
    public function setExtraData(array $extraData = [])
    {
        $this->extraData = $extraData;
    }

    /**
     * Returns the currently configured extra data.
     *
     * @return array
     */
    public function getExtraData()
    {
        return $this->extraData;
    }

    /**
     * Adds more extra data into the processor.
     *
     * Overwrites existing data without warning.
     *
     * @param array $extraData
     * @return void
     */
    public function addExtraData(array $extraData = [])
    {
        foreach ($extraData as $key => $data) {
            $this->extraData[$key] = $data;
        }
    }

    /**
     * Removes the passed extra data keys.
     *
     * @param array $extraDataKeys
     * @return void
     */
    public function removeExtraData(array $extraDataKeys = [])
    {
        foreach ($extraDataKeys as $key) {
            if (array_key_exists($key, $this->extraData)) {
                unset($this->extraData[$key]);
            }
        }
    }
}
