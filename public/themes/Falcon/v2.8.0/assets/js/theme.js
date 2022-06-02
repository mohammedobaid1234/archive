"use strict";

var _this = this;

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/*-----------------------------------------------
|   Theme Configuration
-----------------------------------------------*/
var storage = {
  isDark: false
};
/*-----------------------------------------------
|   Utilities
-----------------------------------------------*/

var utils = function ($) {
  var grays = function grays() {
    var colors = {
      white: '#fff',
      100: '#f9fafd',
      200: '#edf2f9',
      300: '#d8e2ef',
      400: '#b6c1d2',
      500: '#9da9bb',
      600: '#748194',
      700: '#5e6e82',
      800: '#4d5969',
      900: '#344050',
      1000: '#232e3c',
      1100: '#0b1727',
      black: '#000'
    };

    if (storage.isDark) {
      colors = {
        white: '#0e1c2f',
        100: '#132238',
        200: '#061325',
        300: '#344050',
        400: '#4d5969',
        500: '#5e6e82',
        600: '#748194',
        700: '#9da9bb',
        800: '#b6c1d2',
        900: '#d8e2ef',
        1000: '#edf2f9',
        1100: '#f9fafd',
        black: '#fff'
      };
    }

    return colors;
  };

  var themeColors = function themeColors() {
    var colors = {
      primary: '#2c7be5',
      secondary: '#748194',
      success: '#00d27a',
      info: '#27bcfd',
      warning: '#f5803e',
      danger: '#e63757',
      light: '#f9fafd',
      dark: '#0b1727'
    };

    if (storage.isDark) {
      colors.light = grays()['100'];
      colors.dark = grays()['1100'];
    }

    return colors;
  };

  var pluginSettings = function pluginSettings() {
    var settings = {
      tinymce: {
        theme: 'oxide'
      },
      chart: {
        borderColor: 'rgba(255, 255, 255, 0.8)'
      }
    };

    if (storage.isDark) {
      settings.tinymce.theme = 'oxide-dark';
      settings.chart.borderColor = themeColors().primary;
    }

    return settings;
  };

  var Utils = {
    $window: $(window),
    $document: $(document),
    $html: $('html'),
    $body: $('body'),
    $main: $('main'),
    isRTL: function isRTL() {
      return this.$html.attr('dir') === 'rtl';
    },
    location: window.location,
    nua: navigator.userAgent,
    breakpoints: {
      xs: 0,
      sm: 576,
      md: 768,
      lg: 992,
      xl: 1200,
      xxl: 1540
    },
    colors: themeColors(),
    grays: grays(),
    offset: function offset(element) {
      var rect = element.getBoundingClientRect();
      var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      return {
        top: rect.top + scrollTop,
        left: rect.left + scrollLeft
      };
    },
    isScrolledIntoViewJS: function isScrolledIntoViewJS(element) {
      var windowHeight = window.innerHeight;
      var elemTop = this.offset(element).top;
      var elemHeight = element.offsetHeight;
      var windowScrollTop = window.scrollY;
      return elemTop <= windowScrollTop + windowHeight && windowScrollTop <= elemTop + elemHeight;
    },
    isScrolledIntoView: function isScrolledIntoView(el) {
      var $el = $(el);
      var windowHeight = this.$window.height();
      var elemTop = $el.offset().top;
      var elemHeight = $el.height();
      var windowScrollTop = this.$window.scrollTop();
      return elemTop <= windowScrollTop + windowHeight && windowScrollTop <= elemTop + elemHeight;
    },
    getCurrentScreanBreakpoint: function getCurrentScreanBreakpoint() {
      var _this2 = this;

      var currentScrean = '';
      var windowWidth = this.$window.width();
      $.each(this.breakpoints, function (index, value) {
        if (windowWidth >= value) {
          currentScrean = index;
        } else if (windowWidth >= _this2.breakpoints.xl) {
          currentScrean = 'xl';
        }
      });
      return {
        currentScrean: currentScrean,
        currentBreakpoint: this.breakpoints[currentScrean]
      };
    },
    hexToRgb: function hexToRgb(hexValue) {
      var hex;
      hexValue.indexOf('#') === 0 ? hex = hexValue.substring(1) : hex = hexValue; // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")

      var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
      var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex.replace(shorthandRegex, function (m, r, g, b) {
        return r + r + g + g + b + b;
      }));
      return result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : null;
    },
    rgbColor: function rgbColor(color) {
      if (color === void 0) {
        color = '#fff';
      }

      return "rgb(" + this.hexToRgb(color) + ")";
    },
    rgbaColor: function rgbaColor(color, alpha) {
      if (color === void 0) {
        color = '#fff';
      }

      if (alpha === void 0) {
        alpha = 0.5;
      }

      return "rgba(" + this.hexToRgb(color) + ", " + alpha + ")";
    },
    rgbColors: function rgbColors() {
      var _this3 = this;

      return Object.keys(this.colors).map(function (color) {
        return _this3.rgbColor(_this3.colors[color]);
      });
    },
    rgbaColors: function rgbaColors() {
      var _this4 = this;

      return Object.keys(this.colors).map(function (color) {
        return _this4.rgbaColor(_this4.colors[color]);
      });
    },
    settings: pluginSettings(_this),
    isIterableArray: function isIterableArray(array) {
      return Array.isArray(array) && !!array.length;
    },
    setCookie: function setCookie(name, value, expire) {
      var expires = new Date();
      expires.setTime(expires.getTime() + expire);
      document.cookie = name + "=" + value + ";expires=" + expires.toUTCString();
    },
    getCookie: function getCookie(name) {
      var keyValue = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
      return keyValue ? keyValue[2] : keyValue;
    },
    getBreakpoint: function getBreakpoint($element) {
      var classes = $element.attr('class');
      var breakpoint;

      if (classes) {
        breakpoint = this.breakpoints[classes.split(' ').filter(function (cls) {
          return cls.indexOf('navbar-expand-') === 0;
        }).pop().split('-').pop()];
      }

      return breakpoint;
    }
  };
  return Utils;
}(jQuery);
/*-----------------------------------------------
|   Detector
-----------------------------------------------*/


utils.$document.ready(function () {
  if (window.is.opera()) utils.$html.addClass('opera');
  if (window.is.mobile()) utils.$html.addClass('mobile');
  if (window.is.firefox()) utils.$html.addClass('firefox');
  if (window.is.safari()) utils.$html.addClass('safari');
  if (window.is.ios()) utils.$html.addClass('ios');
  if (window.is.iphone()) utils.$html.addClass('iphone');
  if (window.is.ipad()) utils.$html.addClass('ipad');
  if (window.is.ie()) utils.$html.addClass('ie');
  if (window.is.edge()) utils.$html.addClass('edge');
  if (window.is.chrome()) utils.$html.addClass('chrome');
  if (utils.nua.match(/puppeteer/i)) utils.$html.addClass('puppeteer');
  if (window.is.mac()) utils.$html.addClass('osx');
  if (window.is.windows()) utils.$html.addClass('windows');
  if (navigator.userAgent.match('CriOS')) utils.$html.addClass('chrome');
});
/*-----------------------------------------------
|   Emoji Picker
-----------------------------------------------*/

utils.$document.ready(function () {
  var Event = {
    FOCUS: 'focus'
  };
  var Selector = {
    EMOJIAREA: '.emojiarea'
  };
  var DATA_KEY = {
    OPTIONS: 'options'
  };
  var emojioneareas = $(Selector.EMOJIAREA); // Place the Blinking Text Cursor at the end of the editor text

  var placeCaretAtEnd = function placeCaretAtEnd(el) {
    if (!!window.getSelection && !!document.createRange) {
      var range = document.createRange();
      range.selectNodeContents(el);
      range.collapse(false);
      var sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(range);
    } else if (document.body.createTextRange) {
      var textRange = document.body.createTextRange();
      textRange.moveToElementText(el);
      textRange.collapse(false);
      textRange.select();
    }
  }; // EmojioneArea plugin call


  if (emojioneareas.length) {
    emojioneareas.each(function (item, value) {
      var $this = $(value);
      var options = $.extend({}, $this.data(DATA_KEY.OPTIONS));
      $this.emojioneArea(options); // // Call the caret position function on focus

      emojioneareas[item].emojioneArea.on(Event.FOCUS, function ($editor) {
        placeCaretAtEnd($editor.get(0));
      });
    });
  }
});
/*-----------------------------------------------
|   Animated progressbar
-----------------------------------------------*/

utils.$document.ready(function () {
  var toggle = $('#progress-toggle-animation');
  toggle.on('click', function () {
    return $('#progress-toggle').toggleClass('progress-bar-animated');
  });
});
/*-----------------------------------------------
|   Top navigation opacity on scroll
-----------------------------------------------*/

utils.$document.ready(function () {
  var $navbar = $('.navbar-theme');

  if ($navbar.length) {
    var windowHeight = utils.$window.height();
    utils.$window.scroll(function () {
      var scrollTop = utils.$window.scrollTop();
      var alpha = scrollTop / windowHeight * 2;
      alpha >= 1 && (alpha = 1);
      $navbar.css({
        'background-color': "rgba(11, 23, 39, " + alpha + ")"
      });
    }); // Fix navbar background color [after and before expand]

    var classList = $navbar.attr('class').split(' ');
    var breakpoint = classList.filter(function (c) {
      return c.indexOf('navbar-expand-') >= 0;
    })[0].split('navbar-expand-')[1];
    utils.$window.resize(function () {
      if (utils.$window.width() > utils.breakpoints[breakpoint]) {
        return $navbar.removeClass('bg-dark');
      }

      if (!$navbar.find('.navbar-toggler').hasClass('collapsed')) {
        return $navbar.addClass('bg-dark');
      }

      return null;
    }); // Top navigation background toggle on mobile

    $navbar.on('show.bs.collapse hide.bs.collapse', function (e) {
      $(e.currentTarget).toggleClass('bg-dark');
    });
  }
});
/*-----------------------------------------------
|   Select menu [bootstrap 4]
-----------------------------------------------*/

utils.$document.ready(function () {
  // https://getbootstrap.com/docs/4.0/getting-started/browsers-devices/#select-menu
  // https://github.com/twbs/bootstrap/issues/26183
  window.is.android() && $('select.form-control').removeClass('form-control').css('width', '100%');
});
/*-----------------------------------------------
|   Bootstrap Wizard
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selector = {
    DATA_WIZARD: '[data-wizard]',
    PREVIOUS_BUTTON: '.previous .btn',
    TAB_PANE: '.tab-pane',
    FORM_VALIDATION: '.form-validation',
    NAV_ITEM_CIRCLE: '.nav-item-circle',
    NAV_ITEM: '.nav-item',
    NAV_LINK: '.nav-link',
    WIZARD_LOTTIE: '.wizard-lottie'
  };
  var ClassName = {
    ACTIVE: 'active',
    DONE: 'done',
    NAV: 'nav'
  };
  var DATA_KEY = {
    OPTIONS: 'options',
    WIZARD_STATE: 'wizard-state',
    CONTROLLER: 'controller',
    ERROR_MODAL: 'error-modal'
  };
  var wizards = $(Selector.DATA_WIZARD);

  var isFormValidate = function isFormValidate($currentTab) {
    var $currentTabForms = $currentTab.find(Selector.FORM_VALIDATION);
    var isValidate = true;
    $currentTabForms.each(function (i, v) {
      isValidate = $(v).valid();
      return isValidate;
    });
    return isValidate;
  };

  !!wizards.length && wizards.each(function (index, value) {
    var $this = $(value);
    var controller = $this.data(DATA_KEY.CONTROLLER);
    var $controller = $(controller);
    var $buttonPrev = $controller.find(Selector.PREVIOUS_BUTTON);
    var $modal = $($this.data(DATA_KEY.ERROR_MODAL));
    var $lottie = $(value).find(Selector.WIZARD_LOTTIE);
    var options = $.extend({
      container: value.querySelector(Selector.WIZARD_LOTTIE),
      renderer: 'svg',
      loop: true,
      autoplay: false,
      name: 'Hello World'
    }, $lottie.data(DATA_KEY.OPTIONS));
    var animation = window.bodymovin.loadAnimation(options);
    $this.bootstrapWizard({
      tabClass: ClassName.NAV,
      onNext: function onNext(tab, navigation, idx) {
        var $currentTab = $this.find(Selector.TAB_PANE).eq(idx - 1);

        var data = {
          validate: isFormValidate($currentTab),
          tab: tab,
          navigation: navigation,
          idx: idx
        };

        if(data.validate){
          $this.trigger('utils.wizard.onNext', data);
        }

        return data.validate;
      },
      onTabClick: function onTabClick(tab, navigation, idx, clickedIndex) {
        var stepDone = $this.find(".nav-item:nth-child(" + (clickedIndex + 1) + ") .nav-link").data(DATA_KEY.WIZARD_STATE);

        if (stepDone === 'done') {
          $modal.modal('show');
          return false;
        }

        if (clickedIndex <= idx) {
          return true;
        }

        var isValid = true;
        $this.find(Selector.TAB_PANE).each(function (tabIndex, tabValue) {
          if (tabIndex < clickedIndex) {
            $this.bootstrapWizard('show', tabIndex);
            isValid = isFormValidate($(tabValue));
          }

          return isValid;
        });
        return isValid;
      },
      onTabShow: function onTabShow(tab, navigation, idx) {
        var length = navigation.find('li').length - 1;
        idx === 0 ? $buttonPrev.hide() : $buttonPrev.show();
        idx === length && setTimeout(function () {
          return animation.play();
        }, 300);
        $this.find(Selector.NAV_LINK).removeClass(ClassName.DONE);
        $this.find(Selector.NAV_ITEM).each(function (i, v) {
          var link = $(v).find(Selector.NAV_LINK);

          if (idx === length && !link.hasClass(ClassName.ACTIVE)) {
            link.attr('data-wizard-state', 'done');
          }

          if (!link.hasClass(ClassName.ACTIVE)) {
            link.addClass(ClassName.DONE);
            return true;
          }

          if (idx === length) {
            link.addClass(ClassName.DONE);
            $controller.hide();
          }

          return false;
        });
      }
    });
  });
});
/*-----------------------------------------------
|   Bulk Actions
-----------------------------------------------*/

utils.$document.ready(function () {
  var checkboxBulkSelects = $('.checkbox-bulk-select');

  if (checkboxBulkSelects.length) {
    var Event = {
      CLICK: 'click'
    };
    var Selector = {
      CHECKBOX_BULK_SELECT_CHECKBOX: '.checkbox-bulk-select-target'
    };
    var ClassName = {
      D_NONE: 'd-none'
    };
    var DATA_KEY = {
      CHECKBOX_BODY: 'checkbox-body',
      CHECKBOX_ACTIONS: 'checkbox-actions',
      CHECKBOX_REPLACED_ELEMENT: 'checkbox-replaced-element'
    };
    var Attribute = {
      CHECKED: 'checked',
      INDETERMINATE: 'indeterminate'
    };
    checkboxBulkSelects.each(function (index, value) {
      var checkboxBulkAction = $(value);
      var bulkActions = $(checkboxBulkAction.data(DATA_KEY.CHECKBOX_ACTIONS));
      var replacedElement = $(checkboxBulkAction.data(DATA_KEY.CHECKBOX_REPLACED_ELEMENT));
      var rowCheckboxes = $(checkboxBulkAction.data(DATA_KEY.CHECKBOX_BODY)).find(Selector.CHECKBOX_BULK_SELECT_CHECKBOX);
      checkboxBulkAction.on(Event.CLICK, function () {
        if (checkboxBulkAction.attr(Attribute.INDETERMINATE) === Attribute.INDETERMINATE) {
          bulkActions.addClass(ClassName.D_NONE);
          replacedElement.removeClass(ClassName.D_NONE);
          checkboxBulkAction.prop(Attribute.INDETERMINATE, false).attr(Attribute.INDETERMINATE, false);
          checkboxBulkAction.prop(Attribute.CHECKED, false).attr(Attribute.CHECKED, false);
          rowCheckboxes.prop(Attribute.CHECKED, false).attr(Attribute.CHECKED, false);
        } else {
          bulkActions.toggleClass(ClassName.D_NONE);
          replacedElement.toggleClass(ClassName.D_NONE);

          if (checkboxBulkAction.attr(Attribute.CHECKED)) {
            checkboxBulkAction.prop(Attribute.CHECKED, false).attr(Attribute.CHECKED, false);
          } else {
            checkboxBulkAction.prop(Attribute.CHECKED, true).attr(Attribute.CHECKED, true);
          }

          rowCheckboxes.each(function (i, v) {
            var $this = $(v);

            if ($this.attr(Attribute.CHECKED)) {
              $this.prop(Attribute.CHECKED, false).attr(Attribute.CHECKED, false);
            } else {
              $this.prop(Attribute.CHECKED, true).attr(Attribute.CHECKED, true);
            }
          });
        }
      });
      rowCheckboxes.on(Event.CLICK, function (e) {
        var $this = $(e.target);

        if ($this.attr(Attribute.CHECKED)) {
          $this.prop(Attribute.CHECKED, false).attr(Attribute.CHECKED, false);
        } else {
          $this.prop(Attribute.CHECKED, true).attr(Attribute.CHECKED, true);
        }

        rowCheckboxes.each(function (i, v) {
          var $elem = $(v);

          if ($elem.attr(Attribute.CHECKED)) {
            checkboxBulkAction.prop(Attribute.INDETERMINATE, true).attr(Attribute.INDETERMINATE, Attribute.INDETERMINATE);
            bulkActions.removeClass(ClassName.D_NONE);
            replacedElement.addClass(ClassName.D_NONE);
            return false;
          }

          if (i === checkboxBulkAction.length) {
            checkboxBulkAction.prop(Attribute.INDETERMINATE, false).attr(Attribute.INDETERMINATE, false);
            checkboxBulkAction.prop(Attribute.CHECKED, false).attr(Attribute.CHECKED, false);
            bulkActions.addClass(ClassName.D_NONE);
            replacedElement.removeClass(ClassName.D_NONE);
          }

          return true;
        });
      });
    });
  }
});
/*-----------------------------------------------
|   Chart
-----------------------------------------------*/

