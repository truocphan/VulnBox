import './lib/jquery-throttle.js';
import flatpickr from "flatpickr";
import ionRangeSlider from 'ion-rangeslider';
import select2 from 'select2';
import minicolors from '@claviska/jquery-minicolors';
import PerfectScrollbar from 'perfect-scrollbar';
import jqueryValidation from 'jquery-validation';
import additional_methods from 'jquery-validation/dist/additional-methods';
import jquery_mask_plugin from 'jquery-mask-plugin';

import View from './editor/view';
import piotnetforms from './editor/piotnetforms';
import SettingGenerator from "./generator/setting-generator";
import CSSGenerator from "./generator/css-generator";
import StringUtil from './util/string-util';
import ObjectUtil from './util/object-util';

const pb = new piotnetforms();
window.pb = pb;

// Ion.RangeSlider, 2.3.0, © Denis Ineshin, 2010 - 2018, IonDen.com, Build date: 2018-12-12 00:00:37
!function(i){!jQuery&&"function"==typeof define&&define.amd?define(["jquery"],function(t){return i(t,document,window,navigator)}):jQuery||"object"!=typeof exports?i(jQuery,document,window,navigator):i(require("jquery"),document,window,navigator)}(function(a,c,l,t,_){"use strict";var i,s,o=0,e=(i=t.userAgent,s=/msie\s\d+/i,0<i.search(s)&&s.exec(i).toString().split(" ")[1]<9&&(a("html").addClass("lt-ie9"),!0));Function.prototype.bind||(Function.prototype.bind=function(o){var e=this,h=[].slice;if("function"!=typeof e)throw new TypeError;var r=h.call(arguments,1),n=function(){if(this instanceof n){var t=function(){};t.prototype=e.prototype;var i=new t,s=e.apply(i,r.concat(h.call(arguments)));return Object(s)===s?s:i}return e.apply(o,r.concat(h.call(arguments)))};return n}),Array.prototype.indexOf||(Array.prototype.indexOf=function(t,i){var s;if(null==this)throw new TypeError('"this" is null or not defined');var o=Object(this),e=o.length>>>0;if(0===e)return-1;var h=+i||0;if(Math.abs(h)===1/0&&(h=0),e<=h)return-1;for(s=Math.max(0<=h?h:e-Math.abs(h),0);s<e;){if(s in o&&o[s]===t)return s;s++}return-1});var h=function(t,i,s){this.VERSION="2.3.0",this.input=t,this.plugin_count=s,this.current_plugin=0,this.calc_count=0,this.update_tm=0,this.old_from=0,this.old_to=0,this.old_min_interval=null,this.raf_id=null,this.dragging=!1,this.force_redraw=!1,this.no_diapason=!1,this.has_tab_index=!0,this.is_key=!1,this.is_update=!1,this.is_start=!0,this.is_finish=!1,this.is_active=!1,this.is_resize=!1,this.is_click=!1,i=i||{},this.$cache={win:a(l),body:a(c.body),input:a(t),cont:null,rs:null,min:null,max:null,from:null,to:null,single:null,bar:null,line:null,s_single:null,s_from:null,s_to:null,shad_single:null,shad_from:null,shad_to:null,edge:null,grid:null,grid_labels:[]},this.coords={x_gap:0,x_pointer:0,w_rs:0,w_rs_old:0,w_handle:0,p_gap:0,p_gap_left:0,p_gap_right:0,p_step:0,p_pointer:0,p_handle:0,p_single_fake:0,p_single_real:0,p_from_fake:0,p_from_real:0,p_to_fake:0,p_to_real:0,p_bar_x:0,p_bar_w:0,grid_gap:0,big_num:0,big:[],big_w:[],big_p:[],big_x:[]},this.labels={w_min:0,w_max:0,w_from:0,w_to:0,w_single:0,p_min:0,p_max:0,p_from_fake:0,p_from_left:0,p_to_fake:0,p_to_left:0,p_single_fake:0,p_single_left:0};var o,e,h,r=this.$cache.input,n=r.prop("value");for(h in o={skin:"flat",type:"single",min:10,max:100,from:null,to:null,step:1,min_interval:0,max_interval:0,drag_interval:!1,values:[],p_values:[],from_fixed:!1,from_min:null,from_max:null,from_shadow:!1,to_fixed:!1,to_min:null,to_max:null,to_shadow:!1,prettify_enabled:!0,prettify_separator:" ",prettify:null,force_edges:!1,keyboard:!0,grid:!1,grid_margin:!0,grid_num:4,grid_snap:!1,hide_min_max:!1,hide_from_to:!1,prefix:"",postfix:"",max_postfix:"",decorate_both:!0,values_separator:" — ",input_values_separator:";",disable:!1,block:!1,extra_classes:"",scope:null,onStart:null,onChange:null,onFinish:null,onUpdate:null},"INPUT"!==r[0].nodeName&&console&&console.warn&&console.warn("Base element should be <input>!",r[0]),(e={skin:r.data("skin"),type:r.data("type"),min:r.data("min"),max:r.data("max"),from:r.data("from"),to:r.data("to"),step:r.data("step"),min_interval:r.data("minInterval"),max_interval:r.data("maxInterval"),drag_interval:r.data("dragInterval"),values:r.data("values"),from_fixed:r.data("fromFixed"),from_min:r.data("fromMin"),from_max:r.data("fromMax"),from_shadow:r.data("fromShadow"),to_fixed:r.data("toFixed"),to_min:r.data("toMin"),to_max:r.data("toMax"),to_shadow:r.data("toShadow"),prettify_enabled:r.data("prettifyEnabled"),prettify_separator:r.data("prettifySeparator"),force_edges:r.data("forceEdges"),keyboard:r.data("keyboard"),grid:r.data("grid"),grid_margin:r.data("gridMargin"),grid_num:r.data("gridNum"),grid_snap:r.data("gridSnap"),hide_min_max:r.data("hideMinMax"),hide_from_to:r.data("hideFromTo"),prefix:r.data("prefix"),postfix:r.data("postfix"),max_postfix:r.data("maxPostfix"),decorate_both:r.data("decorateBoth"),values_separator:r.data("valuesSeparator"),input_values_separator:r.data("inputValuesSeparator"),disable:r.data("disable"),block:r.data("block"),extra_classes:r.data("extraClasses")}).values=e.values&&e.values.split(","),e)e.hasOwnProperty(h)&&(e[h]!==_&&""!==e[h]||delete e[h]);n!==_&&""!==n&&((n=n.split(e.input_values_separator||i.input_values_separator||";"))[0]&&n[0]==+n[0]&&(n[0]=+n[0]),n[1]&&n[1]==+n[1]&&(n[1]=+n[1]),i&&i.values&&i.values.length?(o.from=n[0]&&i.values.indexOf(n[0]),o.to=n[1]&&i.values.indexOf(n[1])):(o.from=n[0]&&+n[0],o.to=n[1]&&+n[1])),a.extend(o,i),a.extend(o,e),this.options=o,this.update_check={},this.validate(),this.result={input:this.$cache.input,slider:null,min:this.options.min,max:this.options.max,from:this.options.from,from_percent:0,from_value:null,to:this.options.to,to_percent:0,to_value:null},this.init()};h.prototype={init:function(t){this.no_diapason=!1,this.coords.p_step=this.convertToPercent(this.options.step,!0),this.target="base",this.toggleInput(),this.append(),this.setMinMax(),t?(this.force_redraw=!0,this.calc(!0),this.callOnUpdate()):(this.force_redraw=!0,this.calc(!0),this.callOnStart()),this.updateScene()},append:function(){var t='<span class="irs irs--'+this.options.skin+" js-irs-"+this.plugin_count+" "+this.options.extra_classes+'"></span>';this.$cache.input.before(t),this.$cache.input.prop("readonly",!0),this.$cache.cont=this.$cache.input.prev(),this.result.slider=this.$cache.cont,this.$cache.cont.html('<span class="irs"><span class="irs-line" tabindex="0"></span><span class="irs-min">0</span><span class="irs-max">1</span><span class="irs-from">0</span><span class="irs-to">0</span><span class="irs-single">0</span></span><span class="irs-grid"></span>'),this.$cache.rs=this.$cache.cont.find(".irs"),this.$cache.min=this.$cache.cont.find(".irs-min"),this.$cache.max=this.$cache.cont.find(".irs-max"),this.$cache.from=this.$cache.cont.find(".irs-from"),this.$cache.to=this.$cache.cont.find(".irs-to"),this.$cache.single=this.$cache.cont.find(".irs-single"),this.$cache.line=this.$cache.cont.find(".irs-line"),this.$cache.grid=this.$cache.cont.find(".irs-grid"),"single"===this.options.type?(this.$cache.cont.append('<span class="irs-bar irs-bar--single"></span><span class="irs-shadow shadow-single"></span><span class="irs-handle single"><i></i><i></i><i></i></span>'),this.$cache.bar=this.$cache.cont.find(".irs-bar"),this.$cache.edge=this.$cache.cont.find(".irs-bar-edge"),this.$cache.s_single=this.$cache.cont.find(".single"),this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.shad_single=this.$cache.cont.find(".shadow-single")):(this.$cache.cont.append('<span class="irs-bar"></span><span class="irs-shadow shadow-from"></span><span class="irs-shadow shadow-to"></span><span class="irs-handle from"><i></i><i></i><i></i></span><span class="irs-handle to"><i></i><i></i><i></i></span>'),this.$cache.bar=this.$cache.cont.find(".irs-bar"),this.$cache.s_from=this.$cache.cont.find(".from"),this.$cache.s_to=this.$cache.cont.find(".to"),this.$cache.shad_from=this.$cache.cont.find(".shadow-from"),this.$cache.shad_to=this.$cache.cont.find(".shadow-to"),this.setTopHandler()),this.options.hide_from_to&&(this.$cache.from[0].style.display="none",this.$cache.to[0].style.display="none",this.$cache.single[0].style.display="none"),this.appendGrid(),this.options.disable?(this.appendDisableMask(),this.$cache.input[0].disabled=!0):(this.$cache.input[0].disabled=!1,this.removeDisableMask(),this.bindEvents()),this.options.disable||(this.options.block?this.appendDisableMask():this.removeDisableMask()),this.options.drag_interval&&(this.$cache.bar[0].style.cursor="ew-resize")},setTopHandler:function(){var t=this.options.min,i=this.options.max,s=this.options.from,o=this.options.to;t<s&&o===i?this.$cache.s_from.addClass("type_last"):o<i&&this.$cache.s_to.addClass("type_last")},changeLevel:function(t){switch(t){case"single":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_single_fake),this.$cache.s_single.addClass("state_hover");break;case"from":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_from_fake),this.$cache.s_from.addClass("state_hover"),this.$cache.s_from.addClass("type_last"),this.$cache.s_to.removeClass("type_last");break;case"to":this.coords.p_gap=this.toFixed(this.coords.p_pointer-this.coords.p_to_fake),this.$cache.s_to.addClass("state_hover"),this.$cache.s_to.addClass("type_last"),this.$cache.s_from.removeClass("type_last");break;case"both":this.coords.p_gap_left=this.toFixed(this.coords.p_pointer-this.coords.p_from_fake),this.coords.p_gap_right=this.toFixed(this.coords.p_to_fake-this.coords.p_pointer),this.$cache.s_to.removeClass("type_last"),this.$cache.s_from.removeClass("type_last")}},appendDisableMask:function(){this.$cache.cont.append('<span class="irs-disable-mask"></span>'),this.$cache.cont.addClass("irs-disabled")},removeDisableMask:function(){this.$cache.cont.remove(".irs-disable-mask"),this.$cache.cont.removeClass("irs-disabled")},remove:function(){this.$cache.cont.remove(),this.$cache.cont=null,this.$cache.line.off("keydown.irs_"+this.plugin_count),this.$cache.body.off("touchmove.irs_"+this.plugin_count),this.$cache.body.off("mousemove.irs_"+this.plugin_count),this.$cache.win.off("touchend.irs_"+this.plugin_count),this.$cache.win.off("mouseup.irs_"+this.plugin_count),e&&(this.$cache.body.off("mouseup.irs_"+this.plugin_count),this.$cache.body.off("mouseleave.irs_"+this.plugin_count)),this.$cache.grid_labels=[],this.coords.big=[],this.coords.big_w=[],this.coords.big_p=[],this.coords.big_x=[],cancelAnimationFrame(this.raf_id)},bindEvents:function(){this.no_diapason||(this.$cache.body.on("touchmove.irs_"+this.plugin_count,this.pointerMove.bind(this)),this.$cache.body.on("mousemove.irs_"+this.plugin_count,this.pointerMove.bind(this)),this.$cache.win.on("touchend.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.win.on("mouseup.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.line.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.line.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.line.on("focus.irs_"+this.plugin_count,this.pointerFocus.bind(this)),this.options.drag_interval&&"double"===this.options.type?(this.$cache.bar.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"both")),this.$cache.bar.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"both"))):(this.$cache.bar.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.bar.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))),"single"===this.options.type?(this.$cache.single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.s_single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.shad_single.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.s_single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"single")),this.$cache.edge.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_single.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))):(this.$cache.single.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,null)),this.$cache.single.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,null)),this.$cache.from.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.s_from.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.to.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.s_to.on("touchstart.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.shad_from.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_to.on("touchstart.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.from.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.s_from.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"from")),this.$cache.to.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.s_to.on("mousedown.irs_"+this.plugin_count,this.pointerDown.bind(this,"to")),this.$cache.shad_from.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click")),this.$cache.shad_to.on("mousedown.irs_"+this.plugin_count,this.pointerClick.bind(this,"click"))),this.options.keyboard&&this.$cache.line.on("keydown.irs_"+this.plugin_count,this.key.bind(this,"keyboard")),e&&(this.$cache.body.on("mouseup.irs_"+this.plugin_count,this.pointerUp.bind(this)),this.$cache.body.on("mouseleave.irs_"+this.plugin_count,this.pointerUp.bind(this))))},pointerFocus:function(t){var i,s;this.target||(i=(s="single"===this.options.type?this.$cache.single:this.$cache.from).offset().left,i+=s.width()/2-1,this.pointerClick("single",{preventDefault:function(){},pageX:i}))},pointerMove:function(t){if(this.dragging){var i=t.pageX||t.originalEvent.touches&&t.originalEvent.touches[0].pageX;this.coords.x_pointer=i-this.coords.x_gap,this.calc()}},pointerUp:function(t){this.current_plugin===this.plugin_count&&this.is_active&&(this.is_active=!1,this.$cache.cont.find(".state_hover").removeClass("state_hover"),this.force_redraw=!0,e&&a("*").prop("unselectable",!1),this.updateScene(),this.restoreOriginalMinInterval(),(a.contains(this.$cache.cont[0],t.target)||this.dragging)&&this.callOnFinish(),this.dragging=!1)},pointerDown:function(t,i){i.preventDefault();var s=i.pageX||i.originalEvent.touches&&i.originalEvent.touches[0].pageX;2!==i.button&&("both"===t&&this.setTempMinInterval(),t||(t=this.target||"from"),this.current_plugin=this.plugin_count,this.target=t,this.is_active=!0,this.dragging=!0,this.coords.x_gap=this.$cache.rs.offset().left,this.coords.x_pointer=s-this.coords.x_gap,this.calcPointerPercent(),this.changeLevel(t),e&&a("*").prop("unselectable",!0),this.$cache.line.trigger("focus"),this.updateScene())},pointerClick:function(t,i){i.preventDefault();var s=i.pageX||i.originalEvent.touches&&i.originalEvent.touches[0].pageX;2!==i.button&&(this.current_plugin=this.plugin_count,this.target=t,this.is_click=!0,this.coords.x_gap=this.$cache.rs.offset().left,this.coords.x_pointer=+(s-this.coords.x_gap).toFixed(),this.force_redraw=!0,this.calc(),this.$cache.line.trigger("focus"))},key:function(t,i){if(!(this.current_plugin!==this.plugin_count||i.altKey||i.ctrlKey||i.shiftKey||i.metaKey)){switch(i.which){case 83:case 65:case 40:case 37:i.preventDefault(),this.moveByKey(!1);break;case 87:case 68:case 38:case 39:i.preventDefault(),this.moveByKey(!0)}return!0}},moveByKey:function(t){var i=this.coords.p_pointer,s=(this.options.max-this.options.min)/100;s=this.options.step/s,t?i+=s:i-=s,this.coords.x_pointer=this.toFixed(this.coords.w_rs/100*i),this.is_key=!0,this.calc()},setMinMax:function(){if(this.options){if(this.options.hide_min_max)return this.$cache.min[0].style.display="none",void(this.$cache.max[0].style.display="none");if(this.options.values.length)this.$cache.min.html(this.decorate(this.options.p_values[this.options.min])),this.$cache.max.html(this.decorate(this.options.p_values[this.options.max]));else{var t=this._prettify(this.options.min),i=this._prettify(this.options.max);this.result.min_pretty=t,this.result.max_pretty=i,this.$cache.min.html(this.decorate(t,this.options.min)),this.$cache.max.html(this.decorate(i,this.options.max))}this.labels.w_min=this.$cache.min.outerWidth(!1),this.labels.w_max=this.$cache.max.outerWidth(!1)}},setTempMinInterval:function(){var t=this.result.to-this.result.from;null===this.old_min_interval&&(this.old_min_interval=this.options.min_interval),this.options.min_interval=t},restoreOriginalMinInterval:function(){null!==this.old_min_interval&&(this.options.min_interval=this.old_min_interval,this.old_min_interval=null)},calc:function(t){if(this.options&&(this.calc_count++,(10===this.calc_count||t)&&(this.calc_count=0,this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.calcHandlePercent()),this.coords.w_rs)){this.calcPointerPercent();var i=this.getHandleX();switch("both"===this.target&&(this.coords.p_gap=0,i=this.getHandleX()),"click"===this.target&&(this.coords.p_gap=this.coords.p_handle/2,i=this.getHandleX(),this.options.drag_interval?this.target="both_one":this.target=this.chooseHandle(i)),this.target){case"base":var s=(this.options.max-this.options.min)/100,o=(this.result.from-this.options.min)/s,e=(this.result.to-this.options.min)/s;this.coords.p_single_real=this.toFixed(o),this.coords.p_from_real=this.toFixed(o),this.coords.p_to_real=this.toFixed(e),this.coords.p_single_real=this.checkDiapason(this.coords.p_single_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_single_fake=this.convertToFakePercent(this.coords.p_single_real),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real),this.target=null;break;case"single":if(this.options.from_fixed)break;this.coords.p_single_real=this.convertToRealPercent(i),this.coords.p_single_real=this.calcWithStep(this.coords.p_single_real),this.coords.p_single_real=this.checkDiapason(this.coords.p_single_real,this.options.from_min,this.options.from_max),this.coords.p_single_fake=this.convertToFakePercent(this.coords.p_single_real);break;case"from":if(this.options.from_fixed)break;this.coords.p_from_real=this.convertToRealPercent(i),this.coords.p_from_real=this.calcWithStep(this.coords.p_from_real),this.coords.p_from_real>this.coords.p_to_real&&(this.coords.p_from_real=this.coords.p_to_real),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkMinInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_real=this.checkMaxInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real);break;case"to":if(this.options.to_fixed)break;this.coords.p_to_real=this.convertToRealPercent(i),this.coords.p_to_real=this.calcWithStep(this.coords.p_to_real),this.coords.p_to_real<this.coords.p_from_real&&(this.coords.p_to_real=this.coords.p_from_real),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_real=this.checkMinInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_real=this.checkMaxInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real);break;case"both":if(this.options.from_fixed||this.options.to_fixed)break;i=this.toFixed(i+.001*this.coords.p_handle),this.coords.p_from_real=this.convertToRealPercent(i)-this.coords.p_gap_left,this.coords.p_from_real=this.calcWithStep(this.coords.p_from_real),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_real=this.checkMinInterval(this.coords.p_from_real,this.coords.p_to_real,"from"),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_real=this.convertToRealPercent(i)+this.coords.p_gap_right,this.coords.p_to_real=this.calcWithStep(this.coords.p_to_real),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_real=this.checkMinInterval(this.coords.p_to_real,this.coords.p_from_real,"to"),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real);break;case"both_one":if(this.options.from_fixed||this.options.to_fixed)break;var h=this.convertToRealPercent(i),r=this.result.from_percent,n=this.result.to_percent-r,a=n/2,c=h-a,l=h+a;c<0&&(l=(c=0)+n),100<l&&(c=(l=100)-n),this.coords.p_from_real=this.calcWithStep(c),this.coords.p_from_real=this.checkDiapason(this.coords.p_from_real,this.options.from_min,this.options.from_max),this.coords.p_from_fake=this.convertToFakePercent(this.coords.p_from_real),this.coords.p_to_real=this.calcWithStep(l),this.coords.p_to_real=this.checkDiapason(this.coords.p_to_real,this.options.to_min,this.options.to_max),this.coords.p_to_fake=this.convertToFakePercent(this.coords.p_to_real)}"single"===this.options.type?(this.coords.p_bar_x=this.coords.p_handle/2,this.coords.p_bar_w=this.coords.p_single_fake,this.result.from_percent=this.coords.p_single_real,this.result.from=this.convertToValue(this.coords.p_single_real),this.result.from_pretty=this._prettify(this.result.from),this.options.values.length&&(this.result.from_value=this.options.values[this.result.from])):(this.coords.p_bar_x=this.toFixed(this.coords.p_from_fake+this.coords.p_handle/2),this.coords.p_bar_w=this.toFixed(this.coords.p_to_fake-this.coords.p_from_fake),this.result.from_percent=this.coords.p_from_real,this.result.from=this.convertToValue(this.coords.p_from_real),this.result.from_pretty=this._prettify(this.result.from),this.result.to_percent=this.coords.p_to_real,this.result.to=this.convertToValue(this.coords.p_to_real),this.result.to_pretty=this._prettify(this.result.to),this.options.values.length&&(this.result.from_value=this.options.values[this.result.from],this.result.to_value=this.options.values[this.result.to])),this.calcMinMax(),this.calcLabels()}},calcPointerPercent:function(){this.coords.w_rs?(this.coords.x_pointer<0||isNaN(this.coords.x_pointer)?this.coords.x_pointer=0:this.coords.x_pointer>this.coords.w_rs&&(this.coords.x_pointer=this.coords.w_rs),this.coords.p_pointer=this.toFixed(this.coords.x_pointer/this.coords.w_rs*100)):this.coords.p_pointer=0},convertToRealPercent:function(t){return t/(100-this.coords.p_handle)*100},convertToFakePercent:function(t){return t/100*(100-this.coords.p_handle)},getHandleX:function(){var t=100-this.coords.p_handle,i=this.toFixed(this.coords.p_pointer-this.coords.p_gap);return i<0?i=0:t<i&&(i=t),i},calcHandlePercent:function(){"single"===this.options.type?this.coords.w_handle=this.$cache.s_single.outerWidth(!1):this.coords.w_handle=this.$cache.s_from.outerWidth(!1),this.coords.p_handle=this.toFixed(this.coords.w_handle/this.coords.w_rs*100)},chooseHandle:function(t){return"single"===this.options.type?"single":this.coords.p_from_real+(this.coords.p_to_real-this.coords.p_from_real)/2<=t?this.options.to_fixed?"from":"to":this.options.from_fixed?"to":"from"},calcMinMax:function(){this.coords.w_rs&&(this.labels.p_min=this.labels.w_min/this.coords.w_rs*100,this.labels.p_max=this.labels.w_max/this.coords.w_rs*100)},calcLabels:function(){this.coords.w_rs&&!this.options.hide_from_to&&("single"===this.options.type?(this.labels.w_single=this.$cache.single.outerWidth(!1),this.labels.p_single_fake=this.labels.w_single/this.coords.w_rs*100,this.labels.p_single_left=this.coords.p_single_fake+this.coords.p_handle/2-this.labels.p_single_fake/2):(this.labels.w_from=this.$cache.from.outerWidth(!1),this.labels.p_from_fake=this.labels.w_from/this.coords.w_rs*100,this.labels.p_from_left=this.coords.p_from_fake+this.coords.p_handle/2-this.labels.p_from_fake/2,this.labels.p_from_left=this.toFixed(this.labels.p_from_left),this.labels.p_from_left=this.checkEdges(this.labels.p_from_left,this.labels.p_from_fake),this.labels.w_to=this.$cache.to.outerWidth(!1),this.labels.p_to_fake=this.labels.w_to/this.coords.w_rs*100,this.labels.p_to_left=this.coords.p_to_fake+this.coords.p_handle/2-this.labels.p_to_fake/2,this.labels.p_to_left=this.toFixed(this.labels.p_to_left),this.labels.p_to_left=this.checkEdges(this.labels.p_to_left,this.labels.p_to_fake),this.labels.w_single=this.$cache.single.outerWidth(!1),this.labels.p_single_fake=this.labels.w_single/this.coords.w_rs*100,this.labels.p_single_left=(this.labels.p_from_left+this.labels.p_to_left+this.labels.p_to_fake)/2-this.labels.p_single_fake/2,this.labels.p_single_left=this.toFixed(this.labels.p_single_left)),this.labels.p_single_left=this.checkEdges(this.labels.p_single_left,this.labels.p_single_fake))},updateScene:function(){this.raf_id&&(cancelAnimationFrame(this.raf_id),this.raf_id=null),clearTimeout(this.update_tm),this.update_tm=null,this.options&&(this.drawHandles(),this.is_active?this.raf_id=requestAnimationFrame(this.updateScene.bind(this)):this.update_tm=setTimeout(this.updateScene.bind(this),300))},drawHandles:function(){this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.coords.w_rs&&(this.coords.w_rs!==this.coords.w_rs_old&&(this.target="base",this.is_resize=!0),(this.coords.w_rs!==this.coords.w_rs_old||this.force_redraw)&&(this.setMinMax(),this.calc(!0),this.drawLabels(),this.options.grid&&(this.calcGridMargin(),this.calcGridLabels()),this.force_redraw=!0,this.coords.w_rs_old=this.coords.w_rs,this.drawShadow()),this.coords.w_rs&&(this.dragging||this.force_redraw||this.is_key)&&((this.old_from!==this.result.from||this.old_to!==this.result.to||this.force_redraw||this.is_key)&&(this.drawLabels(),this.$cache.bar[0].style.left=this.coords.p_bar_x+"%",this.$cache.bar[0].style.width=this.coords.p_bar_w+"%","single"===this.options.type?(this.$cache.bar[0].style.left=0,this.$cache.bar[0].style.width=this.coords.p_bar_w+this.coords.p_bar_x+"%",this.$cache.s_single[0].style.left=this.coords.p_single_fake+"%"):(this.$cache.s_from[0].style.left=this.coords.p_from_fake+"%",this.$cache.s_to[0].style.left=this.coords.p_to_fake+"%",(this.old_from!==this.result.from||this.force_redraw)&&(this.$cache.from[0].style.left=this.labels.p_from_left+"%"),(this.old_to!==this.result.to||this.force_redraw)&&(this.$cache.to[0].style.left=this.labels.p_to_left+"%")),this.$cache.single[0].style.left=this.labels.p_single_left+"%",this.writeToInput(),this.old_from===this.result.from&&this.old_to===this.result.to||this.is_start||(this.$cache.input.trigger("change"),this.$cache.input.trigger("input")),this.old_from=this.result.from,this.old_to=this.result.to,this.is_resize||this.is_update||this.is_start||this.is_finish||this.callOnChange(),(this.is_key||this.is_click)&&(this.is_key=!1,this.is_click=!1,this.callOnFinish()),this.is_update=!1,this.is_resize=!1,this.is_finish=!1),this.is_start=!1,this.is_key=!1,this.is_click=!1,this.force_redraw=!1))},drawLabels:function(){if(this.options){var t,i,s,o,e,h=this.options.values.length,r=this.options.p_values;if(!this.options.hide_from_to)if("single"===this.options.type)t=h?this.decorate(r[this.result.from]):(o=this._prettify(this.result.from),this.decorate(o,this.result.from)),this.$cache.single.html(t),this.calcLabels(),this.labels.p_single_left<this.labels.p_min+1?this.$cache.min[0].style.visibility="hidden":this.$cache.min[0].style.visibility="visible",this.labels.p_single_left+this.labels.p_single_fake>100-this.labels.p_max-1?this.$cache.max[0].style.visibility="hidden":this.$cache.max[0].style.visibility="visible";else{s=h?(this.options.decorate_both?(t=this.decorate(r[this.result.from]),t+=this.options.values_separator,t+=this.decorate(r[this.result.to])):t=this.decorate(r[this.result.from]+this.options.values_separator+r[this.result.to]),i=this.decorate(r[this.result.from]),this.decorate(r[this.result.to])):(o=this._prettify(this.result.from),e=this._prettify(this.result.to),this.options.decorate_both?(t=this.decorate(o,this.result.from),t+=this.options.values_separator,t+=this.decorate(e,this.result.to)):t=this.decorate(o+this.options.values_separator+e,this.result.to),i=this.decorate(o,this.result.from),this.decorate(e,this.result.to)),this.$cache.single.html(t),this.$cache.from.html(i),this.$cache.to.html(s),this.calcLabels();var n=Math.min(this.labels.p_single_left,this.labels.p_from_left),a=this.labels.p_single_left+this.labels.p_single_fake,c=this.labels.p_to_left+this.labels.p_to_fake,l=Math.max(a,c);this.labels.p_from_left+this.labels.p_from_fake>=this.labels.p_to_left?(this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.single[0].style.visibility="visible",l=this.result.from===this.result.to?("from"===this.target?this.$cache.from[0].style.visibility="visible":"to"===this.target?this.$cache.to[0].style.visibility="visible":this.target||(this.$cache.from[0].style.visibility="visible"),this.$cache.single[0].style.visibility="hidden",c):(this.$cache.from[0].style.visibility="hidden",this.$cache.to[0].style.visibility="hidden",this.$cache.single[0].style.visibility="visible",Math.max(a,c))):(this.$cache.from[0].style.visibility="visible",this.$cache.to[0].style.visibility="visible",this.$cache.single[0].style.visibility="hidden"),n<this.labels.p_min+1?this.$cache.min[0].style.visibility="hidden":this.$cache.min[0].style.visibility="visible",l>100-this.labels.p_max-1?this.$cache.max[0].style.visibility="hidden":this.$cache.max[0].style.visibility="visible"}}},drawShadow:function(){var t,i,s,o,e=this.options,h=this.$cache,r="number"==typeof e.from_min&&!isNaN(e.from_min),n="number"==typeof e.from_max&&!isNaN(e.from_max),a="number"==typeof e.to_min&&!isNaN(e.to_min),c="number"==typeof e.to_max&&!isNaN(e.to_max);"single"===e.type?e.from_shadow&&(r||n)?(t=this.convertToPercent(r?e.from_min:e.min),i=this.convertToPercent(n?e.from_max:e.max)-t,t=this.toFixed(t-this.coords.p_handle/100*t),i=this.toFixed(i-this.coords.p_handle/100*i),t+=this.coords.p_handle/2,h.shad_single[0].style.display="block",h.shad_single[0].style.left=t+"%",h.shad_single[0].style.width=i+"%"):h.shad_single[0].style.display="none":(e.from_shadow&&(r||n)?(t=this.convertToPercent(r?e.from_min:e.min),i=this.convertToPercent(n?e.from_max:e.max)-t,t=this.toFixed(t-this.coords.p_handle/100*t),i=this.toFixed(i-this.coords.p_handle/100*i),t+=this.coords.p_handle/2,h.shad_from[0].style.display="block",h.shad_from[0].style.left=t+"%",h.shad_from[0].style.width=i+"%"):h.shad_from[0].style.display="none",e.to_shadow&&(a||c)?(s=this.convertToPercent(a?e.to_min:e.min),o=this.convertToPercent(c?e.to_max:e.max)-s,s=this.toFixed(s-this.coords.p_handle/100*s),o=this.toFixed(o-this.coords.p_handle/100*o),s+=this.coords.p_handle/2,h.shad_to[0].style.display="block",h.shad_to[0].style.left=s+"%",h.shad_to[0].style.width=o+"%"):h.shad_to[0].style.display="none")},writeToInput:function(){"single"===this.options.type?(this.options.values.length?this.$cache.input.prop("value",this.result.from_value):this.$cache.input.prop("value",this.result.from),this.$cache.input.data("from",this.result.from)):(this.options.values.length?this.$cache.input.prop("value",this.result.from_value+this.options.input_values_separator+this.result.to_value):this.$cache.input.prop("value",this.result.from+this.options.input_values_separator+this.result.to),this.$cache.input.data("from",this.result.from),this.$cache.input.data("to",this.result.to))},callOnStart:function(){this.writeToInput(),this.options.onStart&&"function"==typeof this.options.onStart&&(this.options.scope?this.options.onStart.call(this.options.scope,this.result):this.options.onStart(this.result))},callOnChange:function(){this.writeToInput(),this.options.onChange&&"function"==typeof this.options.onChange&&(this.options.scope?this.options.onChange.call(this.options.scope,this.result):this.options.onChange(this.result))},callOnFinish:function(){this.writeToInput(),this.options.onFinish&&"function"==typeof this.options.onFinish&&(this.options.scope?this.options.onFinish.call(this.options.scope,this.result):this.options.onFinish(this.result))},callOnUpdate:function(){this.writeToInput(),this.options.onUpdate&&"function"==typeof this.options.onUpdate&&(this.options.scope?this.options.onUpdate.call(this.options.scope,this.result):this.options.onUpdate(this.result))},toggleInput:function(){this.$cache.input.toggleClass("irs-hidden-input"),this.has_tab_index?this.$cache.input.prop("tabindex",-1):this.$cache.input.removeProp("tabindex"),this.has_tab_index=!this.has_tab_index},convertToPercent:function(t,i){var s,o=this.options.max-this.options.min,e=o/100;return o?(s=(i?t:t-this.options.min)/e,this.toFixed(s)):(this.no_diapason=!0,0)},convertToValue:function(t){var i,s,o=this.options.min,e=this.options.max,h=o.toString().split(".")[1],r=e.toString().split(".")[1],n=0,a=0;if(0===t)return this.options.min;if(100===t)return this.options.max;h&&(n=i=h.length),r&&(n=s=r.length),i&&s&&(n=s<=i?i:s),o<0&&(o=+(o+(a=Math.abs(o))).toFixed(n),e=+(e+a).toFixed(n));var c,l=(e-o)/100*t+o,_=this.options.step.toString().split(".")[1];return l=_?+l.toFixed(_.length):(l/=this.options.step,+(l*=this.options.step).toFixed(0)),a&&(l-=a),(c=_?+l.toFixed(_.length):this.toFixed(l))<this.options.min?c=this.options.min:c>this.options.max&&(c=this.options.max),c},calcWithStep:function(t){var i=Math.round(t/this.coords.p_step)*this.coords.p_step;return 100<i&&(i=100),100===t&&(i=100),this.toFixed(i)},checkMinInterval:function(t,i,s){var o,e,h=this.options;return h.min_interval?(o=this.convertToValue(t),e=this.convertToValue(i),"from"===s?e-o<h.min_interval&&(o=e-h.min_interval):o-e<h.min_interval&&(o=e+h.min_interval),this.convertToPercent(o)):t},checkMaxInterval:function(t,i,s){var o,e,h=this.options;return h.max_interval?(o=this.convertToValue(t),e=this.convertToValue(i),"from"===s?e-o>h.max_interval&&(o=e-h.max_interval):o-e>h.max_interval&&(o=e+h.max_interval),this.convertToPercent(o)):t},checkDiapason:function(t,i,s){var o=this.convertToValue(t),e=this.options;return"number"!=typeof i&&(i=e.min),"number"!=typeof s&&(s=e.max),o<i&&(o=i),s<o&&(o=s),this.convertToPercent(o)},toFixed:function(t){return+(t=t.toFixed(20))},_prettify:function(t){return this.options.prettify_enabled?this.options.prettify&&"function"==typeof this.options.prettify?this.options.prettify(t):this.prettify(t):t},prettify:function(t){return t.toString().replace(/(\d{1,3}(?=(?:\d\d\d)+(?!\d)))/g,"$1"+this.options.prettify_separator)},checkEdges:function(t,i){return this.options.force_edges&&(t<0?t=0:100-i<t&&(t=100-i)),this.toFixed(t)},validate:function(){var t,i,s=this.options,o=this.result,e=s.values,h=e.length;if("string"==typeof s.min&&(s.min=+s.min),"string"==typeof s.max&&(s.max=+s.max),"string"==typeof s.from&&(s.from=+s.from),"string"==typeof s.to&&(s.to=+s.to),"string"==typeof s.step&&(s.step=+s.step),"string"==typeof s.from_min&&(s.from_min=+s.from_min),"string"==typeof s.from_max&&(s.from_max=+s.from_max),"string"==typeof s.to_min&&(s.to_min=+s.to_min),"string"==typeof s.to_max&&(s.to_max=+s.to_max),"string"==typeof s.grid_num&&(s.grid_num=+s.grid_num),s.max<s.min&&(s.max=s.min),h)for(s.p_values=[],s.min=0,s.max=h-1,s.step=1,s.grid_num=s.max,s.grid_snap=!0,i=0;i<h;i++)t=+e[i],t=isNaN(t)?e[i]:(e[i]=t,this._prettify(t)),s.p_values.push(t);("number"!=typeof s.from||isNaN(s.from))&&(s.from=s.min),("number"!=typeof s.to||isNaN(s.to))&&(s.to=s.max),"single"===s.type?(s.from<s.min&&(s.from=s.min),s.from>s.max&&(s.from=s.max)):(s.from<s.min&&(s.from=s.min),s.from>s.max&&(s.from=s.max),s.to<s.min&&(s.to=s.min),s.to>s.max&&(s.to=s.max),this.update_check.from&&(this.update_check.from!==s.from&&s.from>s.to&&(s.from=s.to),this.update_check.to!==s.to&&s.to<s.from&&(s.to=s.from)),s.from>s.to&&(s.from=s.to),s.to<s.from&&(s.to=s.from)),("number"!=typeof s.step||isNaN(s.step)||!s.step||s.step<0)&&(s.step=1),"number"==typeof s.from_min&&s.from<s.from_min&&(s.from=s.from_min),"number"==typeof s.from_max&&s.from>s.from_max&&(s.from=s.from_max),"number"==typeof s.to_min&&s.to<s.to_min&&(s.to=s.to_min),"number"==typeof s.to_max&&s.from>s.to_max&&(s.to=s.to_max),o&&(o.min!==s.min&&(o.min=s.min),o.max!==s.max&&(o.max=s.max),(o.from<o.min||o.from>o.max)&&(o.from=s.from),(o.to<o.min||o.to>o.max)&&(o.to=s.to)),("number"!=typeof s.min_interval||isNaN(s.min_interval)||!s.min_interval||s.min_interval<0)&&(s.min_interval=0),("number"!=typeof s.max_interval||isNaN(s.max_interval)||!s.max_interval||s.max_interval<0)&&(s.max_interval=0),s.min_interval&&s.min_interval>s.max-s.min&&(s.min_interval=s.max-s.min),s.max_interval&&s.max_interval>s.max-s.min&&(s.max_interval=s.max-s.min)},decorate:function(t,i){var s="",o=this.options;return o.prefix&&(s+=o.prefix),s+=t,o.max_postfix&&(o.values.length&&t===o.p_values[o.max]?(s+=o.max_postfix,o.postfix&&(s+=" ")):i===o.max&&(s+=o.max_postfix,o.postfix&&(s+=" "))),o.postfix&&(s+=o.postfix),s},updateFrom:function(){this.result.from=this.options.from,this.result.from_percent=this.convertToPercent(this.result.from),this.result.from_pretty=this._prettify(this.result.from),this.options.values&&(this.result.from_value=this.options.values[this.result.from])},updateTo:function(){this.result.to=this.options.to,this.result.to_percent=this.convertToPercent(this.result.to),this.result.to_pretty=this._prettify(this.result.to),this.options.values&&(this.result.to_value=this.options.values[this.result.to])},updateResult:function(){this.result.min=this.options.min,this.result.max=this.options.max,this.updateFrom(),this.updateTo()},appendGrid:function(){if(this.options.grid){var t,i,s,o,e,h,r=this.options,n=r.max-r.min,a=r.grid_num,c=0,l=4,_="";for(this.calcGridMargin(),r.grid_snap&&(a=n/r.step),50<a&&(a=50),s=this.toFixed(100/a),4<a&&(l=3),7<a&&(l=2),14<a&&(l=1),28<a&&(l=0),t=0;t<a+1;t++){for(o=l,100<(c=this.toFixed(s*t))&&(c=100),e=((this.coords.big[t]=c)-s*(t-1))/(o+1),i=1;i<=o&&0!==c;i++)_+='<span class="irs-grid-pol small" style="left: '+this.toFixed(c-e*i)+'%"></span>';_+='<span class="irs-grid-pol" style="left: '+c+'%"></span>',h=this.convertToValue(c),_+='<span class="irs-grid-text js-grid-text-'+t+'" style="left: '+c+'%">'+(h=r.values.length?r.p_values[h]:this._prettify(h))+"</span>"}this.coords.big_num=Math.ceil(a+1),this.$cache.cont.addClass("irs-with-grid"),this.$cache.grid.html(_),this.cacheGridLabels()}},cacheGridLabels:function(){var t,i,s=this.coords.big_num;for(i=0;i<s;i++)t=this.$cache.grid.find(".js-grid-text-"+i),this.$cache.grid_labels.push(t);this.calcGridLabels()},calcGridLabels:function(){var t,i,s=[],o=[],e=this.coords.big_num;for(t=0;t<e;t++)this.coords.big_w[t]=this.$cache.grid_labels[t].outerWidth(!1),this.coords.big_p[t]=this.toFixed(this.coords.big_w[t]/this.coords.w_rs*100),this.coords.big_x[t]=this.toFixed(this.coords.big_p[t]/2),s[t]=this.toFixed(this.coords.big[t]-this.coords.big_x[t]),o[t]=this.toFixed(s[t]+this.coords.big_p[t]);for(this.options.force_edges&&(s[0]<-this.coords.grid_gap&&(s[0]=-this.coords.grid_gap,o[0]=this.toFixed(s[0]+this.coords.big_p[0]),this.coords.big_x[0]=this.coords.grid_gap),o[e-1]>100+this.coords.grid_gap&&(o[e-1]=100+this.coords.grid_gap,s[e-1]=this.toFixed(o[e-1]-this.coords.big_p[e-1]),this.coords.big_x[e-1]=this.toFixed(this.coords.big_p[e-1]-this.coords.grid_gap))),this.calcGridCollision(2,s,o),this.calcGridCollision(4,s,o),t=0;t<e;t++)i=this.$cache.grid_labels[t][0],this.coords.big_x[t]!==Number.POSITIVE_INFINITY&&(i.style.marginLeft=-this.coords.big_x[t]+"%")},calcGridCollision:function(t,i,s){var o,e,h,r=this.coords.big_num;for(o=0;o<r&&!(r<=(e=o+t/2));o+=t)h=this.$cache.grid_labels[e][0],s[o]<=i[e]?h.style.visibility="visible":h.style.visibility="hidden"},calcGridMargin:function(){this.options.grid_margin&&(this.coords.w_rs=this.$cache.rs.outerWidth(!1),this.coords.w_rs&&("single"===this.options.type?this.coords.w_handle=this.$cache.s_single.outerWidth(!1):this.coords.w_handle=this.$cache.s_from.outerWidth(!1),this.coords.p_handle=this.toFixed(this.coords.w_handle/this.coords.w_rs*100),this.coords.grid_gap=this.toFixed(this.coords.p_handle/2-.1),this.$cache.grid[0].style.width=this.toFixed(100-this.coords.p_handle)+"%",this.$cache.grid[0].style.left=this.coords.grid_gap+"%"))},update:function(t){this.input&&(this.is_update=!0,this.options.from=this.result.from,this.options.to=this.result.to,this.update_check.from=this.result.from,this.update_check.to=this.result.to,this.options=a.extend(this.options,t),this.validate(),this.updateResult(t),this.toggleInput(),this.remove(),this.init(!0))},reset:function(){this.input&&(this.updateResult(),this.update())},destroy:function(){this.input&&(this.toggleInput(),this.$cache.input.prop("readonly",!1),a.data(this.input,"ionRangeSlider",null),this.remove(),this.input=null,this.options=null)}},a.fn.ionRangeSlider=function(t){return this.each(function(){a.data(this,"ionRangeSlider")||a.data(this,"ionRangeSlider",new h(this,t,o++))})},function(){for(var h=0,t=["ms","moz","webkit","o"],i=0;i<t.length&&!l.requestAnimationFrame;++i)l.requestAnimationFrame=l[t[i]+"RequestAnimationFrame"],l.cancelAnimationFrame=l[t[i]+"CancelAnimationFrame"]||l[t[i]+"CancelRequestAnimationFrame"];l.requestAnimationFrame||(l.requestAnimationFrame=function(t,i){var s=(new Date).getTime(),o=Math.max(0,16-(s-h)),e=l.setTimeout(function(){t(s+o)},o);return h=s+o,e}),l.cancelAnimationFrame||(l.cancelAnimationFrame=function(t){clearTimeout(t)})}()});

