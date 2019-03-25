/**
 * Frontend components. This file is part of LCSS framework.
 * @author Eugene Dementyev
 * @license MIT
 */
// инициализация при загрузке документа
$(document).ready(function(){
  // Автом. привязка cms.ajaction()
  $('.ajaction').click(function(e) {
    e.preventDefault();
    cms.ajaction(this);
  });

  // Комбинации клавиш
  $(document).keydown(function(e){
    // закрытие текущего модального окна по Escape
    if(e.keyCode==27){
      cms.modal.close();
      //todo прерывание ajax-запросов
    }
    // отправка основной формы по Ctrl+Enter
    else if((e.ctrlKey) && ((e.keyCode == 13)||(e.keyCode == 10))) {
      $('.form-default').submit();
    }
  });

  // Scroll to top button
  $(window).scroll(function(){
    if ($(this).scrollTop() > 600) {
      $('#toTop').fadeIn();
    } else {
      $('#toTop').fadeOut();
    }
  });
  $('#toTop').click(function(){
    $('html, body').animate({scrollTop : 0},200);
    return false;
  });
});


// btn-upload
$(function() {
  var fileInput = document.getElementById('file');
  defaultLabel = $(fileInput).parent('.btn-upload').find('span').text();

  fileInput.addEventListener('change', function(e) {

    if (fileInput.value.substring(fileInput.value.lastIndexOf('.') + 1, fileInput.value.length).toLowerCase() != 'png') {
      alert('Only png files are accepted!');
      fileInput.value = null;
      $(fileInput).parent('.btn-upload').find('span').html(defaultLabel);
    }

    fileNames = [];

    if (e.target.files.length) {
      for (i=0; i < e.target.files.length; i++) {
        fileNames.push(e.target.files[i].name);
      };
    }

    if (fileInput.files.length) {
      $(fileInput).parent('.btn-upload').addClass('selected');
      $(fileInput).parent('.btn-upload').find('span').html('(' + fileNames.length + ' шт.) ' + fileNames.join("; ").trim());
    } else {
      $(fileInput).parent('.btn-upload').removeClass('selected');
      $(fileInput).parent('.btn-upload').find('span').html(defaultLabel);
    }
  }, false);

});

// navs & tabs
$(function() {
  // $('.tabs__link').click(function(e) {
    // e.preventDefault();
    // $(this).parent().parent().find('.tabs__link').removeClass('active');
    // $(this).addClass('active');
  // });

  $('.nav-trigger').click(function(e) {
    $('#responsive-nav').toggleClass('show-md');
    if (!$('#responsive-nav').hasClass('show-md')) {
      $('.nav-trigger').addClass('nav-is-visible');
    } else {
      $('.nav-trigger').removeClass('nav-is-visible');
    }
  });

  $('[data-toggle="tooltip"]').tooltip({
    delay: { "show": 0, "hide": 200 }
  });
});


// Main object
var cms = {};

/**
 * Диалоговые/модальные окна
 */