utils.$document.ready(function () {
  /*-----------------------------------------------
  |   Helper functions and Data
  -----------------------------------------------*/
  var chartData = [3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5, 8, 9, 7, 9, 3, 2, 3, 8, 4, 6, 2, 6, 4, 3, 3, 8, 3, 2, 7, 9, 5, 0, 2, 8, 8, 4, 1, 9, 7];
  var labels = ['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM', '6:00 PM', '7:00 PM', '8:00 PM'];
  /*-----------------------------------------------
  |   Chart Initialization
  -----------------------------------------------*/

  var newChart = function newChart(chart, config) {
    var ctx = chart.getContext('2d');
    return new window.Chart(ctx, config);
  };
  /*-----------------------------------------------
  |   Line Chart
  -----------------------------------------------*/


  var chartLine = document.getElementById('chart-line');

  if (chartLine) {
    var getChartBackground = function getChartBackground(chart) {
      var ctx = chart.getContext('2d');

      if (storage.isDark) {
        var _gradientFill = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);

        _gradientFill.addColorStop(0, utils.rgbaColor(utils.colors.primary, 0.5));

        _gradientFill.addColorStop(1, 'transparent');

        return _gradientFill;
      }

      var gradientFill = ctx.createLinearGradient(0, 0, 0, 250);
      gradientFill.addColorStop(0, 'rgba(255, 255, 255, 0.3)');
      gradientFill.addColorStop(1, 'rgba(255, 255, 255, 0)');
      return gradientFill;
    };

    var dashboardLineChart = newChart(chartLine, {
      type: 'line',
      data: {
        labels: labels.map(function (label) {
          return label.substring(0, label.length - 3);
        }),
        datasets: [{
          borderWidth: 2,
          data: chartData.map(function (d) {
            return (d * 3.14).toFixed(2);
          }),
          borderColor: utils.settings.chart.borderColor,
          backgroundColor: getChartBackground(chartLine)
        }]
      },
      options: {
        legend: {
          display: false
        },
        tooltips: {
          mode: 'x-axis',
          xPadding: 20,
          yPadding: 10,
          displayColors: false,
          callbacks: {
            label: function label(tooltipItem) {
              return labels[tooltipItem.index] + " - " + tooltipItem.yLabel + " USD";
            },
            title: function title() {
              return null;
            }
          }
        },
        hover: {
          mode: 'label'
        },
        scales: {
          xAxes: [{
            scaleLabel: {
              show: true,
              labelString: 'Month'
            },
            ticks: {
              fontColor: utils.rgbaColor('#fff', 0.7),
              fontStyle: 600
            },
            gridLines: {
              color: utils.rgbaColor('#fff', 0.1),
              zeroLineColor: utils.rgbaColor('#fff', 0.1),
              lineWidth: 1
            }
          }],
          yAxes: [{
            display: false
          }]
        }
      }
    });
    $('#dashboard-chart-select').on('change', function (e) {
      var LineDB = {
        all: [4, 1, 6, 2, 7, 12, 4, 6, 5, 4, 5, 10].map(function (d) {
          return (d * 3.14).toFixed(2);
        }),
        successful: [3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5, 8].map(function (d) {
          return (d * 3.14).toFixed(2);
        }),
        failed: [1, 0, 2, 1, 2, 1, 1, 0, 0, 1, 0, 2].map(function (d) {
          return (d * 3.14).toFixed(2);
        })
      };
      dashboardLineChart.data.datasets[0].data = LineDB[e.target.value];
      dashboardLineChart.update();
    });
  }
  /*-----------------------------------------------
  |   Bar Chart
  -----------------------------------------------*/


  var chartBar = document.getElementById('chart-bar');

  if (chartBar) {
    newChart(chartBar, {
      type: 'bar',
      data: {
        labels: labels.slice(0, 2),
        datasets: [{
          label: 'First dataset',
          backgroundColor: [utils.rgbaColor(utils.colors.info), utils.rgbaColor(utils.colors.warning)],
          borderColor: [utils.rgbColor(utils.colors.info), utils.rgbColor(utils.colors.warning)],
          borderWidth: 2,
          data: [6, 10]
        }, {
          label: 'Second dataset',
          backgroundColor: [utils.rgbaColor(utils.colors.success), utils.rgbaColor(utils.colors.danger)],
          borderColor: [utils.rgbColor(utils.colors.success), utils.rgbColor(utils.colors.danger)],
          borderWidth: 2,
          data: [3, 7]
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });
  }
  /*-----------------------------------------------
  |   Radar Chart
  -----------------------------------------------*/


  var chartRadar = document.getElementById('chart-radar');

  if (chartRadar) {
    newChart(chartRadar, {
      type: 'radar',
      data: {
        labels: labels,
        datasets: [{
          label: 'First dataset',
          backgroundColor: utils.rgbaColor(utils.colors.warning),
          borderColor: utils.rgbColor(utils.colors.warning),
          borderWidth: 2,
          data: chartData.slice(0, 12),
          fill: 1
        }, {
          label: 'Second dataset',
          backgroundColor: utils.rgbaColor(utils.colors.danger),
          borderColor: utils.rgbColor(utils.colors.danger),
          borderWidth: 2,
          data: chartData.slice(12, 24),
          fill: 1
        }]
      },
      options: {
        maintainAspectRatio: true,
        spanGaps: false,
        elements: {
          line: {
            tension: 0.000001
          }
        }
      }
    });
  }
  /*-----------------------------------------------
  |   Pie Chart
  -----------------------------------------------*/


  var chartPie = document.getElementById('chart-pie');

  if (chartPie) {
    newChart(chartPie, {
      type: 'pie',
      data: {
        labels: labels.slice(0, 3),
        datasets: [{
          backgroundColor: utils.rgbaColors(),
          borderColor: utils.rgbColors(),
          data: chartData.slice(0, 3)
        }]
      },
      options: {
        responsive: true
      }
    });
  }
  /*-----------------------------------------------
  |   Doughnut Chart
  -----------------------------------------------*/


  var chartDoughnut = document.getElementById('chart-doughnut');

  if (chartDoughnut) {
    newChart(chartDoughnut, {
      type: 'doughnut',
      data: {
        labels: labels.slice(0, 3),
        datasets: [{
          backgroundColor: utils.rgbColors(),
          borderColor: utils.rgbColors(),
          data: chartData.slice(0, 3)
        }]
      },
      options: {
        responsive: true
      }
    });
  }
  /*-----------------------------------------------
  |   Polar Area Chart
  -----------------------------------------------*/


  var chartPolarArea = document.getElementById('chart-polar-area');

  if (chartPolarArea) {
    newChart(chartPolarArea, {
      type: 'polarArea',
      data: {
        labels: labels.slice(0, 3),
        datasets: [{
          backgroundColor: utils.rgbaColors(),
          borderColor: utils.rgbaColors(),
          data: chartData.slice(0, 3)
        }]
      },
      options: {
        responsive: true
      }
    });
  }
  /* eslint-disable */

  /*-----------------------------------------------
  |   Polar Bubble
  -----------------------------------------------*/


  var colorize = function colorize(opaque, context) {
    var value = context.dataset.data[context.dataIndex];
    var x = value.x / 100;
    var y = value.y / 100;
    var r = x < 0 && y < 0 ? 250 : x < 0 ? 150 : y < 0 ? 50 : 0;
    var g = x < 0 && y < 0 ? 0 : x < 0 ? 50 : y < 0 ? 150 : 250;
    var b = x < 0 && y < 0 ? 0 : x > 0 && y > 0 ? 250 : 150;
    var a = opaque ? 1 : 0.5 * value.v / 1000;
    return "rgba(" + r + ", " + g + ", " + b + ", " + a + ")";
  };

  var rand = function rand(min, max) {
    var seed = _this._seed;
    min = min === undefined ? 0 : min;
    max = max === undefined ? 1 : max;
    _this._seed = (seed * 9301 + 49297) % 233280;
    return min + _this._seed / 233280 * (max - min);
  };

  var generateData = function generateData() {
    var data = [];
    var DATA_COUNT = 16;
    var MIN_XY = -150;
    var MAX_XY = 100;

    for (var i = 0; i < DATA_COUNT; i += 1) {
      data.push({
        x: rand(MIN_XY, MAX_XY),
        y: rand(MIN_XY, MAX_XY),
        v: rand(0, 1000)
      });
    }

    return data;
  };

  var chartBubble = document.getElementById("chart-bubble");

  if (chartBubble) {
    newChart(chartBubble, {
      type: "bubble",
      data: {
        datasets: [{
          label: ["Deer Population"],
          data: [{
            x: -10,
            y: -20,
            r: 20
          }, {
            x: 100,
            y: 0,
            r: 10
          }, {
            x: 60,
            y: 30,
            r: 20
          }, {
            x: 40,
            y: 60,
            r: 25
          }, {
            x: 80,
            y: 80,
            r: 30
          }, {
            x: 20,
            y: 30,
            r: 25
          }, {
            x: 0,
            y: 100,
            r: 5
          }],
          backgroundColor: "#2C7BE5"
        }]
      }
    });
  }
  /*-----------------------------------------------
  |   Component Line Chart
  -----------------------------------------------*/


  var componentChartLine = document.getElementById("component-chart-line");

  if (componentChartLine) {
    newChart(componentChartLine, {
      type: "line",
      data: {
        labels: labels,
        datasets: [{
          borderWidth: 2,
          data: chartData.slice(2, 14).map(function (d) {
            return (d * 3.14).toFixed(2);
          }),
          borderColor: utils.rgbaColor(utils.colors.primary, 0.4),
          backgroundColor: utils.rgbaColor(utils.colors.primary)
        }, {
          borderWidth: 2,
          borderColor: "#fff",
          data: chartData.slice(3, 15).map(function (d) {
            return (d * 3.14).toFixed(2);
          }),
          backgroundColor: utils.rgbaColor(utils.colors.primary)
        }]
      },
      options: {
        legend: {
          display: false
        },
        tooltips: {
          mode: "x-axis",
          xPadding: 20,
          yPadding: 10,
          displayColors: false,
          callbacks: {
            label: function label(tooltipItem) {
              return labels[tooltipItem.index] + " - " + tooltipItem.yLabel + " USD";
            },
            title: function title() {
              return null;
            }
          }
        },
        hover: {
          mode: "label"
        },
        scales: {
          xAxes: [{
            scaleLabel: {
              show: true,
              labelString: "Month"
            },
            ticks: {
              fontColor: utils.rgbaColor("#000", 0.7),
              fontStyle: 600
            },
            gridLines: {
              // color: utils.rgbaColor('#000', 0.1),
              color: utils.rgbaColor("#000", 0.1),
              zeroLineColor: utils.rgbaColor("#000", 0.1),
              lineWidth: 1
            }
          }],
          yAxes: [{
            display: false
          }]
        }
      }
    });
  }
  /*-----------------------------------------------
  |   Real time user
  -----------------------------------------------*/

  /*-----------------------------------------------
  |   Bar Chart
  -----------------------------------------------*/


  var realTimeUser = document.getElementById("real-time-user");

  if (realTimeUser) {
    var realTimeUserChart = newChart(realTimeUser, {
      type: "bar",
      data: {
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25],
        datasets: [{
          label: "Users",
          backgroundColor: utils.rgbaColor("#fff", 0.3),
          data: [183, 163, 176, 172, 166, 161, 164, 159, 172, 173, 184, 163, 99, 173, 183, 167, 160, 183, 163, 176, 172, 166, 173, 188, 175],
          barPercentage: 0.9,
          categoryPercentage: 1.0
        }]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            display: false,
            stacked: true
          }],
          xAxes: [{
            stacked: false,
            ticks: {
              display: false
            },
            gridLines: {
              color: utils.rgbaColor("#fff", 0.1),
              display: false
            }
          }]
        }
      }
    });
    var userCounterDom = $(".real-time-user");
    setInterval(function () {
      var userCounter = Math.floor(Math.random() * (120 - 60) + 60);
      /*-----------------------------------------------
      |   Remove data
      -----------------------------------------------*/

      realTimeUserChart.data.datasets.forEach(function (dataset) {
        dataset.data.shift();
      });
      realTimeUserChart.update();
      /*-----------------------------------------------
      |   Add data
      -----------------------------------------------*/

      setTimeout(function () {
        realTimeUserChart.data.datasets.forEach(function (dataset) {
          dataset.data.push(userCounter);
        });
        realTimeUserChart.update();
        userCounterDom.text(userCounter);
      }, 500);
    }, 2000);
  }
});
/*-----------------------------------------------
|   Chat
-----------------------------------------------*/

utils.$document.ready(function () {
  var Event = {
    CLICK: 'click',
    SHOWN_BS_TAB: 'shown.bs.tab',
    RESIZE: 'resize',
    KEYUP: 'keyup',
    EMOJI_BTN_CLIK: 'emojibtn.click'
  };
  var Selector = {
    CHAT_SIDEBAR: '.chat-sidebar',
    CHAT_CONTACT: '.chat-contact',
    CHAT_CONTENT_SCROLL_AREA: '.chat-content-scroll-area',
    CHAT_CONTENT_HEADER_ACTIVE: '.card-chat-pane.active .chat-content-header',
    CHAT_CONTENT_SCROLL_AREA_ACTIVE: '.card-chat-pane.active .chat-content-scroll-area',
    CARD_CHAT_PANE_ACTIVE: '.card-chat-pane.active',
    CHAT_EMOJIAREA: '.chat-editor-area .emojiarea',
    BTN_SEND: '.btn-send',
    CHAT_FILE_UPLOAD: '.chat-file-upload',
    CARD_CHAT_CONTENT: '.card-chat-content',
    EMOJIONEAREA_EDITOR: '.emojionearea-editor',
    BTN_INFO: '.btn-info',
    CONVERSATION_INFO: '.conversation-info',
    CONTACTS_LIST_SHOW: '.contacts-list-show',
    CHAT_EDITOR_AREA: '.chat-editor-area'
  };
  var ClassName = {
    UNREAD_MESSAGE: 'unread-message',
    TEXT_PRIMARY: 'text-primary',
    SHOW: 'show',
    LEFT_0: 'l-0'
  };
  var DATA_KEY = {
    TARGET: 'target',
    INDEX: 'index'
  };
  var Attribute = {
    STYLE: 'style'
  };
  var $chatSidebar = $(Selector.CHAT_SIDEBAR);
  var $chatContact = $(Selector.CHAT_CONTACT);
  var $chatEmojiarea = $(Selector.CHAT_EMOJIAREA);
  var $chatIcons = $(Selector.BTN_SEND + "," + Selector.CHAT_FILE_UPLOAD);
  var $btnSend = $(Selector.BTN_SEND);
  var initialEditorHeight = $(Selector.EMOJIONEAREA_EDITOR).outerHeight();
  var $chatContent = $(Selector.CARD_CHAT_CONTENT);
  var $scrollArea = $(Selector.CHAT_CONTENT_SCROLL_AREA);
  var $currentChatArea = document.querySelector(Selector.CHAT_CONTENT_SCROLL_AREA); // Set chat scrollbar area height

  var setChatAreaHeight = function setChatAreaHeight(chatContentArea, editorAreaHeight) {
    var chatContentHeight = chatContentArea.height();
    var calculated = chatContentHeight - editorAreaHeight;
    var chatContentHeaderHeight = $(Selector.CHAT_CONTENT_HEADER_ACTIVE).outerHeight();
    var chatArea = chatContentArea.find(Selector.CHAT_CONTENT_SCROLL_AREA_ACTIVE);
    chatArea.css({
      height: calculated - chatContentHeaderHeight
    });
  }; // Set scrollbar position


  var setScrollbarPosition = function setScrollbarPosition($chatArea) {
    if ($chatArea) {
      var scrollArea = $chatArea;
      scrollArea.scrollTop = $chatArea.scrollHeight;
    }
  };

  setTimeout(function () {
    setScrollbarPosition($currentChatArea);
  }, 700);
  utils.$document.on(Event.CLICK, Selector.CHAT_CONTACT, function (e) {
    var $this = $(e.currentTarget); // Hide contact list sidebar on responsive

    utils.$window.width() < 768 && $chatSidebar.removeClass(ClassName.LEFT_0); // Remove unread-message class when read

    $this.hasClass(ClassName.UNREAD_MESSAGE) && $this.removeClass(ClassName.UNREAD_MESSAGE);
  });
  $chatContact.on(Event.SHOWN_BS_TAB, function (e) {
    var $this = $(e.currentTarget);
    var $tab = $this.data(DATA_KEY.TARGET);
    $chatEmojiarea.length && $chatEmojiarea[0].emojioneArea.setText('');
    var editorHeight = $(Selector.EMOJIONEAREA_EDITOR).outerHeight();
    setChatAreaHeight($chatContent, editorHeight);
    $chatIcons.removeAttr(Attribute.STYLE);
    $btnSend.removeClass(ClassName.TEXT_PRIMARY); // Set scrollbar position on bottom

    var $chatArea = document.querySelector($tab + " " + Selector.CHAT_CONTENT_SCROLL_AREA);
    setScrollbarPosition($chatArea);
  }); // Detect keyup event on EmojioneArea Editor

  if ($chatEmojiarea.length) {
    $chatEmojiarea[0].emojioneArea.on(Event.KEYUP + " " + Event.EMOJI_BTN_CLIK, function ($editor) {
      var textLength = $editor.text().trim().length;
      var _$editor$ = $editor[0],
          offsetWidth = _$editor$.offsetWidth,
          clientWidth = _$editor$.clientWidth;
      var currentEditorHeight = $editor.outerHeight();
      var emojiLength = $editor.find('img').length; // Change color of submit button on keyup

      textLength || emojiLength ? $btnSend.addClass(ClassName.TEXT_PRIMARY) : $btnSend.removeClass(ClassName.TEXT_PRIMARY);

      if (currentEditorHeight !== initialEditorHeight) {
        setChatAreaHeight($chatContent, currentEditorHeight); // Set scrollbar position on bottom

        var tabContentId = $chatContent.find(Selector.CARD_CHAT_PANE_ACTIVE).attr('id');
        var $chatArea = document.querySelector("#" + tabContentId + " " + Selector.CHAT_CONTENT_SCROLL_AREA);
        setScrollbarPosition($chatArea);
      } // Align file upload and send icons when editor overflow scroll


      $chatIcons.css({
        marginRight: offsetWidth === clientWidth ? 0 : '1rem'
      });
      initialEditorHeight = currentEditorHeight;
    });
  } // Open conversation info sidebar


  utils.$document.on(Event.CLICK, Selector.BTN_INFO, function (e) {
    var $this = $(e.currentTarget);
    var dataIndex = $this.data(DATA_KEY.INDEX);
    var $info = $(Selector.CONVERSATION_INFO + "[data-" + DATA_KEY.INDEX + "='" + dataIndex + "']");
    $info.toggleClass(ClassName.SHOW);
  }); // Show contact list sidebar on responsive

  utils.$document.on(Event.CLICK, Selector.CONTACTS_LIST_SHOW, function () {
    $chatSidebar.addClass(ClassName.LEFT_0);
  }); // Set scrollbar area height on resize

  utils.$window.on(Event.RESIZE, function () {
    if ($scrollArea.length) {
      var editorCurrentHeight = $(Selector.EMOJIONEAREA_EDITOR).outerHeight();
      setChatAreaHeight($chatContent, editorCurrentHeight);
      var chatArea = document.querySelector(Selector.CHAT_CONTENT_SCROLL_AREA_ACTIVE);
      setScrollbarPosition(chatArea);
    }
  });
});
/*-----------------------------------------------
|   Copy link
-----------------------------------------------*/

