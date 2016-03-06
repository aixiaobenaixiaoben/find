<div class="row">
    <div class="large-9 medium-8 columns">
        <div class="form">

            <div class="contact">
                <input type="hidden" id="csrf" value={$csrf}>
                <label for="email"><h5>新邮箱</h5></label>
                <input type="text" id="email">
                <label for="dynamic-key"><h5>动态口令</h5></label>
                <input type="password" id="dynamic-key">
                <label id="change-email-result"><h5>&nbsp</h5></label>

                <div class="bottom-button">
                    <div class="large-6 columns">
                        <button id="send-dynamic-key"><h5>发送动态口令</h5></button>
                    </div>
                    <div class="large-6 columns">
                        <button id="change-email"><h5>更改邮箱</h5></button>
                    </div>
                </div>
                <br><br><br><br>
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
                <a href="/user/index/profile">
                    <button class=""><h4>个人中心</h4></button>
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

