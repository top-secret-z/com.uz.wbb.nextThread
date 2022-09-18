{if $templateName == 'thread' && $nextThread|isset && $nextThread && WBB_BOARD_THREAD_ENABLE_NEXT}
	<script data-relocate="true">
		require(['Language', 'UZ/NextThread/JumpToNext'], function (Language, UzNextThreadJumpToNext) {
			Language.addObject({
				'wbb.thread.nextThread': '{jslang}wbb.thread.nextThread{/jslang}'
			});
			
			new UzNextThreadJumpToNext({WBB_BOARD_THREAD_ENABLE_NEXT_MOBILE}, '{link application='wbb' controller='Thread' object=$nextThread}action=firstNew{/link}');
		});
	</script>
{/if}