utils.$document.ready(function () {
  $('#copyLinkModal').on('shown.bs.modal', function () {
    $('.invitation-link').focus().select();
  });
  utils.$document.on('click', '[data-copy]', function (e) {
    var $this = $(e.currentTarget);
    var targetID = $this.data('copy');
    $(targetID).focus().select();
    document.execCommand('copy');
    $this.attr('title', 'Copied!').tooltip('_fixTitle').tooltip('show').attr('title', 'Copy to clipboard').tooltip('_fixTitle');
  });
});
/*-----------------------------------------------
|   Count Up
-----------------------------------------------*/

utils.$document.ready(function () {
  var $counters = $('[data-countup]');

  if ($counters.length) {
    $counters.each(function (index, value) {
      var $counter = $(value);
      var counter = $counter.data('countup');

      var toAlphanumeric = function toAlphanumeric(num) {
        var number = num;
        var abbreviations = {
          k: 1000,
          m: 1000000,
          b: 1000000000,
          t: 1000000000000
        };

        if (num < abbreviations.m) {
          number = (num / abbreviations.k).toFixed(2) + "k";
        } else if (num < abbreviations.b) {
          number = (num / abbreviations.m).toFixed(2) + "m";
        } else if (num < abbreviations.t) {
          number = (num / abbreviations.b).toFixed(2) + "b";
        } else {
          number = (num / abbreviations.t).toFixed(2) + "t";
        }

        return number;
      };

      var toComma = function toComma(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      };

      var toSpace = function toSpace(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
      };

      var playCountUpTriggered = false;

      var countUP = function countUP() {
        if (utils.isScrolledIntoView(value) && !playCountUpTriggered) {
          if (!playCountUpTriggered) {
            $({
              countNum: 0
            }).animate({
              countNum: counter.count
            }, {
              duration: counter.duration || 1000,
              easing: 'linear',
              step: function step() {
                $counter.text((counter.prefix ? counter.prefix : '') + Math.floor(this.countNum));
              },
              complete: function complete() {
                switch (counter.format) {
                  case 'comma':
                    $counter.text((counter.prefix ? counter.prefix : '') + toComma(this.countNum));
                    break;

                  case 'space':
                    $counter.text((counter.prefix ? counter.prefix : '') + toSpace(this.countNum));
                    break;

                  case 'alphanumeric':
                    $counter.text((counter.prefix ? counter.prefix : '') + toAlphanumeric(this.countNum));
                    break;

                  default:
                    $counter.text((counter.prefix ? counter.prefix : '') + this.countNum);
                }
              }
            });
            playCountUpTriggered = true;
          }
        }

        return playCountUpTriggered;
      };

      countUP();
      utils.$window.scroll(function () {
        countUP();
      });
    });
  }
});
/*-----------------------------------------------
|   Data table
-----------------------------------------------*/

utils.$document.ready(function () {
  var dataTables = $('.data-table');

  var customDataTable = function customDataTable(elem) {
    elem.find('.pagination').addClass('pagination-sm');
  };

  dataTables.length && dataTables.each(function (index, value) {
    var $this = $(value);
    var options = $.extend({
      responsive: true,
      dom: "<'row mx-1'<'col-sm-12 col-md-6 px-3'l><'col-sm-12 col-md-6 px-3'f>>" + "<'table-responsive'tr>" + "<'row mx-1 align-items-center justify-content-center justify-content-md-between'<'col-auto mb-2 mb-sm-0'i><'col-auto'p>>"
    }, $this.data('options'));
    $this.DataTable(options);
    var $wrpper = $this.closest('.dataTables_wrapper');
    customDataTable($wrpper);
    $this.on('draw.dt', function () {
      return customDataTable($wrpper);
    });
  });
});
/*-----------------------------------------------
|   Countdown
-----------------------------------------------*/

utils.$document.ready(function () {
  var $dataCountdowns = $('[data-countdown]');
  var DATA_KEY = {
    FALLBACK: 'countdown-fallback',
    COUNTDOWN: 'countdown'
  };

  if ($dataCountdowns.length) {
    $dataCountdowns.each(function (index, value) {
      var $dateCountdown = $(value);
      var date = $dateCountdown.data(DATA_KEY.COUNTDOWN);
      var fallback;

      if (typeof $dateCountdown.data(DATA_KEY.FALLBACK) !== typeof undefined) {
        fallback = $dateCountdown.data(DATA_KEY.FALLBACK);
      }

      $dateCountdown.countdown(date, function (event) {
        if (event.elapsed) {
          $dateCountdown.text(fallback);
        } else {
          $dateCountdown.text(event.strftime('%D days %H:%M:%S'));
        }
      });
    });
  }
});
/*-----------------------------------------------
|   Demo mode
-----------------------------------------------*/

utils.$document.ready(function () {
  var _window = window,
      location = _window.location;
  var Event = {
    CHANGE: 'change'
  };
  var Selector = {
    RTL: '#mode-rtl',
    FLUID: '#mode-fluid',
    INPUT_NAVBAR: "input[name='navbar']",
    INPUT_COLOR_SCHEME: "input[name='colorScheme']",
    NAVBAR_STYLE_TRANSPARENT: '#navbar-style-transparent',
    NAVBAR_STYLE_INVERTED: '#navbar-style-inverted',
    NAVBAR_STYLE_VIBRANT: '#navbar-style-vibrant',
    NAVBAR_STYLE_WHITE: '#navbar-style-card'
  };
  var DATA_KEY = {
    URL: 'url',
    HOME_URL: 'home-url',
    PAGE_URL: 'page-url'
  }; // Redirect on Checkbox change

  var handleChange = function handleChange(selector) {
    utils.$document.on(Event.CHANGE, selector, function (e) {
      var $this = $(e.currentTarget);
      var isChecked = $this.prop('checked');

      if (isChecked) {
        var url = $this.data(DATA_KEY.URL);
        location.replace(url);
      } else {
        var homeUrl = $this.data(DATA_KEY.HOME_URL);
        location.replace(homeUrl);
      }
    });
  };

  var handleInputChange = function handleInputChange(selector) {
    utils.$document.on(Event.CHANGE, selector, function (e) {
      var $this = $(e.currentTarget);
      var pageUrl = $this.data(DATA_KEY.PAGE_URL);
      location.replace(pageUrl);
    });
  }; // Mode checkbox handler


  handleChange(Selector.RTL);
  handleChange(Selector.FLUID);
  handleInputChange(Selector.INPUT_NAVBAR);
  handleInputChange(Selector.INPUT_COLOR_SCHEME);
  handleInputChange(Selector.NAVBAR_STYLE_TRANSPARENT);
  handleInputChange(Selector.NAVBAR_STYLE_INVERTED);
  handleInputChange(Selector.NAVBAR_STYLE_VIBRANT);
  handleInputChange(Selector.NAVBAR_STYLE_WHITE);
});
/*-----------------------------------------------
|   Documentation and Component Navigation
-----------------------------------------------*/

utils.$document.ready(function () {
  var $componentNav = $('#components-nav');

  if ($componentNav.length) {
    var loc = window.location.href;

    var _loc$split = loc.split('#');

    loc = _loc$split[0];
    var location = loc.split('/');
    var url = location[location.length - 2] + "/" + location.pop();
    var urls = $componentNav.children('li').children('a');

    for (var i = 0, max = urls.length; i < max; i += 1) {
      var dom = urls[i].href.split('/');
      var domURL = dom[dom.length - 2] + "/" + dom.pop();

      if (domURL === url) {
        var $targetedElement = $(urls[i]);
        $targetedElement.removeClass('text-800');
        $targetedElement.addClass('font-weight-medium');
        break;
      }
    }
  }
});
/*-----------------------------------------------
|   Draggable
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selectors = {
    BODY: 'body',
    KANBAN_CONTAINER: '.kanban-container',
    KANBAN_ITEMS_CONTAINER: '.kanban-items-container',
    KANBAN_ITEM: '.kanban-item',
    KANBAN_COLLAPSE: "[data-collapse='kanban']",
    PS_RAILS: '.ps__rail-x, .ps__rail-y' // Perfect scrollbar rails in IE

  };
  var Events = {
    DRAG_START: 'drag:start',
    DRAG_STOP: 'drag:stop'
  };
  var columns = document.querySelectorAll(Selectors.KANBAN_ITEMS_CONTAINER);
  var container = document.querySelector(Selectors.KANBAN_CONTAINER);
  var scrollItems = $(Selectors.KANBAN_ITEMS_CONTAINER);
  var scrollableElements = [];
  scrollItems.each(function (index, item) {
    scrollableElements[index] = item;
  });

  if (columns.length) {
    // Initialize Sortable
    var sortable = new window.Draggable.Sortable(columns, {
      draggable: Selectors.KANBAN_ITEM,
      delay: 200,
      mirror: {
        appendTo: Selectors.BODY,
        constrainDimensions: true
      },
      scrollable: {
        draggable: Selectors.KANBAN_ITEM,
        scrollableElements: [].concat(scrollableElements, [container])
      }
    }); // Hide form when drag start

    sortable.on(Events.DRAG_START, function () {
      $(Selectors.KANBAN_COLLAPSE).collapse('hide');
    }); // Place forms and other contents bottom of the sortable container

    sortable.on(Events.DRAG_STOP, function (e) {
      var $this = $(e.data.source);
      var $itemContainer = $this.closest(Selectors.KANBAN_ITEMS_CONTAINER);
      var $collapse = $this.closest(Selectors.KANBAN_ITEMS_CONTAINER).find(Selectors.KANBAN_COLLAPSE);
      $this.is(':last-child') && $itemContainer.append($collapse); // For IE

      if (window.is.ie()) {
        var $rails = $itemContainer.find(Selectors.PS_RAILS);
        $itemContainer.append($rails);
      }
    });
  }
});
/*-----------------------------------------------
|   Dashboard Table dropdown
-----------------------------------------------*/

utils.$document.ready(function () {
  // Only for ios
  if (window.is.ios()) {
    var Event = {
      SHOWN_BS_DROPDOWN: 'shown.bs.dropdown',
      HIDDEN_BS_DROPDOWN: 'hidden.bs.dropdown'
    };
    var Selector = {
      TABLE_RESPONSIVE: '.table-responsive',
      DROPDOWN_MENU: '.dropdown-menu'
    };
    $(Selector.TABLE_RESPONSIVE).on(Event.SHOWN_BS_DROPDOWN, function dashboardTableDropdown(e) {
      var t = $(this);
      var m = $(e.target).find(Selector.DROPDOWN_MENU);
      var tb = t.offset().top + t.height();
      var mb = m.offset().top + m.outerHeight(true);
      var d = 20; // Space for shadow + scrollbar.

      if (t[0].scrollWidth > t.innerWidth()) {
        if (mb + d > tb) {
          t.css('padding-bottom', mb + d - tb);
        }
      } else {
        t.css('overflow', 'visible');
      }
    }).on(Event.HIDDEN_BS_DROPDOWN, function (e) {
      var $this = $(e.currentTarget);
      $this.css({
        'padding-bottom': '',
        overflow: ''
      });
    });
  }
}); // Reference
// https://github.com/twbs/bootstrap/issues/11037#issuecomment-274870381

/*-----------------------------------------------
|   Documentation and Component Navigation
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selector = {
    NAVBAR_THEME_DROPDOWN: '.navbar-theme .dropdown',
    DROPDOWN_ON_HOVER: '.dropdown-on-hover',
    DATA_TOGGLE_DROPDOWN: '[data-toggle="dropdown"]',
    BODY: 'body'
  };
  var ClassName = {
    DROPDOWN_ON_HOVER: 'dropdown-on-hover'
  };
  var Event = {
    CLICK: 'click',
    MOUSE_LEAVE: 'mouseleave',
    MOUSE_EVENT: 'mouseenter mouseleave'
  };
  var $navbarDropdown = $(Selector.NAVBAR_THEME_DROPDOWN);
  !window.is.mobile() ? $navbarDropdown.addClass(ClassName.DROPDOWN_ON_HOVER) : $navbarDropdown.removeClass(ClassName.DROPDOWN_ON_HOVER);

  var toggleDropdown = function toggleDropdown(e) {
    var $el = $(e.target);
    var dropdown = $el.closest(Selector.DROPDOWN_ON_HOVER);
    var btnDropdown = dropdown.find(Selector.DATA_TOGGLE_DROPDOWN);
    setTimeout(function () {
      var shouldOpen = e.type !== Event.CLICK && dropdown.is(':hover');
      shouldOpen ? btnDropdown.dropdown('show') : btnDropdown.dropdown('hide');
    }, e.type === Event.MOUSE_LEAVE ? 100 : 0);
  };

  $(Selector.BODY).on(Event.MOUSE_EVENT, Selector.DROPDOWN_ON_HOVER, toggleDropdown);
});
/*-----------------------------------------------
|   Dropzone
-----------------------------------------------*/

window.Dropzone ? window.Dropzone.autoDiscover = false : '';
utils.$document.ready(function () {
  var Selector = {
    DROPZONE: '[data-dropzone]',
    DZ_ERROR_MESSAGE: '.dz-error-message',
    DZ_PREVIEW: '.dz-preview',
    DZ_PROGRESS: '.dz-preview .dz-preview-cover .dz-progress',
    DZ_PREVIEW_COVER: '.dz-preview .dz-preview-cover'
  };
  var ClassName = {
    DZ_FILE_PROCESSING: 'dz-file-processing',
    DZ_FILE_COMPLETE: 'dz-file-complete',
    DZ_COMPLETE: 'dz-complete',
    DZ_PROCESSING: 'dz-processing'
  };
  var DATA_KEY = {
    OPTIONS: 'options'
  };
  var Events = {
    ADDED_FILE: 'addedfile',
    COMPLETE: 'complete'
  };
  var dropzones = $(Selector.DROPZONE);
  !!dropzones.length && dropzones.each(function (index, value) {
    var element = value;
    var $this = $(element);
    var userOptions = $this.data(DATA_KEY.OPTIONS);
    userOptions = userOptions || {};
    var data = userOptions.data ? userOptions.data : {};
    var options = $.extend({
      url: '/assets/php/',
      addRemoveLinks: false,
      previewsContainer: element.querySelector(Selector.DZ_PREVIEW),
      previewTemplate: element.querySelector(Selector.DZ_PREVIEW).innerHTML,
      thumbnailWidth: null,
      thumbnailHeight: null,
      init: function init() {
        var thisDropzone = this;

        if (data.length) {
          $.each(data, function (i, v) {
            var mockFile = {
              name: v.name,
              size: v.size
            };
            thisDropzone.options.addedfile.call(thisDropzone, mockFile);
            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, v.url + "/" + v.name);
          });
        }

        thisDropzone.on(Events.ADDED_FILE, function addedfile() {
          if ('maxFiles' in userOptions) {
            if (userOptions.maxFiles === 1 && $this.find(Selector.DZ_PREVIEW_COVER).length > 1) {
              $this.find(Selector.DZ_PREVIEW_COVER).first().remove();
            }

            if (userOptions.maxFiles === 1 && this.files.length > 1) {
              this.removeFile(this.files[0]);
            }
          }
        });
      }
    }, userOptions);
    element.querySelector(Selector.DZ_PREVIEW).innerHTML = '';
    var dropzone = new window.Dropzone(value, options);
    dropzone.on(Events.ADDED_FILE, function () {
      $this.find(Selector.DZ_PREVIEW_COVER).removeClass(ClassName.DZ_FILE_COMPLETE);
      $this.addClass(ClassName.DZ_FILE_PROCESSING);
    });
    dropzone.on(Events.COMPLETE, function () {
      $this.find(Selector.DZ_PREVIEW_COVER).removeClass(ClassName.DZ_PROCESSING);
      $this.addClass(ClassName.DZ_FILE_COMPLETE);
    });
  });
});
/*-----------------------------------------------
|  Echarts
-----------------------------------------------*/

var getPosition = function getPosition(pos, params, dom, rect, size) {
  return {
    top: pos[1] - size.contentSize[1] - 10,
    left: pos[0] - size.contentSize[0] / 2
  };
};

