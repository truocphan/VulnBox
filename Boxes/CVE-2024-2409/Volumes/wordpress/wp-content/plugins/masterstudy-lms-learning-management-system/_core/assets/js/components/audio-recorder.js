"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var MasterstudyAudioRecorder = /*#__PURE__*/function () {
  function MasterstudyAudioRecorder(container, options) {
    _classCallCheck(this, MasterstudyAudioRecorder);
    this.recorder = container;
    options = options || {};
    if (typeof container === 'string') {
      this.recorder = document.querySelector(container);
    }
    if (!this.recorder) {
      return;
    }
    this.directRecording = options.directRecording || false;
    this.recorder.classList.add('masterstudy-audio__recorder');
    this.recorder.setAttribute('data-recorded', 'false');
    this.recorder.setAttribute('data-recording', this.directRecording ? 'true' : 'false');
    if (options.isHidden || false) {
      this.recorder.classList.add('masterstudy-audio__recorder_hidden');
    } else {
      this.recorder.classList.remove('masterstudy-audio__recorder_hidden');
    }
    if (options.darkMode) {
      this.recorder.classList.add('masterstudy-audio__recorder_dark-mode');
    }

    // Defaults
    this.controls = {
      startBtn: '.masterstudy-audio__recorder-btn_start',
      pauseBtn: '.masterstudy-audio__recorder-btn_pause',
      stopBtn: '.masterstudy-audio__recorder-btn_stop',
      controls: '.masterstudy-audio__recorder-controls',
      activeBtn: '.masterstudy-audio__recorder-btn_primary',
      visualizer: '.masterstudy-audio__recorder-progress__visualizer',
      recorderState: '.masterstudy-audio__recorder-state',
      audioTimeline: '.masterstudy-audio__recorder-progress__timeline'
    };
    this.timerInterval;
    this.isStarted = false;
    this.isResumed = false;
    this.nodeNumber = 16; //Number power of two
    this.constraints = {
      audio: true,
      video: false
    };
    this.audioChunks = [];
    this.audioType = 'audio/webm';
    this.audioFormat = 'mp3';
    this.startTime = Date.now();
    this.timerStop = false;
    this.timerMinutes = 0;
    this.timerSeconds = 0;
    this.recorderActions = [];

    // set Controls
    this.startBtn = this.getControl(this.controls.startBtn);
    this.stopBtn = this.getControl(this.controls.stopBtn);
    this.pauseBtn = this.getControl(this.controls.pauseBtn);
    this.resumeBtn = this.getControl(this.controls.resumeBtn);
    this.deleteBtn = this.getControl(this.controls.deleteBtn);
    this.visualizer = this.getControl(this.controls.visualizer);
    this.audioTimeline = this.getControl(this.controls.audioTimeline);
    this.recorderState = this.getControl(this.controls.recorderState);
    this.audioInput = this.recorder.querySelector('audio');
    this.visualizerNodes = this.createVisualizerNodes();
  }
  _createClass(MasterstudyAudioRecorder, [{
    key: "startRecording",
    value: function startRecording() {
      return this.startAudioStream(this.audioStream.bind(this));
    }
  }, {
    key: "recordAudio",
    value: function recordAudio(mediaRecorder, mediaStream, event) {
      if ('inactive' === mediaRecorder.state) {
        mediaRecorder.start();
        this.setActiveControl(this.stopBtn);
        this.recorder.setAttribute('data-recording', true);
        this.recorder.setAttribute('data-recorded', false);
        this.audioVisualizer(mediaStream);
        this.isStarted = true;
        this.timerStop = false;
        this.startTime = Date.now();
        this.timerInterval = setInterval(this.updateRecordingTime.bind(this), 1 * 1000);
        this.doAction('onRecord', mediaStream, mediaRecorder, event);
      }
    }
  }, {
    key: "pauseRecording",
    value: function pauseRecording(mediaRecorder, mediaStream, event) {
      switch (mediaRecorder.state) {
        case "recording":
          mediaRecorder.pause();
          this.setActiveControl(this.pauseBtn);
          this.changeControlAttributes(this.recorderState, 'pause', 'Paused');
          this.changeControlAttributes(this.pauseBtn, 'mic', 'Resume');
          this.audioVisualizer(mediaStream).close();
          this.timerStop = true;
          this.doAction('onPause', mediaStream, mediaRecorder, event);
          break;
        case "paused":
          mediaRecorder.resume();
          this.setActiveControl(this.stopBtn);
          this.changeControlAttributes(this.recorderState, 'record', 'Recording');
          this.changeControlAttributes(this.pauseBtn, 'pause', 'Pause');
          this.audioVisualizer(mediaStream).resume();
          this.timerStop = false;
          this.isResumed = true;
          this.startTime = Date.now();
          this.doAction('onResume', mediaStream, mediaRecorder, event);
          break;
        default:
          console.log('Somethings went wrong.');
          break;
      }
    }
  }, {
    key: "masterstudyLmsIsSafari",
    value: function masterstudyLmsIsSafari() {
      var userAgent = navigator.userAgent.toLowerCase();
      return userAgent.includes('safari') && !userAgent.includes('chrome');
    }
  }, {
    key: "stopRecording",
    value: function stopRecording(mediaRecorder, mediaStream, event) {
      mediaRecorder.stop();
      mediaStream.getTracks().forEach(function (track) {
        return track.stop();
      });
      this.setActiveControl(this.startBtn);
      if (!this.directRecording) {
        this.recorder.setAttribute('data-recording', false);
        this.recorder.setAttribute('data-recorded', true);
      }
      this.changeControlAttributes(this.pauseBtn, 'pause', 'Pause');
      this.changeControlAttributes(this.recorderState, 'record', 'Recording');
      this.isStarted = false;
      this.timerStop = false;
      this.audioVisualizer(mediaStream).close();
      clearInterval(this.timerInterval);
      this.audioTimeline.innerHTML = "00:00";
    }
  }, {
    key: "updateVisualizer",
    value: function updateVisualizer(analyserNode) {
      var _this = this;
      var self = this;
      requestAnimationFrame(function () {
        self.updateVisualizer(analyserNode);
      });
      var data = new Uint8Array(this.nodeNumber * 4);
      analyserNode.getByteFrequencyData(data);
      this.visualizerNodes.forEach(function (node, i) {
        node.style.setProperty('--visualizer-bar-height', data[i] / 255 * (1 + i / _this.nodeNumber));
      });
    }
  }, {
    key: "createVisualizerNodes",
    value: function createVisualizerNodes() {
      var _this2 = this;
      var nodes = Array.from({
        length: this.nodeNumber
      }, function (n, i) {
        var node = document.createElement('div');
        node.className = _this2.controls.visualizer.replace(/(?:^|\s)[#.](\w+)/g, '$1') + '-bar';
        node.style.animationDuration = "".concat(Math.random() * (i * 0.8 - 0.2) + 0.2, "s");
        _this2.visualizer.appendChild(node);
        return node;
      });
      return nodes;
    }
  }, {
    key: "audioStream",
    value: function audioStream(mediaStream) {
      var self = this;
      var mediaRecorder = new MediaRecorder(mediaStream, {
        mimeType: self.audioType
      });
      mediaRecorder.ondataavailable = function (event) {
        if (event.data.size > 0) {
          self.audioChunks.push(event.data);
        }
      };
      mediaRecorder.onstop = function (event) {
        var audioBlob = new Blob(self.audioChunks, {
          type: self.audioType
        });
        self.audioChunks = [];
        self.audioInput.src = window.URL.createObjectURL(audioBlob);
        self.doAction('beforeStop', self);
        var audioContext = new AudioContext();
        var fileReader = new FileReader();
        fileReader.onloadend = function () {
          var arrayBuffer = fileReader.result;
          audioContext.decodeAudioData(arrayBuffer, function (audioBuffer) {
            var audioWav = self.audioBufferToWav(audioBuffer);
            var format = self.audioFormat.toLowerCase();
            var convertedBlob = audioBlob;
            convertedBlob = 'wav' === format ? audioWav : convertedBlob;
            if ('mp3' === format) {
              if (audioWav.channels === 1) {
                convertedBlob = self.wavToMp3(1, audioWav.sampleRate, audioWav.data);
              }
              if (audioWav.channels === 2) {
                convertedBlob = self.wavToMp3(2, audioWav.sampleRate, audioWav.left, audioWav.right);
              }
            }
            self.audioInput.src = window.URL.createObjectURL(convertedBlob);
            self.doAction('onStop', convertedBlob, mediaStream, mediaRecorder);
          });
        };
        fileReader.readAsArrayBuffer(audioBlob);
      };
      self.recorderControlEvents(mediaRecorder, mediaStream);
      if (self.directRecording) {
        this.recordAudio(mediaRecorder, mediaStream);
      }
    }
  }, {
    key: "startAudioStream",
    value: function startAudioStream(streamCallback) {
      this.audioType = this.masterstudyLmsIsSafari() ? 'audio/mp4' : 'audio/webm';
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
    key: "audioVisualizer",
    value: function audioVisualizer(mediaStream) {
      var self = this;
      var audioCtx = new AudioContext(mediaStream);
      var audioSource = audioCtx.createMediaStreamSource(mediaStream);
      if (audioSource) {
        var analyserNode = new AnalyserNode(audioCtx, {
          fftSize: Math.max(self.nodeNumber * 4, 32),
          maxDecibels: -20,
          minDecibels: -80,
          smoothingTimeConstant: 0.8
        });
        audioSource.connect(analyserNode);
        self.updateVisualizer(analyserNode);
      }
      return audioCtx;
    }
  }, {
    key: "getUserMedia",
    value: function getUserMedia() {
      if (window.navigator.mediaDevices === undefined) {
        window.navigator.mediaDevices = {};
        window.navigator.mediaDevices.getUserMedia = function (constraintObj) {
          var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
          if (!getUserMedia) {
            return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
          }
          return new Promise(function (resolve, reject) {
            getUserMedia.call(navigator, constraintObj, resolve, reject);
          });
        };
      }
      return window.navigator.mediaDevices.getUserMedia(this.constraints);
    }
  }, {
    key: "recorderControlEvents",
    value: function recorderControlEvents(mediaRecorder, mediaStream) {
      var self = this;
      var buttons = [{
        btn: self.startBtn,
        handler: self.startRecording
      }, {
        btn: self.pauseBtn,
        handler: self.pauseRecording
      }, {
        btn: self.stopBtn,
        handler: self.stopRecording
      }];
      buttons.forEach(function (_ref) {
        var btn = _ref.btn,
          handler = _ref.handler;
        if (btn) {
          self.addRecorderEvent(btn, "click", function (event) {
            event.preventDefault();
            handler.call(self, mediaRecorder, mediaStream, event);
          });
        }
      });
    }
  }, {
    key: "updateRecordingTime",
    value: function updateRecordingTime() {
      if (!this.timerStop) {
        if (this.isResumed) {
          this.startTime = this.startTime - (this.timerMinutes * 60000 + this.timerSeconds * 1000);
          this.timerMinutes = 0;
          this.timerSeconds = 0;
          this.isResumed = false;
        }
        var timestamp = new Date(Date.now() - this.startTime);
        var minutes = timestamp.getMinutes();
        var seconds = timestamp.getSeconds();
        this.timerMinutes = minutes;
        this.timerSeconds = seconds;
        minutes = minutes < 10 ? "0".concat(minutes) : minutes;
        seconds = seconds < 10 ? "0".concat(seconds) : seconds;
        this.audioTimeline.innerHTML = "".concat(minutes, ":").concat(seconds);
      }
    }
  }, {
    key: "addRecorderEvent",
    value: function addRecorderEvent(element, event, callback) {
      if (element) {
        element.addEventListener(event, callback);
      }
    }
  }, {
    key: "getControl",
    value: function getControl(control, all) {
      if (typeof control === 'string' && control) {
        return all ? this.recorder.querySelectorAll(control) : this.recorder.querySelector(control);
      }
      if (control instanceof HTMLElement) {
        return control;
      }
      return null;
    }
  }, {
    key: "setActiveControl",
    value: function setActiveControl(btn) {
      btn = typeof btn === 'string' ? this.getControl(btn) : btn;
      if (btn) {
        var activeClass = this.controls.activeBtn.replace(/(?:^|\s)[#.](\w+)/g, '$1');
        var siblings = Array.from(btn.parentNode.children).filter(function (child) {
          return child !== btn;
        });
        siblings.forEach(function (sigling) {
          sigling.classList.remove(activeClass);
        });
        btn.classList.add(activeClass);
      }
    }
  }, {
    key: "changeControlAttributes",
    value: function changeControlAttributes(control, icon, text) {
      var pathElements = control.querySelectorAll('svg path');
      pathElements.forEach(function (path, i) {
        path.attributes.d.value = this.getControlcon(icon)[i] || '';
      }, this);
      if (text) {
        var textElements = control.querySelectorAll('span');
        textElements.forEach(function (span, i) {
          span.innerHTML = Array.isArray(text) ? text[i] : text;
        }, this);
      }
    }
  }, {
    key: "getControlcon",
    value: function getControlcon(icon) {
      var icons = {
        record: ['M8 13A5 5 0 1 0 8 3a5 5 0 0 0 0 10z'],
        mic: ['M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0V3z', 'M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z'],
        micMuted: ['M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4.02 4.02 0 0 0 12 8V7a.5.5 0 0 1 1 0v1zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a4.973 4.973 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4zm3-9v4.879L5.158 2.037A3.001 3.001 0 0 1 11 3z', 'M9.486 10.607 5 6.12V8a3 3 0 0 0 4.486 2.607zm-7.84-9.253 12 12 .708-.708-12-12-.708.708z'],
        camera: ['M2.66675 3.65479C1.56218 3.65479 0.666748 4.55022 0.666748 5.65479V12.3215C0.666748 13.4261 1.56218 14.3215 2.66675 14.3215H8.66675C9.77135 14.3215 10.6667 13.4261 10.6667 12.3215V5.65479C10.6667 4.55022 9.77135 3.65479 8.66675 3.65479H2.66675ZM15.3334 6.67824V11.298C15.3334 11.8633 14.8752 12.3215 14.3099 12.3215C14.1079 12.3215 13.9103 12.2617 13.7422 12.1496L12.2969 11.1861C12.1115 11.0624 12.0001 10.8543 12.0001 10.6313V7.34491C12.0001 7.12201 12.1115 6.91385 12.2969 6.79021L13.7422 5.82667C13.9103 5.71459 14.1079 5.65479 14.3099 5.65479C14.8752 5.65479 15.3334 6.11301 15.3334 6.67824Z'],
        recordAlt: ['M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'],
        pause: ['M0 0 V14 H3.11111 V0 H0 Z M7.70776 0 V14 H10.81887 V0 H7.70776 Z'],
        play: ['M0 12V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm6.79-6.907A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z']
      };
      return icons[icon] || [];
    }
  }, {
    key: "showRecorder",
    value: function showRecorder() {
      this.recorder.classList.remove("masterstudy-audio__recorder_hidden");
    }
  }, {
    key: "hideRecorder",
    value: function hideRecorder() {
      this.recorder.classList.add("masterstudy-audio__recorder_hidden");
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
    key: "audioBufferToWav",
    value: function audioBufferToWav(aBuffer) {
      var numOfChan = aBuffer.numberOfChannels,
        btwLength = aBuffer.length * numOfChan * 2 + 44,
        btwArrBuff = new ArrayBuffer(btwLength),
        btwView = new DataView(btwArrBuff),
        btwChnls = [],
        btwIndex,
        btwSample,
        btwOffset = 0,
        btwPos = 0;
      setUint32(0x46464952);
      setUint32(btwLength - 8);
      setUint32(0x45564157);
      setUint32(0x20746d66);
      setUint32(16);
      setUint16(1);
      setUint16(numOfChan);
      setUint32(aBuffer.sampleRate);
      setUint32(aBuffer.sampleRate * 2 * numOfChan);
      setUint16(numOfChan * 2);
      setUint16(16);
      setUint32(0x61746164);
      setUint32(btwLength - btwPos - 4);
      for (btwIndex = 0; btwIndex < aBuffer.numberOfChannels; btwIndex++) btwChnls.push(aBuffer.getChannelData(btwIndex));
      while (btwPos < btwLength) {
        for (btwIndex = 0; btwIndex < numOfChan; btwIndex++) {
          btwSample = Math.max(-1, Math.min(1, btwChnls[btwIndex][btwOffset]));
          btwSample = (0.5 + btwSample < 0 ? btwSample * 32768 : btwSample * 32767) | 0;
          btwView.setInt16(btwPos, btwSample, true);
          btwPos += 2;
        }
        btwOffset++;
      }
      var wavHdr = lamejs.WavHeader.readHeader(new DataView(btwArrBuff));
      var data = new Int16Array(btwArrBuff, wavHdr.dataOffset, wavHdr.dataLen / 2);
      var leftData = [];
      var rightData = [];
      for (var i = 0; i < data.length; i += 2) {
        leftData.push(data[i]);
        rightData.push(data[i + 1]);
      }
      var left = new Int16Array(leftData);
      var right = new Int16Array(rightData);
      var format = this.audioFormat.toLowerCase();
      if ('mp3' === format) {
        return {
          channels: wavHdr.channels,
          sampleRate: wavHdr.sampleRate,
          left: left,
          right: right,
          data: data
        };
      }
      if ('wav' === format) {
        return new Blob([btwArrBuff], {
          type: "audio/wav"
        });
      }
      return aBuffer;
      function setUint16(data) {
        btwView.setUint16(btwPos, data, true);
        btwPos += 2;
      }
      function setUint32(data) {
        btwView.setUint32(btwPos, data, true);
        btwPos += 4;
      }
    }
  }, {
    key: "wavToMp3",
    value: function wavToMp3(channels, sampleRate, left, right) {
      var buffer = [];
      var mp3enc = new lamejs.Mp3Encoder(channels, sampleRate, 128);
      var remaining = left.length;
      var samplesPerFrame = 1152;
      for (var i = 0; remaining >= samplesPerFrame; i += samplesPerFrame) {
        if (!right) {
          var mono = left.subarray(i, i + samplesPerFrame);
          var mp3buf = mp3enc.encodeBuffer(mono);
        } else {
          var leftChunk = left.subarray(i, i + samplesPerFrame);
          var rightChunk = right.subarray(i, i + samplesPerFrame);
          var mp3buf = mp3enc.encodeBuffer(leftChunk, rightChunk);
        }
        if (mp3buf.length > 0) {
          buffer.push(mp3buf);
        }
        remaining -= samplesPerFrame;
      }
      var d = mp3enc.flush();
      if (d.length > 0) {
        buffer.push(new Int8Array(d));
      }
      return new Blob(buffer, {
        type: "audio/mp3"
      });
    }
  }]);
  return MasterstudyAudioRecorder;
}();