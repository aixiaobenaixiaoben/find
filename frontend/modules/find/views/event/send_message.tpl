<div class="program row">
    <div class="large-9 medium-8 columns">
        <div class="form">

            <div class="contact">
                <input type="hidden" id="csrf" value={$csrf}>
                <input type="hidden" id="event_id" value={$event.id}>
                <input type="hidden" id="profile_id" value={$profile.id}>
                <input type="hidden" id="location_current_id" value={$current.id}>

                <label for="name"><h5>*失踪儿童姓名</h5></label>
                <input type="text" id="name" value={$profile.name}>
                <label for="age"><h5>*年龄</h5></label>
                <input type="text" id="age" value={$profile.age}>
                <div class="for-gender">性别&nbsp&nbsp&nbsp
                    男 <input type="radio" name="gender" value="male" {if $profile.gender=='male'}checked{/if} >&nbsp&nbsp
                    女 <input type="radio" name="gender" value="female" {if $profile.gender=='female'}checked{/if}>
                </div>
                <br>
                <label for="height"><h5>*身高(厘米)</h5></label>
                <input type="text" id="height" value={$profile.height}>
                <label for="clothes"><h5>*衣着</h5></label>
                <input type="text" id="clothes" value={$profile.dress}>
                <label for="appearance"><h5>外貌特征简要描述</h5></label>
                <textarea style="background-color:#2E2938" rows="5" id="appearance"
                        {if $profile.appearance==''} placeholder="the summary of appearance"{/if}>{$profile.appearance}</textarea>

                <label for="city"><h5>*所在城市</h5></label>
                <input type="text" id="city" value={$event.city}>
                <label for="location"><h5>*具体位置(尽量详细)</h5></label>
                <input type="text" id="location" value={$current.title}>

                <label id="send-message-result"><h5>&nbsp</h5></label>

                <div class="bottom-button">
                    <div class="large-6 columns">
                        <a href="/find/event/event/{$event.id}">
                            <button><h5>取消</h5></button>
                        </a>
                    </div>
                    <div class="large-6 columns">
                        <button id="send-message"><h5>确认发送短信</h5></button>
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

