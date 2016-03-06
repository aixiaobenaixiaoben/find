<div class="program row">
    <div class="large-9 medium-8 columns">
        <div class="form">

            <div class="contact">
                <input type="hidden" id="csrf" value={$csrf}>
                <label for="email"><h5>收件邮箱</h5></label>
                <input type="text" class="input-class" id="email">
                <label for="subject"><h5>主题</h5></label>
                <input type="text" class="input-class" id="subject">
                <label for="content"><h5>留言</h5></label>
                <textarea style="background-color:#2E2938" rows="5" class="input-class" id="content"
                          placeholder="Your Message Here"></textarea>
                <label id="send-email-result"><h5>&nbsp</h5></label>

                <div class="bottom-button">
                    <div class="large-6 columns">
                        <a href="/user/index/profile">
                            <button><h5>取消</h5></button>
                        </a>
                    </div>
                    <div class="large-6 columns">
                        <button id="send-email"><h5>发送邮件</h5></button>
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

