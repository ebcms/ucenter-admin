<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Http\User;

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
        if ($request->get('state')) {
            $where['state'] = $request->get('state');
        }
        if ($request->get('user_id')) {
            $where['id'] = $request->get('user_id');
        }
        if ($request->get('q')) {
            $where['OR'] = [
                'id' => $request->get('q'),
                'phone[~]' => $request->get('q'),
                'nickname[~]' => $request->get('q'),
                'introduction[~]' => $request->get('q'),
            ];
        }
        $total = $db->count('ebcms_user_user', $where);

        $page = $request->get('page') ?: 1;
        $pagenum = $request->get('pagenum') ?: 20;
        $where['LIMIT'] = [($page - 1) * $pagenum, $pagenum];
        $where['ORDER'] = [
            'id' => 'DESC',
        ];

        return $this->html($template->renderFromFile('user/index@ebcms/ucenter-admin', [
            'users' => $db->select('ebcms_user_user', '*', $where),
            'total' => $total,
            'pages' => $pagination->render($page, $total, $pagenum),
        ]));
    }
}
