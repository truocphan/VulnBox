const CONTROL_WIDGETS = "control_widgets";
const CSS_WIDGETS = "css_widgets";
const WIDGET_INFOS = "widget_infos";
const LIBS = "libs";
const SETTINGS = "settings";
const TEMPLATES = "templates";
const WIDGET_STRUCTURES = "widget_structures";
const BREAKPOINTS = "breakpoints";
const RESPONSIVE = "responsive";

export default class piotnetforms {
    constructor() {
        const master = {}
        master[CONTROL_WIDGETS] = {};
        master[CSS_WIDGETS] = {};
        master[WIDGET_INFOS] = {};
        master[LIBS] = {};
        master[SETTINGS] = {
            'widgets': {},
            'tree': {}
        };
        master[TEMPLATES] = {};
        master[WIDGET_STRUCTURES] = {};
        master[BREAKPOINTS] = {};
        master[RESPONSIVE] = "desktop";
        this.master = master;
    }

    get_template(template_id) {
        return this.master[TEMPLATES][template_id];
    }

    set_template(template_id, $template) {
        this.master[TEMPLATES][template_id] = $template;
    }

    get_setting_widgets() {
        return this.master[SETTINGS]['widgets'];
    }

    set_setting_widgets(setting_widgets) {
        this.master[SETTINGS]['widgets'] = setting_widgets;
    }

    get_setting_widget(widget_id) {
        return this.get_setting_widgets()[widget_id];
    }

    set_setting_widget(widget_id, setting_widget) {
        this.get_setting_widgets()[widget_id] = setting_widget;
    }

    get_tree_setting_widgets() {
        return this.master[SETTINGS]['tree'];
    }

    set_tree_setting_widgets(tree_setting_widgets) {
        this.master[SETTINGS]['tree'] = tree_setting_widgets;
    }

    get_css_widgets() {
        return this.master[CSS_WIDGETS];
    }

    get_css_widget(widget_id) {
        return this.get_css_widgets()[widget_id];
    }

    set_css_widget(widget_id, css) {
        this.get_css_widgets()[widget_id] = css;
    }

    get_control_widgets() {
        return this.master[CONTROL_WIDGETS];
    }

    get_control_widget(widget_id) {
        return this.get_control_widgets()[widget_id];
    }

    set_control_widget(widget_id, control_widget) {
        this.get_control_widgets()[widget_id] = control_widget;
    }

    get_widget_structures() {
        return this.master[WIDGET_STRUCTURES];
    }

    get_widget_structure(widget_id) {
        return this.get_widget_structures()[widget_id];
    }

    set_widget_structure(widget_id, widget_structure) {
        this.get_widget_structures()[widget_id] = widget_structure;
    }

    get_widget_infos() {
        return this.master[WIDGET_INFOS];
    }

    set_widget_infos(widget_infos) {
        this.master[WIDGET_INFOS] = widget_infos;
    }

    get_widget_info(name) {
        return this.get_widget_infos()[name];
    }

    get_libs() {
        return this.master[LIBS];
    }

    set_libs(libs) {
        this.master[LIBS] = libs;
    }

    set_breakpoint(breakpoint_id, value) {
        this.master[BREAKPOINTS][breakpoint_id] = value;
    }

    get_breakpoint(breakpoint_id) {
        return this.master[BREAKPOINTS][breakpoint_id];
    }

    set_responsive(responsive) {
        this.master[RESPONSIVE] = responsive;
    }

    get_responsive() {
        return this.master[RESPONSIVE];
    }
}
