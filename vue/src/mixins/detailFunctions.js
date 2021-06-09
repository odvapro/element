import qs from 'qs';
export default
{
	methods:
	{
		async saveElement(data)
		{
			let result = await this.$store.dispatch('saveSelectedElement', data);
			return result;
		},
		async createElement(data)
		{
			let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(data.tableCode);
			let setValues  = {};
			for(let fieldCode in data.selectedElement)
			{
				if(primaryKeyCode == fieldCode) continue;
				setValues[fieldCode] = data.selectedElement[fieldCode];
			}

			let insertData = qs.stringify({
				insert:
				{
					table   :data.tableCode,
					values  :[setValues]
				}
			});
			let result = await this.$axios.post('/el/insert/',insertData);
			return result;
		},
		async removeElement(data)
		{
			let primaryKeyCode = this.$store.getters.getPrimaryKeyCode(data.tableCode);
			let result = await this.$store.dispatch('removeRecord', {
				delete:
				{
					table: data.tableCode,
					where:
					{
						operation:'and',
						fields:[
							{
								code      : primaryKeyCode,
								operation : 'IS',
								value     : data.selectedElement[primaryKeyCode],
							}
						]
					}
				}
			});
			return result;
		}
	}
}