utils.$document.ready(function () {
  var Events = {
    CHANGE: 'change'
  };
  var Selector = {
    ECHART_LINE_TOTAL_ORDER: '.echart-line-total-order',
    ECHART_BAR_WEEKLY_SALES: '.echart-bar-weekly-sales',
    ECHART_LINE_TOTAL_SALES: '.echart-line-total-sales',
    SELECT_MONTH: '.select-month',
    ECHART_BAR_TOP_PRODUCTS: '.echart-bar-top-products',
    ECHART_WORLD_MAP: '.echart-world-map',
    ECHART_DOUGHNUT: '.echart-doughnut'
  }; //
  // ─── TOTAL ORDER CHART ──────────────────────────────────────────────────────────
  //

  var $echartLineTotalOrder = document.querySelector(Selector.ECHART_LINE_TOTAL_ORDER);

  if ($echartLineTotalOrder) {
    var $this = $($echartLineTotalOrder); // Get options from data attribute

    var userOptions = $this.data('options');
    var chart = window.echarts.init($echartLineTotalOrder); // Default options

    var defaultOptions = {
      tooltip: {
        triggerOn: 'mousemove',
        trigger: 'axis',
        padding: [7, 10],
        formatter: '{b0}: {c0}',
        backgroundColor: utils.grays.white,
        borderColor: utils.grays['300'],
        borderWidth: 1,
        transitionDuration: 0,
        position: function position(pos, params, dom, rect, size) {
          return getPosition(pos, params, dom, rect, size);
        },
        textStyle: {
          color: utils.colors.dark
        }
      },
      xAxis: {
        type: 'category',
        data: ['Week 4', 'Week 5'],
        boundaryGap: false,
        splitLine: {
          show: false
        },
        axisLine: {
          show: false,
          lineStyle: {
            color: utils.grays['300'],
            type: 'dashed'
          }
        },
        axisLabel: {
          show: false
        },
        axisTick: {
          show: false
        },
        axisPointer: {
          type: 'none'
        }
      },
      yAxis: {
        type: 'value',
        splitLine: {
          show: false
        },
        axisLine: {
          show: false
        },
        axisLabel: {
          show: false
        },
        axisTick: {
          show: false
        },
        axisPointer: {
          show: false
        }
      },
      series: [{
        type: 'line',
        lineStyle: {
          color: utils.colors.primary,
          width: 3
        },
        itemStyle: {
          color: utils.grays.white,
          borderColor: utils.colors.primary,
          borderWidth: 2
        },
        hoverAnimation: true,
        data: [20, 130],
        connectNulls: true,
        smooth: 0.6,
        smoothMonotone: 'x',
        symbol: 'circle',
        symbolSize: 8,
        areaStyle: {
          color: {
            type: 'linear',
            x: 0,
            y: 0,
            x2: 0,
            y2: 1,
            colorStops: [{
              offset: 0,
              color: utils.rgbaColor(utils.colors.primary, 0.25)
            }, {
              offset: 1,
              color: utils.rgbaColor(utils.colors.primary, 0)
            }]
          }
        }
      }],
      grid: {
        bottom: '2%',
        top: '0%',
        right: '10px',
        left: '10px'
      }
    }; // Merge options using lodash

    var options = window._.merge(defaultOptions, userOptions);

    chart.setOption(options);
  } //
  // ─── WEEKLY SALES CHART ─────────────────────────────────────────────────────────
  //


  var $echartBarWeeklySales = document.querySelector(Selector.ECHART_BAR_WEEKLY_SALES);

  if ($echartBarWeeklySales) {
    var _$this = $($echartBarWeeklySales); // Get options from data attribute


    var _userOptions = _$this.data('options');

    var data = [120, 200, 150, 80, 70, 110, 120]; // Max value of data

    var yMax = Math.max.apply(Math, data);
    var dataBackground = data.map(function () {
      return yMax;
    });

    var _chart = window.echarts.init($echartBarWeeklySales); // Default options


    var _defaultOptions = {
      tooltip: {
        trigger: 'axis',
        padding: [7, 10],
        formatter: '{b1}: {c1}',
        backgroundColor: utils.grays.white,
        borderColor: utils.grays['300'],
        borderWidth: 1,
        textStyle: {
          color: utils.colors.dark
        },
        transitionDuration: 0,
        position: function position(pos, params, dom, rect, size) {
          return getPosition(pos, params, dom, rect, size);
        }
      },
      xAxis: {
        type: 'category',
        data: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        boundaryGap: false,
        axisLine: {
          show: false
        },
        axisLabel: {
          show: false
        },
        axisTick: {
          show: false
        },
        axisPointer: {
          type: 'none'
        }
      },
      yAxis: {
        type: 'value',
        splitLine: {
          show: false
        },
        axisLine: {
          show: false
        },
        axisLabel: {
          show: false
        },
        axisTick: {
          show: false
        },
        axisPointer: {
          type: 'none'
        }
      },
      series: [{
        type: 'bar',
        barWidth: '5px',
        barGap: '-100%',
        itemStyle: {
          color: utils.grays['200'],
          barBorderRadius: 10
        },
        data: dataBackground,
        animation: false,
        emphasis: {
          itemStyle: {
            color: utils.grays['200']
          }
        }
      }, {
        type: 'bar',
        barWidth: '5px',
        itemStyle: {
          color: utils.colors.primary,
          barBorderRadius: 10
        },
        data: data,
        emphasis: {
          itemStyle: {
            color: utils.colors.primary
          }
        },
        z: 10
      }],
      grid: {
        right: 5,
        left: 10,
        top: 0,
        bottom: 0
      }
    }; // Merge user options with lodash

    var _options = window._.merge(_defaultOptions, _userOptions);

    _chart.setOption(_options);
  } //
  // ─── EHCART LINE TOTAL SALES ────────────────────────────────────────────────────────────────
  //


  var $echartsLineTotalSales = document.querySelector(Selector.ECHART_LINE_TOTAL_SALES);
  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

  function getFormatter(params) {
    var _params$ = params[0],
        name = _params$.name,
        value = _params$.value;
    var date = new Date(name);
    return months[0] + " " + date.getDate() + ", " + value;
  }

  if ($echartsLineTotalSales) {
    var _$this2 = $($echartsLineTotalSales); // Get options from data attribute


    var _userOptions2 = _$this2.data('options');

    var _chart2 = window.echarts.init($echartsLineTotalSales);

    var monthsnumber = [[60, 80, 60, 80, 65, 130, 120, 100, 30, 40, 30, 70], [100, 70, 80, 50, 120, 100, 130, 140, 90, 100, 40, 50], [80, 50, 60, 40, 60, 120, 100, 130, 60, 80, 50, 60], [70, 80, 100, 70, 90, 60, 80, 130, 40, 60, 50, 80], [90, 40, 80, 80, 100, 140, 100, 130, 90, 60, 70, 50], [80, 60, 80, 60, 40, 100, 120, 100, 30, 40, 30, 70], [20, 40, 20, 50, 70, 60, 110, 80, 90, 30, 50, 50], [60, 70, 30, 40, 80, 140, 80, 140, 120, 130, 100, 110], [90, 90, 40, 60, 40, 110, 90, 110, 60, 80, 60, 70], [50, 80, 50, 80, 50, 80, 120, 80, 50, 120, 110, 110], [60, 90, 60, 70, 40, 70, 100, 140, 30, 40, 30, 70], [20, 40, 20, 50, 30, 80, 120, 100, 30, 40, 30, 70]];
    var _defaultOptions2 = {
      color: utils.grays.white,
      tooltip: {
        trigger: 'axis',
        padding: [7, 10],
        backgroundColor: utils.grays.white,
        borderColor: utils.grays['300'],
        borderWidth: 1,
        textStyle: {
          color: utils.colors.dark
        },
        formatter: function formatter(params) {
          return getFormatter(params);
        },
        transitionDuration: 0,
        position: function position(pos, params, dom, rect, size) {
          return getPosition(pos, params, dom, rect, size);
        }
      },
      xAxis: {
        type: 'category',
        data: ['2019-01-05', '2019-01-06', '2019-01-07', '2019-01-08', '2019-01-09', '2019-01-10', '2019-01-11', '2019-01-12', '2019-01-13', '2019-01-14', '2019-01-15', '2019-01-16'],
        boundaryGap: false,
        axisPointer: {
          lineStyle: {
            color: utils.grays['300'],
            type: 'dashed'
          }
        },
        splitLine: {
          show: false
        },
        axisLine: {
          lineStyle: {
            // color: utils.grays['300'],
            color: utils.rgbaColor('#000', 0.01),
            type: 'dashed'
          }
        },
        axisTick: {
          show: false
        },
        axisLabel: {
          color: utils.grays['400'],
          formatter: function formatter(value) {
            var date = new Date(value);
            return months[date.getMonth()] + " " + date.getDate();
          },
          margin: 15
        }
      },
      yAxis: {
        type: 'value',
        axisPointer: {
          show: false
        },
        splitLine: {
          lineStyle: {
            color: utils.grays['300'],
            type: 'dashed'
          }
        },
        boundaryGap: false,
        axisLabel: {
          show: true,
          color: utils.grays['400'],
          margin: 15
        },
        axisTick: {
          show: false
        },
        axisLine: {
          show: false
        }
      },
      series: [{
        type: 'line',
        data: monthsnumber[0],
        lineStyle: {
          color: utils.colors.primary
        },
        itemStyle: {
          borderColor: utils.colors.primary,
          borderWidth: 2
        },
        symbol: 'circle',
        symbolSize: 10,
        smooth: false,
        hoverAnimation: true,
        areaStyle: {
          color: {
            type: 'linear',
            x: 0,
            y: 0,
            x2: 0,
            y2: 1,
            colorStops: [{
              offset: 0,
              color: utils.rgbaColor(utils.colors.primary, 0.2)
            }, {
              offset: 1,
              color: utils.rgbaColor(utils.colors.primary, 0)
            }]
          }
        }
      }],
      grid: {
        right: '28px',
        left: '40px',
        bottom: '15%',
        top: '5%'
      }
    };

    var _options2 = window._.merge(_defaultOptions2, _userOptions2);

    _chart2.setOption(_options2); // Change chart options accordiong to the selected month


    utils.$document.on(Events.CHANGE, Selector.SELECT_MONTH, function (e) {
      var $field = $(e.target);
      var month = $field.val();
      var data = monthsnumber[month];

      _chart2.setOption({
        tooltip: {
          formatter: function formatter(params) {
            var _params$2 = params[0],
                name = _params$2.name,
                value = _params$2.value;
            var date = new Date(name);
            return months[month] + " " + date.getDate() + ", " + value;
          }
        },
        xAxis: {
          axisLabel: {
            formatter: function formatter(value) {
              var date = new Date(value);
              return months[$field.val()] + " " + date.getDate();
            },
            margin: 15
          }
        },
        series: [{
          data: data
        }]
      });
    });
  } //
  // ─── BAR CHART TOP PRODUCTS ──────────────────────────────────────────────────────────────────
  //


  var $echartBarTopProducts = document.querySelector(Selector.ECHART_BAR_TOP_PRODUCTS);

  if ($echartBarTopProducts) {
    var _data = [['product', '2019', '2018'], ['Boots4', 43, 85], ['Reign Pro', 83, 73], ['Slick', 86, 62], ['Falcon', 72, 53], ['Sparrow', 80, 50], ['Hideway', 50, 70], ['Freya', 80, 90] // ['Raven Pro', 60, 70],
    // ['Posh', 80, 70],
    ];

    var _$this3 = $($echartBarTopProducts);

    var _userOptions3 = _$this3.data('options');

    var _chart3 = window.echarts.init($echartBarTopProducts);

    var _defaultOptions3 = {
      color: [utils.colors.primary, utils.grays['300']],
      dataset: {
        source: _data
      },
      tooltip: {
        trigger: 'item',
        padding: [7, 10],
        backgroundColor: utils.grays.white,
        borderColor: utils.grays['300'],
        borderWidth: 1,
        textStyle: {
          color: utils.colors.dark
        },
        transitionDuration: 0,
        position: function position(pos, params, dom, rect, size) {
          return getPosition(pos, params, dom, rect, size);
        },
        formatter: function formatter(params) {
          return "<div class=\"font-weight-semi-bold\">" + params.seriesName + "</div><div class=\"fs--1 text-600\"><strong>" + params.name + ":</strong> " + params.value[params.componentIndex + 1] + "</div>";
        }
      },
      legend: {
        data: ['2019', '2018'],
        left: 'left',
        itemWidth: 10,
        itemHeight: 10,
        borderRadius: 0,
        icon: 'circle',
        inactiveColor: utils.grays['500'],
        textStyle: {
          color: utils.grays['700']
        }
      },
      xAxis: {
        type: 'category',
        axisLabel: {
          color: utils.grays['400']
        },
        axisLine: {
          lineStyle: {
            color: utils.grays['300'],
            type: 'dashed'
          }
        },
        axisTick: false,
        boundaryGap: true
      },
      yAxis: {
        axisPointer: {
          type: 'none'
        },
        axisTick: 'none',
        splitLine: {
          lineStyle: {
            color: utils.grays['300'],
            type: 'dashed'
          }
        },
        axisLine: {
          show: false
        },
        axisLabel: {
          color: utils.grays['400']
        }
      },
      series: [{
        type: 'bar',
        barWidth: '12%',
        barGap: '30%',
        label: {
          normal: {
            show: false
          }
        },
        z: 10,
        itemStyle: {
          normal: {
            barBorderRadius: [10, 10, 0, 0],
            color: utils.colors.primary
          }
        }
      }, {
        type: 'bar',
        barWidth: '12%',
        barGap: '30%',
        label: {
          normal: {
            show: false
          }
        },
        itemStyle: {
          normal: {
            barBorderRadius: [4, 4, 0, 0],
            color: utils.grays[300]
          }
        }
      }],
      grid: {
        right: '0',
        left: '30px',
        bottom: '10%',
        top: '20%'
      }
    };

    var _options3 = window._.merge(_defaultOptions3, _userOptions3);

    _chart3.setOption(_options3);
  } //
  // ─── PIE CHART ──────────────────────────────────────────────────────────────────
  //


  var $pieChartRevenue = document.querySelector(Selector.ECHART_DOUGHNUT);

  if ($pieChartRevenue) {
    var _$this4 = $($pieChartRevenue);

    var _userOptions4 = _$this4.data('options');

    var _chart4 = window.echarts.init($pieChartRevenue);

    var _defaultOptions4 = {
      color: [utils.colors.primary, utils.colors.info, utils.grays[300]],
      tooltip: {
        trigger: 'item',
        padding: [7, 10],
        backgroundColor: utils.grays.white,
        textStyle: {
          color: utils.grays.black
        },
        transitionDuration: 0,
        borderColor: utils.grays['300'],
        borderWidth: 1,
        formatter: function formatter(params) {
          return "<strong>" + params.data.name + ":</strong> " + params.percent + "%";
        }
      },
      position: function position(pos, params, dom, rect, size) {
        return getPosition(pos, params, dom, rect, size);
      },
      legend: {
        show: false
      },
      series: [{
        type: 'pie',
        radius: ['100%', '87%'],
        avoidLabelOverlap: false,
        hoverAnimation: false,
        itemStyle: {
          borderWidth: 2,
          borderColor: utils.grays.white
        },
        label: {
          normal: {
            show: false,
            position: 'center',
            textStyle: {
              fontSize: '20',
              fontWeight: '500',
              color: utils.grays['700']
            }
          },
          emphasis: {
            show: false
          }
        },
        labelLine: {
          normal: {
            show: false
          }
        },
        data: [{
          value: 5300000,
          name: 'Samsung'
        }, {
          value: 1900000,
          name: 'Huawei'
        }, {
          value: 2000000,
          name: 'Apple'
        }]
      }]
    };

    var _options4 = window._.merge(_defaultOptions4, _userOptions4);

    _chart4.setOption(_options4);
  } //
  // ─── ECHART FIX ON WINDOW RESIZE ────────────────────────────────────────────────
  //


  var $echarts = document.querySelectorAll('[data-echart-responsive]');

  window.onresize = function () {
    if ($echarts.length) {
      $.each($echarts, function (item, value) {
        if ($(value).data('echart-responsive')) {
          window.echarts.init(value).resize();
        }
      });
    }
  };
});
/*-----------------------------------------------
|   On page scroll for #id targets
-----------------------------------------------*/

utils.$document.ready(function ($) {
  $('a[data-fancyscroll]').click(function scrollTo(e) {
    // const $this = $(e.currentTarget);
    var $this = $(this);

    if (utils.location.pathname === $this[0].pathname && utils.location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && utils.location.hostname === this.hostname) {
      e.preventDefault();
      var target = $(this.hash);
      target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");

      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top - ($this.data('offset') || 0)
        }, 400, 'swing', function () {
          var hash = $this.attr('href');
          window.history.pushState ? window.history.pushState(null, null, hash) : window.location.hash = hash;
        });
        return false;
      }
    }

    return true;
  });
  var hash = window.location.hash;

  if (hash && document.getElementById(hash.slice(1))) {
    var $this = $(hash);
    $('html,body').animate({
      scrollTop: $this.offset().top - $("a[href='" + hash + "']").data('offset')
    }, 400, 'swing', function () {
      window.history.pushState ? window.history.pushState(null, null, hash) : window.location.hash = hash;
    });
  }
});
/*-----------------------------------------------
|   File Input
-----------------------------------------------*/

utils.$document.ready(function () {
  $('.custom-file-input').on('change', function (e) {
    var $this = $(e.currentTarget);
    var fileName = $this.val().split('\\').pop();
    $this.next('.custom-file-label').addClass('selected').html(fileName);
  });
});
/*-----------------------------------------------
|   Flatpickr
-----------------------------------------------*/

utils.$document.ready(function () {
  var datetimepicker = $('.datetimepicker');
  datetimepicker.length && datetimepicker.each(function (index, value) {
    var $this = $(value);
    var options = $.extend({
      dateFormat: 'd/m/y',
      disableMobile: true
    }, $this.data('options'));
    $this.flatpickr(options);
  });
});
/*-----------------------------------------------
|   Bootstrap validation
-----------------------------------------------*/

