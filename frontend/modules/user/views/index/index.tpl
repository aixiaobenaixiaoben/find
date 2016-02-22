<div class="row">
    <div class="large-9 medium-8 columns">

        <div class="view">

            <div class="item">
                <div class="title">
                    <h4>关于失踪儿童定位系统</h4>
                </div>
                {*<div class="about">
                    <i class="calendar icon"></i>
                    <a class="date">创建日期</a>
                </div>*}
            </div>

            <div class="content">
                <h5>
                    <br>基于动态口令的失踪儿童搜寻定位系统是借助成熟Web技术、动态口令技术和GIS地理信息系统的支持，结合中国国情和特点的中国儿童失踪社会应急响应系统。该系统可以在儿童走失发生时迅速启动，通过系统信息发布和民众信息反馈加警方联动的方式，尽可能快速精确地定位儿童位置，帮助家长迅速找回走失儿童，降低孩子发生意外的概率。
                    <br><br>由于系统涉及民众隐私数据，所以系统的使用者需要达到一定安全权限。在系统使用者身份认证过程中，采用动态口令技术。
                    <br><br>系统向以儿童失踪地点为中心，以适当地理长度为半径的范围内所有的手机讯号发送短信。内容包括失踪儿童的特征，失踪地点，以及儿童失踪时间。收到短信的民众如果在儿童失踪时间之后见过该儿童，则反馈见到该儿童的时间及地点。
                    <br><br>警方将该反馈内容整理后将时间地点信息作为反馈信息输入系统。
                    <br><br>系统根据一定算法求出该反馈信息的可信度指数，作为权值和该反馈信息一起参与更新失踪儿童当前地点，系统根据当前地点的变化继续更新发布儿童失踪信息。直到警方找到失踪儿童。
                    <br><br>在系统对反馈信息的分析处理显示过程中，借助GIS地理信息系统支持。
                </h5>
            </div>


        </div>

    </div>
    <div class="large-3 medium-4 columns">
        <div class="side">
            {if Yii::$app->user->isGuest}
                <div class="row">
                    <a href="/user/index/login">
                        <button class=""><h4>登陆</h4></button>
                    </a>
                </div>
            {else}
                <div class="row">
                    <a href="/user/index/test">
                        <button class=""><h4>首页</h4></button>
                    </a>
                </div>
                <div class="row">
                    <button class=""><h4>创建事件</h4></button>
                </div>
                <div class="row">
                    <button class=""><h4>进行中事件</h4></button>
                </div>
                <div class="row">
                    <button class=""><h4>已结束事件</h4></button>
                </div>
                <div class="row">
                    <button class=""><h4>用户中心</h4></button>
                </div>
                <div class="row">
                    <a href="/user/index/logout">
                        <button class=""><h4>退出</h4></button>
                    </a>
                </div>
            {/if}
            {*<div class="row">
                <button class=""><h4>首页</h4></button>
            </div>
            <div class="row">
                <button class=""><h4>结束事件</h4></button>
            </div>


            <div class="row">
                <button class=""><h4>首页</h4></button>
            </div>
            <div class="row">
                <button class=""><h4>写邮件</h4></button>
            </div>
            <div class="row">
                <button class=""><h4>更改邮箱</h4></button>
            </div>
            <div class="row">
                <button class=""><h4>更改密码</h4></button>
            </div>
            <div class="row">
                <button class=""><h4>注册用户</h4></button>
            </div>*}


        </div>
    </div>
</div>
