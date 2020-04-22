; /* /bitrix/js/main/session.min.js?15874897342511*/
; /* /bitrix/js/main/core/core_window.js?158748973798248*/
; /* /bitrix/js/main/date/main.date.min.js?158748974416360*/
; /* /bitrix/js/main/core/core_date.min.js?158748973724935*/
; /* /bitrix/js/main/utils.min.js?158748973419858*/
; /* /bitrix/js/main/core/core_fx.min.js?15874897379768*/

; /* Start:"a:4:{s:4:"full";s:45:"/bitrix/js/main/session.min.js?15874897342511";s:6:"source";s:26:"/bitrix/js/main/session.js";s:3:"min";s:30:"/bitrix/js/main/session.min.js";s:3:"map";s:30:"/bitrix/js/main/session.map.js";}"*/
function CBXSession(){var e=this;this.mess={};this.timeout=null;this.sessid=null;this.bShowMess=true;this.dateStart=new Date;this.dateInput=new Date;this.dateCheck=new Date;this.activityInterval=0;this.notifier=null;this.Expand=function(t,i,s,n){this.timeout=t;this.sessid=i;this.bShowMess=s;this.key=n;BX.ready(function(){BX.bind(document,"keypress",e.OnUserInput);BX.bind(document.body,"mousemove",e.OnUserInput);BX.bind(document.body,"click",e.OnUserInput);setTimeout(e.CheckSession,(e.timeout-60)*1e3)})};this.OnUserInput=function(){var t=new Date;e.dateInput.setTime(t.valueOf())};this.CheckSession=function(){var t=new Date;if(t.valueOf()-e.dateCheck.valueOf()<3e4)return;e.activityInterval=Math.round((e.dateInput.valueOf()-e.dateStart.valueOf())/1e3);e.dateStart.setTime(e.dateInput.valueOf());var i=e.activityInterval>e.timeout?e.timeout-60:e.activityInterval;var s={method:"GET",dataType:"html",url:"/bitrix/tools/public_session.php?sessid="+e.sessid+"&interval="+i+"&k="+e.key,data:"",onsuccess:function(t){e.CheckResult(t)},lsId:"sess_expand",lsTimeout:60};if(i>0){s.lsForce=true}BX.ajax(s)};this.CheckResult=function(t){if(t=="SESSION_EXPIRED"){if(e.bShowMess){if(!e.notifier){e.notifier=document.body.appendChild(BX.create("DIV",{props:{className:"bx-session-message"},style:{top:"0px",backgroundColor:"#FFEB41",border:"1px solid #EDDA3C",width:"630px",fontFamily:"Arial,Helvetica,sans-serif",fontSize:"13px",fontWeight:"bold",textAlign:"center",color:"black",position:"absolute",zIndex:"10000",padding:"10px"},html:'<a class="bx-session-message-close" style="display:block; width:12px; height:12px; background:url(/bitrix/js/main/core/images/close.gif) center no-repeat; float:right;" href="javascript:bxSession.Close()"></a>'+e.mess.messSessExpired}));var i=BX.GetWindowScrollPos();var s=BX.GetWindowInnerSize();e.notifier.style.left=parseInt(i.scrollLeft+s.innerWidth/2-parseInt(e.notifier.clientWidth)/2)+"px";if(BX.browser.IsIE()){e.notifier.style.top=i.scrollTop+"px";BX.bind(window,"scroll",function(){var t=BX.GetWindowScrollPos();e.notifier.style.top=t.scrollTop+"px"})}else{e.notifier.style.position="fixed"}}e.notifier.style.display=""}}else{var n;if(t=="SESSION_CHANGED")n=e.timeout-60;else n=e.activityInterval<60?60:e.activityInterval>e.timeout?e.timeout-60:e.activityInterval;var o=new Date;e.dateCheck.setTime(o.valueOf());setTimeout(e.CheckSession,n*1e3)}};this.Close=function(){this.notifier.style.display="none"}}var bxSession=new CBXSession;
/* End */
;
; /* Start:"a:4:{s:4:"full";s:51:"/bitrix/js/main/core/core_window.js?158748973798248";s:6:"source";s:35:"/bitrix/js/main/core/core_window.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
;(function(window) {
if (BX.WindowManager) return;

/* windows manager */
BX.WindowManager = {
	_stack: [],
	_runtime_resize: {},
	_delta: 2,
	_delta_start: 1000,
	currently_loaded: null,

	settings_category: 'BX.WindowManager.9.5',

	register: function (w)
	{
		this.currently_loaded = null;
		var div = w.Get();

		div.style.zIndex = w.zIndex = this.GetZIndex();

		w.WM_REG_INDEX = this._stack.length;
		this._stack.push(w);

		if (this._stack.length < 2)
		{
			BX.bind(document, 'keyup', BX.proxy(this.__checkKeyPress, this));
		}
	},

	unregister: function (w)
	{
		if (null == w.WM_REG_INDEX)
			return null;

		var _current;
		if (this._stack.length > 0)
		{
			while ((_current = this.__pop_stack()) != w)
			{
				if (!_current)
				{
					_current = null;
					break;
				}
			}

			if (this._stack.length <= 0)
			{
				this.enableKeyCheck();
			}

			return _current;
		}
		else
		{
			return null;
		}
	},

	__pop_stack: function(clean)
	{
		if (this._stack.length > 0)
		{
			var _current = this._stack.pop();
			_current.WM_REG_INDEX = null;
			BX.onCustomEvent(_current, 'onWindowUnRegister', [clean === true]);

			return _current;
		}
		else
			return null;
	},

	clean: function()
	{
		while (this.__pop_stack(true)){}
		this._stack = null;
		this.disableKeyCheck();
	},

	Get: function()
	{
		if (this.currently_loaded)
			return this.currently_loaded;
		else if (this._stack.length > 0)
			return this._stack[this._stack.length-1];
		else
			return null;
	},

	setStartZIndex: function(value)
	{
		this._delta_start = value;
	},

	restoreStartZIndex: function()
	{
		this._delta_start = 1000;
	},

	GetZIndex: function()
	{
		var _current;
		return (null != (_current = this._stack[this._stack.length-1])
			? parseInt(_current.Get().style.zIndex) + this._delta
			: this._delta_start
		);
	},

	__get_check_url: function(url)
	{
		var pos = url.indexOf('?');
		return pos == -1 ? url : url.substring(0, pos);
	},

	saveWindowSize: function(url, params)
	{
		var check_url = this.__get_check_url(url);
		if (BX.userOptions)
		{
			BX.userOptions.save(this.settings_category, 'size_' + check_url, 'width', params.width);
			BX.userOptions.save(this.settings_category, 'size_' + check_url, 'height', params.height);
		}

		this._runtime_resize[check_url] = params;
	},

	saveWindowOptions: function(wnd_id, opts)
	{
		if (BX.userOptions)
		{
			for (var i in opts)
			{
				if(opts.hasOwnProperty(i))
				{
					BX.userOptions.save(this.settings_category, 'options_' + wnd_id, i, opts[i]);
				}
			}
		}
	},

	getRuntimeWindowSize: function(url)
	{
		return this._runtime_resize[this.__get_check_url(url)];
	},

	disableKeyCheck: function()
	{
		BX.unbind(document, 'keyup', BX.proxy(this.__checkKeyPress, this));
	},

	enableKeyCheck: function()
	{
		BX.bind(document, 'keyup', BX.proxy(this.__checkKeyPress, this));
	},

	__checkKeyPress: function(e)
	{
		if (null == e)
			e = window.event;

		if (e.keyCode == 27)
		{
			var wnd = BX.WindowManager.Get();
			if (wnd && !wnd.unclosable) wnd.Close();
		}
	}
};

BX.garbage(BX.WindowManager.clean, BX.WindowManager);

/* base button class */
BX.CWindowButton = function(params)
{
	if (params.btn)
	{
		this.btn = params.btn;
		this.parentWindow = params.parentWindow;

		if (/save|apply/i.test(this.btn.name))
		{
			BX.bind(this.btn, 'click', BX.delegate(this.disableUntilError, this));
		}
	}
	else
	{
		this.title = params.title; // html value attr
		this.hint = params.hint; // html title attr
		this.id = params.id; // html name and id attrs
		this.name = params.name; // html name or value attrs when id and title 're absent
		this.className = params.className; // className for button input

		this.action = params.action;
		this.onclick = params.onclick;

		// you can override button creation method
		if (params.Button && BX.type.isFunction(params.Button))
			this.Button = params.Button;

		this.btn = null;
	}
};

BX.CWindowButton.prototype.disable = function()
{
	if (this.btn)
		this.parentWindow.showWait(this.btn);
};
BX.CWindowButton.prototype.enable = function(){
	if (this.btn)
		this.parentWindow.closeWait(this.btn);
};

BX.CWindowButton.prototype.emulate = function()
{
	if (this.btn && this.btn.disabled)
		return;

	var act =
		this.action
		? BX.delegate(this.action, this)
		: (
			this.onclick
			? this.onclick
			: (
				this.btn
				? this.btn.getAttribute('onclick')
				: ''
			)
		);

	if (act)
	{
		setTimeout(act, 50);
		if (this.btn && /save|apply/i.test(this.btn.name) && !this.action)
		{
			this.disableUntilError();
		}
	}
};

BX.CWindowButton.prototype.Button = function(parentWindow)
{
	this.parentWindow = parentWindow;

	var btn = {
		props: {
			'type': 'button',
			'name': this.id ? this.id : this.name,
			'value': this.title ? this.title : this.name,
			'id': this.id
		}
	};

	if (this.hint)
		btn.props.title = this.hint;
	if (!!this.className)
		btn.props.className = this.className;

	if (this.action)
	{
		btn.events = {
			'click': BX.delegate(this.action, this)
		};
	}
	else if (this.onclick)
	{
		if (BX.browser.IsIE())
		{
			btn.events = {
				'click': BX.delegate(function() {eval(this.onclick)}, this)
			};
		}
		else
		{
			btn.attrs = {
				'onclick': this.onclick
			};
		}
	}

	this.btn = BX.create('INPUT', btn);

	return this.btn;
};

BX.CWindowButton.prototype.disableUntilError = function() {
	this.disable();
	if (!this.__window_error_handler_set)
	{
		BX.addCustomEvent(this.parentWindow, 'onWindowError', BX.delegate(this.enable, this));
		this.__window_error_handler_set = true;
	}
};

/* base window class */
BX.CWindow = function(div, type)
{
	this.DIV = div || document.createElement('DIV');

	this.SETTINGS = {
		resizable: false,
		min_height: 0,
		min_width: 0,
		top: 0,
		left: 0,
		draggable: false,
		drag_restrict: true,
		resize_restrict: true
	};

	this.ELEMENTS = {
		draggable: [],
		resizer: [],
		close: []
	};

	this.type = type == 'float' ? 'float' : 'dialog';

	BX.adjust(this.DIV, {
		props: {
			className: 'bx-core-window'
		},
		style: {
			'zIndex': 0,
			'position': 'absolute',
			'display': 'none',
			'top': this.SETTINGS.top + 'px',
			'left': this.SETTINGS.left + 'px',
			'height': '100px',
			'width': '100px'
		}
	});

	this.isOpen = false;

	BX.addCustomEvent(this, 'onWindowRegister', BX.delegate(this.onRegister, this));
	BX.addCustomEvent(this, 'onWindowUnRegister', BX.delegate(this.onUnRegister, this));

	this.MOUSEOVER = null;
	BX.bind(this.DIV, 'mouseover', BX.delegate(this.__set_msover, this));
	BX.bind(this.DIV, 'mouseout', BX.delegate(this.__unset_msover, this));

	BX.ready(BX.delegate(function() {
		document.body.appendChild(this.DIV);
	}, this));
};

BX.CWindow.prototype.Get = function () {return this.DIV};
BX.CWindow.prototype.visible = function() {return this.isOpen;};

BX.CWindow.prototype.Show = function(bNotRegister)
{
	this.DIV.style.display = 'block';

	if (!bNotRegister)
	{
		BX.WindowManager.register(this);
		BX.onCustomEvent(this, 'onWindowRegister');
	}
};

BX.CWindow.prototype.Hide = function()
{
	BX.WindowManager.unregister(this);
	this.DIV.style.display = 'none';
};

BX.CWindow.prototype.onRegister = function()
{
	this.isOpen = true;
};

BX.CWindow.prototype.onUnRegister = function(clean)
{
	this.isOpen = false;

	if (clean || (this.PARAMS && this.PARAMS.content_url))
	{
		if (clean) {BX.onCustomEvent(this, 'onWindowClose', [this, true]);}

		if (this.DIV.parentNode)
			this.DIV.parentNode.removeChild(this.DIV);
	}
	else
	{
		this.DIV.style.display = 'none';
	}
};

BX.CWindow.prototype.CloseDialog = // compatibility
BX.CWindow.prototype.Close = function(bImmediately)
{
	BX.onCustomEvent(this, 'onBeforeWindowClose', [this]);
	if (bImmediately !== true)
	{
		if (this.denyClose)
			return false;
	}

	BX.onCustomEvent(this, 'onWindowClose', [this]);

	//this crashes vis editor in ie via onWindowResizeExt event handler
	//if (this.bExpanded) this.__expand();
	// alternative version:
	if (this.bExpanded)
	{
		var pDocElement = BX.GetDocElement();
		BX.unbind(window, 'resize', BX.proxy(this.__expand_onresize, this));
		pDocElement.style.overflow = this.__expand_settings.overflow;
	}

	BX.WindowManager.unregister(this);

	return true;
};

BX.CWindow.prototype.SetResize = function(elem)
{
	elem.style.cursor = 'se-resize';
	BX.bind(elem, 'mousedown', BX.proxy(this.__startResize, this));

	this.ELEMENTS.resizer.push(elem);
	this.SETTINGS.resizable = true;
};

BX.CWindow.prototype.SetExpand = function(elem, event_name)
{
	event_name = event_name || 'click';
	BX.bind(elem, event_name, BX.proxy(this.__expand, this));
};

BX.CWindow.prototype.__expand_onresize = function()
{
	var windowSize = BX.GetWindowInnerSize();
	this.DIV.style.width = windowSize.innerWidth + "px";
	this.DIV.style.height = windowSize.innerHeight + "px";

	BX.onCustomEvent(this, 'onWindowResize');
};

BX.CWindow.prototype.__expand = function()
{
	var pDocElement = BX.GetDocElement();

	if (!this.bExpanded)
	{
		var wndScroll = BX.GetWindowScrollPos(),
			wndSize = BX.GetWindowInnerSize();

		this.__expand_settings = {
			resizable: this.SETTINGS.resizable,
			draggable: this.SETTINGS.draggable,
			width: this.DIV.style.width,
			height: this.DIV.style.height,
			left: this.DIV.style.left,
			top: this.DIV.style.top,
			scrollTop: wndScroll.scrollTop,
			scrollLeft: wndScroll.scrollLeft,
			overflow: BX.style(pDocElement, 'overflow')
		};

		this.SETTINGS.resizable = false;
		this.SETTINGS.draggable = false;

		window.scrollTo(0,0);
		pDocElement.style.overflow = 'hidden';

		this.DIV.style.top = '0px';
		this.DIV.style.left = '0px';

		this.DIV.style.width = wndSize.innerWidth + 'px';
		this.DIV.style.height = wndSize.innerHeight + 'px';

		this.bExpanded = true;

		BX.onCustomEvent(this, 'onWindowExpand');
		BX.onCustomEvent(this, 'onWindowResize');

		BX.bind(window, 'resize', BX.proxy(this.__expand_onresize, this));
	}
	else
	{
		BX.unbind(window, 'resize', BX.proxy(this.__expand_onresize, this));

		this.SETTINGS.resizable = this.__expand_settings.resizable;
		this.SETTINGS.draggable = this.__expand_settings.draggable;

		pDocElement.style.overflow = this.__expand_settings.overflow;

		this.DIV.style.top = this.__expand_settings.top;
		this.DIV.style.left = this.__expand_settings.left;
		this.DIV.style.width = this.__expand_settings.width;
		this.DIV.style.height = this.__expand_settings.height;

		window.scrollTo(this.__expand_settings.scrollLeft, this.__expand_settings.scrollTop);

		this.bExpanded = false;

		BX.onCustomEvent(this, 'onWindowNarrow');
		BX.onCustomEvent(this, 'onWindowResize');

	}
};

BX.CWindow.prototype.Resize = function(x, y)
{
	var new_width = Math.max(x - this.pos.left + this.dx, this.SETTINGS.min_width);
	var new_height = Math.max(y - this.pos.top + this.dy, this.SETTINGS.min_height);

	if (this.SETTINGS.resize_restrict)
	{
		var scrollSize = BX.GetWindowScrollSize();

		if (this.pos.left + new_width > scrollSize.scrollWidth - this.dw)
			new_width = scrollSize.scrollWidth - this.pos.left - this.dw;
	}

	this.DIV.style.width = new_width + 'px';
	this.DIV.style.height = new_height + 'px';

	BX.onCustomEvent(this, 'onWindowResize');
};

BX.CWindow.prototype.__startResize = function(e)
{
	if (!this.SETTINGS.resizable)
		return false;

	if(!e) e = window.event;

	this.wndSize = BX.GetWindowScrollPos();
	this.wndSize.innerWidth = BX.GetWindowInnerSize().innerWidth;

	this.pos = BX.pos(this.DIV);

	this.x = e.clientX + this.wndSize.scrollLeft;
	this.y = e.clientY + this.wndSize.scrollTop;

	this.dx = this.pos.left + this.pos.width - this.x;
	this.dy = this.pos.top + this.pos.height - this.y;
	this.dw = this.pos.width - parseInt(this.DIV.style.width);

	BX.bind(document, "mousemove", BX.proxy(this.__moveResize, this));
	BX.bind(document, "mouseup", BX.proxy(this.__stopResize, this));

	if(document.body.setCapture)
		document.body.setCapture();

	document.onmousedown = BX.False;

	var b = document.body;
	b.ondrag = b.onselectstart = BX.False;
	b.style.MozUserSelect = this.DIV.style.MozUserSelect = 'none';
	b.style.cursor = 'se-resize';

	BX.onCustomEvent(this, 'onWindowResizeStart');

	return true;
};

BX.CWindow.prototype.__moveResize = function(e)
{
	if(!e) e = window.event;

	var windowScroll = BX.GetWindowScrollPos();

	var x = e.clientX + windowScroll.scrollLeft;
	var y = e.clientY + windowScroll.scrollTop;

	if(this.x == x && this.y == y)
		return;

	this.Resize(x, y);

	this.x = x;
	this.y = y;
};

BX.CWindow.prototype.__stopResize = function()
{
	if(document.body.releaseCapture)
		document.body.releaseCapture();

	BX.unbind(document, "mousemove", BX.proxy(this.__moveResize, this));
	BX.unbind(document, "mouseup", BX.proxy(this.__stopResize, this));

	document.onmousedown = null;

	var b = document.body;
	b.ondrag = b.onselectstart = null;
	b.style.MozUserSelect = this.DIV.style.MozUserSelect = '';
	b.style.cursor = '';

	BX.onCustomEvent(this, 'onWindowResizeFinished')
};

BX.CWindow.prototype.SetClose = function(elem)
{
	BX.bind(elem, 'click', BX.proxy(this.Close, this));
	this.ELEMENTS.close.push(elem);
};

BX.CWindow.prototype.SetDraggable = function(elem)
{
	BX.bind(elem, 'mousedown', BX.proxy(this.__startDrag, this));

	elem.style.cursor = 'move';

	this.ELEMENTS.draggable.push(elem);
	this.SETTINGS.draggable = true;
};

BX.CWindow.prototype.Move = function(x, y)
{
	var dxShadow = 1; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

	var left = parseInt(this.DIV.style.left)+x;
	var top = parseInt(this.DIV.style.top)+y;

	if (this.SETTINGS.drag_restrict)
	{
		//Left side
		if (left < 0)
			left = 0;

		//Right side
		var scrollSize = BX.GetWindowScrollSize();
		var floatWidth = this.DIV.offsetWidth;
		var floatHeight = this.DIV.offsetHeight;

		if (left > (scrollSize.scrollWidth - floatWidth - dxShadow))
			left = scrollSize.scrollWidth - floatWidth - dxShadow;

		var scrollHeight = Math.max(
			document.body.scrollHeight, document.documentElement.scrollHeight,
			document.body.offsetHeight, document.documentElement.offsetHeight,
			document.body.clientHeight, document.documentElement.clientHeight,
			scrollSize.scrollHeight
		);

		if (top > (scrollHeight - floatHeight - dxShadow))
			top = scrollHeight - floatHeight - dxShadow;

		//Top side
		if (top < 0)
			top = 0;
	}

	this.DIV.style.left = left+'px';
	this.DIV.style.top = top+'px';

	//this.AdjustShadow(div);
};

BX.CWindow.prototype.__startDrag = function(e)
{
	if (!this.SETTINGS.draggable)
		return false;

	if(!e) e = window.event;

	this.x = e.clientX + document.body.scrollLeft;
	this.y = e.clientY + document.body.scrollTop;

	this.__bWasDragged = false;
	BX.bind(document, "mousemove", BX.proxy(this.__moveDrag, this));
	BX.bind(document, "mouseup", BX.proxy(this.__stopDrag, this));

	if(document.body.setCapture)
		document.body.setCapture();

	document.onmousedown = BX.False;

	var b = document.body;
	b.ondrag = b.onselectstart = BX.False;
	b.style.MozUserSelect = this.DIV.style.MozUserSelect = 'none';
	b.style.cursor = 'move';
	return BX.PreventDefault(e);
};

BX.CWindow.prototype.__moveDrag = function(e)
{
	if(!e) e = window.event;

	var x = e.clientX + document.body.scrollLeft;
	var y = e.clientY + document.body.scrollTop;

	if(this.x == x && this.y == y)
		return;

	this.Move((x - this.x), (y - this.y));
	this.x = x;
	this.y = y;

	if (!this.__bWasDragged)
	{
		BX.onCustomEvent(this, 'onWindowDragStart');
		this.__bWasDragged = true;
		BX.bind(BX.proxy_context, "click", BX.PreventDefault);
	}

	BX.onCustomEvent(this, 'onWindowDrag');
};

BX.CWindow.prototype.__stopDrag = function(e)
{
	if(document.body.releaseCapture)
		document.body.releaseCapture();

	BX.unbind(document, "mousemove", BX.proxy(this.__moveDrag, this));
	BX.unbind(document, "mouseup", BX.proxy(this.__stopDrag, this));

	document.onmousedown = null;

	var b = document.body;
	b.ondrag = b.onselectstart = null;
	b.style.MozUserSelect = this.DIV.style.MozUserSelect = '';
	b.style.cursor = '';

	if (this.__bWasDragged)
	{
		BX.onCustomEvent(this, 'onWindowDragFinished');
		var _proxy_context = BX.proxy_context;
		setTimeout(function(){BX.unbind(_proxy_context, "click", BX.PreventDefault)}, 100);
		this.__bWasDragged = false;
	}
	return BX.PreventDefault(e);
};

BX.CWindow.prototype.DenyClose = function()
{
	this.denyClose = true;
};

BX.CWindow.prototype.AllowClose = function()
{
	this.denyClose = false;
};

BX.CWindow.prototype.ShowError = function(str)
{
	BX.onCustomEvent(this, 'onWindowError', [str]);

	if (this._wait)
		BX.closeWait(this._wait);

	window.alert(str);
};

BX.CWindow.prototype.__set_msover = function() {this.MOUSEOVER = true;};
BX.CWindow.prototype.__unset_msover = function() {this.MOUSEOVER = false;};

/* dialog window class extends window class */
BX.CWindowDialog = function() {
	var a = arguments;
	a[1] = 'dialog';
	BX.CWindowDialog.superclass.constructor.apply(this, a);

	this.DIV.style.top = '10px';
	this.OVERLAY = null;
};
BX.extend(BX.CWindowDialog, BX.CWindow);

BX.CWindowDialog.prototype.__resizeOverlay = function()
{
	var windowSize = BX.GetWindowScrollSize();
	this.OVERLAY.style.width = windowSize.scrollWidth + "px";
};

BX.CWindowDialog.prototype.CreateOverlay = function(zIndex)
{
	if (null == this.OVERLAY)
	{
		var windowSize = BX.GetWindowScrollSize();

		// scrollHeight in BX.GetWindowScrollSize may be incorrect
		var scrollHeight = Math.max(
			document.body.scrollHeight, document.documentElement.scrollHeight,
			document.body.offsetHeight, document.documentElement.offsetHeight,
			document.body.clientHeight, document.documentElement.clientHeight,
			windowSize.scrollHeight
		);

		this.OVERLAY = document.body.appendChild(BX.create("DIV", {
			style: {
				position: 'absolute',
				top: '0px',
				left: '0px',
				zIndex: zIndex || (parseInt(this.DIV.style.zIndex)-2),
				width: windowSize.scrollWidth + "px",
				height: scrollHeight + "px"
			}
		}));
	}

	return this.OVERLAY;
};

BX.CWindowDialog.prototype.Show = function()
{
	BX.CWindowDialog.superclass.Show.apply(this, arguments);

	this.CreateOverlay();

	this.OVERLAY.style.display = 'block';
	this.OVERLAY.style.zIndex = parseInt(this.DIV.style.zIndex)-2;

	BX.unbind(window, 'resize', BX.proxy(this.__resizeOverlay, this));
	BX.bind(window, 'resize', BX.proxy(this.__resizeOverlay, this));
};

BX.CWindowDialog.prototype.onUnRegister = function(clean)
{
	BX.CWindowDialog.superclass.onUnRegister.apply(this, arguments);

	if (this.clean)
	{
		if (this.OVERLAY.parentNode)
			this.OVERLAY.parentNode.removeChild(this.OVERLAY);
	}
	else
	{
		this.OVERLAY.style.display = 'none';
	}

	BX.unbind(window, 'resize', BX.proxy(this.__resizeOverlay, this));
};

