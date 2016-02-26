<div class="program row">
    <div class="large-9 medium-8 columns">
        <div class="board">

            <div class="tag-name">
                <i class="large tag icon"></i>
                <h4>{if $is_finish}已结束事件{else}进行中事件{/if}</h4>
            </div>

            {foreach $events as $event}
                <div class="item">
                    <div class="title">
                        <a href="/find/event/event/{$event.id}"><h5>{$event.theme}</h5></a>
                    </div>
                    <div class="content-summary">
                        <h5>Urgent: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{$event.urgent}</h5>
                        <h5>Occur At: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{$event.occur_at}</h5>
                    </div>
                    <div class="about">
                        <div class="large-5 columns">
                            <i class="calendar icon"></i>
                            <a class="date">{$event.created_at}</a>
                        </div>
                        <div class="large-7 columns">
                            <i class="pointing right icon"></i>
                            <a class="link"
                               href="/find/event/event/{$event.id}">https://find.forfreedomandlove.com/{$event.id}</a>
                        </div>
                    </div>
                </div>
            {/foreach}


        </div>
    </div>
    <div class="large-3 medium-4 columns">
        <div class="side">
            <div class="row">
                <a href="/user/index/index">
                    <button class=""><h4>首页</h4></button>
                </a>
            </div>
            <div class="row">
                <a href="/find/event/create-event">
                    <button class=""><h4>创建事件</h4></button>
                </a>
            </div>
            {if $is_finish}
                <div class="row">
                    <a href="/find/event/event-lists/0">
                        <button class=""><h4>进行中事件</h4></button>
                    </a>
                </div>
            {else}
                <div class="row">
                    <a href="/find/event/event-lists/1">
                        <button class=""><h4>已结束事件</h4></button>
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