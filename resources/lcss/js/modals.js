/*
 * LCSS - ligthing fast UI-design framework.
 * Copyright 2018 Eugene Dementyev <devg@ya.ru>
 * Licensed under MIT (https://opensource.org/licenses/MIT)
 */

;(function(root, factory) {

  if (typeof define === 'function' && define.amd) {
    define(factory);
  } else if (typeof exports === 'object') {
    module.exports = factory();
  } else {
    root.Modals = factory();
  }

})(this, function() {
  
  var Modals = {};

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
  
  
  Modals.open = function(message) {
    if(!current)
      return new Modal(message).open();
    else
      current.content.html(message);
  };

  // show ajax-preloader icon + timeout
  Modals.wait = function(interval) {
    interval = interval || 300;
    if(!Modals.microbar) Modals.microbar = new MProgress();
    Modals.microbar.interval(interval).show();
  };

  Modals.waitClose = function() {
    Modals.microbar.hide();
  };

  // alert message
  Modals.alert = function(message, fn) {
    var modal =  new Modal('', fn, fn); // same callback
    modal.content.append('<div style="margin-bottom:1rem">'+message+'</div>');
    modal.content.append('<div class="text-center"><button type="button" class="btn primary mw__confirm">ОК</button></div>');modal.content.find(".mw__confirm").focus();
    modal.open();
  };
  // confirm message
  Modals.confirm = function(message, fnConfirm, fnCancel) {
    var modal =  new Modal('', fnConfirm, fnCancel);
    modal.content.append('<div style="margin-bottom:1rem">'+message+'</div>');
    modal.content.append('<div class="text-center"><button type="button" class="btn primary mw__confirm">ОК</button> <button type="button" class="btn mw__cancel">Отмена</button></div>');
    modal.content.find(".mw__confirm").focus();
    modal.open();
  };

  // open dialog
  Modals.openAjax = function(url) { //todo param showWait
    Modals.wait();

    $.get(url).done(function(data) {
      Modals.waitClose(); // скрытие mprogress
      Modals.open(data);
    })
    .fail(function() {
      Modals.alert('Ошибка связи. Проверьте интернет<br> и попробуйте еще раз', Modals.closeAll);
    });
  };

  // close current dialog
  Modals.close = function() {
    if(current) current.close();
  };
  // close all dialogs
  Modals.closeAll = function() {
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
  Modals.ajaction = function (obj, confirmMessage) {
    if(!confirmMessage) {
      confirmMessage = 'Are you sure?';
    }
    if($(obj).hasClass('-confirmable')) {
      Modals.confirm(confirmMessage,
      function(){
        var url = $(obj).attr('href');
        Modals.openAjax(url);
        return false;
      },
      function(){
        return false;
      });
    }
    else {
      var url = $(obj).attr('href');
      Modals.openAjax(url);
      return false;
    }
  };

  /**
   * Send form via ajax
   */
  Modals.ajaxSubmit =  function (form, opts) {
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
  Modals.ajaxSubmitModal =  function (form) {
    Modals.ajaxSubmit(form, {
      before:  function () {
        Modals.wait(0);
      },
      success: function (data) {
        Modals.waitClose(); // скрытие mprogress
        Modals.open(data);
      },
      error:   function (data) {
        Modals.waitClose(); // скрытие mprogress
        Modals.alert('Ошибка связи. Проверьте интернет<br> и попробуйте еще раз', Modals.closeAll);
      }
    });
    return false;
  };

  /* Modals.configure = function(options) {
    var key, value;
    for (key in options) {
      value = options[key];
      if (value !== undefined && options.hasOwnProperty(key)) Settings[key] = value;
    }

    return this;
  }; */

  

  /**
   * (Internal) Determines if an element or space separated list of class names contains a class name.
   */

  function hasClass(element, name) {
    var list = typeof element == 'string' ? element : classList(element);
    return list.indexOf(' ' + name + ' ') >= 0;
  }

  /**
   * (Internal) Adds a class to an element.
   */

  function addClass(element, name) {
    var oldList = classList(element),
        newList = oldList + name;

    if (hasClass(oldList, name)) return;

    // Trim the opening space.
    element.className = newList.substring(1);
  }

  /**
   * (Internal) Removes a class from an element.
   */

  function removeClass(element, name) {
    var oldList = classList(element),
        newList;

    if (!hasClass(element, name)) return;

    // Replace the class name.
    newList = oldList.replace(' ' + name + ' ', ' ');

    // Trim the opening and closing spaces.
    element.className = newList.substring(1, newList.length - 1);
  }

  /**
   * (Internal) Gets a space separated list of the class names on the element.
   * The list is wrapped with a single space on each end to facilitate finding
   * matches within the list.
   */

  function classList(element) {
    return (' ' + (element && element.className || '') + ' ').replace(/\s+/gi, ' ');
  }

  /**
   * (Internal) Removes an element from the DOM.
   */

  function removeElement(element) {
    element && element.parentNode && element.parentNode.removeChild(element);
  }

  return Modals;
});