/* standard bitrix dialog extends BX.CWindowDialog */
/*
	arParams = {
		(
			title: 'dialog title',
			head: 'head block html',
			content: 'dialog content',
			icon: 'head icon classname or filename',

			resize_id: 'some id to save resize information'// useless if resizable = false
		)
		or
		(
			content_url: url to content load
				loaded content scripts can use BX.WindowManager.Get() to get access to the current window object
		)

		height: window_height_in_pixels,
		width: window_width_in_pixels,

		draggable: true|false,
		resizable: true|false,

		min_height: min_window_height_in_pixels, // useless if resizable = false
		min_width: min_window_width_in_pixels, // useless if resizable = false

		buttons: [
			'html_code',
			BX.CDialog.btnSave, BX.CDialog.btnCancel, BX.CDialog.btnClose
		]
	}
*/
BX.CDialog = function(arParams)
{
	BX.CDialog.superclass.constructor.apply(this);

	this._sender = 'core_window_cdialog';

	this.PARAMS = arParams || {};

	for (var i in this.defaultParams)
	{
		if (typeof this.PARAMS[i] == 'undefined')
			this.PARAMS[i] = this.defaultParams[i];
	}

	this.PARAMS.width = (!isNaN(parseInt(this.PARAMS.width)))
		? this.PARAMS.width
		: this.defaultParams['width'];
	this.PARAMS.height = (!isNaN(parseInt(this.PARAMS.height)))
		? this.PARAMS.height
		: this.defaultParams['height'];

	if (this.PARAMS.resize_id || this.PARAMS.content_url)
	{
		var arSize = BX.WindowManager.getRuntimeWindowSize(this.PARAMS.resize_id || this.PARAMS.content_url);
		if (arSize)
		{
			this.PARAMS.width = arSize.width;
			this.PARAMS.height = arSize.height;
		}
	}

	BX.addClass(this.DIV, 'bx-core-adm-dialog');
	this.DIV.id = 'bx-admin-prefix';

	this.PARTS = {};

	this.DIV.style.height = null;
	this.DIV.style.width = null;

	this.PARTS.TITLEBAR = this.DIV.appendChild(BX.create('DIV', {props: {
			className: 'bx-core-adm-dialog-head'
		}
	}));

	this.PARTS.TITLE_CONTAINER = this.PARTS.TITLEBAR.appendChild(BX.create('SPAN', {
		props: {className: 'bx-core-adm-dialog-head-inner'},
		text: this.PARAMS.title
	}));

	this.PARTS.TITLEBAR_ICONS = this.PARTS.TITLEBAR.appendChild(BX.create('DIV', {
		props: {
			className: 'bx-core-adm-dialog-head-icons'
		},
		children: (this.PARAMS.resizable ? [
			BX.create('SPAN', {props: {className: 'bx-core-adm-icon-expand', title: BX.message('JS_CORE_WINDOW_EXPAND')}}),
			BX.create('SPAN', {props: {className: 'bx-core-adm-icon-close', title: BX.message('JS_CORE_WINDOW_CLOSE')}})
		] : [
			BX.create('SPAN', {props: {className: 'bx-core-adm-icon-close', title: BX.message('JS_CORE_WINDOW_CLOSE')}})
		])
	}));


	this.PARTS.CONTENT = this.DIV.appendChild(BX.create('DIV', {
		props: {className: 'bx-core-adm-dialog-content-wrap adm-workarea'}
	}));

	this.PARTS.CONTENT_DATA = this.PARTS.CONTENT.appendChild(BX.create('DIV', {
		props: {className: 'bx-core-adm-dialog-content'},
		style: {
			height: this.PARAMS.height + 'px',
			width: this.PARAMS.width + 'px'
		}
	}));

	this.PARTS.HEAD = this.PARTS.CONTENT_DATA.appendChild(BX.create('DIV', {
		props: {
			className: 'bx-core-adm-dialog-head-block' + (this.PARAMS.icon ? ' ' + this.PARAMS.icon : '')
		}
	}));

	this.SetHead(this.PARAMS.head);
	this.SetContent(this.PARAMS.content);
	this.SetTitle(this.PARAMS.title);
	this.SetClose(this.PARTS.TITLEBAR_ICONS.lastChild);

	if (this.PARAMS.resizable)
	{
		this.SetExpand(this.PARTS.TITLEBAR_ICONS.firstChild);
		this.SetExpand(this.PARTS.TITLEBAR, 'dblclick');

		BX.addCustomEvent(this, 'onWindowExpand', BX.proxy(this.__onexpand, this));
		BX.addCustomEvent(this, 'onWindowNarrow', BX.proxy(this.__onexpand, this));
	}

	this.PARTS.FOOT = this.PARTS.BUTTONS_CONTAINER = this.PARTS.CONTENT.appendChild(BX.create('DIV', {
			props: {
				className: 'bx-core-adm-dialog-buttons'
			},
			//events: {
			//	'click': BX.delegateEvent({property:{type: /button|submit/}}, BX.delegate(function() {this.showWait(BX.proxy_context)}, this))
			//},
			children: this.ShowButtons()
		}
	));

	if (this.PARAMS.draggable)
		this.SetDraggable(this.PARTS.TITLEBAR);

	if (this.PARAMS.resizable)
	{
		this.PARTS.RESIZER = this.DIV.appendChild(BX.create('DIV', {
			props: {className: 'bx-core-resizer'}
		}));

		this.SetResize(this.PARTS.RESIZER);

		this.SETTINGS.min_width = this.PARAMS.min_width;
		this.SETTINGS.min_height = this.PARAMS.min_height;
	}

	this.auth_callback = BX.delegate(function(){
		this.PARAMS.content = '';
		this.hideNotify();
		this.Show();
	}, this)
};
BX.extend(BX.CDialog, BX.CWindowDialog);

BX.CDialog.prototype.defaultParams = {
	width: 700,
	height: 400,
	min_width: 500,
	min_height: 300,

	resizable: true,
	draggable: true,

	title: '',
	icon: ''
};

BX.CDialog.prototype.showWait = function(el)
{
	if (BX.type.isElementNode(el) && (el.type == 'button' || el.type == 'submit'))
	{
		BX.defer(function(){el.disabled = true})();

		var bSave = (BX.hasClass(el, 'adm-btn-save') || BX.hasClass(el, 'adm-btn-save')),
			pos = BX.pos(el, true);

		el.bxwaiter = this.PARTS.FOOT.appendChild(BX.create('DIV', {
			props: {className: 'adm-btn-load-img' + (bSave ? '-green' : '')},
			style: {
				top: parseInt((pos.bottom + pos.top)/2 - 10) + 'px',
				left: parseInt((pos.right + pos.left)/2 - 10) + 'px'
			}
		}));

		BX.addClass(el, 'adm-btn-load');

		this.lastWaitElement = el;

		return el.bxwaiter;
	}
	return null;
};

BX.CDialog.prototype.closeWait = function(el)
{
	el = el || this.lastWaitElement;

	if (BX.type.isElementNode(el))
	{
		if (el.bxwaiter)
		{
			if(el.bxwaiter.parentNode)
			{
				el.bxwaiter.parentNode.removeChild(el.bxwaiter);
			}

			el.bxwaiter = null;
		}

		el.disabled = false;
		BX.removeClass(el, 'adm-btn-load');

		if (this.lastWaitElement == el)
			this.lastWaitElement = null;
	}
};

BX.CDialog.prototype.Authorize = function(arAuthResult)
{
	this.bSkipReplaceContent = true;
	this.ShowError(BX.message('JSADM_AUTH_REQ'));

	BX.onCustomEvent(this, 'onWindowError', []);

	BX.closeWait();

	(new BX.CAuthDialog({
		content_url: this.PARAMS.content_url,
		auth_result: arAuthResult,
		callback: BX.delegate(function(){
			if (this.auth_callback)
				this.auth_callback()
		}, this)
	})).Show();
};

BX.CDialog.prototype.ShowError = function(str)
{
	BX.onCustomEvent(this, 'onWindowError', [str]);

	this.closeWait();

	if (this._wait)
		BX.closeWait(this._wait);

	this.Notify(str, true);
};


BX.CDialog.prototype.__expandGetSize = function()
{
	var pDocElement = BX.GetDocElement();
	pDocElement.style.overflow = 'hidden';

	var wndSize = BX.GetWindowInnerSize();

	pDocElement.scrollTop = 0;

	this.DIV.style.top = '-' + this.dxShadow + 'px';
	this.DIV.style.left = '-' + this.dxShadow + 'px';

	return {
		width: (wndSize.innerWidth - parseInt(BX.style(this.PARTS.CONTENT, 'padding-right')) - parseInt(BX.style(this.PARTS.CONTENT, 'padding-left'))) + this.dxShadow,
		height: (wndSize.innerHeight - this.PARTS.TITLEBAR.offsetHeight - this.PARTS.FOOT.offsetHeight - parseInt(BX.style(this.PARTS.CONTENT, 'padding-top')) - parseInt(BX.style(this.PARTS.CONTENT, 'padding-bottom'))) + this.dxShadow
	};
};

BX.CDialog.prototype.__expand = function()
{
	var pDocElement = BX.GetDocElement();
	this.dxShadow = 2;

	if (!this.bExpanded)
	{
		var wndScroll = BX.GetWindowScrollPos();

		this.__expand_settings = {
			resizable: this.SETTINGS.resizable,
			draggable: this.SETTINGS.draggable,
			width: this.PARTS.CONTENT_DATA.style.width,
			height: this.PARTS.CONTENT_DATA.style.height,
			left: this.DIV.style.left,
			top: this.DIV.style.top,
			scrollTop: wndScroll.scrollTop,
			scrollLeft: wndScroll.scrollLeft,
			overflow: BX.style(pDocElement, 'overflow')
		};

		this.SETTINGS.resizable = false;
		this.SETTINGS.draggable = false;

		var pos = this.__expandGetSize();

		this.PARTS.CONTENT_DATA.style.width = pos.width + 'px';
		this.PARTS.CONTENT_DATA.style.height = pos.height + 'px';

		window.scrollTo(0,0);
		pDocElement.style.overflow = 'hidden';

		this.bExpanded = true;

		BX.onCustomEvent(this, 'onWindowExpand');
		BX.onCustomEvent(this, 'onWindowResize');
		BX.onCustomEvent(this, 'onWindowResizeExt', [{'width': pos.width, 'height': pos.height}]);

		BX.bind(window, 'resize', BX.proxy(this.__expand_onresize, this));
	}
	else
	{
		BX.unbind(window, 'resize', BX.proxy(this.__expand_onresize, this));

		this.SETTINGS.resizable = this.__expand_settings.resizable;
		this.SETTINGS.draggable = this.__expand_settings.draggable;

		pDocElement.style.overflow = this.__expand_settings.overflow;

		this.DIV.style.top = this.__expand_settings.top;
		this.DIV.style.left = this.__expand_settings.left;
		this.PARTS.CONTENT_DATA.style.width = this.__expand_settings.width;
		this.PARTS.CONTENT_DATA.style.height = this.__expand_settings.height;
		window.scrollTo(this.__expand_settings.scrollLeft, this.__expand_settings.scrollTop);
		this.bExpanded = false;

		BX.onCustomEvent(this, 'onWindowNarrow');
		BX.onCustomEvent(this, 'onWindowResize');
		BX.onCustomEvent(this, 'onWindowResizeExt', [{'width': parseInt(this.__expand_settings.width), 'height': parseInt(this.__expand_settings.height)}]);
	}
};

BX.CDialog.prototype.__expand_onresize = function()
{
	var pos = this.__expandGetSize();

	this.PARTS.CONTENT_DATA.style.width = pos.width + 'px';
	this.PARTS.CONTENT_DATA.style.height = pos.height + 'px';

	BX.onCustomEvent(this, 'onWindowResize');
	BX.onCustomEvent(this, 'onWindowResizeExt', [pos]);
};

BX.CDialog.prototype.__onexpand = function()
{
	var ob = this.PARTS.TITLEBAR_ICONS.firstChild;
	ob.className = BX.toggle(ob.className, ['bx-core-adm-icon-expand', 'bx-core-adm-icon-narrow']);
	ob.title = BX.toggle(ob.title, [BX.message('JS_CORE_WINDOW_EXPAND'), BX.message('JS_CORE_WINDOW_NARROW')]);

	if (this.PARTS.RESIZER)
	{
		this.PARTS.RESIZER.style.display = this.bExpanded ? 'none' : 'block';
	}
};


BX.CDialog.prototype.__startResize = function(e)
{
	if (!this.SETTINGS.resizable)
		return false;

	if(!e) e = window.event;

	this.wndSize = BX.GetWindowScrollPos();
	this.wndSize.innerWidth = BX.GetWindowInnerSize().innerWidth;

	this.pos = BX.pos(this.PARTS.CONTENT_DATA);

	this.x = e.clientX + this.wndSize.scrollLeft;
	this.y = e.clientY + this.wndSize.scrollTop;

	this.dx = this.pos.left + this.pos.width - this.x;
	this.dy = this.pos.top + this.pos.height - this.y;


	// TODO: suspicious
	this.dw = this.pos.width - parseInt(this.PARTS.CONTENT_DATA.style.width) + parseInt(BX.style(this.PARTS.CONTENT, 'padding-right'));

	BX.bind(document, "mousemove", BX.proxy(this.__moveResize, this));
	BX.bind(document, "mouseup", BX.proxy(this.__stopResize, this));

	if(document.body.setCapture)
		document.body.setCapture();

	document.onmousedown = BX.False;

	var b = document.body;
	b.ondrag = b.onselectstart = BX.False;
	b.style.MozUserSelect = this.DIV.style.MozUserSelect = 'none';
	b.style.cursor = 'se-resize';

	BX.onCustomEvent(this, 'onWindowResizeStart');

	return true;
};

BX.CDialog.prototype.Resize = function(x, y)
{
	var new_width = Math.max(x - this.pos.left + this.dx, this.SETTINGS.min_width);
	var new_height = Math.max(y - this.pos.top + this.dy, this.SETTINGS.min_height);

	if (this.SETTINGS.resize_restrict)
	{
		var scrollSize = BX.GetWindowScrollSize();

		if (this.pos.left + new_width > scrollSize.scrollWidth - this.dw)
			new_width = scrollSize.scrollWidth - this.pos.left - this.dw;
	}

	this.PARTS.CONTENT_DATA.style.width = new_width + 'px';
	this.PARTS.CONTENT_DATA.style.height = new_height + 'px';

	BX.onCustomEvent(this, 'onWindowResize');
	BX.onCustomEvent(this, 'onWindowResizeExt', [{'height': new_height, 'width': new_width}]);
};

BX.CDialog.prototype.SetSize = function(obSize)
{
	this.PARTS.CONTENT_DATA.style.width = obSize.width + 'px';
	this.PARTS.CONTENT_DATA.style.height = obSize.height + 'px';

	BX.onCustomEvent(this, 'onWindowResize');
	BX.onCustomEvent(this, 'onWindowResizeExt', [obSize]);
};

BX.CDialog.prototype.GetParameters = function(form_name)
{
	var form = this.GetForm();

	if(!form)
		return "";

	var i, s = "";
	var n = form.elements.length;

	var delim = '';
	for(i=0; i<n; i++)
	{
		if (s != '') delim = '&';
		var el = form.elements[i];
		if (el.disabled)
			continue;

		switch(el.type.toLowerCase())
		{
			case 'text':
			case 'textarea':
			case 'password':
			case 'hidden':
				if (null == form_name && el.name.substr(el.name.length-4) == '_alt' && form.elements[el.name.substr(0, el.name.length-4)])
					break;
				s += delim + el.name + '=' + BX.util.urlencode(el.value);
				break;
			case 'radio':
				if(el.checked)
					s += delim + el.name + '=' + BX.util.urlencode(el.value);
				break;
			case 'checkbox':
				s += delim + el.name + '=' + BX.util.urlencode(el.checked ? 'Y':'N');
				break;
			case 'select-one':
				var val = "";
				if (null == form_name && form.elements[el.name + '_alt'] && el.selectedIndex == 0)
					val = form.elements[el.name+'_alt'].value;
				else
					val = el.value;
				s += delim + el.name + '=' + BX.util.urlencode(val);
				break;
			case 'select-multiple':
				var j, bAdded = false;
				var l = el.options.length;
				for (j=0; j<l; j++)
				{
					if (el.options[j].selected)
					{
						s += delim + el.name + '=' + BX.util.urlencode(el.options[j].value);
						bAdded = true;
					}
				}
				if (!bAdded)
					s += delim + el.name + '=';
				break;
			default:
				break;
		}
	}

	return s;
};

BX.CDialog.prototype.PostParameters = function(params)
{
	var url = this.PARAMS.content_url;

	if (null == params)
		params = "";

	params += (params == "" ? "" : "&") + "bxsender=" + this._sender;

	var index = url.indexOf('?');
	if (index == -1)
		url += '?' + params;
	else
		url = url.substring(0, index) + '?' + params + "&" + url.substring(index+1);

	BX.showWait();

	this.auth_callback = BX.delegate(function(){
		this.hideNotify();
		this.PostParameters(params);
	}, this);

	BX.ajax.Setup({skipAuthCheck:true},true);
	BX.ajax.post(url, this.GetParameters(), BX.delegate(function(result) {
		BX.closeWait();
		if (!this.bSkipReplaceContent)
		{
			this.ClearButtons(); // buttons are appended during form reload, so we should clear footer
			this.SetContent(result);
			this.Show(true);
		}

		this.bSkipReplaceContent = false;
	}, this));
};

BX.CDialog.prototype.Submit = function(params, url)
{
	var FORM = this.GetForm();
	if (FORM)
	{
		FORM.onsubmit = null;

		FORM.method = 'POST';
		if (!FORM.action || url)
		{
			url = url || this.PARAMS.content_url;
			if (null != params)
			{
				var index = url.indexOf('?');
				if (index == -1)
					url += '?' + params;
				else
					url = url.substring(0, index) + '?' + params + "&" + url.substring(index+1);
			}

			FORM.action = url;
		}

		if (!FORM._bxsender)
		{
			FORM._bxsender = FORM.appendChild(BX.create('INPUT', {
				attrs: {
					type: 'hidden',
					name: 'bxsender',
					value: this._sender
				}
			}));
		}

		this._wait = BX.showWait();

		this.auth_callback = BX.delegate(function(){
			this.hideNotify();
			this.Submit(params);
		}, this);

		BX.ajax.submit(FORM, BX.delegate(function(){this.closeWait()}, this));
	}
	else
	{
		window.alert('no form registered!');
	}
};

BX.CDialog.prototype.GetForm = function()
{
	if (null == this.__form)
	{
		var forms = this.PARTS.CONTENT_DATA.getElementsByTagName('FORM');
		this.__form = forms[0] ? forms[0] : null;
	}

	return this.__form;
};

BX.CDialog.prototype.GetRealForm = function()
{
	if (null == this.__rform)
	{
		var forms = this.PARTS.CONTENT_DATA.getElementsByTagName('FORM');
		this.__rform = forms[1] ? forms[1] : (forms[0] ? forms[0] : null);
	}

	return this.__rform;
};

BX.CDialog.prototype._checkButton = function(btn)
{
	var arCustomButtons = ['btnSave', 'btnCancel', 'btnClose'];

	for (var i = 0; i < arCustomButtons.length; i++)
	{
		if (this[arCustomButtons[i]] && (btn == this[arCustomButtons[i]]))
			return arCustomButtons[i];
	}

	return false;
};

BX.CDialog.prototype.ShowButtons = function()
{
	var result = [];
	if (this.PARAMS.buttons)
	{
		if (this.PARAMS.buttons.title) this.PARAMS.buttons = [this.PARAMS.buttons];

		for (var i=0, len=this.PARAMS.buttons.length; i<len; i++)
		{
			if (BX.type.isNotEmptyString(this.PARAMS.buttons[i]))
			{
				result.push(this.PARAMS.buttons[i]);
			}
			else if (BX.type.isElementNode(this.PARAMS.buttons[i]))
			{
				result.push(this.PARAMS.buttons[i]);
			}
			else if (this.PARAMS.buttons[i])
			{
				//if (!(this.PARAMS.buttons[i] instanceof BX.CWindowButton))
				if (!BX.is_subclass_of(this.PARAMS.buttons[i], BX.CWindowButton))
				{
					var b = this._checkButton(this.PARAMS.buttons[i]); // hack to set links to real CWindowButton object in btnSave etc;
					this.PARAMS.buttons[i] = new BX.CWindowButton(this.PARAMS.buttons[i]);
					if (b) this[b] = this.PARAMS.buttons[i];
				}

				result.push(this.PARAMS.buttons[i].Button(this));
			}
		}
	}

	return result;
};

BX.CDialog.prototype.setAutosave = function () {
	if (!this.bSetAutosaveDelay)
	{
		this.bSetAutosaveDelay = true;
		setTimeout(BX.proxy(this.setAutosave, this), 10);
	}
};

BX.CDialog.prototype.SetTitle = function(title)
{
	this.PARAMS.title = title;
	BX.cleanNode(this.PARTS.TITLE_CONTAINER).appendChild(document.createTextNode(this.PARAMS.title));
};

BX.CDialog.prototype.SetHead = function(head)
{
	this.PARAMS.head = BX.util.trim(head);
	this.PARTS.HEAD.innerHTML = this.PARAMS.head || "&nbsp;";
	this.PARTS.HEAD.style.display = this.PARAMS.head ? 'block' : 'none';
	this.adjustSize();
};

BX.CDialog.prototype.Notify = function(note, bError, html)
{
	if (!this.PARTS.NOTIFY)
	{
		this.PARTS.NOTIFY = this.DIV.insertBefore(BX.create('DIV', {
			props: {className: 'adm-warning-block'},
			children: [
				BX.create('SPAN', {
					props: {className: 'adm-warning-text'}
				}),
				BX.create('SPAN', {
					props: {className: 'adm-warning-icon'}
				}),
				BX.create('SPAN', {
					props: {className: 'adm-warning-close'},
					events: {click: BX.proxy(this.hideNotify, this)}
				})
			]
		}), this.DIV.firstChild);
	}

	if (bError)
		BX.addClass(this.PARTS.NOTIFY, 'adm-warning-block-red');
	else
		BX.removeClass(this.PARTS.NOTIFY, 'adm-warning-block-red');

	if(html !== true)
	{
		note = BX.util.htmlspecialchars(note);
	}

	this.PARTS.NOTIFY.firstChild.innerHTML = note || '&nbsp;';
	this.PARTS.NOTIFY.firstChild.style.width = (this.PARAMS.width-50) + 'px';
	BX.removeClass(this.PARTS.NOTIFY, 'adm-warning-animate');
};

BX.CDialog.prototype.hideNotify = function()
{
	BX.addClass(this.PARTS.NOTIFY, 'adm-warning-animate');
};

BX.CDialog.prototype.__adjustHeadToIcon = function()
{
	if (!this.PARTS.HEAD.offsetHeight)
	{
		setTimeout(BX.delegate(this.__adjustHeadToIcon, this), 50);
	}
	else
	{
		if (this.icon_image && this.icon_image.height && this.icon_image.height > this.PARTS.HEAD.offsetHeight - 5)
		{
			this.PARTS.HEAD.style.height = this.icon_image.height + 5 + 'px';
			this.adjustSize();
		}

		this.icon_image.onload = null;
		this.icon_image = null;
	}
};

BX.CDialog.prototype.SetIcon = function(icon_class)
{
	if (this.PARAMS.icon != icon_class)
	{
		if (this.PARAMS.icon)
			BX.removeClass(this.PARTS.HEAD, this.PARAMS.icon);

		this.PARAMS.icon = icon_class;

		if (this.PARAMS.icon)
		{
			BX.addClass(this.PARTS.HEAD, this.PARAMS.icon);

			var icon_file = (BX.style(this.PARTS.HEAD, 'background-image') || BX.style(this.PARTS.HEAD, 'backgroundImage'));
			if (BX.type.isNotEmptyString(icon_file) && icon_file != 'none')
			{
				var match = icon_file.match(new RegExp('url\\s*\\(\\s*(\'|"|)(.+?)(\\1)\\s*\\)'));
				if(match)
				{
					icon_file = match[2];
					if (BX.type.isNotEmptyString(icon_file))
					{
						this.icon_image = new Image();
						this.icon_image.onload = BX.delegate(this.__adjustHeadToIcon, this);
						this.icon_image.src = icon_file;
					}
				}
			}
		}
	}
	this.adjustSize();
};

BX.CDialog.prototype.SetIconFile = function(icon_file)
{
	this.icon_image = new Image();
	this.icon_image.onload = BX.delegate(this.__adjustHeadToIcon, this);
	this.icon_image.src = icon_file;

	BX.adjust(this.PARTS.HEAD, {style: {backgroundImage: 'url(' + icon_file + ')', backgroundPosition: 'right 9px'/*'99% center'*/}});
	this.adjustSize();
};

/*
BUTTON: {
	title: 'title',
	'action': function executed in window object context
}
BX.CDialog.btnSave || BX.CDialog.btnCancel - standard buttons
*/

BX.CDialog.prototype.SetButtons = function(a)
{
	if (BX.type.isString(a))
	{
		if (a.length > 0)
		{
			this.PARTS.BUTTONS_CONTAINER.innerHTML += a;

			var btns = this.PARTS.BUTTONS_CONTAINER.getElementsByTagName('INPUT');
			if (btns.length > 0)
			{
				this.PARAMS.buttons = [];
				for (var i = 0; i < btns.length; i++)
				{
					this.PARAMS.buttons.push(new BX.CWindowButton({btn: btns[i], parentWindow: this}));
				}
			}
		}
	}
	else
	{
		this.PARAMS.buttons = a;
		BX.adjust(this.PARTS.BUTTONS_CONTAINER, {
			children: this.ShowButtons()
		});
	}
	this.adjustSize();
};

BX.CDialog.prototype.ClearButtons = function()
{
	BX.cleanNode(this.PARTS.BUTTONS_CONTAINER);
	this.adjustSize();
};

BX.CDialog.prototype.SetContent = function(html)
{
	this.__form = null;

	if (BX.type.isElementNode(html))
	{
		if (html.parentNode)
			html.parentNode.removeChild(html);
	}
	else if (BX.type.isString(html))
	{
		html = BX.create('DIV', {html: html});
	}

	this.PARAMS.content = html;
	BX.cleanNode(this.PARTS.CONTENT_DATA);

	BX.adjust(this.PARTS.CONTENT_DATA, {
		children: [
			this.PARTS.HEAD,
			BX.create('DIV', {
				props: {
					className: 'bx-core-adm-dialog-content-wrap-inner'
				},
				children: [this.PARAMS.content]
			})
		]
	});

	if (this.PARAMS.content_url && this.GetForm())
	{
		this.__form.submitbtn = this.__form.appendChild(BX.create('INPUT', {props:{type:'submit'},style:{display:'none'}}));
		this.__form.onsubmit = BX.delegate(this.__submit, this);
	}
};

BX.CDialog.prototype.__submit = function(e)
{
	for (var i=0,len=this.PARAMS.buttons.length; i<len; i++)
	{
		if (
			this.PARAMS.buttons[i]
			&& (
				this.PARAMS.buttons[i].name && /save|apply/i.test(this.PARAMS.buttons[i].name)
				||
				this.PARAMS.buttons[i].btn && this.PARAMS.buttons[i].btn.name && /save|apply/i.test(this.PARAMS.buttons[i].btn.name)
			)
		)
		{
			this.PARAMS.buttons[i].emulate();
			break;
		}
	}

	return BX.PreventDefault(e);
};

BX.CDialog.prototype.SwapContent = function(cont)
{
	cont = BX(cont);

	BX.cleanNode(this.PARTS.CONTENT_DATA);
	cont.parentNode.removeChild(cont);
	this.PARTS.CONTENT_DATA.appendChild(cont);
	cont.style.display = 'block';
	this.SetContent(cont.innerHTML);
};

// this method deprecated
BX.CDialog.prototype.adjustSize = function()
{
};

// this method deprecated
BX.CDialog.prototype.__adjustSize = function()
{
};

BX.CDialog.prototype.adjustSizeEx = function()
{
	BX.defer(this.__adjustSizeEx, this)();
};

BX.CDialog.prototype.__adjustSizeEx = function()
{
	var ob = this.PARTS.CONTENT_DATA.firstChild,
		new_height = 0,
		marginTop,
		marginBottom;

	while (ob)
	{
		if (BX.type.isElementNode(ob))
		{
			marginTop = parseInt(BX.style(ob, 'margin-top'), 10);
			if (isNaN(marginTop))
				marginTop = 0;
			marginBottom = parseInt(BX.style(ob, 'margin-bottom'), 10);
			if (isNaN(marginBottom))
				marginBottom = 0;
			new_height += ob.offsetHeight + marginTop + marginBottom;
		}
		ob = BX.nextSibling(ob);
	}

	if (new_height)
		this.PARTS.CONTENT_DATA.style.height = new_height + 'px';
};


