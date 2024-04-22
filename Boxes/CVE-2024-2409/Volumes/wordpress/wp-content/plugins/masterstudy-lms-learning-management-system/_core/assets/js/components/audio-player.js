"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : "undefined" != typeof Symbol && arr[Symbol.iterator] || arr["@@iterator"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0); } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i["return"] && (_r = _i["return"](), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var MasterstudyAudioPlayer = /*#__PURE__*/function () {
  function MasterstudyAudioPlayer(player, options) {
    _classCallCheck(this, MasterstudyAudioPlayer);
    this.audioPlayer = typeof player === 'string' ? document.querySelector(player) : player;
    var opts = options || {};
    this.audioPlayer.classList.add('masterstudy-audio-player');
    this.isDevice = /ipad|iphone|ipod|android/i.test(window.navigator.userAgent.toLowerCase()) && !window.MSStream;
    this.playPauseBtn = this.audioPlayer.querySelector('.masterstudy-audio-player__play-pause-btn');
    this.loading = this.audioPlayer.querySelector('.masterstudy-audio-player__loading');
    this.sliders = this.audioPlayer.querySelectorAll('.masterstudy-audio-player__slider');
    this.progress = this.audioPlayer.querySelector('.masterstudy-audio-player__controls-progress');
    this.volumeBtn = this.audioPlayer.querySelector('.masterstudy-audio-player__volume-button');
    this.volumeControls = this.audioPlayer.querySelector('.masterstudy-audio-player__volume-controls');
    this.volumeProgress = this.volumeControls.querySelector('.masterstudy-audio-player__volume-progress');
    this.player = this.audioPlayer.querySelector('audio');
    this.currentTime = this.audioPlayer.querySelector('.masterstudy-audio-player__controls-current-time');
    this.totalTime = this.audioPlayer.querySelector('.masterstudy-audio-player__controls-total-time');
    this.speaker = this.audioPlayer.querySelector('.masterstudy-audio-player__volume-speaker');
    this.download = this.audioPlayer.querySelector('.masterstudy-audio-player__download');
    this.downloadLink = this.audioPlayer.querySelector('.masterstudy-audio-player__download-link');
    this.span = this.audioPlayer.querySelectorAll('.masterstudy-audio-player__message-offscreen');
    this.svg = this.audioPlayer.getElementsByTagName('svg');
    this.img = this.audioPlayer.getElementsByTagName('img');
    this.draggableClasses = ['masterstudy-audio-player__pin'];
    this.currentlyDragged = null;
    this.stopOthersOnPlay = opts.stopOthersOnPlay || true;
    this.enableKeystrokes = opts.enableKeystrokes || false;
    this.showTooltips = opts.showTooltips || false;
    this.showDeleteButton = opts.showDeleteButton || false;
    var self = this;
    this.labels = {
      volume: {
        open: 'Open Volume Controls',
        close: 'Close Volume Controls'
      },
      pause: 'Pause',
      play: 'Play',
      download: 'Download'
    };
    if (!this.enableKeystrokes) {
      for (var i = 0; i < this.span.length; i++) {
        this.span[i].outerHTML = '';
      }
    } else {
      window.addEventListener('keydown', this.pressKb.bind(self), false);
      window.addEventListener('keyup', this.unPressKb.bind(self), false);
      this.sliders[0].setAttribute('tabindex', 0);
      this.sliders[1].setAttribute('tabindex', 0);
      this.download.setAttribute('tabindex', -1);
      this.downloadLink.setAttribute('tabindex', -1);
      for (var j = 0; j < this.svg.length; j++) {
        this.svg[j].setAttribute('tabindex', 0);
        this.svg[j].setAttribute('focusable', true);
      }
      for (var k = 0; k < this.img.length; k++) {
        this.img[k].setAttribute('tabindex', 0);
      }
    }
    if (this.showTooltips) {
      this.playPauseBtn.setAttribute('title', this.labels.play);
      this.volumeBtn.setAttribute('title', this.labels.volume.open);
      this.downloadLink.setAttribute('title', this.labels.download);
    }
    if (opts.outlineControls || false) {
      this.audioPlayer.classList.add('masterstudy-audio-player_accessible');
    }
    if (opts.showDownloadButton || true) {
      this.showDownload();
    }
    this.initEvents();
    this.directionAware();
    this.overcomeIosLimitations();
    if ('autoplay' in this.player.attributes) {
      var promise = this.player.play();
      if (promise !== undefined) {
        promise.then(function () {
          var playPauseButton = self.player.parentElement.querySelector('.masterstudy-audio-player__play-pause-btn__icon');
          playPauseButton.attributes.d.value = 'M0 0h6v24H0zM12 0h6v24h-6z';
          self.playPauseBtn.setAttribute('aria-label', self.labels.pause);
          self.hasSetAttribute(self.playPauseBtn, 'title', self.labels.pause);
        })["catch"](function () {
          // eslint-disable-next-line no-console
          console.error('MasterStudy Audio Player Error: Autoplay has been prevented, because it is not allowed by this browser.');
        });
      }
    }
    if ('preload' in this.player.attributes && this.player.attributes.preload.value === 'none') {
      this.playPauseBtn.style.visibility = 'visible';
      this.loading.style.visibility = 'hidden';
    }
  }
  _createClass(MasterstudyAudioPlayer, [{
    key: "initEvents",
    value: function initEvents() {
      var _this = this;
      var self = this;
      self.audioPlayer.addEventListener('mousedown', function (event) {
        if (self.isDraggable(event.target)) {
          self.currentlyDragged = event.target;
          var handleMethod = self.currentlyDragged.dataset.method;
          var listener = self[handleMethod].bind(self);
          window.addEventListener('mousemove', listener, false);
          if (self.currentlyDragged.parentElement.parentElement === self.sliders[0]) {
            self.paused = self.player.paused;
            if (self.paused === false) self.togglePlay();
          }
          window.addEventListener('mouseup', function () {
            if (self.currentlyDragged !== false && self.currentlyDragged.parentElement.parentElement === self.sliders[0] && self.paused !== self.player.paused) {
              self.togglePlay();
            }
            self.currentlyDragged = false;
            window.removeEventListener('mousemove', listener, false);
          }, false);
        }
      });

      // for mobile touches
      self.audioPlayer.addEventListener('touchstart', function (event) {
        if (self.isDraggable(event.target)) {
          var _event$targetTouches = _slicedToArray(event.targetTouches, 1);
          self.currentlyDragged = _event$targetTouches[0];
          var handleMethod = self.currentlyDragged.target.dataset.method;
          var listener = self[handleMethod].bind(self);
          window.addEventListener('touchmove', listener, false);
          if (self.currentlyDragged.parentElement.parentElement === self.sliders[0]) {
            self.paused = self.player.paused;
            if (self.paused === false) self.togglePlay();
          }
          window.addEventListener('touchend', function () {
            if (self.currentlyDragged !== false && self.currentlyDragged.parentElement.parentElement === self.sliders[0] && self.paused !== self.player.paused) {
              self.togglePlay();
            }
            self.currentlyDragged = false;
            window.removeEventListener('touchmove', listener, false);
          }, false);
          event.preventDefault();
        }
      });
      this.playPauseBtn.addEventListener('click', this.togglePlay.bind(self));
      this.player.addEventListener('timeupdate', this.updateProgress.bind(self));
      this.player.addEventListener('volumechange', this.updateVolume.bind(self));
      this.player.volume = 0.81;
      this.player.addEventListener('loadedmetadata', function () {
        _this.totalTime.textContent = MasterstudyAudioPlayer.formatTime(_this.player.duration, _this.player);
      });
      this.player.addEventListener('seeking', function () {
        self.toggleLoadingIndicator(true);
      });
      this.player.addEventListener('seeked', function () {
        self.toggleLoadingIndicator(false);
      });
      this.player.addEventListener('canplay', function () {
        self.toggleLoadingIndicator(false);
      });
      this.player.addEventListener('ended', function () {
        MasterstudyAudioPlayer.playPausePlayer(self.player, true);
        self.player.currentTime = 0;
        self.playPauseBtn.setAttribute('aria-label', self.labels.play);
        self.hasSetAttribute(self.playPauseBtn, 'title', self.labels.play);
      });
      this.volumeBtn.addEventListener('click', this.showHideVolume.bind(self));
      document.addEventListener('click', function (event) {
        var isVolumeControlClick = self.volumeControls.contains(event.target) || self.volumeBtn.contains(event.target);
        if (!isVolumeControlClick) {
          self.volumeControls.classList.add('hidden');
          self.volumeBtn.setAttribute('aria-label', self.labels.volume.open);
          self.hasSetAttribute(self.volumeBtn, 'title', self.labels.volume.open);
          self.volumeBtn.classList.remove('open');
        }
      });
      window.addEventListener('resize', self.directionAware.bind(self));
      window.addEventListener('scroll', self.directionAware.bind(self));
      for (var i = 0; i < this.sliders.length; i++) {
        var pin = this.sliders[i].querySelector('.masterstudy-audio-player__pin');
        this.sliders[i].addEventListener('click', self[pin.dataset.method].bind(self));
      }
      this.downloadLink.addEventListener('click', this.downloadAudio.bind(self));
      this.player.load();
    }
  }, {
    key: "overcomeIosLimitations",
    value: function overcomeIosLimitations() {
      var self = this;
      if (this.isDevice) {
        // iOS does not support "canplay" event
        this.player.addEventListener('loadedmetadata', function () {
          self.toggleLoadingIndicator(false);
        });
        // iOS does not let "volume" property to be set programmatically
        this.audioPlayer.querySelector('.masterstudy-audio-player__volume').style.display = 'none';
        this.audioPlayer.querySelector('.masterstudy-audio-player__controls').style.marginRight = '0';
      }
    }
  }, {
    key: "isDraggable",
    value: function isDraggable(el) {
      var canDrag = false;
      if (typeof el.classList === 'undefined') return false; // fix for IE 11 not supporting classList on SVG elements

      for (var i = 0; i < this.draggableClasses.length; i++) {
        if (el.classList.contains(this.draggableClasses[i])) {
          canDrag = true;
        }
      }
      return canDrag;
    }
  }, {
    key: "inRange",
    value: function inRange(event) {
      var touch = ('touches' in event); // instanceof TouchEvent may also be used
      var rangeBox = this.getRangeBox(event);
      var sliderPositionAndDimensions = rangeBox.getBoundingClientRect();
      var direction = rangeBox.dataset.direction;
      var min = null;
      var max = null;
      if (direction === 'horizontal') {
        min = sliderPositionAndDimensions.x;
        max = min + sliderPositionAndDimensions.width;
        var clientX = touch ? event.touches[0].clientX : event.clientX;
        if (clientX < min || clientX > max) return false;
      } else {
        min = sliderPositionAndDimensions.top;
        max = min + sliderPositionAndDimensions.height;
        var clientY = touch ? event.touches[0].clientY : event.clientY;
        if (clientY < min || clientY > max) return false;
      }
      return true;
    }
  }, {
    key: "updateProgress",
    value: function updateProgress() {
      var current = this.player.currentTime;
      var percent = current / this.player.duration * 100;
      this.progress.setAttribute('aria-valuenow', percent);
      this.progress.style.width = "".concat(percent, "%");
      this.currentTime.textContent = MasterstudyAudioPlayer.formatTime(current, this.player);
    }
  }, {
    key: "updateVolume",
    value: function updateVolume() {
      this.volumeProgress.setAttribute('aria-valuenow', this.player.volume * 100);
      this.volumeProgress.style.height = "".concat(this.player.volume * 100, "%");
      if (this.player.volume >= 0.5) {
        this.speaker.attributes.d.value = 'M14.667 0v2.747c3.853 1.146 6.666 4.72 6.666 8.946 0 4.227-2.813 7.787-6.666 8.934v2.76C20 22.173 24 17.4 24 11.693 24 5.987 20 1.213 14.667 0zM18 11.693c0-2.36-1.333-4.386-3.333-5.373v10.707c2-.947 3.333-2.987 3.333-5.334zm-18-4v8h5.333L12 22.36V1.027L5.333 7.693H0z';
      } else if (this.player.volume < 0.5 && this.player.volume > 0.05) {
        this.speaker.attributes.d.value = 'M0 7.667v8h5.333L12 22.333V1L5.333 7.667M17.333 11.373C17.333 9.013 16 6.987 14 6v10.707c2-.947 3.333-2.987 3.333-5.334z';
      } else if (this.player.volume <= 0.05) {
        this.speaker.attributes.d.value = 'M0 7.667v8h5.333L12 22.333V1L5.333 7.667';
      }
    }
  }, {
    key: "getRangeBox",
    value: function getRangeBox(event) {
      var rangeBox = event.target;
      var el = this.currentlyDragged;
      if (event.type === 'click' && this.isDraggable(event.target)) {
        rangeBox = event.target.parentElement.parentElement;
      }
      if (event.type === 'mousemove') {
        rangeBox = el.parentElement.parentElement;
      }
      if (event.type === 'touchmove') {
        rangeBox = el.target.parentElement.parentElement;
      }
      return rangeBox;
    }
  }, {
    key: "getCoefficient",
    value: function getCoefficient(event) {
      var touch = ('touches' in event); // instanceof TouchEvent may also be used

      var slider = this.getRangeBox(event);
      var sliderPositionAndDimensions = slider.getBoundingClientRect();
      var coefficient = 0;
      if (slider.dataset.direction === 'horizontal') {
        var clientX = touch ? event.touches[0].clientX : event.clientX;
        var offsetX = clientX - sliderPositionAndDimensions.left;
        var width = sliderPositionAndDimensions.width;
        coefficient = offsetX / width;
      } else if (slider.dataset.direction === 'vertical') {
        var height = sliderPositionAndDimensions.height;
        var clientY = touch ? event.touches[0].clientY : event.clientY;
        var offsetY = clientY - sliderPositionAndDimensions.top;
        coefficient = 1 - offsetY / height;
      }
      return coefficient;
    }
  }, {
    key: "rewind",
    value: function rewind(event) {
      if (this.player.seekable && this.player.seekable.length) {
        // no seek if not (pre)loaded
        if (this.inRange(event)) {
          this.player.currentTime = this.player.duration * this.getCoefficient(event);
        }
      }
    }
  }, {
    key: "showVolume",
    value: function showVolume() {
      if (this.volumeBtn.getAttribute('aria-attribute') === this.labels.volume.open) {
        this.volumeControls.classList.remove('hidden');
        this.volumeBtn.classList.add('open');
        this.volumeBtn.setAttribute('aria-label', this.labels.volume.close);
        this.hasSetAttribute(this.volumeBtn, 'title', this.labels.volume.close);
      }
    }
  }, {
    key: "showHideVolume",
    value: function showHideVolume() {
      this.volumeControls.classList.toggle('hidden');
      if (this.volumeBtn.getAttribute('aria-label') === this.labels.volume.open) {
        this.volumeBtn.setAttribute('aria-label', this.labels.volume.close);
        this.hasSetAttribute(this.volumeBtn, 'title', this.labels.volume.close);
        this.volumeBtn.classList.add('open');
      } else {
        this.volumeBtn.setAttribute('aria-label', this.labels.volume.open);
        this.hasSetAttribute(this.volumeBtn, 'title', this.labels.volume.open);
        this.volumeBtn.classList.remove('open');
      }
    }
  }, {
    key: "changeVolume",
    value: function changeVolume(event) {
      if (this.inRange(event)) {
        this.player.volume = Math.round(this.getCoefficient(event) * 50) / 50;
      }
    }
  }, {
    key: "preloadNone",
    value: function preloadNone() {
      var self = this;
      if (!this.player.duration) {
        self.playPauseBtn.style.visibility = 'hidden';
        self.loading.style.visibility = 'visible';
      }
    }
  }, {
    key: "togglePlay",
    value: function togglePlay() {
      // this.preloadNone();
      if (this.player.paused) {
        if (this.stopOthersOnPlay) {
          MasterstudyAudioPlayer.stopOtherPlayers();
        }
        MasterstudyAudioPlayer.playPausePlayer(this.player);
        this.playPauseBtn.setAttribute('aria-label', this.labels.pause);
        this.hasSetAttribute(this.playPauseBtn, 'title', this.labels.pause);
      } else {
        MasterstudyAudioPlayer.playPausePlayer(this.player, true);
        this.playPauseBtn.setAttribute('aria-label', this.labels.play);
        this.hasSetAttribute(this.playPauseBtn, 'title', this.labels.play);
      }
    }
  }, {
    key: "hasSetAttribute",
    value: function hasSetAttribute(el, a, v) {
      if (this.showTooltips) {
        if (el.hasAttribute(a)) {
          el.setAttribute(a, v);
        }
      }
    }
  }, {
    key: "setCurrentTime",
    value: function setCurrentTime(time) {
      var pos = this.player.currentTime;
      var end = Math.floor(this.player.duration);
      if (pos + time < 0 && pos === 0) {
        this.player.currentTime = this.player.currentTime;
      } else if (pos + time < 0) {
        this.player.currentTime = 0;
      } else if (pos + time > end) {
        this.player.currentTime = end;
      } else {
        this.player.currentTime += time;
      }
    }
  }, {
    key: "setVolume",
    value: function setVolume(volume) {
      if (this.isDevice) return;
      var vol = this.player.volume;
      if (vol + volume >= 0 && vol + volume < 1) {
        this.player.volume += volume;
      } else if (vol + volume <= 0) {
        this.player.volume = 0;
      } else {
        this.player.volume = 1;
      }
    }
  }, {
    key: "unPressKb",
    value: function unPressKb(event) {
      var evt = event || window.event;
      if (this.seeking && (evt.keyCode === 37 || evt.keyCode === 39)) {
        this.togglePlay();
        this.seeking = false;
      }
    }
  }, {
    key: "pressKb",
    value: function pressKb(event) {
      var evt = event || window.event;
      switch (evt.keyCode) {
        case 13: // Enter
        case 32:
          // Spacebar
          if (document.activeElement.parentNode === this.playPauseBtn) {
            this.togglePlay();
          } else if (document.activeElement.parentNode === this.volumeBtn || document.activeElement === this.sliders[1]) {
            if (document.activeElement === this.sliders[1]) {
              try {
                // IE 11 not supporting programmatic focus on svg elements
                this.volumeBtn.children[0].focus();
              } catch (error) {
                this.volumeBtn.focus();
              }
            }
            this.showHideVolume();
          }
          if (evt.keyCode === 13 && this.showDownload && document.activeElement.parentNode === this.downloadLink) {
            this.downloadLink.focus();
          }
          break;
        case 37:
        case 39:
          // horizontal Arrows
          if (document.activeElement === this.sliders[0]) {
            this.setCurrentTime(evt.keyCode === 37 ? -5 : 5);
            if (!this.player.paused && this.player.seeking) {
              this.togglePlay();
              this.seeking = true;
            }
          }
          break;
        case 38:
        case 40:
          // vertical Arrows
          if (document.activeElement.parentNode === this.volumeBtn || document.activeElement === this.sliders[1]) {
            this.setVolume(evt.keyCode === 38 ? 0.05 : -0.05);
          }
          if (document.activeElement.parentNode === this.volumeBtn) {
            this.showVolume();
          }
          break;
        default:
          break;
      }
    }
  }, {
    key: "toggleLoadingIndicator",
    value: function toggleLoadingIndicator(show) {
      this.playPauseBtn.style.visibility = show ? 'hidden' : 'visible';
      this.loading.style.visibility = show ? 'visible' : 'hidden';
    }
  }, {
    key: "showDownload",
    value: function showDownload() {
      this.download.classList.add('masterstudy-audio-player__download_visible');
    }
  }, {
    key: "downloadAudio",
    value: function downloadAudio() {
      var src = this.player.currentSrc;
      var name = src.split('/').reverse()[0];
      this.downloadLink.setAttribute('href', src);
      this.downloadLink.setAttribute('download', name);
    }
  }, {
    key: "directionAware",
    value: function directionAware() {
      this.volumeControls.classList.remove('top', 'middle', 'bottom');
      if (window.innerHeight < 250) {
        this.volumeControls.classList.add('middle');
      } else if (this.audioPlayer.getBoundingClientRect().top < 180) {
        this.volumeControls.classList.add('bottom');
      } else {
        this.volumeControls.classList.add('top');
      }
    }
  }], [{
    key: "init",
    value: function init(options) {
      var players = document.querySelectorAll(options.selector);
      players.forEach(function (player) {
        /* eslint-disable no-new */
        new MasterstudyAudioPlayer(player, options);
      });
    }
  }, {
    key: "formatTime",
    value: function formatTime(time, player) {
      if (!isNaN(player.duration) && isFinite(player.duration)) {
        var min = Math.floor(time / 60);
        var sec = Math.floor(time % 60);
        return "".concat(min < 10 ? "0".concat(min) : min, ":").concat(sec < 10 ? "0".concat(sec) : sec);
      }
      return '00:00';
    }
  }, {
    key: "playPausePlayer",
    value: function playPausePlayer(player) {
      var pause = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var playPauseButton = player.parentElement.querySelector('.masterstudy-audio-player__play-pause-btn__icon');
      playPauseButton.attributes.d.value = pause ? 'M18 12L0 24V0' : 'M0 0h6v24H0zM12 0h6v24h-6z';
      if (pause) {
        player.pause();
      } else {
        player.play();
      }
    }
  }, {
    key: "stopOtherPlayers",
    value: function stopOtherPlayers() {
      var players = document.querySelectorAll('.masterstudy-audio-player audio');
      for (var i = 0; i < players.length; i++) {
        MasterstudyAudioPlayer.playPausePlayer(players[i], true);
      }
    }
  }]);
  return MasterstudyAudioPlayer;
}();