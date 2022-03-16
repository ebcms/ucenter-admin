<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Http\Log;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Pagination\Pagination;
use DigPHP\Request\Request;
use DigPHP\Template\Template;

class Index extends Common
{
    public function get(
        Db $db,
        Request $request,
        Template $template,
        Pagination $pagination
    ) {
        $where = [];
        if ($request->get('user_id')) {
            $where['user_id'] = $request->get('user_id');
        }
        if ($request->get('type')) {
            $where['type'] = $request->get('type');
        }
        $total = $db->count('ebcms_user_log', $where);

        $page = $request->get('page') ?: 1;
        $pagenum = $request->get('pagenum') ?: 20;
        $where['LIMIT'] = [($page - 1) * $pagenum, $pagenum];
        $where['ORDER'] = [
            'id' => 'DESC',
        ];

        return $template->renderFromFile('log/index@ebcms/ucenter-admin', [
            'logs' => $db->select('ebcms_user_log', '*', $where),
            'total' => $total,
            'pages' => $pagination->render($page, $total, $pagenum),
        ]);
    }
}