BX.CDialog.prototype.__onResizeFinished = function()
{
	BX.WindowManager.saveWindowSize(
		this.PARAMS.resize_id || this.PARAMS.content_url, {height: parseInt(this.PARTS.CONTENT_DATA.style.height), width: parseInt(this.PARTS.CONTENT_DATA.style.width)}
	);
};

BX.CDialog.prototype.Show = function(bNotRegister)
{
	if ((!this.PARAMS.content) && this.PARAMS.content_url && BX.ajax && !bNotRegister)
	{
		var wait = BX.showWait();

		BX.WindowManager.currently_loaded = this;

		var zIndex = (this.PARAMS.zIndex ? this.PARAMS.zIndex : parseInt(BX.style(wait, 'z-index'))-1);
		this.CreateOverlay(zIndex);
		this.OVERLAY.style.display = 'block';
		this.OVERLAY.className = 'bx-core-dialog-overlay';

		var post_data = '', method = 'GET';
		if (this.PARAMS.content_post)
		{
			post_data = this.PARAMS.content_post;
			method = 'POST';
		}

		var url = this.PARAMS.content_url
			+ (this.PARAMS.content_url.indexOf('?')<0?'?':'&')+'bxsender=' + this._sender;

		this.auth_callback = BX.delegate(function(){
			this.PARAMS.content = '';
			this.hideNotify();
			this.Show();
		}, this);

		BX.ajax({
			method: method,
			dataType: 'html',
			url: url,
			data: post_data,
			skipAuthCheck: true,
			onsuccess: BX.delegate(function(data) {
				BX.closeWait(null, wait);

				this.SetContent(data || '&nbsp;');
				this.Show();
			}, this)
		});
	}
	else
	{
		BX.WindowManager.currently_loaded = null;
		BX.CDialog.superclass.Show.apply(this, arguments);

		this.adjustPos();

		this.OVERLAY.className = 'bx-core-dialog-overlay';

		this.__adjustSize();

		BX.removeCustomEvent(this, 'onWindowResize', BX.proxy(this.__adjustSize, this));
		BX.addCustomEvent(this, 'onWindowResize', BX.proxy(this.__adjustSize, this));

		if (this.PARAMS.resizable && (this.PARAMS.content_url || this.PARAMS.resize_id))
		{
			BX.removeCustomEvent(this, 'onWindowResizeFinished', BX.proxy(this.__onResizeFinished, this));
			BX.addCustomEvent(this, 'onWindowResizeFinished', BX.proxy(this.__onResizeFinished, this));
		}
	}
};

BX.CDialog.prototype.GetInnerPos = function()
{
	return {'width': parseInt(this.PARTS.CONTENT_DATA.style.width), 'height': parseInt(this.PARTS.CONTENT_DATA.style.height)};
};

BX.CDialog.prototype.adjustPos = function()
{
	if (!this.bExpanded)
	{
		var currentWindow = window;
		if (top.BX.SidePanel && top.BX.SidePanel.Instance && top.BX.SidePanel.Instance.getTopSlider())
		{
			currentWindow = top.BX.SidePanel.Instance.getTopSlider().getWindow();
		}
		var windowSize = currentWindow.BX.GetWindowInnerSize();
		var windowScroll = currentWindow.BX.GetWindowScrollPos();

		var style = {
			left: parseInt(windowScroll.scrollLeft + windowSize.innerWidth / 2 - parseInt(this.DIV.offsetWidth) / 2) + 'px',
			top: Math.max(parseInt(windowScroll.scrollTop + windowSize.innerHeight / 2 - parseInt(this.DIV.offsetHeight) / 2), 0) + 'px'
		};
		if (this.PARAMS.zIndex)
		{
			style["z-index"] = this.PARAMS.zIndex;
		}

		BX.adjust(this.DIV, {
			style: style
		});
	}
};

BX.CDialog.prototype.GetContent = function () {return this.PARTS.CONTENT_DATA};

BX.CDialog.prototype.btnSave = BX.CDialog.btnSave = {
	title: BX.message('JS_CORE_WINDOW_SAVE'),
	id: 'savebtn',
	name: 'savebtn',
	className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
	action: function () {
		this.disableUntilError();
		this.parentWindow.PostParameters();
	}
};

BX.CDialog.prototype.btnCancel = BX.CDialog.btnCancel = {
	title: BX.message('JS_CORE_WINDOW_CANCEL'),
	id: 'cancel',
	name: 'cancel',
	action: function () {
		this.parentWindow.Close();
	}
};

BX.CDialog.prototype.btnClose = BX.CDialog.btnClose = {
	title: BX.message('JS_CORE_WINDOW_CLOSE'),
	id: 'close',
	name: 'close',
	action: function () {
		this.parentWindow.Close();
	}
};

/* special child for admin forms loaded into public page */
BX.CAdminDialog = function(arParams)
{
	BX.CAdminDialog.superclass.constructor.apply(this, arguments);

	this._sender = 'core_window_cadmindialog';

	BX.addClass(this.DIV, 'bx-core-adm-admin-dialog');

	this.PARTS.CONTENT.insertBefore(this.PARTS.HEAD, this.PARTS.CONTENT.firstChild);
	this.PARTS.HEAD.className = 'bx-core-adm-dialog-tabs';
};
BX.extend(BX.CAdminDialog, BX.CDialog);

BX.CAdminDialog.prototype.SetHead = function()
{
	BX.CAdminDialog.superclass.SetHead.apply(this, arguments);

	if (this.PARTS.HEAD.firstChild && BX.type.isElementNode(this.PARTS.HEAD.firstChild))
	{
		var ob = this.PARTS.HEAD.firstChild, new_width = 0, marginLeft = 0, marginRight = 0;

		while (ob)
		{
			if (BX.type.isElementNode(ob))
			{
				marginLeft = parseInt(BX.style(ob, 'margin-left'), 10);
				if (isNaN(marginLeft))
					marginLeft = 0;
				marginRight = parseInt(BX.style(ob, 'margin-right'), 10);
				if (isNaN(marginRight))
					marginRight = 0;
				new_width += ob.offsetWidth + marginLeft + marginRight;
			}
			ob = BX.nextSibling(ob);
		}

		this.SETTINGS.min_width = Math.max(new_width, this.SETTINGS.min_width) - 2;
		if (this.PARAMS.width < this.SETTINGS.min_width)
		{
			BX.adjust(this.PARTS.CONTENT_DATA, {
				style: {
					width: this.SETTINGS.min_width + 'px'
				}
			});
		}
	}
};

BX.CAdminDialog.prototype.SetContent = function(html)
{
	this.__form = null;

	if (BX.type.isElementNode(html))
	{
		if (html.parentNode)
			html.parentNode.removeChild(html);
	}

	this.PARAMS.content = html;
	BX.cleanNode(this.PARTS.CONTENT_DATA);

	BX.adjust(this.PARTS.CONTENT_DATA, {
		children: [
			this.PARAMS.content || '&nbsp;'
		]
	});

	if (this.PARAMS.content_url && this.GetForm())
	{
		this.__form.appendChild(BX.create('INPUT', {props:{type:'submit'},style:{display:'none'}}));
		this.__form.onsubmit = BX.delegate(this.__submit, this);
	}
};

BX.CAdminDialog.prototype.__expandGetSize = function()
{
	var res = BX.CAdminDialog.superclass.__expandGetSize.apply(this, arguments);

	res.width -= parseInt(BX.style(this.PARTS.CONTENT_DATA, 'padding-right')) + parseInt(BX.style(this.PARTS.CONTENT_DATA, 'padding-left'));
	res.height -= parseInt(BX.style(this.PARTS.CONTENT_DATA, 'padding-top')) + parseInt(BX.style(this.PARTS.CONTENT_DATA, 'padding-bottom'));

	res.height -= this.PARTS.HEAD.offsetHeight;

	return res;
};

BX.CAdminDialog.prototype.Submit = function()
{
	var FORM = this.GetForm();
	if (FORM && !FORM['bxpublic'] && !/bxpublic=/.test(FORM.action))
	{
		FORM.appendChild(BX.create('INPUT', {
			props: {
				type: 'hidden',
				name: 'bxpublic',
				value: 'Y'
			}
		}));
	}

	return BX.CAdminDialog.superclass.Submit.apply(this, arguments);
};

BX.CAdminDialog.prototype.btnSave = BX.CAdminDialog.btnSave = {
	title: BX.message('JS_CORE_WINDOW_SAVE'),
	id: 'savebtn',
	name: 'savebtn',
	className: 'adm-btn-save',
	action: function () {
		this.disableUntilError();
		this.parentWindow.Submit();
	}
};

BX.CAdminDialog.btnCancel = BX.CAdminDialog.superclass.btnCancel;
BX.CAdminDialog.btnClose = BX.CAdminDialog.superclass.btnClose;

BX.CDebugDialog = function(arParams)
{
	BX.CDebugDialog.superclass.constructor.apply(this, arguments);
};
BX.extend(BX.CDebugDialog, BX.CDialog);

BX.CDebugDialog.prototype.ShowDetails = function(div_id)
{
	var div = BX(div_id);
	if (div)
	{
		if (this.div_detail_current)
			this.div_detail_current.style.display = 'none';

		div.style.display = 'block';
		this.div_detail_current = div;
	}
};

BX.CDebugDialog.prototype.SetContent = function(html)
{
	if (!html)
		return;

	var arHtml = html.split('#DIVIDER#');
	if (arHtml.length > 1)
	{
		this.PARAMS.content = arHtml[1];

		this.PARTS.CONTENT_DATA.style.overflow = 'hidden';

		BX.CDebugDialog.superclass.SetContent.apply(this, [arHtml[1]]);

		this.PARTS.CONTENT_INNER = this.PARTS.CONTENT_DATA.firstChild.nextSibling;
		this.PARTS.CONTENT_TOP = this.PARTS.CONTENT_DATA.insertBefore(BX.create('DIV', {
			props: {
				className: 'bx-debug-content-top'
			},
			html: arHtml[0]
		}), this.PARTS.CONTENT_INNER);
		this.PARTS.CONTENT_INNER.style.overflow = 'auto';
	}
	else
	{
		BX.CDebugDialog.superclass.SetContent.apply(this, arguments);
	}
};

BX.CDebugDialog.prototype.__adjustSize = function()
{
	BX.CDebugDialog.superclass.__adjustSize.apply(this, arguments);

	if (this.PARTS.CONTENT_TOP)
	{
		var new_height = this.PARTS.CONTENT_DATA.offsetHeight - this.PARTS.HEAD.offsetHeight - this.PARTS.CONTENT_TOP.offsetHeight - 38;

		if (new_height > 0)
		{
			this.PARTS.CONTENT_INNER.style.height = new_height + 'px';
		}
	}
};


/* class for dialog window with editors */

BX.CEditorDialog = function(arParams)
{
	BX.CEditorDialog.superclass.constructor.apply(this, arguments);

	BX.removeClass(this.PARTS.CONTENT, 'bx-core-adm-dialog-content-wrap');
	BX.removeClass(this.PARTS.CONTENT_DATA, 'bx-core-adm-dialog-content');

	BX.removeClass(this.PARTS.CONTENT_DATA.lastChild, 'bx-core-adm-dialog-content-wrap-inner');
	BX.removeClass(this.PARTS.BUTTONS_CONTAINER, 'bx-core-adm-dialog-buttons');

	BX.addClass(this.PARTS.CONTENT, 'bx-core-editor-dialog-content-wrap');
	BX.addClass(this.PARTS.CONTENT_DATA, 'bx-core-editor-dialog-content');
	BX.addClass(this.PARTS.BUTTONS_CONTAINER, 'bx-core-editor-dialog-buttons');
};
BX.extend(BX.CEditorDialog, BX.CDialog);

BX.CEditorDialog.prototype.SetContent  = function()
{
	BX.CEditorDialog.superclass.SetContent.apply(this, arguments);

	BX.removeClass(this.PARTS.CONTENT_DATA.lastChild, 'bx-core-adm-dialog-content-wrap-inner');
};

/* class for wizards in admin section */
BX.CWizardDialog = function(arParams)
{
	BX.CWizardDialog.superclass.constructor.apply(this, arguments);

	BX.removeClass(this.PARTS.CONTENT, 'bx-core-adm-dialog-content-wrap');
	BX.removeClass(this.PARTS.CONTENT_DATA, 'bx-core-adm-dialog-content');
	BX.removeClass(this.PARTS.CONTENT_DATA.lastChild, 'bx-core-adm-dialog-content-wrap-inner');
	BX.removeClass(this.PARTS.BUTTONS_CONTAINER, 'bx-core-adm-dialog-buttons');

	BX.addClass(this.PARTS.CONTENT, 'bx-core-wizard-dialog-content-wrap');
};

BX.extend(BX.CWizardDialog, BX.CDialog);

/* class for auth dialog */
BX.CAuthDialog = function(arParams)
{
	arParams.resizable = false;
	arParams.width = 350;
	arParams.height = 200;

	arParams.buttons = [this.btnSave];

	BX.CAuthDialog.superclass.constructor.apply(this, arguments);
	this._sender = 'core_window_cauthdialog';

	BX.addClass(this.DIV, 'bx-core-auth-dialog');

	BX.AUTHAGENT = this;
};
BX.extend(BX.CAuthDialog, BX.CDialog);

BX.CAuthDialog.prototype.btnSave = BX.CAuthDialog.btnSave = {
	title: BX.message('JS_CORE_WINDOW_AUTH'),
	id: 'savebtn',
	name: 'savebtn',
	className: 'adm-btn-save',
	action: function () {
		this.disableUntilError();
		this.parentWindow.Submit('', this.parentWindow.PARAMS.content_url);
	}
};

BX.CAuthDialog.prototype.SetError = function(error)
{
	BX.closeWait();

	if (!!error)
		this.ShowError(error.MESSAGE || error);
};

BX.CAuthDialog.prototype.setAuthResult = function(result)
{
	BX.closeWait();

	if (result === false)
	{
		this.Close();
		if (this.PARAMS.callback)
			this.PARAMS.callback();
	}
	else
	{
		this.SetError(result);
	}
};

/* MENU CLASSES */

BX.CWindowFloat = function(node)
{
	BX.CWindowFloat.superclass.constructor.apply(this, [node, 'float']);

	this.SETTINGS.resizable = false;
};
BX.extend(BX.CWindowFloat, BX.CWindow);

BX.CWindowFloat.prototype.adjustPos = function()
{
	if (this.PARAMS.parent)
		this.adjustToNode();
	else if (this.PARAMS.x && this.PARAMS.y)
		this.adjustToPos([this.PARAMS.x, this.PARAMS.y]);
};

BX.CWindowFloat.prototype.adjustToPos = function(pos)
{
	this.DIV.style.left = parseInt(pos[0]) + 'px';
	this.DIV.style.top = parseInt(pos[1]) + 'px';
};

BX.CWindowFloat.prototype.adjustToNodeGetPos = function()
{
	return BX.pos(this.PARAMS.parent);
};

BX.CWindowFloat.prototype.adjustToNode = function(el)
{
	el = el || this.PARAMS.parent;

	this.PARAMS.parent = BX(el);

	if (this.PARAMS.parent)
	{
		var pos = this.adjustToNodeGetPos();

		this.DIV.style.top = pos.top + 'px';//(pos.top - 26) + 'px';
		this.DIV.style.left = pos.left + 'px';

		this.PARAMS.parent.OPENER = this;
	}
};

BX.CWindowFloat.prototype.Show = function()
{
	this.adjustToPos([-1000, -1000]);
	BX.CWindowFloat.superclass.Show.apply(this, arguments);
	this.adjustPos();
};

/* menu opener class */
/*
{
	DOMNode DIV,
	BX.CMenu or Array MENU,
	TYPE = 'hover' | 'click',
	TIMEOUT: 1000
	ATTACH_MODE: 'top' | 'right'
	ACTIVE_CLASS: className for opener element when menu is opened
}
*/
BX.COpener = function(arParams)
{
	this.PARAMS = arParams || {};

	this.MENU = arParams.MENU || [];

	this.DIV = arParams.DIV;
	this.ATTACH = arParams.ATTACH || arParams.DIV;
	this.ATTACH_MODE = arParams.ATTACH_MODE || 'bottom';

	this.ACTIVE_CLASS = arParams.ACTIVE_CLASS || '';
	this.PUBLIC_FRAME = arParams.PUBLIC_FRAME || 0;
	this.LEVEL = arParams.LEVEL || 0;

	this.CLOSE_ON_CLICK = typeof arParams.CLOSE_ON_CLICK != 'undefined' ? !!arParams.CLOSE_ON_CLICK : true;
	this.ADJUST_ON_CLICK = typeof arParams.ADJUST_ON_CLICK != 'undefined' ? !!arParams.ADJUST_ON_CLICK : true;

	this.TYPE = this.PARAMS.TYPE == 'hover' ? 'hover' : 'click';

	this._openTimeout = null;

	if (this.PARAMS.TYPE == 'hover' && arParams.TIMEOUT !== 0)
		this.TIMEOUT = arParams.TIMEOUT || 1000;
	else
		this.TIMEOUT = 0;

	if (!!this.PARAMS.MENU_URL)
	{
		this.bMenuLoaded = false;
		this.bMenuLoading = false;

		this.MENU = [{
			TEXT: BX.message('JS_CORE_LOADING'),
			CLOSE_ON_CLICK: false
		}];

		if (this.PARAMS.MENU_PRELOAD)
		{
			BX.defer(this.Load, this)();
		}
	}

	BX.ready(BX.defer(this.Init, this));
};

BX.COpener.prototype.Init = function()
{
	this.DIV = BX(this.DIV);

	switch (this.TYPE)
	{
		case 'hover':
			BX.bind(this.DIV, 'mouseover', BX.proxy(this.Open, this));
			BX.bind(this.DIV, 'click', BX.proxy(this.Toggle, this));
		break;

		case 'click':
			BX.bind(this.DIV, 'click', BX.proxy(this.Toggle, this));
		break;
	}

	//BX.bind(window, 'scroll', BX.delegate(this.__close_immediately, this));

	this.bMenuInit = false;
};

BX.COpener.prototype.Load = function()
{
	if (this.PARAMS.MENU_URL && !this.bMenuLoaded)
	{
		if (!this.bMenuLoading)
		{
			var url = this.PARAMS.MENU_URL;
			if (url.indexOf('sessid=') <= 0)
				url += (url.indexOf('?') > 0 ? '&' : '?') + 'sessid=' + BX.bitrix_sessid();

			this.bMenuLoading = true;
			BX.ajax.loadJSON(url, BX.proxy(this.SetMenu, this), BX.proxy(this.LoadFailed, this));
		}
	}
};

BX.COpener.prototype.SetMenu = function(menu)
{
	this.bMenuLoaded = true;
	this.bMenuLoading = false;
	if (this.bMenuInit)
	{
		this.MENU.setItems(menu);
	}
	else
	{
		this.MENU = menu;
	}
};

BX.COpener.prototype.LoadFailed = function(type, error)
{
	this.bMenuLoading = false;
	this.SetMenu([{
		TEXT: BX.message('JS_CORE_NO_DATA'),
		CLOSE_ON_CLICK: true
	}]);
	BX.debug(arguments);
};

BX.COpener.prototype.checkAdminMenu = function()
{
	if (document.documentElement.id == 'bx-admin-prefix')
		return true;

	return !!BX.findParent(this.DIV, {property: {id: 'bx-admin-prefix'}});
};

BX.COpener.prototype.Toggle = function(e)
{
	this.__clear_timeout();

	if (!this.bMenuInit || !this.MENU.visible())
	{
		var t = this.TIMEOUT;
		this.TIMEOUT = 0;
		this.Open(e);
		this.TIMEOUT = t;
	}
	else
	{
		this.MENU.Close();
	}

	return !!(e||window.event) && BX.PreventDefault(e);
};

BX.COpener.prototype.GetMenu = function()
{
	if (!this.bMenuInit)
	{
		if (BX.type.isArray(this.MENU))
		{
			this.MENU = new BX.CMenu({
				ITEMS: this.MENU,
				ATTACH_MODE: this.ATTACH_MODE,
				SET_ID: this.checkAdminMenu() ? 'bx-admin-prefix' : '',
				CLOSE_ON_CLICK: !!this.CLOSE_ON_CLICK,
				ADJUST_ON_CLICK: !!this.ADJUST_ON_CLICK,
				PUBLIC_FRAME: !!this.PUBLIC_FRAME,
				LEVEL: this.LEVEL,
				parent: BX(this.DIV),
				parent_attach: BX(this.ATTACH)
			});

			if (this.LEVEL > 0)
			{
				BX.bind(this.MENU.DIV, 'mouseover', BX.proxy(this._on_menu_hover, this));
				BX.bind(this.MENU.DIV, 'mouseout', BX.proxy(this._on_menu_hout, this));
			}
		}

		BX.addCustomEvent(this.MENU, 'onMenuOpen', BX.proxy(this.handler_onopen, this));
		BX.addCustomEvent(this.MENU, 'onMenuClose', BX.proxy(this.handler_onclose, this));

		BX.addCustomEvent('onMenuItemHover', BX.proxy(this.handler_onover, this));

		this.bMenuInit = true;
	}

	return this.MENU;
};

BX.COpener.prototype.Open = function()
{
	this.GetMenu();

	this.bOpen = true;

	this.__clear_timeout();

	if (this.TIMEOUT > 0)
	{
		BX.bind(this.DIV, 'mouseout', BX.proxy(this.__clear_timeout, this));
		this._openTimeout = setTimeout(BX.proxy(this.__open, this), this.TIMEOUT);
	}
	else
	{
		this.__open();
	}

	if (!!this.PARAMS.MENU_URL && !this.bMenuLoaded)
	{
		this._loadTimeout = setTimeout(BX.proxy(this.Load, this), parseInt(this.TIMEOUT/2));
	}

	return true;
};

BX.COpener.prototype.__clear_timeout = function()
{
	if (!!this._openTimeout)
		clearTimeout(this._openTimeout);
	if (!!this._loadTimeout)
		clearTimeout(this._loadTimeout);

	BX.unbind(this.DIV, 'mouseout', BX.proxy(this.__clear_timeout, this));
};

BX.COpener.prototype._on_menu_hover = function()
{
	this.bMenuHover = true;

	this.__clear_timeout();

	if (this.ACTIVE_CLASS)
		BX.addClass(this.DIV, this.ACTIVE_CLASS);

};

BX.COpener.prototype._on_menu_hout = function()
{
	this.bMenuHover = false;
};

BX.COpener.prototype.handler_onover = function(level, opener)
{
	if (this.bMenuHover)
		return;

	if (opener != this && level == this.LEVEL-1 && this.ACTIVE_CLASS)
	{
		BX.removeClass(this.DIV, this.ACTIVE_CLASS);
	}

	if (this.bMenuInit && level <= this.LEVEL-1 && this.MENU.visible())
	{
		if (opener != this)
		{
			this.__clear_timeout();
			this._openTimeout = setTimeout(BX.proxy(this.Close, this), this.TIMEOUT);
		}
	}
};

BX.COpener.prototype.handler_onopen = function()
{
	this.bOpen = true;

	if (this.ACTIVE_CLASS)
		BX.addClass(this.DIV, this.ACTIVE_CLASS);

	BX.defer(function() {
		BX.onCustomEvent(this, 'onOpenerMenuOpen');
	}, this)();
};

BX.COpener.prototype.handler_onclose = function()
{
	this.bOpen = false;
	BX.onCustomEvent(this, 'onOpenerMenuClose');

	if (this.ACTIVE_CLASS)
		BX.removeClass(this.DIV, this.ACTIVE_CLASS);
};

BX.COpener.prototype.Close = function()
{
	if (!this.bMenuInit)
		return;

	if (!!this._openTimeout)
		clearTimeout(this._openTimeout);

	this.bOpen = false;

	this.__close();
};

BX.COpener.prototype.__open = function()
{
	this.__clear_timeout();

	if (this.bMenuInit && this.bOpen && !this.MENU.visible())
		this.MENU.Show();
};

BX.COpener.prototype.__close = function()
{
	if (this.bMenuInit && !this.bOpen && this.MENU.visible())
		this.MENU.Hide();
};

BX.COpener.prototype.__close_immediately = function() {
	this.bOpen = false; this.__close();
};

BX.COpener.prototype.isMenuVisible = function() {
	return null != this.MENU.visible && this.MENU.visible()
};

/* common menu class */

BX.CMenu = function(arParams)
{
	BX.CMenu.superclass.constructor.apply(this);

	this.DIV.style.width = 'auto';//this.DIV.firstChild.offsetWidth + 'px';
	this.DIV.style.height = 'auto';//this.DIV.firstChild.offsetHeight + 'px';

	this.PARAMS = arParams || {};
	this.PARTS = {};

	this.PARAMS.ATTACH_MODE = this.PARAMS.ATTACH_MODE || 'bottom';
	this.PARAMS.CLOSE_ON_CLICK = typeof this.PARAMS.CLOSE_ON_CLICK == 'undefined' ? true : this.PARAMS.CLOSE_ON_CLICK;
	this.PARAMS.ADJUST_ON_CLICK = typeof this.PARAMS.ADJUST_ON_CLICK == 'undefined' ? true : this.PARAMS.ADJUST_ON_CLICK;
	this.PARAMS.PUBLIC_FRAME = typeof this.PARAMS.PUBLIC_FRAME == 'undefined' ? false : this.PARAMS.PUBLIC_FRAME;
	this.PARAMS.LEVEL = this.PARAMS.LEVEL || 0;

	this.DIV.className = 'bx-core-popup-menu bx-core-popup-menu-' + this.PARAMS.ATTACH_MODE + ' bx-core-popup-menu-level' + this.PARAMS.LEVEL + (typeof this.PARAMS.ADDITIONAL_CLASS != 'undefined' ? ' ' + this.PARAMS.ADDITIONAL_CLASS : '');
	if (!!this.PARAMS.SET_ID)
		this.DIV.id = this.PARAMS.SET_ID;

	if (this.PARAMS.LEVEL == 0)
	{
		this.ARROW = this.DIV.appendChild(BX.create('SPAN', {props: {className: 'bx-core-popup-menu-angle'}, style: {left:'15px'}}));
	}

	if (!!this.PARAMS.CLASS_NAME)
		this.DIV.className += ' ' + this.PARAMS.CLASS_NAME;

	BX.bind(this.DIV, 'click', BX.eventCancelBubble);

	this.ITEMS = [];

	this.setItems(this.PARAMS.ITEMS);

	BX.addCustomEvent('onMenuOpen', BX.proxy(this._onMenuOpen, this));
	BX.addCustomEvent('onMenuItemSelected', BX.proxy(this.Hide, this));
};
BX.extend(BX.CMenu, BX.CWindowFloat);

BX.CMenu.broadcastCloseEvent = function()
{
	BX.onCustomEvent("onMenuItemSelected");
};

BX.CMenu._toggleChecked = function()
{
	BX.toggleClass(this, 'bx-core-popup-menu-item-checked');
};

BX.CMenu._itemDblClick = function()
{
	window.location.href = this.href;
};

BX.CMenu.prototype.toggleArrow = function(v)
{
	if (!!this.ARROW)
	{
		if (typeof v == 'undefined')
		{
			v = this.ARROW.style.visibility == 'hidden';
		}

		this.ARROW.style.visibility = !!v ? 'visible' : 'hidden';
	}
};

BX.CMenu.prototype.visible = function()
{
	return this.DIV.style.display !== 'none';
};

