{include common/header@ebcms/admin}
<div class="container">
    <div class="my-4 h1">用户消息</div>
    <div class="my-4">
        <a class="btn btn-primary" href="{:$router->build('/ebcms/ucenter-admin/message/create')}">发送消息</a>
    </div>
    <div class="my-4">
        <form id="form_filter" class="row row-cols-auto gy-2 gx-3 align-items-center mb-3" action="{echo $router->build('/ebcms/ucenter-admin/message/index')}" method="GET">

            <div class="col-auto">
                <label class="visually-hidden">分页大小</label>
                <select class="form-select" name="page_num" onchange="document.getElementById('form_filter').submit();">
                    <option {if $request->get('page_num')=='20' }selected{/if} value="20">20</option>
                    <option {if $request->get('page_num')=='50' }selected{/if} value="50">50</option>
                    <option {if $request->get('page_num')=='100' }selected{/if} value="100">100</option>
                    <option {if $request->get('page_num')=='500' }selected{/if} value="500">500</option>
                </select>
            </div>

            <div class="col">
                <label class="visually-hidden">状态</label>
                <select class="form-select" name="is_read" onchange="document.getElementById('form_filter').submit();">
                    <option {if $request->get('is_read')=='' }selected{/if} value="">全部</option>
                    <option {if $request->get('is_read')=='0' }selected{/if} value="0">未读</option>
                    <option {if $request->get('is_read')=='1' }selected{/if} value="1">已读</option>
                </select>
            </div>

            <div class="col-auto">
                <label class="visually-hidden">用户ID</label>
                <input type="search" class="form-control" placeholder="用户ID" name="user_id" value="{:$request->get('user_id')}" onchange="document.getElementById('form_filter').submit();">
            </div>
        </form>
    </div>
    <div class="table-responsive my-4">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-nowrap">用户</th>
                    <th class="text-nowrap">标题</th>
                    <th class="text-nowrap">发送时间</th>
                    <th class="text-nowrap">阅读时间</th>
                </tr>
            </thead>
            <tbody>
                {foreach $messages as $v}
                <tr>
                    <td>{$v.id}</td>
                    <td>{$v['user']['nickname']}</td>
                    <td>{$v.title}</td>
                    <td class="text-nowrap">{:date('Y-m-d H:i:s', $v['send_time'])}</td>
                    <td class="text-nowrap">
                        {if $v['is_read']}
                        {:date('Y-m-d H:i:s', $v['read_time'])}
                        {else}
                        <span class="text-warning">未读</span>
                        {/if}
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <nav>
        <ul class="pagination">
            {foreach $pages as $v}
            {if $v=='...'}
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
            {elseif isset($v['current'])}
            <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
            {else}
            <li class="page-item"><a class="page-link" href="{echo $router->build('/ebcms/ucenter-admin/user/index')}?page={$v.page}">{$v.page}</a></li>
            {/if}
            {/foreach}
        </ul>
    </nav>
</div>
{include common/footer@ebcms/admin}