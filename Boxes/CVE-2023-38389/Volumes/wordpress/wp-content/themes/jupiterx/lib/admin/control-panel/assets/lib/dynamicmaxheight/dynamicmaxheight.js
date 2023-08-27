/*jslint browser: true*/
/*global define, module, exports*/
(function (root, factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.DynamicMaxHeight = factory();
    }
}(this, function () {
    "use strict";

    var DynamicMaxHeight = function (options) {
        if (!this || !(this instanceof DynamicMaxHeight)) {
            return new DynamicMaxHeight(options);
        }

        if (!options) {
            options = {};
        }

        this.selector = document.querySelectorAll(options.selector);

        this.run();
    };

    DynamicMaxHeight.prototype = {
        run: function () {
            this.heightControl();
        },
        hasClass: function (el, name) {
            return new RegExp('(\\s|^)' + name + '(\\s|$)').test(el.className);
        },
        addClass: function (el, name) {
            if (!this.hasClass(el, name)) {
                el.className += (el.className ? ' ' : '') + name;
            }
        },
        removeClass: function (el, name) {
            if (this.hasClass(el, name)) {
                el.className = el.className.replace(new RegExp('(\\s|^)' + name + '(\\s|$)'), ' ').replace(/^\s+|\s+$/g, '');
            }
        },
        toggleClass: function (el, name) {
            var newClass = ' ' + el.className.replace( /[\t\r\n]/g, ' ' ) + ' ';

            if (this.hasClass(el, name)) {
                while (newClass.indexOf(' ' + name + ' ') >= 0 ) {
                    newClass = newClass.replace( ' ' + name + ' ' , ' ' );
                }
                el.className = newClass.replace(/^\s+|\s+$/g, '');
            } else {
                el.className += ' ' + name;
            }
        },
        createEls: function (name, props, text) {
            var el = document.createElement(name), p;

            for (p in props) {
                if (props.hasOwnProperty(p)) {
                    el[p] = props[p];
                }
            }

            if (text) {
                el.appendChild(document.createTextNode(text));
            }

            return el;
        },
        heightControl: function () {
            Array.prototype.forEach.call(this.selector, function (el) {
                var maxItemHeight = el.getAttribute('data-maxheight'),
                    msgMore       = el.getAttribute('data-button-more') || 'Read more',
                    msgLess       = el.getAttribute('data-button-less') || 'Show less',
                    itemHeight    = el.firstElementChild.offsetHeight;

                el.setAttribute("data-itemheight", itemHeight);

                if (itemHeight > maxItemHeight) {
                    this.addClass(el, 'height-active');
                    this.showMoreShowLess(el, itemHeight, maxItemHeight, msgMore, msgLess);
                    el.firstElementChild.style.maxHeight = maxItemHeight + 'px';
                }
            }.bind(this));
        },
        showMoreShowLess: function (el, iHeight, mItemHeight, msgMore, msgLess) {
            var a, text;

            text = this.hasClass(el, 'height-active') ? msgMore : msgLess;
            a    = this.createEls('a', {className: 'dynamic-show-more', href: '#', title: text}, text);

            el.appendChild(a);

            el.addEventListener('click', function (e) {
                e.preventDefault();

                if (e.target && e.target.nodeName === 'A' && e.target.className === 'dynamic-show-more') {
                    if (this.hasClass(el, 'height-active')) {
                        e.target.textContent = msgLess;
                        el.firstElementChild.style.maxHeight = iHeight + 'px';
                    } else {
                        e.target.textContent = msgMore;
                        el.firstElementChild.style.maxHeight = mItemHeight + 'px';
                    }
                    this.toggleClass(el, 'height-active');
                }
            }.bind(this), false);
        }
    }

    return DynamicMaxHeight;
}));
