import DocumentService from '@typo3/core/document-service.js'
import RegularEvent from '@typo3/core/event/regular-event.js';
import DebounceEvent from '@typo3/core/event/debounce-event.js';
import AjaxRequest from '@typo3/core/ajax/ajax-request.js';

DocumentService.ready().then(function () {
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
