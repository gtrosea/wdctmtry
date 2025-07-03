<script type="text/javascript">
	(function($) {
		var dragging, placeholders = $();
		$.fn.sortable = function(options) {
			var method = String(options);
			options = $.extend({
				connectWith: false
			}, options);
			return this.each(function() {
				if (/^(enable|disable|destroy)$/.test(method)) {
					var items = $(this).children($(this).data('items')).attr('draggable', method == 'enable');
					if (method == 'destroy') {
						items.add(this).removeData('connectWith items')
							.off('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
					}
					return;
				}
				var isHandle, index, items = $(this).children(options.items);
				var placeholder = $('<' + (/^(ul|ol)$/i.test(this.tagName) ? 'li' : 'div') + ' class="sortable-placeholder">');
				items.find(options.handle).mousedown(function() {
					isHandle = true;
				}).mouseup(function() {
					isHandle = false;
				});
				$(this).data('items', options.items)
				placeholders = placeholders.add(placeholder);
				if (options.connectWith) {
					$(options.connectWith).add(this).data('connectWith', options.connectWith);
				}
				items.attr('draggable', 'true').on('dragstart.h5s', function(e) {
					if (options.handle && !isHandle) {
						return false;
					}
					isHandle = false;
					var dt = e.originalEvent.dataTransfer;
					dt.effectAllowed = 'move';
					dt.setData('Text', 'dummy');
					index = (dragging = $(this)).addClass('sortable-dragging').index();
				}).on('dragend.h5s', function() {
					if (!dragging) {
						return;
					}
					dragging.removeClass('sortable-dragging').show();
					placeholders.detach();
					if (index != dragging.index()) {
						dragging.parent().trigger('sortupdate', {
							item: dragging
						});
					}
					dragging = null;
				}).not('a[href], img').on('selectstart.h5s', function() {
					this.dragDrop && this.dragDrop();
					return false;
				}).end().add([this, placeholder]).on('dragover.h5s dragenter.h5s drop.h5s', function(e) {
					if (!items.is(dragging) && options.connectWith !== $(dragging).parent().data('connectWith')) {
						return true;
					}
					if (e.type == 'drop') {
						e.stopPropagation();
						placeholders.filter(':visible').after(dragging);
						dragging.trigger('dragend.h5s');
						return false;
					}
					e.preventDefault();
					e.originalEvent.dataTransfer.dropEffect = 'move';
					if (items.is(this)) {
						if (options.forcePlaceholderSize) {
							placeholder.height(dragging.outerHeight());
						}
						dragging.hide();
						$(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
						placeholders.not(placeholder).detach();
					} else if (!placeholders.is(this) && !$(this).children(options.items).length) {
						placeholders.detach();
						$(this).append(placeholder);
					}
					return false;
				});
			});
		};
	})(jQuery);

	jQuery(document).ready(function($) {

		function applyCustome() {
			$('.modal-body .jet-fb-form-block .jet-form-builder-row').addClass('p-0 mb-5');
			$('.modal-body .jet-fb-form-block .jet-form-builder__label-text').addClass('form-label');
			$('.modal-body .jet-fb-form-block .jet-form-builder__label-text .jet-form-builder__required').addClass('text-danger');
			$('.modal-body .jet-fb-form-block input[type="text"], .modal-body .jet-fb-form-block input[type="url"], .modal-body .jet-fb-form-block input[type="email"], .modal-body .jet-fb-form-block input[type="number"], .modal-body .jet-fb-form-block input[type="datetime-local"], .modal-body .jet-fb-form-block input[type="date"], .modal-body .jet-fb-form-block textarea, .modal-body input[type="url"], .modal-body input[type="number"]').removeAttr('class').addClass('form-control form-control-solid').css({
				'border': '1px solid #C5C5C5',
			});
			$('.modal-body .jet-fb-form-block .jet-form-builder__desc, .modal-body .jet-fb-form-block .jet-form-builder-file-upload__message').addClass('form-text');
			$('.modal-body .jet-fb-form-block .jet-form-builder__submit').addClass('btn btn-primary');
			$('.modal-body .jet-fb-form-block .jet-form-builder-repeater__new').addClass('btn btn-sm btn-secondary');
		}

		applyCustome();

		// Initialize Select2 function
		function initSelect2() {
			$('.modal-body .jet-fb-form-block select').each(function() {
				// Check if Select2 is not already initialized
				if (!$(this).hasClass("select2-hidden-accessible")) {
					$(this)
						.removeAttr('class')
						.addClass('form-select form-select-solid')
						.attr('data-control', 'select2')
						.attr('data-placeholder', '<?php esc_html_e( 'Pilih opsi', 'weddingsaas' ); ?>')
						.attr('data-allow-clear', 'true')
						.prepend('<option></option>')
						.select2({
							dropdownParent: $(this).closest('.modal-body')
						});
				}
			});
		}

		// Call on page load
		initSelect2();

		// Reinitialize when new repeater row is added
		$('.modal-body').on('click', '.jet-form-builder-repeater__new', function() {
			applyCustome();
			initSelect2();
		});

		autosize($('.modal-body .jet-fb-form-block textarea'));
	});

	document.addEventListener('DOMContentLoaded', function () {

		$(document).on('click', '.jet-form-builder__submit', function (event) {
			const $button = $(this);
			const $form = $button.closest('form');
			const $modal = $button.closest('.modal');
			const modalId = $modal.attr('id');

			if (!$form.length) {
				return;
			}

			if (!$form[0].checkValidity()) {
				return;
			}

			const hasFileError = $form.find('.jet-form-builder-file-upload__file-invalid-marker[style*="display: block"]').length > 0;
			const FileErrorMessage = $form.find('.jet-form-builder-file-upload__file-invalid-marker[style*="display: block"]').attr('title') || '';
			const message = FileErrorMessage ? FileErrorMessage : 'Pastikan semua field terisi dengan benar.';

			if (hasFileError) {
				run_ajax_error(message).then((result) => {
					if (result.isConfirmed) {
						const modalId = $form.closest('.modal').attr('id');
						if (modalId) {
							$('#' + modalId + ' #form-overlay').addClass('d-none');
						}
						return;
					}
				});
			}

			if (modalId) {
				$('#' + modalId + ' #form-overlay').removeClass('d-none');
			} else {
				console.error('Modal ID not found');
			}
		});

		$(document).on('jet-form-builder/ajax/processing-error', handleError);

		function handleError(event, response, $form) {
			if (!response || !response.status || !response.message) {
				console.error('Invalid response structure:', response);
				return;
			}
			const message = $('<div>').html(response.message).text();
			if (response.status != 'success') {
				run_ajax_error(message).then((result) => {
					if (result.isConfirmed) {
						const modalId = $form.closest('.modal').attr('id');
						if (modalId) {
							$('#' + modalId + ' #form-overlay').addClass('d-none');
						} else {
							console.error('Modal ID not found for form:', $form);
						}
						$('.jet-form-builder-messages-wrap').hide();
					}
				});
			}
		}

		$(document).on('jet-form-builder/ajax/on-success', handleSuccess);

		function handleSuccess(event, response, $form) {
			if (!response || !response.status || !response.message) {
				console.error('Invalid response structure:', response);
				return;
			}

			const message = $('<div>').html(response.message).text();

			if (response.status === 'success') {
				run_ajax_success(message).then((result) => {
					if (result.isConfirmed) {
						const modalId = $form.closest('.modal').attr('id');
						if (modalId) {
							$('#' + modalId + ' #form-overlay').addClass('d-none');
							$('#' + modalId).modal('hide');
						} else {
							console.error('Modal ID not found for form:', $form);
						}
						$('.jet-form-builder-messages-wrap').hide();
					}
				});
			} else {
				run_ajax_error(message);
			}
		}
	});

</script>
