<?php
namespace wbb\system\event\listener;
use wbb\data\thread\UnreadThreadList;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\WCF;

/**
 * Next Thread listener
 * @author		2020-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wbb.nextThread
 */
class NextThreadListener implements IParameterizedEventListener {
	/**
	 * @inheritdoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		$thread = null;
		
		// only users
		if (WCF::getUser()->userID) {
			$threadList = new UnreadThreadList();
			$threadList->getConditionBuilder()->add('thread.threadID <> ?', [$eventObj->thread->threadID]);
			$threadList->sqlLimit = 1;
			if (WBB_BOARD_THREAD_NEXT_SORTING == 'old') {
				$threadList->sqlOrderBy = 'thread.lastPostTime ASC';
			}
			elseif (WBB_BOARD_THREAD_NEXT_SORTING == 'new') {
				$threadList->sqlOrderBy = 'thread.lastPostTime DESC';
			}
			$threadList->readObjects();
			$threads = $threadList->getObjects();
			
			if (count($threads)) $thread = reset($threads);
		}
		
		WCF::getTPL()->assign([
				'nextThread' => $thread
		]);
	}
}
