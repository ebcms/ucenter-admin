<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Http\Stats;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;
use DigPHP\Template\Template;

class Index extends Common
{

    public function get(
        Db $db,
        Template $template
    ) {
        $data = [];
        $data['total'] = $db->count('ebcms_user_user');
        return $this->html($template->renderFromFile('stats/index@ebcms/ucenter-admin', $data));
    }

    public function post(
        Db $db,
        Request $request
    ) {
        $month = date('Y-m', strtotime($request->post('month', date('Y-m'))));

        $reg = [];
        $login = [];

        $days = $this->getMonthDays($month);

        for ($i = 0; $i < $days; $i++) {
            $date = $month . '-' . str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT);
            $reg[$date] = $db->count('ebcms_user_log', [
                'type' => 'reg',
                'record_date' => $date,
            ]);
            $login[$date] = $db->count('ebcms_user_log', [
                'type' => 'login',
                'record_date' => $date,
            ]);
        }

        $x = [
            'title' => [
                // 'text' => '趋势统计',
            ],
            'backgroundColor' => '#f5f5f5',
            'legend' => [
                'top' => 20,
                'data' => ['活跃用户', '新注册用户']
            ],
            'grid' => [
                'containLabel' => true
            ],
            'tooltip' => [
                'trigger' => 'axis'
            ],
            'yAxis' => [
                'type' => 'value',
            ],
            'xAxis' => [
                'type' => 'category',
                'boundaryGap' => false,
                'data' => array_keys($login)
            ],
            'series' => [[
                'name' => '新注册用户',
                'type' => 'line',
                'smooth' => true,
                'data' => array_values($reg),
            ], [
                'name' => '活跃用户',
                'type' => 'line',
                'smooth' => true,
                'data' => array_values($login),
            ]]
        ];

        return $this->json($x);
    }

    private function getMonthDays(string $month): int
    {
        return (int)date('d', strtotime(date('Y-m-01 00:00:01', strtotime($month) + 86400 * 32)) - 100);
    }
}