utils.$document.ready(function () {
  window.addEventListener('load', function () {
    // Fetch all the forms we want to apply theme Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation'); // Loop over them and prevent submission

    Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add('was-validated');
      }, false);
    });
  }, false);
}); // /*-----------------------------------------------
// |   Full Calendar
// -----------------------------------------------*/

var merge = window._.merge;

var renderCalendar = function renderCalendar(el, option) {
  var options = merge({
    themeSystem: 'bootstrap',
    initialView: 'dayGridMonth',
    editable: true,
    direction: $('html').attr('dir'),
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    buttonText: {
      month: 'Month',
      week: 'Week',
      day: 'Day'
    }
  }, option);
  var calendar = window.FullCalendar && new window.FullCalendar.Calendar(el, options);
  calendar.render();
  $('.navbar-vertical-toggle').on('navbar.vertical.toggle', function () {
    return calendar.updateSize();
  });
  return calendar;
};

utils.$document.ready(function () {
  var calendars = $('[data-calendar]');

  if (calendars.length) {
    calendars.each(function (index, calendar) {
      renderCalendar(calendar, JSON.parse(calendar.dataset.calendar));
    });
  }

  window.FontAwesome.config.autoReplaceSvg = 'nest';
});
/*-----------------------------------------------
|   Gooogle Map
-----------------------------------------------*/

/*
  global google
*/

function initMap() {
  var $googlemaps = $('.googlemap');
  var isDark = storage.isDark;

  if ($googlemaps.length) {
    // Visit https://snazzymaps.com/ for more themes
    var mapStyles = {
      Default: [{
        featureType: 'water',
        elementType: 'geometry',
        stylers: [{
          color: '#e9e9e9'
        }, {
          lightness: 17
        }]
      }, {
        featureType: 'landscape',
        elementType: 'geometry',
        stylers: [{
          color: '#f5f5f5'
        }, {
          lightness: 20
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#ffffff'
        }, {
          lightness: 17
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#ffffff'
        }, {
          lightness: 29
        }, {
          weight: 0.2
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'geometry',
        stylers: [{
          color: '#ffffff'
        }, {
          lightness: 18
        }]
      }, {
        featureType: 'road.local',
        elementType: 'geometry',
        stylers: [{
          color: '#ffffff'
        }, {
          lightness: 16
        }]
      }, {
        featureType: 'poi',
        elementType: 'geometry',
        stylers: [{
          color: '#f5f5f5'
        }, {
          lightness: 21
        }]
      }, {
        featureType: 'poi.park',
        elementType: 'geometry',
        stylers: [{
          color: '#dedede'
        }, {
          lightness: 21
        }]
      }, {
        elementType: 'labels.text.stroke',
        stylers: [{
          visibility: 'on'
        }, {
          color: '#ffffff'
        }, {
          lightness: 16
        }]
      }, {
        elementType: 'labels.text.fill',
        stylers: [{
          saturation: 36
        }, {
          color: '#333333'
        }, {
          lightness: 40
        }]
      }, {
        elementType: 'labels.icon',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'transit',
        elementType: 'geometry',
        stylers: [{
          color: '#f2f2f2'
        }, {
          lightness: 19
        }]
      }, {
        featureType: 'administrative',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#fefefe'
        }, {
          lightness: 20
        }]
      }, {
        featureType: 'administrative',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#fefefe'
        }, {
          lightness: 17
        }, {
          weight: 1.2
        }]
      }],
      Gray: [{
        featureType: 'all',
        elementType: 'labels.text.fill',
        stylers: [{
          saturation: 36
        }, {
          color: '#000000'
        }, {
          lightness: 40
        }]
      }, {
        featureType: 'all',
        elementType: 'labels.text.stroke',
        stylers: [{
          visibility: 'on'
        }, {
          color: '#000000'
        }, {
          lightness: 16
        }]
      }, {
        featureType: 'all',
        elementType: 'labels.icon',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'administrative',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 20
        }]
      }, {
        featureType: 'administrative',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 17
        }, {
          weight: 1.2
        }]
      }, {
        featureType: 'landscape',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 20
        }]
      }, {
        featureType: 'poi',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 21
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 17
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 29
        }, {
          weight: 0.2
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 18
        }]
      }, {
        featureType: 'road.local',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 16
        }]
      }, {
        featureType: 'transit',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 19
        }]
      }, {
        featureType: 'water',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 17
        }]
      }],
      Midnight: [{
        featureType: 'all',
        elementType: 'labels.text.fill',
        stylers: [{
          color: '#ffffff'
        }]
      }, {
        featureType: 'all',
        elementType: 'labels.text.stroke',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 13
        }]
      }, {
        featureType: 'administrative',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#000000'
        }]
      }, {
        featureType: 'administrative',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#144b53'
        }, {
          lightness: 14
        }, {
          weight: 1.4
        }]
      }, {
        featureType: 'landscape',
        elementType: 'all',
        stylers: [{
          color: '#08304b'
        }]
      }, {
        featureType: 'poi',
        elementType: 'geometry',
        stylers: [{
          color: '#0c4152'
        }, {
          lightness: 5
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#000000'
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#0b434f'
        }, {
          lightness: 25
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#000000'
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#0b3d51'
        }, {
          lightness: 16
        }]
      }, {
        featureType: 'road.local',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }]
      }, {
        featureType: 'transit',
        elementType: 'all',
        stylers: [{
          color: '#146474'
        }]
      }, {
        featureType: 'water',
        elementType: 'all',
        stylers: [{
          color: '#021019'
        }]
      }],
      Hopper: [{
        featureType: 'water',
        elementType: 'geometry',
        stylers: [{
          hue: '#165c64'
        }, {
          saturation: 34
        }, {
          lightness: -69
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'landscape',
        elementType: 'geometry',
        stylers: [{
          hue: '#b7caaa'
        }, {
          saturation: -14
        }, {
          lightness: -18
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'landscape.man_made',
        elementType: 'all',
        stylers: [{
          hue: '#cbdac1'
        }, {
          saturation: -6
        }, {
          lightness: -9
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'road',
        elementType: 'geometry',
        stylers: [{
          hue: '#8d9b83'
        }, {
          saturation: -89
        }, {
          lightness: -12
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry',
        stylers: [{
          hue: '#d4dad0'
        }, {
          saturation: -88
        }, {
          lightness: 54
        }, {
          visibility: 'simplified'
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'geometry',
        stylers: [{
          hue: '#bdc5b6'
        }, {
          saturation: -89
        }, {
          lightness: -3
        }, {
          visibility: 'simplified'
        }]
      }, {
        featureType: 'road.local',
        elementType: 'geometry',
        stylers: [{
          hue: '#bdc5b6'
        }, {
          saturation: -89
        }, {
          lightness: -26
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'poi',
        elementType: 'geometry',
        stylers: [{
          hue: '#c17118'
        }, {
          saturation: 61
        }, {
          lightness: -45
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'poi.park',
        elementType: 'all',
        stylers: [{
          hue: '#8ba975'
        }, {
          saturation: -46
        }, {
          lightness: -28
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'transit',
        elementType: 'geometry',
        stylers: [{
          hue: '#a43218'
        }, {
          saturation: 74
        }, {
          lightness: -51
        }, {
          visibility: 'simplified'
        }]
      }, {
        featureType: 'administrative.province',
        elementType: 'all',
        stylers: [{
          hue: '#ffffff'
        }, {
          saturation: 0
        }, {
          lightness: 100
        }, {
          visibility: 'simplified'
        }]
      }, {
        featureType: 'administrative.neighborhood',
        elementType: 'all',
        stylers: [{
          hue: '#ffffff'
        }, {
          saturation: 0
        }, {
          lightness: 100
        }, {
          visibility: 'off'
        }]
      }, {
        featureType: 'administrative.locality',
        elementType: 'labels',
        stylers: [{
          hue: '#ffffff'
        }, {
          saturation: 0
        }, {
          lightness: 100
        }, {
          visibility: 'off'
        }]
      }, {
        featureType: 'administrative.land_parcel',
        elementType: 'all',
        stylers: [{
          hue: '#ffffff'
        }, {
          saturation: 0
        }, {
          lightness: 100
        }, {
          visibility: 'off'
        }]
      }, {
        featureType: 'administrative',
        elementType: 'all',
        stylers: [{
          hue: '#3a3935'
        }, {
          saturation: 5
        }, {
          lightness: -57
        }, {
          visibility: 'off'
        }]
      }, {
        featureType: 'poi.medical',
        elementType: 'geometry',
        stylers: [{
          hue: '#cba923'
        }, {
          saturation: 50
        }, {
          lightness: -46
        }, {
          visibility: 'on'
        }]
      }],
      Beard: [{
        featureType: 'poi.business',
        elementType: 'labels.text',
        stylers: [{
          visibility: 'on'
        }, {
          color: '#333333'
        }]
      }],
      AssassianCreed: [{
        featureType: 'all',
        elementType: 'all',
        stylers: [{
          visibility: 'on'
        }]
      }, {
        featureType: 'all',
        elementType: 'labels',
        stylers: [{
          visibility: 'off'
        }, {
          saturation: '-100'
        }]
      }, {
        featureType: 'all',
        elementType: 'labels.text.fill',
        stylers: [{
          saturation: 36
        }, {
          color: '#000000'
        }, {
          lightness: 40
        }, {
          visibility: 'off'
        }]
      }, {
        featureType: 'all',
        elementType: 'labels.text.stroke',
        stylers: [{
          visibility: 'off'
        }, {
          color: '#000000'
        }, {
          lightness: 16
        }]
      }, {
        featureType: 'all',
        elementType: 'labels.icon',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'administrative',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 20
        }]
      }, {
        featureType: 'administrative',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 17
        }, {
          weight: 1.2
        }]
      }, {
        featureType: 'landscape',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 20
        }]
      }, {
        featureType: 'landscape',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#4d6059'
        }]
      }, {
        featureType: 'landscape',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#4d6059'
        }]
      }, {
        featureType: 'landscape.natural',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#4d6059'
        }]
      }, {
        featureType: 'poi',
        elementType: 'geometry',
        stylers: [{
          lightness: 21
        }]
      }, {
        featureType: 'poi',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#4d6059'
        }]
      }, {
        featureType: 'poi',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#4d6059'
        }]
      }, {
        featureType: 'road',
        elementType: 'geometry',
        stylers: [{
          visibility: 'on'
        }, {
          color: '#7f8d89'
        }]
      }, {
        featureType: 'road',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#7f8d89'
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#7f8d89'
        }, {
          lightness: 17
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#7f8d89'
        }, {
          lightness: 29
        }, {
          weight: 0.2
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 18
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#7f8d89'
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#7f8d89'
        }]
      }, {
        featureType: 'road.local',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 16
        }]
      }, {
        featureType: 'road.local',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#7f8d89'
        }]
      }, {
        featureType: 'road.local',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#7f8d89'
        }]
      }, {
        featureType: 'transit',
        elementType: 'geometry',
        stylers: [{
          color: '#000000'
        }, {
          lightness: 19
        }]
      }, {
        featureType: 'water',
        elementType: 'all',
        stylers: [{
          color: '#2b3638'
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'water',
        elementType: 'geometry',
        stylers: [{
          color: '#2b3638'
        }, {
          lightness: 17
        }]
      }, {
        featureType: 'water',
        elementType: 'geometry.fill',
        stylers: [{
          color: '#24282b'
        }]
      }, {
        featureType: 'water',
        elementType: 'geometry.stroke',
        stylers: [{
          color: '#24282b'
        }]
      }, {
        featureType: 'water',
        elementType: 'labels',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'water',
        elementType: 'labels.text',
        stylers: [{
          visibility: 'off '
        }]
      }, {
        featureType: 'water',
        elementType: 'labels.text.fill',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'water',
        elementType: 'labels.text.stroke',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'water',
        elementType: 'labels.icon',
        stylers: [{
          visibility: 'off'
        }]
      }],
      SubtleGray: [{
        featureType: 'administrative',
        elementType: 'all',
        stylers: [{
          saturation: '-100'
        }]
      }, {
        featureType: 'administrative.province',
        elementType: 'all',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'landscape',
        elementType: 'all',
        stylers: [{
          saturation: -100
        }, {
          lightness: 65
        }, {
          visibility: 'on'
        }]
      }, {
        featureType: 'poi',
        elementType: 'all',
        stylers: [{
          saturation: -100
        }, {
          lightness: '50'
        }, {
          visibility: 'simplified'
        }]
      }, {
        featureType: 'road',
        elementType: 'all',
        stylers: [{
          saturation: -100
        }]
      }, {
        featureType: 'road.highway',
        elementType: 'all',
        stylers: [{
          visibility: 'simplified'
        }]
      }, {
        featureType: 'road.arterial',
        elementType: 'all',
        stylers: [{
          lightness: '30'
        }]
      }, {
        featureType: 'road.local',
        elementType: 'all',
        stylers: [{
          lightness: '40'
        }]
      }, {
        featureType: 'transit',
        elementType: 'all',
        stylers: [{
          saturation: -100
        }, {
          visibility: 'simplified'
        }]
      }, {
        featureType: 'water',
        elementType: 'geometry',
        stylers: [{
          hue: '#ffff00'
        }, {
          lightness: -25
        }, {
          saturation: -97
        }]
      }, {
        featureType: 'water',
        elementType: 'labels',
        stylers: [{
          lightness: -25
        }, {
          saturation: -100
        }]
      }],
      Tripitty: [{
        featureType: 'all',
        elementType: 'labels',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'administrative',
        elementType: 'all',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'landscape',
        elementType: 'all',
        stylers: [{
          color: '#2c5ca5'
        }]
      }, {
        featureType: 'poi',
        elementType: 'all',
        stylers: [{
          color: '#2c5ca5'
        }]
      }, {
        featureType: 'road',
        elementType: 'all',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'transit',
        elementType: 'all',
        stylers: [{
          visibility: 'off'
        }]
      }, {
        featureType: 'water',
        elementType: 'all',
        stylers: [{
          color: '#193a70'
        }, {
          visibility: 'on'
        }]
      }]
    };
    $googlemaps.each(function (index, value) {
      var $googlemap = $(value);
      var latLng = $googlemap.data('latlng').split(',');
      var markerPopup = $googlemap.html();
      var icon = $googlemap.data('icon') ? $googlemap.data('icon') : 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png';
      var zoom = $googlemap.data('zoom');
      var mapStyle = isDark ? 'Midnight' : $googlemap.data('theme');
      var mapElement = value;

      if ($googlemap.data('theme') === 'streetview') {
        var pov = $googlemap.data('pov');
        var _mapOptions = {
          position: {
            lat: Number(latLng[0]),
            lng: Number(latLng[1])
          },
          pov: pov,
          zoom: zoom,
          gestureHandling: 'none',
          scrollwheel: false
        };
        return new google.maps.StreetViewPanorama(mapElement, _mapOptions);
      }

      var mapOptions = {
        zoom: zoom,
        scrollwheel: $googlemap.data('scrollwheel'),
        center: new google.maps.LatLng(latLng[0], latLng[1]),
        styles: mapStyles[mapStyle]
      };
      var map = new google.maps.Map(mapElement, mapOptions);
      var infowindow = new google.maps.InfoWindow({
        content: markerPopup
      });
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(latLng[0], latLng[1]),
        icon: icon,
        map: map
      });
      marker.addListener('click', function () {
        infowindow.open(map, marker);
      });
      return null;
    });
  }
}
/*-----------------------------------------------
|   Jquery Validation
-----------------------------------------------*/


utils.$document.ready(function () {
  var forms = $('.form-validation');
  forms.length && forms.each(function (index, value) {
    var $this = $(value);
    var options = $.extend({}, $this.data('options'));
    $this.validate(options);
  });
});
/*-----------------------------------------------
|   Draggable
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selectors = {
    ADD_LIST_FORM: '#add-list-form',
    KANBAN_COLUMN: '.kanban-column',
    BTN_ADD_CARD: '.btn-add-card',
    KANBAN_ITEMS_CONTAINER: '.kanban-items-container',
    COLLAPSE: '.collapse',
    COLLAPSE_CLOSE: "[data-dismiss='collapse']",
    ADD_CARD: '.add-card',
    ADD_LIST: '.add-list',
    MODAL_ANCHOR: "[data-toggle='modal'] a",
    HIDE_ADD_CARD_FORM: '.hide-add-card-form'
  };
  var Events = {
    TOGGLE_BS_COLLAPSE: 'show.bs.collapse hide.bs.collapse',
    SHOWN_BS_COLLAPSE: 'shown.bs.collapse',
    CLICK: 'click'
  };
  var ClassNames = {
    FORM_ADDED: 'form-added',
    D_NONE: 'd-none'
  }; // Hide button when Add list form is shown

  $(Selectors.ADD_LIST_FORM).on(Events.TOGGLE_BS_COLLAPSE, function (e) {
    var $this = $(e.currentTarget);
    $this.next().toggleClass(ClassNames.D_NONE);
  }); // Show card add form and scroll to the bottom

  utils.$document.on(Events.CLICK, Selectors.BTN_ADD_CARD, function (e) {
    var $this = $(e.currentTarget);
    var $column = $this.closest(Selectors.KANBAN_COLUMN);
    var $container = $column.find(Selectors.KANBAN_ITEMS_CONTAINER);
    var scrollHeight = $container[0].scrollHeight;
    $column.addClass(ClassNames.FORM_ADDED);
    $container.scrollTop(scrollHeight);
    $container.find(Selectors.ADD_CARD).focus();
  }); // Hide card add form

  utils.$document.on(Events.CLICK, Selectors.HIDE_ADD_CARD_FORM, function (e) {
    var $this = $(e.currentTarget);
    var $column = $this.closest(Selectors.KANBAN_COLUMN);
    $column.removeClass(ClassNames.FORM_ADDED);
  }); // Focus add list form on form shown

  $(Selectors.ADD_LIST_FORM).on(Events.SHOWN_BS_COLLAPSE, function (e) {
    var $this = $(e.currentTarget);
    $this.find(Selectors.ADD_LIST).focus();
  });
  utils.$document.on(Events.CLICK, Selectors.MODAL_ANCHOR, function (e) {
    e.stopPropagation();
  }); // Close collapse when corresponding close button is clicked

  utils.$document.on(Events.CLICK, Selectors.COLLAPSE_CLOSE, function (e) {
    var $this = $(e.currentTarget);
    $this.closest(Selectors.COLLAPSE).collapse('hide');
  });
});
/*-----------------------------------------------
|   Leaflet
-----------------------------------------------*/

