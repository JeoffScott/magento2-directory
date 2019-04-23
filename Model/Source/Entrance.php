<?php
namespace SR\Directory\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Entrance  extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => '', 'label' => __('Please Select')],
                ['value' => 'א', 'label' => __('א')],
                ['value' => 'ב', 'label' => __('ב')],
                ['value' => 'ג', 'label' => __('ג')],
                ['value' => 'ד', 'label' => __('ד')],
                ['value' => 'ה', 'label' => __('ה')],
                ['value' => 'ו', 'label' => __('ו')],
                ['value' => 'ז', 'label' => __('ז')],
                ['value' => 'ח', 'label' => __('ח')],
                ['value' => 'ט', 'label' => __('ט')],
                ['value' => 'י', 'label' => __('י')]
            ];
        }
        return $this->_options;
    }

    /**
     * Get text of the option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionValue($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

}