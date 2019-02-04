export default
{
	methods:
	{
		/**
		 * Открыть попап и добавить координаты отображения
		 */
		showPopup(element, popupName, positionType, slide)
		{
			var coords = '',
				position = {};

			if(typeof positionType == 'undefined')
				positionType = 'left-bottom';

			if(typeof slide == 'undefined')
				slide = 'down';

			switch(positionType)
			{
				case 'left-top':
					coords = element.getBoundingClientRect();
					position.top = coords.top - 1;
					position.left = coords.left - 1;
					break;

				case 'center-bottom':
				default:
					coords = element.getBoundingClientRect();
					position.left = coords.left;
					position.top = coords.top - 1;
					position.top = coords.top + coords.height + 4;
					break;
			}

			position.bottom = coords.bottom;
			position.height = coords.height;
			position.width  = coords.width;
			position.right  = coords.right;
			position.positionType = positionType;

			this.$store.commit('openPopup', {name: popupName, coords: position});
		}
	}
}