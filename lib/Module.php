<?php

namespace vkolesnev_formevent\base;

class Module
{

    /**
     * @return self
     */
    public static function instance()
    {
        static $instance;

        if ($instance === null) {
            $instance = new self();
        }

        return $instance;
    }
}

