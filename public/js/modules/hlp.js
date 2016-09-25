/*HELPERS*/
el.hlp = 
{
	/*преобразует JSON в строку*/
	jsonStringifyWidthEscaping : function(inJson)
	{
		inJson = JSON.stringify(inJson);
		inJson = inJson.replace(/\\n/g, "\\n")
			.replace(/\\'/g, "\\'")
			.replace(/\\"/g, '\\"')
			.replace(/\\&/g, "\\&")
			.replace(/\\r/g, "\\r")
			.replace(/\\t/g, "\\t")
			.replace(/\\b/g, "\\b")
			.replace(/"/g, '&quot;')
			.replace(/\\f/g, "\\f");
		return inJson;
	},
	'cookies' : 
	{
		'_init' : function()
		{
			this._cookies = {};
			var ca = document.cookie.split(';');
			var re = /^[\s]*([^\s]+?)$/i;
			for (var i = 0, l = ca.length; i < l; i++)
			{
				var c = ca[i].split('=');
				if (c.length == 2)
				{
					this._cookies[c[0].match(re)[1]] = unescape(c[1].match(re) ? c[1].match(re)[1] : '');
				}
			}
		},
		'get' : function(name)
		{
			this._init();
			return this._cookies[name];
		},
		'set' :  function(name, value, days, secure)
		{
			var expires = '';
			if(days)
			{
				var date = new Date();
				date.setTime(date.getTime()+(days*24*60*60*1000));
				expires = '; expires='+date.toGMTString();
			}
			// var domain = el.config.baseUri;
			var domain = false;
			document.cookie = name + '='+escape(value) + expires + '; path=/' + (domain ? '; domain=.' + domain : '') + ((secure && H.locProtocol == 'https:') ? '; secure' : '');
		}
	},
	/* подключение скрипта один раз */
	requiredfiles:[],
	requireonce:function(url)
	{
		if(this.requiredfiles.indexOf(url) === -1)
		{
			$.getScript(url);
			this.requiredfiles.push(url);
		}
	}
}