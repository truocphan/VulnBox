"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
!function (e, t) {
  "object" == (typeof exports === "undefined" ? "undefined" : _typeof(exports)) && "undefined" != typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define(t) : e.VueRecaptcha = t();
}(void 0, function () {
  "use strict";

  var e = function e() {
      var e = !1,
        t = [];
      return {
        resolved: function resolved() {
          return e;
        },
        resolve: function resolve(n) {
          if (!e) {
            e = !0;
            for (var i = 0, r = t.length; i < r; i++) t[i](n);
          }
        },
        promise: {
          then: function then(n) {
            e ? n() : t.push(n);
          }
        }
      };
    },
    t = function () {
      var t = e();
      return {
        notify: function notify() {
          t.resolve();
        },
        wait: function wait() {
          return t.promise;
        },
        render: function render(e, t, n) {
          this.wait().then(function () {
            n(window.grecaptcha.render(e, t));
          });
        },
        reset: function reset(e) {
          void 0 !== e && (this.assertLoaded(), this.wait().then(function () {
            return window.grecaptcha.reset(e);
          }));
        },
        execute: function execute(e) {
          void 0 !== e && (this.assertLoaded(), this.wait().then(function () {
            return window.grecaptcha.execute(e);
          }));
        },
        checkRecaptchaLoad: function checkRecaptchaLoad() {
          window.hasOwnProperty("grecaptcha") && this.notify();
        },
        assertLoaded: function assertLoaded() {
          if (!t.resolved()) throw new Error("ReCAPTCHA has not been loaded");
        }
      };
    }();
  "undefined" != typeof window && (window.vueRecaptchaApiLoaded = t.notify);
  var n = Object.assign || function (e) {
    for (var t = 1; t < arguments.length; t++) {
      var n = arguments[t];
      for (var i in n) Object.prototype.hasOwnProperty.call(n, i) && (e[i] = n[i]);
    }
    return e;
  };
  return {
    name: "VueRecaptcha",
    props: {
      sitekey: {
        type: String,
        required: !0
      },
      theme: {
        type: String
      },
      badge: {
        type: String
      },
      type: {
        type: String
      },
      size: {
        type: String
      },
      tabindex: {
        type: String
      }
    },
    mounted: function mounted() {
      var e = this;
      t.checkRecaptchaLoad();
      var i = n({}, this.$props, {
          callback: this.emitVerify,
          "expired-callback": this.emitExpired
        }),
        r = this.$slots["default"] ? this.$el.children[0] : this.$el;
      t.render(r, i, function (t) {
        e.$widgetId = t, e.$emit("render", t);
      });
    },
    methods: {
      reset: function reset() {
        t.reset(this.$widgetId);
      },
      execute: function execute() {
        t.execute(this.$widgetId);
      },
      emitVerify: function emitVerify(e) {
        this.$emit("verify", e);
      },
      emitExpired: function emitExpired() {
        this.$emit("expired");
      }
    },
    render: function render(e) {
      return e("div", {}, this.$slots["default"]);
    }
  };
});