<div class="program row">
    <div class="large-9 medium-8 columns">
        <div class="form">

            <div class="contact">
                <input type="hidden" id="csrf" value={$csrf}>

                <label for="name"><h5>*失踪儿童姓名</h5></label>
                <input type="text" id="name">
                <label for="age"><h5>*年龄</h5></label>
                <input type="text" id="age">
                <div class="for-gender">性别&nbsp&nbsp&nbsp
                    男 <input type="radio" name="gender" value="male" checked>&nbsp&nbsp
                    女 <input type="radio" name="gender" value="female">
                </div>
                <br>
                <label for="height"><h5>*身高(厘米)</h5></label>
                <input type="text" id="height">
                <label for="clothes"><h5>*衣着</h5></label>
                <input type="text" id="clothes">
                <label for="appearance"><h5>外貌特征简要描述</h5></label>
                <textarea style="background-color:#2E2938" rows="5" id="appearance"
                          placeholder="the summary of appearance"></textarea>

                <br>
                <label for="theme"><h5>*事件概述</h5></label>
                <input type="text" id="theme">
                <label for="description"><h5>详情</h5></label>
                <textarea style="background-color:#2E2938" rows="5" id="description"
                          placeholder="Describe the event here"></textarea>
                <label for="city"><h5>*所在城市</h5></label>
                <input type="text" id="city">
                <label for="title-from-provider"><h5>*事件具体地点(尽量详细)</h5></label>
                <input type="text" id="title-from-provider">

                <div class="for-urgent-level">紧急程度&nbsp&nbsp&nbsp
                    mild <input type="radio" name="urgent-level" value="mild" checked>&nbsp&nbsp
                    urgent <input type="radio" name="urgent-level" value="urgent">&nbsp&nbsp
                    emergency <input type="radio" name="urgent-level" value="emergency">
                </div>
                <br>

                <label for="datetimepicker"><h5>*事件发生时间</h5></label>
                <input type="text" class="datetimepicker" id="occur_at">
                <label id="create-event-result"><h5>&nbsp</h5></label>

                <div class="bottom-button">
                    <div class="large-6 columns">
                        <a href="/user/index/index">
                            <button><h5>取消</h5></button>
                        </a>
                    </div>
                    <div class="large-6 columns">
                        <button id="create-event"><h5>新建事件</h5></button>
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

