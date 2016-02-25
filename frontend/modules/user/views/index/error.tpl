<div class="row">
    <div class="large-9 medium-8 columns">

        <div class="view">

            <div class="item">
                <div class="title">
                    <h1>{$name}</h1>
                </div>
            </div>

            <div class="content">
                <h5>{$message}</h5>
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
                    <button class=""><h4>创建事件</h4></button>
                </div>
                <div class="row">
                    <button class=""><h4>进行中事件</h4></button>
                </div>
                <div class="row">
                    <button class=""><h4>已结束事件</h4></button>
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
            {/if}

        </div>
    </div>
</div>
