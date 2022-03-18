<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Http\User;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Router\Router;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Cover;
use DigPHP\Form\Field\Hidden;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Field\Radio;
use DigPHP\Form\Field\Textarea;
use DigPHP\Form\Component\Row;
use DigPHP\Request\Request;

class Edit extends Common
{
    public function get(
        Db $db,
        Router $router,
        Request $request
    ) {
        $user = $db->get('ebcms_user_user', '*', [
            'id' => $request->get('id', 0),
        ]);

        $form = new Builder('编辑用户信息');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Hidden('id', $user['id'])),
                    (new Cover('头像', 'avatar', $user['avatar'], $router->build('/ebcms/admin/upload'))),
                    (new Input('电话号码', 'phone', $user['phone'])),
                    (new Radio('状态', 'state', $user['state'], [
                        '1' => '正常',
                        '2' => '禁止登陆',
                        '99' => '待审核',
                    ]))
                ),
                (new Col('col-md-9'))->addItem(
                    (new Input('昵称', 'nickname', $user['nickname'])),
                    (new Textarea('个人说明', 'introduction', $user['introduction']))
                )
            )
        );
        return $this->html($form->__toString());
    }

    public function post(
        Request $request,
        Db $db
    ) {
        $user = $db->get('ebcms_user_user', '*', [
            'id' => $request->post('id', 0),
        ]);

        $update = array_intersect_key($request->post(), [
            'avatar' => '',
            'nickname' => '',
            'introduction' => '',
            'phone' => '',
            'state' => '',
        ]);
        $update = array_merge($user, $update);

        $db->update('ebcms_user_user', $update, [
            'id' => $user['id'],
        ]);

        return $this->success('操作成功！');
    }
}
