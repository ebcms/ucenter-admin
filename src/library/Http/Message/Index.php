<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Http\Message;

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
        if (strlen($request->get('user_id', ''))) {
            $where['user_id'] = $request->get('user_id');
        }
        if (strlen($request->get('is_read', ''))) {
            $where['is_read'] = $request->get('is_read');
        }
        $total = $db->count('ebcms_user_message', $where);

        $page = $request->get('page') ?: 1;
        $pagenum = $request->get('pagenum') ?: 20;
        $where['LIMIT'] = [($page - 1) * $pagenum, $pagenum];
        $where['ORDER'] = [
            'id' => 'DESC',
        ];

        $messages = $db->select('ebcms_user_message', '*', $where);
        foreach ($messages as &$value) {
            $value['user'] = $db->get('ebcms_user_user', '*', [
                'id' => $value['user_id']
            ]);
        }

        return $template->renderFromFile('message/index@ebcms/ucenter-admin', [
            'messages' => $messages,
            'total' => $total,
            'pages' => $pagination->render($page, $total, $pagenum),
        ]);
    }
}
