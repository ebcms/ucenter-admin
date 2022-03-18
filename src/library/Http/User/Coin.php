<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Http\User;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\UcenterAdmin\Model\Log;
use DigPHP\Database\Db;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Field\Textarea;
use DigPHP\Form\Component\Row;
use DigPHP\Request\Request;

class Coin extends Common
{
    public function get(
        Request $request
    ) {

        $form = new Builder('金币操作');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Input('用户ID', 'user_id', $request->get('user_id', 0))),
                    (new Input('金币数量', 'num', 0, ['type' => 'number'])),
                    (new Textarea('原因', 'tips'))
                ),
                (new Col('col-md-9'))->addItem()
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Log $logModel,
        Db $db
    ) {
        $coin = $request->post('num', 0);
        if ($coin == 0) {
            return $this->error('参数错误~');
        }

        $user = $db->get('ebcms_user_user', '*', [
            'id' => $request->post('user_id', 0),
        ]);

        if ($coin + $user['coin'] < 0) {
            return $this->error('数量不足！');
        }


        if ($coin > 0) {
            $db->update('ebcms_user_user', [
                'coin[+]' => $coin,
            ], [
                'id' => $user['id'],
            ]);
        } elseif ($coin < 0) {
            $db->update('ebcms_user_user', [
                'coin[-]' => abs($coin),
            ], [
                'id' => $user['id'],
            ]);
        }
        $logModel->record($user['id'], 'coin', [
            'coin' => $coin,
            'tips' => $request->post('tips'),
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}