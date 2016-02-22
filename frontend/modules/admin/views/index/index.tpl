<ul class="model-list">
    {foreach $models as $model}
        <li>
            <a href="/admin/{$model}/index">{$model}</a><br>
        </li>
    {/foreach}
</ul>