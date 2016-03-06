<div class="row">
    <div class="large-9 medium-8 columns">
        <div class="form">

            <div class="contact">
                <input type="hidden" id="csrf" value={$csrf}>
                <label for="old-password"><h5>旧密码</h5></label>
                <input type="password" id="old-password">
                <label for="new-password"><h5>新密码</h5></label>
                <input type="password" id="new-password">
                <label for="password-confirm"><h5>确认新密码</h5></label>
                <input type="password" id="password-confirm">
                <label id="change-password-result"><h5>&nbsp</h5></label>

                <div class="bottom-button">
                    <div class="large-6 columns">
                        <a href="/user/index/profile">
                            <button><h5>取消</h5></button>
                        </a>
                    </div>
                    <div class="large-6 columns">
                        <button id="change-password"><h5>重设密码</h5></button>
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

