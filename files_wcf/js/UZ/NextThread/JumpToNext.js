/**
 * Display next thread button at bottom of page
 * 
 * @author        2020-2022 Zaydowicz
 * @license        GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package        com.uz.wbb.nextThread
 */
define(['Environment', 'Language', 'WoltLabSuite/Core/Ui/Page/Action'], function(Environment, Language, PageAction) {
    "use strict";

    function UzNextThreadJumpToNext(mobile, url) { this.init(mobile, url); }

    UzNextThreadJumpToNext.prototype = {
        init: function(mobile, url) {
            // no botton on mobile devices, if configured
            var showMobile = parseInt(mobile);
            if (Environment.platform() !== 'desktop' && !showMobile) {
                return;
            }

            this._url = url.replace(/&amp;action/g, "&action");

            this._callbackScrollEnd = this._afterScroll.bind(this);
            this._timeoutScroll = null;

            // create button
            this._button = elCreate('a');
            this._button.className = 'jsTooltip';
            this._button.href = this._url;
            elAttr(this._button, 'id', 'nextThreadButton');
            elAttr(this._button, 'aria-hidden', 'false');
            elAttr(this._button, 'title', Language.get('wbb.thread.nextThread'));
            this._button.innerHTML = '<span class="icon icon32 fa-angle-right"></span>';

            // prevent button from flickering
            this._button.style.visibility = 'hidden';
            this._visible = 0;

            PageAction.add('JumpToNext', this._button);

            var element = this._button.parentElement;
            if (element !== null) {
                if (element.nodeName == 'LI') {
                    element.classList.add("toTop");
                }
                else if (element.nodeName == 'DIV') {
                    this._button.classList.add('pageActionButtonToTop', 'nextThreadButton');
                }
            }

            window.addEventListener('scroll', this._scroll.bind(this));
            this._afterScroll();
        },

        /**
         * Callback executed whenever the window is being scrolled.
         */
        _scroll: function() {
            if (this._timeoutScroll !== null) {
                window.clearTimeout(this._timeoutScroll);
            }
            this._timeoutScroll = window.setTimeout(this._callbackScrollEnd, 100);
        },

        /**
         * Delayed callback executed once the page has not been scrolled for a certain amount of time.
         */
        _afterScroll: function() {
            this._timeoutScroll = null;

            // set button visibility
            if (!this._visible && window.pageYOffset >= 300) {
                this._visible = 1;
                this._button.style.visibility = 'visible';
            }

            PageAction[(window.pageYOffset >= 300) ? 'show' : 'hide']('JumpToNext');
        }
    };

    return UzNextThreadJumpToNext;
});
