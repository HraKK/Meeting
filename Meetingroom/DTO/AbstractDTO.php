<?php
namespace Meetingroom\DTO;

/**
 * Class AbstractDTO
 *
 * @author Denis Maximovskikh <denkin.syneforge.com>
 * @package Meetingroom\DTO
 */
class AbstractDTO
{

    /**
     * Bind params
     *
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        foreach ($properties as $field => $value) {
            $this->$field = $value;
        }
    }

} 