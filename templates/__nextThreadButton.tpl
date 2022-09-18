{if $nextThread|isset && $nextThread}
    <li><a href="{link application='wbb' controller='Thread' object=$nextThread}action=firstNew{/link}" class="button jsTooltip" title="{lang}wbb.thread.nextThread{/lang}"><span class="icon icon16 fa-chevron-right"></span></a></li>
{/if}
