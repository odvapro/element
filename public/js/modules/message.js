/* работа с сообщениями */
el.message = 
{
	error : function(msg)
	{
		$('.alert .msg').html(msg);
		$('.alert').removeClass('show success').addClass('show error');
		this.closeTimeOut();
	},

	success : function(msg)
	{
		$('.alert .msg').html(msg);
		$('.alert').removeClass('show error').addClass('show success');
		this.closeTimeOut();
	},

	close : function()
	{
		$('.alert').removeClass('show');
	},

	closeTimeOut : function()
	{
		setTimeout(function(){
			el.message.close();
		},5000);
	}
}