utils.$document.ready(function () {
  var points = [{
    lat: 53.958332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 52.958332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 51.958332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 53.958332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 54.958332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 55.958332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 53.908332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 53.008332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 53.158332,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 53.000032,
    "long": -1.080278,
    name: 'Diana Meyer',
    street: 'Slude Strand 27',
    location: '1130 Kobenhavn'
  }, {
    lat: 52.292001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 52.392001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 51.492001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 51.192001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 52.292001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 54.392001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 51.292001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 52.102001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 52.202001,
    "long": -2.22,
    name: 'Anke Schroder',
    street: 'Industrivej 54',
    location: '4140 Borup'
  }, {
    lat: 51.063202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.363202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.463202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.563202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.763202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.863202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.963202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.000202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.000202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.163202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 52.263202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 53.463202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 55.163202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 56.263202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 56.463202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 56.563202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 56.663202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 56.763202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 56.863202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 56.963202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 57.973202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 57.163202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.163202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.263202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.363202,
    "long": -1.308,
    name: 'Tobias Vogel',
    street: 'Mollebakken 33',
    location: '3650 Olstykke'
  }, {
    lat: 51.409,
    "long": -2.647,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.68,
    "long": -1.49,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 50.259998,
    "long": -5.051,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 54.906101,
    "long": -1.38113,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.383331,
    "long": -1.466667,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.483002,
    "long": -2.2931,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.509865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.109865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.209865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.309865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.409865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.609865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.709865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.809865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 51.909865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.109865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.209865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.309865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.409865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.509865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.609865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.709865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.809865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.909865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.519865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.529865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.539865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.549865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 52.549865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.109865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.209865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.319865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.329865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.409865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.559865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.619865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.629865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.639865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.649865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.669865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.669865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.719865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.739865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.749865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.759865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.769865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.769865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.819865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.829865,
    "long": -0.118092,
    name: 'Richard Hendricks',
    street: '37 Seafield Place',
    location: 'London'
  }, {
    lat: 53.483959,
    "long": -2.244644,
    name: 'Ethel B. Brooks',
    street: '2576 Sun Valley Road'
  }, {
    lat: 40.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 39.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 38.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 37.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 40.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 41.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 42.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 43.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 44.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 45.737,
    "long": -73.923,
    name: 'Marshall D. Lewis',
    street: '1489 Michigan Avenue',
    location: 'Michigan'
  }, {
    lat: 46.7128,
    "long": 74.006,
    name: 'Elizabeth C. Lyons',
    street: '4553 Kenwood Place',
    location: 'Fort Lauderdale'
  }, {
    lat: 40.7128,
    "long": 74.1181,
    name: 'Elizabeth C. Lyons',
    street: '4553 Kenwood Place',
    location: 'Fort Lauderdale'
  }, {
    lat: 14.235,
    "long": 51.9253,
    name: 'Ralph D. Wylie',
    street: '3186 Levy Court',
    location: 'North Reading'
  }, {
    lat: 15.235,
    "long": 51.9253,
    name: 'Ralph D. Wylie',
    street: '3186 Levy Court',
    location: 'North Reading'
  }, {
    lat: 16.235,
    "long": 51.9253,
    name: 'Ralph D. Wylie',
    street: '3186 Levy Court',
    location: 'North Reading'
  }, {
    lat: 14.235,
    "long": 51.9253,
    name: 'Ralph D. Wylie',
    street: '3186 Levy Court',
    location: 'North Reading'
  }, {
    lat: 15.8267,
    "long": 47.9218,
    name: 'Hope A. Atkins',
    street: '3715 Hillcrest Drive',
    location: 'Seattle'
  }, {
    lat: 15.9267,
    "long": 47.9218,
    name: 'Hope A. Atkins',
    street: '3715 Hillcrest Drive',
    location: 'Seattle'
  }, {
    lat: 23.4425,
    "long": 58.4438,
    name: 'Samuel R. Bailey',
    street: '2883 Raoul Wallenberg Place',
    location: 'Cheshire'
  }, {
    lat: 23.5425,
    "long": 58.3438,
    name: 'Samuel R. Bailey',
    street: '2883 Raoul Wallenberg Place',
    location: 'Cheshire'
  }, {
    lat: -37.8927369333,
    "long": 175.4087452333,
    name: 'Samuel R. Bailey',
    street: '3228 Glory Road',
    location: 'Nashville'
  }, {
    lat: -38.9064188833,
    "long": 175.4441556833,
    name: 'Samuel R. Bailey',
    street: '3228 Glory Road',
    location: 'Nashville'
  }, {
    lat: -12.409874,
    "long": -65.596832,
    name: 'Ann J. Perdue',
    street: '921 Ella Street',
    location: 'Dublin'
  }, {
    lat: -22.090887,
    "long": -57.411827,
    name: 'Jorge C. Woods',
    street: '4800 North Bend River Road',
    location: 'Allen'
  }, {
    lat: -19.019585,
    "long": -65.261963,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: -16.500093,
    "long": -68.214684,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: -17.413977,
    "long": -66.165321,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: -16.489689,
    "long": -68.119293,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: 54.766323,
    "long": 3.08603729,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: 54.866323,
    "long": 3.08603729,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: 49.537685,
    "long": 3.08603729,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: 54.715424,
    "long": 0.509207,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: 44.891666,
    "long": 10.136665,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: 48.078335,
    "long": 14.535004,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: -26.358055,
    "long": 27.398056,
    name: 'Russ E. Panek',
    street: '4068 Hartland Avenue',
    location: 'Appleton'
  }, {
    lat: -29.1,
    "long": 26.2167,
    name: 'Wilbur J. Dry',
    street: '2043 Jadewood Drive',
    location: 'Northbrook'
  }, {
    lat: -29.883333,
    "long": 31.049999,
    name: 'Wilbur J. Dry',
    street: '2043 Jadewood Drive',
    location: 'Northbrook'
  }, {
    lat: -26.266111,
    "long": 27.865833,
    name: 'Wilbur J. Dry',
    street: '2043 Jadewood Drive',
    location: 'Northbrook'
  }, {
    lat: -29.087217,
    "long": 26.154898,
    name: 'Wilbur J. Dry',
    street: '2043 Jadewood Drive',
    location: 'Northbrook'
  }, {
    lat: -33.958252,
    "long": 25.619022,
    name: 'Wilbur J. Dry',
    street: '2043 Jadewood Drive',
    location: 'Northbrook'
  }, {
    lat: -33.977074,
    "long": 22.457581,
    name: 'Wilbur J. Dry',
    street: '2043 Jadewood Drive',
    location: 'Northbrook'
  }, {
    lat: -26.563404,
    "long": 27.844164,
    name: 'Wilbur J. Dry',
    street: '2043 Jadewood Drive',
    location: 'Northbrook'
  }, {
    lat: 51.21389,
    "long": -102.462776,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 52.321945,
    "long": -106.584167,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 50.288055,
    "long": -107.793892,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 52.7575,
    "long": -108.28611,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 50.393333,
    "long": -105.551941,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 50.930557,
    "long": -102.807777,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 52.856388,
    "long": -104.610001,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 52.289722,
    "long": -106.666664,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 52.201942,
    "long": -105.123055,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 53.278046,
    "long": -110.00547,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 49.13673,
    "long": -102.990959,
    name: 'Joseph B. Poole',
    street: '3364 Lunetta Street',
    location: 'Wichita Falls'
  }, {
    lat: 45.484531,
    "long": -73.597023,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 45.266666,
    "long": -71.900002,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 45.349998,
    "long": -72.51667,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 47.333332,
    "long": -79.433334,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 45.400002,
    "long": -74.033333,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 45.683334,
    "long": -73.433334,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 48.099998,
    "long": -77.783333,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 45.5,
    "long": -72.316666,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 46.349998,
    "long": -72.550003,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 48.119999,
    "long": -69.18,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 45.599998,
    "long": -75.25,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 46.099998,
    "long": -71.300003,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 45.700001,
    "long": -73.633331,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 47.68,
    "long": -68.879997,
    name: 'Claudette D. Nowakowski',
    street: '3742 Farland Avenue',
    location: 'San Antonio'
  }, {
    lat: 46.716667,
    "long": -79.099998,
    name: '299'
  }, {
    lat: 45.016666,
    "long": -72.099998,
    name: '299'
  }];
  var _window2 = window,
      L = _window2.L;
  var mapContainer = document.getElementById('map');

  if (L && mapContainer) {
    var filterColor = ['bright:101%', 'contrast:101%', 'hue:23deg', 'saturate:225%'];
    var tileLayerTheme = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
    var tiles;

    if (storage.isDark) {
      filterColor = ['invert:98%', 'grayscale:69%', 'bright:89%', 'contrast:111%', 'hue:205deg', 'saturate:1000%'];
    }

    if (window.is.ie()) {
      if (storage.isDark) {
        tileLayerTheme = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
      }

      tiles = L.tileLayer(tileLayerTheme, {
        attribution: null,
        transparent: true
      });
    } else {
      tiles = L.tileLayer.colorFilter(tileLayerTheme, {
        attribution: null,
        transparent: true,
        filter: filterColor
      });
    }

    var map = L.map('map', {
      center: L.latLng(10.737, 0),
      zoom: 0,
      layers: [tiles],
      minZoom: 1.3,
      zoomSnap: 0.5,
      dragging: !L.Browser.mobile,
      tap: !L.Browser.mobile
    });
    var mcg = L.markerClusterGroup({
      chunkedLoading: false,
      spiderfyOnMaxZoom: false
    });
    points.map(function (point) {
      var name = point.name,
          location = point.location,
          street = point.street;
      var marker = L.marker(new L.LatLng(point.lat, point["long"]), {
        name: name,
        location: location
      });
      var popupContent = "\n        <h6 class=\"mb-1\">" + name + "</h6>\n        <p class=\"m-0 text-500\">" + street + ", " + location + "</p>\n      ";
      var popup = L.popup({
        minWidth: 180
      }).setContent(popupContent);
      marker.bindPopup(popup);
      mcg.addLayer(marker);
      return true;
    });
    map.addLayer(mcg);
  }
});
/*-----------------------------------------------
|   Lightbox
-----------------------------------------------*/

/*
  global lightbox
*/

utils.$document.ready(function () {
  if ($('[data-lightbox]').length) {
    lightbox.option({
      resizeDuration: 400,
      wrapAround: true,
      fadeDuration: 300,
      imageFadeDuration: 300
    });
  }
});
/*-----------------------------------------------
|   Lottie
-----------------------------------------------*/

utils.$document.ready(function () {
  var lotties = $('.lottie');

  if (lotties.length) {
    lotties.each(function (index, value) {
      var $this = $(value);
      var options = $.extend({
        container: value,
        path: '../img/animated-icons/warning-light.json',
        renderer: 'svg',
        loop: true,
        autoplay: true,
        name: 'Hello World'
      }, $this.data('options'));
      window.bodymovin.loadAnimation(options);
    });
  }
});
/*-----------------------------------------------
|   Modal
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selector = {
    MODAL_THEME: '.modal-theme'
  };
  var DataKey = {
    OPTIONS: 'options'
  };
  var Events = {
    HIDDEN_BS_MODAL: 'hidden.bs.modal'
  };
  var modals = $(Selector.MODAL_THEME);
  var showModal = true;

  if (modals.length) {
    modals.each(function (index, value) {
      var $this = $(value);
      var userOptions = $this.data(DataKey.OPTIONS);
      var options = $.extend({
        autoShow: false,
        autoShowDelay: 0,
        showOnce: false
      }, userOptions);

      if (options.showOnce) {
        var modal = utils.getCookie('modal');
        showModal = modal === null;
      }

      if (options.autoShow && showModal) {
        setTimeout(function () {
          $this.modal('show');
        }, options.autoShowDelay);
      }
    });
  }

  $(Selector.MODAL_THEME).on(Events.HIDDEN_BS_MODAL, function (e) {
    var $this = $(e.currentTarget);
    var userOptions = $this.data(DataKey.OPTIONS);
    var options = $.extend({
      cookieExpireTime: 7200000,
      showOnce: false
    }, userOptions);
    options.showOnce && utils.setCookie('modal', false, options.cookieExpireTime);
  });
});
/*-----------------------------------------------
|   Navbar Top
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selectors = {
    COLLAPSE: '.collapse',
    NAVBAR_NAV: '.navbar-nav',
    NAVBAR_TOP_COMBO: '.navbar-top-combo',
    NAVBAR_VERTICAL: '.navbar-vertical',
    NAVBAR_VERTICAL_DIVIDER: '.navbar-vertical-divider',
    NAVBAR_TOP_COMBO_COLLAPSE: '.navbar-top-combo .collapse',
    MOVEABLE_CONTENT: '[data-move-container]'
  };
  var CLASS_NAME = {
    FLEX_COLUMN: 'flex-column'
  };
  var DATA_KEYS = {
    MOVE_TARGET: 'move-target'
  };
  var $navbarTop = $(Selectors.NAVBAR_TOP_COMBO);
  var $navbarVertical = $(Selectors.NAVBAR_VERTICAL);
  var navbarTopBreakpoint = utils.getBreakpoint($navbarTop);
  var navbarVertcicalBreakpoint = utils.getBreakpoint($navbarVertical);

  var moveNavContent = function moveNavContent(width) {
    if (width < navbarTopBreakpoint) {
      var $navbarTopCollapse = $navbarTop.find(Selectors.COLLAPSE);
      var navbarTopContent = $navbarTopCollapse.html();

      if (navbarTopContent) {
        $navbarTopCollapse.html('');
        var divider = "<div class='navbar-vertical-divider'><hr class='navbar-vertical-hr' /></div>";
        navbarTopContent = "<div data-move-container>" + divider + navbarTopContent + "</div>";
        var targetID = $navbarTop.data(DATA_KEYS.MOVE_TARGET);
        $(navbarTopContent).insertAfter(targetID);
        navbarTopBreakpoint > navbarVertcicalBreakpoint && $(Selectors.MOVEABLE_CONTENT).find(Selectors.NAVBAR_NAV).addClass(CLASS_NAME.FLEX_COLUMN);
      }
    } else {
      var $container = $(Selectors.MOVEABLE_CONTENT);
      var $navbarNav = $container.find(Selectors.NAVBAR_NAV);
      $navbarNav.hasClass(CLASS_NAME.FLEX_COLUMN) && $navbarNav.removeClass(CLASS_NAME.FLEX_COLUMN);
      $container.find(Selectors.NAVBAR_VERTICAL_DIVIDER).remove();
      var content = $container.html();
      $container.remove();
      $(Selectors.NAVBAR_TOP_COMBO_COLLAPSE).html(content);
    }
  };

  moveNavContent(utils.$window.outerWidth());
  utils.$window.on('resize', function () {
    moveNavContent(utils.$window.outerWidth());
  });
});
/*-----------------------------------------------
|   Navbar
-----------------------------------------------*/

