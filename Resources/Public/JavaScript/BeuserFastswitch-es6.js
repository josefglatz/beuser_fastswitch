import '@typo3/backend/input/clearable.js';

class BeuserFastswitch {
  constructor() {
      this.clearableElements = document.querySelectorAll(".t3js-clearable");
      this.initializeClearableElements();
  }

  initializeClearableElements() {
    this.clearableElements.forEach((e => e.clearable()));
  }
}

export default new BeuserFastswitch;
