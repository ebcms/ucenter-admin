<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Http\Log;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;
use DigPHP\Template\Template;

class Detail extends Common
{
    public function get(
        Db $db,
        Request $request,
        Template $template
    ) {
        if (!$log = $db->get('ebcms_user_log', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('不存在！');
        }

        return $template->renderFromFile('log/detail@ebcms/ucenter-admin', [
            'log' => $log,
        ]);
    }
}
