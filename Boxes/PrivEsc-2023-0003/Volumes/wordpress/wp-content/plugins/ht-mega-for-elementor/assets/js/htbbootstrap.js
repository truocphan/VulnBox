/*!
 * Bootstrap v4.0.0-beta (https://getbootstrap.com)
 * Copyright 2011-2017 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
if ("undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript.");
! function(t) {
    var e = jQuery.fn.jquery.split(" ")[0].split(".");
    if (e[0] < 2 && e[1] < 9 || 1 == e[0] && 9 == e[1] && e[2] < 1 || e[0] >= 4) throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")
}(),
function() {
    function t(t, e) {
        if (!t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
        return !e || "object" != typeof e && "function" != typeof e ? t : e
    }

    function e(t, e) {
        if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function, not " + typeof e);
        t.prototype = Object.create(e && e.prototype, {
            constructor: {
                value: t,
                enumerable: !1,
                writable: !0,
                configurable: !0
            }
        }), e && (Object.setPrototypeOf ? Object.setPrototypeOf(t, e) : t.__proto__ = e)
    }

    function n(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }
    var i = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        },
        o = function() {
            function t(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var i = e[n];
                    i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
                }
            }
            return function(e, n, i) {
                return n && t(e.prototype, n), i && t(e, i), e
            }
        }(),
        r = function(t) {
            function e(t) {
                return {}.toString.call(t).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
            }

            function n(t) {
                return (t[0] || t).nodeType
            }

            function i() {
                return {
                    bindType: s.end,
                    delegateType: s.end,
                    handle: function(e) {
                        if (t(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
                    }
                }
            }

            function o() {
                if (window.QUnit) return !1;
                var t = document.createElement("bootstrap");
                for (var e in a)
                    if (void 0 !== t.style[e]) return {
                        end: a[e]
                    };
                return !1
            }

            function r(e) {
                var n = this,
                    i = !1;
                return t(this).one(l.TRANSITION_END, function() {
                    i = !0
                }), setTimeout(function() {
                    i || l.triggerTransitionEnd(n)
                }, e), this
            }
            var s = !1,
                a = {
                    WebkitTransition: "webkitTransitionEnd",
                    MozTransition: "transitionend",
                    OTransition: "oTransitionEnd otransitionend",
                    transition: "transitionend"
                },
                l = {
                    TRANSITION_END: "bsTransitionEnd",
                    getUID: function(t) {
                        do {
                            t += ~~(1e6 * Math.random())
                        } while (document.getElementById(t));
                        return t
                    },
                    getSelectorFromElement: function(e) {
                        var n = e.getAttribute("data-target");
                        n && "#" !== n || (n = e.getAttribute("href") || "");
                        try {
                            return t(n).length > 0 ? n : null
                        } catch (t) {
                            return null
                        }
                    },
                    reflow: function(t) {
                        return t.offsetHeight
                    },
                    triggerTransitionEnd: function(e) {
                        t(e).trigger(s.end)
                    },
                    supportsTransitionEnd: function() {
                        return Boolean(s)
                    },
                    typeCheckConfig: function(t, i, o) {
                        for (var r in o)
                            if (o.hasOwnProperty(r)) {
                                var s = o[r],
                                    a = i[r],
                                    l = a && n(a) ? "element" : e(a);
                                if (!new RegExp(s).test(l)) throw new Error(t.toUpperCase() + ': Option "' + r + '" provided type "' + l + '" but expected type "' + s + '".')
                            }
                    }
                };
            return s = o(), t.fn.emulateTransitionEnd = r, l.supportsTransitionEnd() && (t.event.special[l.TRANSITION_END] = i()), l
        }(jQuery),
        s = (function(t) {
            var e = "htb-alert",
                i = t.fn[e],
                s = {
                    DISMISS: '[data-dismiss="alert"]'
                },
                a = {
                    CLOSE: "htb-close.htbbs.htb-alert",
                    CLOSED: "htb-closed.htbbs.htb-alert",
                    CLICK_DATA_API: "click.htbbs.htb-alert.data-api"
                },
                l = {
                    ALERT: "htb-alert",
                    FADE: "htb-fade",
                    SHOW: "htb-show"
                },
                h = function() {
                    function e(t) {
                        n(this, e), this._element = t
                    }
                    return e.prototype.close = function(t) {
                        t = t || this._element;
                        var e = this._getRootElement(t);
                        this._triggerCloseEvent(e).isDefaultPrevented() || this._removeElement(e)
                    }, e.prototype.dispose = function() {
                        t.removeData(this._element, "htbbs.htb-alert"), this._element = null
                    }, e.prototype._getRootElement = function(e) {
                        var n = r.getSelectorFromElement(e),
                            i = !1;
                        return n && (i = t(n)[0]), i || (i = t(e).closest("." + l.ALERT)[0]), i
                    }, e.prototype._triggerCloseEvent = function(e) {
                        var n = t.Event(a.CLOSE);
                        return t(e).trigger(n), n
                    }, e.prototype._removeElement = function(e) {
                        var n = this;
                        t(e).removeClass(l.SHOW), r.supportsTransitionEnd() && t(e).hasClass(l.FADE) ? t(e).one(r.TRANSITION_END, function(t) {
                            return n._destroyElement(e, t)
                        }).emulateTransitionEnd(150) : this._destroyElement(e)
                    }, e.prototype._destroyElement = function(e) {
                        t(e).detach().trigger(a.CLOSED).remove()
                    }, e._jQueryInterface = function(n) {
                        return this.each(function() {
                            var i = t(this),
                                o = i.data("htbbs.htb-alert");
                            o || (o = new e(this), i.data("htbbs.htb-alert", o)), "htb-close" === n && o[n](this)
                        })
                    }, e._handleDismiss = function(t) {
                        return function(e) {
                            e && e.preventDefault(), t.close(this)
                        }
                    }, o(e, null, [{
                        key: "VERSION",
                        get: function() {
                            return "4.0.0-beta"
                        }
                    }]), e
                }();
            t(document).on(a.CLICK_DATA_API, s.DISMISS, h._handleDismiss(new h)), t.fn[e] = h._jQueryInterface, t.fn[e].Constructor = h, t.fn[e].noConflict = function() {
                return t.fn[e] = i, h._jQueryInterface
            }
        }(jQuery), function(t) {
            var e = "htb-button",
                i = t.fn[e],
                r = {
                    ACTIVE: "htb-active",
                    BUTTON: "htb-btn",
                    FOCUS: "htb-focus"
                },
                s = {
                    DATA_TOGGLE_CARROT: '[data-toggle^="button"]',
                    DATA_TOGGLE: '[data-toggle="buttons"]',
                    INPUT: "input",
                    ACTIVE: ".htb-active",
                    BUTTON: ".htb-btn"
                },
                a = {
                    CLICK_DATA_API: "click.htbbs.htb-button.data-api",
                    FOCUS_BLUR_DATA_API: "htb-focus.htbbs.htb-button.data-api blur.htbbs.htb-button.data-api"
                },
                l = function() {
                    function e(t) {
                        n(this, e), this._element = t
                    }
                    return e.prototype.toggle = function() {
                        var e = !0,
                            n = !0,
                            i = t(this._element).closest(s.DATA_TOGGLE)[0];
                        if (i) {
                            var o = t(this._element).find(s.INPUT)[0];
                            if (o) {
                                if ("radio" === o.type)
                                    if (o.checked && t(this._element).hasClass(r.ACTIVE)) e = !1;
                                    else {
                                        var a = t(i).find(s.ACTIVE)[0];
                                        a && t(a).removeClass(r.ACTIVE)
                                    }
                                if (e) {
                                    if (o.hasAttribute("disabled") || i.hasAttribute("disabled") || o.classList.contains("disabled") || i.classList.contains("disabled")) return;
                                    o.checked = !t(this._element).hasClass(r.ACTIVE), t(o).trigger("change")
                                }
                                o.focus(), n = !1
                            }
                        }
                        n && this._element.setAttribute("aria-pressed", !t(this._element).hasClass(r.ACTIVE)), e && t(this._element).toggleClass(r.ACTIVE)
                    }, e.prototype.dispose = function() {
                        t.removeData(this._element, "htbbs.htb-button"), this._element = null
                    }, e._jQueryInterface = function(n) {
                        return this.each(function() {
                            var i = t(this).data("htbbs.htb-button");
                            i || (i = new e(this), t(this).data("htbbs.htb-button", i)), "toggle" === n && i[n]()
                        })
                    }, o(e, null, [{
                        key: "VERSION",
                        get: function() {
                            return "4.0.0-beta"
                        }
                    }]), e
                }();
            t(document).on(a.CLICK_DATA_API, s.DATA_TOGGLE_CARROT, function(e) {
                e.preventDefault();
                var n = e.target;
                t(n).hasClass(r.BUTTON) || (n = t(n).closest(s.BUTTON)), l._jQueryInterface.call(t(n), "toggle")
            }).on(a.FOCUS_BLUR_DATA_API, s.DATA_TOGGLE_CARROT, function(e) {
                var n = t(e.target).closest(s.BUTTON)[0];
                t(n).toggleClass(r.FOCUS, /^focus(in)?$/.test(e.type))
            }), t.fn[e] = l._jQueryInterface, t.fn[e].Constructor = l, t.fn[e].noConflict = function() {
                return t.fn[e] = i, l._jQueryInterface
            }
        }(jQuery), function(t) {
            var e = "htb-collapse",
                s = "htbbs.htb-collapse",
                a = t.fn[e],
                l = {
                    toggle: !0,
                    parent: ""
                },
                h = {
                    toggle: "boolean",
                    parent: "string"
                },
                c = {
                    SHOW: "show.htbbs.htb-collapse",
                    SHOWN: "shown.htbbs.htb-collapse",
                    HIDE: "hide.htbbs.htb-collapse",
                    HIDDEN: "hidden.htbbs.htb-collapse",
                    CLICK_DATA_API: "click.htbbs.htb-collapse.data-api"
                },
                u = {
                    SHOW: "htb-show",
                    COLLAPSE: "htb-collapse",
                    COLLAPSING: "htb-collapsing",
                    COLLAPSED: "htb-collapsed"
                },
                d = {
                    WIDTH: "width",
                    HEIGHT: "height"
                },
                f = {
                    ACTIVES: ".htb-show, .htb-collapsing",
                    DATA_TOGGLE: '[data-toggle="htbcollapse"]'
                },
                p = function() {
                    function a(e, i) {
                        n(this, a), this._isTransitioning = !1, this._element = e, this._config = this._getConfig(i), this._triggerArray = t.makeArray(t('[data-toggle="collapse"][href="#' + e.id + '"],[data-toggle="collapse"][data-target="#' + e.id + '"]'));
                        for (var o = t(f.DATA_TOGGLE), s = 0; s < o.length; s++) {
                            var l = o[s],
                                h = r.getSelectorFromElement(l);
                            null !== h && t(h).filter(e).length > 0 && this._triggerArray.push(l)
                        }
                        this._parent = this._config.parent ? this._getParent() : null, this._config.parent || this._addAriaAndCollapsedClass(this._element, this._triggerArray), this._config.toggle && this.toggle()
                    }
                    return a.prototype.toggle = function() {
                        t(this._element).hasClass(u.SHOW) ? this.hide() : this.show()
                    }, a.prototype.show = function() {
                        var e = this;
                        if (!this._isTransitioning && !t(this._element).hasClass(u.SHOW)) {
                            var n = void 0,
                                i = void 0;
                            if (this._parent && ((n = t.makeArray(t(this._parent).children().children(f.ACTIVES))).length || (n = null)), !(n && (i = t(n).data(s)) && i._isTransitioning)) {
                                var o = t.Event(c.SHOW);
                                if (t(this._element).trigger(o), !o.isDefaultPrevented()) {
                                    n && (a._jQueryInterface.call(t(n), "hide"), i || t(n).data(s, null));
                                    var l = this._getDimension();
                                    t(this._element).removeClass(u.COLLAPSE).addClass(u.COLLAPSING), this._element.style[l] = 0, this._triggerArray.length && t(this._triggerArray).removeClass(u.COLLAPSED).attr("aria-expanded", !0), this.setTransitioning(!0);
                                    var h = function() {
                                        t(e._element).removeClass(u.COLLAPSING).addClass(u.COLLAPSE).addClass(u.SHOW), e._element.style[l] = "", e.setTransitioning(!1), t(e._element).trigger(c.SHOWN)
                                    };
                                    if (r.supportsTransitionEnd()) {
                                        var d = "scroll" + (l[0].toUpperCase() + l.slice(1));
                                        t(this._element).one(r.TRANSITION_END, h).emulateTransitionEnd(600), this._element.style[l] = this._element[d] + "px"
                                    } else h()
                                }
                            }
                        }
                    }, a.prototype.hide = function() {
                        var e = this;
                        if (!this._isTransitioning && t(this._element).hasClass(u.SHOW)) {
                            var n = t.Event(c.HIDE);
                            if (t(this._element).trigger(n), !n.isDefaultPrevented()) {
                                var i = this._getDimension();
                                if (this._element.style[i] = this._element.getBoundingClientRect()[i] + "px", r.reflow(this._element), t(this._element).addClass(u.COLLAPSING).removeClass(u.COLLAPSE).removeClass(u.SHOW), this._triggerArray.length)
                                    for (var o = 0; o < this._triggerArray.length; o++) {
                                        var s = this._triggerArray[o],
                                            a = r.getSelectorFromElement(s);
                                        null !== a && (t(a).hasClass(u.SHOW) || t(s).addClass(u.COLLAPSED).attr("aria-expanded", !1))
                                    }
                                this.setTransitioning(!0);
                                var l = function() {
                                    e.setTransitioning(!1), t(e._element).removeClass(u.COLLAPSING).addClass(u.COLLAPSE).trigger(c.HIDDEN)
                                };
                                this._element.style[i] = "", r.supportsTransitionEnd() ? t(this._element).one(r.TRANSITION_END, l).emulateTransitionEnd(600) : l()
                            }
                        }
                    }, a.prototype.setTransitioning = function(t) {
                        this._isTransitioning = t
                    }, a.prototype.dispose = function() {
                        t.removeData(this._element, s), this._config = null, this._parent = null, this._element = null, this._triggerArray = null, this._isTransitioning = null
                    }, a.prototype._getConfig = function(n) {
                        return n = t.extend({}, l, n), n.toggle = Boolean(n.toggle), r.typeCheckConfig(e, n, h), n
                    }, a.prototype._getDimension = function() {
                        return t(this._element).hasClass(d.WIDTH) ? d.WIDTH : d.HEIGHT
                    }, a.prototype._getParent = function() {
                        var e = this,
                            n = t(this._config.parent)[0],
                            i = '[data-toggle="collapse"][data-parent="' + this._config.parent + '"]';
                        return t(n).find(i).each(function(t, n) {
                            e._addAriaAndCollapsedClass(a._getTargetFromElement(n), [n])
                        }), n
                    }, a.prototype._addAriaAndCollapsedClass = function(e, n) {
                        if (e) {
                            var i = t(e).hasClass(u.SHOW);
                            n.length && t(n).toggleClass(u.COLLAPSED, !i).attr("aria-expanded", i)
                        }
                    }, a._getTargetFromElement = function(e) {
                        var n = r.getSelectorFromElement(e);
                        return n ? t(n)[0] : null
                    }, a._jQueryInterface = function(e) {
                        return this.each(function() {
                            var n = t(this),
                                o = n.data(s),
                                r = t.extend({}, l, n.data(), "object" === (void 0 === e ? "undefined" : i(e)) && e);
                            if (!o && r.toggle && /show|hide/.test(e) && (r.toggle = !1), o || (o = new a(this, r), n.data(s, o)), "string" == typeof e) {
                                if (void 0 === o[e]) throw new Error('No method named "' + e + '"');
                                o[e]()
                            }
                        })
                    }, o(a, null, [{
                        key: "VERSION",
                        get: function() {
                            return "4.0.0-beta"
                        }
                    }, {
                        key: "Default",
                        get: function() {
                            return l
                        }
                    }]), a
                }();
            t(document).on(c.CLICK_DATA_API, f.DATA_TOGGLE, function(e) {
                /input|textarea/i.test(e.target.tagName) || e.preventDefault();
                var n = t(this),
                    i = r.getSelectorFromElement(this);
                t(i).each(function() {
                    var e = t(this),
                        i = e.data(s) ? "toggle" : n.data();
                    p._jQueryInterface.call(e, i)
                })
            }), t.fn[e] = p._jQueryInterface, t.fn[e].Constructor = p, t.fn[e].noConflict = function() {
                return t.fn[e] = a, p._jQueryInterface
            }
        }(jQuery), function(t) {
            var e = "htb-modal",
                s = ".htbbs.htb-modal",
                a = t.fn[e],
                l = {
                    backdrop: !0,
                    keyboard: !0,
                    focus: !0,
                    show: !0
                },
                h = {
                    backdrop: "(boolean|string)",
                    keyboard: "boolean",
                    focus: "boolean",
                    show: "boolean"
                },
                c = {
                    HIDE: "hide.htbbs.htb-modal",
                    HIDDEN: "hidden.htbbs.htb-modal",
                    SHOW: "show.htbbs.htb-modal",
                    SHOWN: "shown.htbbs.htb-modal",
                    FOCUSIN: "focusin.htbbs.htb-modal",
                    RESIZE: "resize.htbbs.htb-modal",
                    CLICK_DISMISS: "click.dismiss.htbbs.htb-modal",
                    KEYDOWN_DISMISS: "keydown.dismiss.htbbs.htb-modal",
                    MOUSEUP_DISMISS: "mouseup.dismiss.htbbs.htb-modal",
                    MOUSEDOWN_DISMISS: "mousedown.dismiss.htbbs.htb-modal",
                    CLICK_DATA_API: "click.htbbs.htb-modal.data-api"
                },
                u = {
                    SCROLLBAR_MEASURER: "htb-modal-scrollbar-measure",
                    BACKDROP: "htb-modal-backdrop",
                    OPEN: "htb-modal-open",
                    FADE: "htb-fade",
                    SHOW: "htb-show"
                },
                d = {
                    DIALOG: ".htb-modal-dialog",
                    DATA_TOGGLE: '[data-toggle="htbmodal"]',
                    DATA_DISMISS: '[data-dismiss="modal"]',
                    FIXED_CONTENT: ".htb-fixed-top, .htb-fixed-bottom, .htb-is-fixed, .htb-sticky-top",
                    NAVBAR_TOGGLER: ".htb-navbar-toggler"
                },
                f = function() {
                    function a(e, i) {
                        n(this, a), this._config = this._getConfig(i), this._element = e, this._dialog = t(e).find(d.DIALOG)[0], this._backdrop = null, this._isShown = !1, this._isBodyOverflowing = !1, this._ignoreBackdropClick = !1, this._originalBodyPadding = 0, this._scrollbarWidth = 0
                    }
                    return a.prototype.toggle = function(t) {
                        return this._isShown ? this.hide() : this.show(t)
                    }, a.prototype.show = function(e) {
                        var n = this;
                        if (!this._isTransitioning) {
                            r.supportsTransitionEnd() && t(this._element).hasClass(u.FADE) && (this._isTransitioning = !0);
                            var i = t.Event(c.SHOW, {
                                relatedTarget: e
                            });
                            t(this._element).trigger(i), this._isShown || i.isDefaultPrevented() || (this._isShown = !0, this._checkScrollbar(), this._setScrollbar(), t(document.body).addClass(u.OPEN), this._setEscapeEvent(), this._setResizeEvent(), t(this._element).on(c.CLICK_DISMISS, d.DATA_DISMISS, function(t) {
                                return n.hide(t)
                            }), t(this._dialog).on(c.MOUSEDOWN_DISMISS, function() {
                                t(n._element).one(c.MOUSEUP_DISMISS, function(e) {
                                    t(e.target).is(n._element) && (n._ignoreBackdropClick = !0)
                                })
                            }), this._showBackdrop(function() {
                                return n._showElement(e)
                            }))
                        }
                    }, a.prototype.hide = function(e) {
                        var n = this;
                        if (e && e.preventDefault(), !this._isTransitioning && this._isShown) {
                            var i = r.supportsTransitionEnd() && t(this._element).hasClass(u.FADE);
                            i && (this._isTransitioning = !0);
                            var o = t.Event(c.HIDE);
                            t(this._element).trigger(o), this._isShown && !o.isDefaultPrevented() && (this._isShown = !1, this._setEscapeEvent(), this._setResizeEvent(), t(document).off(c.FOCUSIN), t(this._element).removeClass(u.SHOW), t(this._element).off(c.CLICK_DISMISS), t(this._dialog).off(c.MOUSEDOWN_DISMISS), i ? t(this._element).one(r.TRANSITION_END, function(t) {
                                return n._hideModal(t)
                            }).emulateTransitionEnd(300) : this._hideModal())
                        }
                    }, a.prototype.dispose = function() {
                        t.removeData(this._element, "htbbs.htb-modal"), t(window, document, this._element, this._backdrop).off(s), this._config = null, this._element = null, this._dialog = null, this._backdrop = null, this._isShown = null, this._isBodyOverflowing = null, this._ignoreBackdropClick = null, this._scrollbarWidth = null
                    }, a.prototype.handleUpdate = function() {
                        this._adjustDialog()
                    }, a.prototype._getConfig = function(n) {
                        return n = t.extend({}, l, n), r.typeCheckConfig(e, n, h), n
                    }, a.prototype._showElement = function(e) {
                        var n = this,
                            i = r.supportsTransitionEnd() && t(this._element).hasClass(u.FADE);
                        this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE || document.body.appendChild(this._element), this._element.style.display = "block", this._element.removeAttribute("aria-hidden"), this._element.scrollTop = 0, i && r.reflow(this._element), t(this._element).addClass(u.SHOW), this._config.focus && this._enforceFocus();
                        var o = t.Event(c.SHOWN, {
                                relatedTarget: e
                            }),
                            s = function() {
                                n._config.focus && n._element.focus(), n._isTransitioning = !1, t(n._element).trigger(o)
                            };
                        i ? t(this._dialog).one(r.TRANSITION_END, s).emulateTransitionEnd(300) : s()
                    }, a.prototype._enforceFocus = function() {
                        var e = this;
                        t(document).off(c.FOCUSIN).on(c.FOCUSIN, function(n) {
                            document === n.target || e._element === n.target || t(e._element).has(n.target).length || e._element.focus()
                        })
                    }, a.prototype._setEscapeEvent = function() {
                        var e = this;
                        this._isShown && this._config.keyboard ? t(this._element).on(c.KEYDOWN_DISMISS, function(t) {
                            27 === t.which && (t.preventDefault(), e.hide())
                        }) : this._isShown || t(this._element).off(c.KEYDOWN_DISMISS)
                    }, a.prototype._setResizeEvent = function() {
                        var e = this;
                        this._isShown ? t(window).on(c.RESIZE, function(t) {
                            return e.handleUpdate(t)
                        }) : t(window).off(c.RESIZE)
                    }, a.prototype._hideModal = function() {
                        var e = this;
                        this._element.style.display = "none", this._element.setAttribute("aria-hidden", !0), this._isTransitioning = !1, this._showBackdrop(function() {
                            t(document.body).removeClass(u.OPEN), e._resetAdjustments(), e._resetScrollbar(), t(e._element).trigger(c.HIDDEN)
                        })
                    }, a.prototype._removeBackdrop = function() {
                        this._backdrop && (t(this._backdrop).remove(), this._backdrop = null)
                    }, a.prototype._showBackdrop = function(e) {
                        var n = this,
                            i = t(this._element).hasClass(u.FADE) ? u.FADE : "";
                        if (this._isShown && this._config.backdrop) {
                            var o = r.supportsTransitionEnd() && i;
                            if (this._backdrop = document.createElement("div"), this._backdrop.className = u.BACKDROP, i && t(this._backdrop).addClass(i), t(this._backdrop).appendTo(document.body), t(this._element).on(c.CLICK_DISMISS, function(t) {
                                    n._ignoreBackdropClick ? n._ignoreBackdropClick = !1 : t.target === t.currentTarget && ("static" === n._config.backdrop ? n._element.focus() : n.hide())
                                }), o && r.reflow(this._backdrop), t(this._backdrop).addClass(u.SHOW), !e) return;
                            if (!o) return void e();
                            t(this._backdrop).one(r.TRANSITION_END, e).emulateTransitionEnd(150)
                        } else if (!this._isShown && this._backdrop) {
                            t(this._backdrop).removeClass(u.SHOW);
                            var s = function() {
                                n._removeBackdrop(), e && e()
                            };
                            r.supportsTransitionEnd() && t(this._element).hasClass(u.FADE) ? t(this._backdrop).one(r.TRANSITION_END, s).emulateTransitionEnd(150) : s()
                        } else e && e()
                    }, a.prototype._adjustDialog = function() {
                        var t = this._element.scrollHeight > document.documentElement.clientHeight;
                        !this._isBodyOverflowing && t && (this._element.style.paddingLeft = this._scrollbarWidth + "px"), this._isBodyOverflowing && !t && (this._element.style.paddingRight = this._scrollbarWidth + "px")
                    }, a.prototype._resetAdjustments = function() {
                        this._element.style.paddingLeft = "", this._element.style.paddingRight = ""
                    }, a.prototype._checkScrollbar = function() {
                        this._isBodyOverflowing = document.body.clientWidth < window.innerWidth, this._scrollbarWidth = this._getScrollbarWidth()
                    }, a.prototype._setScrollbar = function() {
                        var e = this;
                        if (this._isBodyOverflowing) {
                            t(d.FIXED_CONTENT).each(function(n, i) {
                                var o = t(i)[0].style.paddingRight,
                                    r = t(i).css("padding-right");
                                t(i).data("padding-right", o).css("padding-right", parseFloat(r) + e._scrollbarWidth + "px")
                            }), t(d.NAVBAR_TOGGLER).each(function(n, i) {
                                var o = t(i)[0].style.marginRight,
                                    r = t(i).css("margin-right");
                                t(i).data("margin-right", o).css("margin-right", parseFloat(r) + e._scrollbarWidth + "px")
                            });
                            var n = document.body.style.paddingRight,
                                i = t("body").css("padding-right");
                            t("body").data("padding-right", n).css("padding-right", parseFloat(i) + this._scrollbarWidth + "px")
                        }
                    }, a.prototype._resetScrollbar = function() {
                        t(d.FIXED_CONTENT).each(function(e, n) {
                            var i = t(n).data("padding-right");
                            void 0 !== i && t(n).css("padding-right", i).removeData("padding-right")
                        }), t(d.NAVBAR_TOGGLER).each(function(e, n) {
                            var i = t(n).data("margin-right");
                            void 0 !== i && t(n).css("margin-right", i).removeData("margin-right")
                        });
                        var e = t("body").data("padding-right");
                        void 0 !== e && t("body").css("padding-right", e).removeData("padding-right")
                    }, a.prototype._getScrollbarWidth = function() {
                        var t = document.createElement("div");
                        t.className = u.SCROLLBAR_MEASURER, document.body.appendChild(t);
                        var e = t.getBoundingClientRect().width - t.clientWidth;
                        return document.body.removeChild(t), e
                    }, a._jQueryInterface = function(e, n) {
                        return this.each(function() {
                            var o = t(this).data("bs.modal"),
                                r = t.extend({}, a.Default, t(this).data(), "object" === (void 0 === e ? "undefined" : i(e)) && e);
                            if (o || (o = new a(this, r), t(this).data("bs.modal", o)), "string" == typeof e) {
                                if (void 0 === o[e]) throw new Error('No method named "' + e + '"');
                                o[e](n)
                            } else r.show && o.show(n)
                        })
                    }, o(a, null, [{
                        key: "VERSION",
                        get: function() {
                            return "4.0.0-beta"
                        }
                    }, {
                        key: "Default",
                        get: function() {
                            return l
                        }
                    }]), a
                }();
            t(document).on(c.CLICK_DATA_API, d.DATA_TOGGLE, function(e) {
                var n = this,
                    i = void 0,
                    o = r.getSelectorFromElement(this);
                o && (i = t(o)[0]);
                var s = t(i).data("htbbs.htb-modal") ? "toggle" : t.extend({}, t(i).data(), t(this).data());
                "A" !== this.tagName && "AREA" !== this.tagName || e.preventDefault();
                var a = t(i).one(c.SHOW, function(e) {
                    e.isDefaultPrevented() || a.one(c.HIDDEN, function() {
                        t(n).is(":visible") && n.focus()
                    })
                });
                f._jQueryInterface.call(t(i), s, this)
            }), t.fn[e] = f._jQueryInterface, t.fn[e].Constructor = f, t.fn[e].noConflict = function() {
                return t.fn[e] = a, f._jQueryInterface
            }
        }(jQuery), function(t) {
            var e = t.fn.tab,
                i = {
                    HIDE: "hide.htbbs.htb-tab",
                    HIDDEN: "hidden.htbbs.htb-tab",
                    SHOW: "show.htbbs.htb-tab",
                    SHOWN: "shown.htbbs.htb-tab",
                    CLICK_DATA_API: "click.htbbs.htb-tab.data-api"
                },
                s = {
                    DROPDOWN_MENU: "htb-dropdown-menu",
                    ACTIVE: "htb-active",
                    DISABLED: "disabled",
                    FADE: "htb-fade",
                    SHOW: "htb-show"
                },
                a = {
                    DROPDOWN: ".htb-dropdown",
                    NAV_LIST_GROUP: ".htb-nav, .htb-list-group",
                    ACTIVE: ".htb-active",
                    DATA_TOGGLE: '[data-toggle="htbtab"]',
                    DROPDOWN_TOGGLE: ".htb-dropdown-toggle",
                    DROPDOWN_ACTIVE_CHILD: "> .htb-dropdown-menu .htb-active"
                },
                l = function() {
                    function e(t) {
                        n(this, e), this._element = t
                    }
                    return e.prototype.show = function() {
                        var e = this;
                        if (!(this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && t(this._element).hasClass(s.ACTIVE) || t(this._element).hasClass(s.DISABLED))) {
                            var n = void 0,
                                o = void 0,
                                l = t(this._element).closest(a.NAV_LIST_GROUP)[0],
                                h = r.getSelectorFromElement(this._element);
                            l && (o = t.makeArray(t(l).find(a.ACTIVE)), o = o[o.length - 1]);
                            var c = t.Event(i.HIDE, {
                                    relatedTarget: this._element
                                }),
                                u = t.Event(i.SHOW, {
                                    relatedTarget: o
                                });
                            if (o && t(o).trigger(c), t(this._element).trigger(u), !u.isDefaultPrevented() && !c.isDefaultPrevented()) {
                                h && (n = t(h)[0]), this._activate(this._element, l);
                                var d = function() {
                                    var n = t.Event(i.HIDDEN, {
                                            relatedTarget: e._element
                                        }),
                                        r = t.Event(i.SHOWN, {
                                            relatedTarget: o
                                        });
                                    t(o).trigger(n), t(e._element).trigger(r)
                                };
                                n ? this._activate(n, n.parentNode, d) : d()
                            }
                        }
                    }, e.prototype.dispose = function() {
                        t.removeData(this._element, "bs.tab"), this._element = null
                    }, e.prototype._activate = function(e, n, i) {
                        var o = this,
                            l = t(n).find(a.ACTIVE)[0],
                            h = i && r.supportsTransitionEnd() && l && t(l).hasClass(s.FADE),
                            c = function() {
                                return o._transitionComplete(e, l, h, i)
                            };
                        l && h ? t(l).one(r.TRANSITION_END, c).emulateTransitionEnd(150) : c(), l && t(l).removeClass(s.SHOW)
                    }, e.prototype._transitionComplete = function(e, n, i, o) {
                        if (n) {
                            t(n).removeClass(s.ACTIVE);
                            var l = t(n.parentNode).find(a.DROPDOWN_ACTIVE_CHILD)[0];
                            l && t(l).removeClass(s.ACTIVE), n.setAttribute("aria-expanded", !1)
                        }
                        if (t(e).addClass(s.ACTIVE), e.setAttribute("aria-expanded", !0), i ? (r.reflow(e), t(e).addClass(s.SHOW)) : t(e).removeClass(s.FADE), e.parentNode && t(e.parentNode).hasClass(s.DROPDOWN_MENU)) {
                            var h = t(e).closest(a.DROPDOWN)[0];
                            h && t(h).find(a.DROPDOWN_TOGGLE).addClass(s.ACTIVE), e.setAttribute("aria-expanded", !0)
                        }
                        o && o()
                    }, e._jQueryInterface = function(n) {
                        return this.each(function() {
                            var i = t(this),
                                o = i.data("htbbs.htb-tab");
                            if (o || (o = new e(this), i.data("htbbs.htb-tab", o)), "string" == typeof n) {
                                if (void 0 === o[n]) throw new Error('No method named "' + n + '"');
                                o[n]()
                            }
                        })
                    }, o(e, null, [{
                        key: "VERSION",
                        get: function() {
                            return "4.0.0-beta"
                        }
                    }]), e
                }();
            t(document).on(i.CLICK_DATA_API, a.DATA_TOGGLE, function(e) {
                e.preventDefault(), l._jQueryInterface.call(t(this), "show")
            }), t.fn.tab = l._jQueryInterface, t.fn.tab.Constructor = l, t.fn.tab.noConflict = function() {
                return t.fn.tab = e, l._jQueryInterface
            }
        }(jQuery), function(t) {
            if ("undefined" == typeof Popper) throw new Error("Bootstrap tooltips require Popper.js (https://popper.js.org)");
            var e = "htbtooltip",
                s = ".htbbs.htb-tooltip",
                a = t.fn[e],
                l = new RegExp("(^|\\s)bs-tooltip\\S+", "g"),
                h = {
                    animation: "boolean",
                    template: "string",
                    title: "(string|element|function)",
                    trigger: "string",
                    delay: "(number|object)",
                    html: "boolean",
                    selector: "(string|boolean)",
                    placement: "(string|function)",
                    offset: "(number|string)",
                    container: "(string|element|boolean)",
                    fallbackPlacement: "(string|array)"
                },
                c = {
                    AUTO: "auto",
                    TOP: "top",
                    RIGHT: "right",
                    BOTTOM: "bottom",
                    LEFT: "left"
                },
                u = {
                    animation: !0,
                    template: '<div class="htb-tooltip" role="tooltip"><div class="htb-arrow"></div><div class="htb-tooltip-inner"></div></div>',
                    trigger: "hover focus",
                    title: "",
                    delay: 0,
                    html: !1,
                    selector: !1,
                    placement: "top",
                    offset: 0,
                    container: !1,
                    fallbackPlacement: "flip"
                },
                d = {
                    SHOW: "show",
                    OUT: "out"
                },
                f = {
                    HIDE: "hide" + s,
                    HIDDEN: "hidden" + s,
                    SHOW: "show" + s,
                    SHOWN: "shown" + s,
                    INSERTED: "inserted" + s,
                    CLICK: "click" + s,
                    FOCUSIN: "focusin" + s,
                    FOCUSOUT: "focusout" + s,
                    MOUSEENTER: "mouseenter" + s,
                    MOUSELEAVE: "mouseleave" + s
                },
                p = {
                    FADE: "htb-fade",
                    SHOW: "htb-show"
                },
                _ = {
                    TOOLTIP: ".htb-tooltip",
                    TOOLTIP_INNER: ".htb-tooltip-inner",
                    ARROW: ".htb-arrow"
                },
                g = {
                    HOVER: "hover",
                    FOCUS: "focus",
                    CLICK: "click",
                    MANUAL: "manual"
                },
                m = function() {
                    function a(t, e) {
                        n(this, a), this._isEnabled = !0, this._timeout = 0, this._hoverState = "", this._activeTrigger = {}, this._popper = null, this.element = t, this.config = this._getConfig(e), this.tip = null, this._setListeners()
                    }
                    return a.prototype.enable = function() {
                        this._isEnabled = !0
                    }, a.prototype.disable = function() {
                        this._isEnabled = !1
                    }, a.prototype.toggleEnabled = function() {
                        this._isEnabled = !this._isEnabled
                    }, a.prototype.toggle = function(e) {
                        if (e) {
                            var n = this.constructor.DATA_KEY,
                                i = t(e.currentTarget).data(n);
                            i || (i = new this.constructor(e.currentTarget, this._getDelegateConfig()), t(e.currentTarget).data(n, i)), i._activeTrigger.click = !i._activeTrigger.click, i._isWithActiveTrigger() ? i._enter(null, i) : i._leave(null, i)
                        } else {
                            if (t(this.getTipElement()).hasClass(p.SHOW)) return void this._leave(null, this);
                            this._enter(null, this)
                        }
                    }, a.prototype.dispose = function() {
                        clearTimeout(this._timeout), t.removeData(this.element, this.constructor.DATA_KEY), t(this.element).off(this.constructor.EVENT_KEY), t(this.element).closest(".htb-modal").off("hide.htbbs.htb-modal"), this.tip && t(this.tip).remove(), this._isEnabled = null, this._timeout = null, this._hoverState = null, this._activeTrigger = null, null !== this._popper && this._popper.destroy(), this._popper = null, this.element = null, this.config = null, this.tip = null
                    }, a.prototype.show = function() {
                        var e = this;
                        if ("none" === t(this.element).css("display")) throw new Error("Please use show on visible elements");
                        var n = t.Event(this.constructor.Event.SHOW);
                        if (this.isWithContent() && this._isEnabled) {
                            t(this.element).trigger(n);
                            var i = t.contains(this.element.ownerDocument.documentElement, this.element);
                            if (n.isDefaultPrevented() || !i) return;
                            var o = this.getTipElement(),
                                s = r.getUID(this.constructor.NAME);
                            o.setAttribute("id", s), this.element.setAttribute("aria-describedby", s), this.setContent(), this.config.animation && t(o).addClass(p.FADE);
                            var l = "function" == typeof this.config.placement ? this.config.placement.call(this, o, this.element) : this.config.placement,
                                h = this._getAttachment(l);
                            this.addAttachmentClass(h);
                            var c = !1 === this.config.container ? document.body : t(this.config.container);
                            t(o).data(this.constructor.DATA_KEY, this), t.contains(this.element.ownerDocument.documentElement, this.tip) || t(o).appendTo(c), t(this.element).trigger(this.constructor.Event.INSERTED), this._popper = new Popper(this.element, o, {
                                placement: h,
                                modifiers: {
                                    offset: {
                                        offset: this.config.offset
                                    },
                                    flip: {
                                        behavior: this.config.fallbackPlacement
                                    },
                                    arrow: {
                                        element: _.ARROW
                                    }
                                },
                                onCreate: function(t) {
                                    t.originalPlacement !== t.placement && e._handlePopperPlacementChange(t)
                                },
                                onUpdate: function(t) {
                                    e._handlePopperPlacementChange(t)
                                }
                            }), t(o).addClass(p.SHOW), "ontouchstart" in document.documentElement && t("body").children().on("mouseover", null, t.noop);
                            var u = function() {
                                e.config.animation && e._fixTransition();
                                var n = e._hoverState;
                                e._hoverState = null, t(e.element).trigger(e.constructor.Event.SHOWN), n === d.OUT && e._leave(null, e)
                            };
                            r.supportsTransitionEnd() && t(this.tip).hasClass(p.FADE) ? t(this.tip).one(r.TRANSITION_END, u).emulateTransitionEnd(a._TRANSITION_DURATION) : u()
                        }
                    }, a.prototype.hide = function(e) {
                        var n = this,
                            i = this.getTipElement(),
                            o = t.Event(this.constructor.Event.HIDE),
                            s = function() {
                                n._hoverState !== d.SHOW && i.parentNode && i.parentNode.removeChild(i), n._cleanTipClass(), n.element.removeAttribute("aria-describedby"), t(n.element).trigger(n.constructor.Event.HIDDEN), null !== n._popper && n._popper.destroy(), e && e()
                            };
                        t(this.element).trigger(o), o.isDefaultPrevented() || (t(i).removeClass(p.SHOW), "ontouchstart" in document.documentElement && t("body").children().off("mouseover", null, t.noop), this._activeTrigger[g.CLICK] = !1, this._activeTrigger[g.FOCUS] = !1, this._activeTrigger[g.HOVER] = !1, r.supportsTransitionEnd() && t(this.tip).hasClass(p.FADE) ? t(i).one(r.TRANSITION_END, s).emulateTransitionEnd(150) : s(), this._hoverState = "")
                    }, a.prototype.update = function() {
                        null !== this._popper && this._popper.scheduleUpdate()
                    }, a.prototype.isWithContent = function() {
                        return Boolean(this.getTitle())
                    }, a.prototype.addAttachmentClass = function(e) {
                        t(this.getTipElement()).addClass("bs-tooltip-" + e)
                    }, a.prototype.getTipElement = function() {
                        return this.tip = this.tip || t(this.config.template)[0]
                    }, a.prototype.setContent = function() {
                        var e = t(this.getTipElement());
                        this.setElementContent(e.find(_.TOOLTIP_INNER), this.getTitle()), e.removeClass(p.FADE + " " + p.SHOW)
                    }, a.prototype.setElementContent = function(e, n) {
                        var o = this.config.html;
                        "object" === (void 0 === n ? "undefined" : i(n)) && (n.nodeType || n.jquery) ? o ? t(n).parent().is(e) || e.empty().append(n) : e.text(t(n).text()): e[o ? "html" : "text"](n)
                    }, a.prototype.getTitle = function() {
                        var t = this.element.getAttribute("data-original-title");
                        return t || (t = "function" == typeof this.config.title ? this.config.title.call(this.element) : this.config.title), t
                    }, a.prototype._getAttachment = function(t) {
                        return c[t.toUpperCase()]
                    }, a.prototype._setListeners = function() {
                        var e = this;
                        this.config.trigger.split(" ").forEach(function(n) {
                            if ("click" === n) t(e.element).on(e.constructor.Event.CLICK, e.config.selector, function(t) {
                                return e.toggle(t)
                            });
                            else if (n !== g.MANUAL) {
                                var i = n === g.HOVER ? e.constructor.Event.MOUSEENTER : e.constructor.Event.FOCUSIN,
                                    o = n === g.HOVER ? e.constructor.Event.MOUSELEAVE : e.constructor.Event.FOCUSOUT;
                                t(e.element).on(i, e.config.selector, function(t) {
                                    return e._enter(t)
                                }).on(o, e.config.selector, function(t) {
                                    return e._leave(t)
                                })
                            }
                            t(e.element).closest(".htb-modal").on("hide.htbbs.htb-modal", function() {
                                return e.hide()
                            })
                        }), this.config.selector ? this.config = t.extend({}, this.config, {
                            trigger: "manual",
                            selector: ""
                        }) : this._fixTitle()
                    }, a.prototype._fixTitle = function() {
                        var t = i(this.element.getAttribute("data-original-title"));
                        (this.element.getAttribute("title") || "string" !== t) && (this.element.setAttribute("data-original-title", this.element.getAttribute("title") || ""), this.element.setAttribute("title", ""))
                    }, a.prototype._enter = function(e, n) {
                        var i = this.constructor.DATA_KEY;
                        (n = n || t(e.currentTarget).data(i)) || (n = new this.constructor(e.currentTarget, this._getDelegateConfig()), t(e.currentTarget).data(i, n)), e && (n._activeTrigger["focusin" === e.type ? g.FOCUS : g.HOVER] = !0), t(n.getTipElement()).hasClass(p.SHOW) || n._hoverState === d.SHOW ? n._hoverState = d.SHOW : (clearTimeout(n._timeout), n._hoverState = d.SHOW, n.config.delay && n.config.delay.show ? n._timeout = setTimeout(function() {
                            n._hoverState === d.SHOW && n.show()
                        }, n.config.delay.show) : n.show())
                    }, a.prototype._leave = function(e, n) {
                        var i = this.constructor.DATA_KEY;
                        (n = n || t(e.currentTarget).data(i)) || (n = new this.constructor(e.currentTarget, this._getDelegateConfig()), t(e.currentTarget).data(i, n)), e && (n._activeTrigger["focusout" === e.type ? g.FOCUS : g.HOVER] = !1), n._isWithActiveTrigger() || (clearTimeout(n._timeout), n._hoverState = d.OUT, n.config.delay && n.config.delay.hide ? n._timeout = setTimeout(function() {
                            n._hoverState === d.OUT && n.hide()
                        }, n.config.delay.hide) : n.hide())
                    }, a.prototype._isWithActiveTrigger = function() {
                        for (var t in this._activeTrigger)
                            if (this._activeTrigger[t]) return !0;
                        return !1
                    }, a.prototype._getConfig = function(n) {
                        return (n = t.extend({}, this.constructor.Default, t(this.element).data(), n)).delay && "number" == typeof n.delay && (n.delay = {
                            show: n.delay,
                            hide: n.delay
                        }), n.title && "number" == typeof n.title && (n.title = n.title.toString()), n.content && "number" == typeof n.content && (n.content = n.content.toString()), r.typeCheckConfig(e, n, this.constructor.DefaultType), n
                    }, a.prototype._getDelegateConfig = function() {
                        var t = {};
                        if (this.config)
                            for (var e in this.config) this.constructor.Default[e] !== this.config[e] && (t[e] = this.config[e]);
                        return t
                    }, a.prototype._cleanTipClass = function() {
                        var e = t(this.getTipElement()),
                            n = e.attr("class").match(l);
                        null !== n && n.length > 0 && e.removeClass(n.join(""))
                    }, a.prototype._handlePopperPlacementChange = function(t) {
                        this._cleanTipClass(), this.addAttachmentClass(this._getAttachment(t.placement))
                    }, a.prototype._fixTransition = function() {
                        var e = this.getTipElement(),
                            n = this.config.animation;
                        null === e.getAttribute("x-placement") && (t(e).removeClass(p.FADE), this.config.animation = !1, this.hide(), this.show(), this.config.animation = n)
                    }, a._jQueryInterface = function(e) {
                        return this.each(function() {
                            var n = t(this).data("htbbs.tooltip"),
                                o = "object" === (void 0 === e ? "undefined" : i(e)) && e;
                            if ((n || !/dispose|hide/.test(e)) && (n || (n = new a(this, o), t(this).data("htbbs.tooltip", n)), "string" == typeof e)) {
                                if (void 0 === n[e]) throw new Error('No method named "' + e + '"');
                                n[e]()
                            }
                        })
                    }, o(a, null, [{
                        key: "VERSION",
                        get: function() {
                            return "4.0.0-beta"
                        }
                    }, {
                        key: "Default",
                        get: function() {
                            return u
                        }
                    }, {
                        key: "NAME",
                        get: function() {
                            return e
                        }
                    }, {
                        key: "DATA_KEY",
                        get: function() {
                            return "htbbs.tooltip"
                        }
                    }, {
                        key: "Event",
                        get: function() {
                            return f
                        }
                    }, {
                        key: "EVENT_KEY",
                        get: function() {
                            return s
                        }
                    }, {
                        key: "DefaultType",
                        get: function() {
                            return h
                        }
                    }]), a
                }();
            return t.fn[e] = m._jQueryInterface, t.fn[e].Constructor = m, t.fn[e].noConflict = function() {
                return t.fn[e] = a, m._jQueryInterface
            }, m
        }(jQuery));
    ! function(r) {
        var a = "htbpopover",
            l = ".htbbs.htb-popover",
            h = r.fn[a],
            c = new RegExp("(^|\\s)htb-bs-popover\\S+", "g"),
            u = r.extend({}, s.Default, {
                placement: "right",
                trigger: "click",
                content: "",
                template: '<div class="htb-popover" role="tooltip"><div class="htb-arrow"></div><h3 class="htb-popover-header"></h3><div class="htb-popover-body"></div></div>'
            }),
            d = r.extend({}, s.DefaultType, {
                content: "(string|element|function)"
            }),
            f = {
                FADE: "htb-fade",
                SHOW: "htb-show"
            },
            p = {
                TITLE: ".htb-popover-header",
                CONTENT: ".htb-popover-body"
            },
            _ = {
                HIDE: "hide" + l,
                HIDDEN: "hidden" + l,
                SHOW: "show" + l,
                SHOWN: "shown" + l,
                INSERTED: "inserted" + l,
                CLICK: "click" + l,
                FOCUSIN: "focusin" + l,
                FOCUSOUT: "focusout" + l,
                MOUSEENTER: "mouseenter" + l,
                MOUSELEAVE: "mouseleave" + l
            },
            g = function(s) {
                function h() {
                    return n(this, h), t(this, s.apply(this, arguments))
                }
                return e(h, s), h.prototype.isWithContent = function() {
                    return this.getTitle() || this._getContent()
                }, h.prototype.addAttachmentClass = function(t) {
                    r(this.getTipElement()).addClass("htb-bs-popover-" + t)
                }, h.prototype.getTipElement = function() {
                    return this.tip = this.tip || r(this.config.template)[0]
                }, h.prototype.setContent = function() {
                    var t = r(this.getTipElement());
                    this.setElementContent(t.find(p.TITLE), this.getTitle()), this.setElementContent(t.find(p.CONTENT), this._getContent()), t.removeClass(f.FADE + " " + f.SHOW)
                }, h.prototype._getContent = function() {
                    return this.element.getAttribute("data-content") || ("function" == typeof this.config.content ? this.config.content.call(this.element) : this.config.content)
                }, h.prototype._cleanTipClass = function() {
                    var t = r(this.getTipElement()),
                        e = t.attr("class").match(c);
                    null !== e && e.length > 0 && t.removeClass(e.join(""))
                }, h._jQueryInterface = function(t) {
                    return this.each(function() {
                        var e = r(this).data("htbbs.popover"),
                            n = "object" === (void 0 === t ? "undefined" : i(t)) ? t : null;
                        if ((e || !/destroy|hide/.test(t)) && (e || (e = new h(this, n), r(this).data("htbbs.popover", e)), "string" == typeof t)) {
                            if (void 0 === e[t]) throw new Error('No method named "' + t + '"');
                            e[t]()
                        }
                    })
                }, o(h, null, [{
                    key: "VERSION",
                    get: function() {
                        return "4.0.0-beta"
                    }
                }, {
                    key: "Default",
                    get: function() {
                        return u
                    }
                }, {
                    key: "NAME",
                    get: function() {
                        return a
                    }
                }, {
                    key: "DATA_KEY",
                    get: function() {
                        return "htbbs.popover"
                    }
                }, {
                    key: "Event",
                    get: function() {
                        return _
                    }
                }, {
                    key: "EVENT_KEY",
                    get: function() {
                        return l
                    }
                }, {
                    key: "DefaultType",
                    get: function() {
                        return d
                    }
                }]), h
            }(s);
        r.fn[a] = g._jQueryInterface, r.fn[a].Constructor = g, r.fn[a].noConflict = function() {
            return r.fn[a] = h, g._jQueryInterface
        }
    }(jQuery)
}();