BX.CMenu.prototype._onMenuOpen = function(menu, menu_level)
{
	if (this.visible())
	{
		if (menu_level == this.PARAMS.LEVEL && menu != this)
		{
			this.Hide();
		}
	}
};

BX.CMenu.prototype.onUnRegister = function()
{
	if (!this.visible())
		return;

	this.Hide();
};

BX.CMenu.prototype.setItems = function(items)
{
	this.PARAMS.ITEMS = items;

	BX.cleanNode(this.DIV);

	if (!!this.ARROW)
		this.DIV.appendChild(this.ARROW);

	if (this.PARAMS.ITEMS)
	{
		this.PARAMS.ITEMS = BX.util.array_values(this.PARAMS.ITEMS);

		var bIcons = false;
		var cnt = 0;
		for (var i = 0, len = this.PARAMS.ITEMS.length; i < len; i++)
		{
			if ((i == 0 || i == len-1) && this.PARAMS.ITEMS[i].SEPARATOR)
				continue;

			cnt++;

			if (!bIcons)
				bIcons = !!this.PARAMS.ITEMS[i].GLOBAL_ICON;

			this.addItem(this.PARAMS.ITEMS[i], i);
		}

		// Occam turning in his grave
		if (cnt === 1)
			BX.addClass(this.DIV, 'bx-core-popup-menu-single-item');
		else
			BX.removeClass(this.DIV, 'bx-core-popup-menu-single-item');

		if (!bIcons)
			BX.addClass(this.DIV, 'bx-core-popup-menu-no-icons');
		else
			BX.removeClass(this.DIV, 'bx-core-popup-menu-no-icons');

	}
};

BX.CMenu.prototype.addItem = function(item)
{
	this.ITEMS.push(item);

	if (item.SEPARATOR)
	{
		item.NODE = BX.create(
			'DIV', {props: {className: 'bx-core-popup-menu-separator'}}
		);
	}
	else
	{
		var bHasMenu = (!!item.MENU
			&& (
				(BX.type.isArray(item.MENU) && item.MENU.length > 0)
				|| item.MENU instanceof BX.CMenu
			) || !!item.MENU_URL
		);

		if (item.DISABLED)
		{
			item.CLOSE_ON_CLICK = false;
			item.LINK = null;
			item.ONCLICK = null;
			item.ACTION = null;
		}

		var attrs = {};
		if (!!item.LINK || BX.browser.IsIE() && !BX.browser.IsDoctype())
		{
			attrs.href = item.LINK || 'javascript:void(0)';
		}
		if (this.PARAMS.PUBLIC_FRAME)
		{
			attrs.target = '_top';
		}

		item.NODE = BX.create(!!item.LINK || BX.browser.IsIE() && !BX.browser.IsDoctype() ? 'A' : 'SPAN', {
			props: {
				className: 'bx-core-popup-menu-item'
					+ (bHasMenu ? ' bx-core-popup-menu-item-opener' : '')
					+ (!!item.DEFAULT ? ' bx-core-popup-menu-item-default' : '')
					+ (!!item.DISABLED ? ' bx-core-popup-menu-item-disabled' : '')
					+ (!!item.CHECKED ? ' bx-core-popup-menu-item-checked' : ''),
					title: !!BX.message['MENU_ENABLE_TOOLTIP'] || !!item.SHOW_TITLE ? item.TITLE || '' : '',
				BXMENULEVEL: this.PARAMS.LEVEL
			},
			attrs: attrs,
			events: {
				mouseover: function()
				{
					BX.onCustomEvent('onMenuItemHover', [this.BXMENULEVEL, this.OPENER])
				}
			},
			html: '<span class="bx-core-popup-menu-item-icon' + (item.GLOBAL_ICON ? ' '+item.GLOBAL_ICON : '') + '"></span><span class="bx-core-popup-menu-item-text">'+(item.HTML||(item.TEXT? BX.util.htmlspecialchars(item.TEXT) : ''))+'</span>'
		});

		if (bHasMenu && !item.DISABLED)
		{
			item.NODE.OPENER = new BX.COpener({
				DIV: item.NODE,
				ACTIVE_CLASS: 'bx-core-popup-menu-item-opened',
				TYPE: 'hover',
				MENU: item.MENU,
				MENU_URL: item.MENU_URL,
				MENU_PRELOAD: !!item.MENU_PRELOAD,
				LEVEL: this.PARAMS.LEVEL + 1,
				ATTACH_MODE:'right',
				TIMEOUT: 500
			});
		}
		else if (this.PARAMS.CLOSE_ON_CLICK && (typeof item.CLOSE_ON_CLICK == 'undefined' || !!item.CLOSE_ON_CLICK))
		{
			BX.bind(item.NODE, 'click', BX.CMenu.broadcastCloseEvent);
		}
		else if (this.PARAMS.ADJUST_ON_CLICK && (typeof item.ADJUST_ON_CLICK == 'undefined' || !!item.ADJUST_ON_CLICK))
		{
			BX.bind(item.NODE, 'click', BX.defer(this.adjustPos, this));
		}

		if (bHasMenu && !!item.LINK)
		{
			BX.bind(item.NODE, 'dblclick', BX.CMenu._itemDblClick);
		}

		if (typeof item.CHECKED != 'undefined')
		{
			BX.bind(item.NODE, 'click', BX.CMenu._toggleChecked);
		}

		item.ONCLICK = item.ACTION || item.ONCLICK;
		if (!!item.ONCLICK)
		{
			if (BX.type.isString(item.ONCLICK))
			{
				item.ONCLICK = new Function("event", item.ONCLICK);
			}

			BX.bind(item.NODE, 'click', item.ONCLICK);
		}
	}

	this.DIV.appendChild(item.NODE);
};

BX.CMenu.prototype._documentClickBind = function()
{
	this._documentClickUnBind();
	BX.bind(document, 'click', BX.proxy(this._documentClick, this));
};

BX.CMenu.prototype._documentClickUnBind = function()
{
	BX.unbind(document, 'click', BX.proxy(this._documentClick, this));
};

BX.CMenu.prototype._documentClick = function(e)
{
	e = e||window.event;
	if(!!e && !(BX.getEventButton(e) & BX.MSLEFT))
		return;

	this.Close();
};

BX.CMenu.prototype.Show = function()
{
	BX.onCustomEvent(this, 'onMenuOpen', [this, this.PARAMS.LEVEL]);
	BX.CMenu.superclass.Show.apply(this, []);

	this.bCloseEventFired = false;

	BX.addCustomEvent(this.PARAMS.parent_attach, 'onChangeNodePosition', BX.proxy(this.adjustToNode, this));

	(BX.defer(this._documentClickBind, this))();
};

BX.CMenu.prototype.Close = // we shouldn't 'Close' window - only hide
BX.CMenu.prototype.Hide = function()
{
	if (!this.visible())
		return;

	BX.removeCustomEvent(this.PARAMS.parent_attach, 'onChangeNodePosition', BX.proxy(this.adjustToNode, this));

	this._documentClickUnBind();

	if (!this.bCloseEventFired)
	{
		BX.onCustomEvent(this, 'onMenuClose', [this, this.PARAMS.LEVEL]);
		this.bCloseEventFired = true;
	}
	BX.CMenu.superclass.Hide.apply(this, arguments);


//	this.DIV.onclick = null;
	//this.PARAMS.parent.onclick = null;
};

BX.CMenu.prototype.__adjustMenuToNode = function()
{
	var pos = BX.pos(this.PARAMS.parent_attach),
		bFixed = !!BX.findParent(this.PARAMS.parent_attach, BX.is_fixed);

	if (bFixed)
		this.DIV.style.position = 'fixed';
	else
		this.DIV.style.position = 'absolute';

	if (!pos.top)
	{
		this.DIV.style.top = '-1000px';
		this.DIV.style.left = '-1000px';
	}

	if (this.bTimeoutSet) return;

	var floatWidth = this.DIV.offsetWidth, floatHeight = this.DIV.offsetHeight;
	if (!floatWidth)
	{
		setTimeout(BX.delegate(function(){
			this.bTimeoutSet = false; this.__adjustMenuToNode();
		}, this), 100);

		this.bTimeoutSet = true;
		return;
	}

	var menu_pos = {},
		wndSize = BX.GetWindowSize();

/*
	if (BX.browser.IsIE() && !BX.browser.IsDoctype())
	{
		pos.top -= 4; pos.bottom -= 4;
		pos.left -= 2; pos.right -= 2;
	}
*/

	switch (this.PARAMS.ATTACH_MODE)
	{
		case 'bottom':
			menu_pos.top = pos.bottom + 9;
			menu_pos.left = pos.left;

			var arrowPos = 0;
			if (!!this.ARROW)
			{
				if (pos.width > floatWidth)
					arrowPos = parseInt(floatWidth/2 - 7);
				else
					arrowPos = parseInt(Math.min(floatWidth, pos.width)/2 - 7);

				if (arrowPos < 7)
				{
					menu_pos.left -= 15;
					arrowPos += 15;
				}
			}

			if (menu_pos.left > wndSize.scrollWidth - floatWidth - 10)
			{
				var orig_menu_pos = menu_pos.left;
				menu_pos.left = wndSize.scrollWidth - floatWidth - 10;

				if (!!this.ARROW)
					arrowPos += orig_menu_pos - menu_pos.left;
			}

			if (bFixed)
			{
				menu_pos.left -= wndSize.scrollLeft;
			}

			if (!!this.ARROW)
				this.ARROW.style.left = arrowPos + 'px';
		break;
		case 'right':
			menu_pos.top = pos.top-1;
			menu_pos.left = pos.right;

			if (menu_pos.left > wndSize.scrollWidth - floatWidth - 10)
			{
				menu_pos.left = pos.left - floatWidth - 1;
			}
		break;
	}

	if (bFixed)
	{
		menu_pos.top -= wndSize.scrollTop;
	}

	if (!!this.ARROW)
		this.ARROW.className = 'bx-core-popup-menu-angle';

	if((menu_pos.top + floatHeight > wndSize.scrollTop + wndSize.innerHeight)
		|| (menu_pos.top + floatHeight > wndSize.scrollHeight))
	{
		var new_top = this.PARAMS.ATTACH_MODE == 'bottom'
			? pos.top - floatHeight - 9
			: pos.bottom - floatHeight + 1;

		if((new_top > wndSize.scrollTop)
			|| (menu_pos.top + floatHeight > wndSize.scrollHeight))
		{
			if ((menu_pos.top + floatHeight > wndSize.scrollHeight))
			{
				menu_pos.top = Math.max(0, wndSize.scrollHeight-floatHeight);
				this.toggleArrow(false);
			}
			else
			{
				menu_pos.top = new_top;

				if (!!this.ARROW)
					this.ARROW.className = 'bx-core-popup-menu-angle-bottom';
			}
		}
	}

	if (menu_pos.top + menu_pos.left == 0)
	{
		this.Hide();
	}
	else
	{
		this.DIV.style.top = menu_pos.top + 'px';
		this.DIV.style.left = menu_pos.left + 'px';
	}
};

BX.CMenu.prototype.adjustToNode = function(el)
{
	this.PARAMS.parent_attach = BX(el) || this.PARAMS.parent_attach || this.PARAMS.parent;
	this.__adjustMenuToNode();
};


/* components toolbar class */

BX.CMenuOpener = function(arParams)
{
	BX.CMenuOpener.superclass.constructor.apply(this);

	this.PARAMS = arParams || {};
	this.setParent(this.PARAMS.parent);
	this.PARTS = {};

	this.SETTINGS.drag_restrict = true;

	this.defaultAction = null;

	this.timeout = 500;

	this.DIV.className = 'bx-component-opener';
	this.DIV.ondblclick = BX.PreventDefault;

	if (this.PARAMS.component_id)
	{
		this.PARAMS.transform = !!this.PARAMS.transform;
	}

	this.OPENERS = [];

	this.DIV.appendChild(BX.create('SPAN', {
		props: {className: 'bx-context-toolbar' + (this.PARAMS.transform ? ' bx-context-toolbar-vertical-mode' : '')}
	}));

	//set internal structure and register draggable element
	this.PARTS.INNER = this.DIV.firstChild.appendChild(BX.create('SPAN', {
		props: {className: 'bx-context-toolbar-inner'},
		html: '<span class="bx-context-toolbar-drag-icon"></span><span class="bx-context-toolbar-vertical-line"></span><br>'
	}));

	this.EXTRA_BUTTONS = {};

	var btnCount = 0;
	for (var i = 0, len = this.PARAMS.menu.length; i < len; i++)
	{
		var item = this.addItem(this.PARAMS.menu[i]);
		if (null != item)
		{
			btnCount++;
			this.PARTS.INNER.appendChild(item);
			this.PARTS.INNER.appendChild(BX.create('BR'));
		}
	}
	var bHasButtons = btnCount > 0;

	//menu items will be attached here

	this.PARTS.ICONS = this.PARTS.INNER.appendChild(BX.create('SPAN', {
		props: {className: 'bx-context-toolbar-icons'}
	}));

	if (this.PARAMS.component_id)
	{
		this.PARAMS.pin = !!this.PARAMS.pin;

		if (bHasButtons)
			this.PARTS.ICONS.appendChild(BX.create('SPAN', {props: {className: 'bx-context-toolbar-separator'}}));

		this.PARTS.ICON_PIN = this.PARTS.ICONS.appendChild(BX.create('A', {
			attrs: {
				href: 'javascript:void(0)'
			},
			props: {
				className: this.PARAMS.pin
							? 'bx-context-toolbar-pin-fixed'
							: 'bx-context-toolbar-pin'
			},
			events: {
				click: BX.delegate(this.__pin_btn_clicked, this)
			}
		}));
	}


	if (this.EXTRA_BUTTONS['components2_props'])
	{
		var btn = this.EXTRA_BUTTONS['components2_props'] || {URL: 'javascript:void(0)'};
		if (null == this.defaultAction)
		{
			this.defaultAction = btn.ONCLICK;
			this.defaultActionTitle = btn.TITLE || btn.TEXT;
		}

		btn.URL = 'javascript:' + BX.util.urlencode(btn.ONCLICK);

		this.ATTACH = this.PARTS.ICONS.appendChild(BX.create('SPAN', {
			props: {className: 'bx-context-toolbar-button bx-context-toolbar-button-settings' },
			children:
			[
				BX.create('SPAN',
				{
					props:{className: 'bx-context-toolbar-button-inner'},
					children:
					[
						BX.create('A', {
							attrs: {href: btn.URL},
							events: {
								mouseover: BX.proxy(this.__msover_text, this),
								mouseout: BX.proxy(this.__msout_text, this),
								mousedown: BX.proxy(this.__msdown_text, this)
							},
							html: '<span class="bx-context-toolbar-button-icon bx-context-toolbar-settings-icon"></span>'
						}),
						BX.create('A', {
							attrs: {href: 'javascript: void(0)'},
							props: {className: 'bx-context-toolbar-button-arrow'},
							events: {
								mouseover: BX.proxy(this.__msover_arrow, this),
								mouseout: BX.proxy(this.__msout_arrow, this),
								mousedown: BX.proxy(this.__msdown_arrow, this)
							},
							html: '<span class="bx-context-toolbar-button-arrow"></span>'
						})
					]
				})
			]
		}));

		this.OPENER = this.ATTACH.firstChild.lastChild;

		var opener = this.attachMenu(this.EXTRA_BUTTONS['components2_submenu']['MENU']);

		BX.addCustomEvent(opener, 'onOpenerMenuOpen', BX.proxy(this.__menu_open, this));
		BX.addCustomEvent(opener, 'onOpenerMenuClose', BX.proxy(this.__menu_close, this));
	}

	if (btnCount > 1)
	{
		this.PARTS.ICONS.appendChild(BX.create('span', { props: {className: 'bx-context-toolbar-separator bx-context-toolbar-separator-switcher'}}));

		this.ICON_TRANSFORM = this.PARTS.ICONS.appendChild(BX.create('A', {
			attrs: {href: 'javascript: void(0)'},
			props: {className: 'bx-context-toolbar-switcher'},
			events: {
				click: BX.delegate(this.__trf_btn_clicked, this)
			}
		}));
	}

	if (this.PARAMS.HINT)
	{
		this.DIV.BXHINT = this.HINT = new BX.CHint({
			parent: this.DIV,
			hint:this.PARAMS.HINT.TEXT || '',
			title: this.PARAMS.HINT.TITLE || '',
			hide_timeout: this.timeout/2,
			preventHide: false
		});
	}

	BX.addCustomEvent(this, 'onWindowDragFinished', BX.delegate(this.__onMoveFinished, this));
	BX.addCustomEvent('onDynamicModeChange', BX.delegate(this.__onDynamicModeChange, this));
	BX.addCustomEvent('onTopPanelCollapse', BX.delegate(this.__onPanelCollapse, this));

	BX.addCustomEvent('onMenuOpenerMoved', BX.delegate(this.checkPosition, this));
	BX.addCustomEvent('onMenuOpenerUnhide', BX.delegate(this.checkPosition, this));

	if (this.OPENERS)
	{
		for (i=0,len=this.OPENERS.length; i<len; i++)
		{
			BX.addCustomEvent(this.OPENERS[i], 'onOpenerMenuOpen', BX.proxy(this.__hide_hint, this));
		}
	}
};
BX.extend(BX.CMenuOpener, BX.CWindowFloat);

BX.CMenuOpener.prototype.setParent = function(new_parent)
{
	new_parent = BX(new_parent);
	if(new_parent.OPENER && new_parent.OPENER != this)
	{
		new_parent.OPENER.Close();
		new_parent.OPENER.clearHoverHoutEvents();
	}

	if(this.PARAMS.parent && this.PARAMS.parent != new_parent)
	{
		this.clearHoverHoutEvents();
		this.PARAMS.parent.OPENER = null;
	}

	this.PARAMS.parent = new_parent;
	this.PARAMS.parent.OPENER = this;
};

BX.CMenuOpener.prototype.setHoverHoutEvents = function(hover, hout)
{
	if(!this.__opener_events_set)
	{
		BX.bind(this.Get(), 'mouseover', hover);
		BX.bind(this.Get(), 'mouseout', hout);
		this.__opener_events_set = true;
	}
};

BX.CMenuOpener.prototype.clearHoverHoutEvents = function()
{
	if(this.Get())
	{
		BX.unbindAll(this.Get());
		this.__opener_events_set = false;
	}
};


BX.CMenuOpener.prototype.unclosable = true;

BX.CMenuOpener.prototype.__check_intersection = function(pos_self, pos_other)
{
	return !(pos_other.right <= pos_self.left || pos_other.left >= pos_self.right
			|| pos_other.bottom <= pos_self.top || pos_other.top >= pos_self.bottom);
};


BX.CMenuOpener.prototype.__msover_text = function() {
	this.bx_hover = true;
	if (!this._menu_open)
		BX.addClass(this.ATTACH, 'bx-context-toolbar-button-text-hover');
};

BX.CMenuOpener.prototype.__msout_text = function() {
	this.bx_hover = false;
	if (!this._menu_open)
		BX.removeClass(this.ATTACH, 'bx-context-toolbar-button-text-hover bx-context-toolbar-button-text-active');
};

BX.CMenuOpener.prototype.__msover_arrow = function() {
	this.bx_hover = true;
	if (!this._menu_open)
		BX.addClass(this.ATTACH, 'bx-context-toolbar-button-arrow-hover');
};

BX.CMenuOpener.prototype.__msout_arrow = function() {
	this.bx_hover = false;
	if (!this._menu_open)
		BX.removeClass(this.ATTACH, 'bx-context-toolbar-button-arrow-hover bx-context-toolbar-button-arrow-active');
};

BX.CMenuOpener.prototype.__msdown_text = function() {
	this.bx_active = true;
	if (!this._menu_open)
		BX.addClass(this.ATTACH, 'bx-context-toolbar-button-text-active');
};

BX.CMenuOpener.prototype.__msdown_arrow = function() {
	this.bx_active = true;
	if (!this._menu_open)
		BX.addClass(this.ATTACH, 'bx-context-toolbar-button-arrow-active');
};

BX.CMenuOpener.prototype.__menu_close = function() {
	this._menu_open = false;
	this.bx_active = false;
	BX.removeClass(this.ATTACH, 'bx-context-toolbar-button-active bx-context-toolbar-button-text-active bx-context-toolbar-button-arrow-active');
	if (!this.bx_hover)
	{
		BX.removeClass(this.ATTACH, 'bx-context-toolbar-button-hover bx-context-toolbar-button-text-hover bx-context-toolbar-button-arrow-hover');
		this.bx_hover = false;
	}
};

BX.CMenuOpener.prototype.__menu_open = function() {
	this._menu_open = true;
};

BX.CMenuOpener.prototype.checkPosition = function()
{
	if (this.isMenuVisible() || this.DIV.style.display == 'none'
		|| this == BX.proxy_context || BX.proxy_context.zIndex > this.zIndex)
		return;

	this.correctPosition(BX.proxy_context);
};

BX.CMenuOpener.prototype.correctPosition = function(opener)
{
	var pos_self = BX.pos(this.DIV), pos_other = BX.pos(opener.Get());
	if (this.__check_intersection(pos_self, pos_other))
	{
		var new_top = pos_other.top - pos_self.height;
		if (new_top < 0)
			new_top = pos_other.bottom;

		this.DIV.style.top = new_top + 'px';

		BX.addCustomEvent(opener, 'onMenuOpenerHide', BX.proxy(this.restorePosition, this));
		BX.onCustomEvent(this, 'onMenuOpenerMoved');
	}
};

BX.CMenuOpener.prototype.restorePosition = function()
{
	if (!this.MOUSEOVER && !this.isMenuVisible())
	{
		if (this.originalPos)
			this.DIV.style.top = this.originalPos.top + 'px';

		BX.removeCustomEvent(BX.proxy_context, 'onMenuOpenerHide', BX.proxy(this.restorePosition, this));
		if (this.restore_pos_timeout) clearTimeout(this.restore_pos_timeout);
	}
	else
	{
		this.restore_pos_timeout = setTimeout(BX.proxy(this.restorePosition, this), this.timeout);
	}
};


BX.CMenuOpener.prototype.Show = function()
{
	BX.CMenuOpener.superclass.Show.apply(this, arguments);

	this.SetDraggable(this.PARTS.INNER.firstChild);

	this.DIV.style.width = 'auto';
	this.DIV.style.height = 'auto';

	if (!this.PARAMS.pin)
	{
		this.DIV.style.left = '-1000px';
		this.DIV.style.top = '-1000px';

		this.Hide();
	}
	else
	{
		this.bPosAdjusted = true;
		this.bMoved = true;

		if (this.PARAMS.top) this.DIV.style.top = this.PARAMS.top + 'px';
		if (this.PARAMS.left) this.DIV.style.left = this.PARAMS.left + 'px';

		this.DIV.style.display = (!BX.admin.dynamic_mode || BX.admin.dynamic_mode_show_borders) ? 'block' : 'none';

		if (this.DIV.style.display == 'block')
		{
			setTimeout(BX.delegate(function() {BX.onCustomEvent(this, 'onMenuOpenerUnhide')}, this), 50);
		}
	}
};

BX.CMenuOpener.prototype.executeDefaultAction = function()
{
	if (this.defaultAction)
	{
		if (BX.type.isFunction(this.defaultAction))
			this.defaultAction();
		else if(BX.type.isString(this.defaultAction))
			BX.evalGlobal(this.defaultAction);
	}
};

BX.CMenuOpener.prototype.__onDynamicModeChange = function(val)
{
	this.DIV.style.display = val ? 'block' : 'none';
};

BX.CMenuOpener.prototype.__onPanelCollapse = function(bCollapsed, dy)
{
	this.DIV.style.top = (parseInt(this.DIV.style.top) + dy) + 'px';
	if (this.PARAMS.pin)
	{
		this.__savePosition();
	}
};

BX.CMenuOpener.prototype.__onMoveFinished = function()
{
	BX.onCustomEvent(this, 'onMenuOpenerMoved');

	this.bMoved = true;

	if (this.PARAMS.pin)
		this.__savePosition();
};

BX.CMenuOpener.prototype.__savePosition = function()
{
	var arOpts = {};

	arOpts.pin = this.PARAMS.pin;
	if (!this.PARAMS.pin)
	{
		arOpts.top = false; arOpts.left = false; arOpts.transform = false;
	}
	else
	{
		arOpts.transform = this.PARAMS.transform;
		if (this.bMoved)
		{
			arOpts.left = parseInt(this.DIV.style.left);
			arOpts.top = parseInt(this.DIV.style.top);
		}
	}

	BX.WindowManager.saveWindowOptions(this.PARAMS.component_id, arOpts);
};

BX.CMenuOpener.prototype.__pin_btn_clicked = function() {this.Pin()};
BX.CMenuOpener.prototype.Pin = function(val)
{
	if (null == val)
		this.PARAMS.pin = !this.PARAMS.pin;
	else
		this.PARAMS.pin = !!val;

	this.PARTS.ICON_PIN.className = (this.PARAMS.pin ? 'bx-context-toolbar-pin-fixed' : 'bx-context-toolbar-pin');

	this.__savePosition();
};

BX.CMenuOpener.prototype.__trf_btn_clicked = function() {this.Transform()};
BX.CMenuOpener.prototype.Transform = function(val)
{
	var pos = {};

	if (null == val)
		this.PARAMS.transform = !this.PARAMS.transform;
	else
		this.PARAMS.transform = !!val;

	if (this.bMoved)
	{
		pos = BX.pos(this.DIV);
	}

	if (this.PARAMS.transform)
		BX.addClass(this.DIV.firstChild, 'bx-context-toolbar-vertical-mode');
	else
		BX.removeClass(this.DIV.firstChild, 'bx-context-toolbar-vertical-mode');

	if (!this.bMoved)
	{
		this.adjustPos();
	}
	else
	{
		this.DIV.style.left = (pos.right - this.DIV.offsetWidth - (BX.browser.IsIE() && !BX.browser.IsDoctype() ? 2 : 0)) + 'px';
	}

	this.__savePosition();
};

BX.CMenuOpener.prototype.adjustToNodeGetPos = function()
{
	var pos = BX.pos(this.PARAMS.parent/*, true*/);

	var scrollSize = BX.GetWindowScrollSize();
	var floatWidth = this.DIV.offsetWidth;

	pos.left -= BX.admin.__border_dx;
	pos.top -= BX.admin.__border_dx;

	if (true || !this.PARAMS.transform)
	{
		pos.top -= 45;
	}

	if (pos.left > scrollSize.scrollWidth - floatWidth)
	{
		pos.left = scrollSize.scrollWidth - floatWidth;
	}

	return pos;
};

BX.CMenuOpener.prototype.addItem = function(item)
{
	if (item.TYPE)
	{
		this.EXTRA_BUTTONS[item.TYPE] = item;
		return null;
	}
	else
	{
		var q = new BX.CMenuOpenerItem(item);
		if (null == this.defaultAction)
		{
			if (q.item.ONCLICK)
			{
				this.defaultAction = item.ONCLICK;
			}
			else if (q.item.MENU)
			{
				this.defaultAction = BX.delegate(function() {this.Open()}, q.item.OPENER);
			}

			this.defaultActionTitle = item.TITLE || item.TEXT;

			BX.addClass(q.Get(), 'bx-content-toolbar-default');
		}
		if (q.item.OPENER) this.OPENERS[this.OPENERS.length] = q.item.OPENER;
		return q.Get();
	}
};

BX.CMenuOpener.prototype.attachMenu = function(menu)
{
	var opener = new BX.COpener({
		'DIV':  this.OPENER,
		'ATTACH': this.ATTACH,
		'MENU': menu,
		'TYPE': 'click'
	});

	this.OPENERS[this.OPENERS.length] = opener;

	return opener;
};

