<div class="program row">
    <div class="large-9 medium-8 columns">
        <div class="form">

            <div class="contact">
                <input type="hidden" id="event_id" value={$event_id}>
                <label for="title"><h5>*发生位置</h5></label>
                <input type="text" id="title" placeholder="XX市XX区XX路XXX号">
                <label for="occur_at"><h5>*发生时间</h5></label>
                <input type="text" id="occur_at" class="datetimepicker">
                <label for="provided_at"><h5>*信息提供时间</h5></label>
                <input type="text" id="provided_at" class="datetimepicker">

                信息来源&nbsp&nbsp&nbsp
                Police <input type="radio" name="identity_kind" value="police" checked>&nbsp&nbsp
                Monitor_System <input type="radio" name="identity_kind" value="monitor_system">&nbsp&nbsp
                People <input type="radio" name="identity_kind" value="people"><br>

                <label for="phone"><h5>提供者手机号码</h5></label>
                <input type="text" id="phone" placeholder="仅当信息来源为People时填写" class="phone">

                <label id="add-location-result"><h5>&nbsp</h5></label>

                <div class="bottom-button">
                    <div class="large-6 columns">
                        <a href="/find/event/event/{$event_id}">
                            <button><h5>取消</h5></button>
                        </a>
                    </div>
                    <div class="large-6 columns">
                        <button id="add-location"><h5>添加新节点</h5></button>
                    </div>
                </div>
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
                <a href="/user/index/logout">
                    <button class=""><h4>退出</h4></button>
                </a>
            </div>
        </div>
    </div>
</div>

