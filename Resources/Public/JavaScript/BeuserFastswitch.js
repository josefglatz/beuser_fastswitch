import DocumentService from '@typo3/core/document-service.js'
import RegularEvent from '@typo3/core/event/regular-event.js';
import DebounceEvent from '@typo3/core/event/debounce-event.js';
import AjaxRequest from '@typo3/core/ajax/ajax-request.js';
import Modal from '@typo3/backend/modal.js';

DocumentService.ready().then(function () {
  new RegularEvent('shown.bs.dropdown', function (e) {
    if (e.target.closest('.tx-beuser-fastswitch') !== null) {
      const searchMask = document.querySelector('#beuser-fastswitch-search-mask');
      if (searchMask !== null) {
        searchMask.focus();
        searchMask.select();
      }
    }
  }).bindTo(document);

  // Registered in the capture phase: Bootstrap's delegated dropdown handlers
  // are capture-phase on document and stop propagation for arrow keys inside
  // an open menu. Being registered later on the same node, this listener still
  // runs right after Bootstrap's, so the focus set here wins.
  new RegularEvent('keydown', function (e) {
    const toolbarItem = e.target.closest('.tx-beuser-fastswitch');
    if (toolbarItem === null || !['ArrowDown', 'ArrowUp', 'Enter'].includes(e.key)) {
      return;
    }
    const searchMask = document.querySelector('#beuser-fastswitch-search-mask');
    const items = Array.from(toolbarItem.querySelectorAll('.beuser-fastswitch__useritem'));
    const currentItem = e.target.closest('.beuser-fastswitch__useritem');

    if (e.key === 'Enter') {
      // Switch to the focused user — or to the first match when pressing
      // Enter in the search field. Inactive users render no switch button.
      const item = currentItem !== null ? currentItem : (e.target === searchMask ? items[0] : null);
      const switchUserButton = item ? item.querySelector('typo3-backend-switch-user button') : null;
      if (switchUserButton !== null) {
        e.preventDefault();
        switchUserButton.click();
      }
      return;
    }

    const index = items.indexOf(currentItem);
    if (e.key === 'ArrowDown' && (currentItem !== null || e.target === searchMask)) {
      const next = currentItem === null ? items[0] : items[index + 1];
      if (next !== undefined) {
        e.preventDefault();
        e.stopImmediatePropagation();
        next.focus();
      }
    } else if (e.key === 'ArrowUp' && currentItem !== null) {
      e.preventDefault();
      e.stopImmediatePropagation();
      if (index > 0) {
        items[index - 1].focus();
      } else if (searchMask !== null) {
        searchMask.focus();
      }
    }
  }, true).bindTo(document);

  new RegularEvent('click', function (e, target) {
    e.preventDefault();
    const modal = Modal.advanced({
      type: Modal.types.iframe,
      content: target.href,
      title: target.title,
      size: Modal.sizes.full,
    });
    modal.addEventListener('typo3-modal-shown', function () {
      const iframe = modal.querySelector('iframe');
      iframe.addEventListener('load', function () {
        let pathname = '';
        try {
          pathname = iframe.contentWindow.location.pathname;
        } catch (err) {
          return;
        }
        // The edit link carries returnUrl={be:moduleLink(route: 'dummy')} (path
        // /typo3/empty), so close/save&close navigates the iframe there.
        if (pathname.endsWith('/empty')) {
          modal.hideModal();
        }
      });
    });
  }).delegateTo(document, '.tx-beuser-fastswitch a.beuser-fastswitch__useritem-title');

  new RegularEvent('submit', function (e) {
    e.preventDefault();
  }).bindTo(document.querySelector('#beuser-fastswitch-search-form'));

  new DebounceEvent('input', function (e) {
    const searchValue = e.target.value;
    const resultContainer = document.querySelector('#beuser-fastswitch-ajax-result');

    resultContainer.replaceChildren();

    let request = new AjaxRequest(TYPO3.settings.ajaxUrls['beuser_fastswitch_backend_userlookup']);
    if (searchValue.length >= 1) {
      request = request.withQueryArguments({search: searchValue});
    }

    request.get().then(async function (response) {
      resultContainer.replaceChildren(document.createRange().createContextualFragment(await response.resolve('text/html')));
    });
  }, 250).bindTo(document.querySelector('#beuser-fastswitch-search-mask'));
});