utils.$document.ready(function () {
  var $window = utils.$window;
  var navDropShadowFlag = true;
  var ClassName = {
    SHOW: 'show',
    NAVBAR_GLASS_SHADOW: 'navbar-glass-shadow',
    NAVBAR_VERTICAL_COLLAPSED: 'navbar-vertical-collapsed',
    NAVBAR_VERTICAL_COLLAPSE_HOVER: 'navbar-vertical-collapsed-hover'
  };
  var Selector = {
    HTML: 'html',
    NAVBAR: '.navbar:not(.navbar-vertical)',
    NAVBAR_VERTICAL: '.navbar-vertical',
    NAVBAR_VERTICAL_TOGGLE: '.navbar-vertical-toggle',
    NAVBAR_VERTICAL_COLLAPSE: '#navbarVerticalCollapse',
    NAVBAR_VERTICAL_CONTENT: '.navbar-vertical-content',
    NAVBAR_VERTICAL_COLLAPSED: '.navbar-vertical-collapsed',
    NAVBAR_VERTICAL_DROPDOWN_NAV: '.navbar-vertical .navbar-collapse .nav',
    NAVBAR_VERTICAL_COLLAPSED_DROPDOWN_NAV: '.navbar-vertical-collapsed .navbar-vertical .navbar-collapse .nav',
    MAIN_CONTENT: '.main .content',
    NAVBAR_TOP: '.navbar-top',
    OWL_CAROUSEL: '.owl-carousel',
    ECHART_RESPONSIVE: '[data-echart-responsive]'
  };
  var Events = {
    LOAD_SCROLL: 'load scroll',
    SCROLL: 'scroll',
    CLICK: 'click',
    RESIZE: 'resize',
    SHOW_BS_COLLAPSE: 'show.bs.collapse',
    HIDDEN_BS_COLLAPSE: 'hidden.bs.collapse'
  };
  var $html = $(Selector.HTML);
  var $navbar = $(Selector.NAVBAR);
  var $navbarVerticalCollapse = $(Selector.NAVBAR_VERTICAL_COLLAPSE);
  var $navbarVerticalContent = $(Selector.NAVBAR_VERTICAL_CONTENT);
  var navbarVertical = $(Selector.NAVBAR_VERTICAL);
  var breakPoint = utils.getBreakpoint(navbarVertical);

  var setDropShadow = function setDropShadow($elem) {
    if ($elem.scrollTop() > 0 && navDropShadowFlag) {
      $navbar.addClass(ClassName.NAVBAR_GLASS_SHADOW);
    } else {
      $navbar.removeClass(ClassName.NAVBAR_GLASS_SHADOW);
    }
  };

  $window.on(Events.LOAD_SCROLL, function () {
    return setDropShadow($window);
  });
  $navbarVerticalContent.on('scroll', function () {
    if ($window.width() < breakPoint) {
      navDropShadowFlag = true;
      setDropShadow($navbarVerticalContent);
    }
  });
  $navbarVerticalCollapse.on(Events.SHOW_BS_COLLAPSE, function () {
    if ($window.width() < breakPoint) {
      navDropShadowFlag = false;
      setDropShadow($window);
    }
  });
  $navbarVerticalCollapse.on(Events.HIDDEN_BS_COLLAPSE, function () {
    if ($navbarVerticalCollapse.hasClass(ClassName.SHOW) && $window.width() < breakPoint) {
      navDropShadowFlag = false;
    } else {
      navDropShadowFlag = true;
    }

    setDropShadow($window);
  }); // Expand or Collapse vertical navbar on mouse over and out

  $navbarVerticalCollapse.hover(function (e) {
    setTimeout(function () {
      if ($(e.currentTarget).is(':hover')) {
        $(Selector.NAVBAR_VERTICAL_COLLAPSED).addClass(ClassName.NAVBAR_VERTICAL_COLLAPSE_HOVER);
      }
    }, 100);
  }, function () {
    $(Selector.NAVBAR_VERTICAL_COLLAPSED).removeClass(ClassName.NAVBAR_VERTICAL_COLLAPSE_HOVER);
  }); // Set navbar top width from content

  var setNavbarWidth = function setNavbarWidth() {
    var contentWidth = $(Selector.MAIN_CONTENT).width() + 30;
    $(Selector.NAVBAR_TOP).outerWidth(contentWidth);
  }; // Toggle navbar vertical collapse on click


  utils.$document.on(Events.CLICK, Selector.NAVBAR_VERTICAL_TOGGLE, function (e) {
    // Set collapse state on localStorage
    var isNavbarVerticalCollapsed = JSON.parse(localStorage.getItem('isNavbarVerticalCollapsed'));
    localStorage.setItem('isNavbarVerticalCollapsed', !isNavbarVerticalCollapsed); // Toggle class

    $html.toggleClass(ClassName.NAVBAR_VERTICAL_COLLAPSED); // Set navbar top width

    setNavbarWidth(); // Refresh Echarts

    var $echarts = document.querySelectorAll(Selector.ECHART_RESPONSIVE);

    if ($echarts.length) {
      $.each($echarts, function (item, value) {
        if ($(value).data('echart-responsive')) {
          window.echarts.init(value).resize();
        }
      });
    }

    $(e.currentTarget).trigger('navbar.vertical.toggle');
  }); // Set navbar top width on window resize

  $window.on(Events.RESIZE, function () {
    setNavbarWidth();
  });
});
/*-----------------------------------------------
|   Cookie Notice
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selector = {
    NOTICE: '.notice',
    DATA_TOGGLE_NOTICE: "[data-toggle='notice']"
  };
  var DataKeys = {
    OPTIONS: 'options'
  };
  var CookieNames = {
    COOKIE_NOTICE: 'cookieNotice'
  };
  var Events = {
    CLICK: 'click',
    HIDDEN_BS_TOAST: 'hidden.bs.toast'
  };
  var $notices = $(Selector.NOTICE);
  var defaultOptions = {
    autoShow: false,
    autoShowDelay: 0,
    showOnce: false,
    cookieExpireTime: 3600000
  };
  $notices.each(function (index, value) {
    var $this = $(value);
    var options = $.extend(defaultOptions, $this.data(DataKeys.OPTIONS));
    var cookieNotice;

    if (options.showOnce) {
      cookieNotice = utils.getCookie(CookieNames.COOKIE_NOTICE);
    }

    if (options.autoShow && cookieNotice === null) {
      setTimeout(function () {
        return $this.toast('show');
      }, options.autoShowDelay);
    }
  });
  $(Selector.NOTICE).on(Events.HIDDEN_BS_TOAST, function (e) {
    var $this = $(e.currentTarget);
    var options = $.extend(defaultOptions, $this.data(DataKeys.OPTIONS));
    options.showOnce && utils.setCookie(CookieNames.COOKIE_NOTICE, false, options.cookieExpireTime);
  });
  utils.$document.on(Events.CLICK, Selector.DATA_TOGGLE_NOTICE, function (e) {
    e.preventDefault();
    var $this = $(e.currentTarget);
    var $target = $($this.attr('href'));
    $target.hasClass('show') ? $target.toast('hide') : $target.toast('show');
  });
});
/*-----------------------------------------------
|   Owl Carousel
-----------------------------------------------*/

var $carousel = $('.owl-carousel');
utils.$document.ready(function () {
  if ($carousel.length) {
    var Selector = {
      ALL_TIMELINE: '*[data-zanim-timeline]',
      ACTIVE_ITEM: '.owl-item.active'
    };
    var owlZanim = {
      zanimTimeline: function zanimTimeline($el) {
        return $el.find(Selector.ALL_TIMELINE);
      },
      play: function play($el) {
        if (this.zanimTimeline($el).length === 0) return;
        $el.find(Selector.ACTIVE_ITEM + " > " + Selector.ALL_TIMELINE).zanimation(function (animation) {
          animation.play();
        });
      },
      kill: function kill($el) {
        if (this.zanimTimeline($el).length === 0) return;
        this.zanimTimeline($el).zanimation(function (animation) {
          animation.kill();
        });
      }
    };
    $carousel.each(function (index, value) {
      var $this = $(value);
      var options = $this.data('options') || {};
      utils.isRTL() && (options.rtl = true);
      options.navText || (options.navText = ['<span class="fas fa-angle-left"></span>', '<span class="fas fa-angle-right"></span>']);
      options.touchDrag = true;
      $this.owlCarousel($.extend(options || {}, {
        onInitialized: function onInitialized(event) {
          owlZanim.play($(event.target));
        },
        onTranslate: function onTranslate(event) {
          owlZanim.kill($(event.target));
        },
        onTranslated: function onTranslated(event) {
          owlZanim.play($(event.target));
        }
      }));
    });
  }

  var $controllers = $('[data-owl-carousel-controller]');

  if ($controllers.length) {
    $controllers.each(function (index, value) {
      var $this = $(value);
      var $thumbs = $($this.data('owl-carousel-controller'));
      $thumbs.find('.owl-item:first-child').addClass('current');
      $thumbs.on('click', '.item', function (e) {
        var thumbIndex = $(e.target).parents('.owl-item').index();
        $('.owl-item').removeClass('current');
        $(e.target).parents('.owl-item').addClass('current');
        $this.trigger('to.owl.carousel', thumbIndex, 500);
      });
      $this.on('changed.owl.carousel', function (e) {
        var itemIndex = e.item.index;
        var item = itemIndex + 1;
        $('.owl-item').removeClass('current');
        $thumbs.find(".owl-item:nth-child(" + item + ")").addClass('current');
        $thumbs.trigger('to.owl.carousel', itemIndex, 500);
      });
    });
  } // Refresh owlCarousel


  $('.navbar-vertical-toggle').on('navbar.vertical.toggle', function () {
    $carousel.length && $carousel.owlCarousel('refresh');
  });
});
/*-----------------------------------------------
|   Perfect Scrollbar
-----------------------------------------------*/

utils.$document.ready(function () {
  if (window.is.ie() || window.is.edge()) {
    var scrollbars = document.querySelectorAll('.perfect-scrollbar');

    if (scrollbars.length) {
      $.each(scrollbars, function (item, value) {
        var $this = $(value);
        var userOptions = $this.data('options');
        var options = $.extend({
          wheelPropagation: true,
          suppressScrollX: true,
          suppressScrollY: false
        }, userOptions);
        var ps = new PerfectScrollbar(value, options);
        ps.update();
      });
    }
  }
});
/*-----------------------------------------------
|   Inline Player [plyr]
-----------------------------------------------*/

/*
  global Plyr
*/

utils.$document.ready(function () {
  var $players = $('.player');

  if ($players.length) {
    $players.each(function (index, value) {
      return new Plyr($(value), {
        captions: {
          active: true
        }
      });
    });
  }

  return false;
});
/*
 global ProgressBar
*/

utils.$document.ready(function () {
  var merge = window._.merge; // progressbar.js@1.0.0 version is used
  // Docs: http://progressbarjs.readthedocs.org/en/1.0.0/

  /*-----------------------------------------------
  |   Progress Circle
  -----------------------------------------------*/

  var progresCircle = $('.progress-circle');

  if (progresCircle.length) {
    progresCircle.each(function (index, value) {
      var $this = $(value);
      var userOptions = $this.data('options');
      var defaultOptions = {
        strokeWidth: 2,
        trailWidth: 2,
        trailColor: utils.grays['200'],
        easing: 'easeInOut',
        duration: 3000,
        svgStyle: {
          'stroke-linecap': 'round',
          display: 'block',
          width: '100%'
        },
        text: {
          autoStyleContainer: false
        },
        // Set default step function for all animate calls
        step: function step(state, circle) {
          // Change stroke color during progress
          // circle.path.setAttribute('stroke', state.color);
          // Change stroke width during progress
          // circle.path.setAttribute('stroke-width', state.width);
          var percentage = Math.round(circle.value() * 100);
          circle.setText("<span class='value'>" + percentage + "<b>%</b></span> <span>" + (userOptions.text || '') + "</span>");
        }
      }; // Assign default color for IE

      var color = userOptions.color && userOptions.color.includes('url');

      if (window.is.ie() && color) {
        userOptions.color = utils.colors.primary;
      }

      var options = merge(defaultOptions, userOptions);
      var bar = new ProgressBar.Circle(value, options);
      var linearGradient = "<defs>\n          <linearGradient id=\"gradient\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"0%\" gradientUnits=\"userSpaceOnUse\">\n            <stop offset=\"0%\" stop-color='#1970e2' />\n            <stop offset=\"100%\" stop-color='#4695ff' />\n          </linearGradient>\n        </defs>"; // Disable gradient color in IE

      !window.is.ie() && bar.svg.insertAdjacentHTML('beforeEnd', linearGradient);
      var playProgressTriggered = false;

      var progressCircleAnimation = function progressCircleAnimation() {
        if (!playProgressTriggered) {
          if (utils.isScrolledIntoView(value) || utils.nua.match(/puppeteer/i)) {
            bar.animate(options.progress / 100);
            playProgressTriggered = true;
          }
        }

        return playProgressTriggered;
      };

      progressCircleAnimation();
      utils.$window.scroll(function () {
        progressCircleAnimation();
      });
    });
  }
  /*-----------------------------------------------
  |   Progress Line
  -----------------------------------------------*/


  var progressLine = $('.progress-line');

  if (progressLine.length) {
    progressLine.each(function (index, value) {
      var $this = $(value);
      var options = $this.data('options');
      var bar = new ProgressBar.Line(value, {
        strokeWidth: 1,
        easing: 'easeInOut',
        duration: 3000,
        color: '#333',
        trailColor: '#eee',
        trailWidth: 1,
        svgStyle: {
          width: '100%',
          height: '0.25rem',
          'stroke-linecap': 'round',
          'border-radius': '0.125rem'
        },
        text: {
          style: {
            transform: null
          },
          autoStyleContainer: false
        },
        from: {
          color: '#aaa'
        },
        to: {
          color: '#111'
        },
        step: function step(state, line) {
          line.setText("<span class='value'>" + Math.round(line.value() * 100) + "<b>%</b></span> <span>" + options.text + "</span>");
        }
      });
      var playProgressTriggered = false;

      var progressLineAnimation = function progressLineAnimation() {
        if (!playProgressTriggered) {
          if (utils.isScrolledIntoView(value) || utils.nua.match(/puppeteer/i)) {
            bar.animate(options.progress / 100);
            playProgressTriggered = true;
          }
        }

        return playProgressTriggered;
      };

      progressLineAnimation();
      utils.$window.scroll(function () {
        progressLineAnimation();
      });
    });
  }
});
/*-----------------------------------------------
|   Increment/Decrement Input Fields
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selector = {
    DATA_FIELD: '[data-field]',
    INPUT_GROUP: '.input-group'
  };
  var DATA_KEY = {
    FIELD: 'field',
    TYPE: 'type'
  };
  var Events = {
    CLICK: 'click'
  };
  var Attributes = {
    MIN: 'min'
  };
  utils.$document.on(Events.CLICK, Selector.DATA_FIELD, function (e) {
    var $this = $(e.target);
    var inputField = $this.data(DATA_KEY.FIELD);
    var $inputField = $this.parents(Selector.INPUT_GROUP).children("." + inputField);
    var $btnType = $this.data(DATA_KEY.TYPE);
    var value = parseInt($inputField.val(), 10);
    var min = $inputField.attr(Attributes.MIN);
    if (min) min = parseInt(min, 10);else min = 0;

    if ($btnType === 'plus') {
      value += 1;
    } else if (value > min) value -= 1;

    $inputField.val(value);
  });
});
/*-----------------------------------------------
|   Raty
-----------------------------------------------*/

utils.$document.ready(function () {
  var ratings = $('.raty');

  if (ratings.length) {
    ratings.each(function (index, value) {
      var $this = $(value);
      var options = $.extend({
        starHalf: 'star-half-png text-warning',
        starOff: 'star-off-png text-300',
        starOn: 'star-on-png text-warning',
        starType: 'span',
        size: 30
      }, $this.data('options'));
      $(value).raty(options);
    });
  }
});
/* -------------------------------------------------------------------------- */

/*                             Autocomplete Search                            */

/* -------------------------------------------------------------------------- */

utils.$document.ready(function () {
  var Selectors = {
    DROPDOWN: '.dropdown',
    SEARCH_DISMISS: '[data-dismiss="search"]',
    DROPDOWN_TOGGLE: '[data-toggle="dropdown"]',
    SEARCH_BOX: '.search-box',
    SEARCH_INPUT: '.search-input',
    SEARCH_TOGGLE: '[data-toggle="search"]'
  };
  var Events = {
    CLICK: 'click',
    FOCUS: 'focus',
    SHOW_BS_DROPDOWN: 'show.bs.dropdown'
  };
  var $searchAreas = $(Selectors.SEARCH_BOX);

  var hideSearchSuggestion = function hideSearchSuggestion(searchArea) {
    var el = searchArea.querySelector(Selectors.SEARCH_TOGGLE);
    var dropdown = $(el).dropdown();
    dropdown == null ? void 0 : dropdown.dropdown('hide');
  };

  var hideAllSearchAreas = function hideAllSearchAreas() {
    $searchAreas.each(function (index, value) {
      return hideSearchSuggestion(value);
    });
  };

  $searchAreas.each(function (index, value) {
    var input = value.querySelector(Selectors.SEARCH_INPUT);
    var btnDropdownClose = value.querySelector(Selectors.SEARCH_DISMISS);
    input.addEventListener(Events.FOCUS, function () {
      hideAllSearchAreas();
      var el = value.querySelector(Selectors.SEARCH_TOGGLE);
      var dropdown = $(el).dropdown();
      dropdown.dropdown('show');
    });
    document.addEventListener(Events.CLICK, function (_ref) {
      var target = _ref.target;
      !value.contains(target) && hideSearchSuggestion(value);
    });
    btnDropdownClose && btnDropdownClose.addEventListener(Events.CLICK, function () {
      hideSearchSuggestion(value);
      input.value = '';
    });
  });
  $(Selectors.DROPDOWN).on(Events.SHOW_BS_DROPDOWN, function () {
    return hideAllSearchAreas();
  });
});
/*-----------------------------------------------
|   Select2
-----------------------------------------------*/

utils.$document.ready(function () {
  var select2 = $('.selectpicker');
  select2.length && select2.each(function (index, value) {
    var $this = $(value);
    var options = $.extend({
      theme: 'bootstrap4'
    }, $this.data('options'));
    $this.select2(options);
  });
});
/*
  global Stickyfill
*/

/*-----------------------------------------------
|   Sticky fill
-----------------------------------------------*/

utils.$document.ready(function () {
  Stickyfill.add($('.sticky-top'));
  Stickyfill.add($('.sticky-bottom'));
});
/*-----------------------------------------------
|   Sticky Kit
-----------------------------------------------*/

utils.$document.ready(function () {
  if (window.is.ie()) {
    var stickyKits = $('.sticky-kit');

    if (stickyKits.length) {
      stickyKits.each(function (index, value) {
        var $this = $(value);

        var options = _objectSpread({}, $this.data('options'));

        $this.stick_in_parent(options);
      });
    }
  }
});
/*-----------------------------------------------
|   Tabs
-----------------------------------------------*/

