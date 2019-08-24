import Vue from 'vue';
export let message = function message(options)
{
	if(typeof options != 'string' && typeof options != 'object')
		return false;

	if(typeof options == 'string' || typeof options.type == 'undefined')
		message.notif(options);

	if(typeof message[options.type] != 'function')
		return false;

	message[options.type](options);
};

message.defaultParams = {closeTimeout: 1000 };
message.params = {closeTimeout: 1000 };
message.messageParams = {type: 'notif', text: 'notification'};
message.template = `
	<div class="element-message element-message-#type# #messageClass#">
		<span class="element-message-text">#text#</span>
		<svg class="element-message-close" width="16" height="16"><use xlink:href="#plus-white"></use></svg>
	</div>
`;

message.prepareParams = function(params)
{
	if(typeof params != 'string' && (typeof params != 'object' || typeof params.text == 'undefined'))
		return false;

	message.messageParams = {
		type: (params.type) ? params.type : message.messageParams.type,
		text: (params.text) ? params.text : params
	};

	message.params = JSON.parse(JSON.stringify(message.defaultParams));

	if(typeof params.closeTimeout == 'number')
		message.params.closeTimeout = params.closeTimeout;

	return true;
};

message.notif = function(options)
{
	message.messageParams.type = 'notif';

	if(!message.prepareParams(options))
		return false;

	message.render();
}

message.error = function(options)
{
	message.messageParams.type = 'error';

	if(!message.prepareParams(options))
		return false;

	message.render();
}

message.close = function(messageElement)
{
	if(messageElement.parentNode == null)
		return;

	document.body.removeChild(messageElement)
};

message.render = function()
{
	var html = this.template.trim();

	html = message.replace(html, message.messageParams);

	if(html == false)
		return false;

	var messageElement = document.createElement('div');

	messageElement.innerHTML = html;
	messageElement = messageElement.firstChild;

	messageElement.querySelector('.element-message-close').addEventListener('click', function()
	{
		message.close(messageElement);
	});

	document.body.appendChild(messageElement);

	setTimeout(function()
	{
		message.close(messageElement);
	}, message.params.closeTimeout);
};

message.replace = function(string, params)
{
	if(typeof params !== 'object')
		return false;

	for(var index in params)
	{
		var regexp = new RegExp(`#${index}#`, 'g');
		string = string.replace(regexp, params[index]);
	}

	string = string.replace(/#.*#/g, '');

	return string;
}

Vue.prototype.ElMessage = message;