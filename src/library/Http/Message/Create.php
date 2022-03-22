<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Http\Message;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Component\Row;
use DigPHP\Form\Field\Summernote;
use DigPHP\Request\Request;
use DigPHP\Router\Router;

class Create extends Common
{
    public function get(
        Router $router,
        Request $request
    ) {

        $form = new Builder('发送站内信');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    new Input('用户ID', 'user_id', $request->get('user_id')),
                    new Input('标题', 'title'),
                    new Summernote('内容', 'body', '', $router->build('/ebcms/admin/upload'))
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        $db->insert('ebcms_user_message', [
            'user_id' => $request->post('user_id'),
            'title' => $request->post('title'),
            'body' => $request->post('body'),
            'send_time' => time(),
        ]);
        return $this->success('操作成功！');
    }
}
