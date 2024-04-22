"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var MasterstudyVideoRecoder = /*#__PURE__*/function () {
  function MasterstudyVideoRecoder(container, options) {
    _classCallCheck(this, MasterstudyVideoRecoder);
    this.recorder = container;
    this.options = options || {};
    if (typeof container === "string") {
      this.recorder = document.querySelector(container);
    }
    if (!this.recorder) {
      return;
    }

    // Initial settings
    this.recorder.classList.add("masterstudy-video__recorder");
    this.recorder.setAttribute("data-recording", "false");
    this.recorder.setAttribute("data-recorded", "false");

    // Defaults
    this.controls = {
      startRecorderBtn: "",
      startBtn: ".masterstudy-video__recorder-btn__record",
      stopBtn: ".masterstudy-video__recorder-btn__stop",
      pauseBtn: ".masterstudy-video__recorder-btn__pause",
      resumeBtn: ".masterstudy-video__recorder-btn__resume",
      deleteBtn: ".masterstudy-video__recorder-btn__delete",
      microphoneBtn: ".masterstudy-video__recorder-btn__microphone",
      videoRecorderInput: ".masterstudy-video__recorder-video",
      videoPlayerInput: ".masterstudy-video__recorder-player",
      videoTimeline: ".masterstudy-video__recorder__timeline"
    };
    this.constraints = {
      audio: true,
      video: {
        facingMode: "user",
        //environment and user
        width: {
          min: 640,
          ideal: 1280,
          max: 1920
        },
        height: {
          min: 480,
          ideal: 720,
          max: 1080
        }
      }
    };
    this.timerInterval;
    this.videoChunks = [];
    this.isRecording = false;
    this.isStopped = false;
    this.isMuted = false;
    this.stopTimer = false;
    this.startTime = Date.now();
    this.videoType = "video/mp4";
    this.isHidden = this.options.isHidden || false;
    this.clearSource = this.options.clearSource || false;
    this.recorderActions = [];
    this.currentMediaStream = null;
    this.mediaStreamStopped = false;
    if (this.isHidden) {
      this.recorder.classList.add("masterstudy-video__recorder_hidden");
    } else {
      this.recorder.classList.remove("masterstudy-video__recorder_hidden");
    }

    // set Controls
    this.setControls(this.options);
    this.startBtn = this.getControl(this.controls.startBtn);
    this.stopBtn = this.getControl(this.controls.stopBtn);
    this.pauseBtn = this.getControl(this.controls.pauseBtn);
    this.resumeBtn = this.getControl(this.controls.resumeBtn);
    this.deleteBtn = this.getControl(this.controls.deleteBtn);
    this.microphoneBtn = this.getControl(this.controls.microphoneBtn);
    this.startRecorderBtn = this.getControl(this.controls.startRecorderBtn);
    this.videoPlayerInput = this.getControl(this.controls.videoPlayerInput);
    this.videoRecorderInput = this.getControl(this.controls.videoRecorderInput);
    this.videoTimeline = this.getControl(this.controls.videoTimeline);

    // Set constraint
    this.setConstraints(this.options.constraints || {});
  }
  _createClass(MasterstudyVideoRecoder, [{
    key: "startRecording",
    value: function startRecording() {
      if (this.clearSource) {
        this.videoPlayerInput.src = "";
        this.videoRecorderInput.src = "";
        this.recorder.setAttribute("data-recording", false);
        this.recorder.setAttribute("data-recorded", false);
      }
      this.videoChunks = [];
      //Start video stream
      return this.startVideoStream(this.videoStream.bind(this));
    }
  }, {
    key: "recordVideo",
    value: function recordVideo(mediaRecorder, mediaStream, event) {
      if ("inactive" === mediaRecorder.state) {
        if (!this.mediaStreamStopped) {
          // TODO: Video Stream is running second time for IOS. Needs to be fixed.
          if (this.isIOS()) {
            this.startVideoStream(this.videoStream.bind(this));
          }
          mediaRecorder.start();
        }
        this.isRecording = true;
        this.isStopped = false;
        this.stopTimer = false;
        this.startTime = Date.now();
        this.recorder.setAttribute("data-recording", true);
        this.changeControlAttributes(this.startBtn, "recordAlt", "Stop Recording");
        this.changeControlAttributes(this.microphoneBtn, "record");
        this.timerInterval = setInterval(this.updateRecordingTime.bind(this), 1 * 1000);
        this.doAction("onRecord", mediaStream, mediaRecorder, event);
      } else {
        if (!this.stopBtn && !this.isStopped) {
          this.stopRecording(mediaRecorder, mediaStream);
          this.isStopped = true;
          this.recorder.setAttribute("data-recorded", true);
          this.changeControlAttributes(this.startBtn, "camera", "Start Recording");
          if (!this.isMuted) {
            this.changeControlAttributes(this.microphoneBtn, "mic");
          } else {
            this.changeControlAttributes(this.microphoneBtn, "micMuted");
          }
        }
      }
    }
  }, {
    key: "pauseRecording",
    value: function pauseRecording(mediaRecorder, mediaStream, event) {
      if ("recording" === mediaRecorder.state) {
        mediaRecorder.pause();
        this.stopTimer = true;
        this.changeControlAttributes(this.resumeBtn, "play", "Resume");
        this.doAction("onPause", mediaStream, mediaRecorder, event);
      }
    }
  }, {
    key: "resumeRecording",
    value: function resumeRecording(mediaRecorder, mediaStream, event) {
      if ("paused" === mediaRecorder.state) {
        mediaRecorder.resume();
        this.stopTimer = false;
        this.startTime = Date.now();
        this.changeControlAttributes(this.startBtn, "recordAlt", "Stop Recording");
        this.doAction("onResume", mediaStream, mediaRecorder, event);
      }
    }
  }, {
    key: "stopRecording",
    value: function stopRecording(mediaRecorder, mediaStream) {
      mediaRecorder.stop();
      this.videoTimeline.setAttribute("data-minutes", 0);
      this.videoTimeline.setAttribute("data-seconds", 0);
      this.turnOffCameraAndMic(mediaStream);
      clearInterval(this.timerInterval);
    }
  }, {
    key: "deleteRecording",
    value: function deleteRecording(mediaRecorder, mediaStream, event) {
      this.videoPlayerInput.src = "";
      this.videoRecorderInput.src = "";
      this.recorder.setAttribute("data-recording", false);
      this.recorder.setAttribute("data-recorded", false);
      this.doAction("onDelete", mediaStream, mediaRecorder, event);
    }
  }, {
    key: "showRecorder",
    value: function showRecorder() {
      this.recorder.classList.remove("masterstudy-video__recorder_hidden");
    }
  }, {
    key: "hideRecorder",
    value: function hideRecorder() {
      this.recorder.classList.add("masterstudy-video__recorder_hidden");
    }
  }, {
    key: "toggleMicrophoneSound",
    value: function toggleMicrophoneSound(mediaRecorder) {
      this.toggleStreamAudioSound(mediaRecorder, this.isMuted);
      if (!this.isMuted) {
        this.changeControlAttributes(this.microphoneBtn, "micMuted");
      } else {
        this.changeControlAttributes(this.microphoneBtn, "mic");
      }
      this.isMuted = !this.isMuted;
    }
  }, {
    key: "videoStream",
    value: function videoStream(mediaStream) {
      var self = this;
      self.currentMediaStream = mediaStream;
      if ("srcObject" in self.videoRecorderInput) {
        self.videoRecorderInput.srcObject = mediaStream;
        self.videoRecorderInput.muted = true;
      } else {
        self.videoRecorderInput.src = window.URL.createObjectURL(mediaStream); // old version
      }

      self.videoRecorderInput.onloadedmetadata = function (event) {
        event.target.play();
      };
      var mediaRecorder = new MediaRecorder(mediaStream, {
        type: self.videoType
      });
      mediaRecorder.ondataavailable = function (event) {
        if (event.data.size > 0) {
          self.startTime = Date.now();
          self.videoChunks.push(event.data);
        }
      };
      mediaRecorder.onstop = function (event) {
        var videoBlob = new Blob(self.videoChunks, {
          type: self.videoType
        });
        self.videoChunks = [];
        self.videoPlayerInput.src = window.URL.createObjectURL(videoBlob);
        if (videoBlob && videoBlob.size > 0) {
          self.doAction("onStop", videoBlob, self.currentMediaStream, mediaRecorder);
        }
        self.currentMediaStream = null;
      };
      this.recorderControlEvents(mediaRecorder, mediaStream);
    }
  }, {
    key: "recorderControlEvents",
    value: function recorderControlEvents(mediaRecorder, mediaStream) {
      var self = this;
      var buttons = [{
        btn: self.startBtn,
        handler: self.recordVideo
      }, {
        btn: self.pauseBtn,
        handler: self.pauseRecording
      }, {
        btn: self.resumeBtn,
        handler: self.resumeRecording
      }, {
        btn: self.stopBtn,
        handler: self.stopRecording
      }, {
        btn: self.deleteBtn,
        handler: self.deleteRecording
      }, {
        btn: self.microphoneBtn,
        handler: self.toggleMicrophoneSound
      }];
      buttons.forEach(function (_ref) {
        var btn = _ref.btn,
          handler = _ref.handler;
        if (btn) {
          self.addRecorderEvent(btn, "click", function (event) {
            handler.call(self, mediaRecorder, mediaStream, event);
          });
        }
      });
    }
  }, {
    key: "startVideoStream",
    value: function startVideoStream(streamCallback) {
      var userMedia = this.getUserMedia(this.constraints);
      if (userMedia) {
        return userMedia.then(function (mediaStream) {
          streamCallback(mediaStream);
          return true;
        })["catch"](function (err) {
          console.log(err.name, err.message);
          return false;
        });
      }
    }
  }, {
    key: "getUserMedia",
    value: function getUserMedia() {
      if (window.navigator.mediaDevices === undefined) {
        window.navigator.mediaDevices = {};
        window.navigator.mediaDevices.getUserMedia = function (constraintObj) {
          var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
          if (!getUserMedia) {
            return Promise.reject(new Error("getUserMedia is not implemented in this browser"));
          }
          return new Promise(function (resolve, reject) {
            getUserMedia.call(navigator, constraintObj, resolve, reject);
          });
        };
      }
      return window.navigator.mediaDevices.getUserMedia(this.constraints);
    }
  }, {
    key: "updateRecordingTime",
    value: function updateRecordingTime() {
      if (!this.stopTimer) {
        var timestamp = new Date(Date.now() - this.startTime);
        var minutes = timestamp.getMinutes();
        var seconds = timestamp.getSeconds();
        minutes = minutes < 10 ? "0".concat(minutes) : minutes;
        seconds = seconds < 10 ? "0".concat(seconds) : seconds;
        this.videoTimeline.innerHTML = "".concat(minutes, ":").concat(seconds);
      }
    }
  }, {
    key: "turnOffCameraAndMic",
    value: function turnOffCameraAndMic(mediaStream) {
      if (mediaStream) {
        var tracks = mediaStream.getTracks();
        tracks.forEach(function (track) {
          track.stop();
        });
        this.mediaStreamStopped = true;
        mediaStream = null;
      }
    }
  }, {
    key: "toggleStreamAudioSound",
    value: function toggleStreamAudioSound(mediaRecorder, soundEnbaled) {
      var audioTracks = mediaRecorder.stream.getAudioTracks();
      audioTracks.forEach(function (track) {
        track.enabled = soundEnbaled;
      }, soundEnbaled);
    }
  }, {
    key: "setControls",
    value: function setControls(controls) {
      controls = controls || {};
      if (controls.constraints) {
        delete controls.constraints;
      }
      Object.assign(this.controls, controls);
    }
  }, {
    key: "getControl",
    value: function getControl(control, all) {
      if (typeof control === "string" && control) {
        if (all) {
          return this.recorder.querySelectorAll(control);
        } else {
          return this.recorder.querySelector(control);
        }
      }
      return null;
    }
  }, {
    key: "setConstraints",
    value: function setConstraints(constraints) {
      Object.assign(this.constraints, constraints);
    }
  }, {
    key: "addRecorderEvent",
    value: function addRecorderEvent(element, event, callback) {
      if (element) {
        element.addEventListener(event, callback);
      }
    }
  }, {
    key: "changeControlAttributes",
    value: function changeControlAttributes(control, icon, text) {
      var pathElements = control.querySelectorAll("svg path");
      pathElements.forEach(function (path, i) {
        path.attributes.d.value = this.getControlcon(icon)[i] || "";
      }, this);
      if (text) {
        var textElements = control.querySelectorAll("span");
        textElements.forEach(function (span, i) {
          if (Array.isArray(text)) {
            span.innerHTML = text[i];
          } else {
            span.innerHTML = text;
          }
        }, this);
      }
    }
  }, {
    key: "getControlcon",
    value: function getControlcon(icon) {
      var icons = {
        record: ["M8 13A5 5 0 1 0 8 3a5 5 0 0 0 0 10z"],
        mic: ["M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0V3z", "M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"],
        micMuted: ["M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4.02 4.02 0 0 0 12 8V7a.5.5 0 0 1 1 0v1zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a4.973 4.973 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4zm3-9v4.879L5.158 2.037A3.001 3.001 0 0 1 11 3z", "M9.486 10.607 5 6.12V8a3 3 0 0 0 4.486 2.607zm-7.84-9.253 12 12 .708-.708-12-12-.708.708z"],
        camera: ["M2.66675 3.65479C1.56218 3.65479 0.666748 4.55022 0.666748 5.65479V12.3215C0.666748 13.4261 1.56218 14.3215 2.66675 14.3215H8.66675C9.77135 14.3215 10.6667 13.4261 10.6667 12.3215V5.65479C10.6667 4.55022 9.77135 3.65479 8.66675 3.65479H2.66675ZM15.3334 6.67824V11.298C15.3334 11.8633 14.8752 12.3215 14.3099 12.3215C14.1079 12.3215 13.9103 12.2617 13.7422 12.1496L12.2969 11.1861C12.1115 11.0624 12.0001 10.8543 12.0001 10.6313V7.34491C12.0001 7.12201 12.1115 6.91385 12.2969 6.79021L13.7422 5.82667C13.9103 5.71459 14.1079 5.65479 14.3099 5.65479C14.8752 5.65479 15.3334 6.11301 15.3334 6.67824Z"],
        recordAlt: ["M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"],
        pause: ["M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z", "M5 6.25a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0v-3.5zm3.5 0a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0v-3.5z"],
        play: ["M0 12V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm6.79-6.907A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"]
      };
      return icons[icon] || [];
    }
  }, {
    key: "addAction",
    value: function addAction(action, callback) {
      if (!this.recorderActions[action]) {
        this.recorderActions[action] = [];
      }
      this.recorderActions[action].push(callback);
    }
  }, {
    key: "doAction",
    value: function doAction(action) {
      for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
        args[_key - 1] = arguments[_key];
      }
      if (this.recorderActions[action]) {
        this.recorderActions[action].forEach(function (callback) {
          callback.apply(void 0, args);
        });
      }
    }
  }, {
    key: "isIOS",
    value: function isIOS() {
      return ['iPad Simulator', 'iPhone Simulator', 'iPod Simulator', 'iPad', 'iPhone', 'iPod'].includes(navigator.platform);
    }
  }], [{
    key: "init",
    value: function init(container, options) {
      if (typeof container === "string" && container.startsWith(".")) {
        var recorders = document.querySelectorAll(container);
        recorders.forEach(function (recorder) {
          new MasterstudyVideoRecoder(recorder, options);
        });
      } else {
        new MasterstudyVideoRecoder(container, options);
      }
    }
  }]);
  return MasterstudyVideoRecoder;
}();