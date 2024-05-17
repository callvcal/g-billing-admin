<?php

namespace App\Admin\Extensions;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid\Displayers\AbstractDisplayer;

class Popover extends AbstractDisplayer
{
    public function display($placement = 'left')
    {
        if (empty($this->value)) {
            $this->value = " "; // bootstrap 5.0.2 bug: not allows empty string
        }

        return <<<EOT
<button type="button"
    class="btn btn-secondary"
    title="Title"
    data-bs-container="body"
    data-bs-toggle="popover"
    data-bs-placement="$placement"
    data-bs-content='{$this->value}'>
  Popover
</button>
EOT;
    }
}