var hashFormBuilder = hashFormBuilder || {};

(function ($) {
    'use strict';
    let $editorFieldsWrap = $('#hf-editor-fields'),
            $buildForm = $('#hf-fields-form'),
            buildForm = document.getElementById('hf-fields-form'),
            $formMeta = $('#hf-meta-form'),
            $formSettings = $('#hf-settings-form'),
            currentFormId = $('#hf-form-id').val(),
            copyHelper = false,
            // fieldsUpdated = 0,
            autoId = 0;


    const wysiwyg = {
        init(editor, { setupCallback, height, addFocusEvents } = {}) {
            if (isTinyMceActive()) {
                setTimeout(resetTinyMce, 0);
            } else {
                initQuickTagsButtons();
            }

            setUpTinyMceVisualButtonListener();
            setUpTinyMceHtmlButtonListener();

            function initQuickTagsButtons() {
                if ('function' !== typeof window.quicktags || typeof window.QTags.instances[ editor.id ] !== 'undefined') {
                    return;
                }

                const id = editor.id;
                window.quicktags({
                    name: 'qt_' + id,
                    id: id,
                    canvas: editor,
                    settings: {id},
                    toolbar: document.getElementById('qt_' + id + '_toolbar'),
                    theButtons: {}
                });
            }

            function initRichText() {
                const key = Object.keys(tinyMCEPreInit.mceInit)[0];
                const orgSettings = tinyMCEPreInit.mceInit[ key ];

                const settings = Object.assign(
                        {},
                        orgSettings,
                        {
                            selector: '#' + editor.id,
                            body_class: orgSettings.body_class.replace(key, editor.id)
                        }
                );

                settings.setup = editor => {
                    if (addFocusEvents) {
                        function focusInCallback() {
                            $(editor.targetElm).trigger('focusin');
                            editor.off('focusin', '**');
                        }

                        editor.on('focusin', focusInCallback);

                        editor.on('focusout', function () {
                            editor.on('focusin', focusInCallback);
                        });
                    }
                    if (setupCallback) {
                        setupCallback(editor);
                    }
                };

                if (height) {
                    settings.height = height;
                }

                tinymce.init(settings);
            }

            function removeRichText() {
                tinymce.EditorManager.execCommand('mceRemoveEditor', true, editor.id);
            }

            function resetTinyMce() {
                removeRichText();
                initRichText();
            }

            function isTinyMceActive() {
                const id = editor.id;
                const wrapper = document.getElementById('wp-' + id + '-wrap');
                return null !== wrapper && wrapper.classList.contains('tmce-active');
            }

            function setUpTinyMceVisualButtonListener() {
                $(document).on(
                        'click', '#' + editor.id + '-html',
                        function () {
                            editor.style.visibility = 'visible';
                            initQuickTagsButtons(editor);
                        }
                );
            }

            function setUpTinyMceHtmlButtonListener() {
                $('#' + editor.id + '-tmce').on('click', handleTinyMceHtmlButtonClick);
            }

            function handleTinyMceHtmlButtonClick() {
                if (isTinyMceActive()) {
                    resetTinyMce();
                } else {
                    initRichText();
                }

                const wrap = document.getElementById('wp-' + editor.id + '-wrap');
                wrap.classList.add('tmce-active');
                wrap.classList.remove('html-active');
        }
        }
    };

    hashFormBuilder = {
        init: function () {
            hashFormBuilder.initBuild();

        },

        initBuild: function () {
            $('ul.hf-fields-list, .hf-fields-list li').disableSelection();

            hashFormBuilder.setupSortable('ul.hf-editor-sorting');
            document.querySelectorAll('.hf-fields-list > li').forEach(hashFormBuilder.makeDraggable);

            $editorFieldsWrap.on('click', 'li.hf-editor-field-box.ui-state-default', hashFormBuilder.clickField);
            $editorFieldsWrap.on('click', '.hf-editor-delete-action', hashFormBuilder.clickDeleteField);
            $editorFieldsWrap.on('mousedown', 'input, textarea, select', hashFormBuilder.stopFieldFocus);
            $editorFieldsWrap.on('click', 'input[type=radio], input[type=checkbox]', hashFormBuilder.stopFieldFocus);

            $('#hf-add-fields-panel').on('click', '.hf-add-field', hashFormBuilder.addFieldClick);
        },

        setupSortable: function (sortableSelector) {
            document.querySelectorAll(sortableSelector).forEach(
                    list => {
                        hashFormBuilder.makeDroppable(list);
                        Array.from(list.children).forEach(
                                child => hashFormBuilder.makeDraggable(child, '.hf-editor-move-action')
                        );
                    }
            );

        },

        makeDroppable: function (list) {
            $(list).droppable({
                accept: '.hf-field-box, .hf-editor-field-box',
                deactivate: hashFormBuilder.handleFieldDrop,
                over: hashFormBuilder.onDragOverDroppable,
                out: hashFormBuilder.onDraggableLeavesDroppable,
                tolerance: 'pointer'
            });
        },

        makeDraggable: function (draggable, handle) {
            const settings = {
                helper: function (event) {
                    const draggable = event.delegateTarget;

                    if (draggable.classList.contains('hf-editor-field-box') && !draggable.classList.contains('hf-editor-form-field')) {
                        const newTextFieldClone = '';
                        newTextFieldClone.querySelector('span').textContent = 'Field Group';
                        newTextFieldClone.classList.add('hf-editor-field-box');
                        newTextFieldClone.classList.add('ui-sortable-helper');
                        return newTextFieldClone;
                    }

                    let copyTarget;
                    const isNewField = draggable.classList.contains('hf-field-box');
                    if (isNewField) {
                        copyTarget = draggable.cloneNode(true);
                        copyTarget.classList.add('ui-sortable-helper');
                        draggable.classList.add('hf-added-field');
                        return copyTarget;
                    }

                    if (draggable.hasAttribute('data-type')) {
                        const fieldType = draggable.getAttribute('data-type');
                        copyTarget = document.getElementById('hf-add-fields-panel').querySelector('.hashform_' + fieldType);
                        copyTarget = copyTarget.cloneNode(true);
                        copyTarget.classList.add('hf-editor-form-field');

                        copyTarget.classList.add('ui-sortable-helper');

                        if (copyTarget) {
                            return copyTarget.cloneNode(true);
                        }
                    }

                    return hashFormBuilder.div({className: 'hf-field-box'});
                },
                revert: 'invalid',
                delay: 10,
                start: function (event, ui) {
                    document.body.classList.add('hf-dragging');
                    ui.helper.addClass('hf-sortable-helper');

                    event.target.classList.add('hf-drag-fade');

                    hashFormBuilder.unselectFieldGroups();
                    hashFormBuilder.deleteEmptyDividerWrappers();
                    hashFormBuilder.maybeRemoveGroupHoverTarget();
                },
                stop: function () {
                    document.body.classList.remove('hf-dragging');

                    const fade = document.querySelector('.hf-drag-fade');
                    if (fade) {
                        fade.classList.remove('hf-drag-fade');
                    }
                },
                drag: function (event, ui) {
                    // maybeScrollBuilder( event );
                    const draggable = event.target;
                    const droppable = hashFormBuilder.getDroppableTarget();

                    let placeholder = document.getElementById('hf-placeholder');

                    if (!hashFormBuilder.allowDrop(draggable, droppable)) {
                        if (placeholder) {
                            placeholder.remove();
                        }
                        return;
                    }

                    if (!placeholder) {
                        placeholder = hashFormBuilder.tag('li', {
                            id: 'hf-placeholder',
                            className: 'sortable-placeholder'
                        });
                    }
                    const hfSortableHelper = ui.helper.get(0);

                    if ('hf-editor-fields' === droppable.id || droppable.classList.contains('start_divider')) {
                        placeholder.style.left = 0;
                        hashFormBuilder.handleDragOverYAxis({droppable, y: event.clientY, placeholder});
                        return;
                    }

                    placeholder.style.top = '';
                    hashFormBuilder.handleDragOverFieldGroup({droppable, x: event.clientX, placeholder});
                },
                cursor: 'grabbing',
                refreshPositions: true,
                cursorAt: {
                    top: 0,
                    left: 90 // The width of draggable button is 180. 90 should center the draggable on the cursor.
                }
            };
            if ('string' === typeof handle) {
                settings.handle = handle;
            }
            $(draggable).draggable(settings);
        },

        div: function (args) {
            return hashFormBuilder.tag('div', args);
        },

        tag: function (type, args = {}) {
            const output = document.createElement(type);
            if ('string' === typeof args) {
                output.textContent = args;
                return output;
            }

            const {id, className, children, child, text, data} = args;

            if (id) {
                output.id = id;
            }
            if (className) {
                output.className = className;
            }
            if (children) {
                children.forEach(child => output.appendChild(child));
            } else if (child) {
                output.appendChild(child);
            } else if (text) {
                output.textContent = text;
            }
            if (data) {
                Object.keys(data).forEach(function (dataKey) {
                    output.setAttribute('data-' + dataKey, data[dataKey]);
                });
            }
            return output;
        },

        deleteEmptyDividerWrappers: function () {
            const dividers = document.querySelectorAll('ul.start_divider');
            if (!dividers.length) {
                return;
            }
            dividers.forEach(
                    function (divider) {
                        const children = [].slice.call(divider.children);
                        children.forEach(
                                function (child) {
                                    if (0 === child.children.length) {
                                        child.remove();
                                    } else if (1 === child.children.length && 'ul' === child.firstElementChild.nodeName.toLowerCase() && 0 === child.firstElementChild.children.length) {
                                        child.remove();
                                    }
                                }
                        );
                    }
            );
        },

        maybeRemoveGroupHoverTarget: function () {
            var controls, previousHoverTarget;

            controls = document.getElementById('hashform_field_group_controls');
            if (null !== controls) {
                controls.style.display = 'none';
            }

            previousHoverTarget = document.querySelector('.hf-field-group-hover-target');
            if (null === previousHoverTarget) {
                return false;
            }

            $('#wpbody-content').off('mousemove', hashFormBuilder.maybeRemoveHoverTargetOnMouseMove);
            previousHoverTarget.classList.remove('hf-field-group-hover-target');
            return previousHoverTarget;
        },

        getDroppableTarget: function () {
            let droppable = document.getElementById('hf-editor-fields');
            while (droppable.querySelector('.hf-dropabble')) {
                droppable = droppable.querySelector('.hf-dropabble');
            }
            if ('hf-editor-fields' === droppable.id && !droppable.classList.contains('hf-dropabble')) {
                droppable = false;
            }
            return droppable;
        },

        handleDragOverYAxis: function ( {droppable, y, placeholder}) {
            const $list = $(droppable);
            let top;

            const $children = $list.children().not('.hf-editor-field-type-end_divider');
            if (0 === $children.length) {
                $list.prepend(placeholder);
                top = 0;
            } else {
                const insertAtIndex = hashFormBuilder.determineIndexBasedOffOfMousePositionInList($list, y);
                if (insertAtIndex === $children.length) {
                    const $lastChild = $($children.get(insertAtIndex - 1));
                    top = $lastChild.offset().top + $lastChild.outerHeight();
                    $list.append(placeholder);

                    // Make sure nothing gets inserted after the end divider.
                    const $endDivider = $list.children('.hf-editor-field-type-end_divider');
                    if ($endDivider.length) {
                        $list.append($endDivider);
                    }
                } else {
                    top = $($children.get(insertAtIndex)).offset().top;
                    $($children.get(insertAtIndex)).before(placeholder);
                }
            }
            top -= $list.offset().top;
            placeholder.style.top = top + 'px';
        },

        handleDragOverFieldGroup: function ( {droppable, x, placeholder}) {
            const $row = $(droppable);
            const $children = hashFormBuilder.getFieldsInRow($row);
            if (!$children.length) {
                return;
            }
            let left;
            const insertAtIndex = hashFormBuilder.determineIndexBasedOffOfMousePositionInRow($row, x);

            if (insertAtIndex === $children.length) {
                const $lastChild = $($children.get(insertAtIndex - 1));
                left = $lastChild.offset().left + $lastChild.outerWidth();
                $row.append(placeholder);
            } else {
                left = $($children.get(insertAtIndex)).offset().left;
                $($children.get(insertAtIndex)).before(placeholder);

                const amountToOffsetLeftBy = 0 === insertAtIndex ? 4 : 8; // Offset by 8 in between rows, but only 4 for the first item in a group.
                left -= amountToOffsetLeftBy; // Offset the placeholder slightly so it appears between two fields.
            }
            left -= $row.offset().left;
            placeholder.style.left = left + 'px';
        },

        determineIndexBasedOffOfMousePositionInRow: function ($row, x) {
            var $inputs = hashFormBuilder.getFieldsInRow($row),
                    length = $inputs.length,
                    index, input, inputLeft, returnIndex;
            returnIndex = 0;
            for (index = length - 1; index >= 0; --index) {
                input = $inputs.get(index);
                inputLeft = $(input).offset().left;
                if (x > inputLeft) {
                    returnIndex = index;
                    if (x > inputLeft + ($(input).outerWidth() / 2)) {
                        returnIndex = index + 1;
                    }
                    break;
                }
            }
            return returnIndex;
        },

        getFieldsInRow: function ($row) {
            let $fields = $();
            const row = $row.get(0);
            if (!row.children) {
                return $fields;
            }

            Array.from(row.children).forEach(
                    child => {
                        if ('none' === child.style.display) {
                            return;
                        }
                        const classes = child.classList;
                        if (!classes.contains('hf-editor-form-field') || classes.contains('hf-editor-field-type-end_divider') || classes.contains('hf-sortable-helper')) {
                            return;
                        }
                        $fields = $fields.add(child);
                    }
            );
            return $fields;
        },

        allowDrop: function (draggable, droppable) {
            if (false === droppable) {
                return false;
            }

            if (droppable.closest('.hf-sortable-helper')) {
                return false;
            }

            if ('hf-editor-fields' === droppable.id) {
                return true;
            }

            if (!droppable.classList.contains('start_divider')) {
                const $fieldsInRow = hashFormBuilder.getFieldsInRow($(droppable));
                if (!hashFormBuilder.groupCanFitAnotherField($fieldsInRow, $(draggable))) {
                    // Field group is full and cannot accept another field.
                    return false;
                }
            }

            const isNewField = draggable.classList.contains('hf-added-field');
            if (isNewField) {
                return hashFormBuilder.allowNewFieldDrop(draggable, droppable);
            }
            return hashFormBuilder.allowMoveField(draggable, droppable);
        },

        groupCanFitAnotherField: function (fieldsInRow, $field) {
            var fieldId;
            if (fieldsInRow.length < 6) {
                return true;
            }
            if (fieldsInRow.length > 6) {
                return false;
            }
            fieldId = $field.attr('data-fid');
            // allow 6 if we're not changing field groups.
            return 1 === $(fieldsInRow).filter('[data-fid="' + fieldId + '"]').length;
        },

        allowNewFieldDrop: function (draggable, droppable) {
            const classes = draggable.classList;
            const newPageBreakField = classes.contains('hashform_break');
            const newHiddenField = classes.contains('hashform_hidden');
            const newSectionField = classes.contains('hashform_divider');
            const newEmbedField = classes.contains('hashform_form');

            const newFieldWillBeAddedToAGroup = !('hf-editor-fields' === droppable.id || droppable.classList.contains('start_divider'));
            if (newFieldWillBeAddedToAGroup) {
                if (hashFormBuilder.groupIncludesBreakOrHidden(droppable)) {
                    return false;
                }
                return !newHiddenField && !newPageBreakField;
            }

            const fieldTypeIsAlwaysAllowed = !newPageBreakField && !newHiddenField && !newSectionField && !newEmbedField;
            if (fieldTypeIsAlwaysAllowed) {
                return true;
            }

            const newFieldWillBeAddedToASection = droppable.classList.contains('start_divider') || null !== droppable.closest('.start_divider');
            if (newFieldWillBeAddedToASection) {
                return !newEmbedField && !newSectionField;
            }

            return true;
        },

        allowMoveField: function (draggable, droppable) {
            if (draggable.classList.contains('hf-editor-field-box') && !draggable.classList.contains('hf-editor-form-field')) {
                return hashFormBuilder.allowMoveFieldGroup(draggable, droppable);
            }

            const isPageBreak = draggable.classList.contains('hf-editor-field-type-break');
            if (isPageBreak) {
                return false;
            }

            if (droppable.classList.contains('start_divider')) {
                return hashFormBuilder.allowMoveFieldToSection(draggable);
            }

            const isHiddenField = draggable.classList.contains('hf-editor-field-type-hidden');
            if (isHiddenField) {
                return false;
            }
            return hashFormBuilder.allowMoveFieldToGroup(draggable, droppable);
        },

        allowMoveFieldGroup: function (fieldGroup, droppable) {
            if (droppable.classList.contains('start_divider') && null === fieldGroup.querySelector('.start_divider')) {
                // Allow a field group with no section inside of a section.
                return true;
            }
            return false;
        },

        allowMoveFieldToSection: function (draggable) {
            const draggableIncludeEmbedForm = draggable.classList.contains('hf-editor-field-type-form') || draggable.querySelector('.hf-editor-field-type-form');
            if (draggableIncludeEmbedForm) {
                // Do not allow an embedded form inside of a section.
                return false;
            }

            const draggableIncludesSection = draggable.classList.contains('hf-editor-field-type-divider') || draggable.querySelector('.hf-editor-field-type-divider');
            if (draggableIncludesSection) {
                // Do not allow a section inside of a section.
                return false;
            }

            return true;
        },

        allowMoveFieldToGroup: function (draggable, group) {
            if (hashFormBuilder.groupIncludesBreakOrHidden(group)) {
                // Never allow any field beside a page break or a hidden field.
                return false;
            }

            const isFieldGroup = $(draggable).children('ul.hf-editor-sorting').not('.start_divider').length > 0;
            if (isFieldGroup) {
                // Do not allow a field group directly inside of a field group unless it's in a section.
                return false;
            }

            const draggableIncludesASection = draggable.classList.contains('hf-editor-field-type-divider') || draggable.querySelector('.hf-editor-field-type-divider');
            const draggableIsEmbedField = draggable.classList.contains('hf-editor-field-type-form');
            const groupIsInASection = null !== group.closest('.start_divider');
            if (groupIsInASection && (draggableIncludesASection || draggableIsEmbedField)) {
                // Do not allow a section or an embed field inside of a section.
                return false;
            }

            return true;
        },

        groupIncludesBreakOrHidden: function (group) {
            return null !== group.querySelector('.hf-editor-field-type-break, .hf-editor-field-type-hidden');
        },

        unselectFieldGroups: function (event) {
            if ('undefined' !== typeof event) {
                if (null !== event.originalEvent.target.closest('#hf-editor-fields')) {
                    return;
                }
                if (event.originalEvent.target.classList.contains('hf-merge-fields-into-row')) {
                    return;
                }
                if (null !== event.originalEvent.target.closest('.hf-merge-fields-into-row')) {
                    return;
                }
                if (event.originalEvent.target.classList.contains('hf-custom-field-group-layout')) {
                    return;
                }
                if (event.originalEvent.target.classList.contains('hf-cancel-custom-field-group-layout')) {
                    return;
                }
            }
            $('.hf-selected-field-group').removeClass('hf-selected-field-group');
            $(document).off('click', hashFormBuilder.unselectFieldGroups);
        },

        clickField: function (e) {
            /*jshint validthis:true */
            var currentClass, originalList;

            currentClass = e.target.classList;

            if (currentClass.contains('hf-collapse-page') || currentClass.contains('hf-sub-label') || e.target.closest('.dropdown') !== null) {
                return;
            }

            if (this.closest('.start_divider') !== null) {
                e.stopPropagation();
            }

            if (this.classList.contains('hf-editor-field-type-divider')) {
                originalList = e.originalEvent.target.closest('ul.hf-editor-sorting');
                if (null !== originalList) {
                    // prevent section click if clicking a field group within a section.
                    if (originalList.classList.contains('hf-editor-field-type-divider') || originalList.parentNode.parentNode.classList.contains('start_divider')) {
                        return;
                    }
                }
            }

            hashFormBuilder.clickAction(this);
        },

        clickAction: function (obj) {
            var $thisobj = $(obj);
            if (obj.className.indexOf('selected') !== -1)
                return;
            if (obj.className.indexOf('hf-editor-field-type-end_divider') !== -1 && $thisobj.closest('.hf-editor-field-type-divider').hasClass('no_repeat_section'))
                return;
            hashFormBuilder.deselectFields();
            $thisobj.addClass('selected');
            hashFormBuilder.showFieldOptions(obj);
        },

        showFieldOptions: function (obj) {
            var i, singleField,
                    fieldId = obj.getAttribute('data-fid'),
                    fieldType = obj.getAttribute('data-type'),
                    allFieldSettings = document.querySelectorAll('.hf-fields-settings:not(.hf-hidden)');

            for (i = 0; i < allFieldSettings.length; i++) {
                allFieldSettings[i].classList.add('hf-hidden');
            }

            singleField = document.getElementById('hf-fields-settings-' + fieldId);
            hashFormBuilder.moveFieldSettings(singleField);

            singleField.classList.remove('hf-hidden');
            document.getElementById('hf-options-tab').click();

            const editor = singleField.querySelector('.wp-editor-area');
            if (editor) {
                wysiwyg.init(editor, {setupCallback: hashFormBuilder.setupTinyMceEventHandlers});
            }
        },

        clickDeleteField: function () {
            if (confirm("Are you sure?")) {
                hashFormBuilder.deleteFields($(this).attr('data-deletefield'))
            }
            return false;
        },

        deleteFields: function (fieldId) {
            var field = $('#hf-editor-field-id-' + fieldId);

            hashFormBuilder.deleteField(fieldId);
            if (field.hasClass('hf-editor-field-type-divider')) {
                field.find('li.hf-editor-field-box').each(function () {
                    hashFormBuilder.deleteField(this.getAttribute('data-fid'));
                });
            }
            hashFormBuilder.toggleSectionHolder();
        },

        deleteField: function (fieldId) {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'hashform_delete_field',
                    field_id: fieldId,
                    nonce: hashform_backend_js.nonce
                },
                success: function () {
                    var $thisField = $('#hf-editor-field-id-' + fieldId),
                            settings = $('#hf-fields-settings-' + fieldId);

                    // Remove settings from sidebar.
                    if (settings.is(':visible')) {
                        document.getElementById('hf-add-fields-tab').click();
                    }
                    settings.remove();

                    $thisField.fadeOut('fast', function () {
                        var $section = $thisField.closest('.start_divider'),
                                type = $thisField.data('type'),
                                $adjacentFields = $thisField.siblings('li.hf-editor-form-field'),
                                $liWrapper;

                        if (!$adjacentFields.length) {
                            if ($thisField.is('.hf-editor-field-type-end_divider')) {
                                $adjacentFields.length = $thisField.closest('li.hf-editor-form-field').siblings();
                            } else {
                                $liWrapper = $thisField.closest('ul.hf-editor-sorting').parent();
                            }
                        }

                        $thisField.remove();
                        if ($('#hf-editor-fields li').length === 0) {
                            document.getElementById('hf-editor-wrap').classList.remove('hf-editor-has-fields');
                        } else if ($section.length) {
                            hashFormBuilder.toggleOneSectionHolder($section);
                        }
                        if ($adjacentFields.length) {
                            hashFormBuilder.syncLayoutClasses($adjacentFields.first());
                        } else {
                            $liWrapper.remove();
                        }
                    });
                }
            });
        },

        toggleSectionHolder: function () {
            document.querySelectorAll('.start_divider').forEach(
                    function (divider) {
                        hashFormBuilder.toggleOneSectionHolder($(divider));
                    }
            );
        },

        addFieldClick: function () {
            /*jshint validthis:true */
            const $thisObj = $(this);
            // there is no real way to disable a <a> (with a valid href attribute) in HTML - https://css-tricks.com/how-to-disable-links/
            if ($thisObj.hasClass('disabled')) {
                return false;
            }

            $thisObj.parent('.hf-field-box').addClass('hf-added-field');

            const $button = $thisObj.closest('.hf-field-box');
            const fieldType = $button.attr('id');

            let hasBreak = 0;
            if ('summary' === fieldType) {
                hasBreak = $editorFieldsWrap.children('li[data-type="break"]').length > 0 ? 1 : 0;
            }

            var formId = document.getElementById('hf-form-id').value;
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'hashform_insert_field',
                    form_id: formId,
                    field_type: fieldType,
                    nonce: hashform_backend_js.nonce,
                },
                success: function (msg) {
                    document.getElementById('hf-editor-wrap').classList.add('hf-editor-has-fields');
                    const replaceWith = hashFormBuilder.wrapFieldLi(msg);
                    $editorFieldsWrap.append(replaceWith);
                    hashFormBuilder.afterAddField(msg, true);

                    replaceWith.each(
                            function () {
                                hashFormBuilder.makeDroppable(this.querySelector('ul.hf-editor-sorting'));
                                hashFormBuilder.makeDraggable(this.querySelector('.hf-editor-form-field'), '.hf-editor-move-action');
                            }
                    );
                    hashFormBuilder.maybeFixRangeSlider();
                },
                error: hashFormBuilder.handleInsertFieldError
            });
            return false;
        },

        stopFieldFocus: function (e) {
            e.preventDefault();
        },

        deselectFields: function (preventFieldGroups) {
            $('li.ui-state-default.selected').removeClass('selected');
            if (!preventFieldGroups) {
                hashFormBuilder.unselectFieldGroups();
            }
        },

        moveFieldSettings: function (singleField) {
            if (singleField === null)
                return;
            var classes = singleField.parentElement.classList;
            if (classes.contains('hf-editor-field-box') || classes.contains('divider_section_only')) {
                var endMarker = document.getElementById('hf-end-form-marker');
                buildForm.insertBefore(singleField, endMarker);
            }
        },

        debounce: function (func, wait = 100) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(
                        () => func.apply(this, args),
                        wait
                        );
            };
        },

        infoModal: function (msg) {
            var $info = hashFormBuilder.initModal('#hashform_info_modal', '400px');
            if ($info === false) {
                return false;
            }
            $('.hf-info-msg').html(msg);
            $info.dialog('open');
            return false;
        },

        handleFieldDrop: function (_, ui) {
            const draggable = ui.draggable[0];
            const placeholder = document.getElementById('hf-placeholder');

            if (!placeholder) {
                ui.helper.remove();
                hashFormBuilder.syncAfterDragAndDrop();
                return;
            }
            const $previousFieldContainer = ui.helper.parent();
            const previousSection = ui.helper.get(0).closest('ul.start_divider');
            const newSection = placeholder.closest('ul.hf-editor-sorting');

            if (draggable.classList.contains('hf-added-field')) {
                hashFormBuilder.insertNewFieldByDragging(draggable.id);
            } else {
                hashFormBuilder.moveFieldThatAlreadyExists(draggable, placeholder);
            }

            const previousSectionId = previousSection ? parseInt(previousSection.closest('.hf-editor-field-type-divider').getAttribute('data-fid')) : 0;
            const newSectionId = newSection.classList.contains('start_divider') ? parseInt(newSection.closest('.hf-editor-field-type-divider').getAttribute('data-fid')) : 0;

            placeholder.remove();
            ui.helper.remove();

            const $previousContainerFields = $previousFieldContainer.length ? hashFormBuilder.getFieldsInRow($previousFieldContainer) : [];
            hashFormBuilder.maybeUpdatePreviousFieldContainerAfterDrop($previousFieldContainer, $previousContainerFields);
            hashFormBuilder.maybeUpdateDraggableClassAfterDrop(draggable, $previousContainerFields);

            if (previousSectionId !== newSectionId) {
                hashFormBuilder.updateFieldAfterMovingBetweenSections($(draggable), previousSection);
            }
            hashFormBuilder.syncAfterDragAndDrop();
        },

        syncAfterDragAndDrop: function () {
            hashFormBuilder.fixUnwrappedListItems();
            hashFormBuilder.toggleSectionHolder();
            hashFormBuilder.maybeFixEndDividers();
            hashFormBuilder.maybeDeleteEmptyFieldGroups();
            hashFormBuilder.updateFieldOrder();

            const event = new Event('hashform_sync_after_drag_and_drop', {bubbles: false});
            document.dispatchEvent(event);
            hashFormBuilder.maybeFixRangeSlider();
        },

        fixUnwrappedListItems: function () {
            const lists = document.querySelectorAll('ul#hf-editor-fields, ul.start_divider');
            lists.forEach(
                    list => {
                        list.childNodes.forEach(
                                child => {
                                    if ('undefined' === typeof child.classList) {
                                        return;
                                    }

                                    if (child.classList.contains('hf-editor-field-type-end_divider')) {
                                        // Never wrap end divider in place.
                                        return;
                                    }

                                    if ('undefined' !== typeof child.classList && child.classList.contains('hf-editor-form-field')) {
                                        hashFormBuilder.wrapFieldLiInPlace(child);
                                    }
                                }
                        );
                    }
            );
        },

        toggleOneSectionHolder: function ($section) {
            var noSectionFields, $rows, length, index, sectionHasFields;
            if (!$section.length) {
                return;
            }

            $rows = $section.find('ul.hf-editor-sorting');
            sectionHasFields = false;
            length = $rows.length;
            for (index = 0; index < length; ++index) {
                if (0 !== hashFormBuilder.getFieldsInRow($($rows.get(index))).length) {
                    sectionHasFields = true;
                    break;
                }
            }

            noSectionFields = $section.parent().children('.hashform_no_section_fields').get(0);
            noSectionFields.classList.toggle('hashform_block', !sectionHasFields);
        },

        maybeFixEndDividers: function () {
            document.querySelectorAll('.hf-editor-field-type-end_divider').forEach(
                    endDivider => endDivider.parentNode.appendChild(endDivider)
            );
        },

        maybeDeleteEmptyFieldGroups: function () {
            document.querySelectorAll('li.form_field_box:not(.hf-editor-form-field)').forEach(
                    fieldGroup => !fieldGroup.children.length && fieldGroup.remove()
            );
        },

        updateFieldOrder: function () {
            var fields, fieldId, field, currentOrder, newOrder;
            $('#hf-editor-fields').each(function (i) {
                fields = $('li.hf-editor-field-box', this);
                for (i = 0; i < fields.length; i++) {
                    fieldId = fields[ i ].getAttribute('data-fid');
                    field = $('input[name="field_options[field_order_' + fieldId + ']"]');
                    currentOrder = field.val();
                    newOrder = i + 1;

                    if (currentOrder != newOrder) {
                        field.val(newOrder);
                        var singleField = document.getElementById('hf-fields-settings-' + fieldId);
                        hashFormBuilder.moveFieldSettings(singleField);
                        // hashFormBuilder.fieldUpdated();
                    }
                }
            });
        },

        setupTinyMceEventHandlers: function (editor) {
            editor.on('Change', function () {
                hashFormBuilder.handleTinyMceChange(editor);
            });
        },

        handleTinyMceChange: function (editor) {
            if (!hashFormBuilder.isTinyMceActive() || tinyMCE.activeEditor.isHidden()) {
                return;
            }

            editor.targetElm.value = editor.getContent();
            $(editor.targetElm).trigger('change');
        },

        isTinyMceActive: function () {
            var activeSettings, wrapper;

            activeSettings = document.querySelector('.hf-fields-settings:not(.hf-hidden)');
            if (!activeSettings) {
                return false;
            }

            wrapper = activeSettings.querySelector('.wp-editor-wrap');
            return null !== wrapper && wrapper.classList.contains('tmce-active');
        },

        // fieldUpdated: function () {
        //     if (!fieldsUpdated) {
        //         fieldsUpdated = 1;
        //         window.addEventListener('beforeunload', hashFormBuilder.confirmExit);
        //     }
        // },

        // confirmExit: function (event) {
        //     if (fieldsUpdated) {
        //         event.preventDefault();
        //         event.returnValue = '';
        //     }
        // },

        maybeFixRangeSlider: function () {
            setTimeout(() => {
                $(document).find('.hashform-range-input-selector').each(function () {
                    var newSlider = $(this);
                    var sliderValue = newSlider.val();
                    var sliderMinValue = parseFloat(newSlider.attr('min'));
                    var sliderMaxValue = parseFloat(newSlider.attr('max'));
                    var sliderStepValue = parseFloat(newSlider.attr('step'));
                    newSlider.prev('.hashform-range-slider').slider({
                        value: sliderValue,
                        min: sliderMinValue,
                        max: sliderMaxValue,
                        step: sliderStepValue,
                        range: 'min',
                        slide: function (e, ui) {
                            $(this).next().val(ui.value);
                        }
                    });
                })
            }, 1000);
        },

        wrapFieldLiInPlace: function (li) {
            const ul = hashFormBuilder.tag('ul', {
                className: 'hf-editor-grid-container hf-editor-sorting'
            });
            const wrapper = hashFormBuilder.tag('li', {
                className: 'hf-editor-field-box',
                child: ul
            });

            li.replaceWith(wrapper);
            ul.appendChild(li);

            hashFormBuilder.makeDroppable(ul);
            hashFormBuilder.makeDraggable(wrapper, '.hf-editor-move-action');
        },

        maybeUpdatePreviousFieldContainerAfterDrop: function ($previousFieldContainer, $previousContainerFields) {
            if (!$previousFieldContainer.length) {
                return;
            }

            if ($previousContainerFields.length) {
                hashFormBuilder.syncLayoutClasses($previousContainerFields.first());
            } else {
                hashFormBuilder.maybeDeleteAnEmptyFieldGroup($previousFieldContainer.get(0));
            }
        },

        maybeUpdateDraggableClassAfterDrop: function (draggable, $previousContainerFields) {
            if (0 !== $previousContainerFields.length || 1 !== hashFormBuilder.getFieldsInRow($(draggable.parentNode)).length) {
                hashFormBuilder.syncLayoutClasses($(draggable));
            }
        },

        maybeDeleteAnEmptyFieldGroup: function (previousFieldContainer) {
            const closestFieldBox = previousFieldContainer.closest('li.hf-editor-field-box');
            if (closestFieldBox && !closestFieldBox.classList.contains('hf-editor-field-type-divider')) {
                closestFieldBox.remove();
            }
        },

        determineIndexBasedOffOfMousePositionInList: function ($list, y) {
            const $items = $list.children().not('.hf-editor-field-type-end_divider');
            const length = $items.length;
            let index, item, itemTop, returnIndex;
            returnIndex = 0;
            for (index = length - 1; index >= 0; --index) {
                item = $items.get(index);
                itemTop = $(item).offset().top;
                if (y > itemTop) {
                    returnIndex = index;
                    if (y > itemTop + ($(item).outerHeight() / 2)) {
                        returnIndex = index + 1;
                    }
                    break;
                }
            }
            return returnIndex;
        },

        onDragOverDroppable: function (event, ui) {
            const droppable = event.target;
            const draggable = ui.draggable[0];
            if (!hashFormBuilder.allowDrop(draggable, droppable)) {
                droppable.classList.remove('hf-dropabble');
                $(droppable).parents('ul.hf-editor-sorting').addClass('hf-dropabble');
                return;
            }
            document.querySelectorAll('.hf-dropabble').forEach(droppable => droppable.classList.remove('hf-dropabble'));
            droppable.classList.add('hf-dropabble');
            $(droppable).parents('ul.hf-editor-sorting').addClass('hf-dropabble');
        },

        onDraggableLeavesDroppable: function (event) {
            const droppable = event.target;
            droppable.classList.remove('hf-dropabble');
        },

        syncLayoutClasses: function ($item, type) {
            var $fields, size, layoutClasses, classToAddFunction;
            if ('undefined' === typeof type) {
                type = 'even';
            }
            $fields = $item.parent().children('li.hf-editor-form-field, li.hf-field-loading').not('.hf-editor-field-type-end_divider');
            size = $fields.length;
            layoutClasses = hashFormBuilder.getLayoutClasses();

            if ('even' === type && 5 !== size) {
                $fields.each(hashFormBuilder.getSyncLayoutClass(layoutClasses, hashFormBuilder.getEvenClassForSize(size)));
            } else if ('clear' === type) {
                $fields.each(hashFormBuilder.getSyncLayoutClass(layoutClasses, ''));
            } else {
                if (-1 !== ['left', 'right', 'middle', 'even'].indexOf(type)) {
                    classToAddFunction = function (index) {
                        return hashFormBuilder.getClassForBlock(size, type, index);
                    };
                } else {
                    classToAddFunction = function (index) {
                        var size = type[ index ];
                        return hashFormBuilder.getLayoutClassForSize(size);
                    };
                }
                $fields.each(hashFormBuilder.getSyncLayoutClass(layoutClasses, classToAddFunction));
            }
        },

        getSyncLayoutClass: function (layoutClasses, classToAdd) {
            return function (itemIndex) {
                var currentClassToAdd, length, layoutClassIndex, currentClass, activeLayoutClass, fieldId, layoutClassesInput;
                currentClassToAdd = 'function' === typeof classToAdd ? classToAdd(itemIndex) : classToAdd;
                length = layoutClasses.length;
                activeLayoutClass = false;
                for (layoutClassIndex = 0; layoutClassIndex < length; ++layoutClassIndex) {
                    currentClass = layoutClasses[ layoutClassIndex ];
                    if (this.classList.contains(currentClass)) {
                        activeLayoutClass = currentClass;
                        break;
                    }
                }

                fieldId = this.dataset.fid;
                if ('undefined' === typeof fieldId) {
                    // we are syncing the drag/drop placeholder before the actual field has loaded.
                    // this will get called again afterward and the input will exist then.
                    this.classList.add(currentClassToAdd);
                    return;
                }

                hashFormBuilder.moveFieldSettings(document.getElementById('hf-fields-settings-' + fieldId));
                var gridClassInput = document.getElementById('hf-grid-class-' + fieldId);

                if (null === gridClassInput) {
                    // not every field type has a layout class input.
                    return;
                }

                gridClassInput.value = currentClassToAdd;
                hashFormBuilder.changeFieldClass(document.getElementById('hf-editor-field-id-' + fieldId), currentClassToAdd);
            };
        },

        getLayoutClasses: function () {
            return ['hf-grid-1', 'hf-grid-2', 'hf-grid-3', 'hf-grid-4', 'hf-grid-5', 'hf-grid-6', 'hf-grid-7', 'hf-grid-8', 'hf-grid-9', 'hf-grid-10', 'hf-grid-11', 'hf-grid-12'];
        },

        getSectionForFieldPlacement: function (currentItem) {
            var section = '';
            if (typeof currentItem !== 'undefined' && !currentItem.hasClass('hf-editor-field-type-divider')) {
                section = currentItem.closest('.hf-editor-field-type-divider');
            }
            return section;
        },

        getFormIdForFieldPlacement: function (section) {
            var formId = '';
            if (typeof section[0] !== 'undefined') {
                var sDivide = section.children('.start_divider');
                sDivide.children('.hf-editor-field-type-end_divider').appendTo(sDivide);
                if (typeof section.attr('data-formid') !== 'undefined') {
                    var fieldId = section.attr('data-fid');
                    formId = $('input[name="field_options[form_select_' + fieldId + ']"]').val();
                }
            }
            if (typeof formId === 'undefined' || formId === '') {
                formId = currentFormId;
            }
            return formId;
        },

        getSectionIdForFieldPlacement: function (section) {
            var sectionId = 0;
            if (typeof section[0] !== 'undefined') {
                sectionId = section.attr('id').replace('hf-editor-field-id-', '');
            }

            return sectionId;
        },

        updateFieldAfterMovingBetweenSections: function (currentItem, previousSection) {
            if (!currentItem.hasClass('hf-editor-form-field')) {
                hashFormBuilder.getFieldsInRow($(currentItem.get(0).firstChild)).each(
                        function () {
                            hashFormBuilder.updateFieldAfterMovingBetweenSections($(this), previousSection);
                        }
                );
                return;
            }
            const fieldId = currentItem.attr('id').replace('hf-editor-field-id-', '');
            const section = hashFormBuilder.getSectionForFieldPlacement(currentItem);
            const formId = hashFormBuilder.getFormIdForFieldPlacement(section);
            const sectionId = hashFormBuilder.getSectionIdForFieldPlacement(section);
            const previousFormId = previousSection ? hashFormBuilder.getFormIdForFieldPlacement($(previousSection.parentNode)) : 0;

            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'hashform_update_field_after_move',
                    form_id: formId,
                    field: fieldId,
                    section_id: sectionId,
                    previous_form_id: previousFormId,
                    nonce: hashform_backend_js.nonce
                },
                success: function () {
                    hashFormBuilder.toggleSectionHolder();
                    hashFormBuilder.updateInSectionValue(fieldId, sectionId);
                }
            });
        },

        insertNewFieldByDragging: function (fieldType) {
            const placeholder = document.getElementById('hf-placeholder');
            const loadingID = fieldType.replace('|', '-') + '_' + hashFormBuilder.getAutoId();
            const loading = hashFormBuilder.tag('li', {
                id: loadingID,
                className: 'hf-wait hf-field-loading'
            });
            const $placeholder = $(loading);
            const currentItem = $(placeholder);
            const section = hashFormBuilder.getSectionForFieldPlacement(currentItem);
            const formId = hashFormBuilder.getFormIdForFieldPlacement(section);
            const sectionId = hashFormBuilder.getSectionIdForFieldPlacement(section);
            placeholder.parentNode.insertBefore(loading, placeholder);
            placeholder.remove();
            hashFormBuilder.syncLayoutClasses($placeholder);
            let hasBreak = 0;
            if ('summary' === fieldType) {
                hasBreak = $('.hf-field-loading#' + loadingID).prevAll('li[data-type="break"]').length ? 1 : 0;
            }
            jQuery.ajax({
                type: 'POST', url: ajaxurl,
                data: {
                    action: 'hashform_insert_field',
                    form_id: formId,
                    field_type: fieldType,
                    nonce: hashform_backend_js.nonce,
                },
                success: function (msg) {
                    let replaceWith;
                    document.getElementById('hf-editor-wrap').classList.add('hf-editor-has-fields');
                    const $siblings = $placeholder.siblings('li.hf-editor-form-field').not('.hf-editor-field-type-end_divider');
                    if (!$siblings.length) {
                        replaceWith = hashFormBuilder.wrapFieldLi(msg);
                    } else {
                        replaceWith = hashFormBuilder.msgAsObject(msg);
                        if (!$placeholder.get(0).parentNode.parentNode.classList.contains('ui-draggable')) {
                            hashFormBuilder.makeDraggable($placeholder.get(0).parentNode.parentNode, '.hf-editor-move-action');
                        }
                    }
                    $placeholder.replaceWith(replaceWith);
                    hashFormBuilder.updateFieldOrder();
                    hashFormBuilder.afterAddField(msg, false);
                    if ($siblings.length) {
                        hashFormBuilder.syncLayoutClasses($siblings.first());
                    }
                    hashFormBuilder.toggleSectionHolder();
                    if (!$siblings.length) {
                        hashFormBuilder.makeDroppable(replaceWith.get(0).querySelector('ul.hf-editor-sorting'));
                        hashFormBuilder.makeDraggable(replaceWith.get(0).querySelector('li.hf-editor-form-field'), '.hf-editor-move-action');
                    } else {
                        hashFormBuilder.makeDraggable(replaceWith.get(0), '.hf-editor-move-action');
                    }
                },
                error: hashFormBuilder.handleInsertFieldError
            });
        },

        moveFieldThatAlreadyExists: function (draggable, placeholder) {
            placeholder.parentNode.insertBefore(draggable, placeholder);
        },

        msgAsObject: function (msg) {
            const element = hashFormBuilder.div();
            element.innerHTML = msg;
            return $(element.innerHTML);
        },

        handleInsertFieldError: function (jqXHR, _, errorThrown) {
            hashFormBuilder.maybeShowInsertFieldError(errorThrown, jqXHR);
        },

        maybeShowInsertFieldError: function (errorThrown, jqXHR) {
            if (!jqXHRAborted(jqXHR)) {
                hashFormBuilder.infoModal(errorThrown + '. Please try again.');
            }
        },

        jqXHRAborted: function (jqXHR) {
            return jqXHR.status === 0 || jqXHR.readyState === 0;
        },

        getAutoId: function () {
            return ++autoId;
        },

        maybeRemoveHoverTargetOnMouseMove: function (event) {
            var elementFromPoint = document.elementFromPoint(event.clientX, event.clientY);
            if (null !== elementFromPoint && null !== elementFromPoint.closest('#hf-editor-fields')) {
                return;
            }
            hashFormBuilder.maybeRemoveGroupHoverTarget();
        },

        wrapFieldLi: function (field) {
            const wrapper = hashFormBuilder.div();
            if ('string' === typeof field) {
                wrapper.innerHTML = field;
            } else {
                wrapper.appendChild(field);
            }

            let result = $();
            Array.from(wrapper.children).forEach(
                    li => {
                        result = result.add(
                                $('<li>')
                                .addClass('hf-editor-field-box')
                                .html($('<ul>').addClass('hf-editor-grid-container hf-editor-sorting').append(li))
                                );
                    }
            );
            return result;
        },

        afterAddField: function (msg, addFocus) {
            var regex = /id="(\S+)"/,
                    match = regex.exec(msg),
                    field = document.getElementById(match[1]),
                    section = '#' + match[1] + '.hf-editor-field-type-divider ul.hf-editor-sorting.start_divider',
                    $thisSection = $(section),
                    toggled = false,
                    $parentSection;
            var type = field.getAttribute('data-type');

            hashFormBuilder.setupSortable(section);
            if ($thisSection.length) {
                $thisSection.parent('.hf-editor-field-box').children('.hashform_no_section_fields').addClass('hashform_block');
            } else {
                $parentSection = $(field).closest('ul.hf-editor-sorting.start_divider');
                if ($parentSection.length) {
                    hashFormBuilder.toggleOneSectionHolder($parentSection);
                    toggled = true;
                }
            }

            $(field).addClass('hf-newly-added');
            setTimeout(function () {
                field.classList.remove('hf-newly-added');
            }, 1000);

            if (addFocus) {
                var bounding = field.getBoundingClientRect(),
                        container = document.getElementById('hf-form-panel'),
                        inView = (bounding.top >= 0 &&
                                bounding.left >= 0 &&
                                bounding.right <= (window.innerWidth || document.documentElement.clientWidth) &&
                                bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight)
                                );

                if (!inView) {
                    container.scroll({
                        top: container.scrollHeight,
                        left: 0,
                        behavior: 'smooth'
                    });
                }

                if (toggled === false) {
                    hashFormBuilder.toggleOneSectionHolder($thisSection);
                }
            }

            hashFormBuilder.deselectFields();

            const addedEvent = new Event('hashform_added_field', {bubbles: false});
            addedEvent.hfField = field;
            addedEvent.hfSection = section;
            addedEvent.hfType = type;
            addedEvent.hfToggles = toggled;
            document.dispatchEvent(addedEvent);
        },

        getClassForBlock: function (size, type, index) {
            if ('even' === type) {
                return hashFormBuilder.getEvenClassForSize(size, index);
            } else if ('middle' === type) {
                if (3 === size) {
                    return 1 === index ? 'hf-grid-6' : 'hf-grid-3';
                }
                if (5 === size) {
                    return 2 === index ? 'hf-grid-4' : 'hf-grid-2';
                }
            } else if ('left' === type) {
                return 0 === index ? hashFormBuilder.getLargeClassForSize(size) : hashFormBuilder.getSmallClassForSize(size);
            } else if ('right' === type) {
                return index === size - 1 ? hashFormBuilder.getLargeClassForSize(size) : hashFormBuilder.getSmallClassForSize(size);
            }
            return 'hf-grid-12';
        },

        getEvenClassForSize: function (size, index) {
            if (-1 !== [2, 3, 4, 6].indexOf(size)) {
                return hashFormBuilder.getLayoutClassForSize(12 / size);
            }
            if (5 === size && 'undefined' !== typeof index) {
                return 0 === index ? 'hf-grid-4' : 'hf-grid-2';
            }
            return 'hf-grid-12';
        },

        getSmallClassForSize: function (size) {
            switch (size) {
                case 2:
                case 3:
                    return 'hf-grid-3';
                case 4:
                    return 'hf-grid-2';
                case 5:
                    return 'hf-grid-2';
                case 6:
                    return 'hf-grid-1';
            }
            return 'hf-grid-12';
        },

        getLargeClassForSize: function (size) {
            switch (size) {
                case 2:
                    return 'hf-grid-9';
                case 3:
                case 4:
                    return 'hf-grid-6';
                case 5:
                    return 'hf-grid-4';
                case 6:
                    return 'hf-grid-7';
            }
            return 'hf-grid-12';
        },

        getLayoutClassForSize: function (size) {
            return 'hf-grid-' + size;
        },

        resetOptionTextDetails: function () {
            $('.hf-fields-settings ul input[type="text"][name^="field_options[options_"]').filter('[data-value-on-load]').removeAttr('data-value-on-load');
            $('input[type="hidden"][name^=optionmap]').remove();
        },

        addBlankSelectOption: function (field, placeholder) {
            var opt = document.createElement('option'),
                    firstChild = field.firstChild;

            opt.value = '';
            opt.innerHTML = placeholder;
            if (firstChild !== null) {
                field.insertBefore(opt, firstChild);
                field.selectedIndex = 0;
            } else {
                field.appendChild(opt);
            }
        },

        getImageLabel: function (label, showLabelWithImage, imageUrl, fieldType) {
            var imageLabelClass, fullLabel,
                    originalLabel = label;

            fullLabel = '<div class="hf-field-is-image">';
            fullLabel += '<span class="hf-field-is-checked mdi-check-circle"></span>';
            if (imageUrl) {
                fullLabel += '<img src="' + imageUrl + '" alt="' + originalLabel + '" />';
            }
            fullLabel += '</div>';
            fullLabel += '<div class="hf-field-is-label">' + originalLabel + '</div>';

            imageLabelClass = showLabelWithImage ? ' hf-field-is-has-label' : '';

            return ('<div class="hf-field-is-container' + imageLabelClass + '">' + fullLabel + '</div>');
        },

        getImageUrlFromInput: function (optVal) {
            var img, wrapper = $(optVal).closest('li').find('.hf-is-image-preview');

            if (!wrapper.length) {
                return '';
            }

            img = wrapper.find('img');
            if (!img.length) {
                return '';
            }

            return img.attr('src');
        },

        getChecked: function (id) {
            var field = $('.' + id);

            if (field.length === 0) {
                return false;
            }

            var checkbox = field.siblings('.hf-choice-input');
            return checkbox.length && checkbox.prop('checked');
        },

        /* Change the classes in the builder */
        changeFieldClass: function (field, setting) {
            var classes = field.className.split(' ');
            var filteredClasses = classes.filter(function (value, index, arr) {
                return value.indexOf('hf-grid-');
            });
            filteredClasses.push(setting);
            field.className = filteredClasses.join(' ');
        },

        bindClickForDialogClose: function ($modal) {
            const closeModal = function () {
                $modal.dialog('close');
            };
            $('.ui-widget-overlay').on('click', closeModal);
            $modal.on('click', 'a.dismiss', closeModal);
        },

        removeWPUnload: function () {
            window.onbeforeunload = null;
            var w = $(window);
            w.off('beforeunload.widgets');
            w.off('beforeunload.edit-post');
        },

        maybeAddSaveAndDragIcons: function (fieldId) {
            var fieldOptions = document.querySelectorAll(`[id^=hf-option-list-${fieldId}-]`);
            // return if there are no options.
            if (fieldOptions.length < 2) {
                return;
            }

            let options = [...fieldOptions].slice(1);
            options.forEach((li, _key) => {
                if (li.classList.contains('hashform_other_option')) {
                    return;
                }
            });
        },

    }

    $(function () {
        hashFormBuilder.init();
    });

})(jQuery);