(function(cms, $) {
  "use strict";

  var current, instances = [];
  var isScreenLocked = false;
  //var waitTimer; // old задержка перед показом диалога ожидания

  /**
   * Get a scrollbar width
   * @return {Number}
   * //todo replace by https://www.npmjs.com/package/scrollbar-width
   */
  var getScrollbarWidth = function () {
      /* if ($(document.body).height() <= $(window).height()) {
          return 0;
      } */
      var outer = document.createElement("div");
      outer.style.visibility = "hidden";
      outer.style.width = "100px";
      document.body.appendChild(outer);
      var widthNoScroll = outer.offsetWidth;
      outer.style.overflow = "scroll";
      var inner = document.createElement("div");
      inner.style.width = "100%";
      outer.appendChild(inner);
      var widthWithScroll = inner.offsetWidth;
      outer.parentNode.removeChild(outer);
      return widthNoScroll - widthWithScroll;
  };

  /**
   * Lock screen
   */
  var lockScreen = function () {
    if(!$("html").hasClass("mw-locked")) {
      $(document.body).css("padding-right", "+=" + getScrollbarWidth());
      $("html").addClass("mw-locked");
      isScreenLocked = true;
    }
  };

  /**
   * Unlock screen
   */
  var unlockScreen = function () {
      $(document.body).css("padding-right", "-=" + getScrollbarWidth());
      $("html").removeClass("mw-locked");
      isScreenLocked = false;
  };

  /**
   * Modal constructor
   */
  function Modal(message, fnConfirm, fnCancel) {
      this.buildDOM();
      this.content.html(message);
      this.addEventListeners(fnConfirm, fnCancel);
  }

  /**
   * Build required DOM
   */
  Modal.prototype.buildDOM = function () {
      this.body = $(document.body);
      if (!isScreenLocked) {
        this.overlay = $("<div>").addClass("mw__overlay");
      } else {
        this.overlay = $("<div>").addClass("mw__overlay -transparent");
      }
      this.content = $('<div class="mw__content"></div>');
      this.overlay.append(this.content);
      this.body.append(this.overlay);
  };

  /**
   * Add event listeners to the current modal window
   */
  Modal.prototype.addEventListeners = function (fnConfirm, fnCancel) {
      var self = this;

      $(this.content).on("click", ".mw__confirm", function (e) {
          e.preventDefault();
          if(typeof fnConfirm === 'function') fnConfirm();
          self.close();
      });
      $(this.content).on("click", ".mw__cancel", function (e) {
          e.preventDefault();
          if(typeof fnCancel === 'function') fnCancel();
          self.close();
      });

      this.overlay.on("click", function (e) {
          var $target = $(e.target);
          if (!$target.hasClass("mw__overlay")) {
              return;
          }
          // click on overlay is cancel
          if(typeof fnCancel === 'function') fnCancel();
          self.close();
      });
  };

  /**
   * Open modal window
   */
  Modal.prototype.open = function () {
      current = this;
      instances.push(current);
      //console.log(instances);
      this.overlay.show(0);
      lockScreen();
      this.content.addClass("-opened");
      this.content.find(".mw__confirm").focus();
      return this;
  };

  /**
   * Close modal window
   */
  Modal.prototype.close = function () {
      var self = this;
      instances.pop();
      current = instances[instances.length-1];

      /*
      // fast hide variant
      self.overlay.hide();
      self.overlay.remove();
      if(instances.length==0) unlockScreen(); */

      setTimeout(function(){
        self.overlay.hide();
        self.overlay.remove();
        if(instances.length==0) unlockScreen();
        //console.log(instances);
      }, 80);
      this.content.removeClass("-opened");
      //console.log(instances);
  };

  cms.modal = {};

  // open dialog
  cms.modal.open = function(message) {
    if(!current)
      return new Modal(message).open();
    else
      current.content.html(message);
  };

  // show ajax-preloader icon + timeout
  cms.modal.wait = function(interval) {
    interval = interval || 300;
    if(!cms.microbar) cms.microbar = new MProgress();
    cms.microbar.interval(interval).show();
  };

  cms.modal.waitClose = function() {
    cms.microbar.hide();
  };

  // alert message
  cms.modal.alert = function(message, fn) {
    var modal =  new Modal('', fn, fn); // same callback
    modal.content.append('<div style="margin-bottom:1rem">'+message+'</div>');
    modal.content.append('<div class="text-center"><button type="button" class="btn primary mw__confirm">ОК</button></div>');modal.content.find(".mw__confirm").focus();
    modal.open();
  };
  // confirm message
  cms.modal.confirm = function(message, fnConfirm, fnCancel) {
    var modal =  new Modal('', fnConfirm, fnCancel);
    modal.content.append('<div style="margin-bottom:1rem">'+message+'</div>');
    modal.content.append('<div class="text-center"><button type="button" class="btn primary mw__confirm">ОК</button> <button type="button" class="btn mw__cancel">Отмена</button></div>');
    modal.content.find(".mw__confirm").focus();
    modal.open();
  };

  // open dialog
  cms.modal.openAjax = function(url) { //todo param showWait
    cms.modal.wait();

    $.get(url).done(function(data) {
      cms.modal.waitClose(); // скрытие mprogress
      cms.modal.open(data);
    })
    .fail(function() {
      cms.modal.waitClose(); // скрытие mprogress
      cms.modal.alert('Ошибка связи. Проверьте интернет<br> и попробуйте еще раз', cms.modal.closeAll);
    });
  };

  // close current dialog
  cms.modal.close = function() {
    if(current) current.close();
    return false; /// new
  };
  // close all dialogs
  cms.modal.closeAll = function() {
    if(instances.length>0){
      var elem;
      while (elem = instances[instances.length-1]) {
        console.log(elem);
        if(elem) elem.close();
      }
    }
  };

  /**
   * Open in modal via ajax
   */
  cms.ajaction = function (obj, confirmMessage) {
    if(!confirmMessage) {
      confirmMessage = 'Are you sure?';
    }
    if($(obj).hasClass('-confirmable')) {
      cms.modal.confirm(confirmMessage,
      function(){
        var url = $(obj).attr('href');
        cms.modal.openAjax(url);
        return false;
      },
      function(){
        return false;
      });
    }
    else {
      var url = $(obj).attr('href');
      cms.modal.openAjax(url);
      return false;
    }
  };

  /**
   * Send form via ajax
   */
  cms.ajaxSubmit =  function (form, opts) {
    if(typeof opts.before === 'function') opts.before();

    var url = opts.url || form.action;

    var formData = new FormData(form);
    var xhr = new XMLHttpRequest();

    xhr.open('POST', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(formData);

    xhr.onload = function(e) {
      if (xhr.readyState != 4) return;
      if (this.status == 200) {
        if(typeof opts.success === 'function') opts.success(xhr.responseText);
      } else {
        if(typeof opts.error === 'function') opts.error(this.status);
      }
    };
    xhr.onerror = function(e) {
      if(typeof opts.error === 'function') opts.error();
    };
    return false;
  };

  // упрощенный вызов отправки с результатом в диалоге
  cms.ajaxSubmitModal =  function (form) {
    cms.ajaxSubmit(form, {
      before:  function () {
        cms.modal.wait(0);
      },
      success: function (data) {
        cms.modal.waitClose(); // скрытие mprogress
        cms.modal.open(data);
      },
      error:   function (data) {
        cms.modal.waitClose(); // скрытие mprogress
        cms.modal.alert('Ошибка связи. Проверьте интернет<br> и попробуйте еще раз', cms.modal.closeAll);
      }
    });
    return false;
  };

})(cms, window.jQuery || window.Zepto);

/**
 * Notifications
 */
cms.notice = {};
cms.notice.init = function() {
  if (document.querySelector(".nw") == null) {
    $(document.body).append('<div class="nw nw-hidden"></div>');
  }
};
cms.notice.show = function (message, type, wait) {
  cms.notice.init();
  var msg = document.createElement("div");
  msg.className = "nw__message" + ((typeof type === "string" && type !== "") ? " "+type : "");
  msg.innerHTML = message;
  // append child
  $('.nw').prepend(msg);
  //$(msg).addClass('-show');
  if($('.nw').hasClass('nw-hidden')) $('.nw').removeClass('nw-hidden');
  // triggers the CSS animation
  setTimeout(function() { msg.className = msg.className + " -show"; }, 10);
  cms.notice.close(msg, wait);
  return msg;
};
//todo hover stop timer
cms.notice.close = function (elem, wait) {
  var time = (!isNaN(wait)) ? wait : 4000;
  // set click event on message
  $(elem).on("click", function () {
    cms.notice.hide(elem);
  });
  // never close (until click) if wait is set to 0
  if (time === 0) return;
  // set timeout to auto close the message
  setTimeout(function () { cms.notice.hide(elem); }, time);
};

cms.notice.hide = function (el) {
  var nw = document.querySelector(".nw");
  // ensure element exists
  if (typeof el !== "undefined" && el.parentNode === nw) {
    // whether CSS transition exists
    el.className += " nw-hidden";
    setTimeout(function () {
      nw.removeChild(el);
    }, 150);
    if (!nw.hasChildNodes()) nw.className += " nw-hidden";
  }
};
cms.notice.hideAll = function () {
  var nw = document.querySelector(".nw");
  if(nw) {
    nw.innerHTML="";
    if (!nw.hasChildNodes()) nw.className += " nw-hidden";
  }
};

/*
* A slim progress bar with no dependencies
* https://github.com/jgillich/mprogress
*/
(function (root) {
    function MProgress(styles) {
        this.el = document.createElement('div');
        extend(this.el.style, {
            position: 'fixed',
            width: '0%',
            height: '4px',
            top: '0',
            left: '0',
            right: '0',
            zIndex: 10000,
            background: '#FFDD57',
            transition: 'width 0.3s linear'
        }, styles);
    }
    function extend(destination) {
        var objects = Array.prototype.slice.call(arguments, 1);
        for(var i = 0; i < objects.length; i++) {
            for(var key in objects[i]) {
                destination[key] = objects[i][key];
            }
        }
        return destination;
    }
    MProgress.prototype.interval = function (interval) {
        interval = interval || 500;
        this.intervalID = setInterval(this.increase.bind(this), interval);
        this.el.style.transition = 'width ' + interval / 1000 + 's linear';
        return this;
    };
    MProgress.prototype.show = function () {
        this.set(0); // devg init 0 value
        document.getElementsByTagName('body')[0].appendChild(this.el);
        return this;
    };
    MProgress.prototype.hide = function () {
        this.set(100);
        setTimeout(this.remove.bind(this), 500);
        return this;
    };
    MProgress.prototype.remove = function () {
        if(this.intervalID) {
            clearInterval(this.intervalID);
        }
        this.el.parentNode.removeChild(this.el);
        return this;
    };
    MProgress.prototype.set = function (val) {
        this.el.style.width = val + '%';
        return this;
    };
    MProgress.prototype.get = function () {
        return parseFloat(this.el.style.width.split('%')[0]);
    };
    MProgress.prototype.increase = function (val) {
        if(!val) {
            val = 3;
        }
        this.set(this.get() + val);
        return this;
    };
    if (typeof define === 'function' && define.amd) {
        define(function () { return MProgress; });
    } else if(typeof module !== 'undefined' && module.exports) {
        module.exports = MProgress;
    } else {
        root.MProgress = MProgress;
    }
}(this));