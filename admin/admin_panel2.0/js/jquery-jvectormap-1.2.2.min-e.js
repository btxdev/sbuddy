'use strict';
(function($) {
  var apiParams = {
    set : {
      colors : 1,
      values : 1,
      backgroundColor : 1,
      scaleColors : 1,
      normalizeFunction : 1,
      focus : 1
    },
    get : {
      selectedRegions : 1,
      selectedMarkers : 1,
      mapObject : 1,
      regionName : 1
    }
  };
  /**
   * @param {string} options
   * @return {?}
   */
  $.fn.vectorMap = function(options) {
    var map;
    var methodName;
    var i;
    map = this.children(".jvectormap-container").data("mapObject");
    if (options === "addMap") {
      jvm.WorldMap.maps[arguments[1]] = arguments[2];
    } else {
      if (!(options !== "set" && options !== "get" || !apiParams[options][arguments[1]])) {
        return methodName = arguments[1].charAt(0).toUpperCase() + arguments[1].substr(1), map[options + methodName].apply(map, Array.prototype.slice.call(arguments, 2));
      }
      options = options || {};
      options.container = this;
      map = new jvm.WorldMap(options);
    }
    return this;
  };
})(jQuery), function($) {
  /**
   * @param {!Object} event
   * @return {?}
   */
  function handler(event) {
    var orgEvent = event || window.event;
    /** @type {!Array<?>} */
    var args = [].slice.call(arguments, 1);
    /** @type {number} */
    var delta = 0;
    /** @type {boolean} */
    var s = true;
    /** @type {number} */
    var deltaX = 0;
    /** @type {number} */
    var deltaY = 0;
    return event = $.event.fix(orgEvent), event.type = "mousewheel", orgEvent.wheelDelta && (delta = orgEvent.wheelDelta / 120), orgEvent.detail && (delta = -orgEvent.detail / 3), deltaY = delta, orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS && (deltaY = 0, deltaX = -1 * delta), orgEvent.wheelDeltaY !== undefined && (deltaY = orgEvent.wheelDeltaY / 120), orgEvent.wheelDeltaX !== undefined && (deltaX = -1 * orgEvent.wheelDeltaX / 120), args.unshift(event, delta, deltaX,
    deltaY), ($.event.dispatch || $.event.handle).apply(this, args);
  }
  /** @type {!Array} */
  var types = ["DOMMouseScroll", "mousewheel"];
  if ($.event.fixHooks) {
    /** @type {number} */
    var i = types.length;
    for (; i;) {
      $.event.fixHooks[types[--i]] = $.event.mouseHooks;
    }
  }
  $.event.special.mousewheel = {
    setup : function() {
      if (this.addEventListener) {
        /** @type {number} */
        var i = types.length;
        for (; i;) {
          this.addEventListener(types[--i], handler, false);
        }
      } else {
        /** @type {function(!Object): ?} */
        this.onmousewheel = handler;
      }
    },
    teardown : function() {
      if (this.removeEventListener) {
        /** @type {number} */
        var i = types.length;
        for (; i;) {
          this.removeEventListener(types[--i], handler, false);
        }
      } else {
        /** @type {null} */
        this.onmousewheel = null;
      }
    }
  };
  $.fn.extend({
    mousewheel : function(fn) {
      return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
    },
    unmousewheel : function(fn) {
      return this.unbind("mousewheel", fn);
    }
  });
}(jQuery);
var jvm = {
  inherits : function(child, parent) {
    /**
     * @return {undefined}
     */
    function ctor() {
    }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    /** @type {!Function} */
    child.prototype.constructor = child;
    /** @type {!Function} */
    child.parentClass = parent;
  },
  mixin : function(t, opt) {
    var p;
    for (p in opt.prototype) {
      if (opt.prototype.hasOwnProperty(p)) {
        t.prototype[p] = opt.prototype[p];
      }
    }
  },
  min : function(val) {
    /** @type {number} */
    var min = Number.MAX_VALUE;
    var i;
    if (val instanceof Array) {
      /** @type {number} */
      i = 0;
      for (; i < val.length; i++) {
        if (val[i] < min) {
          min = val[i];
        }
      }
    } else {
      for (i in val) {
        if (val[i] < min) {
          min = val[i];
        }
      }
    }
    return min;
  },
  max : function(values) {
    /** @type {number} */
    var max = Number.MIN_VALUE;
    var i;
    if (values instanceof Array) {
      /** @type {number} */
      i = 0;
      for (; i < values.length; i++) {
        if (values[i] > max) {
          max = values[i];
        }
      }
    } else {
      for (i in values) {
        if (values[i] > max) {
          max = values[i];
        }
      }
    }
    return max;
  },
  keys : function(keys) {
    /** @type {!Array} */
    var r = [];
    var n;
    for (n in keys) {
      r.push(n);
    }
    return r;
  },
  values : function(map) {
    /** @type {!Array} */
    var all = [];
    var id;
    var _i;
    /** @type {number} */
    _i = 0;
    for (; _i < arguments.length; _i++) {
      map = arguments[_i];
      for (id in map) {
        all.push(map[id]);
      }
    }
    return all;
  }
};
jvm.$ = jQuery, jvm.AbstractElement = function(name, config) {
  this.node = this.createElement(name);
  /** @type {string} */
  this.name = name;
  this.properties = {};
  if (config) {
    this.set(config);
  }
}, jvm.AbstractElement.prototype.set = function(property, value) {
  var key;
  if (typeof property == "object") {
    for (key in property) {
      this.properties[key] = property[key];
      this.applyAttr(key, property[key]);
    }
  } else {
    /** @type {string} */
    this.properties[property] = value;
    this.applyAttr(property, value);
  }
}, jvm.AbstractElement.prototype.get = function(key) {
  return this.properties[key];
}, jvm.AbstractElement.prototype.applyAttr = function(name, value) {
  try {
    this.node.setAttribute(name, value);
  }
  catch(errorexception) {
    console.log('name: ' + name);
  }

}, jvm.AbstractElement.prototype.remove = function() {
  jvm.$(this.node).remove();
}, jvm.AbstractCanvasElement = function(container, width, height) {
  /** @type {!HTMLElement} */
  this.container = container;
  this.setSize(width, height);
  this.rootElement = new jvm[this.classPrefix + "GroupElement"];
  this.node.appendChild(this.rootElement.node);
  this.container.appendChild(this.node);
}, jvm.AbstractCanvasElement.prototype.add = function(element, group) {
  group = group || this.rootElement;
  group.add(element);
  element.canvas = this;
}, jvm.AbstractCanvasElement.prototype.addPath = function(config, style, group) {
  var item = new jvm[this.classPrefix + "PathElement"](config, style);
  return this.add(item, group), item;
}, jvm.AbstractCanvasElement.prototype.addCircle = function(config, style, value) {
  var item = new jvm[this.classPrefix + "CircleElement"](config, style);
  return this.add(item, value), item;
}, jvm.AbstractCanvasElement.prototype.addGroup = function(state) {
  var a = new jvm[this.classPrefix + "GroupElement"];
  return state ? state.node.appendChild(a.node) : this.node.appendChild(a.node), a.canvas = this, a;
}, jvm.AbstractShapeElement = function(config, name, style) {
  this.style = style || {};
  this.style.current = {};
  /** @type {boolean} */
  this.isHovered = false;
  /** @type {boolean} */
  this.isSelected = false;
  this.updateStyle();
}, jvm.AbstractShapeElement.prototype.setHovered = function(isHovered) {
  if (this.isHovered !== isHovered) {
    /** @type {boolean} */
    this.isHovered = isHovered;
    this.updateStyle();
  }
}, jvm.AbstractShapeElement.prototype.setSelected = function(isSelected) {
  if (this.isSelected !== isSelected) {
    /** @type {boolean} */
    this.isSelected = isSelected;
    this.updateStyle();
    jvm.$(this.node).trigger("selected", [isSelected]);
  }
}, jvm.AbstractShapeElement.prototype.setStyle = function(t, c) {
  var e = {};
  if (typeof t == "object") {
    e = t;
  } else {
    e[t] = c;
  }
  jvm.$.extend(this.style.current, e);
  this.updateStyle();
}, jvm.AbstractShapeElement.prototype.updateStyle = function() {
  var attrs = {};
  jvm.AbstractShapeElement.mergeStyles(attrs, this.style.initial);
  jvm.AbstractShapeElement.mergeStyles(attrs, this.style.current);
  if (this.isHovered) {
    jvm.AbstractShapeElement.mergeStyles(attrs, this.style.hover);
  }
  if (this.isSelected) {
    jvm.AbstractShapeElement.mergeStyles(attrs, this.style.selected);
    if (this.isHovered) {
      jvm.AbstractShapeElement.mergeStyles(attrs, this.style.selectedHover);
    }
  }
  this.set(attrs);
}, jvm.AbstractShapeElement.mergeStyles = function(key, obj) {
  var k;
  obj = obj || {};
  for (k in obj) {
    if (obj[k] === null) {
      delete key[k];
    } else {
      key[k] = obj[k];
    }
  }
}, jvm.SVGElement = function(name, config) {
  jvm.SVGElement.parentClass.apply(this, arguments);
}, jvm.inherits(jvm.SVGElement, jvm.AbstractElement), jvm.SVGElement.svgns = "http://www.w3.org/2000/svg", jvm.SVGElement.prototype.createElement = function(name) {
  return document.createElementNS(jvm.SVGElement.svgns, name);
}, jvm.SVGElement.prototype.addClass = function(className) {
  this.node.setAttribute("class", className);
}, jvm.SVGElement.prototype.getElementCtr = function(ctr) {
  return jvm["SVG" + ctr];
}, jvm.SVGElement.prototype.getBBox = function() {
  return this.node.getBBox();
}, jvm.SVGGroupElement = function() {
  jvm.SVGGroupElement.parentClass.call(this, "g");
}, jvm.inherits(jvm.SVGGroupElement, jvm.SVGElement), jvm.SVGGroupElement.prototype.add = function(element) {
  this.node.appendChild(element.node);
}, jvm.SVGCanvasElement = function(height, container, width) {
  /** @type {string} */
  this.classPrefix = "SVG";
  jvm.SVGCanvasElement.parentClass.call(this, "svg");
  jvm.AbstractCanvasElement.apply(this, arguments);
}, jvm.inherits(jvm.SVGCanvasElement, jvm.SVGElement), jvm.mixin(jvm.SVGCanvasElement, jvm.AbstractCanvasElement), jvm.SVGCanvasElement.prototype.setSize = function(width, height) {
  /** @type {number} */
  this.width = width;
  /** @type {number} */
  this.height = height;
  this.node.setAttribute("width", width);
  this.node.setAttribute("height", height);
}, jvm.SVGCanvasElement.prototype.applyTransformParams = function(scale, transX, transY) {
  /** @type {boolean} */
  this.scale = scale;
  /** @type {number} */
  this.transX = transX;
  /** @type {number} */
  this.transY = transY;
  try {
    if(!isNaN(scale) && !isNaN(transX) && !isNaN(transY)) {
      this.rootElement.node.setAttribute("transform", "scale(" + scale + ") translate(" + transX + ", " + transY + ")");
    }
  }
  catch(errorexception) {}
}, jvm.SVGShapeElement = function(name, config, style) {
  jvm.SVGShapeElement.parentClass.call(this, name, config);
  jvm.AbstractShapeElement.apply(this, arguments);
}, jvm.inherits(jvm.SVGShapeElement, jvm.SVGElement), jvm.mixin(jvm.SVGShapeElement, jvm.AbstractShapeElement), jvm.SVGPathElement = function(config, style) {
  jvm.SVGPathElement.parentClass.call(this, "path", config, style);
  this.node.setAttribute("fill-rule", "evenodd");
}, jvm.inherits(jvm.SVGPathElement, jvm.SVGShapeElement), jvm.SVGCircleElement = function(config, style) {
  jvm.SVGCircleElement.parentClass.call(this, "circle", config, style);
}, jvm.inherits(jvm.SVGCircleElement, jvm.SVGShapeElement), jvm.VMLElement = function(name, config) {
  if (!jvm.VMLElement.VMLInitialized) {
    jvm.VMLElement.initializeVML();
  }
  jvm.VMLElement.parentClass.apply(this, arguments);
}, jvm.inherits(jvm.VMLElement, jvm.AbstractElement), jvm.VMLElement.VMLInitialized = false, jvm.VMLElement.initializeVML = function() {
  try {
    if (!document.namespaces.rvml) {
      document.namespaces.add("rvml", "urn:schemas-microsoft-com:vml");
    }
    /**
     * @param {string} vnode
     * @return {?}
     */
    jvm.VMLElement.prototype.createElement = function(vnode) {
      return document.createElement("<rvml:" + vnode + ' class="rvml">');
    };
  } catch (e) {
    /**
     * @param {string} vnode
     * @return {?}
     */
    jvm.VMLElement.prototype.createElement = function(vnode) {
      return document.createElement("<" + vnode + ' xmlns="urn:schemas-microsoft.com:vml" class="rvml">');
    };
  }
  document.createStyleSheet().addRule(".rvml", "behavior:url(#default#VML)");
  /** @type {boolean} */
  jvm.VMLElement.VMLInitialized = true;
}, jvm.VMLElement.prototype.getElementCtr = function(ctr) {
  return jvm["VML" + ctr];
}, jvm.VMLElement.prototype.addClass = function(className) {
  jvm.$(this.node).addClass(className);
}, jvm.VMLElement.prototype.applyAttr = function(name, value) {
  /** @type {string} */
  this.node[name] = value;
}, jvm.VMLElement.prototype.getBBox = function() {
  var box = jvm.$(this.node);
  return {
    x : box.position().left / this.canvas.scale,
    y : box.position().top / this.canvas.scale,
    width : box.width() / this.canvas.scale,
    height : box.height() / this.canvas.scale
  };
}, jvm.VMLGroupElement = function() {
  jvm.VMLGroupElement.parentClass.call(this, "group");
  /** @type {string} */
  this.node.style.left = "0px";
  /** @type {string} */
  this.node.style.top = "0px";
  /** @type {string} */
  this.node.coordorigin = "0 0";
}, jvm.inherits(jvm.VMLGroupElement, jvm.VMLElement), jvm.VMLGroupElement.prototype.add = function(element) {
  this.node.appendChild(element.node);
}, jvm.VMLCanvasElement = function(height, container, width) {
  /** @type {string} */
  this.classPrefix = "VML";
  jvm.VMLCanvasElement.parentClass.call(this, "group");
  jvm.AbstractCanvasElement.apply(this, arguments);
  /** @type {string} */
  this.node.style.position = "absolute";
}, jvm.inherits(jvm.VMLCanvasElement, jvm.VMLElement), jvm.mixin(jvm.VMLCanvasElement, jvm.AbstractCanvasElement), jvm.VMLCanvasElement.prototype.setSize = function(width, height) {
  var paths;
  var groups;
  var i;
  var len;
  /** @type {number} */
  this.width = width;
  /** @type {number} */
  this.height = height;
  /** @type {string} */
  this.node.style.width = width + "px";
  /** @type {string} */
  this.node.style.height = height + "px";
  /** @type {string} */
  this.node.coordsize = width + " " + height;
  /** @type {string} */
  this.node.coordorigin = "0 0";
  if (this.rootElement) {
    paths = this.rootElement.node.getElementsByTagName("shape");
    /** @type {number} */
    i = 0;
    len = paths.length;
    for (; i < len; i++) {
      /** @type {string} */
      paths[i].coordsize = width + " " + height;
      /** @type {string} */
      paths[i].style.width = width + "px";
      /** @type {string} */
      paths[i].style.height = height + "px";
    }
    groups = this.node.getElementsByTagName("group");
    /** @type {number} */
    i = 0;
    len = groups.length;
    for (; i < len; i++) {
      /** @type {string} */
      groups[i].coordsize = width + " " + height;
      /** @type {string} */
      groups[i].style.width = width + "px";
      /** @type {string} */
      groups[i].style.height = height + "px";
    }
  }
}, jvm.VMLCanvasElement.prototype.applyTransformParams = function(scale, transX, transY) {
  /** @type {boolean} */
  this.scale = scale;
  /** @type {number} */
  this.transX = transX;
  /** @type {number} */
  this.transY = transY;
  /** @type {string} */
  this.rootElement.node.coordorigin = this.width - transX - this.width / 100 + "," + (this.height - transY - this.height / 100);
  /** @type {string} */
  this.rootElement.node.coordsize = this.width / scale + "," + this.height / scale;
}, jvm.VMLShapeElement = function(name, config) {
  jvm.VMLShapeElement.parentClass.call(this, name, config);
  this.fillElement = new jvm.VMLElement("fill");
  this.strokeElement = new jvm.VMLElement("stroke");
  this.node.appendChild(this.fillElement.node);
  this.node.appendChild(this.strokeElement.node);
  /** @type {boolean} */
  this.node.stroked = false;
  jvm.AbstractShapeElement.apply(this, arguments);
}, jvm.inherits(jvm.VMLShapeElement, jvm.VMLElement), jvm.mixin(jvm.VMLShapeElement, jvm.AbstractShapeElement), jvm.VMLShapeElement.prototype.applyAttr = function(attr, value) {
  switch(attr) {
    case "fill":
      /** @type {string} */
      this.node.fillcolor = value;
      break;
    case "fill-opacity":
      /** @type {string} */
      this.fillElement.node.opacity = Math.round(value * 100) + "%";
      break;
    case "stroke":
      if (value === "none") {
        /** @type {boolean} */
        this.node.stroked = false;
      } else {
        /** @type {boolean} */
        this.node.stroked = true;
      }
      /** @type {string} */
      this.node.strokecolor = value;
      break;
    case "stroke-opacity":
      /** @type {string} */
      this.strokeElement.node.opacity = Math.round(value * 100) + "%";
      break;
    case "stroke-width":
      if (parseInt(value, 10) === 0) {
        /** @type {boolean} */
        this.node.stroked = false;
      } else {
        /** @type {boolean} */
        this.node.stroked = true;
      }
      /** @type {string} */
      this.node.strokeweight = value;
      break;
    case "d":
      this.node.path = jvm.VMLPathElement.pathSvgToVml(value);
      break;
    default:
      jvm.VMLShapeElement.parentClass.prototype.applyAttr.apply(this, arguments);
  }
}, jvm.VMLPathElement = function(config, style) {
  var scale = new jvm.VMLElement("skew");
  jvm.VMLPathElement.parentClass.call(this, "shape", config, style);
  /** @type {string} */
  this.node.coordorigin = "0 0";
  /** @type {boolean} */
  scale.node.on = true;
  /** @type {string} */
  scale.node.matrix = "0.01,0,0,0.01,0,0";
  /** @type {string} */
  scale.node.offset = "0,0";
  this.node.appendChild(scale.node);
}, jvm.inherits(jvm.VMLPathElement, jvm.VMLShapeElement), jvm.VMLPathElement.prototype.applyAttr = function(name, value) {
  if (name === "d") {
    this.node.path = jvm.VMLPathElement.pathSvgToVml(value);
  } else {
    jvm.VMLShapeElement.prototype.applyAttr.call(this, name, value);
  }
}, jvm.VMLPathElement.pathSvgToVml = function(path) {
  /** @type {string} */
  var th_field = "";
  /** @type {number} */
  var size = 0;
  /** @type {number} */
  var index = 0;
  var i;
  var offset;
  return path = path.replace(/(-?\d+)e(-?\d+)/g, "0"), path.replace(/([MmLlHhVvCcSs])\s*((?:-?\d*(?:\.\d+)?\s*,?\s*)+)/g, function(canCreateDiscussions, letter, data, isSlidingUp) {
    data = data.replace(/(\d)-/g, "$1,-").replace(/^\s+/g, "").replace(/\s+$/g, "").replace(/\s+/g, ",").split(",");
    if (!data[0]) {
      data.shift();
    }
    /** @type {number} */
    var i = 0;
    var layerNum = data.length;
    for (; i < layerNum; i++) {
      /** @type {number} */
      data[i] = Math.round(100 * data[i]);
    }
    switch(letter) {
      case "m":
        return size = size + data[0], index = index + data[1], "t" + data.join(",");
      case "M":
        return size = data[0], index = data[1], "m" + data.join(",");
      case "l":
        return size = size + data[0], index = index + data[1], "r" + data.join(",");
      case "L":
        return size = data[0], index = data[1], "l" + data.join(",");
      case "h":
        return size = size + data[0], "r" + data[0] + ",0";
      case "H":
        return size = data[0], "l" + size + "," + index;
      case "v":
        return index = index + data[0], "r0," + data[0];
      case "V":
        return index = data[0], "l" + size + "," + index;
      case "c":
        return i = size + data[data.length - 4], offset = index + data[data.length - 3], size = size + data[data.length - 2], index = index + data[data.length - 1], "v" + data.join(",");
      case "C":
        return i = data[data.length - 4], offset = data[data.length - 3], size = data[data.length - 2], index = data[data.length - 1], "c" + data.join(",");
      case "s":
        return data.unshift(index - offset), data.unshift(size - i), i = size + data[data.length - 4], offset = index + data[data.length - 3], size = size + data[data.length - 2], index = index + data[data.length - 1], "v" + data.join(",");
      case "S":
        return data.unshift(index + index - offset), data.unshift(size + size - i), i = data[data.length - 4], offset = data[data.length - 3], size = data[data.length - 2], index = data[data.length - 1], "c" + data.join(",");
    }
    return "";
  }).replace(/z/g, "e");
}, jvm.VMLCircleElement = function(config, style) {
  jvm.VMLCircleElement.parentClass.call(this, "oval", config, style);
}, jvm.inherits(jvm.VMLCircleElement, jvm.VMLShapeElement), jvm.VMLCircleElement.prototype.applyAttr = function(name, value) {
  switch(name) {
    case "r":
      /** @type {string} */
      this.node.style.width = value * 2 + "px";
      /** @type {string} */
      this.node.style.height = value * 2 + "px";
      this.applyAttr("cx", this.get("cx") || 0);
      this.applyAttr("cy", this.get("cy") || 0);
      break;
    case "cx":
      if (!value) {
        return;
      }
      /** @type {string} */
      this.node.style.left = value - (this.get("r") || 0) + "px";
      break;
    case "cy":
      if (!value) {
        return;
      }
      /** @type {string} */
      this.node.style.top = value - (this.get("r") || 0) + "px";
      break;
    default:
      jvm.VMLCircleElement.parentClass.prototype.applyAttr.call(this, name, value);
  }
}, jvm.VectorCanvas = function(container, width, height) {
  return this.mode = window.SVGAngle ? "svg" : "vml", this.mode == "svg" ? this.impl = new jvm.SVGCanvasElement(container, width, height) : this.impl = new jvm.VMLCanvasElement(container, width, height), this.impl;
}, jvm.SimpleScale = function(scale) {
  /** @type {boolean} */
  this.scale = scale;
}, jvm.SimpleScale.prototype.getValue = function(min) {
  return min;
}, jvm.OrdinalScale = function(scale) {
  /** @type {number} */
  this.scale = scale;
}, jvm.OrdinalScale.prototype.getValue = function(value) {
  return this.scale[value];
}, jvm.NumericScale = function(scale, normalizeFunction, minValue, maxValue) {
  /** @type {!Array} */
  this.scale = [];
  normalizeFunction = normalizeFunction || "linear";
  if (scale) {
    this.setScale(scale);
  }
  if (normalizeFunction) {
    this.setNormalizeFunction(normalizeFunction);
  }
  if (minValue) {
    this.setMin(minValue);
  }
  if (maxValue) {
    this.setMax(maxValue);
  }
}, jvm.NumericScale.prototype = {
  setMin : function(min) {
    /** @type {number} */
    this.clearMinValue = min;
    if (typeof this.normalize == "function") {
      this.minValue = this.normalize(min);
    } else {
      /** @type {number} */
      this.minValue = min;
    }
  },
  setMax : function(max) {
    /** @type {number} */
    this.clearMaxValue = max;
    if (typeof this.normalize == "function") {
      this.maxValue = this.normalize(max);
    } else {
      /** @type {number} */
      this.maxValue = max;
    }
  },
  setScale : function(s) {
    var i;
    /** @type {number} */
    i = 0;
    for (; i < s.length; i++) {
      /** @type {!Array} */
      this.scale[i] = [s[i]];
    }
  },
  setNormalizeFunction : function(f) {
    if (f === "polynomial") {
      /**
       * @param {number} val
       * @return {?}
       */
      this.normalize = function(val) {
        return Math.pow(val, .2);
      };
    } else {
      if (f === "linear") {
        delete this.normalize;
      } else {
        /** @type {string} */
        this.normalize = f;
      }
    }
    this.setMin(this.clearMinValue);
    this.setMax(this.clearMaxValue);
  },
  getValue : function(value) {
    /** @type {!Array} */
    var lengthes = [];
    /** @type {number} */
    var fullLength = 0;
    var l;
    /** @type {number} */
    var i = 0;
    var c;
    if (typeof this.normalize == "function") {
      value = this.normalize(value);
    }
    /** @type {number} */
    i = 0;
    for (; i < this.scale.length - 1; i++) {
      l = this.vectorLength(this.vectorSubtract(this.scale[i + 1], this.scale[i]));
      lengthes.push(l);
      fullLength = fullLength + l;
    }
    /** @type {number} */
    c = (this.maxValue - this.minValue) / fullLength;
    /** @type {number} */
    i = 0;
    for (; i < lengthes.length; i++) {
      lengthes[i] *= c;
    }
    /** @type {number} */
    i = 0;
    /** @type {number} */
    value = value - this.minValue;
    for (; value - lengthes[i] >= 0;) {
      /** @type {number} */
      value = value - lengthes[i];
      i++;
    }
    return i == this.scale.length - 1 ? value = this.vectorToNum(this.scale[i]) : value = this.vectorToNum(this.vectorAdd(this.scale[i], this.vectorMult(this.vectorSubtract(this.scale[i + 1], this.scale[i]), value / lengthes[i]))), value;
  },
  vectorToNum : function(vector) {
    /** @type {number} */
    var num = 0;
    var i;
    /** @type {number} */
    i = 0;
    for (; i < vector.length; i++) {
      /** @type {number} */
      num = num + Math.round(vector[i]) * Math.pow(256, vector.length - i - 1);
    }
    return num;
  },
  vectorSubtract : function(vector1, vector2) {
    /** @type {!Array} */
    var vector = [];
    var i;
    /** @type {number} */
    i = 0;
    for (; i < vector1.length; i++) {
      /** @type {number} */
      vector[i] = vector1[i] - vector2[i];
    }
    return vector;
  },
  vectorAdd : function(vector1, vector2) {
    /** @type {!Array} */
    var vector = [];
    var i;
    /** @type {number} */
    i = 0;
    for (; i < vector1.length; i++) {
      vector[i] = vector1[i] + vector2[i];
    }
    return vector;
  },
  vectorMult : function(vector, num) {
    /** @type {!Array} */
    var result = [];
    var i;
    /** @type {number} */
    i = 0;
    for (; i < vector.length; i++) {
      /** @type {number} */
      result[i] = vector[i] * num;
    }
    return result;
  },
  vectorLength : function(vector) {
    /** @type {number} */
    var sqsum = 0;
    var i;
    /** @type {number} */
    i = 0;
    for (; i < vector.length; i++) {
      /** @type {number} */
      sqsum = sqsum + vector[i] * vector[i];
    }
    return Math.sqrt(sqsum);
  }
}, jvm.ColorScale = function(colors, normalizeFunction, minValue, maxValue) {
  jvm.ColorScale.parentClass.apply(this, arguments);
}, jvm.inherits(jvm.ColorScale, jvm.NumericScale), jvm.ColorScale.prototype.setScale = function(scale) {
  var i;
  /** @type {number} */
  i = 0;
  for (; i < scale.length; i++) {
    this.scale[i] = jvm.ColorScale.rgbToArray(scale[i]);
  }
}, jvm.ColorScale.prototype.getValue = function(value) {
  return jvm.ColorScale.numToRgb(jvm.ColorScale.parentClass.prototype.getValue.call(this, value));
}, jvm.ColorScale.arrayToRgb = function(ar) {
  /** @type {string} */
  var rgb = "#";
  var d;
  var i;
  /** @type {number} */
  i = 0;
  for (; i < ar.length; i++) {
    d = ar[i].toString(16);
    /** @type {string} */
    rgb = rgb + (d.length == 1 ? "0" + d : d);
  }
  return rgb;
}, jvm.ColorScale.numToRgb = function(num) {
  num = num.toString(16);
  for (; num.length < 6;) {
    /** @type {string} */
    num = "0" + num;
  }
  return "#" + num;
}, jvm.ColorScale.rgbToArray = function(rgb) {
  return rgb = rgb.substr(1), [parseInt(rgb.substr(0, 2), 16), parseInt(rgb.substr(2, 2), 16), parseInt(rgb.substr(4, 2), 16)];
}, jvm.DataSeries = function(params, elements) {
  var scaleConstructor;
  params = params || {};
  params.attribute = params.attribute || "fill";
  /** @type {!HTMLElement} */
  this.elements = elements;
  /** @type {!Object} */
  this.params = params;
  if (params.attributes) {
    this.setAttributes(params.attributes);
  }
  if (jvm.$.isArray(params.scale)) {
    /** @type {function(?, ?, ?, ?): undefined} */
    scaleConstructor = params.attribute === "fill" || params.attribute === "stroke" ? jvm.ColorScale : jvm.NumericScale;
    this.scale = new scaleConstructor(params.scale, params.normalizeFunction, params.min, params.max);
  } else {
    if (params.scale) {
      this.scale = new jvm.OrdinalScale(params.scale);
    } else {
      this.scale = new jvm.SimpleScale(params.scale);
    }
  }
  this.values = params.values || {};
  this.setValues(this.values);
}, jvm.DataSeries.prototype = {
  setAttributes : function(id, n) {
    var options = id;
    var i;
    if (typeof id == "string") {
      if (this.elements[id]) {
        this.elements[id].setStyle(this.params.attribute, n);
      }
    } else {
      for (i in options) {
        if (this.elements[i]) {
          this.elements[i].element.setStyle(this.params.attribute, options[i]);
        }
      }
    }
  },
  setValues : function(values) {
    /** @type {number} */
    var max = Number.MIN_VALUE;
    /** @type {number} */
    var min = Number.MAX_VALUE;
    var val;
    var cc;
    var attrs = {};
    if (this.scale instanceof jvm.OrdinalScale || this.scale instanceof jvm.SimpleScale) {
      for (cc in values) {
        if (values[cc]) {
          attrs[cc] = this.scale.getValue(values[cc]);
        } else {
          attrs[cc] = this.elements[cc].element.style.initial[this.params.attribute];
        }
      }
    } else {
      if (!this.params.min || !this.params.max) {
        for (cc in values) {
          /** @type {number} */
          val = parseFloat(values[cc]);
          if (val > max) {
            max = values[cc];
          }
          if (val < min) {
            /** @type {number} */
            min = val;
          }
        }
        if (!this.params.min) {
          this.scale.setMin(min);
        }
        if (!this.params.max) {
          this.scale.setMax(max);
        }
        /** @type {number} */
        this.params.min = min;
        this.params.max = max;
      }
      for (cc in values) {
        /** @type {number} */
        val = parseFloat(values[cc]);
        if (isNaN(val)) {
          attrs[cc] = this.elements[cc].element.style.initial[this.params.attribute];
        } else {
          attrs[cc] = this.scale.getValue(val);
        }
      }
    }
    this.setAttributes(attrs);
    jvm.$.extend(this.values, values);
  },
  clear : function() {
    var i;
    var changes = {};
    for (i in this.values) {
      if (this.elements[i]) {
        changes[i] = this.elements[i].element.style.initial[this.params.attribute];
      }
    }
    this.setAttributes(changes);
    this.values = {};
  },
  setScale : function(scale) {
    this.scale.setScale(scale);
    if (this.values) {
      this.setValues(this.values);
    }
  },
  setNormalizeFunction : function(f) {
    this.scale.setNormalizeFunction(f);
    if (this.values) {
      this.setValues(this.values);
    }
  }
}, jvm.Proj = {
  degRad : 180 / Math.PI,
  radDeg : Math.PI / 180,
  radius : 6381372,
  sgn : function(n) {
    return n > 0 ? 1 : n < 0 ? -1 : n;
  },
  mill : function(lat, lng, c) {
    return {
      x : this.radius * (lng - c) * this.radDeg,
      y : -this.radius * Math.log(Math.tan((45 + .4 * lat) * this.radDeg)) / .8
    };
  },
  mill_inv : function(x, y, c) {
    return {
      lat : (2.5 * Math.atan(Math.exp(.8 * y / this.radius)) - 5 * Math.PI / 8) * this.degRad,
      lng : (c * this.radDeg + x / this.radius) * this.degRad
    };
  },
  merc : function(lat, lng, c) {
    return {
      x : this.radius * (lng - c) * this.radDeg,
      y : -this.radius * Math.log(Math.tan(Math.PI / 4 + lat * Math.PI / 360))
    };
  },
  merc_inv : function(x, y, c) {
    return {
      lat : (2 * Math.atan(Math.exp(y / this.radius)) - Math.PI / 2) * this.degRad,
      lng : (c * this.radDeg + x / this.radius) * this.degRad
    };
  },
  aea : function(lat, lng, c) {
    /** @type {number} */
    var newangle2 = 0;
    /** @type {number} */
    var lambda0 = c * this.radDeg;
    /** @type {number} */
    var fi1 = 29.5 * this.radDeg;
    /** @type {number} */
    var fi2 = 45.5 * this.radDeg;
    /** @type {number} */
    var fi = lat * this.radDeg;
    /** @type {number} */
    var lambda = lng * this.radDeg;
    /** @type {number} */
    var n = (Math.sin(fi1) + Math.sin(fi2)) / 2;
    /** @type {number} */
    var mu2 = Math.cos(fi1) * Math.cos(fi1) + 2 * n * Math.sin(fi1);
    /** @type {number} */
    var theta = n * (lambda - lambda0);
    /** @type {number} */
    var ro = Math.sqrt(mu2 - 2 * n * Math.sin(fi)) / n;
    /** @type {number} */
    var ro0 = Math.sqrt(mu2 - 2 * n * Math.sin(newangle2)) / n;
    return {
      x : ro * Math.sin(theta) * this.radius,
      y : -(ro0 - ro * Math.cos(theta)) * this.radius
    };
  },
  aea_inv : function(xCoord, yCoord, c) {
    /** @type {number} */
    var x = xCoord / this.radius;
    /** @type {number} */
    var y = yCoord / this.radius;
    /** @type {number} */
    var fi = 0;
    /** @type {number} */
    var lambda0 = c * this.radDeg;
    /** @type {number} */
    var fi1 = 29.5 * this.radDeg;
    /** @type {number} */
    var fi2 = 45.5 * this.radDeg;
    /** @type {number} */
    var n = (Math.sin(fi1) + Math.sin(fi2)) / 2;
    /** @type {number} */
    var C = Math.cos(fi1) * Math.cos(fi1) + 2 * n * Math.sin(fi1);
    /** @type {number} */
    var ro0 = Math.sqrt(C - 2 * n * Math.sin(fi)) / n;
    /** @type {number} */
    var ro = Math.sqrt(x * x + (ro0 - y) * (ro0 - y));
    /** @type {number} */
    var theta = Math.atan(x / (ro0 - y));
    return {
      lat : Math.asin((C - ro * ro * n * n) / (2 * n)) * this.degRad,
      lng : (lambda0 + theta / n) * this.degRad
    };
  },
  lcc : function(lng, lat, c) {
    /** @type {number} */
    var r = 0;
    /** @type {number} */
    var lambda0 = c * this.radDeg;
    /** @type {number} */
    var fi = lat * this.radDeg;
    /** @type {number} */
    var fi1 = 33 * this.radDeg;
    /** @type {number} */
    var fi2 = 45 * this.radDeg;
    /** @type {number} */
    var lambda = lng * this.radDeg;
    /** @type {number} */
    var n = Math.log(Math.cos(fi1) * (1 / Math.cos(fi2))) / Math.log(Math.tan(Math.PI / 4 + fi2 / 2) * (1 / Math.tan(Math.PI / 4 + fi1 / 2)));
    /** @type {number} */
    var F = Math.cos(fi1) * Math.pow(Math.tan(Math.PI / 4 + fi1 / 2), n) / n;
    /** @type {number} */
    var ro = F * Math.pow(1 / Math.tan(Math.PI / 4 + lambda / 2), n);
    /** @type {number} */
    var ro0 = F * Math.pow(1 / Math.tan(Math.PI / 4 + r / 2), n);
    return {
      x : ro * Math.sin(n * (fi - lambda0)) * this.radius,
      y : -(ro0 - ro * Math.cos(n * (fi - lambda0))) * this.radius
    };
  },
  lcc_inv : function(xCoord, yCoord, c) {
    /** @type {number} */
    var x = xCoord / this.radius;
    /** @type {number} */
    var y = yCoord / this.radius;
    /** @type {number} */
    var s = 0;
    /** @type {number} */
    var lambda0 = c * this.radDeg;
    /** @type {number} */
    var fi1 = 33 * this.radDeg;
    /** @type {number} */
    var lambda = 45 * this.radDeg;
    /** @type {number} */
    var n = Math.log(Math.cos(fi1) * (1 / Math.cos(lambda))) / Math.log(Math.tan(Math.PI / 4 + lambda / 2) * (1 / Math.tan(Math.PI / 4 + fi1 / 2)));
    /** @type {number} */
    var F = Math.cos(fi1) * Math.pow(Math.tan(Math.PI / 4 + fi1 / 2), n) / n;
    /** @type {number} */
    var ro0 = F * Math.pow(1 / Math.tan(Math.PI / 4 + s / 2), n);
    /** @type {number} */
    var ro = this.sgn(n) * Math.sqrt(x * x + (ro0 - y) * (ro0 - y));
    /** @type {number} */
    var theta = Math.atan(x / (ro0 - y));
    return {
      lat : (2 * Math.atan(Math.pow(F / ro, 1 / n)) - Math.PI / 2) * this.degRad,
      lng : (lambda0 + theta / n) * this.degRad
    };
  }
}, jvm.WorldMap = function(params) {
  var new_message_field = this;
  var e;
  this.params = jvm.$.extend(true, {}, jvm.WorldMap.defaultParams, params);
  if (!jvm.WorldMap.maps[this.params.map]) {
    throw new Error("Attempt to use map which was not loaded: " + this.params.map);
  }
  this.mapData = jvm.WorldMap.maps[this.params.map];
  this.markers = {};
  this.regions = {};
  this.regionsColors = {};
  this.regionsData = {};
  this.container = jvm.$("<div>").css({
    width : "100%",
    height : "100%"
  }).addClass("jvectormap-container");
  this.params.container.append(this.container);
  this.container.data("mapObject", this);
  this.container.css({
    position : "relative",
    overflow : "hidden"
  });
  this.defaultWidth = this.mapData.width;
  this.defaultHeight = this.mapData.height;
  this.setBackgroundColor(this.params.backgroundColor);
  /**
   * @return {undefined}
   */
  this.onResize = function() {
    try {
      new_message_field.setSize();
    }
    catch(errorexception) {}
  };
  jvm.$(window).resize(this.onResize);
  for (e in jvm.WorldMap.apiEvents) {
    if (this.params[e]) {
      this.container.bind(jvm.WorldMap.apiEvents[e] + ".jvectormap", this.params[e]);
    }
  }
  this.canvas = new jvm.VectorCanvas(this.container[0], this.width, this.height);
  if ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch) {
    if (this.params.bindTouchEvents) {
      this.bindContainerTouchEvents();
    }
  } else {
    this.bindContainerEvents();
  }
  this.bindElementEvents();
  this.createLabel();
  if (this.params.zoomButtons) {
    this.bindZoomButtons();
  }
  this.createRegions();
  this.createMarkers(this.params.markers || {});
  this.setSize();
  if (this.params.focusOn) {
    if (typeof this.params.focusOn == "object") {
      this.setFocus.call(this, this.params.focusOn.scale, this.params.focusOn.x, this.params.focusOn.y);
    } else {
      this.setFocus.call(this, this.params.focusOn);
    }
  }
  if (this.params.selectedRegions) {
    this.setSelectedRegions(this.params.selectedRegions);
  }
  if (this.params.selectedMarkers) {
    this.setSelectedMarkers(this.params.selectedMarkers);
  }
  if (this.params.series) {
    this.createSeries();
  }
}, jvm.WorldMap.prototype = {
  transX : 0,
  transY : 0,
  scale : 1,
  baseTransX : 0,
  baseTransY : 0,
  baseScale : 1,
  width : 0,
  height : 0,
  setBackgroundColor : function(backgroundColor) {
    this.container.css("background-color", backgroundColor);
  },
  resize : function() {
    try {
      var curBaseScale = this.baseScale;
      if (this.width / this.height > this.defaultWidth / this.defaultHeight) {
        /** @type {number} */
        this.baseScale = this.height / this.defaultHeight;
        /** @type {number} */
        this.baseTransX = Math.abs(this.width - this.defaultWidth * this.baseScale) / (2 * this.baseScale);
      } else {
        /** @type {number} */
        this.baseScale = this.width / this.defaultWidth;
        /** @type {number} */
        this.baseTransY = Math.abs(this.height - this.defaultHeight * this.baseScale) / (2 * this.baseScale);
      }
      this.scale *= this.baseScale / curBaseScale;
      this.transX *= this.baseScale / curBaseScale;
      this.transY *= this.baseScale / curBaseScale;
    }
    catch(errorexception) {}
  },
  setSize : function() {
    this.width = this.container.width();
    this.height = this.container.height();
    this.resize();
    this.canvas.setSize(this.width, this.height);
    this.applyTransform();
  },
  reset : function() {
    var key;
    var i;
    for (key in this.series) {
      /** @type {number} */
      i = 0;
      for (; i < this.series[key].length; i++) {
        this.series[key][i].clear();
      }
    }
    this.scale = this.baseScale;
    this.transX = this.baseTransX;
    this.transY = this.baseTransY;
    this.applyTransform();
  },
  applyTransform : function() {
    var maxTransX;
    var maxTransY;
    var minTransX;
    var minTransY;
    if (this.defaultWidth * this.scale <= this.width) {
      /** @type {number} */
      maxTransX = (this.width - this.defaultWidth * this.scale) / (2 * this.scale);
      /** @type {number} */
      minTransX = (this.width - this.defaultWidth * this.scale) / (2 * this.scale);
    } else {
      /** @type {number} */
      maxTransX = 0;
      /** @type {number} */
      minTransX = (this.width - this.defaultWidth * this.scale) / this.scale;
    }
    if (this.defaultHeight * this.scale <= this.height) {
      /** @type {number} */
      maxTransY = (this.height - this.defaultHeight * this.scale) / (2 * this.scale);
      /** @type {number} */
      minTransY = (this.height - this.defaultHeight * this.scale) / (2 * this.scale);
    } else {
      /** @type {number} */
      maxTransY = 0;
      /** @type {number} */
      minTransY = (this.height - this.defaultHeight * this.scale) / this.scale;
    }
    if (this.transY > maxTransY) {
      this.transY = maxTransY;
    } else {
      if (this.transY < minTransY) {
        this.transY = minTransY;
      }
    }
    if (this.transX > maxTransX) {
      this.transX = maxTransX;
    } else {
      if (this.transX < minTransX) {
        this.transX = minTransX;
      }
    }
    this.canvas.applyTransformParams(this.scale, this.transX, this.transY);
    if (this.markers) {
      this.repositionMarkers();
    }
    this.container.trigger("viewportChange", [this.scale / this.baseScale, this.transX, this.transY]);
  },
  bindContainerEvents : function() {
    /** @type {boolean} */
    var ret = false;
    var oldPageX;
    var oldPageY;
    var map = this;
    this.container.mousemove(function(e) {
      return ret && (map.transX -= (oldPageX - e.pageX) / map.scale, map.transY -= (oldPageY - e.pageY) / map.scale, map.applyTransform(), oldPageX = e.pageX, oldPageY = e.pageY), false;
    }).mousedown(function(e) {
      return ret = true, oldPageX = e.pageX, oldPageY = e.pageY, false;
    });
    jvm.$("body").mouseup(function() {
      /** @type {boolean} */
      ret = false;
    });
    if (this.params.zoomOnScroll) {
      this.container.mousewheel(function(evt, canCreateDiscussions, n, howManyToRound) {
        var stop_offset = jvm.$(map.container).offset();
        /** @type {number} */
        var centerTouchX = evt.pageX - stop_offset.left;
        /** @type {number} */
        var centerTouchY = evt.pageY - stop_offset.top;
        /** @type {number} */
        var zoomStep = Math.pow(1.3, howManyToRound);
        map.label.hide();
        map.setScale(map.scale * zoomStep, centerTouchX, centerTouchY);
        evt.preventDefault();
      });
    }
  },
  bindContainerTouchEvents : function() {
    var touchStartScale;
    var backingRatio;
    var map = this;
    var oldPageX;
    var oldPageY;
    var centerTouchX;
    var centerTouchY;
    var lastTouchesLength;
    /**
     * @param {!Object} event
     * @return {undefined}
     */
    var handleTouchEvent = function(event) {
      var touches = event.originalEvent.touches;
      var offset;
      var scale;
      var transXOld;
      var transYOld;
      if (event.type == "touchstart") {
        /** @type {number} */
        lastTouchesLength = 0;
      }
      if (touches.length == 1) {
        if (lastTouchesLength == 1) {
          transXOld = map.transX;
          transYOld = map.transY;
          map.transX -= (oldPageX - touches[0].pageX) / map.scale;
          map.transY -= (oldPageY - touches[0].pageY) / map.scale;
          map.applyTransform();
          map.label.hide();
          if (transXOld != map.transX || transYOld != map.transY) {
            event.preventDefault();
          }
        }
        oldPageX = touches[0].pageX;
        oldPageY = touches[0].pageY;
      } else {
        if (touches.length == 2) {
          if (lastTouchesLength == 2) {
            /** @type {number} */
            scale = Math.sqrt(Math.pow(touches[0].pageX - touches[1].pageX, 2) + Math.pow(touches[0].pageY - touches[1].pageY, 2)) / backingRatio;
            map.setScale(touchStartScale * scale, centerTouchX, centerTouchY);
            map.label.hide();
            event.preventDefault();
          } else {
            offset = jvm.$(map.container).offset();
            if (touches[0].pageX > touches[1].pageX) {
              centerTouchX = touches[1].pageX + (touches[0].pageX - touches[1].pageX) / 2;
            } else {
              centerTouchX = touches[0].pageX + (touches[1].pageX - touches[0].pageX) / 2;
            }
            if (touches[0].pageY > touches[1].pageY) {
              centerTouchY = touches[1].pageY + (touches[0].pageY - touches[1].pageY) / 2;
            } else {
              centerTouchY = touches[0].pageY + (touches[1].pageY - touches[0].pageY) / 2;
            }
            /** @type {number} */
            centerTouchX = centerTouchX - offset.left;
            /** @type {number} */
            centerTouchY = centerTouchY - offset.top;
            touchStartScale = map.scale;
            /** @type {number} */
            backingRatio = Math.sqrt(Math.pow(touches[0].pageX - touches[1].pageX, 2) + Math.pow(touches[0].pageY - touches[1].pageY, 2));
          }
        }
      }
      lastTouchesLength = touches.length;
    };
    jvm.$(this.container).bind("touchstart", handleTouchEvent);
    jvm.$(this.container).bind("touchmove", handleTouchEvent);
  },
  bindElementEvents : function() {
    var map = this;
    var t;
    this.container.mousemove(function() {
      /** @type {boolean} */
      t = true;
    });
    this.container.delegate("[class~='jvectormap-element']", "mouseover mouseout", function(event) {
      var n = this;
      var baseVal = jvm.$(this).attr("class").baseVal ? jvm.$(this).attr("class").baseVal : jvm.$(this).attr("class");
      /** @type {string} */
      var type = baseVal.indexOf("jvectormap-region") === -1 ? "marker" : "region";
      var code = type == "region" ? jvm.$(this).attr("data-code") : jvm.$(this).attr("data-index");
      var element = type == "region" ? map.regions[code].element : map.markers[code].element;
      var geoJSON_str = type == "region" ? map.mapData.paths[code].name : map.markers[code].config.name || "";
      var beforeNavigate = jvm.$.Event(type + "LabelShow.jvectormap");
      var dragStartEvent = jvm.$.Event(type + "Over.jvectormap");
      if (event.type == "mouseover") {
        map.container.trigger(dragStartEvent, [code]);
        if (!dragStartEvent.isDefaultPrevented()) {
          element.setHovered(true);
        }
        map.label.text(geoJSON_str);
        map.container.trigger(beforeNavigate, [map.label, code]);
        if (!beforeNavigate.isDefaultPrevented()) {
          map.label.show();
          map.labelWidth = map.label.width();
          map.labelHeight = map.label.height();
        }
      } else {
        element.setHovered(false);
        map.label.hide();
        map.container.trigger(type + "Out.jvectormap", [code]);
      }
    });
    this.container.delegate("[class~='jvectormap-element']", "mousedown", function(canCreateDiscussions) {
      /** @type {boolean} */
      t = false;
    });
    this.container.delegate("[class~='jvectormap-element']", "mouseup", function(n) {
      var r = this;
      var baseVal = jvm.$(this).attr("class").baseVal ? jvm.$(this).attr("class").baseVal : jvm.$(this).attr("class");
      /** @type {string} */
      var type = baseVal.indexOf("jvectormap-region") === -1 ? "marker" : "region";
      var code = type == "region" ? jvm.$(this).attr("data-code") : jvm.$(this).attr("data-index");
      var event = jvm.$.Event(type + "Click.jvectormap");
      var calCheckBox = type == "region" ? map.regions[code].element : map.markers[code].element;
      if (!t) {
        map.container.trigger(event, [code]);
        if (type === "region" && map.params.regionsSelectable || type === "marker" && map.params.markersSelectable) {
          if (!event.isDefaultPrevented()) {
            if (map.params[type + "sSelectableOne"]) {
              map.clearSelected(type + "s");
            }
            calCheckBox.setSelected(!calCheckBox.isSelected);
          }
        }
      }
    });
  },
  bindZoomButtons : function() {
    var map = this;
    jvm.$("<div/>").addClass("jvectormap-zoomin").text("+").appendTo(this.container);
    jvm.$("<div/>").addClass("jvectormap-zoomout").html("&#x2212;").appendTo(this.container);
    this.container.find(".jvectormap-zoomin").click(function() {
      map.setScale(map.scale * map.params.zoomStep, map.width / 2, map.height / 2);
    });
    this.container.find(".jvectormap-zoomout").click(function() {
      map.setScale(map.scale / map.params.zoomStep, map.width / 2, map.height / 2);
    });
  },
  createLabel : function() {
    var $ = this;
    this.label = jvm.$("<div/>").addClass("jvectormap-label").appendTo(jvm.$("body"));
    this.container.mousemove(function(event) {
      /** @type {number} */
      var _ileft = event.pageX - 15 - $.labelWidth;
      /** @type {number} */
      var tabPadding = event.pageY - 15 - $.labelHeight;
      if (_ileft < 5) {
        _ileft = event.pageX + 15;
      }
      if (tabPadding < 5) {
        tabPadding = event.pageY + 15;
      }
      if ($.label.is(":visible")) {
        $.label.css({
          left : _ileft,
          top : tabPadding
        });
      }
    });
  },
  setScale : function(scale, anchorX, anchorY, isCentered) {
    var zoomStep;
    var s = jvm.$.Event("zoom.jvectormap");
    if (scale > this.params.zoomMax * this.baseScale) {
      /** @type {number} */
      scale = this.params.zoomMax * this.baseScale;
    } else {
      if (scale < this.params.zoomMin * this.baseScale) {
        /** @type {number} */
        scale = this.params.zoomMin * this.baseScale;
      }
    }
    if (typeof anchorX != "undefined" && typeof anchorY != "undefined") {
      /** @type {number} */
      zoomStep = scale / this.scale;
      if (isCentered) {
        this.transX = anchorX + this.defaultWidth * (this.width / (this.defaultWidth * scale)) / 2;
        this.transY = anchorY + this.defaultHeight * (this.height / (this.defaultHeight * scale)) / 2;
      } else {
        this.transX -= (zoomStep - 1) / scale * anchorX;
        this.transY -= (zoomStep - 1) / scale * anchorY;
      }
    }
    /** @type {number} */
    this.scale = scale;
    this.applyTransform();
    this.container.trigger(s, [scale / this.baseScale]);
  },
  setFocus : function(scale, n, name) {
    var bbox;
    var value;
    var newBbox;
    var matrixes;
    var i;
    if (jvm.$.isArray(scale) || this.regions[scale]) {
      if (jvm.$.isArray(scale)) {
        /** @type {number} */
        matrixes = scale;
      } else {
        /** @type {!Array} */
        matrixes = [scale];
      }
      /** @type {number} */
      i = 0;
      for (; i < matrixes.length; i++) {
        if (this.regions[matrixes[i]]) {
          value = this.regions[matrixes[i]].element.getBBox();
          if (value) {
            if (typeof bbox == "undefined") {
              bbox = value;
            } else {
              newBbox = {
                x : Math.min(bbox.x, value.x),
                y : Math.min(bbox.y, value.y),
                width : Math.max(bbox.x + bbox.width, value.x + value.width) - Math.min(bbox.x, value.x),
                height : Math.max(bbox.y + bbox.height, value.y + value.height) - Math.min(bbox.y, value.y)
              };
              bbox = newBbox;
            }
          }
        }
      }
      this.setScale(Math.min(this.width / bbox.width, this.height / bbox.height), -(bbox.x + bbox.width / 2), -(bbox.y + bbox.height / 2), true);
    } else {
      /** @type {number} */
      scale = scale * this.baseScale;
      this.setScale(scale, -n * this.defaultWidth, -name * this.defaultHeight, true);
    }
  },
  getSelected : function(type) {
    var key;
    /** @type {!Array} */
    var result = [];
    for (key in this[type]) {
      if (this[type][key].element.isSelected) {
        result.push(key);
      }
    }
    return result;
  },
  getSelectedRegions : function() {
    return this.getSelected("regions");
  },
  getSelectedMarkers : function() {
    return this.getSelected("markers");
  },
  setSelected : function(type, keys) {
    var i;
    if (typeof keys != "object") {
      /** @type {!Array} */
      keys = [keys];
    }
    if (jvm.$.isArray(keys)) {
      /** @type {number} */
      i = 0;
      for (; i < keys.length; i++) {
        this[type][keys[i]].element.setSelected(true);
      }
    } else {
      for (i in keys) {
        this[type][i].element.setSelected(!!keys[i]);
      }
    }
  },
  setSelectedRegions : function(keys) {
    this.setSelected("regions", keys);
  },
  setSelectedMarkers : function(keys) {
    this.setSelected("markers", keys);
  },
  clearSelected : function(type) {
    var keys = {};
    var tags = this.getSelected(type);
    var i;
    /** @type {number} */
    i = 0;
    for (; i < tags.length; i++) {
      /** @type {boolean} */
      keys[tags[i]] = false;
    }
    this.setSelected(type, keys);
  },
  clearSelectedRegions : function() {
    this.clearSelected("regions");
  },
  clearSelectedMarkers : function() {
    this.clearSelected("markers");
  },
  getMapObject : function() {
    return this;
  },
  getRegionName : function(code) {
    return this.mapData.paths[code].name;
  },
  createRegions : function() {
    var key;
    var marker;
    var map = this;
    for (key in this.mapData.paths) {
      marker = this.canvas.addPath({
        d : this.mapData.paths[key].path,
        "data-code" : key
      }, jvm.$.extend(true, {}, this.params.regionStyle));
      jvm.$(marker.node).bind("selected", function(canCreateDiscussions, usingWorker) {
        map.container.trigger("regionSelected.jvectormap", [jvm.$(this).attr("data-code"), usingWorker, map.getSelectedRegions()]);
      });
      marker.addClass("jvectormap-region jvectormap-element");
      this.regions[key] = {
        element : marker,
        config : this.mapData.paths[key]
      };
    }
  },
  createMarkers : function(markers) {
    var i;
    var marker;
    var point;
    var markerConfig;
    var markersArray;
    var map = this;
    this.markersGroup = this.markersGroup || this.canvas.addGroup();
    if (jvm.$.isArray(markers)) {
      markersArray = markers.slice();
      markers = {};
      /** @type {number} */
      i = 0;
      for (; i < markersArray.length; i++) {
        markers[i] = markersArray[i];
      }
    }
    for (i in markers) {
      markerConfig = markers[i] instanceof Array ? {
        latLng : markers[i]
      } : markers[i];
      point = this.getMarkerPosition(markerConfig);
      if (point !== false) {
        marker = this.canvas.addCircle({
          "data-index" : i,
          cx : point.x,
          cy : point.y
        }, jvm.$.extend(true, {}, this.params.markerStyle, {
          initial : markerConfig.style || {}
        }), this.markersGroup);
        marker.addClass("jvectormap-marker jvectormap-element");
        jvm.$(marker.node).bind("selected", function(canCreateDiscussions, usingWorker) {
          map.container.trigger("markerSelected.jvectormap", [jvm.$(this).attr("data-index"), usingWorker, map.getSelectedMarkers()]);
        });
        if (this.markers[i]) {
          this.removeMarkers([i]);
        }
        this.markers[i] = {
          element : marker,
          config : markerConfig
        };
      }
    }
  },
  repositionMarkers : function() {
    var i;
    var point;
    for (i in this.markers) {
      point = this.getMarkerPosition(this.markers[i].config);
      if (point !== false) {
        this.markers[i].element.setStyle({
          cx : point.x,
          cy : point.y
        });
      }
    }
  },
  getMarkerPosition : function(markerConfig) {
    return jvm.WorldMap.maps[this.params.map].projection ? this.latLngToPoint.apply(this, markerConfig.latLng || [0, 0]) : {
      x : markerConfig.coords[0] * this.scale + this.transX * this.scale,
      y : markerConfig.coords[1] * this.scale + this.transY * this.scale
    };
  },
  addMarker : function(key, marker, options) {
    var markers = {};
    /** @type {!Array} */
    var data = [];
    var config;
    var i;
    options = options || [];
    markers[key] = marker;
    /** @type {number} */
    i = 0;
    for (; i < options.length; i++) {
      config = {};
      config[key] = options[i];
      data.push(config);
    }
    this.addMarkers(markers, data);
  },
  addMarkers : function(markers, seriesData) {
    var i;
    seriesData = seriesData || [];
    this.createMarkers(markers);
    /** @type {number} */
    i = 0;
    for (; i < seriesData.length; i++) {
      this.series.markers[i].setValues(seriesData[i] || {});
    }
  },
  removeMarkers : function(markers) {
    var i;
    /** @type {number} */
    i = 0;
    for (; i < markers.length; i++) {
      this.markers[markers[i]].element.remove();
      delete this.markers[markers[i]];
    }
  },
  removeAllMarkers : function() {
    var i;
    /** @type {!Array} */
    var markers = [];
    for (i in this.markers) {
      markers.push(i);
    }
    this.removeMarkers(markers);
  },
  latLngToPoint : function(lat, lng) {
    var point;
    var proj = jvm.WorldMap.maps[this.params.map].projection;
    var centralMeridian = proj.centralMeridian;
    /** @type {number} */
    var width = this.width - this.baseTransX * 2 * this.baseScale;
    /** @type {number} */
    var bottom = this.height - this.baseTransY * 2 * this.baseScale;
    var inset;
    var bbox;
    /** @type {number} */
    var s = this.scale / this.baseScale;
    return lng < -180 + centralMeridian && (lng = lng + 360), point = jvm.Proj[proj.type](lat, lng, centralMeridian), inset = this.getInsetForPoint(point.x, point.y), inset ? (bbox = inset.bbox, point.x = (point.x - bbox[0].x) / (bbox[1].x - bbox[0].x) * inset.width * this.scale, point.y = (point.y - bbox[0].y) / (bbox[1].y - bbox[0].y) * inset.height * this.scale, {
      x : point.x + this.transX * this.scale + inset.left * this.scale,
      y : point.y + this.transY * this.scale + inset.top * this.scale
    }) : false;
  },
  pointToLatLng : function(x, y) {
    var proj = jvm.WorldMap.maps[this.params.map].projection;
    var centralMeridian = proj.centralMeridian;
    var crossfilterable_layers = jvm.WorldMap.maps[this.params.map].insets;
    var layer_i;
    var inset;
    var bbox;
    var nx;
    var ny;
    /** @type {number} */
    layer_i = 0;
    for (; layer_i < crossfilterable_layers.length; layer_i++) {
      inset = crossfilterable_layers[layer_i];
      bbox = inset.bbox;
      /** @type {number} */
      nx = x - (this.transX * this.scale + inset.left * this.scale);
      /** @type {number} */
      ny = y - (this.transY * this.scale + inset.top * this.scale);
      nx = nx / (inset.width * this.scale) * (bbox[1].x - bbox[0].x) + bbox[0].x;
      ny = ny / (inset.height * this.scale) * (bbox[1].y - bbox[0].y) + bbox[0].y;
      if (nx > bbox[0].x && nx < bbox[1].x && ny > bbox[0].y && ny < bbox[1].y) {
        return jvm.Proj[proj.type + "_inv"](nx, -ny, centralMeridian);
      }
    }
    return false;
  },
  getInsetForPoint : function(x, y) {
    var crossfilterable_layers = jvm.WorldMap.maps[this.params.map].insets;
    var layer_i;
    var bbox;
    /** @type {number} */
    layer_i = 0;
    for (; layer_i < crossfilterable_layers.length; layer_i++) {
      bbox = crossfilterable_layers[layer_i].bbox;
      if (x > bbox[0].x && x < bbox[1].x && y > bbox[0].y && y < bbox[1].y) {
        return crossfilterable_layers[layer_i];
      }
    }
  },
  createSeries : function() {
    var i;
    var key;
    this.series = {
      markers : [],
      regions : []
    };
    for (key in this.params.series) {
      /** @type {number} */
      i = 0;
      for (; i < this.params.series[key].length; i++) {
        this.series[key][i] = new jvm.DataSeries(this.params.series[key][i], this[key]);
      }
    }
  },
  remove : function() {
    this.label.remove();
    this.container.remove();
    jvm.$(window).unbind("resize", this.onResize);
  }
}, jvm.WorldMap.maps = {}, jvm.WorldMap.defaultParams = {
  map : "world_mill_en",
  backgroundColor : "#505050",
  zoomButtons : true,
  zoomOnScroll : true,
  zoomMax : 8,
  zoomMin : 1,
  zoomStep : 1.6,
  regionsSelectable : false,
  markersSelectable : false,
  bindTouchEvents : true,
  regionStyle : {
    initial : {
      fill : "white",
      "fill-opacity" : 1,
      stroke : "none",
      "stroke-width" : 0,
      "stroke-opacity" : 1
    },
    hover : {
      "fill-opacity" : .8
    },
    selected : {
      fill : "yellow"
    },
    selectedHover : {}
  },
  markerStyle : {
    initial : {
      fill : "grey",
      stroke : "#505050",
      "fill-opacity" : 1,
      "stroke-width" : 1,
      "stroke-opacity" : 1,
      r : 5
    },
    hover : {
      stroke : "black",
      "stroke-width" : 2
    },
    selected : {
      fill : "blue"
    },
    selectedHover : {}
  }
}, jvm.WorldMap.apiEvents = {
  onRegionLabelShow : "regionLabelShow",
  onRegionOver : "regionOver",
  onRegionOut : "regionOut",
  onRegionClick : "regionClick",
  onRegionSelected : "regionSelected",
  onMarkerLabelShow : "markerLabelShow",
  onMarkerOver : "markerOver",
  onMarkerOut : "markerOut",
  onMarkerClick : "markerClick",
  onMarkerSelected : "markerSelected",
  onViewportChange : "viewportChange"
};