BX.CMenuOpener.prototype.__hide_hint = function()
{
	if (this.HINT) this.HINT.__hide_immediately();
};

BX.CMenuOpener.prototype.isMenuVisible = function()
{
	for (var i=0,len=this.OPENERS.length; i<len; i++)
	{
		if (this.OPENERS[i].isMenuVisible())
			return true;
	}

	return false;
};

BX.CMenuOpener.prototype.Hide = function()
{
	if (!this.PARAMS.pin)
	{
		this.DIV.style.display = 'none';
		BX.onCustomEvent(this, 'onMenuOpenerHide');
	}
};
BX.CMenuOpener.prototype.UnHide = function()
{
	this.DIV.style.display = 'block';
	if (!this.bPosAdjusted && !this.PARAMS.pin)
	{
		this.adjustPos();
		this.bPosAdjusted = true;
	}

	if (null == this.originalPos && !this.bMoved)
	{
		this.originalPos = BX.pos(this.DIV);
	}

	BX.onCustomEvent(this, 'onMenuOpenerUnhide');
};

BX.CMenuOpenerItem = function(item)
{
	this.item = item;

	if (this.item.ACTION && !this.item.ONCLICK)
	{
		this.item.ONCLICK = this.item.ACTION;
	}

	this.DIV = BX.create('SPAN');
	this.DIV.appendChild(BX.create('SPAN', {props: {className: 'bx-context-toolbar-button-underlay'}}));

	this.WRAPPER = this.DIV.appendChild(BX.create('SPAN', {
		props: {className: 'bx-context-toolbar-button-wrapper'},
		children: [
			BX.create('SPAN', {
				props: {className: 'bx-context-toolbar-button', title: item.TITLE},
				children: [
					BX.create('SPAN', {
						props: {className: 'bx-context-toolbar-button-inner'}
					})
				]
			})
		]
	}));

	var btn_icon = BX.create('SPAN', {
		props: {className: 'bx-context-toolbar-button-icon' + (this.item.ICON || this.item.ICONCLASS ? ' ' + (this.item.ICON || this.item.ICONCLASS) : '')},
		attrs: (
				!(this.item.ICON || this.item.ICONCLASS)
				&&
				(this.item.SRC || this.item.IMAGE)
			)
			? {
				style: 'background: scroll transparent url(' + (this.item.SRC || this.item.IMAGE) + ') no-repeat center center !important;'
			}
			: {}
	}), btn_text = BX.create('SPAN', {
		props: {className: 'bx-context-toolbar-button-text'},
		text: this.item.TEXT
	});

	if (this.item.ACTION && !this.item.ONCLICK)
	{
		this.item.ONCLICK = this.item.ACTION;
	}

	this.bHasMenu = !!this.item.MENU;
	this.bHasAction = !!this.item.ONCLICK;

	if (this.bHasAction)
	{
		this.LINK = this.WRAPPER.firstChild.firstChild.appendChild(BX.create('A', {
			attrs: {
				'href': 'javascript: void(0)'
			},
			events: {
				mouseover: this.bHasMenu ? BX.proxy(this.__msover_text, this) : BX.proxy(this.__msover, this),
				mouseout: this.bHasMenu ? BX.proxy(this.__msout_text, this) : BX.proxy(this.__msout, this),
				mousedown: this.bHasMenu ? BX.proxy(this.__msdown_text, this) : BX.proxy(this.__msdown, this)
			},
			children: [btn_icon, btn_text]
		}));

		if (this.bHasMenu)
		{
			this.LINK_MENU = this.WRAPPER.firstChild.firstChild.appendChild(BX.create('A', {
				props: {className: 'bx-context-toolbar-button-arrow'},
				attrs: {
					'href': 'javascript: void(0)'
				},
				events: {
					mouseover: BX.proxy(this.__msover_arrow, this),
					mouseout: BX.proxy(this.__msout_arrow, this),
					mousedown: BX.proxy(this.__msdown_arrow, this)
				},
				children: [
					BX.create('SPAN', {props: {className: 'bx-context-toolbar-button-arrow'}})
				]
			}));
		}

	}
	else if (this.bHasMenu)
	{
		this.item.ONCLICK = null;

		this.LINK = this.LINK_MENU = this.WRAPPER.firstChild.firstChild.appendChild(BX.create('A', {
			attrs: {
				'href': 'javascript: void(0)'
			},
			events: {
				mouseover: BX.proxy(this.__msover, this),
				mouseout: BX.proxy(this.__msout, this),
				mousedown: BX.proxy(this.__msdown, this)
			},
			children: [
				btn_icon,
				btn_text
			]
		}));

		this.LINK.appendChild(BX.create('SPAN', {props: {className: 'bx-context-toolbar-single-button-arrow'}}));

	}

	if (this.bHasMenu)
	{
		this.item.SUBMENU = new BX.CMenu({
			ATTACH_MODE:'bottom',
			ITEMS:this.item['MENU'],
			//PARENT_MENU:this.parentMenu,
			parent: this.LINK_MENU,
			parent_attach: this.WRAPPER.firstChild
		});

		this.item.OPENER = new BX.COpener({
			DIV: this.LINK_MENU,
			TYPE: 'click',
			MENU: this.item.SUBMENU
		});

		BX.addCustomEvent(this.item.OPENER, 'onOpenerMenuOpen', BX.proxy(this.__menu_open, this));
		BX.addCustomEvent(this.item.OPENER, 'onOpenerMenuClose', BX.proxy(this.__menu_close, this));
	}

	if (this.bHasAction)
	{
		BX.bind(this.LINK, 'click', BX.delegate(this.__click, this));
	}
};

BX.CMenuOpenerItem.prototype.Get = function() {return this.DIV;};
BX.CMenuOpenerItem.prototype.__msover = function() {
	this.bx_hover = true;
	if (!this._menu_open)
		BX.addClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-hover');
};
BX.CMenuOpenerItem.prototype.__msout = function() {
	this.bx_hover = false;
	if (!this._menu_open)
		BX.removeClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-hover bx-context-toolbar-button-active');
};
BX.CMenuOpenerItem.prototype.__msover_text = function() {
	this.bx_hover = true;
	if (!this._menu_open)
		BX.addClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-text-hover');
};
BX.CMenuOpenerItem.prototype.__msout_text = function() {
	this.bx_hover = false;
	if (!this._menu_open)
		BX.removeClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-text-hover bx-context-toolbar-button-text-active');
};
BX.CMenuOpenerItem.prototype.__msover_arrow = function() {
	this.bx_hover = true;
	if (!this._menu_open)
		BX.addClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-arrow-hover');
};
BX.CMenuOpenerItem.prototype.__msout_arrow = function() {
	this.bx_hover = false;
	if (!this._menu_open)
		BX.removeClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-arrow-hover bx-context-toolbar-button-arrow-active');
};
BX.CMenuOpenerItem.prototype.__msdown = function() {
	this.bx_active = true;
	if (!this._menu_open)
		BX.addClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-active');
};
BX.CMenuOpenerItem.prototype.__msdown_text = function() {
	this.bx_active = true;
	if (!this._menu_open)
		BX.addClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-text-active');
};
BX.CMenuOpenerItem.prototype.__msdown_arrow = function() {
	this.bx_active = true;
	if (!this._menu_open)
		BX.addClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-arrow-active');
};
BX.CMenuOpenerItem.prototype.__menu_close = function() {

	this._menu_open = false;
	this.bx_active = false;
	BX.removeClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-active bx-context-toolbar-button-text-active bx-context-toolbar-button-arrow-active');
	if (!this.bx_hover)
	{
		BX.removeClass(this.LINK.parentNode.parentNode, 'bx-context-toolbar-button-hover bx-context-toolbar-button-text-hover bx-context-toolbar-button-arrow-hover');
		this.bx_hover = false;
	}
};
BX.CMenuOpenerItem.prototype.__menu_open = function() {
	this._menu_open = true;
};

BX.CMenuOpenerItem.prototype.__click = function() {BX.evalGlobal(this.item.ONCLICK)};

/* global page opener class */
BX.CPageOpener = function(arParams)
{
	//if (null == arParams.pin) arParams.pin = true;
	BX.CPageOpener.superclass.constructor.apply(this, arguments);

	this.timeout = 505;

	window.PAGE_EDIT_CONTROL = this;
};
BX.extend(BX.CPageOpener, BX.CMenuOpener);

BX.CPageOpener.prototype.checkPosition = function()
{
	if (/*this.isMenuVisible() || this.DIV.style.display == 'none' || */this == BX.proxy_context)
		return;

	this.correctPosition(BX.proxy_context);
};

BX.CPageOpener.prototype.correctPosition = function(opener)
{
	if (this.bPosCorrected) return;
	var pos_self;
	if (this.DIV.style.display == 'none')
	{
		pos_self = this.adjustToNodeGetPos();
		pos_self.bottom = pos_self.top + 30;
		pos_self.right = pos_self.left + 300;
	}
	else
	{
		pos_self = BX.pos(this.DIV);
	}

	var pos_other = BX.pos(opener.Get());
	if (this.__check_intersection(pos_self, pos_other))
	{
		this.DIV.style.display = 'none';
		BX.addCustomEvent(opener, 'onMenuOpenerHide', BX.proxy(this.restorePosition, this));

		this.bPosCorrected = true;
	}
};

BX.CPageOpener.prototype.restorePosition = function()
{
	if (BX.proxy_context && BX.proxy_context.Get().style.display == 'none')
	{
		this.bPosCorrected = false;

		if (this.PARAMS.parent.bx_over || this.PARAMS.pin)
			this.UnHide();

		BX.removeCustomEvent('onMenuOpenerHide', BX.proxy(this.restorePosition, this));
	}
};

BX.CPageOpener.prototype.UnHide = function()
{
	if (!this.bPosCorrected)
		BX.CPageOpener.superclass.UnHide.apply(this, arguments);
};

BX.CPageOpener.prototype.Remove = function()
{
	BX.admin.removeComponentBorder(this.PARAMS.parent);
	BX.userOptions.save('global', 'settings', 'page_edit_control_enable', 'N');
	this.DIV.style.display = 'none';
};

/******* HINT ***************/
BX.CHintSimple = function()
{
	BX.CHintSimple.superclass.constructor.apply(this, arguments);
};
BX.extend(BX.CHintSimple, BX.CHint);

BX.CHintSimple.prototype.Init = function()
{
	this.DIV = document.body.appendChild(BX.create('DIV', {props: {className: 'bx-tooltip-simple'}, style: {display: 'none'}, children: [(this.CONTENT = BX.create('DIV'))]}));

	if (this.HINT_TITLE)
		this.CONTENT.appendChild(BX.create('B', {text: this.HINT_TITLE}));

	if (this.HINT)
		this.CONTENT_TEXT = this.CONTENT.appendChild(BX.create('DIV')).appendChild(BX.create('SPAN', {html: this.HINT}));

	if (this.PARAMS.preventHide)
	{
		BX.bind(this.DIV, 'mouseout', BX.proxy(this.Hide, this));
		BX.bind(this.DIV, 'mouseover', BX.proxy(this.Show, this));
	}

	this.bInited = true;
};

/*************************** admin informer **********************************/
BX.adminInformer = {

	itemsShow: 3,

	Init: function (itemsShow)
	{
		if(itemsShow)
			BX.adminInformer.itemsShow = itemsShow;

		var informer = BX("admin-informer");

		if(informer)
			document.body.appendChild(informer);

		BX.addCustomEvent("onTopPanelCollapse", BX.proxy(BX.adminInformer.Close, BX.adminInformer));
	},

	Toggle: function(notifyBlock)
	{
		var informer = BX("admin-informer");

		if(!informer)
			return false;

		var pos = BX.pos(notifyBlock);

		informer.style.top = (parseInt(pos.top)+parseInt(pos.height)+7)+'px';
		informer.style.left = pos.left+'px';

		if(!BX.hasClass(informer, "adm-informer-active"))
			BX.adminInformer.Show(informer);
		else
			BX.adminInformer.Hide(informer);

		return false;
	},

	Close: function()
	{
		BX.adminInformer.Hide(BX("admin-informer"));
	},

	OnInnerClick: function(event)
	{
		var target = event.target || event.srcElement;

		if(target.nodeName.toLowerCase() != 'a' || BX.hasClass(target,"adm-informer-footer"))
		{
			return BX.PreventDefault(event);
		}

		return true;
	},

	ToggleExtra : function()
	{
		var footerLink = BX("adm-informer-footer");

		if (BX.hasClass(footerLink, "adm-informer-footer-collapsed"))
			this.ShowAll();
		else
			this.HideExtra();

		return false;
	},

	ShowAll: function()
	{
		var informer = BX("admin-informer");
		for(var i=0; i<informer.children.length; i++)

			if(BX.hasClass(informer.children[i], "adm-informer-item") && informer.children[i].style.display == "none") {
				informer.children[i].style.display = "block";
			}

		var footerLink = BX("adm-informer-footer");

		if(footerLink.textContent !== undefined)
			footerLink.textContent = BX.message('JSADM_AI_HIDE_EXTRA');
		else
			footerLink.innerText = BX.message('JSADM_AI_HIDE_EXTRA');

		BX.removeClass(footerLink, "adm-informer-footer-collapsed");

		return false;
	},

	HideExtra: function()
	{
		var informer = BX("admin-informer");
		var hided = 0;

		for(var i=BX.adminInformer.itemsShow+1; i<informer.children.length; i++)
		{
			if (BX.hasClass(informer.children[i], "adm-informer-item") && informer.children[i].style.display == "block") {
				informer.children[i].style.display = "none";
				hided++;
			}
		}

		var footerLink = BX("adm-informer-footer");

		var linkText = BX.message('JSADM_AI_ALL_NOTIF')+" ("+(BX.adminInformer.itemsShow+parseInt(hided))+")";

		if(footerLink.textContent !== undefined)
			footerLink.textContent = linkText;
		else
			footerLink.innerText = linkText;

		BX.addClass(footerLink, "adm-informer-footer-collapsed");

		return false;
	},

	Show: function(informer)
	{
		var notifButton = BX("adm-header-notif-block");
		if (notifButton)
			BX.addClass(notifButton, "adm-header-notif-block-active");

		BX.onCustomEvent(informer, 'onBeforeAdminInformerShow');
		setTimeout(
			BX.proxy(function() {
					BX.bind(document, "click", BX.proxy(BX.adminInformer.Close, BX.adminInformer));
				},
				BX.adminInformer
			),0
		);
		BX.addClass(informer, "adm-informer-active");
		setTimeout(function() {BX.addClass(informer, "adm-informer-animate");},0);
	},

	Hide: function(informer)
	{
		var notifButton = BX("adm-header-notif-block");
		if (notifButton)
			BX.removeClass(notifButton, "adm-header-notif-block-active");

		BX.unbind(document, "click", BX.proxy(BX.adminInformer.Close, BX.adminInformer));

		BX.removeClass(informer, "adm-informer-animate");

		if (this.IsAnimationSupported())
			setTimeout(function() {BX.removeClass(informer, "adm-informer-active");}, 300);
		else
			BX.removeClass(informer, "adm-informer-active");

		BX.onCustomEvent(informer, 'onAdminInformerHide');
		//setTimeout(function() {BX.adminInformer.HideExtra();},500);
	},

	IsAnimationSupported : function()
	{
		var d = document.body || document.documentElement;
		if (typeof(d.style.transition) == "string")
			return true;
		else if (typeof(d.style.MozTransition) == "string")
			return true;
		else if (typeof(d.style.OTransition) == "string")
			return true;
		else if (typeof(d.style.WebkitTransition) == "string")
			return true;
		else if (typeof(d.style.msTransition) == "string")
			return true;

		return false;
	},


	SetItemHtml: function(itemIdx, html)
	{
		var itemHtmlDiv = BX("adm-informer-item-html-"+itemIdx);

		if(!itemHtmlDiv)
			return false;

		itemHtmlDiv.innerHTML = html;

		return true;
	},

	SetItemFooter: function(itemIdx, html)
	{
		var itemFooterDiv = BX("adm-informer-item-footer-"+itemIdx);

		if(!itemFooterDiv)
			return false;

		itemFooterDiv.innerHTML = html;

		if(html)
			itemFooterDiv.style.display = "block";
		else
			itemFooterDiv.style.display = "none";

		return true;
	}

};

})(window);


