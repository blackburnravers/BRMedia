/*! Plyr v3.7.8 - Custom Build | MIT License | https://plyr.io */
!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?module.exports=e():"function"==typeof define&&define.amd?define(e):(t="undefined"!=typeof globalThis?globalThis:t||self).Plyr=e()}(this,(function(){/*! Bundled license information... */
// Custom configuration
const defaultConfig = {
  controls: ['play', 'progress', 'current-time', 'mute', 'volume'],
  settings: ['speed'],
  keyboard: { focused: true, global: false },
  tooltips: { controls: true, seek: true }
};

export default class Plyr {
  constructor(target, config) {
    this.player = new _Plyr(target, { ...defaultConfig, ...config });
  }
}
// Rest of the plyr implementation...
}));