"use strict";function _classCallCheck(instance,Constructor){if(!(instance instanceof Constructor))throw new TypeError("Cannot call a class as a function")}var _createClass=function(){function defineProperties(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||!1,descriptor.configurable=!0,"value"in descriptor&&(descriptor.writable=!0),Object.defineProperty(target,descriptor.key,descriptor)}}return function(Constructor,protoProps,staticProps){return protoProps&&defineProperties(Constructor.prototype,protoProps),staticProps&&defineProperties(Constructor,staticProps),Constructor}}();(function(){var ImagePicker,ImagePickerOption,both_array_are_equal,sanitized_options,indexOf=[].indexOf;jQuery.fn.extend({imagepicker:function(){var opts=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return this.each(function(){var select;if((select=jQuery(this)).data("picker")&&select.data("picker").destroy(),select.data("picker",new ImagePicker(this,sanitized_options(opts))),null!=opts.initialized)return opts.initialized.call(select.data("picker"))})}}),sanitized_options=function(opts){var default_options;return default_options={hide_select:!0,show_label:!1,initialized:void 0,changed:void 0,clicked:void 0,selected:void 0,limit:void 0,limit_reached:void 0,font_awesome:!1},jQuery.extend(default_options,opts)},both_array_are_equal=function(a,b){var i,j,len,x;if(!a||!b||a.length!==b.length)return!1;for(a=a.slice(0),b=b.slice(0),a.sort(),b.sort(),i=j=0,len=a.length;j<len;i=++j)if(x=a[i],b[i]!==x)return!1;return!0},ImagePicker=function(){function ImagePicker(select_element){var opts1=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};_classCallCheck(this,ImagePicker),this.sync_picker_with_select=this.sync_picker_with_select.bind(this),this.opts=opts1,this.select=jQuery(select_element),this.multiple="multiple"===this.select.attr("multiple"),null!=this.select.data("limit")&&(this.opts.limit=parseInt(this.select.data("limit"))),this.build_and_append_picker()}return _createClass(ImagePicker,[{key:"destroy",value:function(){var j,len,ref;for(j=0,len=(ref=this.picker_options).length;j<len;j++)ref[j].destroy();return this.picker.remove(),this.select.off("change",this.sync_picker_with_select),this.select.removeData("picker"),this.select.show()}},{key:"build_and_append_picker",value:function(){return this.opts.hide_select&&this.select.hide(),this.select.on("change",this.sync_picker_with_select),null!=this.picker&&this.picker.remove(),this.create_picker(),this.select.after(this.picker),this.sync_picker_with_select()}},{key:"sync_picker_with_select",value:function(){var j,len,option,ref,results;for(results=[],j=0,len=(ref=this.picker_options).length;j<len;j++)(option=ref[j]).is_selected()?results.push(option.mark_as_selected()):results.push(option.unmark_as_selected());return results}},{key:"create_picker",value:function(){return this.picker=jQuery("<ul class='thumbnails image_picker_selector'></ul>"),this.picker_options=[],this.recursively_parse_option_groups(this.select,this.picker),this.picker}},{key:"recursively_parse_option_groups",value:function(scoped_dom,target_container){var container,j,k,len,len1,option,option_group,ref,ref1,results;for(j=0,len=(ref=scoped_dom.children("optgroup")).length;j<len;j++)option_group=ref[j],option_group=jQuery(option_group),(container=jQuery("<ul></ul>")).append(jQuery("<li class='group_title'>"+option_group.attr("label")+"</li>")),target_container.append(jQuery("<li class='group'>").append(container)),this.recursively_parse_option_groups(option_group,container);for(ref1=function(){var l,len1,ref1,results1;for(results1=[],l=0,len1=(ref1=scoped_dom.children("option")).length;l<len1;l++)option=ref1[l],results1.push(new ImagePickerOption(option,this,this.opts));return results1}.call(this),results=[],k=0,len1=ref1.length;k<len1;k++)option=ref1[k],this.picker_options.push(option),option.has_image()&&results.push(target_container.append(option.node));return results}},{key:"has_implicit_blanks",value:function(){var option;return function(){var j,len,ref,results;for(results=[],j=0,len=(ref=this.picker_options).length;j<len;j++)(option=ref[j]).is_blank()&&!option.has_image()&&results.push(option);return results}.call(this).length>0}},{key:"selected_values",value:function(){return this.multiple?this.select.val()||[]:[this.select.val()]}},{key:"toggle",value:function(imagepicker_option,original_event){var new_values,old_values,selected_value;if(old_values=this.selected_values(),selected_value=imagepicker_option.value().toString(),this.multiple?indexOf.call(this.selected_values(),selected_value)>=0?((new_values=this.selected_values()).splice(jQuery.inArray(selected_value,old_values),1),this.select.val([]),this.select.val(new_values)):null!=this.opts.limit&&this.selected_values().length>=this.opts.limit?null!=this.opts.limit_reached&&this.opts.limit_reached.call(this.select):this.select.val(this.selected_values().concat(selected_value)):this.has_implicit_blanks()&&imagepicker_option.is_selected()?this.select.val(""):this.select.val(selected_value),!both_array_are_equal(old_values,this.selected_values())&&(this.select.change(),null!=this.opts.changed))return this.opts.changed.call(this.select,old_values,this.selected_values(),original_event)}}]),ImagePicker}(),ImagePickerOption=function(){function ImagePickerOption(option_element,picker){var opts1=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};_classCallCheck(this,ImagePickerOption),this.clicked=this.clicked.bind(this),this.picker=picker,this.opts=opts1,this.option=jQuery(option_element),this.create_node()}return _createClass(ImagePickerOption,[{key:"destroy",value:function(){return this.node.find(".thumbnail").off("click",this.clicked)}},{key:"has_image",value:function(){return null!=this.option.data("img-src")}},{key:"is_blank",value:function(){return!(null!=this.value()&&""!==this.value())}},{key:"is_selected",value:function(){var select_value;return select_value=this.picker.select.val(),this.picker.multiple?jQuery.inArray(this.value(),select_value)>=0:this.value()===select_value}},{key:"mark_as_selected",value:function(){return this.node.find(".thumbnail").addClass("selected")}},{key:"unmark_as_selected",value:function(){return this.node.find(".thumbnail").removeClass("selected")}},{key:"value",value:function(){return this.option.val()}},{key:"label",value:function(){return this.option.data("img-label")?this.option.data("img-label"):this.option.text()}},{key:"clicked",value:function(event){if(this.picker.toggle(this,event),null!=this.opts.clicked&&this.opts.clicked.call(this.picker.select,this,event),null!=this.opts.selected&&this.is_selected())return this.opts.selected.call(this.picker.select,this,event)}},{key:"create_node",value:function(){var image,imgAlt,imgClass,thumbnail;return this.node=jQuery("<li/>"),this.option.data("font_awesome")?(image=jQuery("<i>")).attr("class","fa-fw "+this.option.data("img-src")):(image=jQuery("<img class='image_picker_image'/>")).attr("src",this.option.data("img-src")),thumbnail=jQuery("<div class='thumbnail'>"),(imgClass=this.option.data("img-class"))&&(this.node.addClass(imgClass),image.addClass(imgClass),thumbnail.addClass(imgClass)),(imgAlt=this.option.data("img-alt"))&&image.attr("alt",imgAlt),thumbnail.on("click",this.clicked),thumbnail.append(image),this.opts.show_label&&thumbnail.append(jQuery("<p/>").html(this.label())),this.node.append(thumbnail),this.node}}]),ImagePickerOption}()}).call(void 0);