/* End */
;
; /* Start:"a:4:{s:4:"full";s:53:"/bitrix/js/main/date/main.date.min.js?158748974416360";s:6:"source";s:33:"/bitrix/js/main/date/main.date.js";s:3:"min";s:37:"/bitrix/js/main/date/main.date.min.js";s:3:"map";s:37:"/bitrix/js/main/date/main.date.map.js";}"*/
(function(e){if(!e.BX){e.BX={}}if(!e.BX.Main){e.BX.Main={}}else if(e.BX.Main.Date){return}var t=e.BX;t.Main.Date={AM_PM_MODE:{UPPER:1,LOWER:2,NONE:false},format:function(e,t,_,n){var a=this;var D=r.isDate(t)?new Date(t.getTime()):r.isNumber(t)?new Date(t*1e3):new Date;var i=r.isDate(_)?new Date(_.getTime()):r.isNumber(_)?new Date(_*1e3):new Date;var s=!!n;if(r.isArray(e))return g(e,D,i,s);else if(!r.isNotEmptyString(e))return"";var o=(e.match(/{{([^{}]*)}}/g)||[]).map(function(e){return(e.match(/[^{}]+/)||[""])[0]});if(o.length>0){o.forEach(function(t,r){e=e.replace("{{"+t+"}}","{{"+r+"}}")})}var M=/\\?(sago|iago|isago|Hago|dago|mago|Yago|sdiff|idiff|Hdiff|ddiff|mdiff|Ydiff|sshort|ishort|Hshort|dshort|mhort|Yshort|yesterday|today|tommorow|tomorrow|[a-z])/gi;var u={d:function(){return r.strPadLeft(c(D).toString(),2,"0")},D:function(){return a._getMessage("DOW_"+H(D))},j:function(){return c(D)},l:function(){return a._getMessage("DAY_OF_WEEK_"+H(D))},N:function(){return H(D)||7},S:function(){if(c(D)%10==1&&c(D)!=11)return"st";else if(c(D)%10==2&&c(D)!=12)return"nd";else if(c(D)%10==3&&c(D)!=13)return"rd";else return"th"},w:function(){return H(D)},z:function(){var e=new Date(F(D),0,1);var t=new Date(F(D),T(D),c(D));return Math.ceil((t-e)/(24*3600*1e3))},W:function(){var e=new Date(D.getTime());var t=(H(D)+6)%7;R(e,c(e)-t+3);var _=e.getTime();S(e,0,1);if(H(e)!=4)S(e,0,1+(4-H(e)+7)%7);var n=1+Math.ceil((_-e)/(7*24*3600*1e3));return r.strPadLeft(n.toString(),2,"0")},F:function(){return a._getMessage("MONTH_"+(T(D)+1)+"_S")},f:function(){return a._getMessage("MONTH_"+(T(D)+1))},m:function(){return r.strPadLeft((T(D)+1).toString(),2,"0")},M:function(){return a._getMessage("MON_"+(T(D)+1))},n:function(){return T(D)+1},t:function(){var e=s?new Date(Date.UTC(F(D),T(D)+1,0)):new Date(F(D),T(D)+1,0);return c(e)},L:function(){var e=F(D);return e%4==0&&e%100!=0||e%400==0?1:0},o:function(){var e=new Date(D.getTime());R(e,c(e)-(H(D)+6)%7+3);return F(e)},Y:function(){return F(D)},y:function(){return F(D).toString().slice(2)},a:function(){return E(D)>11?"pm":"am"},A:function(){return E(D)>11?"PM":"AM"},B:function(){var e=(D.getUTCHours()+1)%24+D.getUTCMinutes()/60+D.getUTCSeconds()/3600;return r.strPadLeft(Math.floor(e*1e3/24).toString(),3,"0")},g:function(){return E(D)%12||12},G:function(){return E(D)},h:function(){return r.strPadLeft((E(D)%12||12).toString(),2,"0")},H:function(){return r.strPadLeft(E(D).toString(),2,"0")},i:function(){return r.strPadLeft(l(D).toString(),2,"0")},s:function(){return r.strPadLeft(A(D).toString(),2,"0")},u:function(){return r.strPadLeft((m(D)*1e3).toString(),6,"0")},e:function(){if(s)return"UTC";return""},I:function(){if(s)return 0;var e=new Date(F(D),0,1);var t=Date.UTC(F(D),0,1);var r=new Date(F(D),6,0);var _=Date.UTC(F(D),6,0);return 0+(e-t!==r-_)},O:function(){if(s)return"+0000";var e=D.getTimezoneOffset();var t=Math.abs(e);return(e>0?"-":"+")+r.strPadLeft((Math.floor(t/60)*100+t%60).toString(),4,"0")},P:function(){if(s)return"+00:00";var e=this.O();return e.substr(0,3)+":"+e.substr(3)},Z:function(){if(s)return 0;return-D.getTimezoneOffset()*60},c:function(){return"Y-m-d\\TH:i:sP".replace(M,h)},r:function(){return"D, d M Y H:i:s O".replace(M,h)},U:function(){return Math.floor(D.getTime()/1e3)},sago:function(){return d(N((i-D)/1e3),{0:"FD_SECOND_AGO_0",1:"FD_SECOND_AGO_1","10_20":"FD_SECOND_AGO_10_20",MOD_1:"FD_SECOND_AGO_MOD_1",MOD_2_4:"FD_SECOND_AGO_MOD_2_4",MOD_OTHER:"FD_SECOND_AGO_MOD_OTHER"})},sdiff:function(){return d(N((i-D)/1e3),{0:"FD_SECOND_DIFF_0",1:"FD_SECOND_DIFF_1","10_20":"FD_SECOND_DIFF_10_20",MOD_1:"FD_SECOND_DIFF_MOD_1",MOD_2_4:"FD_SECOND_DIFF_MOD_2_4",MOD_OTHER:"FD_SECOND_DIFF_MOD_OTHER"})},sshort:function(){return a._getMessage("FD_SECOND_SHORT").replace(/#VALUE#/g,N((i-D)/1e3))},iago:function(){return d(N((i-D)/60/1e3),{0:"FD_MINUTE_AGO_0",1:"FD_MINUTE_AGO_1","10_20":"FD_MINUTE_AGO_10_20",MOD_1:"FD_MINUTE_AGO_MOD_1",MOD_2_4:"FD_MINUTE_AGO_MOD_2_4",MOD_OTHER:"FD_MINUTE_AGO_MOD_OTHER"})},idiff:function(){return d(N((i-D)/60/1e3),{0:"FD_MINUTE_DIFF_0",1:"FD_MINUTE_DIFF_1","10_20":"FD_MINUTE_DIFF_10_20",MOD_1:"FD_MINUTE_DIFF_MOD_1",MOD_2_4:"FD_MINUTE_DIFF_MOD_2_4",MOD_OTHER:"FD_MINUTE_DIFF_MOD_OTHER"})},isago:function(){var e=N((i-D)/60/1e3);var t=d(e,{0:"FD_MINUTE_0",1:"FD_MINUTE_1","10_20":"FD_MINUTE_10_20",MOD_1:"FD_MINUTE_MOD_1",MOD_2_4:"FD_MINUTE_MOD_2_4",MOD_OTHER:"FD_MINUTE_MOD_OTHER"});t+=" ";var r=N((i-D)/1e3)-e*60;t+=d(r,{0:"FD_SECOND_AGO_0",1:"FD_SECOND_AGO_1","10_20":"FD_SECOND_AGO_10_20",MOD_1:"FD_SECOND_AGO_MOD_1",MOD_2_4:"FD_SECOND_AGO_MOD_2_4",MOD_OTHER:"FD_SECOND_AGO_MOD_OTHER"});return t},ishort:function(){return a._getMessage("FD_MINUTE_SHORT").replace(/#VALUE#/g,N((i-D)/60/1e3))},Hago:function(){return d(N((i-D)/60/60/1e3),{0:"FD_HOUR_AGO_0",1:"FD_HOUR_AGO_1","10_20":"FD_HOUR_AGO_10_20",MOD_1:"FD_HOUR_AGO_MOD_1",MOD_2_4:"FD_HOUR_AGO_MOD_2_4",MOD_OTHER:"FD_HOUR_AGO_MOD_OTHER"})},Hdiff:function(){return d(N((i-D)/60/60/1e3),{0:"FD_HOUR_DIFF_0",1:"FD_HOUR_DIFF_1","10_20":"FD_HOUR_DIFF_10_20",MOD_1:"FD_HOUR_DIFF_MOD_1",MOD_2_4:"FD_HOUR_DIFF_MOD_2_4",MOD_OTHER:"FD_HOUR_DIFF_MOD_OTHER"})},Hshort:function(){return a._getMessage("FD_HOUR_SHORT").replace(/#VALUE#/g,N((i-D)/60/60/1e3))},yesterday:function(){return a._getMessage("FD_YESTERDAY")},today:function(){return a._getMessage("FD_TODAY")},tommorow:function(){return a._getMessage("FD_TOMORROW")},tomorrow:function(){return a._getMessage("FD_TOMORROW")},dago:function(){return d(N((i-D)/60/60/24/1e3),{0:"FD_DAY_AGO_0",1:"FD_DAY_AGO_1","10_20":"FD_DAY_AGO_10_20",MOD_1:"FD_DAY_AGO_MOD_1",MOD_2_4:"FD_DAY_AGO_MOD_2_4",MOD_OTHER:"FD_DAY_AGO_MOD_OTHER"})},ddiff:function(){return d(N((i-D)/60/60/24/1e3),{0:"FD_DAY_DIFF_0",1:"FD_DAY_DIFF_1","10_20":"FD_DAY_DIFF_10_20",MOD_1:"FD_DAY_DIFF_MOD_1",MOD_2_4:"FD_DAY_DIFF_MOD_2_4",MOD_OTHER:"FD_DAY_DIFF_MOD_OTHER"})},dshort:function(){return a._getMessage("FD_DAY_SHORT").replace(/#VALUE#/g,N((i-D)/60/60/24/1e3))},mago:function(){return d(N((i-D)/60/60/24/31/1e3),{0:"FD_MONTH_AGO_0",1:"FD_MONTH_AGO_1","10_20":"FD_MONTH_AGO_10_20",MOD_1:"FD_MONTH_AGO_MOD_1",MOD_2_4:"FD_MONTH_AGO_MOD_2_4",MOD_OTHER:"FD_MONTH_AGO_MOD_OTHER"})},mdiff:function(){return d(N((i-D)/60/60/24/31/1e3),{0:"FD_MONTH_DIFF_0",1:"FD_MONTH_DIFF_1","10_20":"FD_MONTH_DIFF_10_20",MOD_1:"FD_MONTH_DIFF_MOD_1",MOD_2_4:"FD_MONTH_DIFF_MOD_2_4",MOD_OTHER:"FD_MONTH_DIFF_MOD_OTHER"})},mshort:function(){return a._getMessage("FD_MONTH_SHORT").replace(/#VALUE#/g,N((i-D)/60/60/24/31/1e3))},Yago:function(){return d(N((i-D)/60/60/24/365/1e3),{0:"FD_YEARS_AGO_0",1:"FD_YEARS_AGO_1","10_20":"FD_YEARS_AGO_10_20",MOD_1:"FD_YEARS_AGO_MOD_1",MOD_2_4:"FD_YEARS_AGO_MOD_2_4",MOD_OTHER:"FD_YEARS_AGO_MOD_OTHER"})},Ydiff:function(){return d(N((i-D)/60/60/24/365/1e3),{0:"FD_YEARS_DIFF_0",1:"FD_YEARS_DIFF_1","10_20":"FD_YEARS_DIFF_10_20",MOD_1:"FD_YEARS_DIFF_MOD_1",MOD_2_4:"FD_YEARS_DIFF_MOD_2_4",MOD_OTHER:"FD_YEARS_DIFF_MOD_OTHER"})},Yshort:function(){return d(N((i-D)/60/60/24/365/1e3),{0:"FD_YEARS_SHORT_0",1:"FD_YEARS_SHORT_1","10_20":"FD_YEARS_SHORT_10_20",MOD_1:"FD_YEARS_SHORT_MOD_1",MOD_2_4:"FD_YEARS_SHORT_MOD_2_4",MOD_OTHER:"FD_YEARS_SHORT_MOD_OTHER"})},x:function(){var e=a.isAmPmMode(true);var t=e===a.AM_PM_MODE.LOWER?"g:i a":e===a.AM_PM_MODE.UPPER?"g:i A":"H:i";return a.format([["tomorrow","tomorrow, "+t],["-",a.convertBitrixFormat(a._getMessage("FORMAT_DATETIME")).replace(/:s/g,"")],["s","sago"],["i","iago"],["today","today, "+t],["yesterday","yesterday, "+t],["",a.convertBitrixFormat(a._getMessage("FORMAT_DATETIME")).replace(/:s/g,"")]],D,i,s)},X:function(){var e=a.isAmPmMode(true);var t=e===a.AM_PM_MODE.LOWER?"g:i a":e===a.AM_PM_MODE.UPPER?"g:i A":"H:i";var r=a.format([["tomorrow","tomorrow"],["-",a.convertBitrixFormat(a._getMessage("FORMAT_DATE"))],["today","today"],["yesterday","yesterday"],["",a.convertBitrixFormat(a._getMessage("FORMAT_DATE"))]],D,i,s);var _=a.format([["tomorrow",t],["today",t],["yesterday",t],["",""]],D,i,s);if(_.length>0)return a._getMessage("FD_DAY_AT_TIME").replace(/#DAY#/g,r).replace(/#TIME#/g,_);else return r},Q:function(){var e=N((i-D)/60/60/24/1e3);if(e==0)return a._getMessage("FD_DAY_DIFF_1").replace(/#VALUE#/g,1);else return a.format([["d","ddiff"],["m","mdiff"],["","Ydiff"]],D,i)}};var f=false;if(e[0]&&e[0]=="^"){f=true;e=e.substr(1)}var O=e.replace(M,h);if(f){O=O.replace(/\s*00:00:00\s*/g,"").replace(/(\d\d:\d\d)(:00)/g,"$1").replace(/(\s*00:00\s*)(?!:)/g,"")}if(o.length>0){o.forEach(function(e,t){O=O.replace("{{"+t+"}}",e)})}return O;function g(e,t,r,_){var n=N((r-t)/1e3);for(var D=0;D<e.length;D++){var i=e[D][0];var s=e[D][1];var o=null;if(i=="s"){if(n<60)return a.format(s,t,r,_)}else if((o=/^s(\d+)\>?(\d+)?/.exec(i))!=null){if(o[1]&&o[2]){if(n<o[1]&&n>o[2]){return a.format(s,t,r,_)}}else if(n<o[1]){return a.format(s,t,r,_)}}else if(i=="i"){if(n<60*60)return a.format(s,t,r,_)}else if((o=/^i(\d+)\>?(\d+)?/.exec(i))!=null){if(o[1]&&o[2]){if(n<o[1]*60&&n>o[2]*60){return a.format(s,t,r,_)}}else if(n<o[1]*60){return a.format(s,t,r,_)}}else if(i=="H"){if(n<24*60*60)return a.format(s,t,r,_)}else if((o=/^H(\d+)\>?(\d+)?/.exec(i))!=null){if(o[1]&&o[2]){if(n<o[1]*60*60&&n>o[2]*60*60){return a.format(s,t,r,_)}}else if(n<o[1]*60*60){return a.format(s,t,r,_)}}else if(i=="d"){if(n<31*24*60*60)return a.format(s,t,r,_)}else if((o=/^d(\d+)\>?(\d+)?/.exec(i))!=null){if(o[1]&&o[2]){if(n<o[1]*24*60*60&&n>o[2]*24*60*60){return a.format(s,t,r,_)}}else if(n<o[1]*24*60*60){return a.format(s,t,r,_)}}else if(i=="m"){if(n<365*24*60*60)return a.format(s,t,r,_)}else if((o=/^m(\d+)\>?(\d+)?/.exec(i))!=null){if(o[1]&&o[2]){if(n<o[1]*31*24*60*60&&n>o[2]*31*24*60*60){return a.format(s,t,r,_)}}else if(n<o[1]*31*24*60*60){return a.format(s,t,r,_)}}else if(i=="now"){if(t.getTime()==r.getTime()){return a.format(s,t,r,_)}}else if(i=="today"){var M=F(r),u=T(r),f=c(r);var O=_?new Date(Date.UTC(M,u,f,0,0,0,0)):new Date(M,u,f,0,0,0,0);var g=_?new Date(Date.UTC(M,u,f+1,0,0,0,0)):new Date(M,u,f+1,0,0,0,0);if(t>=O&&t<g)return a.format(s,t,r,_)}else if(i=="todayFuture"){var M=F(r),u=T(r),f=c(r);var O=r.getTime();var g=_?new Date(Date.UTC(M,u,f+1,0,0,0,0)):new Date(M,u,f+1,0,0,0,0);if(t>=O&&t<g)return a.format(s,t,r,_)}else if(i=="yesterday"){M=F(r);u=T(r);f=c(r);var E=_?new Date(Date.UTC(M,u,f-1,0,0,0,0)):new Date(M,u,f-1,0,0,0,0);var l=_?new Date(Date.UTC(M,u,f,0,0,0,0)):new Date(M,u,f,0,0,0,0);if(t>=E&&t<l)return a.format(s,t,r,_)}else if(i=="tommorow"||i=="tomorrow"){M=F(r);u=T(r);f=c(r);var A=_?new Date(Date.UTC(M,u,f+1,0,0,0,0)):new Date(M,u,f+1,0,0,0,0);var m=_?new Date(Date.UTC(M,u,f+2,0,0,0,0)):new Date(M,u,f+2,0,0,0,0);if(t>=A&&t<m)return a.format(s,t,r,_)}else if(i=="-"){if(n<0)return a.format(s,t,r,_)}}return e.length>0?a.format(e[e.length-1][1],t,r,_):""}function F(e){return s?e.getUTCFullYear():e.getFullYear()}function c(e){return s?e.getUTCDate():e.getDate()}function T(e){return s?e.getUTCMonth():e.getMonth()}function E(e){return s?e.getUTCHours():e.getHours()}function l(e){return s?e.getUTCMinutes():e.getMinutes()}function A(e){return s?e.getUTCSeconds():e.getSeconds()}function m(e){return s?e.getUTCMilliseconds():e.getMilliseconds()}function H(e){return s?e.getUTCDay():e.getDay()}function R(e,t){return s?e.setUTCDate(t):e.setDate(t)}function S(e,t,r){return s?e.setUTCMonth(t,r):e.setMonth(t,r)}function d(e,t){var r=e<100?Math.abs(e):Math.abs(e%100);var _=r%10;var n="";if(r==0)n=a._getMessage(t["0"]);else if(r==1)n=a._getMessage(t["1"]);else if(r>=10&&r<=20)n=a._getMessage(t["10_20"]);else if(_==1)n=a._getMessage(t["MOD_1"]);else if(2<=_&&_<=4)n=a._getMessage(t["MOD_2_4"]);else n=a._getMessage(t["MOD_OTHER"]);return n.replace(/#VALUE#/g,e)}function h(e,t){if(u[e])return u[e]();else return t}function N(e){return e>=0?Math.floor(e):Math.ceil(e)}},convertBitrixFormat:function(e){if(!r.isNotEmptyString(e))return"";return e.replace("YYYY","Y").replace("MMMM","F").replace("MM","m").replace("M","M").replace("DD","d").replace("G","g").replace(/GG/i,"G").replace("H","h").replace(/HH/i,"H").replace("MI","i").replace("SS","s").replace("TT","A").replace("T","a")},convertToUTC:function(e){if(!r.isDate(e))return null;return new Date(Date.UTC(e.getFullYear(),e.getMonth(),e.getDate(),e.getHours(),e.getMinutes(),e.getSeconds(),e.getMilliseconds()))},getNewDate:function(e){return new Date(this.getBrowserTimestamp(e))},getBrowserTimestamp:function(e){e=parseInt(e,10);var t=new Date(e*1e3).getTimezoneOffset()*60;return(parseInt(e,10)+parseInt(this._getMessage("SERVER_TZ_OFFSET"))+t)*1e3},getServerTimestamp:function(e){e=parseInt(e,10);var t=new Date(e).getTimezoneOffset()*60;return Math.round(e/1e3-(parseInt(this._getMessage("SERVER_TZ_OFFSET"),10)+parseInt(t,10)))},formatLastActivityDate:function(e,t,r){var _=this.isAmPmMode(true);var n=_===this.AM_PM_MODE.LOWER?"g:i a":_===this.AM_PM_MODE.UPPER?"g:i A":"H:i";var a=[["tomorrow","#01#"+n],["now","#02#"],["todayFuture","#03#"+n],["yesterday","#04#"+n],["-",this.convertBitrixFormat(this._getMessage("FORMAT_DATETIME")).replace(/:s/g,"")],["s60","sago"],["i60","iago"],["H5","Hago"],["H24","#03#"+n],["d31","dago"],["m12>1","mago"],["m12>0","dago"],["","#05#"]];var D=this.format(a,e,t,r);var i=null;if((i=/^#(\d+)#(.*)/.exec(D))!=null){switch(i[1]){case"01":D=this._getMessage("FD_LAST_SEEN_TOMORROW").replace("#TIME#",i[2]);break;case"02":D=this._getMessage("FD_LAST_SEEN_NOW");break;case"03":D=this._getMessage("FD_LAST_SEEN_TODAY").replace("#TIME#",i[2]);break;case"04":D=this._getMessage("FD_LAST_SEEN_YESTERDAY").replace("#TIME#",i[2]);break;case"05":D=this._getMessage("FD_LAST_SEEN_MORE_YEAR");break;default:D=i[2];break}}return D},isAmPmMode:function(e){if(e===true){return this._getMessage("AMPM_MODE")}return this._getMessage("AMPM_MODE")!==false},_getMessage:function(e){return t.message(e)},parse:function(e,t,_,n){if(r.isNotEmptyString(e)){if(!_)_=this._getMessage("FORMAT_DATE");if(!n)n=this._getMessage("FORMAT_DATETIME");var a="";for(o=1;o<=12;o++){a=a+"|"+this._getMessage("MON_"+o)}var D=new RegExp("([0-9]+|[a-z]+"+a+")","ig"),i=e.match(D),s=_.match(/(DD|MI|MMMM|MM|M|YYYY)/gi),o,M,u=[],f=[],O={};if(!i){return null}if(i.length>s.length){s=n.match(/(DD|MI|MMMM|MM|M|YYYY|HH|H|SS|TT|T|GG|G)/gi)}for(o=0,M=i.length;o<M;o++){if(i[o].trim()!==""){u[u.length]=i[o]}}for(o=0,M=s.length;o<M;o++){if(s[o].trim()!==""){f[f.length]=s[o]}}var g=r.array_search("MMMM",f);if(g>0){u[g]=this.getMonthIndex(u[g]);f[g]="MM"}else{g=r.array_search("M",f);if(g>0){u[g]=this.getMonthIndex(u[g]);f[g]="MM"}}for(o=0,M=f.length;o<M;o++){var F=f[o].toUpperCase();O[F]=F==="T"||F==="TT"?u[o]:parseInt(u[o],10)}if(O["DD"]>0&&O["MM"]>0&&O["YYYY"]>0){var c=new Date;if(t){c.setUTCDate(1);c.setUTCFullYear(O["YYYY"]);c.setUTCMonth(O["MM"]-1);c.setUTCDate(O["DD"]);c.setUTCHours(0,0,0,0)}else{c.setDate(1);c.setFullYear(O["YYYY"]);c.setMonth(O["MM"]-1);c.setDate(O["DD"]);c.setHours(0,0,0,0)}if((!isNaN(O["HH"])||!isNaN(O["GG"])||!isNaN(O["H"])||!isNaN(O["G"]))&&!isNaN(O["MI"])){if(!isNaN(O["H"])||!isNaN(O["G"])){var T=(O["T"]||O["TT"]||"am").toUpperCase()==="PM",E=parseInt(O["H"]||O["G"]||0,10);if(T){O["HH"]=E+(E===12?0:12)}else{O["HH"]=E<12?E:0}}else{O["HH"]=parseInt(O["HH"]||O["GG"]||0,10)}if(isNaN(O["SS"]))O["SS"]=0;if(t){c.setUTCHours(O["HH"],O["MI"],O["SS"])}else{c.setHours(O["HH"],O["MI"],O["SS"])}}return c}}return null},getMonthIndex:function(e){var t,r=e.toUpperCase(),_=["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"],n=["january","february","march","april","may","june","july","august","september","october","november","december"];for(t=1;t<=12;t++){if(r===this._getMessage("MON_"+t).toUpperCase()||r===this._getMessage("MONTH_"+t).toUpperCase()||r===_[t-1].toUpperCase()||r===n[t-1].toUpperCase()){return t}}return e}};var r={isDate:function(e){return e&&Object.prototype.toString.call(e)=="[object Date]"},isNumber:function(e){return e===0?true:e?typeof e=="number"||e instanceof Number:false},isArray:function(e){return e&&Object.prototype.toString.call(e)=="[object Array]"},isString:function(e){return e===""?true:e?typeof e=="string"||e instanceof String:false},isNotEmptyString:function(e){return this.isString(e)?e.length>0:false},strPadLeft:function(e,t,r){var _=e.length,n=r.length;if(_>=t)return e;for(;_<t;_+=n)e=r+e;return e},array_search:function(e,t){for(var r=0;r<t.length;r++){if(t[r]==e)return r}return-1}}})(window);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:53:"/bitrix/js/main/core/core_date.min.js?158748973724935";s:6:"source";s:33:"/bitrix/js/main/core/core_date.js";s:3:"min";s:37:"/bitrix/js/main/core/core_date.min.js";s:3:"map";s:37:"/bitrix/js/main/core/core_date.map.js";}"*/
(function(){if(BX.date)return;BX.date=BX.Main.Date;var e=null;BX.calendar=function(e){return BX.calendar.get().Show(e)};BX.calendar.get=function(){if(!e)e=new BX.JCCalendar;return e};BX.calendar.InsertDaysBack=function(e,t){if(t!=""){var a=new Date;if(t>0){a.setTime(a.valueOf()-t*864e5)}e.value=BX.date.format(BX.date.convertBitrixFormat(BX.message("FORMAT_DATE")),a,null)}else{e.value=""}};BX.calendar.ValueToString=function(e,t,a){return BX.date.format(BX.date.convertBitrixFormat(BX.message(t?"FORMAT_DATETIME":"FORMAT_DATE")),e,null,!!a)};BX.calendar.ValueToStringFormat=function(e,t,a){return BX.date.format(BX.date.convertBitrixFormat(t),e,null,!!a)};BX.CalendarPeriod={Init:function(e,t,a){if((e.value!=""||t.value!="")&&a.value=="")a.value="interval";a.onchange()},ChangeDirectOpts:function(e,t){var a=BX.findChild(t,{className:"adm-select adm-calendar-direction"},true);if(e=="week"){a.options[0].text=BX.message("JSADM_CALEND_PREV_WEEK");a.options[1].text=BX.message("JSADM_CALEND_CURR_WEEK");a.options[2].text=BX.message("JSADM_CALEND_NEXT_WEEK")}else{a.options[0].text=BX.message("JSADM_CALEND_PREV");a.options[1].text=BX.message("JSADM_CALEND_CURR");a.options[2].text=BX.message("JSADM_CALEND_NEXT")}},SaveAndClearInput:function(e){if(!window.SavedPeriodValues)window.SavedPeriodValues={};window.SavedPeriodValues[e.id]=e.value;e.value=""},RestoreInput:function(e){if(!window.SavedPeriodValues||!window.SavedPeriodValues[e.id])return;e.value=window.SavedPeriodValues[e.id];delete window.SavedPeriodValues[e.id]},OnChangeP:function(e){var t=e.parentNode.parentNode;var a,s,i,n;a=s=i=n=false;var r=BX.findChild(t,{className:"adm-input-wrap adm-calendar-inp adm-calendar-first"});var o=BX.findChild(t,{className:"adm-input-wrap adm-calendar-second"});var l=BX.findChild(t,{className:"adm-select-wrap adm-calendar-direction"});var p=BX.findChild(t,{className:"adm-calendar-separate"});var c=BX.findChild(t,{className:"adm-input adm-calendar-from"},true);var h=BX.findChild(t,{className:"adm-input adm-calendar-to"},true);switch(e.value){case"day":case"week":case"month":case"quarter":case"year":i=true;BX.CalendarPeriod.OnChangeD(l.children[0]);break;case"before":s=true;break;case"after":a=true;break;case"exact":a=true;break;case"interval":a=s=n=true;BX.CalendarPeriod.RestoreInput(c);BX.CalendarPeriod.RestoreInput(h);break;case"":BX.CalendarPeriod.SaveAndClearInput(c);BX.CalendarPeriod.SaveAndClearInput(h);break;default:break}BX.CalendarPeriod.ChangeDirectOpts(e.value,t);r.style.display=a?"inline-block":"none";o.style.display=s?"inline-block":"none";l.style.display=i?"inline-block":"none";p.style.display=n?"inline-block":"none"},OnChangeD:function(e){var t=e.parentNode.parentNode;var a=BX.findChild(t,{className:"adm-input adm-calendar-from"},true);var s=BX.findChild(t,{className:"adm-input adm-calendar-to"},true);var i=BX.findChild(t,{className:"adm-select adm-calendar-period"},true);var n=0;switch(e.value){case"previous":n=-1;break;case"next":n=1;break;case"current":default:break}var r=false;var o=false;var l=new Date;var p=l.getFullYear();var c=l.getMonth();var h=l.getDate();var u=l.getDay();if(u==0)u=7;switch(i.value){case"day":r=new Date(p,c,h+n,0,0,0);o=new Date(p,c,h+n,23,59,59);break;case"week":r=new Date(p,c,h-u+1+n*7,0,0,0);o=new Date(p,c,h+(7-u)+n*7,23,59,59);break;case"month":r=new Date(p,c+n,1,0,0,0);o=new Date(p,c+1+n,0,23,59,59);break;case"quarter":var d=Math.floor(c/3)+n;r=new Date(p,3*d,1,0,0,0);o=new Date(p,3*(d+1),0,23,59,59);break;case"year":r=new Date(p+n,0,1,0,0,0);o=new Date(p+1+n,0,0,23,59,59);break;default:break}var m=window[a.name+"_bTime"]?BX.message("FORMAT_DATETIME"):BX.message("FORMAT_DATE");if(r){a.value=BX.formatDate(r,m);BX.addClass(a,"adm-calendar-inp-setted")}if(o){s.value=BX.formatDate(o,m);BX.addClass(s,"adm-calendar-inp-setted")}}};BX.JCCalendar=function(){this.params={};this.bAmPm=BX.isAmPmMode();this.popup=null;this.popup_month=null;this.popup_year=null;this.month_popup_classname="";this.year_popup_classname="";this.value=null;this.control_id=Math.random();this._layers={};this._current_layer=null;this.DIV=null;this.PARTS={};this.weekStart=0;this.numRows=6;this._create=function(e){this.popup=new BX.PopupWindow("calendar_popup_"+this.control_id,e.node,{closeByEsc:true,autoHide:false,content:this._get_content(),zIndex:3e3,bindOptions:{forceBindPosition:true}});BX.bind(this.popup.popupContainer,"click",function(e){e.stopPropagation()})};this._auto_hide_disable=function(){BX.unbind(document,"click",BX.proxy(this._auto_hide,this))};this._auto_hide_enable=function(){BX.bind(document,"click",BX.proxy(this._auto_hide,this))};this._auto_hide=function(e){this._auto_hide_disable();this.popup.close()};this._get_content=function(){var e=BX.delegate(function(e){e=e||window.event;this.SetDate(new Date(parseInt(BX.proxy_context.getAttribute("data-date"))),e.type=="dblclick")},this);this.DIV=BX.create("DIV",{props:{className:"bx-calendar"},children:[BX.create("DIV",{props:{className:"bx-calendar-header"},children:[BX.create("A",{attrs:{href:"javascript:void(0)"},props:{className:"bx-calendar-left-arrow"},events:{click:BX.proxy(this._prev,this)}}),BX.create("SPAN",{props:{className:"bx-calendar-header-content"},children:[this.PARTS.MONTH=BX.create("A",{attrs:{href:"javascript:void(0)"},props:{className:"bx-calendar-top-month"},events:{click:BX.proxy(this._menu_month,this)}}),this.PARTS.YEAR=BX.create("A",{attrs:{href:"javascript:void(0)"},props:{className:"bx-calendar-top-year"},events:{click:BX.proxy(this._menu_year,this)}})]}),BX.create("A",{attrs:{href:"javascript:void(0)"},props:{className:"bx-calendar-right-arrow"},events:{click:BX.proxy(this._next,this)}})]}),this.PARTS.WEEK=BX.create("DIV",{props:{className:"bx-calendar-name-day-wrap"}}),this.PARTS.LAYERS=BX.create("DIV",{props:{className:"bx-calendar-cell-block"},events:{click:BX.delegateEvent({className:"bx-calendar-cell"},e),dblclick:BX.delegateEvent({className:"bx-calendar-cell"},e)}}),this.PARTS.TIME=BX.create("DIV",{props:{className:"bx-calendar-set-time-wrap"},events:{click:BX.delegateEvent({attr:"data-action"},BX.delegate(this._time_actions,this))},html:'<a href="javascript:void(0)" data-action="time_show" class="bx-calendar-set-time"><i></i>'+BX.message("CAL_TIME_SET")+'</a><div class="bx-calendar-form-block"><span class="bx-calendar-form-text">'+BX.message("CAL_TIME")+'</span><span class="bx-calendar-form"><input type="text" class="bx-calendar-form-input" maxwidth="2" onkeyup="BX.calendar.get()._check_time()" /><span class="bx-calendar-form-separator"></span><input type="text" class="bx-calendar-form-input" maxwidth="2" onkeyup="BX.calendar.get()._check_time()" />'+(this.bAmPm?'<span class="bx-calendar-AM-PM-block"><span class="bx-calendar-AM-PM-text" data-action="time_ampm"></span><span class="bx-calendar-form-arrow-r"><a href="javascript:void(0)" class="bx-calendar-form-arrow-top" data-action="time_ampm_up"><i></i></a><a href="javascript:void(0)" class="bx-calendar-form-arrow-bottom" data-action="time_ampm_down"><i></i></a></span></span>':"")+'</span><a href="javascript:void(0)" data-action="time_hide" class="bx-calendar-form-close"><i></i></a></div>'}),BX.create("DIV",{props:{className:"bx-calendar-button-block"},events:{click:BX.delegateEvent({attr:"data-action"},BX.delegate(this._button_actions,this))},html:'<a href="javascript:void(0)" class="bx-calendar-button bx-calendar-button-select" data-action="submit"><span class="bx-calendar-button-left"></span><span class="bx-calendar-button-text">'+BX.message("CAL_BUTTON")+'</span><span class="bx-calendar-button-right"></span></a><a href="javascript:void(0)" class="bx-calendar-button bx-calendar-button-cancel" data-action="cancel"><span class="bx-calendar-button-left"></span><span class="bx-calendar-button-text">'+BX.message("JS_CORE_WINDOW_CLOSE")+'</span><span class="bx-calendar-button-right"></span></a>'})]});this.PARTS.TIME_INPUT_H=BX.findChild(this.PARTS.TIME,{tag:"INPUT"},true);this.PARTS.TIME_INPUT_M=this.PARTS.TIME_INPUT_H.nextSibling.nextSibling;if(this.bAmPm)this.PARTS.TIME_AMPM=this.PARTS.TIME_INPUT_M.nextSibling.firstChild;var t=new BX.JCSpinner({input:this.PARTS.TIME_INPUT_H,callback_change:BX.proxy(this._check_time,this),bSaveValue:false}).Show();t.className="bx-calendar-form-arrow-l";this.PARTS.TIME_INPUT_H.parentNode.insertBefore(t,this.PARTS.TIME_INPUT_H);t=new BX.JCSpinner({input:this.PARTS.TIME_INPUT_M,callback_change:BX.proxy(this._check_time,this),bSaveValue:true}).Show();t.className="bx-calendar-form-arrow-r";if(!this.PARTS.TIME_INPUT_M.nextSibling)this.PARTS.TIME_INPUT_M.parentNode.appendChild(t);else this.PARTS.TIME_INPUT_M.parentNode.insertBefore(t,this.PARTS.TIME_INPUT_M.nextSibling);for(var a=0;a<7;a++){this.PARTS.WEEK.appendChild(BX.create("SPAN",{props:{className:"bx-calendar-name-day"},text:BX.message("DOW_"+(a+this.weekStart)%7)}))}return this.DIV};this._time_actions=function(){switch(BX.proxy_context.getAttribute("data-action")){case"time_show":BX.addClass(this.PARTS.TIME,"bx-calendar-set-time-opened");this.popup.adjustPosition();break;case"time_hide":BX.removeClass(this.PARTS.TIME,"bx-calendar-set-time-opened");this.popup.adjustPosition();break;case"time_ampm":this.PARTS.TIME_AMPM.innerHTML=this.PARTS.TIME_AMPM.innerHTML=="AM"?"PM":"AM";break;case"time_ampm_up":this._check_time({bSaveValue:false},null,12);return;break;case"time_ampm_down":this._check_time({bSaveValue:false},null,-12);return;break}this._check_time()};this._button_actions=function(){switch(BX.proxy_context.getAttribute("data-action")){case"submit":this.SaveValue();break;case"cancel":this.Close();break}};this._check_time=function(e,t,a){var s=parseInt(this.PARTS.TIME_INPUT_H.value.substring(0,5),10)||0,i=parseInt(this.PARTS.TIME_INPUT_M.value.substring(0,5),10)||0,n=false;if(!!e&&!e.bSaveValue){this.value.setUTCHours(this.value.getUTCHours()+a)}else if(!isNaN(s)){if(this.bAmPm){if(s!=12&&this.PARTS.TIME_AMPM.innerHTML=="PM"){s+=12}}n=true;this.value.setUTCHours(s%24)}if(!isNaN(i)){n=true;this.value.setUTCMinutes(i%60)}if(n){this.SetValue(this.value)}};this._set_layer=function(){var e=parseInt(this.value.getUTCFullYear()+""+BX.util.str_pad_left(this.value.getUTCMonth()+"",2,"0"));if(!this._layers[e]){this._layers[e]=this._create_layer();this._layers[e].BXLAYERID=e}if(this._current_layer){var t=new Date(this.value.valueOf());t.setUTCHours(0);t.setUTCMinutes(0);var a=BX.findChild(this._layers[e],{tag:"A",className:"bx-calendar-active"},true),s=BX.findChild(this._layers[e],{tag:"A",attr:{"data-date":t.valueOf()+""}},true);if(a){BX.removeClass(a,"bx-calendar-active")}if(s){BX.addClass(s,"bx-calendar-active")}this._replace_layer(this._current_layer,this._layers[e])}else{this.PARTS.LAYERS.appendChild(this._layers[e])}this._current_layer=this._layers[e]};this._replace_layer=function(e,t){if(e!=t){if(!BX.browser.IsIE()||BX.browser.IsDoctype()){var a=e.BXLAYERID>t.BXLAYERID?1:-1;var s=0;var i=-a*e.offsetHeight;e.style.position="relative";e.style.top="0px";e.style.zIndex=5;t.style.position="absolute";t.style.top=i+"px";t.style.zIndex=6;this.PARTS.LAYERS.appendChild(t);var n=15;var r;(r=function(){i+=a*n;s+=a*n;if(a*i<0){e.style.top=s+"px";t.style.top=i+"px";setTimeout(r,10)}else{e.parentNode.removeChild(e);t.style.top="0px";t.style.position="static";t.style.zIndex=0}})()}else{this.PARTS.LAYERS.replaceChild(t,e)}}};this._create_layer=function(){var e=BX.create("DIV",{props:{className:"bx-calendar-layer"}});var t=new Date(this.value);t.setUTCHours(0);t.setUTCMinutes(0);t.setUTCDate(1);if(t.getUTCDay()!=this.weekStart){var a=t.getUTCDay()-this.weekStart;a+=a<0?7:0;t.setUTCDate(t.getUTCDate()-a)}var s=this.value.getUTCMonth(),i=this.value.getUTCDate(),n="";for(var r=0;r<this.numRows;r++){n+='<div class="bx-calendar-range'+(r==this.numRows-1?" bx-calendar-range-noline":"")+'">';for(var o=0;o<7;o++){a=t.getUTCDate();var l=t.getUTCDay();var p="bx-calendar-cell";if(s!=t.getUTCMonth())p+=" bx-calendar-date-hidden";else if(i==a)p+=" bx-calendar-active";if(l==0||l==6)p+=" bx-calendar-weekend";n+='<a href="javascript:void(0)" class="'+p+'" data-date="'+t.valueOf()+'">'+a+"</a>";t.setUTCDate(t.getUTCDate()+1)}n+="</div>"}e.innerHTML=n;return e};this._prev=function(){this.SetMonth(this.value.getUTCMonth()-1)};this._next=function(){this.SetMonth(this.value.getUTCMonth()+1)};this._menu_month_content=function(){var e="",t=this.value.getMonth(),a;for(a=0;a<12;a++){e+='<span class="bx-calendar-month'+(a==t?" bx-calendar-month-active":"")+'" data-bx-month="'+a+'">'+BX.message("MONTH_"+(a+1))+"</span>"}return'<div class="bx-calendar-month-popup"><div class="bx-calendar-month-title" data-bx-month="'+this.value.getUTCMonth()+'">'+BX.message("MONTH_"+(this.value.getUTCMonth()+1))+'</div><div class="bx-calendar-month-content">'+e+"</div></div>"};this._menu_month=function(){if(!this.popup_month){this.popup_month=new BX.PopupWindow("calendar_popup_month_"+this.control_id,this.PARTS.MONTH,{content:this._menu_month_content(),zIndex:3001,closeByEsc:true,autoHide:true,offsetTop:-29,offsetLeft:-1,className:this.month_popup_classname,events:{onPopupShow:BX.delegate(function(){if(this.popup_year){this.popup_year.close()}},this)}});BX.bind(this.popup_month.popupContainer,"click",BX.proxy(this.month_popup_click,this));this.popup_month.BXMONTH=this.value.getUTCMonth()}else if(this.popup_month.BXMONTH!=this.value.getUTCMonth()){this.popup_month.setContent(this._menu_month_content());this.popup_month.BXMONTH=this.value.getUTCMonth()}this.popup_month.show()};this.month_popup_click=function(e){var t=e.target||e.srcElement;if(t&&t.getAttribute&&t.getAttribute("data-bx-month")){this.SetMonth(parseInt(t.getAttribute("data-bx-month")));this.popup_month.close()}};this._menu_year_content=function(){var e='<div class="bx-calendar-year-popup"><div class="bx-calendar-year-title" data-bx-year="'+this.value.getUTCFullYear()+'">'+this.value.getUTCFullYear()+'</div><div class="bx-calendar-year-content" id="bx-calendar-year-content">';for(var t=-3;t<=3;t++){e+='<span class="bx-calendar-year-number'+(t==0?" bx-calendar-year-active":"")+'" data-bx-year="'+(this.value.getUTCFullYear()-t)+'">'+(this.value.getUTCFullYear()-t)+"</span>"}e+='</div><input data-bx-year-input="Y" type="text" class="bx-calendar-year-input" maxlength="4" /></div>';return e};this._menu_year=function(){if(!this.popup_year){this.popup_year=new BX.PopupWindow("calendar_popup_year_"+this.control_id,this.PARTS.YEAR,{content:this._menu_year_content(),zIndex:3001,closeByEsc:true,autoHide:true,offsetTop:-29,offsetLeft:-1,className:this.year_popup_classname,events:{onPopupShow:BX.delegate(function(){if(this.popup_month){this.popup_month.close()}},this)}});BX.bind(this.popup_year.popupContainer,"click",BX.proxy(this.year_popup_click,this));BX.bind(this.popup_year.popupContainer,"keyup",BX.proxy(this.year_popup_keyup,this));this.popup_year.BXYEAR=this.value.getUTCFullYear()}else if(this.popup_year.BXYEAR!=this.value.getUTCFullYear()){this.popup_year.setContent(this._menu_year_content());this.popup_year.BXYEAR=this.value.getUTCFullYear()}this.popup_year.show()};this.year_popup_click=function(e){var t=e.target||e.srcElement;if(t&&t.getAttribute&&t.getAttribute("data-bx-year")){this.SetYear(parseInt(t.getAttribute("data-bx-year")));this.popup_year.close()}};this.year_popup_keyup=function(e){var t=e.target||e.srcElement;if(t&&t.getAttribute&&t.getAttribute("data-bx-year-input")=="Y"){var a=parseInt(t.value);if(a>=1900&&a<=2100){this.SetYear(a);this.popup_year.close()}}};this._check_date=function(e){var t=e;if(BX.type.isString(e)){t=BX.parseDate(e,true)}if(!BX.type.isDate(t)||isNaN(t.valueOf())){t=BX.date.convertToUTC(new Date);if(this.params.bHideTime){t.setUTCHours(0);t.setUTCMinutes(0)}}t.setUTCMilliseconds(0);t.setUTCSeconds(0);t.BXCHECKED=true;return t}};BX.JCCalendar.prototype.Show=function(e){if(!BX.isReady){BX.ready(BX.delegate(function(){this.Show(e)},this));return}e.node=e.node||document.body;if(BX.type.isNotEmptyString(e.node)){var t=BX(e.node);if(!t){t=document.getElementsByName(e.node);if(t&&t.length>0){t=t[0]}}e.node=t}if(!e.node)return;if(!!e.field){if(BX.type.isString(e.field)){t=BX(e.field);if(!!t){e.field=t}else{if(e.form){if(BX.type.isString(e.form)){e.form=document.forms[e.form]}}if(BX.type.isDomNode(e.form)&&!!e.form[e.field]){e.field=e.form[e.field]}else{t=document.getElementsByName(e.field);if(t&&t.length>0){t=t[0];e.field=t}}}if(BX.type.isString(e.field)){e.field=BX(e.field)}}}var a=!this.popup||!this.popup.isShown()||this.params.node!=e.node;this.params=e;this.params.bTime=typeof this.params.bTime=="undefined"?true:!!this.params.bTime;this.params.bHideTime=typeof this.params.bHideTime=="undefined"?true:!!this.params.bHideTime;this.params.bUseSecond=typeof this.params.bUseSecond=="undefined"?true:!!this.params.bUseSecond;this.weekStart=parseInt(this.params.weekStart||this.params.weekStart||BX.message("WEEK_START"));if(isNaN(this.weekStart))this.weekStart=1;if(!this.popup){this._create(this.params)}else{this.popup.setBindElement(this.params.node)}var s=!!this.params.bHideTime;if(this.params.value){this.SetValue(this.params.value);s=this.value.getUTCHours()<=0&&this.value.getUTCMinutes()<=0}else if(this.params.field){this.SetValue(this.params.field.value);s=this.value.getUTCHours()<=0&&this.value.getUTCMinutes()<=0}else if(!!this.params.currentTime){this.SetValue(this.params.currentTime)}else{this.SetValue()}if(!!this.params.bTime)BX.removeClass(this.DIV,"bx-calendar-time-disabled");else BX.addClass(this.DIV,"bx-calendar-time-disabled");if(!!s)BX.removeClass(this.PARTS.TIME,"bx-calendar-set-time-opened");else BX.addClass(this.PARTS.TIME,"bx-calendar-set-time-opened");if(a){this._auto_hide_disable();this.popup.show();setTimeout(BX.proxy(this._auto_hide_enable,this),0)}this.params.bSetFocus=typeof this.params.bSetFocus=="undefined"?true:!!this.params.bSetFocus;if(this.params.bSetFocus){e.node.blur()}else{BX.bind(e.node,"keyup",BX.defer(function(){this.SetValue(e.node.value);if(!!this.params.bTime){if(this.value.getUTCHours()<=0&&this.value.getUTCMinutes()<=0)BX.removeClass(this.PARTS.TIME,"bx-calendar-set-time-opened");else BX.addClass(this.PARTS.TIME,"bx-calendar-set-time-opened")}},this))}return this};BX.JCCalendar.prototype.SetDay=function(e){this.value.setUTCDate(e);return this.SetValue(this.value)};BX.JCCalendar.prototype.SetMonth=function(e){if(this.popup_month)this.popup_month.close();this.value.setUTCMonth(e);if(e<0)e+=12;else if(e>=12)e-=12;while(this.value.getUTCMonth()>e){this.value.setUTCDate(this.value.getUTCDate()-1)}return this.SetValue(this.value)};BX.JCCalendar.prototype.SetYear=function(e){if(this.popup_year)this.popup_year.close();this.value.setUTCFullYear(e);return this.SetValue(this.value)};BX.JCCalendar.prototype.SetDate=function(e,t){e=this._check_date(e);e.setUTCHours(this.value.getUTCHours());e.setUTCMinutes(this.value.getUTCMinutes());e.setUTCSeconds(this.value.getUTCSeconds());if(this.params.bTime&&!t){return this.SetValue(e)}else{this.SetValue(e);this.SaveValue()}};BX.JCCalendar.prototype.SetValue=function(e){this.value=e&&e.BXCHECKED?e:this._check_date(e);this.PARTS.MONTH.innerHTML=BX.message("MONTH_"+(this.value.getUTCMonth()+1));this.PARTS.YEAR.innerHTML=this.value.getUTCFullYear();if(!!this.params.bTime){var t=this.value.getUTCHours();if(this.bAmPm){if(t>=12){this.PARTS.TIME_AMPM.innerHTML="PM";if(t!=12)t-=12}else{this.PARTS.TIME_AMPM.innerHTML="AM";if(t==0)t=12}}this.PARTS.TIME_INPUT_H.value=BX.util.str_pad_left(t.toString(),2,"0");this.PARTS.TIME_INPUT_M.value=BX.util.str_pad_left(this.value.getUTCMinutes().toString(),2,"0")}this._set_layer();return this};BX.JCCalendar.prototype.SaveValue=function(){if(this.popup_month)this.popup_month.close();if(this.popup_year)this.popup_year.close();var e=true;if(!!this.params.callback){var t=this.params.callback.apply(this,[new Date(this.value.valueOf()+this.value.getTimezoneOffset()*6e4)]);if(t===false)e=false}if(e){var a=!!this.params.bTime&&BX.hasClass(this.PARTS.TIME,"bx-calendar-set-time-opened");if(this.params.field){var s=BX.message(a?"FORMAT_DATETIME":"FORMAT_DATE");if(a&&!this.params.bUseSecond){s=s.replace(":SS","")}this.params.field.value=BX.calendar.ValueToStringFormat(this.value,s,true);BX.fireEvent(this.params.field,"change")}this.popup.close();if(!!this.params.callback_after){this.params.callback_after.apply(this,[new Date(this.value.valueOf()+this.value.getTimezoneOffset()*6e4),a])}}return this};BX.JCCalendar.prototype.Close=function(){if(!!this.popup)this.popup.close();return this};BX.JCSpinner=function(e){e=e||{};this.params={input:e.input||null,delta:e.delta||1,timeout_start:e.timeout_start||1e3,timeout_cont:e.timeout_cont||150,callback_start:e.callback_start||null,callback_change:e.callback_change||null,callback_finish:e.callback_finish||null,bSaveValue:typeof e.bSaveValue=="undefined"?!!e.input:!!e.bSaveValue};this.mousedown=false;this.direction=1};BX.JCSpinner.prototype.Show=function(){this.node=BX.create("span",{events:{mousedown:BX.delegateEvent({attr:"data-dir"},BX.delegate(this.Start,this))},html:'<a href="javascript:void(0)" class="bx-calendar-form-arrow bx-calendar-form-arrow-top" data-dir="1"><i></i></a><a href="javascript:void(0)" class="bx-calendar-form-arrow bx-calendar-form-arrow-bottom" data-dir="-1"><i></i></a>'});return this.node};BX.JCSpinner.prototype.Start=function(){this.mousedown=true;this.direction=BX.proxy_context.getAttribute("data-dir")>0?1:-1;BX.bind(document,"mouseup",BX.proxy(this.MouseUp,this));this.ChangeValue(true)};BX.JCSpinner.prototype.ChangeValue=function(e){if(!this.mousedown)return;if(this.params.input){var t=parseInt(this.params.input.value,10)+this.params.delta*this.direction;if(this.params.bSaveValue)this.params.input.value=t;if(!!e&&this.params.callback_start)this.params.callback_start(this.params,t,this.direction);if(this.params.callback_change)this.params.callback_change(this.params,t,this.direction);setTimeout(BX.proxy(this.ChangeValue,this),!!e?this.params.timeout_start:this.params.timeout_cont)}};BX.JCSpinner.prototype.MouseUp=function(){this.mousedown=false;BX.unbind(document,"mouseup",BX.proxy(this.MouseUp,this));if(this.params.callback_finish)this.params.callback_finish(this.params,this.params.input.value)};window.jsCalendar={Show:function(e,t,a,s,i,n,r,o){return BX.calendar({node:e,field:t,form:r,bTime:!!i,currentTime:n,bHideTimebar:!!o})},ValueToString:BX.calendar.ValueToString};BX.CClockSelector=function(e){this.params=e;this.params.popup_buttons=this.params.popup_buttons||[new BX.PopupWindowButton({text:BX.message("CAL_BUTTON"),className:"popup-window-button-create",events:{click:BX.proxy(this.setValue,this)}})];this.isReady=false;this.WND=new BX.PopupWindow(this.params.popup_id||"clock_selector_popup",this.params.node,this.params.popup_config||{titleBar:BX.message("CAL_TIME"),offsetLeft:-45,offsetTop:-135,autoHide:true,closeIcon:true,closeByEsc:true,zIndex:this.params.zIndex});this.SHOW=false;BX.addCustomEvent(this.WND,"onPopupClose",BX.delegate(this.onPopupClose,this));this.obClocks={};this.CLOCK_ID=this.params.clock_id||"clock_selector"};BX.CClockSelector.prototype.Show=function(){if(!this.isReady){BX.addCustomEvent("onClockRegister",BX.proxy(this.onClockRegister,this));return BX.ajax.get("/bitrix/tools/clock_selector.php",{start_time:this.params.start_time,clock_id:this.CLOCK_ID,sessid:BX.bitrix_sessid()},BX.delegate(this.Ready,this))}this.WND.setButtons(this.params.popup_buttons);this.WND.show();this.SHOW=true;if(window["bxClock_"+this.obClocks[this.CLOCK_ID]]){setTimeout("window['bxClock_"+this.obClocks[this.CLOCK_ID]+"'].CalculateCoordinates()",40)}return true};BX.CClockSelector.prototype.onClockRegister=function(e){if(e[this.CLOCK_ID]){this.obClocks[this.CLOCK_ID]=e[this.CLOCK_ID];BX.removeCustomEvent("onClockRegister",BX.proxy(this.onClockRegister,this))}};BX.CClockSelector.prototype.Ready=function(e){this.content=this.CreateContent(e);this.WND.setContent(this.content);this.isReady=true;setTimeout(BX.proxy(this.Show,this),30)};BX.CClockSelector.prototype.CreateContent=function(e){return BX.create("DIV",{events:{click:BX.PreventDefault},html:'<div class="bx-tm-popup-clock">'+e+"</div>"})};BX.CClockSelector.prototype.setValue=function(e){if(this.params.callback){var t=BX.findChild(this.content,{tagName:"INPUT"},true);this.params.callback.apply(this.params.node,[t.value])}return BX.PreventDefault(e)};BX.CClockSelector.prototype.closeWnd=function(e){this.WND.close();return e||window.event?BX.PreventDefault(e):true};BX.CClockSelector.prototype.setNode=function(e){this.WND.setBindElement(e)};BX.CClockSelector.prototype.setTime=function(e){this.params.start_time=e;if(window["bxClock_"+this.obClocks[this.CLOCK_ID]]){window["bxClock_"+this.obClocks[this.CLOCK_ID]].SetTime(parseInt(e/3600),parseInt(e%3600/60))}};BX.CClockSelector.prototype.setCallback=function(e){this.params.callback=e};BX.CClockSelector.prototype.onPopupClose=function(){this.SHOW=false}})();
/* End */
;
; /* Start:"a:4:{s:4:"full";s:44:"/bitrix/js/main/utils.min.js?158748973419858";s:6:"source";s:24:"/bitrix/js/main/utils.js";s:3:"min";s:28:"/bitrix/js/main/utils.min.js";s:3:"map";s:28:"/bitrix/js/main/utils.map.js";}"*/
var phpVars;if(!phpVars){phpVars={ADMIN_THEME_ID:".default",LANGUAGE_ID:"en",FORMAT_DATE:"DD.MM.YYYY",FORMAT_DATETIME:"DD.MM.YYYY HH:MI:SS",opt_context_ctrl:false,cookiePrefix:"BITRIX_SM",titlePrefix:"",bitrix_sessid:"",messHideMenu:"",messShowMenu:"",messHideButtons:"",messShowButtons:"",messFilterInactive:"",messFilterActive:"",messFilterLess:"",messLoading:"Loading...",messMenuLoading:"",messMenuLoadingTitle:"",messNoData:"",messExpandTabs:"",messCollapseTabs:"",messPanelFixOn:"",messPanelFixOff:"",messPanelCollapse:"",messPanelExpand:""}}var jsUtils={arEvents:Array(),addEvent:function(e,t,n,i){if(e.attachEvent)e.attachEvent("on"+t,n);else if(e.addEventListener)e.addEventListener(t,n,false);else e["on"+t]=n;this.arEvents[this.arEvents.length]={element:e,event:t,fn:n}},removeEvent:function(e,t,n){if(e.detachEvent)e.detachEvent("on"+t,n);else if(e.removeEventListener)e.removeEventListener(t,n,false);else e["on"+t]=null},removeAllEvents:function(e){var t;for(t=0;t<this.arEvents.length;t++){if(this.arEvents[t]&&(e==false||e==this.arEvents[t].element)){jsUtils.removeEvent(this.arEvents[t].element,this.arEvents[t].event,this.arEvents[t].fn);this.arEvents[t]=null}}if(e==false)this.arEvents.length=0},IsDoctype:function(){if(document.compatMode)return document.compatMode=="CSS1Compat";if(document.documentElement&&document.documentElement.clientHeight)return true;return false},GetRealPos:function(e){if(window.BX)return BX.pos(e);if(!e||!e.offsetParent)return false;var t=Array();t["left"]=e.offsetLeft;t["top"]=e.offsetTop;var n=e.offsetParent;while(n&&n.tagName!="BODY"){t["left"]+=n.offsetLeft;t["top"]+=n.offsetTop;n=n.offsetParent}t["right"]=t["left"]+e.offsetWidth;t["bottom"]=t["top"]+e.offsetHeight;return t},FindChildObject:function(e,t,n,i){if(!e)return null;var o=t.toUpperCase();var s=n?n.toLowerCase():null;var r=e.childNodes.length;for(var l=0;l<r;l++){var a=e.childNodes[l];if(a.tagName&&a.tagName.toUpperCase()==o)if(!n||a.className.toLowerCase()==s)return a;if(i==true){var d;if(d=jsUtils.FindChildObject(a,t,n,true))return d}}return null},FindParentObject:function(e,t,n){if(!e)return null;var i=e;var o=t.toUpperCase();var s=n?n.toLowerCase():null;while(i.parentNode){var r=i.parentNode;if(r.tagName&&r.tagName.toUpperCase()==o)if(!n||r.className.toLowerCase()==s)return r;i=r}return null},FindNextSibling:function(e,t){if(!e)return null;var n=e;var i=t.toUpperCase();while(n.nextSibling){var o=n.nextSibling;if(o.tagName&&o.tagName.toUpperCase()==i)return o;n=o}return null},FindPreviousSibling:function(e,t){if(!e)return null;var n=e;var i=t.toUpperCase();while(n.previousSibling){var o=n.previousSibling;if(o.tagName&&o.tagName.toUpperCase()==i)return o;n=o}return null},bOpera:navigator.userAgent.toLowerCase().indexOf("opera")!=-1,bIsIE:document.attachEvent&&navigator.userAgent.toLowerCase().indexOf("opera")==-1,IsIE:function(){return this.bIsIE},IsOpera:function(){return this.bOpera},IsSafari:function(){var e=navigator.userAgent.toLowerCase();return/webkit/.test(e)},IsEditor:function(){var e=navigator.userAgent.toLowerCase();var t=(e.match(/.+(msie)[\/: ]([\d.]+)/)||[])[2];var n=/webkit/.test(e);if(this.IsOpera()||document.all&&!document.compatMode&&t<6||n)return false;return true},ToggleDiv:function(e){var t=document.getElementById(e).style;if(t.display!="none")t.display="none";else t.display="block";return t.display!="none"},urlencode:function(e){return escape(e).replace(new RegExp("\\+","g"),"%2B")},OpenWindow:function(e,t,n){var i=screen.width,o=screen.height;if(this.IsOpera()){i=document.body.offsetWidth;o=document.body.offsetHeight}window.open(e,"","status=no,scrollbars=yes,resizable=yes,width="+t+",height="+n+",top="+Math.floor((o-n)/2-14)+",left="+Math.floor((i-t)/2-5))},SetPageTitle:function(e){document.title=phpVars.titlePrefix+e;var t=document.getElementsByTagName("H1");if(t)t[0].innerHTML=e},LoadPageToDiv:function(e,t){var n=document.getElementById(t);if(!n)return;CHttpRequest.Action=function(e){CloseWaitWindow();document.getElementById(t).innerHTML=e};ShowWaitWindow();CHttpRequest.Send(e)},trim:function(e){if(typeof e=="string"||typeof e=="object"&&e.constructor==String){var t,n;n=/^[\s\r\n]+/g;t=e.replace(n,"");n=/[\s\r\n]+$/g;t=t.replace(n,"");return t}else return e},Redirect:function(e,t){var n=null,i=false;if(e&&e.length>0)n=e[0];if(!n)n=window.event;if(n)i=n.shiftKey;if(i)window.open(t);else{window.location.href=t}},False:function(){return false},AlignToPos:function(e,t,n){var i=e["left"],o=e["bottom"];var s=jsUtils.GetWindowScrollPos();var r=jsUtils.GetWindowInnerSize();if(r.innerWidth+s.scrollLeft-(e["left"]+t)<0){if(e["right"]-t>=0)i=e["right"]-t;else i=s.scrollLeft}if(r.innerHeight+s.scrollTop-(e["bottom"]+n)<0){if(e["top"]-n>=0)o=e["top"]-n;else o=s.scrollTop}return{left:i,top:o}},EvalGlobal:function(e){try{if(window.execScript)window.execScript(e,"javascript");else if(jsUtils.IsSafari())window.setTimeout(e,0);else window.eval(e)}catch(t){}},GetStyleValue:function(e,t){var n;if(e.currentStyle)n=e.currentStyle[t];else if(window.getComputedStyle)n=document.defaultView.getComputedStyle(e,null).getPropertyValue(t);if(!n)n="";return n},GetWindowInnerSize:function(e){var t,n;if(!e)e=document;if(self.innerHeight){t=self.innerWidth;n=self.innerHeight}else if(e.documentElement&&(e.documentElement.clientHeight||e.documentElement.clientWidth)){t=e.documentElement.clientWidth;n=e.documentElement.clientHeight}else if(e.body){t=e.body.clientWidth;n=e.body.clientHeight}return{innerWidth:t,innerHeight:n}},GetWindowScrollPos:function(e){var t,n;if(!e)e=document;if(self.pageYOffset){t=self.pageXOffset;n=self.pageYOffset}else if(e.documentElement&&(e.documentElement.scrollTop||e.documentElement.scrollLeft)){t=document.documentElement.scrollLeft;n=document.documentElement.scrollTop}else if(e.body){t=e.body.scrollLeft;n=e.body.scrollTop}return{scrollLeft:t,scrollTop:n}},GetWindowScrollSize:function(e){var t,n;if(!e)e=document;if(e.compatMode&&e.compatMode=="CSS1Compat"){t=e.documentElement.scrollWidth;n=e.documentElement.scrollHeight}else{if(e.body.scrollHeight>e.body.offsetHeight)n=e.body.scrollHeight;else n=e.body.offsetHeight;if(e.body.scrollWidth>e.body.offsetWidth||e.compatMode&&e.compatMode=="BackCompat"||e.documentElement&&!e.documentElement.clientWidth)t=e.body.scrollWidth;else t=e.body.offsetWidth}return{scrollWidth:t,scrollHeight:n}},GetWindowSize:function(){var e=jsUtils.GetWindowInnerSize();var t=jsUtils.GetWindowScrollPos();var n=jsUtils.GetWindowScrollSize();return{innerWidth:e.innerWidth,innerHeight:e.innerHeight,scrollLeft:t.scrollLeft,scrollTop:t.scrollTop,scrollWidth:n.scrollWidth,scrollHeight:n.scrollHeight}},arCustomEvents:{},addCustomEvent:function(e,t,n,i){if(!this.arCustomEvents[e])this.arCustomEvents[e]=[];if(!n)n=[];if(!i)i=false;this.arCustomEvents[e].push({handler:t,arParams:n,obj:i})},removeCustomEvent:function(e,t){if(!this.arCustomEvents[e])return;var n=this.arCustomEvents[e].length;if(n==1){delete this.arCustomEvents[e];return}for(var i=0;i<n;i++){if(!this.arCustomEvents[e][i])continue;if(this.arCustomEvents[e][i].handler==t){delete this.arCustomEvents[e][i];return}}},onCustomEvent:function(e,t){if(!this.arCustomEvents[e])return;if(!t)t=[];var n;for(var i=0,o=this.arCustomEvents[e].length;i<o;i++){n=this.arCustomEvents[e][i];if(!n||!n.handler)continue;if(n.obj)n.handler.call(n.obj,n.arParams,t);else n.handler(n.arParams,t)}},loadJSFile:function(e,t,n){if(!n)n=document;if(typeof e=="string")e=[e];var i=function(){if(!t)return;if(typeof t=="function")return t();if(typeof t!="object"||!t.func)return;var e=t.params||{};if(t.obj)t.func.apply(t.obj,e);else t.func(e)};var o=function(t){if(t>=e.length)return i();var s=n.body.appendChild(n.createElement("script"));s.src=e[t];var r=false;s.onload=s.onreadystatechange=function(){if(!r&&(!s.readyState||s.readyState=="loaded"||s.readyState=="complete")){r=true;setTimeout(function(){o(++t)},50)}}};o(0)},loadCSSFile:function(e,t,n){if(typeof e=="string"){var i=true;e=[e]}var o,s=e.length,r=[];if(s==0)return;if(!t)t=document;if(!n)n=window;if(!n.bxhead){var l=t.getElementsByTagName("HEAD");n.bxhead=l[0]}if(!n.bxhead)return;for(o=0;o<s;o++){var a=document.createElement("LINK");a.href=e[o];a.rel="stylesheet";a.type="text/css";n.bxhead.appendChild(a);r.push(a)}if(i)return a;return r},appendBXHint:function(e,t){if(!e||!e.parentNode||!t)return;var n=new BXHint(t);e.parentNode.insertBefore(n.oIcon,e);e.parentNode.removeChild(e);n.oIcon.style.marginLeft="5px"},PreventDefault:function(e){if(!e)e=window.event;if(e.stopPropagation){e.preventDefault();e.stopPropagation()}else{e.cancelBubble=true;e.returnValue=false}return false},CreateElement:function(e,t,n,i){if(!i)i=document;var o=i.createElement(e),s;if(t){for(s in t){if(s=="className"||s=="class"){o.setAttribute("class",t[s]);if(jsUtils.IsIE())o.setAttribute("className",t[s]);continue}if(t[s]!=undefined&&t[s]!=null)o.setAttribute(s,t[s])}}if(n){for(s in n)o.style[s]=n[s]}return o},in_array:function(e,t){for(var n=0;n<t.length;n++){if(t[n]==e)return true}return false},htmlspecialchars:function(e){if(!e.replace)return e;return e.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}};function JCFloatDiv(){var e=this;this.floatDiv=null;this.x=this.y=0;this.Create=function(e){var t=document.body.appendChild(document.createElement("DIV"));t.id=e.id;t.style.position="absolute";t.style.left="-10000px";t.style.top="-10000px";if(e.className)t.className=e.className;if(e.zIndex)t.style.zIndex=e.zIndex;if(e.width)t.style.width=e.width+"px";if(e.height)t.style.height=e.height+"px";return t};this.Show=function(e,t,n,i,o,s){if(s!==false)s=true;var r=parseInt(e.style.zIndex);if(r<=0||isNaN(r))r=100;e.style.zIndex=r;if(t<0)t=0;if(n<0)n=0;e.style.left=parseInt(t)+"px";e.style.top=parseInt(n)+"px";if(jsUtils.IsIE()&&s===true){var l=document.getElementById(e.id+"_frame");if(!l){l=document.createElement("IFRAME");l.src="javascript:''";l.id=e.id+"_frame";l.style.position="absolute";l.style.borderWidth="0px";l.style.zIndex=r-1;document.body.appendChild(l)}l.style.width=e.offsetWidth+"px";l.style.height=e.offsetHeight+"px";l.style.left=e.style.left;l.style.top=e.style.top;l.style.visibility="visible"}e.restrictDrag=o||false;if(isNaN(i))i=5;if(i>0){var a=document.getElementById(e.id+"_shadow");if(!a){if(jsUtils.IsIE()){a=document.createElement("DIV");a.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/bitrix/themes/"+phpVars.ADMIN_THEME_ID+"/images/shadow.png',sizingMethod='scale')"}else{a=document.createElement("IMG");a.src="/bitrix/themes/"+phpVars.ADMIN_THEME_ID+"/images/shadow.png"}a.id=e.id+"_shadow";a.style.position="absolute";a.style.zIndex=r-2;a.style.left="-1000px";a.style.top="-1000px";a.style.lineHeight="normal";a.className="bx-js-float-shadow";document.body.appendChild(a)}a.style.width=e.offsetWidth+"px";a.style.height=e.offsetHeight+"px";a.style.left=parseInt(e.style.left)+i+"px";a.style.top=parseInt(e.style.top)+i+"px";a.style.visibility="visible"}e.dxShadow=i};this.Close=function(e){if(!e)return;var t=document.getElementById(e.id+"_shadow");if(t)t.style.visibility="hidden";var n=document.getElementById(e.id+"_frame");if(n)n.style.visibility="hidden"};this.Move=function(e,t,n){if(!e)return;var i=e.dxShadow;var o=parseInt(e.style.left)+t;var s=parseInt(e.style.top)+n;if(e.restrictDrag){if(o<0)o=0;if(document.compatMode&&document.compatMode=="CSS1Compat")windowWidth=document.documentElement.scrollWidth;else{if(document.body.scrollWidth>document.body.offsetWidth||document.compatMode&&document.compatMode=="BackCompat"||document.documentElement&&!document.documentElement.clientWidth)windowWidth=document.body.scrollWidth;else windowWidth=document.body.offsetWidth}var r=e.offsetWidth;if(o>windowWidth-r-i)o=windowWidth-r-i;if(s<0)s=0}e.style.left=o+"px";e.style.top=s+"px";this.AdjustShadow(e)};this.HideShadow=function(e){var t=document.getElementById(e.id+"_shadow");t.style.visibility="hidden"};this.UnhideShadow=function(e){var t=document.getElementById(e.id+"_shadow");t.style.visibility="visible"};this.AdjustShadow=function(e){var t=document.getElementById(e.id+"_shadow");if(t&&t.style.visibility!="hidden"){var n=e.dxShadow;t.style.width=e.offsetWidth+"px";t.style.height=e.offsetHeight+"px";t.style.left=parseInt(e.style.left)+n+"px";t.style.top=parseInt(e.style.top)+n+"px"}var i=document.getElementById(e.id+"_frame");if(i){i.style.width=e.offsetWidth+"px";i.style.height=e.offsetHeight+"px";i.style.left=e.style.left;i.style.top=e.style.top}};this.StartDrag=function(t,n){if(!t)t=window.event;this.x=t.clientX+document.body.scrollLeft;this.y=t.clientY+document.body.scrollTop;this.floatDiv=n;jsUtils.addEvent(document,"mousemove",this.MoveDrag);document.onmouseup=this.StopDrag;if(document.body.setCapture)document.body.setCapture();document.onmousedown=jsUtils.False;var i=document.body;i.ondrag=jsUtils.False;i.onselectstart=jsUtils.False;i.style.MozUserSelect=e.floatDiv.style.MozUserSelect="none";i.style.cursor="move"};this.StopDrag=function(t){if(document.body.releaseCapture)document.body.releaseCapture();jsUtils.removeEvent(document,"mousemove",e.MoveDrag);document.onmouseup=null;this.floatDiv=null;document.onmousedown=null;var n=document.body;n.ondrag=null;n.onselectstart=null;n.style.MozUserSelect=e.floatDiv.style.MozUserSelect="";n.style.cursor=""};this.MoveDrag=function(t){var n=t.clientX+document.body.scrollLeft;var i=t.clientY+document.body.scrollTop;if(e.x==n&&e.y==i)return;e.Move(e.floatDiv,n-e.x,i-e.y);e.x=n;e.y=i}}var jsFloatDiv=new JCFloatDiv;var BXHint=function(e,t,n){this.oDivOver=false;this.timeOutID=null;this.oIcon=null;this.freeze=false;this.x=0;this.y=0;this.time=700;if(!e)e="";this.Create(e,t,n)};BXHint.prototype.Create=function(e,t,n){var i=this,o=0,s=0,r=null,l="icon";this.bWidth=true;if(n){if(n.width===false)this.bWidth=false;else if(n.width)o=n.width;if(n.height)s=n.height;if(n.className)r=n.className;if(n.type&&(n.type=="link"||n.type=="icon"))l=n.type;if(n.time>0)this.time=n.time}if(t)l="element";if(l=="icon"){var t=document.createElement("IMG");t.src=n&&n.iconSrc?n.iconSrc:"/bitrix/themes/"+phpVars.ADMIN_THEME_ID+"/public/popup/hint.gif";t.ondrag=jsUtils.False}else if(l=="link"){var t=document.createElement("A");t.href="";t.onclick=function(e){return false};t.innerHTML="[?]"}this.element=t;if(l=="element"){if(n&&n.show_on_click){jsUtils.addEvent(t,"click",function(t){if(!t)t=window.event;i.GetMouseXY(t);i.timeOutID=setTimeout(function(){i.Show(e,o,s,r)},10)})}else{jsUtils.addEvent(t,"mouseover",function(t){if(!t)t=window.event;i.GetMouseXY(t);i.timeOutID=setTimeout(function(){i.Show(e,o,s,r)},750)})}jsUtils.addEvent(t,"mouseout",function(e){if(i.timeOutID)clearTimeout(i.timeOutID);i.SmartHide(i)})}else{this.oIcon=t;t.onmouseover=function(t){if(!t)t=window.event;i.GetMouseXY(t);i.Show(e,o,s,r)};t.onmouseout=function(){i.SmartHide(i)}}};BXHint.prototype.IsFrozen=function(){return this.freeze};BXHint.prototype.Freeze=function(){this.freeze=true;this.Hide()};BXHint.prototype.UnFreeze=function(){this.freeze=false};BXHint.prototype.GetMouseXY=function(e){if(e.pageX||e.pageY){this.x=e.pageX;this.y=e.pageY}else if(e.clientX||e.clientY){this.x=e.clientX+(document.documentElement.scrollLeft||document.body.scrollLeft)-document.documentElement.clientLeft;this.y=e.clientY+(document.documentElement.scrollTop||document.body.scrollTop)-document.documentElement.clientTop}};BXHint.prototype.Show=function(e,t,n,i){var o=document.getElementById("__BXHint_div");if(o)this.Hide();if(this.freeze)return;var s=this;var r=document.body.appendChild(document.createElement("DIV"));r.onmouseover=function(){s.oDivOver=true};r.onmouseout=function(){s.oDivOver=false;s.SmartHide(s)};r.id="__BXHint_div";r.className=i?i:"bxhint";r.style.position="absolute";if(t&&this.bWidth)r.style.width=t+"px";if(n)r.style.height=n+"px";r.innerHTML=e;var l=r.offsetWidth;var a=r.offsetHeight;if(this.bWidth){if(!t&&l>200)l=Math.round(Math.sqrt(1.618*l*a));r.style.width=l+"px";a=r.offsetHeight}var d={left:this.x+10,right:this.x+l,top:this.y,bottom:this.y+a};d=this.AlignToPos(d,l,a);r.style.zIndex=2100;jsFloatDiv.Show(r,d.left,d.top,3);r=null};BXHint.prototype.AlignToPos=function(e,t,n){var i=document.body;if(i.clientWidth+i.scrollLeft<e.left+t)e.left=e.left-t>=0?e.left-t:i.scrollLeft;if(i.clientHeight+i.scrollTop-e["bottom"]<0)e.top=e.top-n>=0?e.top-n:i.scrollTop;return e};BXHint.prototype.Hide=function(){var e=document.getElementById("__BXHint_div");if(!e)return;jsFloatDiv.Close(e);e.parentNode.removeChild(e);e=null};BXHint.prototype.SmartHide=function(e){setTimeout(function(){if(!e.oDivOver)e.Hide()},100)};function WaitOnKeyPress(e){if(!e)e=window.event;if(!e)return;if(e.keyCode==27)CloseWaitWindow()}function ShowWaitWindow(){CloseWaitWindow();var e=jsUtils.GetWindowSize();var t=document.body.appendChild(document.createElement("DIV"));t.id="wait_window_div";t.innerHTML=phpVars.messLoading;t.className="waitwindow";t.style.right=5-e.scrollLeft+"px";t.style.top=e.scrollTop+5+"px";if(jsUtils.IsIE()){var n=document.createElement("IFRAME");n.src="javascript:''";n.id="wait_window_frame";n.className="waitwindow";n.style.width=t.offsetWidth+"px";n.style.height=t.offsetHeight+"px";n.style.right=t.style.right;n.style.top=t.style.top;document.body.appendChild(n)}jsUtils.addEvent(document,"keypress",WaitOnKeyPress)}function CloseWaitWindow(){jsUtils.removeEvent(document,"keypress",WaitOnKeyPress);var e=document.getElementById("wait_window_frame");if(e)e.parentNode.removeChild(e);var t=document.getElementById("wait_window_div");if(t)t.parentNode.removeChild(t)}var jsSelectUtils={addNewOption:function(e,t,n,i,o){var s=typeof e=="string"||e instanceof String?document.getElementById(e):e;if(s){var r=s.length;if(o!==false){for(var l=0;l<r;l++)if(s[l].value==t)return}var a=new Option(n,t,false,false);s.options[r]=a}if(i===true)this.sortSelect(e)},deleteOption:function(e,t){var n=typeof e=="string"||e instanceof String?document.getElementById(e):e;if(n){for(var i=0;i<n.length;i++)if(n[i].value==t){n.remove(i);break}}},deleteSelectedOptions:function(e){var t=typeof e=="string"||e instanceof String?document.getElementById(e):e;if(t){var n=0;while(n<t.length)if(t[n].selected){t[n].selected=false;t.remove(n)}else n++}},deleteAllOptions:function(e){if(e){for(var t=e.length-1;t>=0;t--)e.remove(t)}},optionCompare:function(e,t){var n=e.optText.toLowerCase();var i=t.optText.toLowerCase();if(n>i)return 1;if(n<i)return-1;return 0},sortSelect:function(e){var t=typeof e=="string"||e instanceof String?document.getElementById(e):e;if(t){var n=[];var i=t.options.length;for(var o=0;o<i;o++){n[o]={optText:t[o].text,optValue:t[o].value}}n.sort(this.optionCompare);t.length=0;i=n.length;for(var o=0;o<i;o++){var s=new Option(n[o].optText,n[o].optValue,false,false);t[o]=s}}},selectAllOptions:function(e){var t=typeof e=="string"||e instanceof String?document.getElementById(e):e;if(t){var n=t.length;for(var i=0;i<n;i++)t[i].selected=true}},selectOption:function(e,t){var n=typeof e=="string"||e instanceof String?document.getElementById(e):e;if(n){var i=n.length;for(var o=0;o<i;o++)n[o].selected=n[o].value==t}},addSelectedOptions:function(e,t,n,i){if(!e)return;var o=e.length;for(var s=0;s<o;s++)if(e[s].selected)this.addNewOption(t,e[s].value,e[s].text,i,n)},moveOptionsUp:function(e){if(!e)return;var t=e.length;for(var n=0;n<t;n++){if(e[n].selected&&n>0&&e[n-1].selected==false){var i=new Option(e[n].text,e[n].value);var o=new Option(e[n-1].text,e[n-1].value);e[n]=o;e[n].selected=false;e[n-1]=i;e[n-1].selected=true}}},moveOptionsDown:function(e){if(!e)return;var t=e.length;for(var n=t-1;n>=0;n--){if(e[n].selected&&n<t-1&&e[n+1].selected==false){var i=new Option(e[n].text,e[n].value);var o=new Option(e[n+1].text,e[n+1].value);e[n]=o;e[n].selected=false;e[n+1]=i;e[n+1].selected=true}}}};
/* End */
;
; /* Start:"a:4:{s:4:"full";s:50:"/bitrix/js/main/core/core_fx.min.js?15874897379768";s:6:"source";s:31:"/bitrix/js/main/core/core_fx.js";s:3:"min";s:35:"/bitrix/js/main/core/core_fx.min.js";s:3:"map";s:35:"/bitrix/js/main/core/core_fx.map.js";}"*/
(function(t){var i={time:1,step:.05,type:"linear",allowFloat:false};BX.fx=function(t){this.options=t;if(null!=this.options.time)this.options.originalTime=this.options.time;if(null!=this.options.step)this.options.originalStep=this.options.step;if(!this.__checkOptions())return false;this.__go=BX.delegate(this.go,this);this.PARAMS={}};BX.fx.prototype.__checkOptions=function(){if(typeof this.options.start!=typeof this.options.finish)return false;if(null==this.options.time)this.options.time=i.time;if(null==this.options.step)this.options.step=i.step;if(null==this.options.type)this.options.type=i.type;if(null==this.options.allowFloat)this.options.allowFloat=i.allowFloat;this.options.time*=1e3;this.options.step*=1e3;if(typeof this.options.start!="object"){this.options.start={_param:this.options.start};this.options.finish={_param:this.options.finish}}var e;for(e in this.options.start){if(null==this.options.finish[e]){this.options.start[e]=null;delete this.options.start[e]}}if(!BX.type.isFunction(this.options.type)){if(BX.type.isFunction(t[this.options.type]))this.options.type=t[this.options.type];else if(BX.type.isFunction(BX.fx.RULES[this.options.type]))this.options.type=BX.fx.RULES[this.options.type];else this.options.type=BX.fx.RULES[i.type]}return true};BX.fx.prototype.go=function(){var t=(new Date).valueOf();if(t<this.PARAMS.timeFinish){for(var i in this.PARAMS.current){this.PARAMS.current[i][0]=this.options.type.apply(this,[{start_value:this.PARAMS.start[i][0],finish_value:this.PARAMS.finish[i][0],current_value:this.PARAMS.current[i][0],current_time:t-this.PARAMS.timeStart,total_time:this.options.time}])}this._callback(this.options.callback);if(!this.paused)this.PARAMS.timer=setTimeout(this.__go,this.options.step)}else{this.stop()}};BX.fx.prototype._callback=function(t){var i={};t=t||this.options.callback;for(var e in this.PARAMS.current){i[e]=(this.options.allowFloat?this.PARAMS.current[e][0]:Math.round(this.PARAMS.current[e][0]))+this.PARAMS.current[e][1]}return t.apply(this,[null!=i["_param"]?i._param:i])};BX.fx.prototype.start=function(){var t,i,e;this.PARAMS.start={};this.PARAMS.current={};this.PARAMS.finish={};for(t in this.options.start){i=+this.options.start[t];e=(this.options.start[t]+"").substring((i+"").length);this.PARAMS.start[t]=[i,e];this.PARAMS.current[t]=[i,e];this.PARAMS.finish[t]=[+this.options.finish[t],e]}this._callback(this.options.callback_start);this._callback(this.options.callback);this.PARAMS.timeStart=(new Date).valueOf();this.PARAMS.timeFinish=this.PARAMS.timeStart+this.options.time;this.PARAMS.timer=setTimeout(BX.delegate(this.go,this),this.options.step);return this};BX.fx.prototype.pause=function(){if(this.paused){this.PARAMS.timer=setTimeout(this.__go,this.options.step);this.paused=false}else{clearTimeout(this.PARAMS.timer);this.paused=true}};BX.fx.prototype.stop=function(t){t=!!t;if(this.PARAMS.timer)clearTimeout(this.PARAMS.timer);if(null!=this.options.originalTime)this.options.time=this.options.originalTime;if(null!=this.options.originalStep)this.options.step=this.options.originalStep;this.PARAMS.current=this.PARAMS.finish;if(!t){this._callback(this.options.callback);this._callback(this.options.callback_complete)}};BX.fx.RULES={linear:function(t){return t.start_value+t.current_time/t.total_time*(t.finish_value-t.start_value)},decelerated:function(t){return t.start_value+Math.sqrt(t.current_time/t.total_time)*(t.finish_value-t.start_value)},accelerated:function(t){var i=t.current_time/t.total_time;return t.start_value+i*i*(t.finish_value-t.start_value)}};BX.fx.hide=function(t,i,e){t=BX(t);if(typeof i=="object"&&null==e){e=i;i=e.type}if(!e)e={};if(!BX.type.isNotEmptyString(i)){t.style.display="none";return}var s=BX.fx.EFFECTS[i](t,e,0);s.callback_complete=function(){if(e.hide!==false)t.style.display="none";if(e.callback_complete)e.callback_complete.apply(this,arguments)};return new BX.fx(s).start()};BX.fx.show=function(t,i,e){t=BX(t);if(typeof i=="object"&&null==e){e=i;i=e.type}if(!e)e={};if(!BX.type.isNotEmptyString(i)){t.style.display="block";return}var s=BX.fx.EFFECTS[i](t,e,1);s.callback_complete=function(){if(e.show!==false)t.style.display="block";if(e.callback_complete)e.callback_complete.apply(this,arguments)};return new BX.fx(s).start()};BX.fx.EFFECTS={scroll:function(t,e,s){if(!e.direction)e.direction="vertical";var n=e.direction=="horizontal"?"width":"height";var o=parseInt(BX.style(t,n));if(isNaN(o)){o=BX.pos(t)[n]}if(s==0)var a=o,r=e.min_height?parseInt(e.min_height):0;else var r=o,a=e.min_height?parseInt(e.min_height):0;return{start:a,finish:r,time:e.time||i.time,type:"linear",callback_start:function(){if(BX.style(t,"position")=="static")t.style.position="relative";t.style.overflow="hidden";t.style[n]=a+"px";t.style.display="block"},callback:function(i){t.style[n]=i+"px"}}},fade:function(t,e,s){var n={time:e.time||i.time,type:s==0?"decelerated":"linear",start:s==0?1:0,finish:s==0?0:1,allowFloat:true};if(BX.browser.IsIE()&&!BX.browser.IsIE9()){n.start*=100;n.finish*=100;n.allowFloat=false;n.callback_start=function(){t.style.display="block";t.style.filter+="progid:DXImageTransform.Microsoft.Alpha(opacity="+n.start+")"};n.callback=function(i){(t.filters["DXImageTransform.Microsoft.alpha"]||t.filters.alpha).opacity=i}}else{n.callback_start=function(){t.style.display="block"};n.callback=function(i){t.style.opacity=t.style.KhtmlOpacity=t.style.MozOpacity=i}}return n},fold:function(t,e,s){if(s!=0)return;var n=BX.pos(t);var o=n.height/(n.width+n.height);var a={time:e.time||i.time,callback_complete:e.callback_complete,hide:e.hide};e.type="scroll";e.direction="vertical";e.min_height=e.min_height||10;e.hide=false;e.time=o*a.time;e.callback_complete=function(){t.style.whiteSpace="nowrap";e.direction="horizontal";e.min_height=null;e.time=a.time-e.time;e.hide=a.hide;e.callback_complete=a.callback_complete;BX.fx.hide(t,e)};return BX.fx.EFFECTS.scroll(t,e,s)},scale:function(t,e,s){var n={width:parseInt(BX.style(t,"width")),height:parseInt(BX.style(t,"height"))};if(isNaN(n.width)||isNaN(n.height)){var o=BX.pos(t);n={width:o.width,height:o.height}}if(s==0)var a=n,r={width:0,height:0};else var r=n,a={width:0,height:0};return{start:a,finish:r,time:e.time||i.time,type:"linear",callback_start:function(){t.style.position="relative";t.style.overflow="hidden";t.style.display="block";t.style.height=a.height+"px";t.style.width=a.width+"px"},callback:function(i){t.style.height=i.height+"px";t.style.width=i.width+"px"}}}};var e={arStack:{},arRules:{},globalAnimationId:0};BX.fx.colorAnimate=function(t,i,s){if(t==null)return;animationId=t.getAttribute("data-animation-id");if(animationId==null){animationId=e.globalAnimationId;t.setAttribute("data-animation-id",e.globalAnimationId++)}var n=i.split(/\s*,\s*/);for(var o=0;o<n.length;o++){i=n[o];if(!e.arRules[i])continue;var a=0;if(!e.arStack[animationId]){e.arStack[animationId]={}}else if(e.arStack[animationId][i]){a=e.arStack[animationId][i].i;clearInterval(e.arStack[animationId][i].tId)}if(a==0&&s||a==e.arRules[i][3]&&!s)continue;e.arStack[animationId][i]={i:a,element:t,tId:setInterval('BX.fx.colorAnimate.run("'+animationId+'","'+i+'")',e.arRules[i][4]),back:Boolean(s)}}};BX.fx.colorAnimate.addRule=function(t,i,s,n,o,a,r){e.arRules[t]=[BX.util.hex2rgb(i),BX.util.hex2rgb(s),n.replace(/\-(.)/g,function(){return arguments[1].toUpperCase()}),o,a||1,r||false]};BX.fx.colorAnimate.run=function(t,i){element=e.arStack[t][i].element;e.arStack[t][i].i+=e.arStack[t][i].back?-1:1;var s=e.arStack[t][i].i/e.arRules[i][3];var n=1-s;var o=e.arRules[i][0];var a=e.arRules[i][1];element.style[e.arRules[i][2]]="rgb("+Math.floor(o["r"]*n+a["r"]*s)+","+Math.floor(o["g"]*n+a["g"]*s)+","+Math.floor(o["b"]*n+a["b"]*s)+")";if(e.arStack[t][i].i==e.arRules[i][3]||e.arStack[t][i].i==0){clearInterval(e.arStack[t][i].tId);if(e.arRules[i][5])BX.fx.colorAnimate(e.arStack[t][i].element,i,true)}};BX.easing=function(t){this.options=t;this.timer=null};BX.easing.prototype.animate=function(){if(!this.options||!this.options.start||!this.options.finish||typeof this.options.start!="object"||typeof this.options.finish!="object")return null;for(var t in this.options.start){if(typeof this.options.finish[t]=="undefined"){delete this.options.start[t]}}this.options.progress=function(t){var i={};for(var e in this.start)i[e]=Math.round(this.start[e]+(this.finish[e]-this.start[e])*t);if(this.step){this.step(i)}};this.animateProgress()};BX.easing.prototype.stop=function(t){if(this.timer){cancelAnimationFrame(this.timer);this.timer=null;if(t){this.options.complete&&this.options.complete()}}};BX.easing.prototype.animateProgress=function(){if(!t.requestAnimationFrame){this.options.progress(1);this.options.complete&&this.options.complete();return}var i=null;var e=this.options.transition||BX.easing.transitions.linear;var s=this.options.duration||1e3;var n=BX.proxy(function(t){if(i===null){i=t}var o=(t-i)/s;if(o>1){o=1}this.options.progress(e(o));if(o==1){this.stop(true)}else{this.timer=requestAnimationFrame(n)}},this);this.timer=requestAnimationFrame(n)};BX.easing.makeEaseInOut=function(t){return function(i){if(i<.5)return t(2*i)/2;else return(2-t(2*(1-i)))/2}};BX.easing.makeEaseOut=function(t){return function(i){return 1-t(1-i)}};BX.easing.transitions={linear:function(t){return t},quad:function(t){return Math.pow(t,2)},cubic:function(t){return Math.pow(t,3)},quart:function(t){return Math.pow(t,4)},quint:function(t){return Math.pow(t,5)},circ:function(t){return 1-Math.sin(Math.acos(t))},back:function(t){return Math.pow(t,2)*((1.5+1)*t-1.5)},elastic:function(t){return Math.pow(2,10*(t-1))*Math.cos(20*Math.PI*1.5/3*t)},bounce:function(t){for(var i=0,e=1;1;i+=e,e/=2){if(t>=(7-4*i)/11){return-Math.pow((11-6*i-11*t)/4,2)+Math.pow(e,2)}}}}})(window);
/* End */
;
//# sourceMappingURL=kernel_main.map.js