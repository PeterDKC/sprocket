<?php

if (! function_exists('console_line')) { // @codeCoverageIgnore
    /**
     * Returns a separator line for console formatting.
     *
     * @param  int $lineLength
     *
     * @return string
     */
    function console_line(int $lineLength = 20)
    {
        return str_repeat('-', $lineLength);
    }
}