//Tinymce
!function(){var a={},b=function(b){for(var c=a[b],e=c.deps,f=c.defn,g=e.length,h=new Array(g),i=0;i<g;++i)h[i]=d(e[i]);var j=f.apply(null,h);if(void 0===j)throw"module ["+b+"] returned undefined";c.instance=j},c=function(b,c,d){if("string"!=typeof b)throw"module id must be a string";if(void 0===c)throw"no dependencies for "+b;if(void 0===d)throw"no definition function for "+b;a[b]={deps:c,defn:d,instance:void 0}},d=function(c){var d=a[c];if(void 0===d)throw"module ["+c+"] was undefined";return void 0===d.instance&&b(c),d.instance},e=function(a,b){for(var c=a.length,e=new Array(c),f=0;f<c;++f)e[f]=d(a[f]);b.apply(null,e)},f={};f.bolt={module:{api:{define:c,require:e,demand:d}}};var g=c,h=function(a,b){g(a,[],function(){return b})};h("1",document),h("2",window),g("0",["1","2"],function(a,b){return function(c){var d,e,f,g,h,i=[];h=c?c:b,g=h.jQuery;var j=function(){return h.tinymce};g.fn.tinymce=function(c){var d,l,m,n=this,o="";if(!n.length)return n;if(!c)return j()?j().get(n[0].id):null;n.css("visibility","hidden");var p=function(){var a=[],b=0;f||(k(),f=!0),n.each(function(d,e){var f,g=e.id,h=c.oninit;g||(e.id=g=j().DOM.uniqueId()),j().get(g)||(f=j().createEditor(g,c),a.push(f),f.on("init",function(){var c,d=h;n.css("visibility",""),h&&++b==a.length&&("string"==typeof d&&(c=d.indexOf(".")===-1?null:j().resolve(d.replace(/\.\w+$/,"")),d=j().resolve(d)),d.apply(c||j(),a))}))}),g.each(a,function(a,b){b.render()})};if(h.tinymce||e||!(d=c.script_url))1===e?i.push(p):p();else{e=1,l=d.substring(0,d.lastIndexOf("/")),d.indexOf(".min")!=-1&&(o=".min"),h.tinymce=h.tinyMCEPreInit||{base:l,suffix:o},d.indexOf("gzip")!=-1&&(m=c.language||"en",d=d+(/\?/.test(d)?"&":"?")+"js=true&core=true&suffix="+escape(o)+"&themes="+escape(c.theme||"modern")+"&plugins="+escape(c.plugins||"")+"&languages="+(m||""),h.tinyMCE_GZ||(h.tinyMCE_GZ={start:function(){var a=function(a){j().ScriptLoader.markDone(j().baseURI.toAbsolute(a))};a("langs/"+m+".js"),a("themes/"+c.theme+"/theme"+o+".js"),a("themes/"+c.theme+"/langs/"+m+".js"),g.each(c.plugins.split(","),function(b,c){c&&(a("plugins/"+c+"/plugin"+o+".js"),a("plugins/"+c+"/langs/"+m+".js"))})},end:function(){}}));var q=a.createElement("script");q.type="text/javascript",q.onload=q.onreadystatechange=function(a){a=a||b.event,2===e||"load"!=a.type&&!/complete|loaded/.test(q.readyState)||(j().dom.Event.domLoaded=1,e=2,c.script_loaded&&c.script_loaded(),p(),g.each(i,function(a,b){b()}))},q.src=d,a.body.appendChild(q)}return n},g.extend(g.expr[":"],{tinymce:function(a){var b;return!!(a.id&&"tinymce"in h&&(b=j().get(a.id),b&&b.editorManager===j()))}});var k=function(){var a=function(a){"remove"===a&&this.each(function(a,b){var d=c(b);d&&d.remove()}),this.find("span.mceEditor,div.mceEditor").each(function(a,b){var c=j().get(b.id.replace(/_parent$/,""));c&&c.remove()})},b=function(b){var c,d=this;if(null!=b)a.call(d),d.each(function(a,c){var d;(d=j().get(c.id))&&d.setContent(b)});else if(d.length>0&&(c=j().get(d[0].id)))return c.getContent()},c=function(a){var b=null;return a&&a.id&&h.tinymce&&(b=j().get(a.id)),b},e=function(a){return!!(a&&a.length&&h.tinymce&&a.is(":tinymce"))},f={};g.each(["text","html","val"],function(a,h){var i=f[h]=g.fn[h],j="text"===h;g.fn[h]=function(a){var f=this;if(!e(f))return i.apply(f,arguments);if(a!==d)return b.call(f.filter(":tinymce"),a),i.apply(f.not(":tinymce"),arguments),f;var h="",k=arguments;return(j?f:f.eq(0)).each(function(a,b){var d=c(b);h+=d?j?d.getContent().replace(/<(?:"[^"]*"|'[^']*'|[^'">])*>/g,""):d.getContent({save:!0}):i.apply(g(b),k)}),h}}),g.each(["append","prepend"],function(a,b){var h=f[b]=g.fn[b],i="prepend"===b;g.fn[b]=function(a){var b=this;return e(b)?a!==d?("string"==typeof a&&b.filter(":tinymce").each(function(b,d){var e=c(d);e&&e.setContent(i?a+e.getContent():e.getContent()+a)}),h.apply(b.not(":tinymce"),arguments),b):void 0:h.apply(b,arguments)}}),g.each(["remove","replaceWith","replaceAll","empty"],function(b,c){var d=f[c]=g.fn[c];g.fn[c]=function(){return a.call(this,c),d.apply(this,arguments)}}),f.attr=g.fn.attr,g.fn.attr=function(a,h){var i=this,j=arguments;if(!a||"value"!==a||!e(i))return h!==d?f.attr.apply(i,j):f.attr.apply(i,j);if(h!==d)return b.call(i.filter(":tinymce"),h),f.attr.apply(i.not(":tinymce"),j),i;var k=i[0],l=c(k);return l?l.getContent({save:!0}):f.attr.apply(g(k),j)}}}}),d("0")()}();

