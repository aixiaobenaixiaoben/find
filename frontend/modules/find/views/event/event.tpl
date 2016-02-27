<div class="row">
    <div class="large-9 medium-8 columns">

        <div class="view">

            <div class="item">
                <div class="title">
                    <h4>{$event.theme}</h4>
                </div>
                <div class="about">
                    <i class="calendar icon"></i>
                    <a class="date">{$event.created_at}</a>
                    <i class="tag icon"></i>
                    {if $event.is_finished}
                        <a class="tag-name" href="/find/event/event-lists/1">已结束</a>
                    {else}
                        <a class="tag-name" href="/find/event/event-lists/0">进行中</a>
                    {/if}
                </div>
            </div>

            <div class="content">
                <table>
                    <tr>
                        <td><h5>紧急程度:</h5></td>
                        <td><h5>{$event.urgent|upper}</h5></td>
                    </tr>
                    <tr>
                        <td><h5>创建者:</h5></td>
                        <td><h5>{$event.user.username}</h5></td>
                    </tr>
                    <tr>
                        <td><h5>发生城市:</h5></td>
                        <td><h5> {$event.city}</h5></td>
                    </tr>
                    <tr>
                        <td><h5>发生时间:</h5></td>
                        <td><h5> {$event.occur_at}</h5></td>
                    </tr>
                    <tr>
                        <td><h5>详情:</h5></td>
                        <td><h5>{$event.description}</h5></td>
                    </tr>


                    <tr>
                        <td><h5>最近出现城市:</h5></td>
                        <td><h5>{$location_new.city}</h5></td>
                    </tr>
                    <tr>
                        <td><h5>最近出现位置:</h5></td>
                        <td><h5>{$location_new.title_from_API}</h5></td>
                    </tr>
                    <tr>
                        <td><h5>最近出现时间:</h5></td>
                        <td><h5>{$location_new.occur_at}</h5></td>
                    </tr>
                    <tr>
                        <td><h5>消息来源:</h5></td>
                        <td><h5>{$provider.identity_kind|upper}</h5></td>
                    </tr>
                    {if $provider.identity_kind=='people'}
                        <tr>
                            <td><h5>消息提供者信息:</h5></td>
                            <td><h5>{$provider.identity_info}</h5></td>
                        </tr>
                    {/if}

                </table>
            </div>


        </div>
    </div>
    <div class="large-3 medium-4 columns">
        <div class="side">
            <div class="row">
                <a href="/user/index/index">
                    <button class=""><h4>首页</h4></button>
                </a>
            </div>
            {if $event.is_finished}
                <div class="row">
                    <a href="/find/event/event-lists/0">
                        <button class=""><h4>进行中事件</h4></button>
                    </a>
                </div>
                <div class="row">
                    <a href="/find/event/event-lists/1">
                        <button class=""><h4>已结束事件</h4></button>
                    </a>
                </div>
                <div class="row">
                    <a href="/find/event/create-event">
                        <button class=""><h4>创建事件</h4></button>
                    </a>
                </div>
                <div class="row">
                    <a href="/find/event/recover-event/{$event.id}">
                        <button class=""><h4>重启该事件</h4></button>
                    </a>
                </div>
            {else}
                <div class="row">
                    <a href="/find/event/view-current-on-map/{$event.id}" target="_blank">
                        <button class=""><h4>查看最近出现位置地图</h4></button>
                    </a>
                </div>
                <div class="row">
                    <a href="/find/event/view-route-on-map/{$event.id}" target="_blank">
                        <button class=""><h4>查看运动轨迹地图</h4></button>
                    </a>
                </div>
                <div class="row">
                    <a href="/find/event/add-location/{$event.id}">
                        <button class=""><h4>添加新结点</h4></button>
                    </a>
                </div>
                {if $event.urgent!='emergency'}
                    <div class="row">
                        <a href="/find/event/raise-urgent-level/{$event.id}">
                            <button class=""><h4>提高紧急等级</h4></button>
                        </a>
                    </div>
                {/if}
                {if $event.urgent!='mild'}
                    <div class="row">
                        <a href="/find/event/moderate-urgent-level/{$event.id}">
                            <button class=""><h4>降低紧急等级</h4></button>
                        </a>
                    </div>
                {/if}
                <div class="row">
                    <a href="/find/event/finish-event/{$event.id}">
                        <button class=""><h4>关闭该事件</h4></button>
                    </a>
                </div>
            {/if}
            <div class="row">
                <a href="/user/index/logout">
                    <button class=""><h4>退出</h4></button>
                </a>
            </div>

        </div>
    </div>
</div>
