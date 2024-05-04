import './lib/jquery-throttle.js';
import SignaturePad from 'signature_pad';
import selectize from 'selectize';
import jqueryValidation from 'jquery-validation';
import additional_methods from 'jquery-validation/dist/additional-methods';
import jquery_mask_plugin from 'jquery-mask-plugin';

// Ion.RangeSlider, 2.3.0, © Denis Ineshin, 2010 - 2018, IonDen.com, Build date: 2018-12-12 00:00:37
!function(i){!jQuery&&"function"==typeof define&&define.amd?define(["jquery"],function(t){return i(t,document,window,navigator)}):jQuery||"object"!=typeof exports?i(jQuery,document,window,navigator):i(require("jquery"),document,window,navigator)}(function(a,c,l,t,_){"use strict";var i,s,o=0,e=(i=t.userAgent,s=/msie\s\d+/i,0<i.search(s)&&s.exec(i).toString().split(" ")[1]<9&&(a("html").addClass("lt-ie9"),!0));Function.prototype.bind||(Function.prototype.bind=function(o){var e=this,h=[].slice;if("function"!=typeof e)throw new TypeError;var r=h.call(arguments,1),n=function(){if(this instanceof n){var t=function(){};t.prototype=e.prototype;var i=new t,s=e.apply(i,r.concat(h.call(arguments)));return Object(s)===s?s:i}return e.apply(o,r.concat(h.call(arguments)))};return n}),Array.prototype.indexOf||(Array.prototype.indexOf=function(t,i){var s;if(null==this)throw new TypeError('"this" is null or not defined');var o=Object(this),e=o.length>>>0;if(0===e)return-1;var h=+i||0;if(Math.abs(h)===1/0&&(h=0),e<=h)return-1;for(s=Math.max(0<=h?h:e-Math.abs(h),0);s<e;){if(s in o&&o[s]===t)return s;s++}return-1});var h=function(t,i,s){this.VERSION="2.3.0",this.input=t,this.plugin_count=s,this.current_plugin=0,this.calc_count=0,this.update_tm=0,this.old_from=0,this.old_to=0,this.old_min_interval=null,this.raf_id=null,this.dragging=!1,this.force_redraw=!1,this.no_diapason=!1,this.has_tab_index=!0,this.is_key=!1,this.is_update=!1,this.is_start=!0,this.is_finish=!1,this.is_active=!1,this.is_resize=!1,this.is_click=!1,i=i||{},this.$cache={win:a(l),body:a(c.body),input:a(t),cont:null,rs:null,min:null,max:null,from:null,to:null,single:null,bar:null,line:null,s_single:null,s_from:null,s_to:null,shad_single:null,shad_from:null,shad_to:null,edge:null,grid:null,grid_labels:[]},this.coords={x_gap:0,x_pointer:0,w_rs:0,w_rs_old:0,w_handle:0,p_gap:0,p_gap_left:0,p_gap_right:0,p_step:0,p_pointer:0,p_handle:0,p_single_fake:0,p_single_real:0,p_from_fake:0,p_from_real:0,p_to_fake:0,p_to_real:0,p_bar_x:0,p_bar_w:0,grid_gap:0,big_num:0,big:[],big_w:[],big_p:[],big_x:[]},this.labels={w_min:0,w_max:0,w_from:0,w_to:0,w_single:0,p_min:0,p_max:0,p_from_fake:0,p_from_left:0,p_to_fake:0,p_to_left:0,p_single_fake:0,p_single_left:0};var o,e,h,r=this.$cache.input,n=r.prop("value");for(h in o={skin:"flat",type:"single",min:10,max:100,from:null,to:null,step:1,min_interval:0,max_interval:0,drag_interval:!1,values:[],p_values:[],from_fixed:!1,from_min:null,from_max:null,from_shadow:!1,to_fixed:!1,to_min:null,to_max:null,to_shadow:!1,prettify_enabled:!0,prettify_separator:" ",prettify:null,force_edges:!1,keyboard:!0,grid:!1,grid_margin:!0,grid_num:4,grid_snap:!1,hide_min_max:!1,hide_from_to:!1,prefix:"",postfix:"",max_postfix:"",decorate_both:!0,values_separator:" — ",input_values_separator:";",disable:!1,block:!1,extra_classes:"",scope:null,onStart:null,onChange:null,onFinish:null,onUpdate:null},"INPUT"!==r[0].nodeName&&console&&console.warn&&console.warn("Base element should be <input>!",r[0]),(e={skin:r.data("skin"),type:r.data("type"),min:r.data("min"),max:r.data("max"),from:r.data("from"),to:r.data("to"),step:r.data("step"),min_interval:r.data("minInterval"),max_interval:r.data("maxInterval"),drag_interval:r.data("dragInterval"),values:r.data("values"),from_fixed:r.data("fromFixed"),from_min:r.data("fromMin"),from_max:r.data("fromMax"),from_shadow:r.data("fromShadow"),to_fixed:r.data("toFixed"),to_min:r.data("toMin"),to_max:r.data("toMax"),to_shadow:r.data("toShadow"),prettify_enabled:r.data("prettifyEnabled"),prettify_separator:r.data("prettifySeparator"),force_edges:r.data("forceEdges"),keyboard:r.data("keyboard"),grid:r.data("grid"),grid_margin:r.data("gridMargin"),grid_num:r.data("gridNum"),grid_snap:r.data("gridSnap"),hide_min_max:r.data("hideMinMax"),hide_from_to:r.data("hideFromTo"),prefix:r.data("prefix"),postfix:r.data("postfix"),max_postfix:r.data("maxPostfix"),decorate_both:r.data("decorateBoth"),values_separator:r.data("valuesSeparator"),input_values_separator:r.data("inputValuesSeparator"),disable:r.data("disable"),block:r.data("block"),extra_classes:r.data("extraClasses")}).values=e.values&&e.values.split(","),e)e.hasOwnProperty(h)&&(e[h]!==_&&""!==e[h]||delete e[h]);n!==_&&""!==n&&((n=n.split(e.input_values_separator||i.input_values_separator||";"))[0]&&n[0]==+n[0]&&(n[0]=+n[0]),n[1]&&n[1]==+n[1]&&(n[1]=+n[1]),i&&i.values&&i.values.length?(o.from=n[0]&&i.values.indexOf(n[0]),o.to=n[1]&&i.values.indexOf(n[1])):(o.from=n[0]&&+n[0],o.to=n[1]&&+n[1])),a.extend(o,i),a.extend(o,e),this.options=o,this.update_check={},this.validate(),this.result={input:this.$cache.input,slider:null,min:this.options.min,max:this.options.max,from:this.options.from,from_percent:0,from_value:null,to:this.options.to,to_percent:0,to_value:null},this.init()};h.prototype={init:function(t){this.no_diapason=!1,this.coords.p_step=this.convertToPercent(this.options.step,!0),this.target="base",this.toggleInput(),this.append(),this.setMinMax(),t?(this.force_redraw=!0,this.calc(!0),this.callOnUpdate()):(this.force_redraw=!0,this.calc(!0),this.callOnStart()),this.updateScene()},append:function(){var t='<span class="irs irs--'+this.options.skin+" js-irs-"+this.plugin_count+" "+this.options.extra_classes+'"></span>';this.$cache.input.before(t),this.$cache.input.prop("readonly",!0),this.$cache.cont=this.$cache.input.prev(),this.result.slider=this.$cache.cont,this.$cache.cont.html('<span class="irs"><span class="irs-line" tabindex="0"></span><span class="irs-min">0</span><span class="irs-max">1</span><span class="irs-from">0</span><span class="irs-to">0</span><span class="irs-single">0</span></span><span class="irs-grid"></span>'),this.$cache.rs=this.$cache.cont.find(".irs"),this.$cache.min=this.$cache.cont.find(".irs-min"),this.$cache.max=this.$cache.cont.find(".irs-max"),this.$cache.from=this.$cache.cont.find(".irs-from"),this.$cache.to=this.$cache.cont.find(".irs-to"),this.$cache.single=this.$cache.cont.find(".irs-single"),this.$cache.line=this.$cache.cont.find(".irs-line"),this.$cache.grid=this.$cache.cont.find(".irs-grid"),"single"===this.options.type?(this.$cache.cont.append('<span class="irs-bar irs-bar--single"></span><span class="irs-shadow shadow-single"></span><span class="irs-handle single"><i></i><i></i><i></i></span>'),this.$cache.bar=this.$cache.cont.find(".irs-bar"),this.$cache.edge=this.$cache.cont.find(".irs-bar-edge"),this.$cache.s_single=this.$cache.cont.find(".single"),this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.shad_single=this.$cache.cont.find(".shadow-single")):(this.$cache.cont.append('<span class="irs-bar"></span><span class="irs-shadow shadow-from"></span><span class="irs-shadow shadow-to"></span><span class="irs-handle from"><i></i><i></i><i></i></span><span class="irs-handle to"><i></i><i></i><i></i></span>'),this.$cache.bar=this.$cache.cont.find(".irs-bar"),this.$cache.s_from=this.$cache.cont.find(".from"),this.$cache.s_to=this.$cache.cont.find(".to"),this.$cache.shad_from=this.$cache.cont.find(".shadow-from"),this.$cache.shad_to=this.$cache.cont.find(".shadow-to"),this.setTopHandler()),this.options.hide_from_to&&(this.$cache.from[0].style.display="none",this.$cache.to[0].style.display="none",this.$cache.single[0].style.display="none"),this.appendGrid(),this.options.disable?(this.appendDisableMask(),this.$cache.input[0].disabled=!0):(this.$cache.input[0].disabled=!1,this.removeDisableMask(),this.bindEvents()),this.options.disable||(this.options.block?this.appendDisableMask():this.removeDisableMask()),this.options.drag_interval&&(this.$cache.bar[0].style.cursor="ew-resize")},setTopHandler:function(){var t=this.options.min,i=this.options.max,s=this.options.from,o=this.options.to;t<s&&o===i?this.$cache.s_from.addClass("type_last"):o<i&&this.$cache.s_to.addClass("type_last")},changeLevel:function(t){switch(t){case"single":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_single_fake),this.$cache.s_single.addClass("state_hover");break;case"from":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_from_fake),this.$cache.s_from.addClass("state_hover"),this.$cache.s_from.addClass("type_last"),this.$cache.s_to.removeClass("type_last");break;case"to":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_to_fake),this.$cache.s_to.addClass("state_hover"),this.$cache.s_to.addClass("type_last"),this.$cache.s_from.removeClass("type_last");break;case"both":this.coords.p_gap_left=this.toFixed(this.coords.p_pointer-this.coords.p_from_fake),this.coords.p_gap_right=this.toFixed(this.coords.p_to_fake-this.coords.p_pointer),this.$cache.s_to.removeClass("type_last"),this.$cache.s_from.removeClass("type_last")}},appendDisableMask:function(){this.$cache.cont.append('<span class="irs-disable-mask"></span>'),this.$cache.cont.addClass("irs-disabled")},removeDisableMask:function(){this.$cache.cont.remove(".irs-disable-mask"),this.$cache.cont.removeClass("irs-disabled")},remove:function(){this.$cache.cont.remove(),this.$cache.cont=null,this.$cache.line.off("keydown.irs_"+this.plugin_count),this.$cache.body.off("touchmove.irs_"+this.plugin_count),this.$cache.body.off("mousemove.irs_"+this.plugin_count),this.$cache.win.off("touchend.irs_"+this.plugin_count),this.$cache.win.off("mouseup.irs_"+this.plugin_count),e&&(this.$cache.body.off("mouseup.irs_"+this.plugin_count),this.$cache.body.off("mouseleave.irs_"+this.plugin_count)),this.$cache.grid_labels=[],this.coords.big=[],this.coords.big_w=[],this.coords.big_p=[],this.coords.big_x=[],cancelAnimationFrame(this.raf_id)},bindEvents:function(){this.no_diapason||(this.$cache.body.on("touchmove.irs_"+this.plugin_count,this.pointerMove.bind(this)),this.$cache.body.on("mousemove.irs_"+this.plugin_count,this.pointerMove.bind(this)),this.$cache.win.on("touchend.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.win.on("mouseup.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.line.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.line.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.line.on("focus.irs_"+this.plugin_count,this.pointerFocus.bind(this)),this.options.drag_interval&&"double"===this.options.type?(this.$cache.bar.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"both")),this.$cache.bar.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"both"))):(this.$cache.bar.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.bar.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))),"single"===this.options.type?(this.$cache.single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.s_single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.shad_single.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.s_single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.edge.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_single.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))):(this.$cache.single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,null)),this.$cache.single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,null)),this.$cache.from.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.s_from.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.to.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.s_to.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.shad_from.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_to.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.from.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.s_from.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.to.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.s_to.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.shad_from.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_to.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))),this.options.keyboard&&this.$cache.line.on("keydown.irs_"+this.plugin_count,this.key.bind(this,"keyboard")),e&&(this.$cache.body.on("mouseup.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.body.on("mouseleave.irs_"+this.plugin_count,this.pointerUp.bind(this))))},pointerFocus:function(t){var i,s;this.target||(i=(s="single"===this.options.type?this.$cache.single:this.$cache.from).offset().left,i+=s.width()/2-1,this.pointerClick("single",{preventDefault:function(){},pageX:i}))},pointerMove:function(t){if(this.dragging){var i=t.pageX||t.originalEvent.touches&&t.originalEvent.touches[0].pageX;this.coords.x_pointer=i-this.coords.x_gap,this.calc()}},pointerUp:function(t){this.current_plugin===this.plugin_count&&this.is_active&&(this.is_active=!1,this.$cache.cont.find(".state_hover").removeClass("state_hover"),this.force_redraw=!0,e&&a("*").prop("unselectable",!1),this.updateScene(),this.restoreOriginalMinInterval(),(a.contains(this.$cache.cont[0],t.target)||this.dragging)&&this.callOnFinish(),this.dragging=!1)},pointerDown:function(t,i){i.preventDefault();var s=i.pageX||i.originalEvent.touches&&i.originalEvent.touches[0].pageX;2!==i.button&&("both"===t&&this.setTempMinInterval(),t||(t=this.target||"from"),this.current_plugin=this.plugin_count,this.target=t,this.is_active=!0,this.dragging=!0,this.coords.x_gap=this.$cache.rs.offset().left,this.coords.x_pointer=s-this.coords.x_gap,this.calcPointerPercent(),this.changeLevel(t),e&&a("*").prop("unselectable",!0),this.$cache.line.trigger("focus"),this.updateScene())},pointerClick:function(t,i){i.preventDefault();var s=i.pageX||i.originalEvent.touches&&i.originalEvent.touches[0].pageX;2!==i.button&&(this.current_plugin=this.plugin_count,this.target=t,this.is_click=!0,this.coords.x_gap=this.$cache.rs.offset().left,this.coords.x_pointer=+(s-this.coords.x_gap).toFixed(),this.force_redraw=!0,this.calc(),this.$cache.line.trigger("focus"))},key:function(t,i){if(!(this.current_plugin!==this.plugin_count||i.altKey||i.ctrlKey||i.shiftKey||i.metaKey)){switch(i.which){case 83:case 65:case 40:case 37:i.preventDefault(),this.moveByKey(!1);break;case 87:case 68:case 38:case 39:i.preventDefault(),this.moveByKey(!0)}return!0}},moveByKey:function(t){var i=this.coords.p_pointer,s=(this.options.max-this.options.min)/100;s=this.options.step/s,t?i+=s:i-=s,this.coords.x_pointer=this.toFixed(this.coords.w_rs/100*i),this.is_key=!0,this.calc()},setMinMax:function(){if(this.options){if(this.options.hide_min_max)return this.$cache.min[0].style.display="none",void(this.$cache.max[0].style.display="none");if(this.options.values.length)this.$cache.min.html(this.decorate(this.options.p_values[this.options.min])),this.$cache.max.html(this.decorate(this.options.p_values[this.options.max]));else{var t=this._prettify(this.options.min),i=this._prettify(this.options.max);this.result.min_pretty=t,this.result.max_pretty=i,this.$cache.min.html(this.decorate(t,this.options.min)),this.$cache.max.html(this.decorate(i,this.options.max))}this.labels.w_min=this.$cache.min.outerWidth(!1),this.labels.w_max=this.$cache.max.outerWidth(!1)}},setTempMinInterval:function(){var t=this.result.to-this.result.from;null===this.old_min_interval&&(this.old_min_interval=this.options.min_interval),this.options.min_interval=t},restoreOriginalMinInterval:function(){null!==this.old_min_interval&&(this.options.min_interval=this.old_min_interval,this.old_min_interval=null)},calc:function(t){if(this.options&&(this.calc_count++,(10===this.calc_count||t)&&(this.calc_count=0,this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.calcHandlePercent()),this.coords.w_rs)){this.calcPointerPercent();var i=this.getHandleX();switch("both"===this.target&&(this.coords.p_gap=0,i=this.getHandleX()),"click"===this.target&&(this.coords.p_gap=this.coords.p_handle/2,i=this.getHandleX(),this.options.drag_interval?this.target="both_one":this.target=this.chooseHandle(i)),this.target){case"base":var s=(this.options.max-this.options.min)/100,o=(this.result.from-this.options.min)/s,e=(this.result.to-this.options.min)/s;this.coords.p_single_real=this.toFixed(o),this.coords.p_from_real=this.toFixed(o),this.coords.p_to_real=this.toFixed(e),this.coords.p_single_real=this.checkDiapason(this.coords.p_single_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_single_fake=this.convertToFakePercent(this.coords.p_single_real),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real),this.target=null;break;case"single":if(this.options.from_fixed)break;this.coords.p_single_real=this.convertToRealPercent(i),this.coords.p_single_real=this.calcWithStep(this.coords.p_single_real),this.coords.p_single_real=this.checkDiapason(this.coords.p_single_real,this.options.from_min,this.options.from_max),this.coords.p_single_fake=this.convertToFakePercent(this.coords.p_single_real);break;case"from":if(this.options.from_fixed)break;this.coords.p_from_real=this.convertToRealPercent(i),this.coords.p_from_real=this.calcWithStep(this.coords.p_from_real),this.coords.p_from_real>this.coords.p_to_real&&(this.coords.p_from_real=this.coords.p_to_real),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkMinInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_real=this.checkMaxInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real);break;case"to":if(this.options.to_fixed)break;this.coords.p_to_real=this.convertToRealPercent(i),this.coords.p_to_real=this.calcWithStep(this.coords.p_to_real),this.coords.p_to_real<this.coords.p_from_real&&(this.coords.p_to_real=this.coords.p_from_real),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_real=this.checkMinInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_real=this.checkMaxInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real);break;case"both":if(this.options.from_fixed||this.options.to_fixed)break;i=this.toFixed(i+.001*this.coords.p_handle),this.coords.p_from_real=this.convertToRealPercent(i)-this.coords.p_gap_left,this.coords.p_from_real=this.calcWithStep(this.coords.p_from_real),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkMinInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_real=this.convertToRealPercent(i)+this.coords.p_gap_right,this.coords.p_to_real=this.calcWithStep(this.coords.p_to_real),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_real=this.checkMinInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real);break;case"both_one":if(this.options.from_fixed||this.options.to_fixed)break;var h=this.convertToRealPercent(i),r=this.result.from_percent,n=this.result.to_percent-r,a=n/2,c=h-a,l=h+a;c<0&&(l=(c=0)+n),100<l&&(c=(l=100)-n),this.coords.p_from_real=this.calcWithStep(c),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_real=this.calcWithStep(l),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real)}"single"===this.options.type?(this.coords.p_bar_x=this.coords.p_handle/2,this.coords.p_bar_w=this.coords.p_single_fake,this.result.from_percent=this.coords.p_single_real,this.result.from=this.convertToValue(this.coords.p_single_real),this.result.from_pretty=this._prettify(this.result.from),this.options.values.length&&(this.result.from_value=this.options.values[this.result.from])):(this.coords.p_bar_x=this.toFixed(this.coords.p_from_fake+this.coords.p_handle/2),this.coords.p_bar_w=this.toFixed(this.coords.p_to_fake-this.coords.p_from_fake),this.result.from_percent=this.coords.p_from_real,this.result.from=this.convertToValue(this.coords.p_from_real),this.result.from_pretty=this._prettify(this.result.from),this.result.to_percent=this.coords.p_to_real,this.result.to=this.convertToValue(this.coords.p_to_real),this.result.to_pretty=this._prettify(this.result.to),this.options.values.length&&(this.result.from_value=this.options.values[this.result.from],this.result.to_value=this.options.values[this.result.to])),this.calcMinMax(),this.calcLabels()}},calcPointerPercent:function(){this.coords.w_rs?(this.coords.x_pointer<0||isNaN(this.coords.x_pointer)?this.coords.x_pointer=0:this.coords.x_pointer>this.coords.w_rs&&(this.coords.x_pointer=this.coords.w_rs),this.coords.p_pointer=this.toFixed(this.coords.x_pointer/this.coords.w_rs*100)):this.coords.p_pointer=0},convertToRealPercent:function(t){return t/(100-this.coords.p_handle)*100},convertToFakePercent:function(t){return t/100*(100-this.coords.p_handle)},getHandleX:function(){var t=100-this.coords.p_handle,i=this.toFixed(this.coords.p_pointer-this.coords.p_gap);return i<0?i=0:t<i&&(i=t),i},calcHandlePercent:function(){"single"===this.options.type?this.coords.w_handle=this.$cache.s_single.outerWidth(!1):this.coords.w_handle=this.$cache.s_from.outerWidth(!1),this.coords.p_handle=this.toFixed(this.coords.w_handle/this.coords.w_rs*100)},chooseHandle:function(t){return"single"===this.options.type?"single":this.coords.p_from_real+(this.coords.p_to_real-this.coords.p_from_real)/2<=t?this.options.to_fixed?"from":"to":this.options.from_fixed?"to":"from"},calcMinMax:function(){this.coords.w_rs&&(this.labels.p_min=this.labels.w_min/this.coords.w_rs*100,this.labels.p_max=this.labels.w_max/this.coords.w_rs*100)},calcLabels:function(){this.coords.w_rs&&!this.options.hide_from_to&&("single"===this.options.type?(this.labels.w_single=this.$cache.single.outerWidth(!1),this.labels.p_single_fake=this.labels.w_single/this.coords.w_rs*100,this.labels.p_single_left=this.coords.p_single_fake+this.coords.p_handle/2-this.labels.p_single_fake/2):(this.labels.w_from=this.$cache.from.outerWidth(!1),this.labels.p_from_fake=this.labels.w_from/this.coords.w_rs*100,this.labels.p_from_left=this.coords.p_from_fake+this.coords.p_handle/2-this.labels.p_from_fake/2,this.labels.p_from_left=this.toFixed(this.labels.p_from_left),this.labels.p_from_left=this.checkEdges(this.labels.p_from_left,this.labels.p_from_fake),this.labels.w_to=this.$cache.to.outerWidth(!1),this.labels.p_to_fake=this.labels.w_to/this.coords.w_rs*100,this.labels.p_to_left=this.coords.p_to_fake+this.coords.p_handle/2-this.labels.p_to_fake/2,this.labels.p_to_left=this.toFixed(this.labels.p_to_left),this.labels.p_to_left=this.checkEdges(this.labels.p_to_left,this.labels.p_to_fake),this.labels.w_single=this.$cache.single.outerWidth(!1),this.labels.p_single_fake=this.labels.w_single/this.coords.w_rs*100,this.labels.p_single_left=(this.labels.p_from_left+this.labels.p_to_left+this.labels.p_to_fake)/2-this.labels.p_single_fake/2,this.labels.p_single_left=this.toFixed(this.labels.p_single_left)),this.labels.p_single_left=this.checkEdges(this.labels.p_single_left,this.labels.p_single_fake))},updateScene:function(){this.raf_id&&(cancelAnimationFrame(this.raf_id),this.raf_id=null),clearTimeout(this.update_tm),this.update_tm=null,this.options&&(this.drawHandles(),this.is_active?this.raf_id=requestAnimationFrame(this.updateScene.bind(this)):this.update_tm=setTimeout(this.updateScene.bind(this),300))},drawHandles:function(){this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.coords.w_rs&&(this.coords.w_rs!==this.coords.w_rs_old&&(this.target="base",this.is_resize=!0),(this.coords.w_rs!==this.coords.w_rs_old||this.force_redraw)&&(this.setMinMax(),this.calc(!0),this.drawLabels(),this.options.grid&&(this.calcGridMargin(),this.calcGridLabels()),this.force_redraw=!0,this.coords.w_rs_old=this.coords.w_rs,this.drawShadow()),this.coords.w_rs&&(this.dragging||this.force_redraw||this.is_key)&&((this.old_from!==this.result.from||this.old_to!==this.result.to||this.force_redraw||this.is_key)&&(this.drawLabels(),this.$cache.bar[0].style.left=this.coords.p_bar_x+"%",this.$cache.bar[0].style.width=this.coords.p_bar_w+"%","single"===this.options.type?(this.$cache.bar[0].style.left=0,this.$cache.bar[0].style.width=this.coords.p_bar_w+this.coords.p_bar_x+"%",this.$cache.s_single[0].style.left=this.coords.p_single_fake+"%"):(this.$cache.s_from[0].style.left=this.coords.p_from_fake+"%",this.$cache.s_to[0].style.left=this.coords.p_to_fake+"%",(this.old_from!==this.result.from||this.force_redraw)&&(this.$cache.from[0].style.left=this.labels.p_from_left+"%"),(this.old_to!==this.result.to||this.force_redraw)&&(this.$cache.to[0].style.left=this.labels.p_to_left+"%")),this.$cache.single[0].style.left=this.labels.p_single_left+"%",this.writeToInput(),this.old_from===this.result.from&&this.old_to===this.result.to||this.is_start||(this.$cache.input.trigger("change"),this.$cache.input.trigger("input")),this.old_from=this.result.from,this.old_to=this.result.to,this.is_resize||this.is_update||this.is_start||this.is_finish||this.callOnChange(),(this.is_key||this.is_click)&&(this.is_key=!1,this.is_click=!1,this.callOnFinish()),this.is_update=!1,this.is_resize=!1,this.is_finish=!1),this.is_start=!1,this.is_key=!1,this.is_click=!1,this.force_redraw=!1))},drawLabels:function(){if(this.options){var t,i,s,o,e,h=this.options.values.length,r=this.options.p_values;if(!this.options.hide_from_to)if("single"===this.options.type)t=h?this.decorate(r[this.result.from]):(o=this._prettify(this.result.from),this.decorate(o,this.result.from)),this.$cache.single.html(t),this.calcLabels(),this.labels.p_single_left<this.labels.p_min+1?this.$cache.min[0].style.visibility="hidden":this.$cache.min[0].style.visibility="visible",this.labels.p_single_left+this.labels.p_single_fake>100-this.labels.p_max-1?this.$cache.max[0].style.visibility="hidden":this.$cache.max[0].style.visibility="visible";else{s=h?(this.options.decorate_both?(t=this.decorate(r[this.result.from]),t+=this.options.values_separator,t+=this.decorate(r[this.result.to])):t=this.decorate(r[this.result.from]+this.options.values_separator+r[this.result.to]),i=this.decorate(r[this.result.from]),this.decorate(r[this.result.to])):(o=this._prettify(this.result.from),e=this._prettify(this.result.to),this.options.decorate_both?(t=this.decorate(o,this.result.from),t+=this.options.values_separator,t+=this.decorate(e,this.result.to)):t=this.decorate(o+this.options.values_separator+e,this.result.to),i=this.decorate(o,this.result.from),this.decorate(e,this.result.to)),this.$cache.single.html(t),this.$cache.from.html(i),this.$cache.to.html(s),this.calcLabels();var n=Math.min(this.labels.p_single_left,this.labels.p_from_left),a=this.labels.p_single_left+this.labels.p_single_fake,c=this.labels.p_to_left+this.labels.p_to_fake,l=Math.max(a,c);this.labels.p_from_left+this.labels.p_from_fake>=this.labels.p_to_left?(this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.single[0].style.visibility="visible",l=this.result.from===this.result.to?("from"===this.target?this.$cache.from[0].style.visibility="visible":"to"===this.target?this.$cache.to[0].style.visibility="visible":this.target||(this.$cache.from[0].style.visibility="visible"),this.$cache.single[0].style.visibility="hidden",c):(this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.single[0].style.visibility="visible",Math.max(a,c))):(this.$cache.from[0].style.visibility="visible",this.$cache.to[0].style.visibility="visible",this.$cache.single[0].style.visibility="hidden"),n<this.labels.p_min+1?this.$cache.min[0].style.visibility="hidden":this.$cache.min[0].style.visibility="visible",l>100-this.labels.p_max-1?this.$cache.max[0].style.visibility="hidden":this.$cache.max[0].style.visibility="visible"}}},drawShadow:function(){var t,i,s,o,e=this.options,h=this.$cache,r="number"==typeof e.from_min&&!isNaN(e.from_min),n="number"==typeof e.from_max&&!isNaN(e.from_max),a="number"==typeof e.to_min&&!isNaN(e.to_min),c="number"==typeof e.to_max&&!isNaN(e.to_max);"single"===e.type?e.from_shadow&&(r||n)?(t=this.convertToPercent(r?e.from_min:e.min),i=this.convertToPercent(n?e.from_max:e.max)-t,t=this.toFixed(t-this.coords.p_handle/100*t),i=this.toFixed(i-this.coords.p_handle/100*i),t+=this.coords.p_handle/2,h.shad_single[0].style.display="block",h.shad_single[0].style.left=t+"%",h.shad_single[0].style.width=i+"%"):h.shad_single[0].style.display="none":(e.from_shadow&&(r||n)?(t=this.convertToPercent(r?e.from_min:e.min),i=this.convertToPercent(n?e.from_max:e.max)-t,t=this.toFixed(t-this.coords.p_handle/100*t),i=this.toFixed(i-this.coords.p_handle/100*i),t+=this.coords.p_handle/2,h.shad_from[0].style.display="block",h.shad_from[0].style.left=t+"%",h.shad_from[0].style.width=i+"%"):h.shad_from[0].style.display="none",e.to_shadow&&(a||c)?(s=this.convertToPercent(a?e.to_min:e.min),o=this.convertToPercent(c?e.to_max:e.max)-s,s=this.toFixed(s-this.coords.p_handle/100*s),o=this.toFixed(o-this.coords.p_handle/100*o),s+=this.coords.p_handle/2,h.shad_to[0].style.display="block",h.shad_to[0].style.left=s+"%",h.shad_to[0].style.width=o+"%"):h.shad_to[0].style.display="none")},writeToInput:function(){"single"===this.options.type?(this.options.values.length?this.$cache.input.prop("value",this.result.from_value):this.$cache.input.prop("value",this.result.from),this.$cache.input.data("from",this.result.from)):(this.options.values.length?this.$cache.input.prop("value",this.result.from_value+this.options.input_values_separator+this.result.to_value):this.$cache.input.prop("value",this.result.from+this.options.input_values_separator+this.result.to),this.$cache.input.data("from",this.result.from),this.$cache.input.data("to",this.result.to))},callOnStart:function(){this.writeToInput(),this.options.onStart&&"function"==typeof this.options.onStart&&(this.options.scope?this.options.onStart.call(this.options.scope,this.result):this.options.onStart(this.result))},callOnChange:function(){this.writeToInput(),this.options.onChange&&"function"==typeof this.options.onChange&&(this.options.scope?this.options.onChange.call(this.options.scope,this.result):this.options.onChange(this.result))},callOnFinish:function(){this.writeToInput(),this.options.onFinish&&"function"==typeof this.options.onFinish&&(this.options.scope?this.options.onFinish.call(this.options.scope,this.result):this.options.onFinish(this.result))},callOnUpdate:function(){this.writeToInput(),this.options.onUpdate&&"function"==typeof this.options.onUpdate&&(this.options.scope?this.options.onUpdate.call(this.options.scope,this.result):this.options.onUpdate(this.result))},toggleInput:function(){this.$cache.input.toggleClass("irs-hidden-input"),this.has_tab_index?this.$cache.input.prop("tabindex",-1):this.$cache.input.removeProp("tabindex"),this.has_tab_index=!this.has_tab_index},convertToPercent:function(t,i){var s,o=this.options.max-this.options.min,e=o/100;return o?(s=(i?t:t-this.options.min)/e,this.toFixed(s)):(this.no_diapason=!0,0)},convertToValue:function(t){var i,s,o=this.options.min,e=this.options.max,h=o.toString().split(".")[1],r=e.toString().split(".")[1],n=0,a=0;if(0===t)return this.options.min;if(100===t)return this.options.max;h&&(n=i=h.length),r&&(n=s=r.length),i&&s&&(n=s<=i?i:s),o<0&&(o=+(o+(a=Math.abs(o))).toFixed(n),e=+(e+a).toFixed(n));var c,l=(e-o)/100*t+o,_=this.options.step.toString().split(".")[1];return l=_?+l.toFixed(_.length):(l/=this.options.step,+(l*=this.options.step).toFixed(0)),a&&(l-=a),(c=_?+l.toFixed(_.length):this.toFixed(l))<this.options.min?c=this.options.min:c>this.options.max&&(c=this.options.max),c},calcWithStep:function(t){var i=Math.round(t/this.coords.p_step)*this.coords.p_step;return 100<i&&(i=100),100===t&&(i=100),this.toFixed(i)},checkMinInterval:function(t,i,s){var o,e,h=this.options;return h.min_interval?(o=this.convertToValue(t),e=this.convertToValue(i),"from"===s?e-o<h.min_interval&&(o=e-h.min_interval):o-e<h.min_interval&&(o=e+h.min_interval),this.convertToPercent(o)):t},checkMaxInterval:function(t,i,s){var o,e,h=this.options;return h.max_interval?(o=this.convertToValue(t),e=this.convertToValue(i),"from"===s?e-o>h.max_interval&&(o=e-h.max_interval):o-e>h.max_interval&&(o=e+h.max_interval),this.convertToPercent(o)):t},checkDiapason:function(t,i,s){var o=this.convertToValue(t),e=this.options;return"number"!=typeof i&&(i=e.min),"number"!=typeof s&&(s=e.max),o<i&&(o=i),s<o&&(o=s),this.convertToPercent(o)},toFixed:function(t){return+(t=t.toFixed(20))},_prettify:function(t){return this.options.prettify_enabled?this.options.prettify&&"function"==typeof this.options.prettify?this.options.prettify(t):this.prettify(t):t},prettify:function(t){return t.toString().replace(/(\d{1,3}(?=(?:\d\d\d)+(?!\d)))/g,"$1"+this.options.prettify_separator)},checkEdges:function(t,i){return this.options.force_edges&&(t<0?t=0:100-i<t&&(t=100-i)),this.toFixed(t)},validate:function(){var t,i,s=this.options,o=this.result,e=s.values,h=e.length;if("string"==typeof s.min&&(s.min=+s.min),"string"==typeof s.max&&(s.max=+s.max),"string"==typeof s.from&&(s.from=+s.from),"string"==typeof s.to&&(s.to=+s.to),"string"==typeof s.step&&(s.step=+s.step),"string"==typeof s.from_min&&(s.from_min=+s.from_min),"string"==typeof s.from_max&&(s.from_max=+s.from_max),"string"==typeof s.to_min&&(s.to_min=+s.to_min),"string"==typeof s.to_max&&(s.to_max=+s.to_max),"string"==typeof s.grid_num&&(s.grid_num=+s.grid_num),s.max<s.min&&(s.max=s.min),h)for(s.p_values=[],s.min=0,s.max=h-1,s.step=1,s.grid_num=s.max,s.grid_snap=!0,i=0;i<h;i++)t=+e[i],t=isNaN(t)?e[i]:(e[i]=t,this._prettify(t)),s.p_values.push(t);("number"!=typeof s.from||isNaN(s.from))&&(s.from=s.min),("number"!=typeof s.to||isNaN(s.to))&&(s.to=s.max),"single"===s.type?(s.from<s.min&&(s.from=s.min),s.from>s.max&&(s.from=s.max)):(s.from<s.min&&(s.from=s.min),s.from>s.max&&(s.from=s.max),s.to<s.min&&(s.to=s.min),s.to>s.max&&(s.to=s.max),this.update_check.from&&(this.update_check.from!==s.from&&s.from>s.to&&(s.from=s.to),this.update_check.to!==s.to&&s.to<s.from&&(s.to=s.from)),s.from>s.to&&(s.from=s.to),s.to<s.from&&(s.to=s.from)),("number"!=typeof s.step||isNaN(s.step)||!s.step||s.step<0)&&(s.step=1),"number"==typeof s.from_min&&s.from<s.from_min&&(s.from=s.from_min),"number"==typeof s.from_max&&s.from>s.from_max&&(s.from=s.from_max),"number"==typeof s.to_min&&s.to<s.to_min&&(s.to=s.to_min),"number"==typeof s.to_max&&s.from>s.to_max&&(s.to=s.to_max),o&&(o.min!==s.min&&(o.min=s.min),o.max!==s.max&&(o.max=s.max),(o.from<o.min||o.from>o.max)&&(o.from=s.from),(o.to<o.min||o.to>o.max)&&(o.to=s.to)),("number"!=typeof s.min_interval||isNaN(s.min_interval)||!s.min_interval||s.min_interval<0)&&(s.min_interval=0),("number"!=typeof s.max_interval||isNaN(s.max_interval)||!s.max_interval||s.max_interval<0)&&(s.max_interval=0),s.min_interval&&s.min_interval>s.max-s.min&&(s.min_interval=s.max-s.min),s.max_interval&&s.max_interval>s.max-s.min&&(s.max_interval=s.max-s.min)},decorate:function(t,i){var s="",o=this.options;return o.prefix&&(s+=o.prefix),s+=t,o.max_postfix&&(o.values.length&&t===o.p_values[o.max]?(s+=o.max_postfix,o.postfix&&(s+=" ")):i===o.max&&(s+=o.max_postfix,o.postfix&&(s+=" "))),o.postfix&&(s+=o.postfix),s},updateFrom:function(){this.result.from=this.options.from,this.result.from_percent=this.convertToPercent(this.result.from),this.result.from_pretty=this._prettify(this.result.from),this.options.values&&(this.result.from_value=this.options.values[this.result.from])},updateTo:function(){this.result.to=this.options.to,this.result.to_percent=this.convertToPercent(this.result.to),this.result.to_pretty=this._prettify(this.result.to),this.options.values&&(this.result.to_value=this.options.values[this.result.to])},updateResult:function(){this.result.min=this.options.min,this.result.max=this.options.max,this.updateFrom(),this.updateTo()},appendGrid:function(){if(this.options.grid){var t,i,s,o,e,h,r=this.options,n=r.max-r.min,a=r.grid_num,c=0,l=4,_="";for(this.calcGridMargin(),r.grid_snap&&(a=n/r.step),50<a&&(a=50),s=this.toFixed(100/a),4<a&&(l=3),7<a&&(l=2),14<a&&(l=1),28<a&&(l=0),t=0;t<a+1;t++){for(o=l,100<(c=this.toFixed(s*t))&&(c=100),e=((this.coords.big[t]=c)-s*(t-1))/(o+1),i=1;i<=o&&0!==c;i++)_+='<span class="irs-grid-pol small" style="left: '+this.toFixed(c-e*i)+'%"></span>';_+='<span class="irs-grid-pol" style="left: '+c+'%"></span>',h=this.convertToValue(c),_+='<span class="irs-grid-text js-grid-text-'+t+'" style="left: '+c+'%">'+(h=r.values.length?r.p_values[h]:this._prettify(h))+"</span>"}this.coords.big_num=Math.ceil(a+1),this.$cache.cont.addClass("irs-with-grid"),this.$cache.grid.html(_),this.cacheGridLabels()}},cacheGridLabels:function(){var t,i,s=this.coords.big_num;for(i=0;i<s;i++)t=this.$cache.grid.find(".js-grid-text-"+i),this.$cache.grid_labels.push(t);this.calcGridLabels()},calcGridLabels:function(){var t,i,s=[],o=[],e=this.coords.big_num;for(t=0;t<e;t++)this.coords.big_w[t]=this.$cache.grid_labels[t].outerWidth(!1),this.coords.big_p[t]=this.toFixed(this.coords.big_w[t]/this.coords.w_rs*100),this.coords.big_x[t]=this.toFixed(this.coords.big_p[t]/2),s[t]=this.toFixed(this.coords.big[t]-this.coords.big_x[t]),o[t]=this.toFixed(s[t]+this.coords.big_p[t]);for(this.options.force_edges&&(s[0]<-this.coords.grid_gap&&(s[0]=-this.coords.grid_gap,o[0]=this.toFixed(s[0]+this.coords.big_p[0]),this.coords.big_x[0]=this.coords.grid_gap),o[e-1]>100+this.coords.grid_gap&&(o[e-1]=100+this.coords.grid_gap,s[e-1]=this.toFixed(o[e-1]-this.coords.big_p[e-1]),this.coords.big_x[e-1]=this.toFixed(this.coords.big_p[e-1]-this.coords.grid_gap))),this.calcGridCollision(2,s,o),this.calcGridCollision(4,s,o),t=0;t<e;t++)i=this.$cache.grid_labels[t][0],this.coords.big_x[t]!==Number.POSITIVE_INFINITY&&(i.style.marginLeft=-this.coords.big_x[t]+"%")},calcGridCollision:function(t,i,s){var o,e,h,r=this.coords.big_num;for(o=0;o<r&&!(r<=(e=o+t/2));o+=t)h=this.$cache.grid_labels[e][0],s[o]<=i[e]?h.style.visibility="visible":h.style.visibility="hidden"},calcGridMargin:function(){this.options.grid_margin&&(this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.coords.w_rs&&("single"===this.options.type?this.coords.w_handle=this.$cache.s_single.outerWidth(!1):this.coords.w_handle=this.$cache.s_from.outerWidth(!1),this.coords.p_handle=this.toFixed(this.coords.w_handle/this.coords.w_rs*100),this.coords.grid_gap=this.toFixed(this.coords.p_handle/2-.1),this.$cache.grid[0].style.width=this.toFixed(100-this.coords.p_handle)+"%",this.$cache.grid[0].style.left=this.coords.grid_gap+"%"))},update:function(t){this.input&&(this.is_update=!0,this.options.from=this.result.from,this.options.to=this.result.to,this.update_check.from=this.result.from,this.update_check.to=this.result.to,this.options=a.extend(this.options,t),this.validate(),this.updateResult(t),this.toggleInput(),this.remove(),this.init(!0))},reset:function(){this.input&&(this.updateResult(),this.update())},destroy:function(){this.input&&(this.toggleInput(),this.$cache.input.prop("readonly",!1),a.data(this.input,"ionRangeSlider",null),this.remove(),this.input=null,this.options=null)}},a.fn.ionRangeSlider=function(t){return this.each(function(){a.data(this,"ionRangeSlider")||a.data(this,"ionRangeSlider",new h(this,t,o++))})},function(){for(var h=0,t=["ms","moz","webkit","o"],i=0;i<t.length&&!l.requestAnimationFrame;++i)l.requestAnimationFrame=l[t[i]+"RequestAnimationFrame"],l.cancelAnimationFrame=l[t[i]+"CancelAnimationFrame"]||l[t[i]+"CancelRequestAnimationFrame"];l.requestAnimationFrame||(l.requestAnimationFrame=function(t,i){var s=(new Date).getTime(),o=Math.max(0,16-(s-h)),e=l.setTimeout(function(){t(s+o)},o);return h=s+o,e}),l.cancelAnimationFrame||(l.cancelAnimationFrame=function(t){clearTimeout(t)})}()});

"use strict";function _classCallCheck(instance,Constructor){if(!(instance instanceof Constructor))throw new TypeError("Cannot call a class as a function")}var _createClass=function(){function defineProperties(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||!1,descriptor.configurable=!0,"value"in descriptor&&(descriptor.writable=!0),Object.defineProperty(target,descriptor.key,descriptor)}}return function(Constructor,protoProps,staticProps){return protoProps&&defineProperties(Constructor.prototype,protoProps),staticProps&&defineProperties(Constructor,staticProps),Constructor}}();(function(){var ImagePicker,ImagePickerOption,both_array_are_equal,sanitized_options,indexOf=[].indexOf;jQuery.fn.extend({imagepicker:function(){var opts=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return this.each(function(){var select;if((select=jQuery(this)).data("picker")&&select.data("picker").destroy(),select.data("picker",new ImagePicker(this,sanitized_options(opts))),null!=opts.initialized)return opts.initialized.call(select.data("picker"))})}}),sanitized_options=function(opts){var default_options;return default_options={hide_select:!0,show_label:!1,initialized:void 0,changed:void 0,clicked:void 0,selected:void 0,limit:void 0,limit_reached:void 0,font_awesome:!1},jQuery.extend(default_options,opts)},both_array_are_equal=function(a,b){var i,j,len,x;if(!a||!b||a.length!==b.length)return!1;for(a=a.slice(0),b=b.slice(0),a.sort(),b.sort(),i=j=0,len=a.length;j<len;i=++j)if(x=a[i],b[i]!==x)return!1;return!0},ImagePicker=function(){function ImagePicker(select_element){var opts1=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};_classCallCheck(this,ImagePicker),this.sync_picker_with_select=this.sync_picker_with_select.bind(this),this.opts=opts1,this.select=jQuery(select_element),this.multiple="multiple"===this.select.attr("multiple"),null!=this.select.data("limit")&&(this.opts.limit=parseInt(this.select.data("limit"))),this.build_and_append_picker()}return _createClass(ImagePicker,[{key:"destroy",value:function(){var j,len,ref;for(j=0,len=(ref=this.picker_options).length;j<len;j++)ref[j].destroy();return this.picker.remove(),this.select.off("change",this.sync_picker_with_select),this.select.removeData("picker"),this.select.show()}},{key:"build_and_append_picker",value:function(){return this.opts.hide_select&&this.select.hide(),this.select.on("change",this.sync_picker_with_select),null!=this.picker&&this.picker.remove(),this.create_picker(),this.select.after(this.picker),this.sync_picker_with_select()}},{key:"sync_picker_with_select",value:function(){var j,len,option,ref,results;for(results=[],j=0,len=(ref=this.picker_options).length;j<len;j++)(option=ref[j]).is_selected()?results.push(option.mark_as_selected()):results.push(option.unmark_as_selected());return results}},{key:"create_picker",value:function(){return this.picker=jQuery("<ul class='thumbnails image_picker_selector'></ul>"),this.picker_options=[],this.recursively_parse_option_groups(this.select,this.picker),this.picker}},{key:"recursively_parse_option_groups",value:function(scoped_dom,target_container){var container,j,k,len,len1,option,option_group,ref,ref1,results;for(j=0,len=(ref=scoped_dom.children("optgroup")).length;j<len;j++)option_group=ref[j],option_group=jQuery(option_group),(container=jQuery("<ul></ul>")).append(jQuery("<li class='group_title'>"+option_group.attr("label")+"</li>")),target_container.append(jQuery("<li class='group'>").append(container)),this.recursively_parse_option_groups(option_group,container);for(ref1=function(){var l,len1,ref1,results1;for(results1=[],l=0,len1=(ref1=scoped_dom.children("option")).length;l<len1;l++)option=ref1[l],results1.push(new ImagePickerOption(option,this,this.opts));return results1}.call(this),results=[],k=0,len1=ref1.length;k<len1;k++)option=ref1[k],this.picker_options.push(option),option.has_image()&&results.push(target_container.append(option.node));return results}},{key:"has_implicit_blanks",value:function(){var option;return function(){var j,len,ref,results;for(results=[],j=0,len=(ref=this.picker_options).length;j<len;j++)(option=ref[j]).is_blank()&&!option.has_image()&&results.push(option);return results}.call(this).length>0}},{key:"selected_values",value:function(){return this.multiple?this.select.val()||[]:[this.select.val()]}},{key:"toggle",value:function(imagepicker_option,original_event){var new_values,old_values,selected_value;if(old_values=this.selected_values(),selected_value=imagepicker_option.value().toString(),this.multiple?indexOf.call(this.selected_values(),selected_value)>=0?((new_values=this.selected_values()).splice(jQuery.inArray(selected_value,old_values),1),this.select.val([]),this.select.val(new_values)):null!=this.opts.limit&&this.selected_values().length>=this.opts.limit?null!=this.opts.limit_reached&&this.opts.limit_reached.call(this.select):this.select.val(this.selected_values().concat(selected_value)):this.has_implicit_blanks()&&imagepicker_option.is_selected()?this.select.val(""):this.select.val(selected_value),!both_array_are_equal(old_values,this.selected_values())&&(this.select.change(),null!=this.opts.changed))return this.opts.changed.call(this.select,old_values,this.selected_values(),original_event)}}]),ImagePicker}(),ImagePickerOption=function(){function ImagePickerOption(option_element,picker){var opts1=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};_classCallCheck(this,ImagePickerOption),this.clicked=this.clicked.bind(this),this.picker=picker,this.opts=opts1,this.option=jQuery(option_element),this.create_node()}return _createClass(ImagePickerOption,[{key:"destroy",value:function(){return this.node.find(".thumbnail").off("click",this.clicked)}},{key:"has_image",value:function(){return null!=this.option.data("img-src")}},{key:"is_blank",value:function(){return!(null!=this.value()&&""!==this.value())}},{key:"is_selected",value:function(){var select_value;return select_value=this.picker.select.val(),this.picker.multiple?jQuery.inArray(this.value(),select_value)>=0:this.value()===select_value}},{key:"mark_as_selected",value:function(){return this.node.find(".thumbnail").addClass("selected")}},{key:"unmark_as_selected",value:function(){return this.node.find(".thumbnail").removeClass("selected")}},{key:"value",value:function(){return this.option.val()}},{key:"label",value:function(){return this.option.data("img-label")?this.option.data("img-label"):this.option.text()}},{key:"clicked",value:function(event){if(this.picker.toggle(this,event),null!=this.opts.clicked&&this.opts.clicked.call(this.picker.select,this,event),null!=this.opts.selected&&this.is_selected())return this.opts.selected.call(this.picker.select,this,event)}},{key:"create_node",value:function(){var image,imgAlt,imgClass,thumbnail;return this.node=jQuery("<li/>"),this.option.data("font_awesome")?(image=jQuery("<i>")).attr("class","fa-fw "+this.option.data("img-src")):(image=jQuery("<img class='image_picker_image'/>")).attr("src",this.option.data("img-src")),thumbnail=jQuery("<div class='thumbnail'>"),(imgClass=this.option.data("img-class"))&&(this.node.addClass(imgClass),image.addClass(imgClass),thumbnail.addClass(imgClass)),(imgAlt=this.option.data("img-alt"))&&image.attr("alt",imgAlt),thumbnail.on("click",this.clicked),thumbnail.append(image),this.opts.show_label&&thumbnail.append(jQuery("<p/>").html(this.label())),this.node.append(thumbnail),this.node}}]),ImagePickerOption}()}).call(void 0);