jQuery(document).ready(function( $ ) {
	$.jMaskGlobals.watchDataMask = true;

	const $preview_iframe = $('[data-piotnetforms-preview-iframe]');
	$preview_iframe.attr('src', $preview_iframe.attr('data-piotnetforms-preview-iframe'));

	const post_id = $('[data-piotnet-widget-post-id]').val();

	$preview_iframe.on('load', function() {
		const $iframe = $(this).contents();

		let $iframeHead = $(this).contents().find('head');
		let iframeCss = '<style>html { margin-top: 0 !important; } #wpadminbar { display : none; }</style>';
		$iframeHead.append(iframeCss);

		const ajaxurl = $('[data-piotnetforms-ajax-url]').attr('data-piotnetforms-ajax-url');

		const setting_generator = new SettingGenerator($);
		const css_generator = new CSSGenerator($);

		const ps = new PerfectScrollbar('.piotnetforms-settings');

		initialize_editor();

		function get_lib_keys_from_control(control, libs) {
			let keys = [];
			const key = control['options_source'];
			if (key) {
				keys.push(key);
				if (libs && libs[key]) {
					control.options = libs[key];
				}
			}
			if (control.controls) {
				$.each(control.controls, (control_index, sub_control) => {
					keys = keys.concat(get_lib_keys_from_control(sub_control, libs));
				})
			}
			// TODO remove duplicate keys

			return keys;
		}

		function get_lib_keys_from_widget_infos(widget_infos, libs) {
			let keys = [];
			$.each(widget_infos, (control_type, widget_info) => {
				const structure = widget_info['structure'];
				$.each(structure, (tab_name, tab) => {
					$.each(tab.sections, (section_name, section) => {
						keys = keys.concat(get_lib_keys_from_control(section, libs));
					})
				})
			})
			return keys;
		}

		async function get_libs(libs) {
			return new Promise((resolve, reject) => {
				const master_libs = {};
				const empty_libs = [];
				libs.forEach(key => {
					const rawValue = localStorage.getItem(key);
					const value = JSON.parse(rawValue);
					if (value) {
						master_libs[key] = value;
					} else if (empty_libs.indexOf(key) < 0) {
						empty_libs.push(key);
					}
				});

				if (empty_libs.length > 0) {
					const data = {
						action: 'piotnetforms_get_json_file',
						libs: empty_libs
					}
					$.post(ajaxurl, data, function (response) {
						response = JSON.parse(response);
						$.each(response, function (key, value) {
							localStorage.setItem(key, value);
							master_libs[key] = JSON.parse(value);
						})
						resolve(master_libs);
						// TODO handle call AJAX error.
					});
				} else {
					resolve(master_libs);
				}
			});
		}

		function conditionalControl( $parent, fields ) {
			const $conditions = $parent.find('[data-piotnet-control-conditions]');

			let triggerConditionalAgain = false;

			$conditions.each( function() {
				let fieldIfValue;
				const condition = JSON.parse($(this).attr('data-piotnet-control-conditions'));
				let error = 0;

				for (let i = 0; i < condition.length; i++) {
					const fieldIfName = condition[i].name;

					if ($(this).closest('[data-piotnet-control-repeater-item]').length > 0) {
						const $repeaterList = $(this).closest('[data-piotnet-control-repeater-list]'),
							repeaterId = $repeaterList.attr('data-piotnet-control-repeater-list'),
							repeaterItemIndex = $(this).closest('[data-piotnet-control-repeater-item]').index() - 1;

						if (!(repeaterId in fields)) {
							error += 1;
						} else {
							if (fields[repeaterId][repeaterItemIndex] !== undefined) {
								fieldIfValue = fields[repeaterId][repeaterItemIndex][fieldIfName];
							} else {
								error += 1;
							}
						}
					} else {
						fieldIfValue = fields[fieldIfName];
					}

					if (fieldIfValue === null || fieldIfValue === undefined) {
						fieldIfValue = '';
					}

					if (fieldIfValue !== null && fieldIfValue !== undefined && error === 0) {
						let  operator = condition[i].operator !== undefined ? condition[i].operator : '==';

						if (operator === '==' || operator === '=') {
							if (fieldIfValue === condition[i].value || fieldIfValue.indexOf(condition[i].value) > -1) {
							} else {
								error += 1;
							}
						}

						if (operator === '!=') {
							if (fieldIfValue !== condition[i].value || fieldIfValue.indexOf(condition[i].value) <= -1) {
							} else {
								error += 1;
							}
						}

						if (operator === '>') {
							if (parseFloat(fieldIfValue) > parseFloat(condition[i].value)) {
							} else {
								error += 1;
							}
						}

						if (operator === '>=') {
							if (parseFloat(fieldIfValue) >= parseFloat(condition[i].value)) {
							} else {
								error += 1;
							}
						}

						if (operator === '<') {
							if (parseFloat(fieldIfValue) < parseFloat(condition[i].value)) {
							} else {
								error += 1;
							}
						}

						if (operator === '<=') {
							if (parseFloat(fieldIfValue) <= parseFloat(condition[i].value)) {
							} else {
								error += 1;
							}
						}

						if (operator === 'in') {
							if (condition[i].value.indexOf(fieldIfValue) > -1) {
							} else {
								error += 1;
							}
						}

						if (operator === '!in') {
							if (condition[i].value.indexOf(fieldIfValue) <= -1) {
							} else {
								error += 1;
							}
						}
					}
				}

				if (error === 0) {
					if ($(this).hasClass('hidden')) {
						triggerConditionalAgain = true;
					}

					$(this).removeClass('hidden');
				} else {
					if (!$(this).hasClass('hidden')) {
						triggerConditionalAgain = true;
					}

					$(this).addClass('hidden');
				}
			});

			if (triggerConditionalAgain) {
				$parent.trigger('conditional-control-remove-hidden');
			}

		}

		function generateWidgetsSettings() {
			const tree_setting_widgets = setting_generator.generateWidgetsSettings(pb.get_setting_widgets());
			pb.set_tree_setting_widgets(tree_setting_widgets);
		}

		function fill_values(controls_widget, fields) {
			const tab_indexes = Object.keys(controls_widget);
			for (const tab_index in tab_indexes) {
				const tab = controls_widget[tab_index];
				const sections = tab['sections'];
				const section_indexes = Object.keys(sections);
				for (const section_index in section_indexes) {
					const section = sections[section_index];
					const controls = section['controls'];
					const control_indexes = Object.keys(controls);
					for (const control_index in control_indexes) {
						controls[control_index] = fill_values_control(controls[control_index], fields);
					}
				}
			}
			return controls_widget;
		}

		function fill_values_control(control, fields) {
			const name = control['name'];
			const field = fields[name];
			if (Array.isArray(field) && control.type === "repeater") {
				for (const repeater_item of field) {
					let new_control = fill_values_control(ObjectUtil.clone(control.controls[0]), repeater_item);
					control['controls'].push(new_control);
				}
			} else if (control.type === 'switch' && !field) {
				control['value'] = '';
			} else if (field) {
				control['value'] = field;
			}

			const controls = control['controls'];
			if (controls) {
				const control_indexes = Object.keys(controls);
				for (const control_index in control_indexes) {
					controls[control_index] = fill_values_control(controls[control_index], fields);
				}
			}
			return control;
		}

		function initialize_editor() {
			// console.time('load widget_infos');
			const t = $("#widget_infos").text();
			const widget_infos = JSON.parse(t);
			pb.set_widget_infos(widget_infos);
			// console.timeEnd('load widget_infos');

			get_libs(get_lib_keys_from_widget_infos(widget_infos)).then(libs => {
				pb.set_libs(libs);
				get_lib_keys_from_widget_infos(widget_infos, libs);
			}).then(() => {
				// console.time('load data');
				const raw_data = $('[data-piotnetforms-data]').val();
				const data = raw_data !== '' ? JSON.parse(raw_data) : {};

				const setting_widgets = data['widgets'];
				for (const widget_key in setting_widgets) {
					const widget = setting_widgets[widget_key];
					widget['fields'] = widget['settings'] // TODO remove
					delete widget['settings'];
				}

				pb.set_setting_widgets(setting_widgets ? setting_widgets : {});
				pb.set_tree_setting_widgets(data['content'] ? data['content'] : [])
				// console.timeEnd('load data');

				pb.set_breakpoint('tablet', $('[data-piotnet-widget-breakpoint-tablet]').val())
				pb.set_breakpoint('mobile', $('[data-piotnet-widget-breakpoint-mobile]').val())

				// console.time('load widget_structures');
				for (let widget_id in setting_widgets) {
					const setting = setting_widgets[widget_id];
					const fields = setting['fields'];

					const widget_type = setting['type'];
					const structure = pb.get_widget_info(widget_type)['structure'];
					if (structure) {
						const widget_structure = ObjectUtil.clone(structure);
						pb.set_widget_structure(widget_id, fill_values(widget_structure, fields));
					}
				}
				// console.timeEnd('load widget_structures');

				// template
				// console.time('load templates');
				const $els = $('[data-piotnetforms-template]');
				$els.each(function(){
					const template_id = $(this).attr("id");
					console.log($(this).html().replace('<!--', '').replace('-->', ''));
					const $template = _.template($(this).html().replace('<!--', '').replace('-->', ''));
					pb.set_template(template_id, $template);
				});
				// console.timeEnd('load templates');

				// render widgets
				const $head = $iframe.find('head').first();
				for (let widget_id in setting_widgets) {
					//const setting = setting_widgets[widget_id];
					// const $tab_template_el = render_control_widget(widget_id);
					// pb.set_control_widget(widget_id, $tab_template_el);
					//
					// conditionalControl( $tab_template_el, setting['fields'] );
					// const widgetSettings = setting_generator.generateSettings($tab_template_el);
					// console.log('widgetSettings', widgetSettings)
					// pb.set_setting_widget(widget_id, widgetSettings)
					// generateWidgetsSettings();

					const css = generate_css_from_settings(widget_id);
					pb.set_css_widget(widget_id, css);
					const style = '<style data-piotnet-widget-css-head="' + widget_id + '">' + css + '</style>';
					$head.append($(style));
				}

				// console.log(pb);

				$('[data-piotnetforms-editor-loading]').removeClass('active');
			});

		}

		function render_control_widget(widget_id) {
			const widget_structure = pb.get_widget_structure(widget_id);

			const tab_template = pb.get_template("piotnetforms-tab-widget-template");
			const $tab_template_el = $(tab_template({"data": {"widget_id": widget_id, "tabs": widget_structure}}));

			for(const tab_index in widget_structure) {
				const tab = widget_structure[tab_index];
				const sections = tab['sections'];
				sections.forEach(function (section) {
					const section_name = section['name'];
					const $body_el = $tab_template_el.find('[data-piotnet-controls-section="' + section_name + '"]').find('.piotnet-controls-section__body');
					const raw = render_controls(section.controls)
					$body_el.append(raw);
				});
			}
			return $tab_template_el;
		}

		function is_empty_string(value) {
			return !value || value.length === 0;
		}

		function is_control_empty_value(control_type, value) {
			switch (control_type) {
				case 'dimensions':
					return !value || (is_empty_string(value.top) && is_empty_string(value.right) && is_empty_string(value.bottom) && is_empty_string(value.left));
				case 'slider':
					return !value || is_empty_string(value.size);
				case 'media':
					return !value || is_empty_string(value.url);
				case 'box-shadow':
					return !value || (is_empty_string(value.horizontal) && is_empty_string(value.vertical));
				default:
					return is_empty_string(value);
			}
		}

		function fill_css_value(control_type, content, value) {
			switch (control_type) {
				case 'dimensions':
					content = StringUtil.replaceAll(content, '{{TOP}}', is_empty_string(value.top) ? '0' : value.top);
					content = StringUtil.replaceAll(content, '{{RIGHT}}', is_empty_string(value.right) ? '0' : value.right);
					content = StringUtil.replaceAll(content, '{{BOTTOM}}', is_empty_string(value.bottom) ? '0' : value.bottom);
					content = StringUtil.replaceAll(content, '{{LEFT}}', is_empty_string(value.left) ? '0' : value.left);
					content = StringUtil.replaceAll(content, '{{UNIT}}', value.unit);
					return content;
				case 'slider':
					content = StringUtil.replaceAll(content, '{{SIZE}}', value.size);
					content = StringUtil.replaceAll(content, '{{UNIT}}', value.unit);
					return content;
				case 'box-shadow':
					content = StringUtil.replaceAll(content, '{{SIZE}}', value.size);
					content = StringUtil.replaceAll(content, '{{UNIT}}', value.unit);
					const css_value = ( is_empty_string(value.horizontal) ? '0' : value.horizontal ) + 'px ' + ( is_empty_string(value.vertical) ? '0' : value.vertical ) + 'px ' + ( is_empty_string(value.blur) ? '0' : value.blur ) + 'px ' + ( is_empty_string(value.spread) ? '0' : value.spread ) + 'px ' + value.color;
					return StringUtil.replaceAll(content, '{{VALUE}}', css_value);
				case 'media':
					return StringUtil.replaceAll(content, '{{VALUE}}', value.url);
				default:
					return StringUtil.replaceAll(content, '{{VALUE}}', value);
			}
		}

		function generate_css_from_control(control, widget_id, control_value, repeater_id) {
			let css_arr = [];
			const control_type = control['type'];
			const responsive = control['responsive'];

			const selectors = control['selectors'];
			if (selectors && !is_control_empty_value(control_type, control_value)) {
				for (const selector_key in selectors) {
					const selector_value = selectors[selector_key];

					let wrapper = StringUtil.replaceAll(selector_key, '{{WRAPPER}}', '#piotnetforms .' + widget_id);
					if (repeater_id) {
						wrapper = StringUtil.replaceAll(wrapper, '{{CURRENT_ITEM}}', '.piotnetforms-repeater-item-' + repeater_id);
					}

					const content = fill_css_value(control_type, selector_value, control_value);
					let css = wrapper + `{` + content + `}`;
					if (responsive) {
						const breakpoint = pb.get_breakpoint(responsive)
						if (breakpoint) {
							css = `@media (max-width:${breakpoint}) {${css}}`
						}
					}
					css_arr.push(css);
				}
			}
			return css_arr;
		}

		function generate_css_from_controls(controls, widget_id, main_settings, repeater_data) {
			let css_arr = []
			const length = controls.length;
			for (let i = 0; i < length; ++i) {
				const control = controls[i];
				const control_name = control.name;
				const control_type = control.type;

				if (!check_conditions(control.conditions, repeater_data && repeater_data.settings ? repeater_data.settings[repeater_data.index] : main_settings)) {
					continue;
				}

				if (control['controls'] && control['controls_query']) {
					let sub_controls = control['controls'];

					if (control_type === 'repeater') {
						const repeater_settings = repeater_data && repeater_data.settings ? repeater_data.settings[control_name]: main_settings[control_name];
						if (repeater_settings && repeater_settings.length > 0) {
							for (let j = 0; j < repeater_settings.length; j++) {
								const sub_repeater_data = {
									'repeater_id': repeater_settings[j]['repeater_id'],
									'index': j,
									'settings': repeater_settings,
								}
								css_arr = css_arr.concat(generate_css_from_controls([control['controls'][0]], widget_id, main_settings, sub_repeater_data));
							}
						}
					} else {
						css_arr = css_arr.concat(generate_css_from_controls(sub_controls, widget_id, main_settings, repeater_data));
					}
				}

				const value = repeater_data && repeater_data.settings ? repeater_data.settings[repeater_data.index][control_name] : main_settings[control_name];
				const css = generate_css_from_control(control, widget_id, value, repeater_data ? repeater_data.repeater_id : null);
				css_arr = css_arr.concat(css);
			}
			return css_arr;
		}

		function compare(leftValue, rightValue, operator) {
			switch (operator) {
				case "==":
				case "=":
					return leftValue == rightValue;
				case "!=":
					return leftValue != rightValue;
				case '!==':
					return leftValue !== rightValue;
				case '>':
					return leftValue > rightValue;
				case '>=':
					return leftValue >= rightValue;
				case '<':
					return leftValue < rightValue;
				case '<=':
					return leftValue <= rightValue;
				case 'in':
					return -1 !== rightValue.indexOf( leftValue );
				case '!in':
					return -1 === rightValue.indexOf( leftValue );
				case 'contains':
					return -1 !== leftValue.indexOf( rightValue );
				case '!contains':
					return -1 === leftValue.indexOf( rightValue );
				default:
					return leftValue === rightValue;
			}
		}

		function check_conditions(conditions, settings) {
			if (!conditions || conditions.length == 0) {
				return true;
			}
			const length = conditions.length;
			for(let i = 0; i < length; i++) {
				const condition = conditions[i];
				const name = condition.name;
				let operator = condition.operator ? condition.operator : '==';
				const value = condition.value;
				const current_value = settings[name];

				if (current_value !== null && current_value !== undefined) {
					if (Array.isArray(current_value)) {
						if (operator === "=" || operator === "==" || operator === "===") {
							operator = 'in';
						} else if (operator === "!==" || operator === "!==") {
							operator = '!in';
						}
						return compare(value, current_value, operator);
					}
					return compare(current_value, value, operator);
				} else {
					return true;
				}
			}
		}

		function generate_css_from_settings(widget_id) {
			const widget_structure = pb.get_widget_structure(widget_id);
			if (!widget_structure) {
				return "";
			}
			let css_arr = []

			for(const tab_index in widget_structure) {
				const tab = widget_structure[tab_index];
				const sections = tab['sections'];
				sections.forEach(function (section) {
					const settings = pb.get_setting_widget(widget_id)['fields'];
					if (!check_conditions(section.conditions, settings)) {
						return;
					}
					css_arr = css_arr.concat(generate_css_from_controls(section.controls, widget_id, settings));
				});
			}
			return css_arr.join("");
		}

		function previewPlaceholder() {
			var $preview = $iframe.find('[data-piotnetforms-widget-preview]');
			if ( $preview.html().trim() == '' ) {
				$preview.addClass('placeholder');
			} else {
				$preview.removeClass('placeholder');
			}
		}

		previewPlaceholder();

		const debouncePreview = {};

		$(document).on('keyup change','[data-piotnetforms-settings-field]:not([data-piotnetforms-settings-not-field])', function(e, options){
			// console.log('keyup change');

			$('[data-piotnetforms-editor-save]').hide();

			// if (!$(this).hasClass('piotnet-select2')) {
			// 	$(this).attr('value', $(this).val());
			// } else {
			// 	// console.log($(this).val());
			// }

			if($(this).attr('data-piotnet-control-dimensions-group') !== undefined){
				const $dimensions = $(this).closest('[data-piotnet-control-dimensions-name]');
				const $isLinked = $dimensions.find('[data-piotnet-control-dimensions="isLinked"]');
				if ($isLinked.prop("checked") === true){
					const value = $(this).val();
					$dimensions.find('[data-piotnet-control-dimensions="top"]').val(value);
		    		$dimensions.find('[data-piotnet-control-dimensions="right"]').val(value);
		    		$dimensions.find('[data-piotnet-control-dimensions="bottom"]').val(value);
		    		$dimensions.find('[data-piotnet-control-dimensions="left"]').val(value);
		    	}
			}

			if($(this).attr('data-piotnet-control-dimensions-group') === 'isLinked'){
				const $dimensions = $(this).closest('[data-piotnet-control-dimensions-name]');
				const $isLinked = $dimensions.find('[data-piotnet-control-dimensions="isLinked"]');
				if ($isLinked.prop("checked") === true){
					const value = $dimensions.find('[data-piotnet-control-dimensions="top"]').val();
					$dimensions.find('[data-piotnet-control-dimensions="top"]').val(value);
		    		$dimensions.find('[data-piotnet-control-dimensions="right"]').val(value);
		    		$dimensions.find('[data-piotnet-control-dimensions="bottom"]').val(value);
		    		$dimensions.find('[data-piotnet-control-dimensions="left"]').val(value);
		    	}
			}

			const $widgetSettings = $(this).closest('[data-piotnetforms-widget-controls]');
			const widget_id = $widgetSettings.attr('data-piotnetforms-widget-controls');

			// Generate setting
			const widgetSettingsCurrent = pb.get_setting_widget(widget_id);
			const $fieldGroup = $(this).closest('[data-piotnet-control]');
			const widgetSettings = setting_generator.generateSettingsField($fieldGroup, widgetSettingsCurrent);
			pb.set_setting_widget(widget_id, widgetSettings);

			if (options && options.returnAtSetSettingWidget) {
				return;
			}

			generateWidgetsSettings();

			conditionalControl( $widgetSettings, widgetSettings['fields'] );

			const widget_type = widgetSettings.type;
			const widget_info = pb.get_widget_info(widget_type);

			// parse render type
			const render_type = $(this).attr('data-piotnet-widget-render-type');
			let closest_render_type = undefined;
			const $closest_render_type = $(this).closest('[data-piotnet-widget-render-type]');
			if ($closest_render_type.length > 0) {
				closest_render_type = $closest_render_type.attr('data-piotnet-widget-render-type');
			}
			const is_render_preview = (options && options.forceRenderPreview) || (!render_type && !closest_render_type) || render_type === 'both' || closest_render_type === 'both';
			const is_render_css = (options && options.forceRenderCSS) || render_type === 'none' || closest_render_type === 'none' || render_type === 'both' || closest_render_type === 'both';

			// render live preview
			if (is_render_preview) {
				let $widget = $iframe.find('[data-piotnet-editor-widgets-item-id="' + widget_id + '"]');

				if (widget_type === 'section' || widget_type === 'column') {
					const division_output_template = pb.get_template("piotnetforms-division-output-template");

					const division_type = "piotnet-" + widget_type;
					const class_container = widget_type === 'column' ? division_type + "__inner" : division_type + "__container";

					const widget_data = {
						'type': widget_info.type,
						'class_name': widget_info.class_name,
						'title': widget_info.title,
						'icon': widget_info.icon,
					}

					let data = {
						"data": {
							"type": widget_type,
							"widget_id": widget_id,
							"widget_settings": widgetSettings['fields'],
							"widget_info": widget_data,
							"division_type": division_type,
							"class_container": class_container
						}
					};
					const view = new View();

					const inner_container = $widget.find('.' + class_container).html();

					const division_output_template_html = get_division_output_template_html(division_output_template, data, view);
					$widget.replaceWith(division_output_template_html);
					$widget = $iframe.find('[data-piotnet-editor-widgets-item-id="' + widget_id + '"]');
					$widget.find('.' + class_container).append(inner_container);
					// setup_sortable();
					$('[data-piotnetforms-editor-save]').show();
					return;
				}

				const template = pb.get_template("piotnetforms-" + widgetSettings['type'] + "-live-preview-template");
				if (template) {
					$widget = $iframe.find('[data-piotnet-editor-widgets-item-id="' + widget_id + '"]').find('[data-piotnet-editor-widgets-item-root]');
					let data = {"data": {"widget_id": widget_id, "widget_settings": widgetSettings['fields']}};
					const live_preview_html = get_live_preview_html(template, data, new View());
					$widget.replaceWith(live_preview_html);
					$iframe.find('[data-piotnet-editor-widgets-item-id="' + widget_id + '"]').find('[data-piotnet-editor-widgets-item-root]').trigger('piotnet-widget-init-' + widget_info.class_name);
				} else {
					clearTimeout(debouncePreview[widget_id]);
					debouncePreview[widget_id] = setTimeout(function(){
						const widgetInformation = JSON.parse($widget.attr('data-piotnet-editor-widgets-item'));
						const data = {
							action: 'piotnetforms_widget_preview',
							function: 'widget_edit',
							widget_id: widget_id,
							widget_settings: widgetSettings['fields'],
							widget_information: widgetInformation,
						};

						$.post(ajaxurl, data, function(response) {
							response = JSON.parse( response );

							$widget.replaceWith(response.outputHTML);

							const $widgetNew = $iframe.find('[data-piotnet-editor-widgets-item-id=' + widget_id + ']');

							$widgetNew.addClass('active');

							$iframe.find('[data-piotnet-editor-widgets-item-id="' + widget_id + '"]').find('[data-piotnet-editor-widgets-item-root]').trigger('piotnet-widget-init-' + widget_info.class_name);
						});
					}, 500);
				}
			}

			if (is_render_css) { // render css
				// console.time("keyup change generateCss");
				const widgetCss = generate_css_from_settings(widget_id);
				// console.timeEnd("keyup change generateCss");

				pb.set_css_widget(widget_id, widgetCss);
				$iframe.find('[data-piotnet-widget-css-head="' + widget_id + '"]').html(widgetCss);
			}

			$('[data-piotnetforms-editor-save]').show();
		});

		$(document).on('conditional-control-remove-hidden','[data-piotnetforms-widget-controls]', $.throttle( 500, function(){
			const $widgetSettings = $(this),
				widgetSettings = setting_generator.generateSettings($widgetSettings);

			conditionalControl( $widgetSettings, widgetSettings['fields'] );

			const widget_id = $(this).attr('data-piotnetforms-widget-controls');
			const widgetCss =  generate_css_from_settings(widget_id);

			pb.set_css_widget(widget_id, widgetCss);
			$iframe.find('[data-piotnet-widget-css-head="' + widget_id + '"]').html(widgetCss);
			})
		);

		$('.piotnet-flatpickr').each(function(){
			const data = JSON.parse(this.getAttribute('data-piotnetforms-settings-field'));
			const data_date = data.picker_options;
			flatpickr(this, {
				altFormat: data_date.altFormat ? data_date.altFormat : "F j, Y",
				altInput: data_date.altInput ? data_date.altInput : false,
	    		altInputClass: data_date.altInput ? data_date.altInput : "",
	    		allowInput: data_date.altInput ? data_date.altInput : false,
	    		//appendTo: data_date.appendTo ? data_date.appendTo : null,
	    		ariaDateFormat: data_date.ariaDateFormat ? data_date.ariaDateFormat : "F j, Y",
	    		clickOpens: data_date.clickOpens ? data_date.clickOpens : true,
	    		dateFormat: data_date.dateFormat ? data_date.dateFormat : "Y-m-d",
	    		defaultDate: data_date.defaultDate ? data_date.defaultDate : null,
	    		defaultHour: data_date.defaultHour ? data_date.defaultHour : 12,
	    		defaultMinute: data_date.defaultMinute ? data_date.defaultMinute : 12,
	    		disable: data_date.disable ? data_date.disable : [],
	    		disableMobile: data_date.disableMobile ? data_date.disableMobile : false,
	    		enable: data_date.enable ? data_date.enable : [],
	    		enableTime: data_date.enableTime ? data_date.enableTime : false,
	    		enableSeconds: data_date.enableSeconds ? data_date.enableSeconds : false,
	    		//formatDate: data_date.formatDate ? data_date.formatDate : null,
	    		hourIncrement: data_date.hourIncrement ? data_date.hourIncrement : 1,
	    		//inline: data_date.inline ? data_date.inline : false,
	    		maxDate: data_date.maxDate ? data_date.maxDate : null,
	    		minDate: data_date.minDate ? data_date.minDate : null,
	    		minuteIncrement: data_date.minuteIncrement ? data_date.minuteIncrement : 5,
	    		mode: data_date.mode ? data_date.mode : "single",
	    		nextArrow: data_date.mode ? data_date.mode : ">",
	    		noCalendar: data_date.noCalendar ? data_date.noCalendar : false,
	    		onChange: data_date.onChange ? data_date.onChange : null,
	    		onClose: data_date.onClose ? data_date.onClose : null,
	    		onOpen: data_date.onOpen ? data_date.onOpen : null,
	    		onReady: data_date.onReady ? data_date.onReady : null,
	    		parseDate: data_date.parseDate ? data_date.parseDate : false,
	    		position: data_date.position ? data_date.position : "auto",
	    		prevArrow: data_date.prevArrow ? data_date.prevArrow : "<",
	    		shorthandCurrentMonth: data_date.shorthandCurrentMonth ? data_date.shorthandCurrentMonth : false,
	    		showMonths: data_date.prevArrow ? data_date.prevArrow : 1,
	    		static: data_date.static ? data_date.static : false,
	    		time_24hr: data_date.time_24hr ? data_date.time_24hr : false,
	    		weekNumbers: data_date.weekNumbers ? data_date.weekNumbers : false,
	    		wrap: data_date.wrap ? data_date.wrap : false,
			});
		});
		$('.piotnet-select2').select2();
		$('.piotnet-pick-color').each( function() {
	        $(this).minicolors({
	          control: $(this).attr('data-control') || 'hue',
	          defaultValue: $(this).attr('data-defaultValue') || '',
	          format: $(this).attr('data-format') || 'hex',
	          keywords: $(this).attr('data-keywords') || '',
	          inline: $(this).attr('data-inline') === 'true',
	          letterCase: $(this).attr('data-letterCase') || 'lowercase',
	          opacity: $(this).attr('data-opacity'),
	          position: $(this).attr('data-position') || 'bottom',
	          swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
	          change: function(hex, opacity) {
	            var log;
	            try {
	              log = hex ? hex : 'transparent';
	              if( opacity ) log += ', ' + opacity;
	            } catch(e) {}
	          },
	          theme: 'default'
	        });

		});

		$(document).on('click', '[data-piotnet-control-responsive]', function(){
			const responsive = $(this).attr('data-piotnet-control-responsive');

			pb.set_responsive(responsive);

			$('[data-piotnet-responsive]').hide();
			$('[data-piotnet-control-responsive]').removeClass('active');
			$('[data-piotnet-responsive="'+responsive+'"]').show();
			$('[data-piotnet-control-responsive="'+responsive+'"]').addClass('active');

			if (responsive == 'mobile') {
				$('[data-piotnetforms-preview-inner]').css({'width': '360px', 'flex': 'none'});
			} else if (responsive == 'tablet') {
				$('[data-piotnetforms-preview-inner]').css({'width': '768px', 'flex': 'none'});
			} else if (responsive == 'desktop') {
				$('[data-piotnetforms-preview-inner]').css({'width': '100%', 'flex': '1 0 auto'});
			} else {
				$('[data-piotnetforms-preview-inner]').css({'width': $('[data-piotnet-widget-breakpoint-' + responsive + ']').val(), 'flex': 'none'});
			}
		});

		$(document).on('click', '[data-piotnet-control-size-unit]', function(){
			const unit = $(this).attr('data-piotnet-control-size-unit');
			const $units = $(this).closest('[data-piotnet-control]').find('[data-piotnet-control-size-unit]');
			$units.removeClass('active');
			$(this).addClass('active');
			const $unitField = $(this).closest('[data-piotnet-control]').find('[data-piotnet-control-unit]');
			$unitField.val(unit).change();

			const $unitSlider = $(this).closest('[data-piotnet-control]').find('[data-piotnet-control-slider-unit]');
			if ($unitSlider.length > 0) {
				$unitSlider.removeClass('active');
				$(this).closest('[data-piotnet-control]').find('[data-piotnet-control-slider-unit="' + unit + '"]').addClass('active');
			}
		});

		$('[data-piotnet-control-slider-options]').each(function(){
			const options = JSON.parse($(this).attr('data-piotnet-control-slider-options'));
			options.skin = 'round';
			$(this).ionRangeSlider(options);
		});

		// Slider 2

		$(document).on('input', 'input.piotnet-range-slider2_range-input', function(){
			const $parent = $(this).closest('.piotnet-range-slider2');
			$parent.find('.piotnet-range-slider2__preview_value').html($(this).val());
			$parent.find('.piotnet-range-slider2__input-value').val($(this).val());
	    });

	    $(document).on('input', 'input.piotnet-range-slider2__input-value', function(){
			const $parent = $(this).closest('.piotnet-range-slider2');
			$parent.find('input.piotnet-range-slider2_range-input').val($(this).val());
	    });

		$(document).on('click', '[data-piotnet-control-media-upload]', function(e){

			e.preventDefault();

			const $button = $(this),
				$media = $(this).closest('[data-piotnet-control-media-wrapper]'),
				$removeButton = $media.find('[data-piotnet-control-media-remove]'),
				$imageId = $media.find('[data-piotnet-control-media="id"]'),
				$imageUrl = $media.find('[data-piotnet-control-media="url"]');

			const custom_uploader = wp.media({
				title: 'Insert image',
				library: {
					// uncomment the next line if you want to attach image to the current post
					// uploadedTo : wp.media.view.settings.post.id,
					type: 'image'
				},
				button: {
					text: 'Use this image' // button label text
				},
				multiple: false // for multiple image selection set to true
			}).on('select', function () { // it also has "open" and "close" events
				const attachment = custom_uploader.state().get('selection').first().toJSON();
				$button.removeClass('button').html('<img src="' + attachment.url + '" style="display:block;" />');
				$imageId.val(attachment.id).change();
				$imageUrl.val(attachment.url).change();
				$removeButton.show();

				/* if you sen multiple to true, here is some code for getting the image IDs
	            var attachments = frame.state().get('selection'),
	                attachment_ids = new Array(),
	                i = 0;
	            attachments.each(function(attachment) {
	                 attachment_ids[i] = attachment['id'];
	                console.log( attachment );
	                i++;
	            });
	            */
			})
				.open();
		});

		$(document).on('click', '[data-piotnet-control-media-remove]', function(){
			const $removeButton = $(this),
				$media = $(this).closest('[data-piotnet-control-media-wrapper]'),
				$button = $media.find('[data-piotnet-control-media-upload]'),
				$imageId = $media.find('[data-piotnet-control-media="id"]'),
				$imageUrl = $media.find('[data-piotnet-control-media="url"]');

			$removeButton.hide();
			$imageId.val('').change();
			$imageUrl.val('').change();
			$button.addClass('button').html('Upload image');

			return false;
		});

		// Gallery

		let file_frame;

		$(document).on('click', '[data-piotnet-control-gallery-upload]', function(e) {

			e.preventDefault();

			let $gallery = $(this).closest('[data-piotnet-control-gallery-wrapper]').find('[data-piotnet-control-gallery-list]');

			if (file_frame) file_frame.close();

			file_frame = wp.media.frames.file_frame = wp.media({
				title: $(this).data('uploader-title'),
				button: {
					text: $(this).data('uploader-button-text'),
				},
				multiple: true
			});

			file_frame.on('select', function() {
				const selection = file_frame.state().get('selection');

				selection.map(function(attachment, i) {
					attachment = attachment.toJSON();

					$gallery.append('<div data-piotnet-control-gallery-item><input type="hidden" data-piotnet-control-gallery="id" data-piotnetforms-settings-field value="' + attachment.id + '" /><input type="hidden" data-piotnet-control-gallery="url" data-piotnetforms-settings-field value="' + attachment.url + '" /><img data-piotnet-control-gallery="preview" src="' + attachment.sizes.thumbnail.url + '"><a data-piotnet-control-gallery-change-image class="change-image button button-small" href="#"  data-uploader-title="Change image" data-uploader-button-text="Change image">Change image</a><small><a data-piotnet-control-gallery-remove class="remove-image" href="#">Remove image</a></small></div>');
					$gallery.find('[data-piotnet-control-gallery]').change();
				});

			});

			$gallery = $(this).closest('[data-piotnet-control-gallery-wrapper]').find('[data-piotnet-control-gallery-list]');

			makeSortable($gallery);

			file_frame.open();

		});

		$(document).on('click', '[data-piotnet-control-gallery-change-image]', function(e) {

			e.preventDefault();

			const $galleryItem = $(this).closest('[data-piotnet-control-gallery-item]');

			if (file_frame) file_frame.close();

			file_frame = wp.media.frames.file_frame = wp.media({
				title: $(this).data('uploader-title'),
				button: {
					text: $(this).data('uploader-button-text'),
				},
				multiple: false
			});

			file_frame.on( 'select', function() {
				const attachment = file_frame.state().get('selection').first().toJSON();

				$galleryItem.find('[data-piotnet-control-gallery="id"]').val(attachment.id).change();
				$galleryItem.find('[data-piotnet-control-gallery="url"]').val(attachment.url).change();
				$galleryItem.find('[data-piotnet-control-gallery="preview"]').attr('src', attachment.sizes.thumbnail.url);
			});

			file_frame.open();

		});


		function makeSortable($gallery) {
			$gallery.sortable({
				opacity: 0.6,
				stop: function() {
					// getSettings();
				}
			});
		}

		$(document).on('click', '[data-piotnet-control-gallery-remove]', function(e) {
			e.preventDefault();

			const $gallery = $(this).closest('[data-piotnet-control-gallery-wrapper]').find('[data-piotnet-control-gallery-list]');

			$(this).closest('[data-piotnet-control-gallery-item]').animate({ opacity: 0 }, 200, function() {
				$(this).remove();
				$gallery.find('[data-piotnet-control-gallery]').change();
			});
		});

		makeSortable($('[data-piotnet-control-gallery-list]'));

		$(document).on('click', '[data-piotnet-control-icon]', function(e) {
			const icon = $(this).attr('data-piotnet-control-icon');
			const $parent = $(this).closest('[data-piotnet-control]');
			$parent.find('[data-piotnetforms-settings-field]').val(icon).change();
		});

		$(document).on('click', '[data-piotnet-select-icon]', function(e) {
			const $parent = $(this).closest('[data-piotnet-control]');
			const $modal = $parent.find('[data-piotnet-modal]');

			$modal.show();
		});

		$(document).on('mousedown touchstart',function (e) {
			if (!$('.piotnet-modal-content').is(e.target) && !$('.piotnet-modal-content *').is(e.target)) {
				$(document).find('[data-piotnet-modal]').hide();
			}

			if (!$('.piotnet-tooltip__body').is(e.target) && !$('.piotnet-tooltip__body *').is(e.target) && !$('.piotnet-tooltip__label').is(e.target)&& !$('.piotnet-tooltip__label *').is(e.target)) {
				$(document).find('[data-piotnet-tooltip]').removeClass('active');
			}

			if (!$('[data-piotnet-editor-widgets-item]').is(e.target) && !$('.piotnetforms-settings').is(e.target) && !$('.piotnetforms-settings *').is(e.target)) {
				$(document).find('[data-piotnet-editor-widgets-item]').removeClass('active');
			}
		});

		$(document).on('click','[data-piotnet-modal-close]',function (e) {
			$(document).find('[data-piotnet-modal]').hide();
		});

		$(document).on('click', '[data-piotnet-tooltip-label]', function(e) {
			const $parent = $(this).closest('[data-piotnet-tooltip]');
			$(document).find('[data-piotnet-tooltip]').not($parent).removeClass('active');
			$parent.toggleClass('active');
		});

		function repeaterSortable($repeaterList) {
			$repeaterList.sortable({
				opacity: 0.6,
				handle: '[data-piotnet-repeater-heading]',
				update: function(event, ui) {
					const start_index = ui.item.data['startIndex'] - 1;
					const end_index = ui.item.index() - 1;

					const widget_id = $(this).closest('[data-piotnetforms-widget-controls]').attr('data-piotnetforms-widget-controls');
					const repeater_name = $(this).closest('[data-piotnet-control-repeater-list]').attr('data-piotnet-control-repeater-list');

					const settings = pb.get_setting_widget(widget_id)['fields'];
					const repeater_setting = settings[repeater_name];

					repeater_setting.splice(end_index, 0, repeater_setting.splice(start_index, 1)[0]);

					const options = {
						'forceRenderPreview': true,
					};
					$($repeaterList.find('[data-piotnetforms-settings-field]:not([data-piotnetforms-settings-not-field])')[0]).trigger('change', options);
				},
				start: function(event, ui) {
					ui.item.data['startIndex'] = ui.item.index();
				}
			});
		}

		function reInitFieldsControls($parent) {

			const $slider = $parent.find('[data-piotnet-control-slider-options]');

			$slider.each(function(){
				$(this).closest('[data-piotnet-control-slider-wrapper]').find('.irs').remove();
				const options = JSON.parse($(this).attr('data-piotnet-control-slider-options'));
				options.skin = 'round';

				if ($(this).attr('value') !== '') {
					options.from = $(this).attr('value');
				}

				$(this).ionRangeSlider(options);
			});

			const $datePicker = $parent.find('.piotnet-flatpickr');

			$datePicker.each(function(){
				const data = JSON.parse(this.getAttribute('data-piotnetforms-settings-field'));
				const data_date = data.picker_options;
				flatpickr(this, {
					altFormat: data_date.altFormat ? data_date.altFormat : "F j, Y",
					altInput: data_date.altInput ? data_date.altInput : false,
		    		altInputClass: data_date.altInput ? data_date.altInput : "",
		    		allowInput: data_date.altInput ? data_date.altInput : false,
		    		//appendTo: data_date.appendTo ? data_date.appendTo : null,
		    		ariaDateFormat: data_date.ariaDateFormat ? data_date.ariaDateFormat : "F j, Y",
		    		clickOpens: data_date.clickOpens ? data_date.clickOpens : true,
		    		dateFormat: data_date.dateFormat ? data_date.dateFormat : "Y-m-d",
		    		defaultDate: data_date.defaultDate ? data_date.defaultDate : null,
		    		defaultHour: data_date.defaultHour ? data_date.defaultHour : 12,
		    		defaultMinute: data_date.defaultMinute ? data_date.defaultMinute : 12,
		    		disable: data_date.disable ? data_date.disable : [],
		    		disableMobile: data_date.disableMobile ? data_date.disableMobile : false,
		    		enable: data_date.enable ? data_date.enable : [],
		    		enableTime: data_date.enableTime ? data_date.enableTime : false,
		    		enableSeconds: data_date.enableSeconds ? data_date.enableSeconds : false,
		    		//formatDate: data_date.formatDate ? data_date.formatDate : null,
		    		hourIncrement: data_date.hourIncrement ? data_date.hourIncrement : 1,
		    		//inline: data_date.inline ? data_date.inline : false,
		    		maxDate: data_date.maxDate ? data_date.maxDate : null,
		    		minDate: data_date.minDate ? data_date.minDate : null,
		    		minuteIncrement: data_date.minuteIncrement ? data_date.minuteIncrement : 5,
		    		mode: data_date.mode ? data_date.mode : "single",
		    		nextArrow: data_date.mode ? data_date.mode : ">",
		    		noCalendar: data_date.noCalendar ? data_date.noCalendar : false,
		    		onChange: data_date.onChange ? data_date.onChange : null,
		    		onClose: data_date.onClose ? data_date.onClose : null,
		    		onOpen: data_date.onOpen ? data_date.onOpen : null,
		    		onReady: data_date.onReady ? data_date.onReady : null,
		    		parseDate: data_date.parseDate ? data_date.parseDate : false,
		    		position: data_date.position ? data_date.position : "auto",
		    		prevArrow: data_date.prevArrow ? data_date.prevArrow : "<",
		    		shorthandCurrentMonth: data_date.shorthandCurrentMonth ? data_date.shorthandCurrentMonth : false,
		    		showMonths: data_date.prevArrow ? data_date.prevArrow : 1,
		    		static: data_date.static ? data_date.static : false,
		    		time_24hr: data_date.time_24hr ? data_date.time_24hr : false,
		    		weekNumbers: data_date.weekNumbers ? data_date.weekNumbers : false,
		    		wrap: data_date.wrap ? data_date.wrap : false,
				});
			});

			const $selectTwo = $parent.find('.piotnet-select2');

			$selectTwo.each(function(){
				$(this).select2();
				$(this).closest('[data-piotnet-control]').find('.select2').remove();
				$(this).select2('destroy');
				$(this).select2();
			});

			const $pickColor = $parent.find('.piotnet-pick-color');

			$pickColor.each( function() {
				const $piotnetControl = $(this).closest('.piotnet-control__field-group');
				const inputHTML = $(this)[0].outerHTML;

				$(this).closest('.piotnet-control__field').remove();
				$piotnetControl.append('<div class="piotnet-control__field">' + inputHTML + '</div>');

		        $piotnetControl.find('.piotnet-pick-color').minicolors({
		          control: $(this).attr('data-control') || 'hue',
		          defaultValue: $(this).attr('data-defaultValue') || '',
		          format: $(this).attr('data-format') || 'hex',
		          keywords: $(this).attr('data-keywords') || '',
		          inline: $(this).attr('data-inline') === 'true',
		          letterCase: $(this).attr('data-letterCase') || 'lowercase',
		          opacity: $(this).attr('data-opacity'),
		          position: $(this).attr('data-position') || 'bottom',
		          swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
		          change: function(hex, opacity) {
					  let log;
					  try {
		              log = hex ? hex : 'transparent';
		              if( opacity ) log += ', ' + opacity;
		            } catch(e) {}
		          },
		          theme: 'default'
		        });

			});
		}

		$(document).on('click', '[data-piotnet-control-repeater-add-item]', function(e) {
			const $repeater = $(this).closest('[data-piotnet-control-repeater]'),
				$repeaterList = $repeater.find('[data-piotnet-control-repeater-list]'),
				$repeaterItemBase = $repeater.find('[data-piotnet-control-repeater-item]');

			$repeaterList.eq(0).append($repeaterItemBase[0].outerHTML);
			repeaterSortable($repeaterList);

			const $repeaterItemNew = $repeater.find('[data-piotnet-control-repeater-item]:last-child');
			reInitFieldsControls($repeaterItemNew);

			const repeaterIdObject = new IDGenerator(),
				repeaterId = repeaterIdObject.generate();

			$repeaterItemNew.find('[data-piotnetforms-settings-field-css]').each(function(){
				let fieldCss = $(this).attr('data-piotnetforms-settings-field-css');
				fieldCss = StringUtil.replaceAll(fieldCss, '{{CURRENT_ITEM}}', '.piotnetforms-repeater-item-' + repeaterId);
				$(this).attr('data-piotnetforms-settings-field-css', fieldCss);
			});

			$repeaterItemNew.find('[data-piotnetforms-settings-field]:not([data-piotnetforms-settings-not-field])').trigger('change', {
				'returnAtSetSettingWidget': true
			});
			$repeaterItemNew.find('[name="repeater_id"]').val( repeaterId ).trigger('change', {
				'forceRenderPreview': true,
				'forceRenderCSS': true,
			});

			//heading Active
			$repeaterList.find('[data-piotnet-repeater-field]').removeClass('active');
			$repeaterList.find('[data-piotnet-repeater-field]').last().addClass('active');
		});

		$(document).on('click', '[data-piotnet-control-repeater-remove-item]', function(e) {
			const $repeater = $(this).closest('[data-piotnet-control-repeater]'),
				$repeater_item = $(this).closest('[data-piotnet-control-repeater-item]'),
				$repeater_items = $(this).parents('[data-piotnet-control-repeater-item]');

			if ($repeater_item.css('display') !== 'none') {
				let levels = [];
				$repeater_items.each(function(){
					if ($(this).css('display') !== 'none') {
						levels.push($(this).index() - 1);
						const $repeater_list = $(this).closest('[data-piotnet-control-repeater-list]');
						levels.push($repeater_list.attr('data-piotnet-control-repeater-list'));
					}
				});

				if (levels.length > 0) {
					levels = levels.reverse()
					const widget_id = $(this).closest('[data-piotnetforms-widget-controls]').attr('data-piotnetforms-widget-controls');
					const settings = pb.get_setting_widget(widget_id)['fields'];
					setting_generator.removeRepeaterItem(settings, levels);
					setting_generator.generateWidgetsSettings(pb.get_setting_widgets());
				}
			}

			$repeater_item.remove();
			repeaterSortable($repeater.find('[data-piotnet-control-repeater-list]'));
			$($repeater.find('[name="repeater_id"]')[0]).trigger('change', {
				'forceRenderPreview': true,
				'forceRenderCSS': true,
			});
		});

		$(document).on('click', '[data-piotnet-controls-section-header]', function(e) {
			const $parent = $(this).closest('[data-piotnet-controls-section]');
			$(this).closest('[data-piotnet-tabs-content]').find('[data-piotnet-controls-section]').not($parent).removeClass('active');
			$parent.toggleClass('active');

			if ($('.piotnetforms-settings').length > 0) {
				ps.update();
			}
		});

		$('.data-piotnetforms-settings-field').attr('data-piotnetforms-settings-field','');


		function initWidgetSettingsSection( $parent ) {
			$parent.find('[data-piotnet-controls-section]:first-child').addClass('active');
			const tabActive = $parent.find('[data-piotnet-tabs-item].active').attr('data-piotnet-tabs-item');
			const $tabActiveContent = $parent.find('[data-piotnet-tabs-content="' + tabActive + '"]');
			$parent.find('[data-piotnet-tabs-content]').not($tabActiveContent).removeClass('active');
			$tabActiveContent.addClass('active');

			$parent.find('.piotnet-start-controls-tabs').each(function(){
		        $(this).find('[data-piotnet-tab-heading]').eq(0).addClass('active');
				const contentActive = $(this).find('[data-piotnet-tab-heading]').eq(0).attr('data-piotnet-tab-heading');
				$parent.find('[data-piotnet-tab-content='+contentActive+']').addClass('active');
		    });
		}

		initWidgetSettingsSection( $iframe );

		$(document).on('click', '[data-piotnet-tabs-item]', function(e) {
			$(this).closest('[data-piotnet-tabs]').find('[data-piotnet-tabs-item]').not(this).removeClass('active');
			$(this).addClass('active');
			$(this).closest('[data-piotnetforms-widget-controls]').find('[data-piotnet-tabs-content]').removeClass('active');
			const tabActive = $(this).attr('data-piotnet-tabs-item');
			const $tabActiveContent = $(this).closest('[data-piotnetforms-widget-controls]').find('[data-piotnet-tabs-content="' + tabActive + '"]');
			$tabActiveContent.addClass('active');

			if ($('.piotnetforms-settings').length > 0) {
				ps.update();
			}
		});

		function IDGenerator() {
			this.length = 8;
			this.timestamp = +new Date;

			const _getRandomInt = function (min, max) {
				return Math.floor(Math.random() * (max - min + 1)) + min;
			};

			this.generate = function() {
				const ts = this.timestamp.toString();
				const parts = ts.split("").reverse();
				let id = "";

				for(let i = 0; i < this.length; ++i ) {
					const index = _getRandomInt(0, parts.length - 1);
					id += parts[index];
				}

				return 'p' + id;
			}
		}

		function render_controls(controls) {
			const length = controls.length;
			const control_htmls = [];
			for (let i = 0; i < length; ++i) {
				const control = controls[i];
				const template = pb.get_template("piotnetforms-" + control.type + "-control-template");
				const data = _.extend({"data": control}, {data_type_html: data_type_html});
				let raw_html = template(data);

				if (control['controls'] && control['controls_query']) {
					const inner_html = render_controls(control['controls']);
					const $sub_el = $(raw_html);
					$sub_el.find(control['controls_query']).append(inner_html);
					raw_html = $sub_el[0].outerHTML;
				}

				control_htmls.push(raw_html);
			}
			return control_htmls.join('');
		}

		function data_type_html(args) {
			const attributes = ["data-piotnetforms-settings-field"];

			if (args) {
				const selectors = args['selectors'];
				if (selectors) {
					for (const key in selectors) {
						const value = selectors[key];
						selectors[ key.replace('"', '\"') ] = value.replace('"', '\"');
					}

					attributes.push("data-piotnetforms-settings-field-css='" + JSON.stringify(selectors) + "'");
				}

				let render_type = args['render_type'];
				if (!render_type && selectors) {
					render_type = 'none';
				}
				if (render_type) {
					attributes.push(`data-piotnet-widget-render-type="${render_type}"`);
				}

				const responsive = args['responsive'];
				if (responsive) {
					attributes.push("data-piotnet-widget-responsive-" + responsive);
				}

				const type = args['type'];
				if (type === 'date') {
					let picker_options = {picker_options:args['picker_options']};
					attributes[0] = "data-piotnetforms-settings-field='" + JSON.stringify(picker_options) + "'";
				}
			}

			return attributes.join(" ");
		}

		function get_live_preview_html(template, data, view) {
			const widget_id = data.data.widget_id;
			const widget_settings = data.data.widget_settings;

			view.add_attribute('wrapper', 'data-piotnet-editor-widgets-item-root', '');
			view.add_attribute('wrapper', 'class', widget_id);
			if (widget_settings.advanced_custom_classes) {
				view.add_attribute('wrapper', 'class', widget_settings.advanced_custom_classes);
			}
			if (widget_settings.advanced_custom_id) {
				view.add_attribute('wrapper', 'id', widget_settings.advanced_custom_id);
			}

			data = _.extend(data, {view: view});
			return template(data)
		}

		function get_division_output_template_html(template, data, view) {
			const d = data.data;
			const widget_id = d.widget_id;
			const widget_settings = d.widget_settings;
			const widget_type = d.type;
			const division_type = d.division_type;

			view.add_attribute('widget_wrapper_editor', 'class', 'piotnet-widget');
			view.add_attribute('widget_wrapper_editor', 'data-piotnet-editor-widgets-item', JSON.stringify( d.widget_info ));
			view.add_attribute('widget_wrapper_editor', 'data-piotnet-editor-widgets-item-id', d.widget_id);
			view.add_attribute('widget_wrapper_editor', 'draggable', 'true');

			if (widget_type === 'section') {
				view.add_attribute('widget_wrapper_editor', 'data-piotnet-editor-widgets-item-section', '');
				view.add_attribute('widget_wrapper_editor', 'data-piotnet-editor-section', '');
				view.add_attribute('widget_wrapper_editor', 'class', 'active');
			}

			if ( widget_type === 'column' ) {
				view.add_attribute('widget_wrapper_editor', 'data-piotnet-editor-column', '');
			}

			view.add_attribute('widget_wrapper_editor', 'class', division_type);

			view.add_attribute('widget_wrapper_editor', 'class', widget_id);

			if (widget_settings.advanced_custom_classes) {
				view.add_attribute('widget_wrapper_editor', 'class', widget_settings.advanced_custom_classes);
			}
			if (widget_settings.advanced_custom_id) {
				view.add_attribute('widget_wrapper_editor', 'id', widget_settings.advanced_custom_id);
			}

			view.add_attribute('widget_wrapper_container', 'class', d.class_container);
			if (widget_settings.section_content_width_type_responsive_desktop === 'full-width') {
				view.add_attribute('widget_wrapper_container', 'piotnet-section__container--full-width', '');
			}
			if ( widget_type === 'column' ) {
				view.add_attribute('widget_wrapper_container', 'data-piotnet-sortable', '');
			}

			data = _.extend(data, {view: view});
			return template(data)
		}

		function get_column_output_template_html(output_template, column_id) {
			const widget_type = 'column';
			const division_type = "piotnet-" + widget_type;
			const class_container = widget_type === 'column' ? division_type + "__inner" : division_type + "__container";

			const widget_info = pb.get_widget_info('column');

			const widget_data = {
				'type': widget_info.type,
				'class_name': widget_info.class_name,
				'title': widget_info.title,
				'icon': widget_info.icon,
			}

			let data = {
				"data": {
					"type": widget_type,
					"widget_id": column_id,
					"widget_settings": {},
					"widget_info": widget_data,
					"division_type": division_type,
					"class_container": class_container
				}
			};
			const view = new View();
			return get_division_output_template_html(output_template, data, view)
		}

		function getDragAfterElement(container, y) {
			const draggableElements = [...container.find('[data-piotnet-editor-widgets-item-id]:not(.dragging)')]

			return draggableElements.reduce((closest, child) => {
				const box = child.getBoundingClientRect()
				const offset = y - box.top - box.height / 2
				if (offset < 0 && offset > closest.offset) {
					return { offset: offset, element: child }
				} else {
					return closest
				}
			}, { offset: Number.NEGATIVE_INFINITY }).element
		}

		const placeholder_drop = document.createElement("div");
		placeholder_drop.classList.add('piotnetforms-draggable-placeholder');

		function setup_draggable() {
			// drag from editor
			const selector = $('[data-piotnetforms-editor-widgets-item]');
			selector.on('dragstart', function(e) {
				e.target.classList.add('dragging');
			});
			selector.on('dragend', function(e) {
				e.target.classList.remove('dragging');
				remove_placeholder();
			});

			// drag from iframe
			$iframe.on('dragstart', function (e) {
				if ( e.target.getAttribute('data-piotnet-editor-widgets-item') ) {
					e.target.classList.add('dragging-preview');
				}
			})
			$iframe.on('dragend', function (e) {
				e.target.classList.remove('dragging-preview');
				remove_placeholder();
			})
		}

		function remove_placeholder() {
			let $placeholder_remove = $iframe.find('.piotnetforms-draggable-placeholder')
			if ($placeholder_remove.length > 0) {
				$placeholder_remove.remove();
			}
		}

		setup_draggable();

		function setup_sortable() {
			let $containersIframe = $iframe.find('[data-piotnet-sortable]');

			$iframe.on('dragover', '[data-piotnet-sortable]', function (e) {
				e.stopPropagation();
				e.preventDefault();
				const afterElement = getDragAfterElement($(this), e.clientY)

				if (afterElement == null) {
					$(this).append(placeholder_drop)
				} else {
					$(afterElement).before(placeholder_drop)
				}

				// Drag Down
				if (e.pageY >= ( $iframe.scrollTop() + $(window).height() - 50)) {
					$iframe.scrollTop($iframe.scrollTop() + 10)
				}

				// Drag Up
				if (e.pageY < ( $iframe.scrollTop() + 50)) {
					$iframe.scrollTop($iframe.scrollTop() - 10)
				}
			})

			$iframe.on('drop', '[data-piotnet-sortable]', function (e) {
				e.stopPropagation();
				e.preventDefault();
				const $panelDraggable = $(document).find('.dragging');
				let clone = null;
				if($panelDraggable.length > 0){
					var $clone = $panelDraggable.clone()
					$clone.removeClass('dragging')
				} else {
					$clone = $iframe.find('.dragging-preview')
				}

				const afterElement = getDragAfterElement($(this), e.clientY)
				if (afterElement == null) {
					$(this).append($clone)
				} else {
					$(afterElement).before($clone)
				}

				create_new_widget($clone);
				previewPlaceholder();
			})
		}

		setup_sortable();

		function create_new_widget($widget) {
			$widget.attr('style','');

			if ($widget.attr('data-piotnetforms-editor-widgets-item-panel') === undefined) {
				// $iframe.find('[data-piotnet-editor-widgets-item]:not([data-piotnet-editor-widgets-item-section]):not([data-piotnet-editor-column])').draggable({
				// 	connectToSortable: $(document).find('[data-piotnetforms-preview-iframe]').contents().find('[data-piotnet-sortable]'),
				// });
				setTimeout(function () {
					generateWidgetsSettings();
				}, 0);
				return;
			}

			const widget_data = JSON.parse($widget.attr('data-piotnetforms-editor-widgets-item'));
			const widget_id = new IDGenerator().generate();

			$widget.removeAttr('data-piotnetforms-editor-widgets-item-panel')

			const $panelWidgetSettings = $("[data-piotnetforms-editor-widget-settings]");

			const widget_type = widget_data['type'];
			if (widget_type === 'section' || widget_type === 'column') {
				$iframe.find('[data-piotnet-editor-widgets-item]').removeClass('active');

				const output_template = pb.get_template("piotnetforms-division-output-template");

				const division_type = "piotnet-" + widget_type;
				const class_container = widget_type === 'column' ? division_type + "__inner" : division_type + "__container";
				let data = {
					"data": {
						"type": widget_type,
						"widget_id": widget_id,
						"widget_settings": {},
						"widget_info": widget_data,
						"division_type": division_type,
						"class_container": class_container
					}
				};
				const view = new View();

				const division_output_template_html = get_division_output_template_html(output_template, data, view)
				$widget.replaceWith(division_output_template_html);

				const $widgetNew = $iframe.find('[data-piotnet-editor-widgets-item-id=' + widget_id + ']');

				if (widget_type === 'section') {
					const column_id = new IDGenerator().generate();
					const column_output_template_html = get_column_output_template_html(output_template, column_id);
					$widgetNew.find('.' + class_container).append(column_output_template_html);

					const controls_widget = pb.get_widget_info('column')['structure'];
					if (controls_widget) {
						pb.set_widget_structure(column_id, controls_widget);
						const $tab_template_el = render_control_widget(column_id);
						pb.set_control_widget(column_id, $tab_template_el);
						$panelWidgetSettings.append($tab_template_el);
					}
					const $columnSettings = $panelWidgetSettings.find('[data-piotnetforms-widget-controls="' + column_id + '"]');
					initWidgetSettingsSection( $columnSettings );
					reInitFieldsControls( $columnSettings );

					const widgetColumnSettings = setting_generator.generateSettings($columnSettings);
					pb.set_setting_widget(column_id, widgetColumnSettings);

					conditionalControl( $columnSettings, pb.get_setting_widget(column_id)['fields'] );
					$columnSettings.find('[data-piotnet-control]:not(.hidden) [data-piotnetforms-settings-field]:not([data-piotnetforms-settings-not-field])').eq(0).trigger('change');

					const widgetColumnCss = generate_css_from_settings(column_id);
					pb.set_css_widget(column_id, widgetColumnCss);
					const style = '<style data-piotnet-widget-css-head="' + column_id + '">' + widgetColumnCss + '</style>';
					$iframe.find('head').first().append($(style));
				}

				$widgetNew.addClass('active');

				const controls_widget = pb.get_widget_info(widget_data['type'])['structure'];
				if (controls_widget) {
					pb.set_widget_structure(widget_id, controls_widget);
					const $tab_template_el = render_control_widget(widget_id);
					pb.set_control_widget(widget_id, $tab_template_el);
					$panelWidgetSettings.append($tab_template_el);
				}

				const $widgetSettings = $panelWidgetSettings.find('[data-piotnetforms-widget-controls="' + widget_id + '"]');
				$panelWidgetSettings.find('[data-piotnetforms-widget-controls]').removeClass('active');
				$widgetSettings.addClass('active');
				$('[data-piotnetforms-widgets]').removeClass('active');
				$('[data-piotnetforms-editor-widgets-open]').addClass('active');

				setTimeout(function () {
					initWidgetSettingsSection( $widgetSettings );
					console.time('reInitFieldsControls');
					reInitFieldsControls( $widgetSettings );
					console.timeEnd('reInitFieldsControls');

					// setup_sortable();

					console.time('generateSettings');
					const widgetSettings = setting_generator.generateSettings($widgetSettings);
					pb.set_setting_widget(widget_id, widgetSettings);
					console.timeEnd('generateSettings');

					console.time('conditionalControl');
					conditionalControl( $widgetSettings, pb.get_setting_widget(widget_id)['fields'] );
					$widgetSettings.find('[data-piotnet-control]:not(.hidden) [data-piotnetforms-settings-field]:not([data-piotnetforms-settings-not-field])').eq(0).trigger('change');
					console.timeEnd('conditionalControl');

					generateWidgetsSettings();

					const widgetCss = generate_css_from_settings(widget_id);
					pb.set_css_widget(widget_id, widgetCss);
					const style = '<style data-piotnet-widget-css-head="' + widget_id + '">' + widgetCss + '</style>';
					$iframe.find('head').first().append($(style));
				}, 0)
				return;
			}

			console.time('render_control_widget');
			$iframe.find('[data-piotnet-editor-widgets-item]').removeClass('active');

			let data = {"data": {"widget_id": widget_id, "widget_settings": {}, "widget_info": widget_data}};
			const view = new View();
			data = _.extend(data, {view: view});

			const output_template = pb.get_template("piotnetforms-output-template");
			$widget.replaceWith(output_template(data));

			const $widgetNew = $iframe.find('[data-piotnet-editor-widgets-item-id=' + widget_id + ']');

			$widgetNew.addClass('active');

			const controls_widget = pb.get_widget_info(widget_data['type'])['structure'];
			if (controls_widget) {
				pb.set_widget_structure(widget_id, controls_widget);
				const $tab_template_el = render_control_widget(widget_id);
				pb.set_control_widget(widget_id, $tab_template_el);
				$panelWidgetSettings.append($tab_template_el);
			}
			console.timeEnd('render_control_widget');

			const $widgetSettings = $panelWidgetSettings.find('[data-piotnetforms-widget-controls="' + widget_id + '"]');
			$panelWidgetSettings.find('[data-piotnetforms-widget-controls]').removeClass('active');
			$widgetSettings.addClass('active');
			$('[data-piotnetforms-widgets]').removeClass('active');
			$('[data-piotnetforms-editor-widgets-open]').addClass('active');

			if (widget_type === 'field') {
				$($widgetSettings).find('[name="field_id"]').val(widget_id);
			} else if (widget_type === 'booking') {
				$($widgetSettings).find('[name="piotnetforms_booking_id"]').val(widget_id);
			}

			setTimeout(function () {
				initWidgetSettingsSection( $widgetSettings );
				console.time('reInitFieldsControls');
				reInitFieldsControls( $widgetSettings );
				console.timeEnd('reInitFieldsControls');

				// setup_sortable();

				console.time('generateSettings');
				const widgetSettings = setting_generator.generateSettings($widgetSettings);
				pb.set_setting_widget(widget_id, widgetSettings);
				console.timeEnd('generateSettings');

				console.time('conditionalControl');
				conditionalControl( $widgetSettings, pb.get_setting_widget(widget_id)['fields'] );
				$widgetSettings.find('[data-piotnet-control]:not(.hidden) [data-piotnetforms-settings-field]:not([data-piotnetforms-settings-not-field])').eq(0).trigger('change');
				console.timeEnd('conditionalControl');

				generateWidgetsSettings();

				const widgetCss = generate_css_from_settings(widget_id);
				pb.set_css_widget(widget_id, widgetCss);
				const style = '<style data-piotnet-widget-css-head="' + widget_id + '">' + widgetCss + '</style>';
				$iframe.find('head').first().append($(style));

				const container_template = pb.get_template("piotnetforms-" + widget_type + "-live-preview-template");
				if (container_template) {
					data = {"data": {"widget_id": widget_id, "widget_settings": widgetSettings['fields'], "widget_info": widget_data}};
					const live_preview_html = get_live_preview_html(container_template, data, view);
					$widgetNew.find('.piotnet-widget__container').append(live_preview_html);
				} else {
					const ajax_data = {
						action: 'piotnetforms_widget_preview',
						function: 'widget_init',
						widget_id: widget_id,
						widget_data: widget_data,
					};
					$.post(ajaxurl, ajax_data, function(response) {
						$iframe.find('[data-piotnet-editor-widgets-item]').removeClass('active');
						var res = JSON.parse( response );
						$widget.replaceWith(res.outputHTML);
					});
				}
			}, 0)
		}

		function back_to_menu() {
			$('[data-piotnetforms-editor-widgets-open]').removeClass('active');
			$('[data-piotnetforms-widget-controls]').removeClass('active');
			$('[data-piotnetforms-widgets]').addClass('active');
		}

		$('[data-piotnetforms-editor-widgets-open-button]').click(function(){
			back_to_menu();
		});

		$iframe.on('click', '[data-piotnet-editor-widgets-item-root]', function(e) {
			click_edit_widget(this);
		});

		$('[data-piotnetforms-editor-collapse-button-close]').click(function(){
			$('.piotnetforms-builder').addClass('piotnetforms-builder--collapse');
			$iframe.find('[data-piotnetforms-widget-preview]').addClass('piotnetforms-widget-preview--collapse');
		});

		$('[data-piotnetforms-editor-collapse-button-open]').click(function(){
			$('.piotnetforms-builder').removeClass('piotnetforms-builder--collapse');
			$iframe.find('[data-piotnetforms-widget-preview]').removeClass('piotnetforms-widget-preview--collapse');
		});

		function change_control_widget_responsive(responsive, $el) {
			const $widget_responsive = $el.find('.piotnet-control__responsive-item.active:first');
			if ($widget_responsive.length > 0) {
				const widget_responsive = $widget_responsive.attr('data-piotnet-control-responsive');
				if (responsive !== widget_responsive) {
					$el.find('[data-piotnet-responsive]').hide();
					$el.find('[data-piotnet-control-responsive]').removeClass('active');
					$el.find('[data-piotnet-responsive="'+responsive+'"]').show();
					$el.find('[data-piotnet-control-responsive="'+responsive+'"]').addClass('active');
				}
			}
		}

		function click_edit_widget(thiz) {
			var $widget = $(thiz).closest('[data-piotnet-editor-widgets-item-id]');
			var widget_id = $widget.attr('data-piotnet-editor-widgets-item-id');
			if (handle_need_install_pro_version(widget_id)) {
				return;
			}

			var $panelWidgetSettings = $("[data-piotnetforms-editor-widget-settings]");
			$panelWidgetSettings.find('[data-piotnetforms-widget-controls]').removeClass('active');

			let $control_widget_el = $panelWidgetSettings.find('[data-piotnetforms-widget-controls="' + widget_id + '"]');

			const $control_widgets = $panelWidgetSettings.find('[data-piotnetforms-widget-controls]');
			const length = $control_widgets.length;
			for (let i = 0; i < length; i++) {
				const $el = $($control_widgets[i]);
				const id = $el.attr('data-piotnetforms-widget-controls');
				if (id !== widget_id) {
					pb.set_control_widget(id, $el);
					$el.detach();
				}
			}

			if ($control_widget_el.length === 0) {
				$control_widget_el = pb.get_control_widget(widget_id);
				if (!$control_widget_el) {
					$control_widget_el = render_control_widget(widget_id);
					pb.set_control_widget(widget_id, $control_widget_el);
					reInitFieldsControls( $control_widget_el );
					conditionalControl( $control_widget_el, pb.get_setting_widget(widget_id)['fields'] );
					var $repeaterList = $control_widget_el.find('[data-piotnet-control-repeater-list]');
					if ($repeaterList.length > 0) {
						repeaterSortable($repeaterList);
					}
				}
				$panelWidgetSettings.append($control_widget_el);
			}

			change_control_widget_responsive(pb.get_responsive(), $panelWidgetSettings);

			$control_widget_el.addClass('active');
			$('[data-piotnetforms-widgets]').removeClass('active');
			$('[data-piotnetforms-editor-widgets-open]').addClass('active');
			$iframe.find('[data-piotnet-editor-widgets-item]').removeClass('active');
			$(thiz).closest('[data-piotnet-editor-widgets-item-id]').addClass('active');
		}

		$iframe.on('click', '[data-piotnet-control-edit]', function(e) {
			click_edit_widget(this);
		});

		function remove_widget_data(widget_id) {
			delete pb.get_control_widgets()[widget_id];
			delete pb.get_css_widgets()[widget_id];
			$iframe.find('[data-piotnet-widget-css-head="' + widget_id + '"]').remove();
			delete pb.get_setting_widgets()[widget_id];
			delete pb.get_widget_structures()[widget_id];
		}

		$iframe.on('click', '[data-piotnet-control-remove]', function(e) {
			const $column = $(this).closest('[data-piotnet-editor-widgets-item]');
			if ($column.length > 0) {
				if( $column.index() === 0 && $column.siblings().length === 0 && $column.attr('data-piotnet-editor-column') !== undefined) {
					return;
				}
			}

			const $widget = $(this).closest('[data-piotnet-editor-widgets-item-id]');
			const widgetId = $widget.attr('data-piotnet-editor-widgets-item-id');

			$("[data-piotnetforms-editor-widget-settings]").find('[data-piotnetforms-widget-controls="' + widgetId + '"]').remove();
			back_to_menu();

			remove_widget_data(widgetId);

			$widget.find('[data-piotnet-editor-widgets-item-id]').each(function(){
				const widgetIdCurrent = $(this).attr('data-piotnet-editor-widgets-item-id');
				remove_widget_data(widgetIdCurrent);
			});

			$widget.remove();

			generateWidgetsSettings();
			previewPlaceholder();
		});

		function duplicate_widget($widget, is_deep_duplicate) {
			const widget_id = $widget.attr('data-piotnet-editor-widgets-item-id');

			const clone_widget_id = new IDGenerator().generate();
			const $clone_widget = is_deep_duplicate ? $($widget[0].outerHTML) : $widget;

			$clone_widget.attr('data-piotnet-editor-widgets-item-id', clone_widget_id);


			$clone_widget.find('.piotnet-widget.active').removeClass('active');

			const widget_type = pb.get_setting_widget(widget_id)['type'];
			if (widget_type === 'section' || widget_type === 'column') {
				$clone_widget.removeClass(widget_id);
				$clone_widget.addClass(clone_widget_id);
			} else {
				$clone_widget.find('[data-piotnet-editor-widgets-item-root]').removeClass(widget_id);
				$clone_widget.find('[data-piotnet-editor-widgets-item-root]').addClass(clone_widget_id);
			}

			const setting_widget = ObjectUtil.clone(pb.get_setting_widget(widget_id));
			if (widget_type === 'field') {
				setting_widget['fields']['field_id'] = clone_widget_id;
			} else if (widget_type === 'booking') {
				setting_widget['fields']['piotnetforms_booking_id'] = clone_widget_id;
			}
			pb.set_setting_widget(clone_widget_id, setting_widget);

			const widget_structure = ObjectUtil.clone(pb.get_widget_structure(widget_id));
			pb.set_widget_structure(clone_widget_id, fill_values(widget_structure, setting_widget['fields']));

			let clone_css_widget = pb.get_css_widget(widget_id);
			clone_css_widget = StringUtil.replaceAll(clone_css_widget, widget_id, clone_widget_id);
			pb.set_css_widget(clone_widget_id, clone_css_widget);
			const style = '<style data-piotnet-widget-css-head="' + clone_widget_id + '">' + clone_css_widget + '</style>';
			$iframe.find('head').first().append($(style));
			return $clone_widget;
		}

		function handle_need_install_pro_version(widget_id) {
			const widget_structure = pb.get_widget_structure(widget_id);
			if (!widget_structure) {
				alert('Please purchase and install Pro version to use this widget, Go Pro Now');
				return true;
			}
			return false;
		}

		$iframe.on('click', '[data-piotnet-control-duplicate]', function(e) {
			$('[data-piotnetforms-editor-save]').hide();
			const $widget = $(this).closest('[data-piotnet-editor-widgets-item-id]');

			const widget_id = $widget.attr('data-piotnet-editor-widgets-item-id');
			if (handle_need_install_pro_version(widget_id)) {
				return;
			}

			const $clone_widget = duplicate_widget($widget, true);

			$clone_widget.find('[data-piotnet-editor-widgets-item-id]').each(function(){
				duplicate_widget($(this), false);
			});

			$widget.after( $clone_widget[0].outerHTML );

			// setup_sortable();

			generateWidgetsSettings();
			$('[data-piotnetforms-editor-save]').show();
		});

		$('#publish').addClass('hidden');

		function build_piotnetforms_data(settings, settings_object) {
			for (const widget_id in settings_object) {
				const widget = settings_object[widget_id];
				delete widget['postID']

				const fields = widget['fields']
				widget['settings'] = fields
				delete widget['fields']
			}

			const data = {
				'widgets': settings_object,
				'content': settings,
			}

			// TODO fonts
			return data;
		}

		$('[data-piotnetforms-editor-save]').click(function(e){
			const css_widgets = pb.get_css_widgets();
			let total_css = '';
			for (const widget_id in css_widgets) {
				total_css += css_widgets[widget_id] + ' ';
			}
			const $saveButton = $(this);
			$saveButton.addClass('saving');

			const piotnetforms_data = build_piotnetforms_data(ObjectUtil.clone(pb.get_tree_setting_widgets()), ObjectUtil.clone(pb.get_setting_widgets()));
			const data = {
				'action': 'piotnetforms_save',
				'post_id': post_id,
				'piotnet-widgets-css': total_css,
				'piotnetforms_data': JSON.stringify(piotnetforms_data)
			};

			$.post(ajaxurl, data, function(response) {
				$saveButton.removeClass('saving');
			});
		});

		$iframe.on('click', 'a', function(e) {
			e.preventDefault(); 
		});

		$(document).on('keyup change', '[name="field_id"]', function() {
			$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-field-shortcode').val('[field id="' + $(this).val() + '"]');
		});

		$(document).on('keyup change', '[name="piotnetforms_repeater_id"]', function() {
			$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-repeater-shortcode').val('[repeater id="' + $(this).val() + '"]');
		});

		$(document).on('keyup change', '[name="piotnetforms_booking_id"]', function() {
			$(this).closest('[data-piotnet-controls-section]').find('.piotnetforms-field-shortcode').val('[field id="' + $(this).val() + '"]');
		});

		$(document).on('click', '.piotnetforms-field-shortcode', function() {
			const $controls_section_el = $(this).closest('[data-piotnet-controls-section]');
			let fieldId = $controls_section_el.find('[name="field_id"]').val();
			if (fieldId === undefined) {
				fieldId = $controls_section_el.find('[name="piotnetforms_booking_id"]').val();
			}
			$controls_section_el.find('.piotnetforms-field-shortcode').val('[field id="' + fieldId + '"]');
		});

		$(document).on('click', '.piotnetforms-repeater-shortcode', function() {
			if ($(this).val() === '') {
				const $controls_section_el = $(this).closest('[data-piotnet-controls-section]');
				const repeaterId = $controls_section_el.find('[name="piotnetforms_repeater_id"]').val();
				$controls_section_el.find('.piotnetforms-repeater-shortcode').val('[repeater id="' + repeaterId + '"]');
			}
		});

		$(document).on('click','[data-piotnetforms-campaign-get-data-list]', function() {
			const $parent = $(this).closest('#elementor-controls'); // FIXME elementor-controls???
			const $results = $parent.find('[data-piotnetforms-campaign-get-data-list-results]');
			const campaign = $parent.find('[data-setting="activecampaign_api_key_source"]').val();
			let campaign_url = false;
			let campaign_key = false;
			if(campaign === 'custom'){
				campaign_url = $parent.find( '[data-setting="activecampaign_api_url"]' ).val();
				campaign_key = $parent.find( '[data-setting="activecampaign_api_key"]' ).val();
			}
			const data = {
				'action': 'piotnetforms_campaign_select_list',
				'campaign_url': campaign_url,
				'campaign_key': campaign_key,
			};
			$.post(ajaxurl, data, function(response) {
				if(response){
					$results.html(response);
					$parent.find('[data-setting="activecampaign_list"]').change();
				}
			});
		});

		$(document).on('keyup, change','[data-setting="activecampaign_list"]', function() {
			const $parent = $(this).closest('#elementor-controls'); // FIXME elementor-controls???
			const campaign = $parent.find('[data-setting="activecampaign_api_key_source"]').val();
			const listId = $(this).val();

			let campaign_url = false;
			let campaign_key = false;
	 		if(campaign === 'custom'){
				campaign_url = $parent.find( '[data-setting="activecampaign_api_url"]' ).val();
				campaign_key = $parent.find( '[data-setting="activecampaign_api_key"]' ).val();
			}
			const data = {
				'action': 'piotnetforms_campaign_fields',
				'campaign_url': campaign_url,
				'campaign_key': campaign_key,
				'list_id': listId
			};
			$.post(ajaxurl, data, function(response) {
				if(response){
					$parent.find('[data-piotnetforms-campaign-get-fields]').html(response);
				}
			});
		});

	    $(document).on('click', '[data-piotnet-tab-heading]', function(){
			const attr = $(this).attr('data-piotnet-tab-heading');
			const $parent = $(this).closest('[data-piotnet-controls-section-body]');
			$parent.find('[data-piotnet-tab-heading]').removeClass('active');
	        $parent.find('[data-piotnet-tab-heading='+attr+']').addClass('active');
	        $parent.find('[data-piotnet-tab-content]').removeClass('active');
	        $parent.find('[data-piotnet-tab-content='+attr+']').addClass('active');
		});
		$(document).on('click','[data-piotnet-repeater-heading]', function(){
			const $parent = $(this).closest('[data-piotnet-control-repeater-list]');
			var $repeater = $(this).closest('[data-piotnet-control-repeater-item]');
			if($repeater.find('[data-piotnet-repeater-field]').eq(0).hasClass('active')){
				$repeater.find('[data-piotnet-repeater-field]').eq(0).removeClass('active');
			}else{
				$parent.find('[data-piotnet-repeater-field]').removeClass('active');
				$repeater.find('[data-piotnet-repeater-field]').eq(0).addClass('active');
			}
		});

		//Icon select
		$(document).on('click', '.piotnet-icon-item__inner', function(){
			var parent = $(this).closest('.piotnet-icon-items');
			parent.find('.piotnet-icon-item__inner').removeClass('active');
			$(this).addClass('active');
		});

		$(document).on('keyup', '[data-piotnet-search-icon]',$.debounce(100, function(){
			var parent = $(this).closest('[data-piotnet-modal-content]');
			var iconItem = parent.find('[data-piotnet-control-icon]');
			var filter = $(this).val().toUpperCase();
			console.log(filter);
			//console.log(iconItem);
			$.each(iconItem, function(index, item){
				var textContent = $(this).find('.piotnet-icon-value').text();
				console.log(textContent.toUpperCase().indexOf(filter))
				if(textContent.toUpperCase().indexOf(filter) > -1){
					$(this).removeClass('hidden');
				}else{
					$(this).addClass('hidden');
				}
				//console.log(textContent);
			});
		}));

	}); // End iframe load
});
