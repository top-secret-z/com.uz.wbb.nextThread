<?php

/*
 * Copyright by Udo Zaydowicz.
 * Modified by SoftCreatR.dev.
 *
 * License: http://opensource.org/licenses/lgpl-license.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
namespace wbb\system\event\listener;

use wbb\data\thread\UnreadThreadList;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\WCF;

/**
 * Next Thread listener
 */
class NextThreadListener implements IParameterizedEventListener
{
    /**
     * @inheritdoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        $thread = null;

        // only users
        if (WCF::getUser()->userID) {
            $threadList = new UnreadThreadList();
            $threadList->getConditionBuilder()->add('thread.threadID <> ?', [$eventObj->thread->threadID]);
            $threadList->sqlLimit = 1;
            if (WBB_BOARD_THREAD_NEXT_SORTING == 'old') {
                $threadList->sqlOrderBy = 'thread.lastPostTime ASC';
            } elseif (WBB_BOARD_THREAD_NEXT_SORTING == 'new') {
                $threadList->sqlOrderBy = 'thread.lastPostTime DESC';
            }
            $threadList->readObjects();
            $threads = $threadList->getObjects();

            if (\count($threads)) {
                $thread = \reset($threads);
            }
        }

        WCF::getTPL()->assign([
            'nextThread' => $thread,
        ]);
    }
}