//Tinymce
!function(){var a={},b=function(b){for(var c=a[b],e=c.deps,f=c.defn,g=e.length,h=new Array(g),i=0;i<g;++i)h[i]=d(e[i]);var j=f.apply(null,h);if(void 0===j)throw"module ["+b+"] returned undefined";c.instance=j},c=function(b,c,d){if("string"!=typeof b)throw"module id must be a string";if(void 0===c)throw"no dependencies for "+b;if(void 0===d)throw"no definition function for "+b;a[b]={deps:c,defn:d,instance:void 0}},d=function(c){var d=a[c];if(void 0===d)throw"module ["+c+"] was undefined";return void 0===d.instance&&b(c),d.instance},e=function(a,b){for(var c=a.length,e=new Array(c),f=0;f<c;++f)e[f]=d(a[f]);b.apply(null,e)},f={};f.bolt={module:{api:{define:c,require:e,demand:d}}};var g=c,h=function(a,b){g(a,[],function(){return b})};h("1",document),h("2",window),g("0",["1","2"],function(a,b){return function(c){var d,e,f,g,h,i=[];h=c?c:b,g=h.jQuery;var j=function(){return h.tinymce};g.fn.tinymce=function(c){var d,l,m,n=this,o="";if(!n.length)return n;if(!c)return j()?j().get(n[0].id):null;n.css("visibility","hidden");var p=function(){var a=[],b=0;f||(k(),f=!0),n.each(function(d,e){var f,g=e.id,h=c.oninit;g||(e.id=g=j().DOM.uniqueId()),j().get(g)||(f=j().createEditor(g,c),a.push(f),f.on("init",function(){var c,d=h;n.css("visibility",""),h&&++b==a.length&&("string"==typeof d&&(c=d.indexOf(".")===-1?null:j().resolve(d.replace(/\.\w+$/,"")),d=j().resolve(d)),d.apply(c||j(),a))}))}),g.each(a,function(a,b){b.render()})};if(h.tinymce||e||!(d=c.script_url))1===e?i.push(p):p();else{e=1,l=d.substring(0,d.lastIndexOf("/")),d.indexOf(".min")!=-1&&(o=".min"),h.tinymce=h.tinyMCEPreInit||{base:l,suffix:o},d.indexOf("gzip")!=-1&&(m=c.language||"en",d=d+(/\?/.test(d)?"&":"?")+"js=true&core=true&suffix="+escape(o)+"&themes="+escape(c.theme||"modern")+"&plugins="+escape(c.plugins||"")+"&languages="+(m||""),h.tinyMCE_GZ||(h.tinyMCE_GZ={start:function(){var a=function(a){j().ScriptLoader.markDone(j().baseURI.toAbsolute(a))};a("langs/"+m+".js"),a("themes/"+c.theme+"/theme"+o+".js"),a("themes/"+c.theme+"/langs/"+m+".js"),g.each(c.plugins.split(","),function(b,c){c&&(a("plugins/"+c+"/plugin"+o+".js"),a("plugins/"+c+"/langs/"+m+".js"))})},end:function(){}}));var q=a.createElement("script");q.type="text/javascript",q.onload=q.onreadystatechange=function(a){a=a||b.event,2===e||"load"!=a.type&&!/complete|loaded/.test(q.readyState)||(j().dom.Event.domLoaded=1,e=2,c.script_loaded&&c.script_loaded(),p(),g.each(i,function(a,b){b()}))},q.src=d,a.body.appendChild(q)}return n},g.extend(g.expr[":"],{tinymce:function(a){var b;return!!(a.id&&"tinymce"in h&&(b=j().get(a.id),b&&b.editorManager===j()))}});var k=function(){var a=function(a){"remove"===a&&this.each(function(a,b){var d=c(b);d&&d.remove()}),this.find("span.mceEditor,div.mceEditor").each(function(a,b){var c=j().get(b.id.replace(/_parent$/,""));c&&c.remove()})},b=function(b){var c,d=this;if(null!=b)a.call(d),d.each(function(a,c){var d;(d=j().get(c.id))&&d.setContent(b)});else if(d.length>0&&(c=j().get(d[0].id)))return c.getContent()},c=function(a){var b=null;return a&&a.id&&h.tinymce&&(b=j().get(a.id)),b},e=function(a){return!!(a&&a.length&&h.tinymce&&a.is(":tinymce"))},f={};g.each(["text","html","val"],function(a,h){var i=f[h]=g.fn[h],j="text"===h;g.fn[h]=function(a){var f=this;if(!e(f))return i.apply(f,arguments);if(a!==d)return b.call(f.filter(":tinymce"),a),i.apply(f.not(":tinymce"),arguments),f;var h="",k=arguments;return(j?f:f.eq(0)).each(function(a,b){var d=c(b);h+=d?j?d.getContent().replace(/<(?:"[^"]*"|'[^']*'|[^'">])*>/g,""):d.getContent({save:!0}):i.apply(g(b),k)}),h}}),g.each(["append","prepend"],function(a,b){var h=f[b]=g.fn[b],i="prepend"===b;g.fn[b]=function(a){var b=this;return e(b)?a!==d?("string"==typeof a&&b.filter(":tinymce").each(function(b,d){var e=c(d);e&&e.setContent(i?a+e.getContent():e.getContent()+a)}),h.apply(b.not(":tinymce"),arguments),b):void 0:h.apply(b,arguments)}}),g.each(["remove","replaceWith","replaceAll","empty"],function(b,c){var d=f[c]=g.fn[c];g.fn[c]=function(){return a.call(this,c),d.apply(this,arguments)}}),f.attr=g.fn.attr,g.fn.attr=function(a,h){var i=this,j=arguments;if(!a||"value"!==a||!e(i))return h!==d?f.attr.apply(i,j):f.attr.apply(i,j);if(h!==d)return b.call(i.filter(":tinymce"),h),f.attr.apply(i.not(":tinymce"),j),i;var k=i[0],l=c(k);return l?l.getContent({save:!0}):f.attr.apply(g(k),j)}}}}),d("0")()}();