utils.$document.ready(function () {
  var $fancyTabs = $('.fancy-tab');

  if ($fancyTabs.length) {
    var Selector = {
      TAB_BAR: '.nav-bar',
      TAB_BAR_ITEM: '.nav-bar-item',
      TAB_CONTENTS: '.tab-contents'
    };
    var ClassName = {
      ACTIVE: 'active',
      TRANSITION_REVERSE: 'transition-reverse',
      TAB_INDICATOR: 'tab-indicator'
    };
    /*-----------------------------------------------
    |   Function for active tab indicator change
    -----------------------------------------------*/

    var updateIncicator = function updateIncicator($indicator, $tabs, $tabnavCurrentItem) {
      var _$tabnavCurrentItem$p = $tabnavCurrentItem.position(),
          left = _$tabnavCurrentItem$p.left;

      var right = $tabs.children(Selector.TAB_BAR).outerWidth() - (left + $tabnavCurrentItem.outerWidth());
      $indicator.css({
        left: left,
        right: right
      });
    };

    $fancyTabs.each(function (index, value) {
      var $tabs = $(value);
      var $navBar = $tabs.children(Selector.TAB_BAR);
      var $tabnavCurrentItem = $navBar.children(Selector.TAB_BAR_ITEM + "." + ClassName.ACTIVE);
      $navBar.append("\n        <div class=" + ClassName.TAB_INDICATOR + "></div>\n      ");
      var $indicator = $navBar.children("." + ClassName.TAB_INDICATOR);
      var $preIndex = $tabnavCurrentItem.index();
      updateIncicator($indicator, $tabs, $tabnavCurrentItem);
      $navBar.children(Selector.TAB_BAR_ITEM).click(function (e) {
        $tabnavCurrentItem = $(e.currentTarget);
        var $currentIndex = $tabnavCurrentItem.index();
        var $tabContent = $tabs.children(Selector.TAB_CONTENTS).children().eq($currentIndex);
        $tabnavCurrentItem.siblings().removeClass(ClassName.ACTIVE);
        $tabnavCurrentItem.addClass(ClassName.ACTIVE);
        $tabContent.siblings().removeClass(ClassName.ACTIVE);
        $tabContent.addClass(ClassName.ACTIVE);
        /*-----------------------------------------------
        |   Indicator Transition
        -----------------------------------------------*/

        updateIncicator($indicator, $tabs, $tabnavCurrentItem);

        if ($currentIndex - $preIndex <= 0) {
          $indicator.addClass(ClassName.TRANSITION_REVERSE);
        } else {
          $indicator.removeClass(ClassName.TRANSITION_REVERSE);
        }

        $preIndex = $currentIndex;
      });
      utils.$window.on('resize', function () {
        updateIncicator($indicator, $tabs, $tabnavCurrentItem);
      });
    });
  }
  /*-----------------------------------------------
  |   Product Review Tab
  -----------------------------------------------*/


  var $review = $('[data-tab-target]');
  $review.click(function (e) {
    var $this = $(e.currentTarget);
    var $reviewTab = $($this.data('tab-target'));
    $reviewTab.trigger('click');
  });
});
/*-----------------------------------------------
|   TINYMCE
-----------------------------------------------*/

utils.$document.ready(function () {
  var tinymces = $('.tinymce');

  if (tinymces.length) {
    window.tinymce.init({
      selector: '.tinymce',
      // height: '50vh',
      menubar: false,
      skin: utils.settings.tinymce.theme,
      content_style: ".mce-content-body { color: " + utils.grays.black + " }",
      mobile: {
        theme: 'mobile',
        toolbar: ['undo', 'bold']
      },
      statusbar: false,
      plugins: 'link,image,lists,table,media,directionality',
      toolbar: 'styleselect | bold italic link bullist numlist image blockquote table media undo redo ltr rtl',
      directionality: "rtl",
      setup: function(editor) {
        editor.on('init', function(event) {
          $('#' + event.target.id).trigger('tinymce.init');
        });
      }
    });
  }
});
/*-----------------------------------------------
|   Toast [bootstrap 4]
-----------------------------------------------*/

utils.$document.ready(function () {
  return $('.toast').toast();
});
/*-----------------------------------------------
|   Toastr
-----------------------------------------------*/

utils.$document.ready(function () {
  var $notifications = $('[data-notification]');
  $notifications.each(function (index, value) {
    var _window3 = window,
        toastr = _window3.toastr;
    var $this = $(value);
    var config = $this.data('notification');
    var defaultOptions = {
      closeButton: true,
      newestOnTop: false,
      positionClass: 'toast-bottom-right'
    };
    $this.on('click', function () {
      var type = config.type,
          title = config.title,
          message = config.message;
      var mergedOptions = $.extend(defaultOptions, config);
      toastr.options.positionClass !== mergedOptions.positionClass && toastr.remove();
      toastr.options = mergedOptions;

      switch (type) {
        case 'success':
          toastr.success(message, title);
          break;

        case 'warning':
          toastr.warning(message, title);
          break;

        case 'error':
          toastr.error(message, title);
          break;

        default:
          toastr.info(message, title);
          break;
      }
    });
  });
});
/*-----------------------------------------------
|   Tootltip [bootstrap 4]
-----------------------------------------------*/

utils.$document.ready(function () {
  // https://getbootstrap.com/docs/4.0/components/tooltips/#example-enable-tooltips-everywhere
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();
});
/*-----------------------------------------------
|   Typed Text
-----------------------------------------------*/

/*
  global Typed
 */

utils.$document.ready(function () {
  var typedText = $('.typed-text');

  if (typedText.length) {
    typedText.each(function (index, value) {
      return new Typed(value, {
        strings: $(value).data('typed-text'),
        typeSpeed: 100,
        loop: true,
        backDelay: 1500
      });
    });
  }
});
/*-----------------------------------------------
|   YTPlayer
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selector = {
    BG_YOUTUBE: '.bg-youtube',
    BG_HOLDER: '.bg-holder'
  };
  var DATA_KEY = {
    PROPERTY: 'property'
  };
  var $youtubeBackground = $(Selector.BG_YOUTUBE);

  if ($youtubeBackground.length) {
    $youtubeBackground.each(function (index, value) {
      var $this = $(value);
      $this.data(DATA_KEY.PROPERTY, $.extend($this.data(DATA_KEY.PROPERTY), {
        showControls: false,
        loop: true,
        autoPlay: true,
        mute: true,
        containment: $this.parent(Selector.BG_HOLDER)
      }));
      $this.YTPlayer();
    });
  }
});
var _window4 = window,
    dayjs = _window4.dayjs;
var currentDay = dayjs && dayjs().format('DD');
var currentMonth = dayjs && dayjs().format('MM');
var prevMonth = dayjs && dayjs().subtract(1, 'month').format('MM');
var nextMonth = dayjs && dayjs().add(1, 'month').format('MM');
var currentYear = dayjs && dayjs().format('YYYY');
var events = [{
  title: 'Boot Camp',
  start: currentYear + "-" + currentMonth + "-01 10:00:00",
  end: currentYear + "-" + currentMonth + "-03 16:00:00",
  description: "Boston Harbor Now in partnership with the Friends of Christopher Columbus Park, the Wharf District Council and the City of Boston is proud to announce the New Year's Eve Midnight Harbor Fireworks! This beloved nearly 40-year old tradition is made possible by the generous support of local waterfront organizations and businesses and the support of the City of Boston and the Office of Mayor Marty Walsh.",
  className: 'bg-soft-success',
  location: 'Boston Harborwalk, Christopher Columbus Park, </br> Boston, MA 02109, United States',
  organizer: 'Boston Harbor Now'
}, {
  title: 'Crain\'s New York Business ',
  start: currentYear + "-" + currentMonth + "-11",
  description: "Crain's 2020 Hall of Fame. Sponsored Content By Crain's Content Studio. Crain's Content Studio Presents: New Jersey: Perfect for Business. Crain's Business Forum: Letitia James, New York State Attorney General. Crain's NYC Summit: Examining racial disparities during the pandemic",
  className: 'bg-soft-primary'
}, {
  title: 'Conference',
  start: currentYear + "-" + currentMonth + "-" + currentDay,
  description: 'The Milken Institute Global Conference gathered the best minds in the world to tackle some of its most stubborn challenges. It was a unique experience in which individuals with the power to enact change connected with experts who are reinventing health, technology, philanthropy, industry, and media.',
  className: 'bg-soft-success',
  allDay: true,
  schedules: [{
    title: 'Reporting',
    start: currentYear + "-" + currentMonth + "-" + currentDay + " 11:00:00",
    description: 'Time to start the conference and will briefly describe all information about the event.  ',
    className: 'event-bg-soft-success'
  }, {
    title: 'Lunch',
    start: currentYear + "-" + currentMonth + "-" + currentDay + " 14:00:00",
    description: 'Lunch facility for all the attendance in the conference.',
    className: 'event-bg-soft-success'
  }, {
    title: 'Contest',
    start: currentYear + "-" + currentMonth + "-" + currentDay + " 16:00:00",
    description: 'The starting of the programming contest',
    className: 'event-bg-soft-success'
  }, {
    title: 'Dinner',
    start: currentYear + "-" + currentMonth + "-" + currentDay + " 22:00:00",
    description: 'Dinner facility for all the attendance in the conference',
    className: 'event-bg-soft-success'
  }]
}, {
  title: "ICT Expo " + currentYear + " - Product Release",
  start: currentYear + "-" + currentMonth + "-16 10:00:00",
  description: "ICT Expo " + currentYear + " is the largest private-sector exposition aimed at showcasing IT and ITES products and services in Switzerland.",
  end: currentYear + "-" + currentMonth + "-18 16:00:00",
  className: 'bg-soft-warning'
}, {
  title: 'Meeting',
  start: currentYear + "-" + currentMonth + "-07 10:00:00",
  description: 'Discuss about the upcoming projects in current year and assign all tasks to the individuals'
}, {
  title: 'Contest',
  start: currentYear + "-" + currentMonth + "-14 10:00:00",
  description: 'PeaceX is an international peace and amity organisation that aims at casting a pall at the striking issues surmounting the development of peoples and is committed to impacting the lives of young people all over the world.'
}, {
  title: 'Event With Url',
  start: currentYear + "-" + currentMonth + "-23",
  description: 'Sample example of a event with url. Click the event, will redirect to the given link.',
  className: 'bg-soft-success',
  url: 'http://google.com'
}, {
  title: 'Competition',
  start: currentYear + "-" + currentMonth + "-26",
  description: 'The Future of Zambia – Top 30 Under 30 is an annual award, ranking scheme, and recognition platform for young Zambian achievers under the age of 30, who are building brands, creating jobs, changing the game, and transforming the country.',
  className: 'bg-soft-danger'
}, {
  title: 'Birthday Party',
  start: currentYear + "-" + nextMonth + "-05",
  description: 'Will celebrate birthday party with my friends and family',
  className: 'bg-soft-primary'
}, {
  title: 'Click for Google',
  url: 'http://google.com/',
  start: currentYear + "-" + prevMonth + "-10",
  description: 'Applications are open for the New Media Writing Prize 2020. The New Media Writing Prize (NMWP) showcases exciting and inventive stories and poetry that integrate a variety of formats, platforms, and digital media.',
  className: 'bg-soft-primary'
}];
/*-----------------------------------------------
|   Calendar
-----------------------------------------------*/

utils.$document.ready(function () {
  var Selectors = {
    ADD_EVENT_FORM: '#addEventForm',
    ADD_EVENT_MODAL: '#addEvent',
    ACTIVE: '.active',
    CALENDAR: 'appCalendar',
    CALENDAR_TITLE: '.calendar-title',
    NAVBAR_VERTICAL_TOGGLE: '.navbar-vertical-toggle',
    EVENT_DETAILS_MODAL: '#eventDetails',
    EVENT_DETAILS_MODAL_CONTENT: '#eventDetails .modal-content',
    DATA_EVENT: '[data-event]',
    DATA_CALENDAR_VIEW: '[data-fc-view]',
    DATA_VIEW_TITLE: '[data-view-title]',
    INPUT_TITLE: '[name="title"]'
  };
  var Events = {
    CLICK: 'click',
    NAVBAR_VERTICAL_TOGGLE: 'navbar.vertical.toggle',
    SHOWN_BS_MODAL: 'shown.bs.modal',
    SUBMIT: 'submit'
  };
  var DataKeys = {
    EVENT: 'event'
  };
  var ClassNames = {
    ACTIVE: 'active'
  };
  var eventList = events.reduce(function (acc, val) {
    return val.schedules ? acc.concat(val.schedules.concat(val)) : acc.concat(val);
  }, []);

  var updateTitle = function updateTitle(title) {
    return $(Selectors.CALENDAR_TITLE).text(title);
  };

  var calendarEl = document.getElementById(Selectors.CALENDAR);

  if (calendarEl) {
    var calendar = renderCalendar(calendarEl, {
      headerToolbar: false,
      dayMaxEvents: 2,
      height: 800,
      stickyHeaderDates: false,
      views: {
        week: {
          eventLimit: 3
        }
      },
      eventTimeFormat: {
        hour: 'numeric',
        minute: '2-digit',
        omitZeroMinute: true,
        meridiem: true
      },
      events: eventList,
      eventClick: function eventClick(info) {
        if (info.event.url) {
          window.open(info.event.url, '_blank');
          info.jsEvent.preventDefault();
        } else {
          var template = getTemplate(info);
          $(Selectors.EVENT_DETAILS_MODAL_CONTENT).html(template);
          $(Selectors.EVENT_DETAILS_MODAL).modal('show');
        }
      },
      dateClick: function dateClick(info) {
        $(Selectors.ADD_EVENT_MODAL).modal('show');
        /* eslint-disable-next-line */

        var flatpickr = document.querySelector("#addEvent [name='startDate']")._flatpickr;

        flatpickr.setDate([info.dateStr]);
      }
    });
    updateTitle(calendar.currentData.viewTitle);
    $(document).on(Events.CLICK, Selectors.DATA_EVENT, function (_ref2) {
      var currentTarget = _ref2.currentTarget;
      var type = $(currentTarget).data(DataKeys.EVENT);

      switch (type) {
        case 'prev':
          calendar.prev();
          updateTitle(calendar.currentData.viewTitle);
          break;

        case 'next':
          calendar.next();
          updateTitle(calendar.currentData.viewTitle);
          break;

        case 'today':
          calendar.today();
          updateTitle(calendar.currentData.viewTitle);
          break;

        default:
          calendar.today();
          updateTitle(calendar.currentData.viewTitle);
          break;
      }
    });
    $(document).on('click', Selectors.DATA_CALENDAR_VIEW, function (e) {
      e.preventDefault();
      var el = $(e.currentTarget);
      var text = el.text();
      el.parent().find('.active').removeClass(ClassNames.ACTIVE);
      el.addClass('active');
      $(Selectors.DATA_VIEW_TITLE).text(text);
      calendar.changeView(el.data('fc-view'));
      updateTitle(calendar.currentData.viewTitle);
    });
    document.querySelector(Selectors.ADD_EVENT_FORM).addEventListener(Events.SUBMIT, function (e) {
      e.preventDefault();
      var _e$target = e.target,
          title = _e$target.title,
          startDate = _e$target.startDate,
          endDate = _e$target.endDate,
          label = _e$target.label,
          description = _e$target.description,
          allDay = _e$target.allDay;
      calendar.addEvent({
        title: title.value,
        start: startDate.value,
        end: endDate.value ? endDate.value : null,
        allDay: allDay.checked,
        className: allDay.checked && label.value ? "bg-soft-" + label.value : '',
        description: description.value
      });
      e.target.reset();
      $(Selectors.ADD_EVENT_MODAL).modal('hide');
    });
  }

  $(Selectors.ADD_EVENT_MODAL).on(Events.SHOWN_BS_MODAL, function (_ref3) {
    var currentTarget = _ref3.currentTarget;
    currentTarget.querySelector(Selectors.INPUT_TITLE).focus();
  });
});

var getStackIcon = function getStackIcon(icon, transform) {
  return "\n      <span class=\"fa-stack ml-n1 mr-3\">\n        <i class=\"fas fa-circle fa-stack-2x text-200\"></i>\n        <i class=\"" + icon + " fa-stack-1x text-primary\" data-fa-transform=" + transform + "></i>\n      </span>\n    ";
};

var getTemplate = function getTemplate(info) {
  return "\n  <div class=\"modal-header px-card bg-light border-0 flex-between-center\">\n    <div>\n      <h5 class=\"mb-0\">" + info.event.title + "</h5>\n      " + (info.event.extendedProps.organizer ? "<p class=\"mb-0 fs--1 mt-1\">\n          by <a href=\"#!\">" + info.event.extendedProps.organizer + "</a>\n        </p>" : '') + "\n    </div>\n    \n    <button class=\"close fs-0 px-card\" data-dismiss=\"modal\" aria-label=\"Close\">\n      <span class=\"fas fa-times\"></span>\n    </button>\n  </div>\n  <div class=\"modal-body px-card pb-card pt-1 fs--1\">\n    " + (info.event.extendedProps.description ? "\n        <div class=\"media mt-3\">\n          " + getStackIcon('fas fa-align-left') + "\n          <div class=\"media-body\">\n            <h6>Description</h6>\n            <p class=\"mb-0\">\n              \n            " + info.event.extendedProps.description.split(' ').slice(0, 30).join(' ') + "\n            </p>\n          </div>\n        </div>\n      " : '') + " \n    <div class=\"media mt-3\">\n      " + getStackIcon('fas fa-calendar-check') + "\n      <div class=\"media-body\">\n          <h6>Date and Time</h6>\n          <p class=\"mb-1\">\n            " + (window.dayjs && window.dayjs(info.event.start).format('dddd, MMMM D, YYYY, h:mm A')) + " \n            " + (info.event.end ? "\u2013 <br/>" + (window.dayjs && window.dayjs(info.event.end).subtract(1, 'day').format('dddd, MMMM D, YYYY, h:mm A')) : '') + "\n          </p>\n      </div>\n    </div>\n    " + (info.event.extendedProps.location ? "\n          <div class=\"media mt-3\">\n            " + getStackIcon('fas fa-map-marker-alt') + "\n            <div class=\"media-body\">\n                <h6>Location</h6>\n                <div class=\"mb-1\">" + info.event.extendedProps.location + "</div>\n            </div>\n          </div>\n        " : '') + "\n    " + (info.event.extendedProps.schedules ? "\n          <div class=\"media mt-3\">\n          " + getStackIcon('fas fa-clock') + "\n          <div class=\"media-body\">\n              <h6>Schedule</h6>\n              \n              <ul class=\"list-unstyled timeline mb-0\">\n                " + info.event.extendedProps.schedules.map(function (schedule) {
    return "<li>" + schedule.title + "</li>";
  }).join('') + "\n              </ul>\n          </div>\n        " : '') + "\n    </div>\n  </div>\n  <div class=\"modal-footer d-flex justify-content-end bg-light px-card border-top-0\">\n    <a href=\"pages/event-create.html\" class=\"btn btn-falcon-default btn-sm\">\n      <span class=\"fas fa-pencil-alt fs--2 mr-2\"></span> Edit\n    </a>\n    <a href='pages/event-detail.html' class=\"btn btn-falcon-primary btn-sm\">\n      See more details\n      <span class=\"fas fa-angle-right fs--2 ml-1\"></span>\n    </a>\n  </div\n  ";
};