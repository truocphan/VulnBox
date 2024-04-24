(function ($, localized) {
	var mto = {
		currentDocType: localized.is_elementor_template
			? elementor.config.document.type
			: '',
		docTypes: {
			courseArchivePage: 'masteriyo-course-archive-page',
			singleCoursePage: 'masteriyo-single-course-page',
		},

		init: function () {
			mto.initElementorComponents();
			mto.initDocumentLoadHandler();
		},

		initElementorComponents: function () {
			$e.components.register(new MasteriyoLibraryComponent());
		},

		initDocumentLoadHandler: function () {
			elementor.on('document:loaded', (isFirstLoad) => {
				mto.maybeOpenLibraryModal();
				mto.initLibraryModalOpenBtn();
			});
		},

		initLibraryModalOpenBtn: function () {
			if (mto.isMasteriyoDocumentType()) {
				var previewIframe = window['elementor-preview-iframe'];
				var previewWindow = previewIframe.contentWindow;

				if (
					previewWindow &&
					!previewWindow.jQuery('.masteriyo-templates-button').length
				) {
					mto.addLibraryModalOpenBtn(previewWindow);
				}
			}
		},

		addLibraryModalOpenBtn: function (previewWindow) {
			previewWindow
				.jQuery('.elementor-add-template-button')
				.after(localized.library_btn_template);

			previewWindow
				.jQuery(previewWindow.document.body)
				.on('click', '.masteriyo-templates-button', function () {
					mto.openLibraryModal();
				});
		},

		openLibraryModal: function () {
			$e.components.components['masteriyo-library'].open();
		},

		maybeOpenLibraryModal: function () {
			if (mto.isMasteriyoDocumentType() && mto.isEmptyDocument()) {
				mto.openLibraryModal();
			}
		},

		isMasteriyoDocumentType: function () {
			return Object.values(mto.docTypes).includes(mto.currentDocType);
		},

		isEmptyDocument: function () {
			return !elementor.config.document.elements?.length;
		},

		importWidgetsTemplate: function (template) {
			var targetContainer = elementor.getPreviewContainer();
			var index = undefined;

			template.forEach((model) => {
				// If is inner create section for `inner-section`.
				if (model.isInner) {
					var section = $e.run('document/elements/create', {
						container: targetContainer,
						model: {
							elType: 'section',
						},
						columns: 1,
						options: {
							at: index,
							edit: false,
						},
					});

					// `targetContainer` = first column at `section`.
					targetContainer = section.view.children.findByIndex(0).getContainer();
				}

				$e.run('document/elements/create', {
					containers: [targetContainer],
					model,
					options: { at: index, clone: true, edit: false },
				});
			});
		},
	};

	class LogoView extends Marionette.ItemView {
		getTemplate() {
			return '#tmpl-masteriyo-templates-modal__header__logo';
		}

		className() {
			return 'elementor-templates-modal__header__logo';
		}

		events() {
			return {
				click: 'onClick',
			};
		}

		templateHelpers() {
			return {
				title: this.getOption('title'),
			};
		}

		onClick() {
			var clickCallback = this.getOption('click');

			if (clickCallback) {
				clickCallback();
			}
		}
	}

	var HeaderActionsView = Marionette.ItemView.extend({
		template: '#tmpl-masteriyo-template-library-header-actions',
		id: 'masteriyo-template-library-header-actions',
		ui: {
			import: '#masteriyo-template-library-header-import',
		},
		events: {
			'click @ui.import': 'onImportClick',
		},

		onImportClick() {
			if (mto.currentDocType === mto.docTypes.courseArchivePage) {
				mto.importWidgetsTemplate(localized.page_templates.course_archive_page);
			} else if (mto.currentDocType === mto.docTypes.singleCoursePage) {
				mto.importWidgetsTemplate(localized.page_templates.single_course_page);
			}
			$('.elementor-templates-modal__header__close').click();
		},
	});

	var SingleCoursePagePreviewView = Marionette.ItemView.extend({
		template: '#tmpl-masteriyo-single-course-page-preview',
		id: 'masteriyo-single-course-page-preview',
	});

	var CourseArchivePagePreviewView = Marionette.ItemView.extend({
		template: '#tmpl-masteriyo-course-archive-page-preview',
		id: 'masteriyo-course-archive-page-preview',
	});

	var ModalLayoutView = elementorModules.common.views.modal.Layout.extend({
		getModalOptions() {
			return {
				id: 'masteriyo-template-library-modal',
			};
		},

		showLogo() {
			this.getHeaderView().logoArea.show(new LogoView(this.getLogoOptions()));
		},

		getLogoOptions() {
			return {
				title: 'Import default layout?',
			};
		},

		setHeaderDefaultParts() {
			this.getHeaderView().tools.show(new HeaderActionsView());
			this.showLogo();
		},

		showPreviewView() {
			if (mto.currentDocType === mto.docTypes.singleCoursePage) {
				this.modalContent.show(new SingleCoursePagePreviewView());
			} else if (mto.currentDocType === mto.docTypes.courseArchivePage) {
				this.modalContent.show(new CourseArchivePagePreviewView());
			}
		},
	});

	class MasteriyoLibraryComponent extends elementorCommon.api.modules
		.ComponentModalBase {
		__construct(args) {
			super.__construct(args);
			$e.data.deleteCache(this, 'masteriyo-library'); // Remove whole component cache data.
		}

		getNamespace() {
			return 'masteriyo-library';
		}

		open() {
			super.open();
			this.layout.setHeaderDefaultParts();
			this.layout.showPreviewView();
			return true;
		}

		close() {
			if (!super.close()) {
				return false;
			}
			this.manager.modalConfig = {};
			return true;
		}

		getModalLayout() {
			return ModalLayoutView;
		}
	}

	mto.init();
})(jQuery, _MASTERIYO_ELEMENTOR_EDITOR_);