jQuery(document).ready(function( $ ) {
	$.jMaskGlobals.watchDataMask = true;

	var pluginURL = $('[data-piotnetforms-plugin-url]').attr('data-piotnetforms-plugin-url');

	function initWidgetRangeSlider($scope, $) {

	    var $elements = $scope.find('[data-piotnetforms-range-slider]');

		if (!$elements.length) {
			return;
		}

		$.each($elements, function (i, $element) {
			var optionsString = $($element).data('piotnetforms-range-slider');
	        var options = {};
			var items = optionsString.split(',');
			for (var j = 0; j < items.length; j++) {
			    var current = items[j].trim().split(':');
			    if (current[0] != undefined && current[1] != undefined) {
			    	var current1 = current[1].trim().replace('"','').replace('"','');
			    	if (current1 == "false" || current1 == "true") {
			    		if (current1 == "false") {
			    			options[current[0]] = false;
			    		} else {
			    			options[current[0]] = true;
			    		}
			    	} else {
			    		options[current[0]] = current1;
			    	}
			    }
			}

			options.onStart = function (data) {
	            //piotnetformsConditionalLogicFormCheck();
	            //piotnetformsCalculatedFieldsForm();
	        };

			$($element).ionRangeSlider(options);
		});

	};

	function initWidgetImageSelect($scope, $) {
	    var $elements = $scope.find('[data-piotnetforms-image-select]');

		if (!$elements.length) {
			return;
		}

		$.each($elements, function (i, $element) {
			var gallery = $($element).data('piotnetforms-image-select'),
                $options = $($element).find('option'),
                imagePickerOptions = {show_label: true};

            if ($($element).attr('data-piotnetforms-image-select-limit-multiple') != undefined) {
            	imagePickerOptions.limit = $($element).attr('data-piotnetforms-image-select-limit-multiple');
            }

            $($element).closest('.piotnetforms-field').addClass('piotnetforms-image-select-field');
            
            $options.each(function(index,element){
            	if ($options.eq(0).attr('value').trim() == '' && index != 0) {
            		var indexGallery = index - 1;
	                var imageURL = gallery[indexGallery]['url'],
	                    optionsContent = $(this).html();

	                $(this).attr('data-img-src',imageURL);
	                $($element).imagepicker(imagePickerOptions);
	            }

	            if ($options.eq(0).attr('value').trim() != '') {
	            	if ( gallery[index] != undefined) { 
		                var imageURL = gallery[index]['url'],
		                    optionsContent = $(this).html();

		                $(this).attr('data-img-src',imageURL);
		                $($element).imagepicker(imagePickerOptions);
	                }
	            }

	            $($element).closest('.piotnetforms-image-select-field').find('.thumbnail.selected').closest('li').addClass('disabled');
            });

		});

	};

	function initWidgetNumberSpiner($scope, $) {
		var $elements = $scope.find('[data-piotnetforms-spiner] input[type="number"]');

		if (!$elements.length) {
			return;
		}

		$.each($elements, function (i, $element) {
			$($element).niceNumber({
				autoSize: false,
			});
		});
    };

    function initWidgetDate($scope, $) {

        var $elements = $scope.find('.piotnetforms-date-field');

		if (!$elements.length) {
			return;
		}

		var addDatePicker = function addDatePicker($element) {
			if ($($element).hasClass('piotnetforms-use-native') || $($element).hasClass('flatpickr-custom-options')) { 
				return;
			}
			var options = {
				minDate: $($element).attr('min') || null,
				maxDate: $($element).attr('max') || null,
				dateFormat: $($element).attr('data-date-format') || null,
				defaultDate: $($element).attr('data-piotnetforms-value') || null,
				allowInput: true,
				animate: false,
				onReady: function(date) { 
					var day = parseInt( date[0] / (1000 * 60 * 60 * 24), 10);
					$($element).attr('data-piotnetforms-date-calculate', day);
				},
				onClose: function(date) { 
					var day = parseInt( date[0] / (1000 * 60 * 60 * 24), 10);
					$($element).attr('data-piotnetforms-date-calculate', day);
				}
			};

			if ($($element).data('piotnetforms-date-range') != undefined) {
				var options = {
					minDate: $($element).attr('min') || null,
					maxDate: $($element).attr('max') || null,
					dateFormat: $($element).attr('data-date-format') || null,
					defaultDate: $($element).attr('data-piotnetforms-value') || null,
					allowInput: true,
					animate: false,
					onClose: function(date) { 
						var startDay = flatpickr.formatDate(date[0], "m/d/Y");
						var endDay = flatpickr.formatDate(date[1], "m/d/Y");

						var newStartDate = new Date(startDay).getTime();
						var newEndDate = new Date(endDay).getTime();

						var newStartDate = eval( newStartDate / 1000 + 3600 ); // for GMT+1 I had to add 3600 (seconds) [1 hour]
						var newEndDate = eval( newEndDate / 1000 + 3600 ); // for GMT+1 I had to add 3600 (seconds) [1 hour]

						var countDays = eval( newEndDate - newStartDate );
						var countDays = eval( countDays / 86400 + 1 );

						$($element).attr('data-piotnetforms-date-range-days', countDays);
					}
				};

				options['mode'] = 'range';
			}

			if ($($element).data('piotnetforms-date-language') != 'english') { 
				options['locale'] = $($element).attr('data-piotnetforms-date-language');
			} 

			$element.flatpickr(options); 
		};

		$.each($elements, function (i, $element) {
			addDatePicker($element);
		});

    };

    function initWidgetTime($scope, $) {

	    var $elements = $scope.find('.piotnetforms-time-field');

		if (!$elements.length) {
			return;
		}

		var addTimePicker = function addTimePicker($element) {
			if ($($element).hasClass('piotnetforms-use-native')) {
				return;
			}

			var time_24hr = false;

			if ($($element).attr('data-piotnetforms-time-24hr') != undefined) {
				time_24hr = true;
			}

			$element.flatpickr({
				noCalendar: true,
				enableTime: true,
				allowInput: true,
				time_24hr: time_24hr,
				defaultDate: $($element).attr('data-piotnetforms-value') || null,
			});
		};
		$.each($elements, function (i, $element) {
			addTimePicker($element);
		});

	};

	function initWidgetSelectAutocomplete($scope, $) {
		var $elements = $scope.find('[data-piotnetforms-select-autocomplete]');

		if (!$elements.length) {
			return;
		}

		$.each($elements, function (i, $element) {
			$($element).selectize({
				dropdownParent: 'body',
			});
		});
    };

    function initWidgetSignature($scope, $) {

        var $elements = $scope.find('[data-piotnetforms-signature] canvas');

		if (!$elements.length) {
			return;
		}

		$.each($elements, function (i, $element) {
			var signaturePad = new SignaturePad($element);

			var $piotnetformsSingature = $($element).closest('[data-piotnetforms-signature]'),
				$clearButton = $piotnetformsSingature.find('[data-piotnetforms-signature-clear]'),
				$exportButton = $piotnetformsSingature.find('[data-piotnetforms-signature-export]');

			$clearButton.click(function(){
				signaturePad.clear();
			});

			$exportButton.click(function(){
				if (signaturePad.isEmpty()) {
					$piotnetformsSingature.find('.piotnetforms-field').val('');
				} else {
					var url = signaturePad.toDataURL();
					$piotnetformsSingature.find('.piotnetforms-field').val(url);
				}
			});
			
		});

    };

    function initWidgetTinymce($scope, $) {

	    var $elements = $scope.find('[data-piotnetforms-tinymce]');

		if (!$elements.length) {
			return;
		}

		$.each($elements, function (i, $element) {
			$($element).tinymce({
				script_url : pluginURL + '/piotnetforms/inc/forms/tinymce/tinymce.min.js',
				height: 500,
				// directionality :"<?php echo $rtl ;?>",
				menubar: false,
				plugins: [
					'advlist autolink lists link image charmap print preview anchor',
					'searchreplace visualblocks code fullscreen',
					'insertdatetime media table contextmenu paste code help youtube'
				],
				toolbar: 'bold italic link | alignleft aligncenter alignright alignjustify | bullist numlist | image youtube',
				image_title: true, 
				images_upload_url: pluginURL + '/piotnetforms/inc/forms/tinymce/tinymce-upload.php',
				file_picker_types: 'image',
				convert_urls: false,
				setup: function (editor) {
					editor.on('change', function () {
						tinymce.triggerSave();
					});
				}
			});
		});

	};

	$('[data-piotnetforms-preview-iframe]').on('load', function() {

		const $iframe = $(this).contents();
		$iframe.on('piotnet-widget-init-Piotnetforms_Field', '[data-piotnet-editor-widgets-item-root]', function(){
			initWidgetRangeSlider($(this), $);
			initWidgetImageSelect($(this), $);
			initWidgetDate($(this), $);
			initWidgetTime($(this), $);
			initWidgetSelectAutocomplete($(this), $);
			initWidgetSignature($(this), $);
			//initWidgetStripe($(this), $);
			
			if($(this).find('.nice-number').length == 0) {
				initWidgetNumberSpiner($(this), $);
			}
		});

	}); // End iframe load

	$(document).on('keyup change', '[name="field_id"]', function() {
		$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-field-shortcode').val('[field id="' + $(this).val() + '"]');
	});

	$(document).on('keyup change', '[name="piotnetforms_repeater_id"]', function() {
		$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-repeater-shortcode').val('[repeater id="' + $(this).val() + '"]');
	});

	$(document).on('keyup change', '[name="piotnetforms_booking_id"]', function() {
		$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-booking-shortcode').val('[field id="' + $(this).val() + '"]');
	});

	$(document).on('click', '.piotnetforms-field-shortcode', function() {
		if ($(this).val() == '') {
			var fieldId = $(this).closest('[data-piotnet-controls-section]').find('[name="field_id"]').val();
			$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-field-shortcode').val('[field id="' + fieldId + '"]');
		}
	});

	$(document).on('click', '.piotnetforms-repeater-shortcode', function() {
		if ($(this).val() == '') {
			var repeaterId = $(this).closest('[data-piotnet-controls-section]').find('[name="piotnetforms_repeater_id"]').val();
			$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-repeater-shortcode').val('[repeater id="' + repeaterId + '"]');
		}
	});

	$(document).on('click', '.piotnetforms-booking-shortcode', function() {
		if ($(this).val() == '') {
			var fieldId = $(this).closest('[data-piotnet-controls-section]').find('[name="piotnetforms_booking_id"]').val();
			$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-booking-shortcode').val('[field id="' + fieldId + '"]');
		}
	}); 

	$(document).on('click','[data-piotnetforms-campaign-get-data-list]', function() {
		$(document).find('[data-piotnetforms-campaign-get-data-list]').addClass('loading');
		var $parent = $(this).closest('[data-piotnet-controls-section-body]');
		var $results = $parent.find('[data-piotnetforms-campaign-get-data-list-results]');
		var campaign = $parent.find( '[name="activecampaign_api_key_source"]' ).val();
		if(campaign == 'custom'){
			campaign_url = $parent.find( '[name="activecampaign_api_url"]' ).val();
			campaign_key = $parent.find( '[name="activecampaign_api_key"]' ).val();
		}else{
			campaign_url = false;
			campaign_key = false;
		}
		var data = {
			'action': 'piotnetforms_campaign_select_list',
			'campaign_url': campaign_url,
			'campaign_key': campaign_key,
		};
		$.post(ajaxurl, data, function(response) {
			if(response){
				$results.html(response);
				$parent.find('[name="activecampaign_list"]').change();
				$(document).find('[data-piotnetforms-campaign-get-data-list]').removeClass('loading');
			}
		});
	});
	//?
	$(document).on('keyup, change','[name="activecampaign_list"]', function() {
		var $parent = $(this).closest('[data-piotnet-controls-section-body]');
		$parent.find('[data-piotnetforms-campaign-get-fields]').closest('.piotnet-control__field-group').find('label.piotnet-control__label').addClass('active');
		var campaign = $parent.find( '[name="activecampaign_api_key_source"]' ).val();
		var listId = $(this).val();
 		if(campaign == 'custom'){
			campaign_url = $parent.find( '[name="activecampaign_api_url"]' ).val();
			campaign_key = $parent.find( '[name="activecampaign_api_key"]' ).val();
		}else{
			campaign_url = false;
			campaign_key = false;
		}
		var data = {
			'action': 'piotnetforms_campaign_fields',
			'campaign_url': campaign_url,
			'campaign_key': campaign_key,
			'list_id': listId
		};
		$.post(ajaxurl, data, function(response) {
			if(response){
				$parent.find('[data-piotnetforms-campaign-get-fields]').html(response);
				$parent.find('[data-piotnetforms-campaign-get-fields]').closest('.piotnet-control__field-group').find('label.piotnet-control__label').removeClass('active');
			}else{
				$parent.find('[data-piotnetforms-campaign-get-fields]').closest('.piotnet-control__field-group').find('label.piotnet-control__label').removeClass('active');
			}
		});
	});

	//get response
	$(document).on('click','[data-piotnetforms-getresponse-get-data-list]', function() {
		$(document).find('[data-piotnetforms-getresponse-get-data-list]').addClass('loading');
		var $parent = $(this).closest('[data-piotnet-controls-section-body]');
		var getresponseApi = $parent.find( '[name="getresponse_api_key_source"]' ).val();
		if(getresponseApi == 'custom'){
			var getresponseApiKey = $parent.find( '[name="getresponse_api_key"]' ).val();
		}else{
			var getresponseApiKey = false;
		}
		var data = {
			'action': 'piotnetforms_getresponse_select_list',
			'api': getresponseApiKey,
		};
		$.post(ajaxurl, data, function(response) {
			if(response){
				$('#piotnetforms-getresponse-list').html(response);
				$('[data-piotnetforms-getresponse-get-data-list]').removeClass('loading');
			}
		});
	});

	$(document).on('click','[data-piotnetforms-getresponse-get-data-custom-fields]', function() {
		$(document).find('[data-piotnetforms-getresponse-get-data-custom-fields]').addClass('loading');
		var $parent = $(this).closest('[data-piotnet-controls-section-body]');
		var getresponseApi = $parent.find( '[name="getresponse_api_key_source"]' ).val();
		if(getresponseApi == 'custom'){
			var getresponseApiKey = $parent.find( '[name="getresponse_api_key"]' ).val();
		}else{
			var getresponseApiKey = false;
		}
		var data = {
			'action': 'piotnetforms_getresponse_custom_fields',
			'api' : getresponseApiKey
		}
		$.post(ajaxurl, data, function(response) {
			if(response){
				$('#piotnetforms-getresponse-custom-fields').html(response);
				$('[data-piotnetforms-getresponse-get-data-custom-fields]').removeClass('loading');
			}
		});
	});
	//Mailchimp
	$(document).on('click', '[data-piotnetforms-mailchimp-get-data-list]', function(){
		$(this).addClass('loading');
		const parent = $(this).closest('[data-piotnet-controls-section-body]');
		var mailchimp_api = parent.find('[name="mailchimp_api_key_source_v3"]').val();
		if(mailchimp_api == 'custom'){
			var api_key = parent.find('[name="mailchimp_api_key_v3"]').val();
		}else{
			var api_key = false;
		}
		var data = {
			'action': 'pafe_mailchimp_select_list',
			'api': api_key,
		};
		$.post(ajaxurl, data, function(response) {
			if(response){
				$('[data-piotnetforms-mailchimp-get-data-list-results]').html(response);
				$('[data-piotnetforms-mailchimp-get-data-list]').removeClass('loading');
			}
		});
	});
	$(document).on('click', '[data-piotnetforms-mailchimp-get-group-and-field]', function(){
		$(this).attr('disabled', 'disabled');
		$(this).addClass('loading');
		const $parent = $(this).closest('[data-piotnet-controls-section-body]');
		var listId = $parent.find('[name="mailchimp_list_id"]').val();
		var mailchimp_api = $parent.find('[name="mailchimp_api_key_source_v3"]').val();
		if(mailchimp_api == 'custom'){
			var api_key = $parent.find('[name="mailchimp_api_key_v3"]').val();
		}else{
			var api_key = false;
		}
		var data_fields = {
			'action': 'pafe_mailchimp_merge_fields',
			'api': api_key,
			'list_id': listId
		};
		var data_groups = {
			'action': 'pafe_mailchimp_get_groups',
			'api': api_key,
			'list_id': listId
		}
		$.post(ajaxurl, data_groups, function(response) {
			if(response){
				$('[data-piotnetforms-mailchimp-get-groups]').html(response);
			}
		});
		$.post(ajaxurl, data_fields, function(response) {
			if(response){
				$('[data-piotnetforms-mailchimp-get-data-merge-fields]').html(response);
				$('[data-piotnetforms-mailchimp-get-group-and-field]').removeClass('loading');
				$('[data-piotnetforms-mailchimp-get-group-and-field]').removeAttr('disabled');
			}
		});
	});
	// Mailpoet
	$(document).on('click', '[data-piotnet-mailpoet-get-custom-fields]', function(){
		$(this).addClass('loading');
		var data = {
			action: 'pafe_mailpoet_get_custom_fields'
		}
		$.post(ajaxurl, data, function(response) {
			if(response){
				$('[data-piotnet-mailpoet-result-custom-field]').html(response);
				$('[data-piotnet-mailpoet-get-custom-fields]').removeClass('loading');
			}
		})
	});
	//Zoho
	$(document).on('click', '[data-piotnetforms-zohocrm-get-tag-name]', function(){
		$(this).addClass('loading');
		$parent = $(this).closest('[data-piotnet-controls-section-body]');
		var module = $parent.find('[name="zohocrm_module"]').val();
		var zoho_data = {
			action: 'zoho_get_tag_name',
			module: module
		}
		$.post(ajaxurl, zoho_data, function(response){
			$('#piotnetforms-zohocrm-tag-name').html(response);
			$('[data-piotnetforms-zohocrm-get-tag-name]').removeClass('loading');
		});
	})
});
